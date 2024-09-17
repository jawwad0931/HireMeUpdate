<?php
session_start();
include 'config/db_connection.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'employee') {
    header("Location: index.php");
    exit();
}

// Fetching user, employee, and booking data
$userResult = $mysqli->query("SELECT id, username FROM users");
if (!$userResult) {
    die("Query failed: " . $mysqli->error);
}

$employeeResult = $mysqli->query("SELECT id, skills, amount FROM employees");
if (!$employeeResult) {
    die("Query failed: " . $mysqli->error);
}

$bookingResult = $mysqli->query("SELECT booking_id FROM bookings");
if (!$bookingResult) {
    die("Query failed: " . $mysqli->error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $employee_id = $_POST['employee_id'];
    $booking_id = $_POST['booking_id'];
    $hours_worked = $_POST['hours_worked'];
    $work_status = $_POST['work_status'];
    $payment_status = $_POST['payment_status'];

    // Fetch the amount from the database instead of hourly_rate
    $rateQuery = "SELECT amount FROM employees WHERE id = ?";
    $stmtRate = $mysqli->prepare($rateQuery);
    $stmtRate->bind_param("i", $employee_id);
    $stmtRate->execute();
    $resultRate = $stmtRate->get_result();
    $rowRate = $resultRate->fetch_assoc();
    $hourly_rate = $rowRate['amount']; // Updated column name

    // Calculate total amount
    $total_amount = $hours_worked * $hourly_rate;

    $sql = "INSERT INTO employee_work (booking_id, employee_id, user_id, hours_worked, work_status, payment_status, total_amount)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $mysqli->error);
    }
    $stmt->bind_param("iiidssd", $booking_id, $employee_id, $user_id, $hours_worked, $work_status, $payment_status, $total_amount);

    if ($stmt->execute()) {
        // echo "<div class='alert alert-success'>New Bill created successfully</div>";
        echo "<script>alert('New Bill Create')</script>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
}






// Function to update booking status
function updateBookingStatus($mysqli, $booking_id, $status)
{
    $update_sql = "UPDATE bookings SET status = ? WHERE booking_id = ?";
    $stmt_update = $mysqli->prepare($update_sql);
    if (!$stmt_update) {
        die("Prepare failed: " . $mysqli->error);
    }
    $stmt_update->bind_param("si", $status, $booking_id);
    $stmt_update->execute();
    $stmt_update->close();
}

// Handle status update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['booking_id']) && isset($_POST['status'])) {
    $booking_id = $_POST['booking_id'];
    $status = $_POST['status'];

    updateBookingStatus($mysqli, $booking_id, $status);

    header("Location: booking_users.php");
    exit;
}




?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>HireMe</title>
    <?php include 'includes/header.php'; ?>
    <style>
        .card {
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .card img {
            max-height: 150px;
            object-fit: cover;
        }

        .labour_image {
            background-image: url('img/removeBg.png');
            height: 650px !important;
            width: 100%;
            background-size: contain;
            background-repeat: no-repeat;
        }

        .labour_desc {
            font-family: 'Poppins', Arial, Helvetica, sans-serif;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }
    </style>
</head>

<body class="bg-white">
    <div class="container mt-5">
        <div class="row">
            <div class="col-lg-4">
                <h1 class="text-primary">Make Your Bill</h1>

                <?php 
                    // $employeeQuery = "SELECT id, amount FROM employees";
                    // $employeeResult = $mysqli->query($employeeQuery);
                    // if ($employeeResult) {
                    //     while ($rows = $employeeResult->fetch_assoc()) {
                    //         echo "Employee ID: " . htmlspecialchars($rows['id']) . "<br>";
                    //         echo "Amount: " . htmlspecialchars($rows['amount']) . "<br><br>";
                    //     }
                    // } else {
                    //     echo "Query failed: " . $mysqli->error;
                    // }
                ?>

                <form class="wow fadeInUp" action="" method="post">
                    <div class="row">
                        <div class="col-3">
                            <div class="form-group">
                                <label for="user_id">User Name:</label>
                                <select id="user_id" name="user_id" class="form-control" required>
                                    <?php while ($row = $userResult->fetch_assoc()): ?>
                                        <option value="<?php echo htmlspecialchars($row['id']); ?>">
                                            <?php echo htmlspecialchars($row['username']); ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>

<!-- ---------------------------------------------------------------------------------------------------- -->






                        <div class="col-3">
                            <div class="form-group">
                                <label for="employee_id">your Id:</label>
                                <select id="employee_id" name="employee_id" class="form-control" required onchange="updateHourlyRate()">
                                    <?php while ($row = $employeeResult->fetch_assoc()): ?>
                                        <option value="<?php echo htmlspecialchars($row['id']); ?>"
                                                data-amount="<?php echo htmlspecialchars($row['amount']); ?>">
                                            <?php echo htmlspecialchars($row['id']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                                <input type="hidden" id="hourly_rate" name="hourly_rate">
                            </div>
                        </div>
                        <script>
                        function updateHourlyRate() {
                            // Get the selected option
                            var selectElement = document.getElementById('employee_id');
                            var selectedOption = selectElement.options[selectElement.selectedIndex];
                            
                            // Get the amount from the selected option
                            var amount = selectedOption.getAttribute('data-amount');
                            
                            // Set the value of the hidden input field
                            document.getElementById('hourly_rate').value = amount;
                            
                            // Recalculate the total amount
                            updateTotalAmount();
                        }

                        function updateTotalAmount() {
                            // Get the hourly rate
                            var hourlyRate = parseFloat(document.getElementById('hourly_rate').value) || 0;
                            
                            // Get the number of hours worked
                            var hoursWorked = parseFloat(document.getElementById('hours_worked').value) || 0;
                            
                            // Calculate the total amount
                            var totalAmount = hourlyRate * hoursWorked;
                            
                            // Update the total amount display
                            document.getElementById('total_amount').textContent = totalAmount.toFixed(2);
                        }

                        // Call updateTotalAmount when the page loads to ensure total is correct if there's already a selected employee
                        document.addEventListener('DOMContentLoaded', updateTotalAmount);
                        </script>









<!-- ------------------------------------------------------------------------------------------------------------- -->

                        <div class="col-3">
                            <div class="form-group">
                                <label for="booking_id">Booking Id:</label>
                                <select id="booking_id" name="booking_id" class="form-control" required>
                                    <?php while ($row = $bookingResult->fetch_assoc()): ?>
                                        <option value="<?php echo htmlspecialchars($row['booking_id']); ?>">
                                            <?php echo htmlspecialchars($row['booking_id']); ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="hours_worked">Hours Worked:</label>
                        <input type="number" id="hours_worked" name="hours_worked" class="form-control" oninput="updateTotalAmount()" min="0" step="0.01">
                    </div>


                    <div class="form-group">
                        <label for="work_status">Work Status:</label>
                        <select id="work_status" name="work_status" class="form-control" required>
                            <option value="work_done">Work Done</option>
                            <option value="work_pending">Work Pending</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="payment_status">Payment Status:</label>
                        <select id="payment_status" name="payment_status" class="form-control" required>
                            <option value="paid">Paid</option>
                            <option value="unpaid">Unpaid</option>
                        </select>
                    </div>

                    
                    <div class="form-group">
                        <label for="total_amount">Total Amount:</label>
                        <span id="total_amount" name="total_amount">0.00</span>
                    </div>

                    <button type="submit" class="btn btn-primary">Make Bill</button>
                </form>
                <hr />                         

            </div>
            <div class="col-lg-8">
                <div class="labour_image">
                    <h2 class="labour_desc text-danger">Find</h2>
                    <h2 class="labour_desc text-info">Best</h2>
                    <h2 class="labour_desc text-success">Labour</h2>
                    <h2 class="labour_desc text-warning">In</h2>
                    <h2 class="labour_desc text-secondary">Youself</h2>
                </div>
            </div>
        </div>

       

        <h2 class="my-1 text-primary mb-4">Employee Work Records & Billings</h2>

  <div class="row">
    <?php
    // Fetching employee work records and billings
    // Assuming $mysqli is your mysqli connection
    $workResult = $mysqli->query("
        SELECT ew.booking_id, ew.employee_id, ew.user_id, ew.hours_worked, ew.work_status, ew.payment_status, ew.total_amount, ew.created_at,
               u.username AS user_name, e.username AS employee_name, emp.amount AS employee_amount
        FROM employee_work ew
        LEFT JOIN users u ON ew.user_id = u.id
        LEFT JOIN users e ON ew.employee_id = e.id
        LEFT JOIN employees emp ON ew.employee_id = emp.id
    ");

    if (!$workResult) {
        die("Query failed: " . $mysqli->error);
    }
    ?>

    <?php if ($workResult->num_rows > 0): ?>
        <?php while ($row = $workResult->fetch_assoc()): ?>
            <div class="col-lg-3 col-md-4 mb-4">
                <div class="card border-primary">
                    <div class="card-body">
                        <h5 class="card-title mb-4">
                            <span class="badge bg-primary">Booking ID:
                                <?php echo htmlspecialchars($row['booking_id']); ?></span>
                        </h5>
                        <div class="d-flex justify-content-between">
                            <h6 class="card-subtitle mb-2 text-muted">Employee
                                Id: <?php echo htmlspecialchars($row['employee_id']); ?></h6>
                            <h6 class="card-subtitle mb-2 text-muted">User:
                                <?php echo htmlspecialchars($row['user_name']); ?></h6>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <p class="card-text">Hours Worked: <?php echo htmlspecialchars($row['hours_worked']); ?>
                                hours</p>
                            <p class="card-text">Work Status: <span
                                    class="badge <?php echo $row['work_status'] == 'Completed' ? 'bg-success' : 'bg-warning'; ?>">
                                    <?php echo htmlspecialchars($row['work_status']); ?>
                                </span></p>
                            <p class="card-text">Payment Status: <span
                                    class="badge <?php echo $row['payment_status'] == 'Paid' ? 'bg-success' : 'bg-danger'; ?>">
                                    <?php echo htmlspecialchars($row['payment_status']); ?>
                                </span></p>


                            <?php $government_tax = 50; ?>
                            <p class="card-text">
                                <span>
                                    <?php
                                    // Get the total amount from the database row
                                    $total_amount = htmlspecialchars($row['total_amount']);
                                    
                                    echo "<p>Actual Amount: $total_amount</p>";

                                    echo "<p>Government Tax: $government_tax</p>";

                                    // Calculate the total amount including tax
                                    $total_amount_with_tax = $total_amount + $government_tax;
                                    
                                    // Display the total amount with tax
                                    echo 'Total with tax ' . $total_amount_with_tax;
                                    ?>
                                </span>
                            </p>   
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <span class="">User Invoice</span>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No records found.</p>
    <?php endif; ?>
</div>

        <!-- <h2 class="mt-5 text-primary">Employee Details</h2> -->
        <!-- <div class="table-responsive">
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
                </tbody>
            </table>
        </div> -->
        
    </div>                  
    <?php 
    // Close the database connection
    $stmt_employee->close();
    $mysqli->close();
    ?>
    <?php include 'includes/footer.php'; ?> 
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>