<?php
session_start();
include 'config/db_connection.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the user is logged in and has the correct role
// if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
//     header("Location: index.php");
//     exit();
// }

// Get the logged-in user's ID
$logged_in_user_id = $_SESSION['id'];
// echo $logged_in_user_id;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HireMe</title>
    <?php include 'includes/header.php'; ?>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body class="bg-white">
    <!-- navbar start -->
    <?php include 'includes/navbar.php'; ?>   
    <!-- navbar end -->
    <!-- Header Start -->
    <div class="container bg-white p-0">
            <div class="row g-0 align-items-center flex-column-reverse flex-md-row">
                <div class="col-md-6 p-5 mt-lg-5">
                    <h1 class="display-5 animated fadeIn mb-4 text-primary">Billings & Records</h1> 
                        <nav aria-label="breadcrumb animated fadeIn">
                        <ol class="breadcrumb text-uppercase bg-white">
                            <li class="breadcrumb-item"><a href="user.php">Home</a></li>
                            <li class="breadcrumb-item text-body active" aria-current="page">Bills & Records</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-md-6 d-flex justify-content-center animated fadeIn">
                    <img class="img-fluid" src="img/billss.jpg" height="400px" width="400px" alt="">
                </div>
            </div>
    </div>
    <!-- Header End -->

    <div class="container">
        <div class="row">
            <div class="col-12">
                <h4 class="text-primary">Get Rewarded for Paying Online!</h4>
                <p class="text-primary">Save more when you pay your bills online!</p>
                <p class="text-primary">Enjoy a special discount as our way of saying thank you for choosing a faster, easier payment method.</p>
                <p class="text-primary">Pay online and start saving today!</p>
            </div>
        </div>
    </div>


    <div class="container mt-5">
        <h4 class="text-primary">Employee Work Details</h4>
        <table class="table wow fadeIn"  data-wow-delay="0.1s"> 
            <thead>
                <tr>
                    <!-- <th>ID</th> -->
                    <th>Booking ID</th>
                    <th>Employee ID</th>
                    <th>User ID</th>
                    <th>Hours Worked</th>
                    <th>Work Status</th>
                    <th>Payment Status</th>
                    <th>Total Amount</th>
                    <th>Created At</th>
                    <th>Action</th>
                    <th>Apply Discount</th>
                </tr>
            </thead>
            <tbody>
            <?php 
                // SQL query to fetch work details for the logged-in user
                $sql = "SELECT id, booking_id, employee_id, user_id, hours_worked, work_status, payment_status, total_amount, created_at
                        FROM employee_work
                        WHERE user_id = ?"; // Use a placeholder for the user_id

                // Prepare the statement
                $stmt = $mysqli->prepare($sql);
                
                // Bind the user_id parameter
                $stmt->bind_param("i", $logged_in_user_id);

                // Execute the statement
                $stmt->execute();

                // Get the result
                $result = $stmt->get_result();

                if (!$result) {
                    die("Query failed: " . $mysqli->error);
                } elseif ($result->num_rows == 0) {
                    echo "<tr><td colspan='11'>No records found.</td></tr>";
                } else {
                    while ($row = $result->fetch_assoc()):
                    // Check if payment status is not 'paid'
                    $showPayButton = $row['payment_status'] !== 'paid';
    
            ?>
                        <tr>
                            <!-- <td><?php echo htmlspecialchars($row['id']); ?></td> -->
                            <td><?php echo htmlspecialchars($row['booking_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['employee_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['user_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['hours_worked']); ?></td>
                            <td><?php echo htmlspecialchars($row['work_status']); ?></td>
                            <td><?php echo htmlspecialchars($row['payment_status']); ?></td>
                            <td><?php echo htmlspecialchars($row['total_amount']); ?></td>
                            <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                            <td>
                                <?php if ($showPayButton): ?>
                                    <a href="payment.php?id=<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-sm btn-primary">Pay</a>
                                    <?php else: ?>
                                        Paid Already
                                <?php endif; ?>
                            </td>
                            <!-- <td><a href="payment.php?id=<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-sm btn-primary">Pay</a></td> -->
                            <td><a href="apply_discount.php?id=<?php echo htmlspecialchars($row['id']); ?>" class="discount btn btn-outline-primary  btn-sm w-100">Apply Disc</a></td>
                        </tr>
            <?php 
                    endwhile; 
                }

                // Close the statement and connection
                $stmt->close();
                $mysqli->close(); 
            ?>
            </tbody>
        </table>
    </div>


    <?php
    include 'includes/footer.php'; 
    ?>
</body>
</html>