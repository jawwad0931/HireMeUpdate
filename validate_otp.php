<?php
require 'vendor/autoload.php';
require 'config/db_connection.php'; // Ensure this uses MySQLi
session_start();

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $inputOtp = $_POST['otp'];

    // Fetch the user ID and role based on email
    $sql = "SELECT id, role FROM users WHERE email = ?";
    $stmt = $mysqli->prepare($sql);
    
    if (!$stmt) {
        die("Prepare failed: " . $mysqli->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($userId, $role);
    $stmt->fetch();

    if ($stmt->num_rows > 0) {
        // Fetch the OTP from the database
        $sql = "SELECT otp_code, expires_at FROM otp_messages WHERE user_id = ? ORDER BY created_at DESC LIMIT 1";
        $stmt = $mysqli->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $mysqli->error);
        }

        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($otpCode, $expiresAt);
        $stmt->fetch();

        if ($stmt->num_rows > 0) {
            // Check if the OTP is still valid
            if ($expiresAt >= date('Y-m-d H:i:s') && $otpCode === $inputOtp) {
                $_SESSION['message'] = "OTP is valid!";
                
                // Set user role in session for future access
                $_SESSION['role'] = $role;
                $_SESSION['user_id'] = $userId;

                // Redirect based on role
                if ($role === 'admin') {
                    header("Location: admin.php");
                } elseif ($role === 'user') {
                    header("Location: user.php");
                } elseif ($role === 'employee') {
                    header("Location: labour.php");
                } else {
                    $message = "Unknown role!";
                }
                exit;
            } else {
                $message = "Invalid or expired OTP.";
            }
        } else {
            $message = "OTP not found.";
        }
    } else {
        $message = "User not found.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include 'includes/header.php'; ?>
    <title>HireMe</title>
</head>
<body class="d-flex justify-content-center align-items-center vh-100 bg-white">
    <!-- Centered content -->
    <div class="text-center border p-3">
        <h2 class="text-primary">Validate OTP</h2>

        <?php if (!empty($message)): ?>
            <p><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <form action="" method="post" class="mt-4">
            <input type="hidden" name="email" value="<?php echo htmlspecialchars($_GET['email']); ?>">
            <div class="mb-3">
                <label for="otp" class="form-label text-primary">Enter OTP:</label>
                <input type="text" id="otp" name="otp" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary btn-sm d-flex justify-content-start">Validate OTP</button>
        </form>
    </div>

    <!-- Include Bootstrap JS (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
