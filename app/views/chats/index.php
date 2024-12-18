<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chats</title>
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

        /* Table Styling */
        .content-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .content-table th, .content-table td {
            border: 1px solid #ddd;
            text-align: left;
            padding: 10px;
        }

        .content-table th {
            background-color: lightcoral;
            color: white;
        }

        .content-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .content-table tr:hover {
            background-color: #ddd;
        }

        .content-table td.actions {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">Chats</div>
        
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
                <h2>Welcome to Chat Rooms</h2>
                <?php if (isset($_SESSION['message'])): ?>
                    <p style="color: green; font-weight: bold;"><?= htmlspecialchars($_SESSION['message']) ?></p>
                    <?php unset($_SESSION['message']); // Xóa thông báo sau khi hiển thị ?>
                <?php endif; ?>
                <!-- Form tạo phòng chat -->
                <form action="<?= BASE_URL ?>?page=create_room" method="POST">
                    <label for="room_name">Create a new room:</label>
                    <input type="text" name="room_name" id="room_name" placeholder="Room name" required>
                    <button type="submit">Create Room</button>
                </form>

                <h3>Available Chat Rooms:</h3>
                <table class="content-table">
                    <thead>
                        <tr>
                            <th>Room Name</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($rooms)): ?>
                            <?php foreach ($rooms as $room): ?>
                                <tr>
                                    <td><?= htmlspecialchars($room['name']); ?></td>
                                    <td><?= htmlspecialchars($room['created_at']); ?></td>
                                    <td class="actions">
                                        <a href="<?= BASE_URL ?>?page=chats&room_id=<?= $room['id']; ?>">Join</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3">No chat rooms available yet.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
