<?php
require_once '../app/models/User.php';

class LoginController
{
    public function index()
    {
        require_once '../app/views/auth/login.php';
    }

    public function submit()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            $userModel = new User();
            $user = $userModel->getUserByUsername($username);

            if ($user) {
                // Kiểm tra nếu tài khoản bị vô hiệu hóa
                if ((int)$user['active'] === 0) {
                    $error = "Tài khoản của bạn đã bị vô hiệu hóa.";
                    require_once '../app/views/auth/login.php';
                    return;
                }
    
                // Kiểm tra mật khẩu
                if (password_verify($password, $user['password'])) {
                    session_start();
                    $_SESSION['user'] = $user;
                    header('Location: ' . BASE_URL . '?page=home');
                    exit;
                }
            }
    
            // Nếu không tìm thấy người dùng hoặc mật khẩu không đúng
            $error = "Invalid username or password.";

            require_once '../app/views/auth/login.php';
        }
    }
}
?>
