<?php

// File: otp_request.php
// ---------------------------------------PHP MAILER START----------------------------------------------
require 'vendor/autoload.php';
require 'config/db_connection.php'; // Ensure this uses MySQLi

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Start the session
session_start();
// name
// $name = $_SESSION['username'];
// echo $name; 





// Check if the user is logged in and has a valid session
if (!isset($_SESSION['id'])) {
    header("Location: index.php"); // Redirect to login page if not logged in
    exit;
}



// Function to generate OTP
function generateOTP($length = 6) {
    return substr(str_shuffle('0123456789'), 0, $length);
}

// Send OTP email
function sendOTP($email, $otp) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'kj768978@gmail.com'; // Replace with your SMTP username
        $mail->Password = 'laap csaj buap qeae';// Replace with your SMTP app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('kj768978@gmail.com', 'Hire Me'); // Ensure this email is verified with your SMTP provider
        $mail->addAddress($email);

        // Fetch the user's name from the session
        $name = $_SESSION['username'];

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Code';
        $mail->Body    = "<p>Hello $name,</p><p>Your OTP code is: <strong>$otp</strong></p>";
        $mail->AltBody = "Hello $name, Your OTP code is: $otp";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}
// ---------------------------------------PHP MAILER END----------------------------------------------

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $otp = generateOTP();
    $userId = $_SESSION['id']; // Get user ID from session
    $expiresAt = date('Y-m-d H:i:s', strtotime('+10 minutes'));

    if (sendOTP($email, $otp)) {
        // Save OTP to database
        $sql = "INSERT INTO otp_messages (user_id, otp_code, expires_at) VALUES (?, ?, ?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("iss", $userId, $otp, $expiresAt);

        if ($stmt->execute()) {
            // Redirect to validate_otp.php with email as a parameter
            header("Location: validate_otp.php?email=" . urlencode($email));
            exit;
        } else {
            $message = "Failed to save OTP.";
        }

        $stmt->close();
    } else {
        $message = "Failed to send OTP.";
    }

    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- dobara redirect se bachne ke liye lagaya gaya hai -->
    <script type="text/javascript">
    window.history.forward();
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HireMe</title>
    <!-- Include Bootstrap CSS -->
    <?php include 'includes/header.php'; ?>

</head>
<body class="d-flex justify-content-center align-items-center vh-100 bg-white">
    <!-- Centered content -->
    <div class="text-center border p-3">
        <h2 class="text-primary">Request OTP</h2>

        <?php if (!empty($message)): ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>

        <form action="" method="post" class="mt-4">
            <div class="mb-3">
                <label for="email" class="form-label text-primary">Enter your email address:</label>
                <input type="email" id="email" name="email" class="form-control w-100" required>
            </div>
            <button type="submit" class="btn btn-primary btn-sm d-flex justify-content-start">Get OTP</button>
        </form>
    </div>

    <!-- Include Bootstrap JS (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
