<?php
require_once __DIR__ . '/../../config/database.php';

class User
{
    private $db;

    public function __construct()
    {
        $this->db = connectDatabase();
    }
    public function getAllUsers()
    {
        $stmt = $this->db->prepare('SELECT id, username, email, role, active, created_at FROM users');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function updateActiveStatus($userId, $isActive)
    {
        $stmt = $this->db->prepare('UPDATE users SET active = :active WHERE id = :id');
        $stmt->execute(['active' => $isActive, 'id' => $userId]);
    }
    public function getUserByUsername($username)
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE username = :username');
        $stmt->execute(['username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getUserById($userId)
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->execute(['id' => $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getFilesByUserId($userId)
    {
        $stmt = $this->db->prepare('SELECT * FROM files WHERE user_id = :user_id');
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function createUser($username, $email, $password)
    {
        // Kiểm tra người dùng hoặc email đã tồn tại
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM users WHERE username = :username OR email = :email');
        $stmt->execute(['username' => $username, 'email' => $email]);
        if ($stmt->fetchColumn() > 0) {
            throw new Exception("Username or email already exists.");
        }

        // Chèn người dùng mới
        $stmt = $this->db->prepare('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');
        $stmt->execute([
            'username' => $username,
            'email' => $email,
            'password' => $password
        ]);
    }

}
?>
