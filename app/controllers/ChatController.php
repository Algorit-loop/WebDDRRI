<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Chat.php';
require_once __DIR__ . '/../../config/database.php';

class ChatController
{
    private $chatModel;

    public function __construct()
    {
        $this->chatModel = new Chat();
        $this->userModel = new User();
    }

    public function index()
    {
        session_start();
        $accountController = new AccountController();
        $accountController->checkUserActiveStatus();
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '?page=login');
            exit;
        }

        // Lấy danh sách các phòng chat
        $rooms = $this->chatModel->getAllRooms();

        // Truyền dữ liệu tới View
        require_once '../app/views/chats/index.php';
    }

    public function room($roomId)
    {
        session_start();
        $accountController = new AccountController();
        $accountController->checkUserActiveStatus();
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '?page=login');
            exit;
        }

        // Lấy thông tin phòng và tin nhắn trong phòng
        $room = $this->chatModel->getRoomById($roomId);
        $messages = $this->chatModel->getMessagesByRoomId($roomId);

        // Truyền dữ liệu tới View
        require_once '../app/views/chats/room.php';
    }
    
    public function joinRoom($roomId)
    {
        session_start();
        $accountController = new AccountController();
        $accountController->checkUserActiveStatus();
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '?page=login');
            exit;
        }

        if (!$roomId || !is_numeric($roomId)) {
            $_SESSION['message'] = "Invalid room ID!";
            header('Location: ' . BASE_URL . '?page=chats');
            exit;
        }

        // Lấy thông tin phòng chat
        $room = $this->chatModel->getRoomById($roomId);
        if (!$room) {
            $_SESSION['message'] = "Room not found!";
            header('Location: ' . BASE_URL . '?page=chats');
            exit;
        }

        // Lấy danh sách tin nhắn trong phòng
        $messages = $this->chatModel->getMessagesByRoomId($roomId);

        // Truyền dữ liệu tới View
        require_once '../app/views/chats/room.php';
    }

    public function createRoom()
    {
        session_start();
        $accountController = new AccountController();
        $accountController->checkUserActiveStatus();
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '?page=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $roomName = $_POST['room_name'] ?? '';

            if (!empty($roomName)) {
                // Tạo phòng chat mới
                $this->chatModel->createRoom($roomName);
                $_SESSION['message'] = "Room '$roomName' created successfully!";
            } else {
                $_SESSION['message'] = "Room name cannot be empty!";
            }

            header('Location: ' . BASE_URL . '?page=chats');
            exit;
        }
    }

    public function saveMessage($roomId, $username, $messageContent)
    {
        // Lấy ID của người dùng dựa vào username
        
        $user = $this->userModel->getUserByUsername($username);

        if ($user) {
            // Lưu tin nhắn vào cơ sở dữ liệu
            $this->chatModel->addMessage($roomId, $user['id'], $messageContent);
        } else {
            echo "User not found: $username\n";
        }
    }

}
