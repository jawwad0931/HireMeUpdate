<?php
session_start();
include 'config/db_connection.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Ensure the user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

// Get the logged-in user's ID
$logged_in_user_id = $_SESSION['id'];

// Get the id from the URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Query to fetch the total amount for the specific ID
$query = "SELECT total_amount FROM employee_work WHERE id = ? AND user_id = ?";
$stmt = $mysqli->prepare($query);
if ($stmt) {
    $stmt->bind_param("ii", $id, $logged_in_user_id);
    $stmt->execute();
    $stmt->bind_result($totalAmount);

    // Fetch the result
    if ($stmt->fetch()) {
        $totalAmount = htmlspecialchars($totalAmount);
    } else {
        $totalAmount = 0; // If no record is found, set totalAmount to 0
    }

    // Close the statement
    $stmt->close();
} else {
    die("Error in preparing statement: " . $mysqli->error);
}

// Handle discount code submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['discountCode'])) {
    $discountCode = trim($_POST['discountCode']);

    // Fetch discount details and check if it's valid for the user
    $stmt = $mysqli->prepare("
        SELECT d.discount_percentage 
        FROM discounts d 
        INNER JOIN user_discounts ud ON d.id = ud.discount_id 
        WHERE d.discount_code = ? 
        AND ud.user_id = ? 
        AND d.start_date <= NOW() 
        AND d.end_date >= NOW()
    ");
    
    if ($stmt) {
        $stmt->bind_param("si", $discountCode, $logged_in_user_id);
        $stmt->execute();
        $stmt->bind_result($discountPercentage);
        $stmt->fetch();
        $stmt->close();

        if ($discountPercentage !== null) {
            // Calculate the new total after applying the discount
            $discountAmount = $totalAmount * ($discountPercentage / 100);
            $newTotal = $totalAmount - $discountAmount;

            // Return success response
            echo json_encode(['success' => true, 'new_total' => round($newTotal, 2)]);
        } else {
            // Return error response
            echo json_encode(['success' => false, 'message' => 'Invalid or expired discount code.']);
        }
    } else {
        // Return error if the statement preparation fails
        echo json_encode(['success' => false, 'message' => 'An error occurred. Please try again.']);
    }
    
    $mysqli->close();
    exit(); // End the script to prevent further output
}


$mysqli->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hire Me</title>
    <?php include 'includes/header.php'; ?>
</head>
<style>
    body {
        background-color: #effdf5;
    }
</style>
<body>
    <div class="container mt-5 d-flex align-items-center justify-content-center">
        <div class="card w-50 p-4" style="margin-top:100px;">
            <h2 class="text-primary">Apply Discount</h2>
            <hr />
            
            <!-- Total Amount -->
            <p><strong>Total Amount:</strong> <span id="totalAmount"><?php echo isset($totalAmount) ? $totalAmount : '0'; ?></span></p>

            <!-- Discount Code Form -->
            <form id="discountForm" method="POST">
                <div class="mb-3">
                    <label for="discountCode" class="form-label">Enter Discount Code</label>
                    <input type="text" class="form-control" id="discountCode" name="discountCode" required>
                </div>
                <button type="submit" class="btn btn-primary">Apply Discount</button>
            </form>

            <!-- Area for displaying success or error message -->
            <div id="responseMessage" class="mt-3"></div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#discountForm').on('submit', function(event) {
            event.preventDefault();  // Prevent the form from reloading the page

            var discountCode = $('#discountCode').val();  // Get the discount code value

            // Make the AJAX request to apply the discount
            $.ajax({
                url: '',  // The current page is handling the form submission
                type: 'POST',
                data: {
                    discountCode: discountCode
                },
                success: function(response) {
                    var jsonResponse = JSON.parse(response);  // Parse the JSON response
                    
                    if (jsonResponse.success) {
                        // If the discount is successfully applied, update the total and display success message
                        $('#totalAmount').text(jsonResponse.new_total);  // Update the total amount
                        $('#responseMessage').html('<div class="alert alert-success mt-3">Discount applied! New total: ' + jsonResponse.new_total + '</div>');  // Show success message
                        
                        // Set timeout to redirect to the payment page after 3 seconds (3000 ms)
                        setTimeout(function() {
                            window.location.href = 'payment.php';  // Redirect to payment page
                        }, 3000);  // 3-second delay
                    } else {
                        // Show error message if the discount code is invalid
                        $('#responseMessage').html('<div class="alert alert-danger mt-3">' + jsonResponse.message + '</div>');
                    }
                },
                error: function() {
                    // Show error message if there's an issue with the request
                    $('#responseMessage').html('<div class="alert alert-danger mt-3">An error occurred while applying the discount.</div>');
                }
            });
        });
    });
    </script>
</body>
</html>


