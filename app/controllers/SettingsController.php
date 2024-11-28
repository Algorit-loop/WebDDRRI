<?php
require_once '../config/database.php';

class SettingsController
{
    public function changePassword()
    {
        session_start();
        $accountController = new AccountController();
        $accountController->checkUserActiveStatus();
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '?page=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            if ($newPassword !== $confirmPassword) {
                $error = "New password and confirmation do not match.";
                require_once __DIR__ . '/../views/settings/index.php';
                return;
            }

            $db = connectDatabase();
            $stmt = $db->prepare('SELECT * FROM users WHERE id = :id');
            $stmt->execute(['id' => $_SESSION['user']['id']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($currentPassword, $user['password'])) {
                $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

                $stmt = $db->prepare('UPDATE users SET password = :password WHERE id = :id');
                $stmt->execute(['password' => $hashedPassword, 'id' => $_SESSION['user']['id']]);

                $success = "Password changed successfully.";
            } else {
                $error = "Current password is incorrect.";
            }
        }

        require_once __DIR__ . '/../views/settings/index.php'; // Sửa đường dẫn ở đây
    }
}
?>
