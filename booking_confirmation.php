<?php
session_start();
include 'config/db_connection.php';

// Check if user is logged in; otherwise, redirect to login page
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header("Location: index.php");
    exit;
}

// Check if booking_id is provided in URL parameter
if (!isset($_GET['booking_id'])) {
    header("Location: index.php");
    exit;
}

$booking_id = $_GET['booking_id'];

// Fetch booking details
$booking_sql = "
    SELECT b.*, e.username AS employee_username, u.username AS user_username, u.contact_info, u.location, u.age
    FROM bookings b
    JOIN employees e ON b.employee_id = e.id
    JOIN users u ON b.user_id = u.id
    WHERE b.id = ?
";
$stmt_booking = $mysqli->prepare($booking_sql);
$stmt_booking->bind_param("i", $booking_id);
$stmt_booking->execute();
$stmt_booking->bind_result($booking_id, $employee_id, $user_id, $user_address, $timing, $day, $description, $hours, $total_amount, $created_at, $employee_username, $user_username, $contact_info, $location, $age);
$stmt_booking->fetch();
$stmt_booking->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Booking Confirmation</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container-xxl py-5">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6 wow fadeIn" data-wow-delay="0.1s">
                    <div class="about-img position-relative overflow-hidden p-5 pe-0">
                        <img class="img-fluid w-100" src="img/thank_you.jpg">
                    </div>
                </div>
                <div class="col-lg-6 wow fadeIn" data-wow-delay="0.5s">
                    <h1 class="mb-4" style="text-align:center;">Thank You for Your Booking!</h1>
                    <h4>Booking Details</h4>
                    <p><strong>Employee Name:</strong> <?php echo htmlspecialchars($employee_username); ?></p>
                    <p><strong>Service Description:</strong> <?php echo htmlspecialchars($description); ?></p>
                    <p><strong>Hours:</strong> <?php echo htmlspecialchars($hours); ?></p>
                    <p><strong>Total Amount:</strong> <?php echo htmlspecialchars($total_amount); ?></p>
                    <p><strong>Booking Date:</strong> <?php echo htmlspecialchars($day); ?></p>
                    <p><strong>Timing:</strong> <?php echo htmlspecialchars($timing); ?></p>
                    <h4>User Details</h4>
                    <p><strong>Username:</strong> <?php echo htmlspecialchars($user_username); ?></p>
                    <p><strong>Contact Info:</strong> <?php echo htmlspecialchars($contact_info); ?></p>
                    <p><strong>Location:</strong> <?php echo htmlspecialchars($location); ?></p>
                    <p><strong>Age:</strong> <?php echo htmlspecialchars($age); ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>
