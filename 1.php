<?php
// Start session and include database connection
session_start();
include 'config/db_connection.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    die("User not logged in");
}

// Fetching data for logged-in employee
$logged_in_employee_id = $_SESSION['id']; // Assuming 'id' is stored in session

// SQL Query
$employee_sql = "
    SELECT e.id AS employee_id, e.skills, e.city_id, e.img, e.status, 
           b.booking_id, b.user_id, b.user_address, b.timing, b.day, b.description, b.created_at, b.status AS booking_status,
           u.username, u.email, u.contact
    FROM employees e
    JOIN bookings b ON e.id = b.employee_id
    JOIN users u ON b.user_id = u.id
    WHERE e.id = ?
";

// Prepare the SQL statement
$stmt_employee = $mysqli->prepare($employee_sql);
if (!$stmt_employee) {
    die("Prepare failed: " . $mysqli->error);
}

// Bind parameters and execute the query
$stmt_employee->bind_param("i", $logged_in_employee_id);
$stmt_employee->execute();
$result_employee = $stmt_employee->get_result();

if (!$result_employee) {
    die("Query failed: " . $mysqli->error);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
    <title>Employee Work Records</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="mt-4 text-primary">Employee Work Records</h1>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Username</th>
                        <th>Skills</th>
                        <th>City</th>
                        <th>Status</th>
                        <th>Booking ID</th>
                        <th>Description</th>
                        <th>User Address</th>
                        <th>Timing</th>
                        <th>Day</th>
                        <th>Created At</th>
                        <th>Booking Status</th>
                        <th>User Email</th>
                        <th>User Contact</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result_employee->num_rows > 0): ?>
                        <?php while ($row = $result_employee->fetch_assoc()): ?>
                            <tr>
                                <td><img src="<?php echo htmlspecialchars($row['img']); ?>" alt="Employee Image" style="width: 100px; height: auto;"></td>
                                <td><?php echo htmlspecialchars($row['username']); ?></td>
                                <td><?php echo htmlspecialchars($row['skills']); ?></td>
                                <td><?php echo htmlspecialchars($row['city_id']); ?></td>
                                <td><?php echo htmlspecialchars($row['status']); ?></td>
                                <td><?php echo htmlspecialchars($row['booking_id']); ?></td>
                                <td><?php echo htmlspecialchars($row['description']); ?></td>
                                <td><?php echo htmlspecialchars($row['user_address']); ?></td>
                                <td><?php echo htmlspecialchars($row['timing']); ?></td>
                                <td><?php echo htmlspecialchars($row['day']); ?></td>
                                <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                                <td><?php echo htmlspecialchars($row['booking_status']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo htmlspecialchars($row['contact']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="14">No records found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php 
    // Close the database connection
    $stmt_employee->close();
    $mysqli->close();
    ?>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
