<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Room: <?= htmlspecialchars($room['name']); ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
    <style>
        /* Bố cục toàn màn hình */
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            overflow-x: hidden;
        }

        .container {
            display: flex;
            flex-direction: column;
            height: 90%;
            width: 100%;
        }

        /* Header */
        .header {
            background-color: salmon;
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 25px;
            font-weight: bold;
        }

        /* Main Content */
        .main-content {
            display: flex;
            flex: 1;
        }

        /* Sidebar */
        .sidebar {
            background-color: #333;
            color: white;
            width: 20%;
            padding: 20px;
            box-sizing: border-box;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar ul li {
            margin-bottom: 15px;
        }

        .sidebar ul li a {
            color: white;
            text-decoration: none;
            font-size: 16px;
            transition: color 0.3s;
        }

        .sidebar ul li a:hover {
            color: #007bff;
        }

        /* Content Section */
        .content {
            flex: 1;
            padding: 20px;
            box-sizing: border-box;
        }

        .content h2 {
            margin-top: 0;
        }

        /* Chat Messages Styling */
        .messages {
            border: 1px solid #ddd;
            padding: 10px;
            height: 300px;
            overflow-y: scroll;
            margin-bottom: 20px;
        }

        .messages p {
            margin: 5px 0;
        }

        .messages .username {
            font-weight: bold;
        }

        .messages .timestamp {
            color: gray;
            font-size: 12px;
        }
        
    </style>
    <style>
    /* Chat Messages Styling */
    .messages {
        border: 1px solid #ddd;
        padding: 10px;
        height: 330px; /* Tăng chiều cao khung chat */
        overflow-y: scroll;
        margin-bottom: 20px;
        border-radius: 8px;
    }

    .messages p {
        margin: 5px 0;
    }

    .messages .username {
        font-weight: bold;
    }

    .messages .timestamp {
        color: gray;
        font-size: 12px;
    }

    .message-form {
        display: flex;
        align-items: center;
        background-color: #f9f9f9;
        border: 1px solid #ddd;
        padding: 10px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .message-form textarea {
        flex: 1;
        height: 40px; /* Đặt chiều cao nhỏ hơn để phù hợp trên một dòng */
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 14px;
        resize: none;
        margin-right: 10px; /* Tạo khoảng cách giữa ô nhập và nút Send */
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .message-form textarea:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    }

    .message-form button {
        height: 40px;
        padding: 0 20px;
        font-size: 14px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .message-form button:hover {
        background-color: #0056b3;
    }
</style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">Room: <?= htmlspecialchars($room['name']); ?></div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Sidebar -->
            <div class="sidebar">
                <ul>
                    <li><a href="<?= BASE_URL ?>?page=dashboard">Dashboard</a></li>
                    <li><a href="<?= BASE_URL ?>?page=public_files">Public</a></li>
                    <li><a href="<?= BASE_URL ?>?page=profile">Profile</a></li>
                    <?php if (in_array($_SESSION['user']['role'], ['admin', 'assistant'])): ?>
                        <li><a href="<?= BASE_URL ?>?page=accounts">Accounts</a></li>
                    <?php endif; ?>
                    <li><a href="<?= BASE_URL ?>?page=chats">Chats</a></li>
                    <li><a href="<?= BASE_URL ?>?page=settings">Settings</a></li>
                    <li><a href="<?= BASE_URL ?>?page=logout">Logout</a></li>
                </ul>
            </div>

            <!-- Content Section -->
            <div class="content">
                <!-- Messages -->
                <h2>Chat Messages</h2>
                <div class="messages">
                    <?php if (!empty($messages)): ?>
                        <?php foreach ($messages as $message): ?>
                            <p>
                                <span class="username"><?= htmlspecialchars($message['username']); ?>:</span>
                                <?= htmlspecialchars($message['message_content']); ?>
                                <span class="timestamp">(<?= $message['timestamp']; ?>)</span>
                            </p>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No messages yet in this room.</p>
                    <?php endif; ?>
                </div>

                <!-- Message Form -->
                <div class="message-form">
                    <form action="<?= BASE_URL ?>?page=send_message" method="POST" style="display: flex; flex: 1;">
                        <input type="hidden" name="room_id" value="<?= $room['id']; ?>">
                        <textarea name="message_content" placeholder="Type your message here..." required></textarea>
                        <button type="submit">Send</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
