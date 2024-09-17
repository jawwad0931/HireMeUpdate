<?php
include("config/db_connection.php");
session_start();
if ($_SESSION['role'] !== 'user') {
    header("Location: index.php");
}

$user_id = $_SESSION['id'];
$username = $_SESSION['username'];

// Fetch all notifications (accepted or rejected bookings)
$sql_notifications_all = "
    SELECT b.status, u.username AS employee_name, b.created_at
    FROM bookings b
    INNER JOIN users u ON b.employee_id = u.id
    WHERE b.user_id = ? AND (b.status = 'accepted' OR b.status = 'rejected')
    ORDER BY b.created_at DESC
";
$stmt_notifications_all = $mysqli->prepare($sql_notifications_all);
$stmt_notifications_all->bind_param("i", $user_id);
$stmt_notifications_all->execute();
$result_notifications_all = $stmt_notifications_all->get_result();
$stmt_notifications_all->close();

// Mark notifications as read (optional, based on your application's logic)
// For example, update a column `read_status` in your `bookings` table

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Notifications</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        .notifications-list {
            list-style-type: none;
            padding: 0;
        }

        .notification-item {
            margin-bottom: 10px;
            padding: 10px;
            background-color: #f9f9f9;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Your Notifications</h1>
        <ul class="notifications-list">
            <?php if ($result_notifications_all->num_rows > 0): ?>
                <?php while ($row = $result_notifications_all->fetch_assoc()): ?>
                    <li class="notification-item">
                        <?php echo "{$row['employee_name']} has {$row['status']} your booking."; ?>
                        <span class="timestamp"><?php echo date('F j, Y, g:i a', strtotime($row['created_at'])); ?></span>
                    </li>
                <?php endwhile; ?>
            <?php else: ?>
                <li>No notifications found.</li>
            <?php endif; ?>
        </ul>
    </div>
</body>
</html>
