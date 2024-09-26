<?php 
// session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"> -->
</head>
<style>
    .close {
        background-color: transparent;
        border: none;
        font-size: 24px;
        color: #000;
        /* Change the color to suit your needs */
        cursor: pointer;
        outline: none;
    }

    .close:hover {
        color: #ff0000;
        /* Change the color on hover */
    }

    .close:focus {
        outline: none;
    }
    .nav-link :hover {
                color: yellow !important;
            }

            /* for badges */
            #notificationBadge {
                display: inline-block;
                padding: 5px 10px;
                border-radius: 50%;
                background-color: #ff0000;
                /* Red background */
                color: #ffffff;
                /* White text */
                font-size: 14px;
                font-weight: bold;
                text-align: center;
                animation: vibrate 0.5s infinite;
            }

            #bookingBadge {
                display: inline-block;
                /* padding: 5px 10px; */
                /* border-radius: 50%; */
                background-color: #ff0000;
                /* Red background */
                color: #ffffff;
                /* White text */
                /* font-size: 14px; */
                font-weight: bold;
                text-align: center;
                animation: vibrate 0.5s infinite;
                margin-left 20px;
            }

            /* @keyframes vibrate {
                0% {
                    transform: translate(0);
                }

                20% {
                    transform: translate(-2px, 2px);
                }

                40% {
                    transform: translate(2px, -2px);
                }

                60% {
                    transform: translate(-2px, -2px);
                }

                80% {
                    transform: translate(2px, 2px);
                }

                100% {
                    transform: translate(0);
                }
            } */
</style>

<body>
    <!-- agar background ki need ho tou yahan apply karna bg-white-->
    <div class="container-xxl pt-0 mt-0">
        <!-- Spinner Start -->
        <div id="spinner"
            class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->

        <!-- Navbar Start -->
        <div class="container-fluid nav-bar py-0">
            <nav class="navbar navbar-expand-lg bg-white py-1 px-4" style="height:60px !important">
                <a href="index.php" class="navbar-brand d-flex align-items-center text-center">
                    <div class="p-2 me-2">
                        <img class="img-fluid" src="img/logo.png" height="40px" width="40px" alt="Icon" style="">
                    </div>
                    <!-- <h1 class="m-0 text-primary fs-3">HireMe</h1> -->
                </a>
                <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav ms-auto" style="hover-color:yellow">
                        <a href="index.php" class="text-primary nav-item nav-link active">Home</a>
                        <a href="view_emp.php" class="text-primary nav-item nav-link">Employees</a>
                        <a href="user_feedback.php" class="nav-item nav-link text-primary">Feedback</a>
                        <a href="bills_Record.php" class="nav-item nav-link text-primary">Bills</a>
                        <a href="aboutus.php" class="nav-item nav-link text-primary">About</a>
                    </div>
                    <div class="nav-item dropdown text-primary">
                        <a href="#" class="nav-link dropdown-toggle text-primary" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Account
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item nav-link text-primary" href="edit_profile.php">Profile</a></li>
                            <li><a class="dropdown-item nav-link text-primary" href="user_discount.php">Discounts</a>
                            </li>
                            <li><a class="dropdown-item nav-link text-primary" href="logout.php">Logout</a></li>
                        </ul>
                    </div>
                    <!-- ======================================= your payment history code start ================================================-->
                    <?php
include 'config/db_connection.php';

// Ensure the user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['id'];

// Fetch payments for the logged-in user
$sql_payments = "
    SELECT id, amount, currency, description, stripe_charge_id, receipt_email, name, created_at
    FROM payments
    WHERE user_id = ?
    ORDER BY created_at DESC";
    
$stmt_payments = $mysqli->prepare($sql_payments);

if ($stmt_payments) {
    $stmt_payments->bind_param("i", $user_id);
    $stmt_payments->execute();
    $result_payments = $stmt_payments->get_result();
    $stmt_payments->close();
} else {
    die("Error in preparing statement: " . $mysqli->error);
}
?>
<!-- Badge icon for payments (clickable) -->
<a id="paymentBadge" class="badge badge-primary bg-primary" href="#" data-toggle="modal" data-target="#paymentsModal">
    <?php echo $result_payments->num_rows; ?>
</a>
<!-- Payments Modal -->
<div class="modal fade" id="paymentsModal" tabindex="-1" role="dialog" aria-labelledby="paymentsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary" id="paymentsModalLabel">Payments</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Display all payments here -->
                <?php
                if ($result_payments->num_rows > 0) {
                    while ($row = $result_payments->fetch_assoc()) {
                        $payment_id = htmlspecialchars($row["id"]);
                        $amount = htmlspecialchars($row["amount"]);
                        $currency = htmlspecialchars($row["currency"]);
                        $description = htmlspecialchars($row["description"]);
                        $stripe_charge_id = htmlspecialchars($row["stripe_charge_id"]);
                        $receipt_email = htmlspecialchars($row["receipt_email"]);
                        $name = htmlspecialchars($row["name"]);
                        $created_at = new DateTime($row["created_at"]);
                        $formatted_date = $created_at->format('F j, Y, g:i a'); // Format: July 16, 2024, 5:14 pm

                        echo "<div class='payment-item mb-3'>";
                        echo "<h5 class='text-primary'>Payment ID: {$payment_id}</h5>";
                        echo "<p class='text-primary'>Amount: {$amount} {$currency}</p>";
                        echo "<p class='text-primary'>Description: {$description}</p>";
                        echo "<p class='text-primary'>Stripe Charge ID: {$stripe_charge_id}</p>";
                        echo "<p class='text-primary'>Receipt Email: {$receipt_email}</p>";
                        echo "<p class='text-primary'>Name: {$name}</p>";
                        echo "<p class='text-primary'>Date: {$formatted_date}</p>";
                        echo "</div>";
                        echo "<hr />";
                    }
                } else {
                    echo "<p>No payments found</p>";
                }
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function () {
    $('#paymentBadge').on('click', function () {
        $(this).addClass('animated-badge');
    });

    $('#paymentsModal').on('shown.bs.modal', function () {
        $('#paymentBadge').removeClass('animated-badge');
    });
});
</script>
<!-- ======================================= your payment history code end ================================================-->


                <!--======================================== Booking Notification start  =======================================================-->
                <?php
                $user_id = $_SESSION['id'];
                $username = $_SESSION['username'];

                // Fetch booking details
                $sql_bookings = "
                SELECT 
                    b.booking_id, b.user_address, b.timing, b.day, b.description, b.created_at, b.status,
                    e.skills, e.city_id, e.img, e.status AS employee_status, e.amount,
                    u.username AS employee_name, u.contact AS employee_contact, u.profileimage AS employee_profileimage
                FROM bookings b
                INNER JOIN employees e ON b.employee_id = e.id
                INNER JOIN users u ON e.user_id = u.id
                WHERE b.user_id = ?
                ORDER BY b.created_at DESC";
                $stmt_bookings = $mysqli->prepare($sql_bookings);
                $stmt_bookings->bind_param("i", $user_id);
                $stmt_bookings->execute();
                $result_bookings = $stmt_bookings->get_result();
                $stmt_bookings->close();
                ?>
                <!-- Badge icon for bookings (clickable) -->
                <a id="bookingBadge" class="badge badge-primary bg-primary" href="#" data-toggle="modal"
                    data-target="#bookingsModal">
                    <?php echo $result_bookings->num_rows; ?>
                </a>
                <!-- Bookings Modal -->
                <div class="modal fade" id="bookingsModal" tabindex="-1" role="dialog"
                    aria-labelledby="bookingsModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title text-primary" id="bookingsModalLabel">Booking Details</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <!-- Display all bookings here -->
                                <?php
                                if ($result_bookings->num_rows > 0) {
                                    while ($row = $result_bookings->fetch_assoc()) {
                                        $status = $row["status"];
                                        $employee_name = $row["employee_name"];
                                        $employee_contact = $row["employee_contact"];
                                        $employee_profileimage = $row["employee_profileimage"];
                                        $skills = $row["skills"];
                                        $city_id = $row["city_id"];
                                        $img = $row["img"];
                                        $employee_status = $row["employee_status"];
                                        $amount = $row["amount"];
                                        $user_address = $row["user_address"];
                                        $timing = $row["timing"];
                                        $day = $row["day"];
                                        $description = $row["description"];
                                        $created_at = new DateTime($row["created_at"]);
                                        $formatted_date = $created_at->format('F j, Y, g:i a'); // Format: July 16, 2024, 5:14 pm
                                
                                        echo "
                        <div class='card mb-3'>
                            <div class='card-header bg-primary text-white'>
                                Booking ID: {$row['booking_id']}
                            </div>
                            <div class='card-body'>
                                <h5 class='card-title text-primary'>Status: {$status}</h5>
                                <p class='card-text text-primary'>
                                    <strong class='text-primary'>Address:</strong> {$user_address}<br>
                                    <strong class='text-primary'>Timing:</strong> {$timing}<br>
                                    <strong class='text-primary'>Day:</strong> {$day}<br>
                                    <strong class='text-primary'>Your Description:</strong> {$description}<br>
                                    <strong class='text-primary'>Created At:</strong> {$formatted_date}<br><br>
                                    <strong class='text-primary'>Employee Details:</strong><br>
                                    <img src='{$employee_profileimage}' alt='Profile Image' class='img-thumbnail rounded-circle' style='width: 100px; height: 100px;'><br>
                                    <strong class='text-primary'>Name:</strong> {$employee_name}<br>
                                    <strong class='text-primary'>Contact:</strong> {$employee_contact}<br>
                                    <strong class='text-primary'>Skills:</strong> {$skills}<br>
                                    <strong class='text-primary'>City ID:</strong> {$city_id}<br>
                                    <strong class='text-primary'>Hourly Rate:</strong> {$amount}<br>
                                    <strong class='text-primary'>Status:</strong> {$employee_status}<br>
                                    </p>
                                    </div>
                                    <div class='card-footer text-primary'>
                                    Amount Charged: {$amount}
                                    </div>
                                    </div>";
                                    // <img src='{$img}' alt='Employee Image' class='img-thumbnail rounded-circle' style='width: 100px; height: 100px;'>
                                    }
                                } else {
                                    echo "<p>No bookings available</p>";
                                }
                                ?>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!--==================================================== Booking Notification end  =====================================================-->
            </nav>
        </div>
        <!-- script and style for badges -->
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>