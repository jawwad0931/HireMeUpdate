<?php
// Database connection
include 'config/db_connection.php';

// Retrieve the ID from the query string
if (isset($_GET['id'])) {
    $paymentStatusId = intval($_GET['id']); // Sanitize input

    // Fetch the current payment status
    $sql = "SELECT payment_status FROM employee_work WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('i', $paymentStatusId);
    $stmt->execute();
    $stmt->bind_result($currentStatus);
    $stmt->fetch();

    // Display the form
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>HireMe</title>
        <?php include 'includes/header.php'; ?>
        <style>
        .full-height {
            height: 100vh;
        }
    </style>
    </head>
    <body class="bg-white">
    <div class="container full-height d-flex align-items-center justify-content-center">
        <div class="row w-100 d-flex align-items-center justify-content-center">
            <div class="col-12 col-md-8 col-lg-3 border p-4 bg-white">
                <h3 class="text-primary fw-light">Update Payment Status</h3>
                <form action="edit_payment_status.php" method="post">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($paymentStatusId); ?>">
                    <div class="form-group">
                        <label for="status">Payment Status:</label>
                        <select name="status" id="status" class="form-control">
                            <option value="Paid" <?php if ($currentStatus === 'Paid') echo 'selected'; ?>>Confirm</option>
                            <option value="Unpaid" <?php if ($currentStatus === 'Pending') echo 'selected'; ?>>UnConfirm</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm mt-3">Update Status</button>
                </form>
            </div>
        </div>
    </div>
    </body>
    </html>
    <?php

    // Close statement
    $stmt->close();
} else {
    echo "No ID provided.";
}

// Close connection
$mysqli->close();
?>
