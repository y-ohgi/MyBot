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
        
        $sql = 'TRUNCATE todos'; // test用
        $stmt = Dbh::get()->prepare($sql);
        $stmt->execute();
        /* $sql = "INSERT INTO todos(`title`, `body`) VALUES(:title, :body)"; */
        /* $stmt = Dbh::get()->prepare($sql); */
        /* $stmt->bindValue(':title', "title", PDO::PARAM_STR); */
        /* $stmt->bindValue(':body', "the description", PDO::PARAM_STR); */
        /* $stmt->execute(); */
        /* $todocom = new TodoCommand(); */
        /* $todocom->excute(); */

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

        $from->send(json_encode(['data' => $message]));


        try{
            $cl = 'Sprint\\'. ucfirst($str[1]) . 'Command'; // セキュリティ的にやばそうだし、 存在しない値を入れられたらエラー泊のでcatch
            $command = new $cl();//$cl();
            $command->excute($message);
            $result = $command->getResult();
        
            $from->send(json_encode(['data' => $result]));
        }catch(Exception $e){
            $from->send(json_encode(['data' => 'error']));
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
