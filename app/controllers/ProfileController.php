<?php
class ProfileController
{
    public function viewProfile()
    {
        session_start();
        $accountController = new AccountController();
        $accountController->checkUserActiveStatus();
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '?page=login');
            exit;
        }

        $user = $_SESSION['user']; // Lấy thông tin người dùng từ session
        require_once '../app/views/profile/index.php'; // Hiển thị giao diện thông tin user
    }
}
?>
