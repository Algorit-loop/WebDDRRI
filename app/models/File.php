<?php
require_once '../config/database.php';

class File
{
    private $db;

    public function __construct()
    {
        $this->db = connectDatabase();
    }

    // Lấy danh sách file của người dùng
    public function getFilesByUserId($userId)
    {
        $stmt = $this->db->prepare('SELECT * FROM files WHERE user_id = :user_id');
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getPublicFiles()
    {
        $stmt = $this->db->prepare('SELECT * FROM files WHERE is_public = 1');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Thêm file mới
    public function addFile($name, $path, $userId, $size, $isPublic)
    {
        $stmt = $this->db->prepare('INSERT INTO files (name, path, user_id, size, is_public) VALUES (:name, :path, :user_id, :size, :is_public)');
        $stmt->execute([
            'name' => $name,
            'path' => $path,
            'user_id' => $userId,
            'size' => $size,
            'is_public' => $isPublic
        ]);
    }

    // Lấy thông tin file theo ID
    public function getFileById($fileId, $userId)
    {
        $stmt = $this->db->prepare('SELECT * FROM files WHERE id = :id AND user_id = :user_id');
        $stmt->execute(['id' => $fileId, 'user_id' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Kiểm tra file đã tồn tại theo tên và user_id
    public function getFileByNameAndUserId($name, $userId)
    {
        $stmt = $this->db->prepare('SELECT * FROM files WHERE name = :name AND user_id = :user_id');
        $stmt->execute(['name' => $name, 'user_id' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Cập nhật file
    public function updateFile($id, $path, $size)
    {
        $stmt = $this->db->prepare('UPDATE files SET path = :path, size = :size, updated_at = NOW() WHERE id = :id');
        $stmt->execute([
            'path' => $path,
            'size' => $size,
            'id' => $id
        ]);
    }

    // Xóa file theo ID
    public function deleteFileById($fileId)
    {
        $stmt = $this->db->prepare('DELETE FROM files WHERE id = :id');
        $stmt->execute(['id' => $fileId]);
    }
}
?>
