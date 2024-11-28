<?php
require_once '../app/models/User.php';

class RegisterController
{
    public function index()
    {
        // Hiển thị giao diện đăng ký
        require_once '../app/views/auth/register.php';
    }

    public function submit()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            // Kiểm tra tên đăng nhập chỉ chứa ký tự hợp lệ
            if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
                $error = "Username can only contain letters, numbers, and underscores.";
                require_once '../app/views/auth/register.php';
                return;
            }

            // Kiểm tra mật khẩu có khớp
            if ($password !== $confirmPassword) {
                $error = "Passwords do not match!";
                require_once '../app/views/auth/register.php';
                return;
            }

            // Mã hóa mật khẩu
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // Tạo người dùng mới
            $userModel = new User();
            try {
                $userModel->createUser($username, $email, $hashedPassword);
                header('Location: ' . BASE_URL . '?page=login');
                exit;
            } catch (Exception $e) {
                $error = $e->getMessage();
                require_once '../app/views/auth/register.php';
            }
        }
    }
}
?>
