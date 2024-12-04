<?php
require_once __DIR__ . '/../../config/database.php';

class Chat
{
    private $db;

    public function __construct()
    {
        $this->db = connectDatabase();
    }

    // Lấy tất cả các phòng chat
    public function getAllRooms()
    {
        $stmt = $this->db->prepare('SELECT * FROM chat_rooms ORDER BY created_at DESC');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy thông tin một phòng chat
    public function getRoomById($roomId)
    {
        $stmt = $this->db->prepare('SELECT * FROM chat_rooms WHERE id = :id');
        $stmt->execute(['id' => $roomId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Lấy tin nhắn trong một phòng chat
    public function getMessagesByRoomId($roomId)
    {
        $stmt = $this->db->prepare('
            SELECT messages.id, messages.message_content, messages.timestamp, 
                users.username 
            FROM messages 
            INNER JOIN users ON messages.sender_id = users.id 
            WHERE messages.room_id = :room_id 
            ORDER BY messages.timestamp ASC
        ');
        $stmt->execute(['room_id' => $roomId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createRoom($roomName)
    {
        $stmt = $this->db->prepare('INSERT INTO chat_rooms (name) VALUES (:name)');
        $stmt->execute(['name' => $roomName]);
    }

    public function addMessage($roomId, $senderId, $messageContent)
    {
        $stmt = $this->db->prepare('INSERT INTO messages (room_id, sender_id, message_content) VALUES (:room_id, :sender_id, :message_content)');
        $stmt->execute([
            'room_id' => $roomId,
            'sender_id' => $senderId,
            'message_content' => $messageContent
        ]);
    }

    
}
