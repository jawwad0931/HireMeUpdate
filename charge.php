<?php 
session_start();

$user_id = $_SESSION['id'];
echo $user_id;

require 'vendor/autoload.php';
require 'config/db_connection.php'; // Include your database connection 

\Stripe\Stripe::setApiKey('sk_test_51PoAkk2LBNlbJVWrAw7XwV1cNyem8JDj71qda0429gPXhjrY1pliqZjcfAdaUISMrA7YRhPa19OJXP6gr5GUwtX200O3Sd0MX5'); // Replace with your actual Secret Key

if (isset($_POST['stripeToken'])) {
    $token = $_POST['stripeToken'];
    $name = $_POST['name']; // Collecting the name from the form
    $email = $_POST['email']; // Collecting the email from the form
    $amount = $_POST['amount'] * 100; // Stripe requires the amount in cents
    $userId = $_POST['user_id']; // Collecting user_id from the form

    try {
        // Create charge with Stripe
        $charge = \Stripe\Charge::create([
            'amount' => $amount, // Amount in cents
            'currency' => 'pkr',
            'description' => 'Payment from ' . $name . ' (' . $email . ')', // Including the name and email in the description
            'source' => $token,
            'receipt_email' => $email,
        ]);

        // Prepare and bind
        $stmt = $mysqli->prepare("INSERT INTO payments (amount, currency, description, stripe_charge_id, receipt_email, name, created_at, user_id) VALUES (?, ?, ?, ?, ?, ?, NOW(), ?)");
        $stmt->bind_param('dsssssi', $amount, $charge->currency, $charge->description, $charge->id, $email, $name, $userId); // Binding the user_id

        // Execute the statement and check for success
        if ($stmt->execute()) {
            $_SESSION['success'] = "Payment Successful!";
            header("Location: payment.php"); 
            exit();
        } else {
            $_SESSION['error'] = "Payment Unsuccessful. Please try again.";
            header("Location: error.php"); // Redirect to an error page or display an error message
            exit();
        }

    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
    } finally {
        // Close connections
        $stmt->close();
        $mysqli->close();
    }
} else {
    echo "No payment token was provided!";
}
?>