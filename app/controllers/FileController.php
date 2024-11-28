<?php
require_once '../app/models/File.php';
require_once '../config/config.php';
require_once '../app/controllers/AccountController.php';
class FileController
{
    private $uploadDir = '../uploads/';
    private $fileModel;

    public function __construct()
    {
        $this->fileModel = new File();
    }

    public function dashboard()
    {
        session_start();
        $accountController = new AccountController();
        $accountController->checkUserActiveStatus();
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '?page=login');
            exit;
        }

        $user = $_SESSION['user'];
        $files = $this->fileModel->getFilesByUserId($user['id']);
        require_once '../app/views/home/index.php';
    }

    public function publicFiles()
    {
        session_start();
        $accountController = new AccountController();
        $accountController->checkUserActiveStatus();
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '?page=login');
            exit;
        }
        // Lấy danh sách các file công khai từ Model
        $files = $this->fileModel->getPublicFiles();

        // Truyền dữ liệu tới View
        require_once '../app/views/home/public_files.php';
    }

    public function upload()
    {
        session_start();
        $accountController = new AccountController();
        $accountController->checkUserActiveStatus();
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '?page=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
            $file = $_FILES['file'];
            $user = $_SESSION['user'];

            if ($file['error'] !== UPLOAD_ERR_OK) {
                $_SESSION['message'] = "Error uploading file.";
                header('Location: ' . BASE_URL . '?page=dashboard');
                exit;
            }

            $uploadDir = $this->uploadDir . $user['username'] . '/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fileName = basename($file['name']);
            $filePath = $uploadDir . $fileName;
            $existingFile = $this->fileModel->getFileByNameAndUserId($fileName, $user['id']);

            if ($existingFile) {
                move_uploaded_file($file['tmp_name'], $filePath);
                $this->fileModel->updateFile($existingFile['id'], '/CNWEB22N15/WebDDRRI/uploads/' . $user['username'] . '/' . $fileName, $file['size']);
                $_SESSION['message'] = "File '$fileName' đã tồn tại và đã được ghi đè!";
            } else {
                move_uploaded_file($file['tmp_name'], $filePath);
                $isPublic = isset($_POST['is_public']) ? 1 : 0;
                $this->fileModel->addFile($fileName, '/CNWEB22N15/WebDDRRI/uploads/' . $user['username'] . '/' . $fileName, $user['id'], $file['size'], $isPublic);
                
                $_SESSION['message'] = "File '$fileName' đã được tải lên thành công!";
            }

            header('Location: ' . BASE_URL . '?page=dashboard');
            exit;
        }
    }

    public function delete()
    {
        session_start();
        $accountController = new AccountController();
        $accountController->checkUserActiveStatus();
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '?page=login');
            exit;
        }

        if (isset($_GET['id'])) {
            $fileId = $_GET['id'];
            $user = $_SESSION['user'];

            $file = $this->fileModel->getFileById($fileId, $user['id']);
            if ($file) {
                $filePath = $this->uploadDir . $user['username'] . '/' . basename($file['path']);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                $this->fileModel->deleteFileById($fileId);
            }

            header('Location: ' . BASE_URL . '?page=dashboard');
            exit;
        }
    }
}
?>
