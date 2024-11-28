<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accounts</title>
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

        .content-table input[type="checkbox"] {
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">My Awesome Website</div>
        
        <!-- Main Content -->
        <div class="main-content">
            <!-- Sidebar -->
            <div class="sidebar">
                <ul>
                    <li><a href="<?= BASE_URL ?>?page=dashboard">Dashboard</a></li>
                    <li><a href="<?= BASE_URL ?>?page=public_files">Public</a></li>
                    <li><a href="<?= BASE_URL ?>?page=profile">Profile</a></li>
                    <li><a href="<?= BASE_URL ?>?page=accounts">Accounts</a></li>
                    <li><a href="<?= BASE_URL ?>?page=settings">Settings</a></li>
                    <li><a href="<?= BASE_URL ?>?page=logout">Logout</a></li>
                </ul>
            </div>
            <!-- Content Section -->
            <div class="content">
                <h2>Accounts</h2>
                <form action="<?= BASE_URL ?>?page=toggle_active" method="POST">
                    <table class="content-table">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Created At</th>
                                <th>Active</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($accounts)): ?>
                                <?php foreach ($accounts as $account): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($account['username']); ?></td>
                                        <td><?= htmlspecialchars($account['email']); ?></td>
                                        <td><?= htmlspecialchars($account['role']); ?></td>
                                        <td><?= htmlspecialchars($account['created_at']); ?></td>
                                        
                                        <td>
                                            <?php if (
                                                ($_SESSION['user']['role'] === 'admin' && in_array($account['role'], ['assistant', 'member'])) ||
                                                ($_SESSION['user']['role'] === 'assistant' && $account['role'] === 'member')
                                            ): ?>
                                                <input type="checkbox" name="active_status[<?= $account['id'] ?>]" <?= $account['active'] ? 'checked' : '' ?>>
                                            <?php else: ?>
                                                <?= $account['active'] ? 'Active' : 'Inactive' ?>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5">No accounts available.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <button type="submit">Update Status</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
