<?php
class HomeController
{
    public function index()
    {
        session_start();
        $accountController = new AccountController();
        $accountController->checkUserActiveStatus();
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '?page=login');
            exit;
        }

        $user = $_SESSION['user'];
        $userModel = new User();
        $files = $userModel->getFilesByUserId($user['id']); // Lấy danh sách file

        require_once '../app/views/home/index.php';
    }


}
?>
