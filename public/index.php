<?php
// Nạp cấu hình
require_once '../config/config.php';
require_once '../config/database.php';

// Tải Controller và Model
require_once '../app/controllers/LoginController.php';
require_once '../app/controllers/HomeController.php';
require_once '../app/controllers/RegisterController.php'; // Nếu có trang đăng ký
require_once '../app/controllers/FileController.php';
require_once '../app/controllers/SettingsController.php';
require_once '../app/controllers/ProfileController.php';
require_once '../app/controllers/AccountController.php';
require_once '../app/controllers/ChatController.php';



require_once '../app/models/User.php';

// Xác định trang dựa trên tham số `page`
$page = $_GET['page'] ?? 'home';
// Xử lý logic dựa trên trang
switch ($page) {
    case 'dashboard':
        $controller = new FileController();
        $controller->dashboard(); // Hiển thị giao diện upload file
        break;

    case 'profile':
        $controller = new ProfileController();
        $controller->viewProfile(); // Hiển thị thông tin user
        break;

    case 'settings':
        $controller = new SettingsController();
        $controller->changePassword(); // Hiển thị giao diện đổi mật khẩu
        break;

    case 'register':
        $controller = new RegisterController();
        $controller->index();
        break;

    case 'register_submit':
        $controller = new RegisterController();
        $controller->submit();
        break;

    case 'login':
        $controller = new LoginController();
        $controller->index();
        break;

    case 'login_submit':
        $controller = new LoginController();
        $controller->submit();
        break;

    case 'logout':
        session_start();
        session_destroy(); // Hủy session
        header('Location: ' . BASE_URL . '?page=login'); // Chuyển về trang đăng nhập
        exit;
    
    case 'file_upload':
        $controller = new FileController();
        $controller->upload();
        break;

    case 'file_delete':
        $controller = new FileController();
        $controller->delete();
        break;

    case 'public_files':
        $fileController = new FileController();
        $fileController->publicFiles(); // Thêm trường hợp này
        break;
    
    case 'accounts':
        $accountController = new AccountController();
        $accountController->index();
        break;

    case 'toggle_active':
        $accountController = new AccountController();
        $accountController->toggleActiveStatus();
        break;
    
    case 'chats':
        $chatController = new ChatController();
        $roomId = $_GET['room_id'] ?? null;
        if ($roomId !== null) {
            $chatController->joinRoom($roomId);
        } else {
            $chatController->index();
        }
        break;

    case 'create_room':
        $chatController = new ChatController();
        $chatController->createRoom(); // Tạo phòng chat mới
        break;
    case 'send_message':
        $chatController = new ChatController();
        $chatController->sendMessage();
        break;
    case 'home':
    
    default:
        $controller = new HomeController();
        $controller->index();
        break;
}
