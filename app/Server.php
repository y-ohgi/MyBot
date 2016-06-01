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

    public function onMessage(ConnectionInterface $from, $message)
    {
        $str = explode(" ", $message);
        if($str[0] !== "bot"){
            foreach ($this->clients as $client) {
                $from->send(json_encode(['data' => $message]));
            }
            return;
        }

        $from->send(json_encode(['data' => $message]));


        try{
            // そのまま突っ込むのは セキュリティ的にやばそう?
            $cl = 'Sprint\\'. ucfirst($str[1]) . 'Command';
            $command = new $cl();
            $command->excute($message);
            $result = $command->getResult();
        
            $from->send(json_encode(['data' => $result]));
        }catch(Exception $e){
            $from->send(json_encode(['data' => 'error']));
        }
    }
    
}

$docroot = __DIR__ . '/../public';
$deamon = popen("php -S 0.0.0.0:9000 --docroot {$docroot}", "r");

$base = new HttpServer(new WsServer(new Chat));
$server = IoServer::factory($base, 3000);
$server->run();
