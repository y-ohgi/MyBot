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

class Chat implements MessageComponentInterface
{
    private $clients;

    public function __construct()
    {
        // initialize clients storage
        $this->clients = new \SplObjectStorage;
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
                $client->send(json_encode(['data' => $message]));
            }
            return;
        }

        if($str[1] === "ping"){
            $from->send(json_encode(['data' => "bot ping"]));
            $from->send(json_encode(['data' => "pong"]));
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
