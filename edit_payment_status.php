<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Database connection
    include 'config/db_connection.php';

    // Get the data from the form
    $paymentStatusId = intval($_POST['id']);
    $newStatus = $_POST['status'];

    // Update query
    $sql = "UPDATE employee_work SET payment_status = ? WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('si', $newStatus, $paymentStatusId);

    if ($stmt->execute()) {
        echo "Payment status updated successfully";
        header('Location: bills_Record.php');
    } else {
        echo "Error updating payment status: " . $mysqli->error;
    }

    // Close statement
    $stmt->close();

    // Close connection
    $mysqli->close();
}
?>
