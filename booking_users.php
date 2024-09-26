<?php
session_start();
include 'config/db_connection.php';

// Verify user is logged in as an employee
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'employee') {
    header("Location: index.php");
    exit;
}

$logged_in_user_id = $_SESSION['id']; // This holds the logged-in user's ID

// Step 1: Fetch the employee_id for the logged-in user_id
$sql_employee = "SELECT id FROM employees WHERE user_id = ?";
$stmt_employee = $mysqli->prepare($sql_employee);
if ($stmt_employee === false) {
    die('Prepare failed: ' . htmlspecialchars($mysqli->error));
}

$stmt_employee->bind_param("i", $logged_in_user_id);
$stmt_employee->execute();
$result_employee = $stmt_employee->get_result();

if ($result_employee->num_rows === 0) {
    die('No employee found for the given user ID.');
}

$employee = $result_employee->fetch_assoc();
$employee_id = $employee['id'];

// Step 2: Handle status update if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'], $_POST['status'])) {
    $booking_id = $_POST['booking_id'];
    $status = $_POST['status'];

    // Prepare the SQL update query
    $sql_update = "UPDATE bookings SET status = ? WHERE booking_id = ? AND employee_id = ?";
    $stmt_update = $mysqli->prepare($sql_update);
    if ($stmt_update === false) {
        die('Prepare failed: ' . htmlspecialchars($mysqli->error));
    }

    $stmt_update->bind_param("sii", $status, $booking_id, $employee_id);
    $stmt_update->execute();

    if ($stmt_update->affected_rows > 0) {
        $_SESSION['message'] = "Booking status updated successfully.";
    } else {
        $_SESSION['message'] = "Failed to update booking status.";
    }

    $stmt_update->close();

    // Redirect to avoid form resubmission issues
    header("Location: booking_users.php");
    exit;
}

// Step 3: Fetch bookings for the retrieved employee_id
$sql_bookings = "SELECT booking_id, employee_id, user_id, user_address, timing, day, description, created_at, status 
                 FROM bookings 
                 WHERE employee_id = ?";
$stmt_bookings = $mysqli->prepare($sql_bookings);
if ($stmt_bookings === false) {
    die('Prepare failed: ' . htmlspecialchars($mysqli->error));
}

$stmt_bookings->bind_param("i", $employee_id);
$stmt_bookings->execute();
$result_bookings = $stmt_bookings->get_result();

if ($result_bookings === false) {
    die('Query failed: ' . htmlspecialchars($mysqli->error));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HireMe</title>
    <?php include 'includes/header.php'; ?>
    <style>
        .table th, .table td {
            vertical-align: middle;
        }
        .btn-custom {
            background: linear-gradient(135deg, #00c6ff, #0072ff);
            color: white;
            transition: opacity 0.3s ease;
        }
        .btn-custom:hover {
            opacity: 0.8;
        }
        .alert-custom {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }
        .table-custom {
            margin-top: 20px;
            width: 100%;
            border-collapse: collapse;
        }
        .table-custom th, .table-custom td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body class="bg-white">

<div class="container mt-4">
    <h2 class="mb-4 text-primary">Employee Dashboard</h2>

    <!-- Important Note Alert Message -->
    <div class="alert alert-custom" role="alert">
        <h4 class="alert-heading wow fadeIn">Important Note</h4>
        <p class="lead">Please review and manage your bookings carefully.</p>
        <p class="lead wow fadeIn">
            You can update the status of each booking based on your needs. Make sure to manage your bookings promptly and efficiently.
        </p>
        <p class="wow fadeIn">
            Regularly reviewing your bookings will help ensure smooth operations and allow you to meet client expectations. Always keep your availability and booking details up-to-date to avoid any conflicts or delays.
        </p>
        <p class="wow fadeIn">
            If there are any issues or changes required for a booking, take immediate action to either reschedule, update the booking status, or contact the client directly.
        </p>
        <p class="wow fadeIn">
            Your commitment to timely and accurate management of bookings will contribute to a successful experience for both you and your clients. Thank you for your dedication.
        </p>
        <hr>
        <p class="mb-0 wow fadeIn">For any issues, please contact the support team.</p>
    </div>

    <!-- Display update message -->
    <?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>


    <!-- Display booking details for the employee -->
    <h3 class="mb-4 text-primary wow fadeIn">Your Booking Details</h3>

    <?php if ($result_bookings->num_rows > 0): ?>
        <table class="table table-custom wow fadeIn">
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Employee ID</th>
                    <th>User ID</th>
                    <th>User Address</th>
                    <th>Timing</th>
                    <th>Day</th>
                    <th>Description</th>
                    <th>Created At</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result_bookings->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row["booking_id"]); ?></td>
                        <td><?php echo htmlspecialchars($row["employee_id"]); ?></td>
                        <td><?php echo htmlspecialchars($row["user_id"]); ?></td>
                        <td><?php echo htmlspecialchars($row["user_address"]); ?></td>
                        <td><?php echo htmlspecialchars($row["timing"]); ?></td>
                        <td><?php echo htmlspecialchars($row["day"]); ?></td>
                        <td><?php echo htmlspecialchars($row["description"]); ?></td>
                        <td><?php echo htmlspecialchars($row["created_at"]); ?></td>
                        <td><?php echo htmlspecialchars($row["status"]); ?></td>
                        <td>
                            <form method="POST" action="">
                                <input type="hidden" name="booking_id" value="<?php echo htmlspecialchars($row['booking_id']); ?>">
                                <select name="status" onchange="this.form.submit()">
                                    <option value="accepted" <?php echo ($row['status'] === 'accepted') ? 'selected' : ''; ?>>Accepted</option>
                                    <option value="rejected" <?php echo ($row['status'] === 'rejected') ? 'selected' : ''; ?>>Rejected</option>
                                    <option value="pending" <?php echo ($row['status'] === 'pending') ? 'selected' : ''; ?>>Pending</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No bookings found.</p>
    <?php endif; ?>

    <!-- Close the statement and connection -->
    <?php
    $stmt_employee->close();
    $stmt_bookings->close();
    $mysqli->close();
    ?>

    <?php include 'includes/footer.php'; ?>
</div>



<!-- Back to top -->
<?php include 'includes/back_top.php'; ?>

</body>
</html>
