<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app/controllers/ChatController.php';
require_once __DIR__ . '/config/database.php'; // Nếu cần thiết

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class ChatServer implements MessageComponentInterface
{
    
    protected $clients;
    protected $chatController;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
        $this->chatController = new ChatController(); // Khởi tạo ChatController
        echo "Chat server started...\n";
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        $this->clients[$conn] = ['room_id' => null];
        echo "New connection: ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        echo "Message received: $msg\n";

        // Parse dữ liệu JSON từ client
        $data = json_decode($msg, true);
        if ($data && isset($data['room_id'], $data['username'], $data['message_content'])) {
            // Lưu tin nhắn vào cơ sở dữ liệu qua ChatController
            $this->chatController->saveMessage($data['room_id'], $data['username'], $data['message_content']);

            // Gửi tin nhắn đến tất cả các client
            foreach ($this->clients as $client) {
                $client->send(json_encode([
                    'username' => $data['username'],
                    'message_content' => $data['message_content'],
                    'timestamp' => date('Y-m-d H:i:s') // Thời gian hiện tại
                ]));
            }
        } else {
            echo "Invalid message format\n";
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        // Khi một client ngắt kết nối
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "Error: {$e->getMessage()}\n";
        $conn->close();
    }
}

// Tạo server WebSocket
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new ChatServer()
        )
    ),
    
    8080 // Port của WebSocket server
);

echo "WebSocket server is running on ws://localhost:8080\n";
$server->run();