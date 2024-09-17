<?php
include("config/db_connection.php");
session_start();

if ($_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$sql = "SELECT * FROM discounts";
$result = $mysqli->query($sql);

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Discounts</title>
</head>
<body>
    <h1>Manage Discounts</h1>
    <a href="admin_add_discount.php">Add Discount</a>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Discount Code</th>
            <th>Discount Percentage</th>
            <th>Start Date</th>
            <th>End Date</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['discount_code']; ?></td>
                <td><?php echo $row['discount_percentage']; ?></td>
                <td><?php echo $row['start_date']; ?></td>
                <td><?php echo $row['end_date']; ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
