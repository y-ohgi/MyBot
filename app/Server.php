<?php
namespace Sprint;
require 'autoload.php';


# Servers
use \Ratchet\Http\HttpServer;
use \Ratchet\Server\IoServer;
use \Ratchet\WebSocket\WsServer;
# Components
use \Ratchet\ConnectionInterface;
use \Ratchet\MessageComponentInterface;

use \PDO;


class Chat implements MessageComponentInterface
{
    private $clients;
    private $todos = array();

    public function __construct()
    {
        // initialize clients
        $this->clients = new \SplObjectStorage;
        
        /* $sql = "INSERT INTO todos(`title`, `body`) VALUES(:title, :body)"; */
        /* $stmt = Dbh::get()->prepare($sql); */
        /* $stmt->bindValue(':title', "title", PDO::PARAM_STR); */
        /* $stmt->bindValue(':body', "the description", PDO::PARAM_STR); */
        /* $stmt->execute(); */
        $todocom = new TodoCommand();
        $todocom->excute();

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

    public function onMessage(ConnectionInterface $from, $message)
    {
        // bot へ対する命令かの判定
        $str = explode(" ", $message);
        if($str[0] !== "bot"){
            // bot へ対する "命令ではなかった" 場合
            //  => そのままブロードキャスト配信
            foreach ($this->clients as $client) {
                $from->send(json_encode(['data' => $message]));
            }
            return;
        }

        // XXX: とりあえずベタ書き、その後まともに実装
        $from->send(json_encode(['data' => $message]));

        
        if($str[1] === "ping"){
            $from->send(json_encode(['data' => "pong"]));
        }else if($str[1] === "todo"){
            if($str[2] === "add"){
                if(!isset($str[3])){
                    return;
                }
                $tmp = array(
                    "title" => $str[3]
                );
                if(isset($str[4])){
                    $tmp["desc"] = $str[4];
                }
                
                array_push($this->todos, $tmp);
                
                $from->send(json_encode(['data' => "todo added"]));
            }else if($str[2] === "delete"){
                if(!$str[3]){
                    return;
                }

                $delflg = false;
                $todos = $this->todos;
                for($i=0; $i< count($this->todos); $i++){
                    if($this->todos[$i]["title"] == $str[3]){
                        unset($todos[$i]);
                        $delflg = true;
                    }
                }
                if($delflg){
                    $this->todos = array_values($todos);

                    $from->send(json_encode(['data' => "todo deleted"]));
                }
                return;
                
            }else if($str[2] === "list"){
                $tmp = "";

                if(empty($this->todos) || count($this->todos) === 0){
                    $from->send(json_encode(['data' => "todo empty"]));
                    return;
                }
                for($i=0; $i< count($this->todos); $i++){
                    $tmp .= $this->todos[$i]["title"];
                    if(isset($this->todos[$i]["desc"])){
                        $tmp .= " ". $this->todos[$i]["desc"];
                    }
                    if($i !== count($this->todos) -1){
                        $tmp .= "\n";
                    }
                }
                
                $from->send(json_encode(['data' => $tmp]));
            }
        }

        // bot へ対する "命令であった" 場合
        //  => 命令のメソッドを取得しに行く
        /***
            > bot $命令 $引数0 $引数1
            // ファクトリ？or アブストラクト
            $command = new Command($cmd);
            $command = new 
            // method_exists ( mixed $コマンドクラス , string $命令 );
            
            
            
            
         ***/
        
    }
}

/**
 * Do NOT remove this code.
 * This code is needed for `codecheck` command to see whether server is running or not
 */
$docroot = __DIR__ . '/../public';
$deamon = popen("php -S 0.0.0.0:9000 --docroot {$docroot}", "r");

$base = new HttpServer(new WsServer(new Chat));
$server = IoServer::factory($base, 3000);
$server->run();
