<?php
namespace Sprint;
require 'autoload.php';
require 'definelist.php';


# Servers
use \Ratchet\Http\HttpServer;
use \Ratchet\Server\IoServer;
use \Ratchet\WebSocket\WsServer;
# Components
use \Ratchet\ConnectionInterface;
use \Ratchet\MessageComponentInterface;

use \PDO;
use \Exception;

class Chat implements MessageComponentInterface
{
    private $clients;
    private $todos = array();

    public function __construct()
    {
        // initialize clients
        $this->clients = new \SplObjectStorage;
        
        /* $sql = 'TRUNCATE todos'; // test用 */
        /* $stmt = Dbh::get()->prepare($sql); */
        /* $stmt->execute(); */
    }

    public function onOpen(ConnectionInterface $conn)
    {
        // store new connection in clients
        $this->clients->attach($conn);
    }

    public function onClose(ConnectionInterface $conn)
    {
        // remove connection from clients
        $this->clients->detach($conn);
        printf("Connection closed: %s\n", $conn->resourceId);
    }

    public function onError(ConnectionInterface $conn, Exception $error)
    {
        // display error message and close connection
        printf("Error: %s\n", $error->getMessage());
        $conn->close();
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        // XXX: token情報は headerに入れたかった。。。 instant
        $parsemsg = explode("@", $msg);
        $message = $parsemsg[0];
        $token = "";
        if(array_key_exists(1, $parsemsg)){
            $token = $parsemsg[1];
        }
        $str = explode(" ", $message);
        if($str[0] !== "bot"){
            foreach ($this->clients as $client) {
                $client->send(json_encode(['chat' => $message]));
            }
            return;
        }

        $from->send(json_encode(['data' => $message]));


        try{
            if(count($str) === 1){
                throw new Exception(400);
            }
            
            // そのまま突っ込むのは セキュリティ的にやばそう?
            $cl = 'Sprint\\'. ucfirst($str[1]) . 'Command';
            if(class_exists($cl) === false){
                throw new Exception(404);
            }
            $command = new $cl(); // コンストラクタにmessageぶち込もうかしら
            $command->setToken($token);
            $command->execute($message);
            $result = $command->getResult();
            
            echo "______________________________________\n";
            var_dump($message);
            var_dump(json_encode($result));
            echo "--------------------------------------\n";
            
            $from->send(json_encode($result));
        }catch(Exception $e){
            $errorcode = intval($e->getMessage());
            $res = array(
                "error" => $errorcode,
                "word" => ""
            );
                
            $sql = "SELECT body FROM (SELECT wm.id as id, type_id, bot_state_id, body FROM bot_word_master as wm INNER JOIN bot_state_master as sm ON wm.bot_state_id = sm.id WHERE bot_state_id = :bot_state_id) as bot_word INNER JOIN bot ON bot_word.type_id = bot.type_id LIMIT 1";
            $stmt = Dbh::get()->prepare($sql);
            $stmt->bindValue(':bot_state_id', $errorcode, PDO::PARAM_INT);
            $stmt->execute();
                
            $res['word'] = $stmt->fetchColumn();
            
            // $from->send(json_encode(['error' => $e->getMessage()]));
            $from->send(json_encode($res));

            // catche　先でブロードキャストをするよ！
            if($str[0] !== "bot"){
                foreach ($this->clients as $client) {
                    $from->send(json_encode(['data' => $message]));
                }
                return;
            }
        }
    }
    
}

date_default_timezone_set('Asia/Tokyo');

$docroot = __DIR__ . '/../public';
$deamon = popen("php -S 0.0.0.0:9000 --docroot {$docroot}", "r");

$base = new HttpServer(new WsServer(new Chat));
$server = IoServer::factory($base, 3000);
$server->run();
