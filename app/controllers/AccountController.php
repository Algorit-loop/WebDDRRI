<?php
require_once '../app/models/User.php';

class AccountController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    // Kiểm tra trạng thái kích hoạt của người dùng
    public function checkUserActiveStatus()
    {
        // Kiểm tra nếu người dùng đã đăng nhập
        if (isset($_SESSION['user'])) {
            $user = $this->userModel->getUserById($_SESSION['user']['id']);
            
            // Nếu tài khoản bị vô hiệu hóa, tự động đăng xuất
            if (!$user || $user['active'] == 0) {
                session_unset();
                session_destroy();
                header('Location: ' . BASE_URL . '?page=login&error=disabled');
                exit;
            }
        }
    }

    // Trang quản lý tài khoản
    public function index()
    {
        session_start();

        // Kiểm tra trạng thái active của người dùng
        $this->checkUserActiveStatus();

        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: ' . BASE_URL . '?page=login');
            exit;
        }

        // Lấy danh sách tài khoản từ model
        $accounts = $this->userModel->getAllUsers();

        // Truyền dữ liệu đến View
        require_once '../app/views/accounts/index.php';
    }

    // Bật hoặc tắt trạng thái active của người dùng
    public function toggleActiveStatus()
    {
        session_start();

        // Kiểm tra trạng thái active của người dùng
        $this->checkUserActiveStatus();

        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: ' . BASE_URL . '?page=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $activeStatus = $_POST['active_status'] ?? [];

            // Duyệt qua danh sách tài khoản
            $accounts = $this->userModel->getAllUsers();
            foreach ($accounts as $account) {
                $isActive = isset($activeStatus[$account['id']]) ? 1 : 0;
                $this->userModel->updateActiveStatus($account['id'], $isActive);
            }

            $_SESSION['message'] = "Account statuses updated successfully!";
            header('Location: ' . BASE_URL . '?page=accounts');
            exit;
        }
    }
}
