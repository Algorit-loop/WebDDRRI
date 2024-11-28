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
        if (isset($_SESSION['user'])) {
            $user = $this->userModel->getUserById($_SESSION['user']['id']);

            // Nếu tài khoản bị vô hiệu hóa hoặc không tồn tại
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

        $this->checkUserActiveStatus();

        if (!isset($_SESSION['user']) ||  !in_array($_SESSION['user']['role'],['admin','assistant'])) {
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

        $this->checkUserActiveStatus();

        if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] !== 'admin' && $_SESSION['user']['role'] !== 'assistant')) {
            header('Location: ' . BASE_URL . '?page=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $activeStatus = $_POST['active_status'] ?? [];

            // Lấy danh sách tất cả tài khoản
            $accounts = $this->userModel->getAllUsers();
            foreach ($accounts as $account) {
                // Không thể thay đổi trạng thái chính mình
                if ($account['id'] == $_SESSION['user']['id']) {
                    continue;
                }

                // Admin chỉ có thể thay đổi trạng thái của assistant và member
                if ($_SESSION['user']['role'] === 'admin' && !in_array($account['role'], ['assistant', 'member'])) {
                    continue;
                }

                // Assistant chỉ có thể thay đổi trạng thái của member
                if ($_SESSION['user']['role'] === 'assistant' && $account['role'] !== 'member') {
                    continue;
                }

                // Xác định trạng thái active
                $isActive = isset($activeStatus[$account['id']]) ? 1 : 0;

                // Cập nhật trạng thái active
                $this->userModel->updateActiveStatus($account['id'], $isActive);
            }

            $_SESSION['message'] = "Account statuses updated successfully!";
            header('Location: ' . BASE_URL . '?page=accounts');
            exit;
        }
    }
}
