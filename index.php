<?php
session_start();


include 'config/db_connection.php';

if (isset($_SESSION['role'])) {
    switch ($_SESSION['role']) {
        case 'admin':
            header("Location: admin.php");
            exit;
        case 'employee':
            header("Location: labour.php");
            exit;
        default:
            header("Location: user.php");
            exit;
    }
}

$login_err = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    function clean_input($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    $username = clean_input($_POST['username']);
    $password = clean_input($_POST['password']);

    $sql = "SELECT id, username, password, role, email FROM users WHERE username = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("s", $username);

        if ($stmt->execute()) {
            $stmt->store_result();

            if ($stmt->num_rows == 1) {
                $stmt->bind_result($id, $username, $hashed_password, $role, $email);

                if ($stmt->fetch()) {
                    if (password_verify($password, $hashed_password)) {
                        $_SESSION['id'] = $id;
                        $_SESSION['username'] = $username;
                        $_SESSION['role'] = $role;
                        $_SESSION['email'] = $email;

                        // Redirect to send_otp.php
                        header("Location: send_otp.php");
                        exit;
                    } else {
                        $login_err = "Invalid username or password not exist.";
                        echo "<script>alert('Invalid username or password.')</script>";
                    }
                }
            } else {
                $login_err = "Invalid username or password not exist.";
                echo "<script>alert('Invalid username or password  not exist.')</script>";
            }
        } else {
            $login_err = "Error executing SQL statement.";
            echo "<script>alert('Error executing SQL statement.')</script>";
        }
        $stmt->close();
    } else {
        $login_err = "Error preparing SQL statement.";
        echo "<script>alert('Error preparing SQL statement.')</script>";
    }
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <script type="text/javascript">
    window.history.forward();
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include 'includes/header.php'; ?>
    <title>Login</title>
</head>
<body class="bg-white">
    <div class="container login-container">
        <div class="row d-flex align-items-center justify-content-center">
            <div class="col-md-12 col-lg-12 p-3 border">
                <h2 class="text-center text-primary">Login</h2>
                <?php if (!empty($login_err)) { ?>
                    <p class='text-danger text-center'><?php echo $login_err; ?></p>
                <?php } ?>
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                    <div class="form-group">
                        <label for="username" class="text-primary">User Name</label>
                        <input type="text" id="username" name="username" class="form-control" required>
                    </div>
                    <div class="form-group mt-4">
                        <label for="password" class="text-primary">Password</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm w-25 mt-3 text-light d-flex justify-content-center">Login</button>
                    <p class="forgot-password mt-2 text-primary">Don't have an account? <a href="register.php"
                            class="text-primary text-decoration-underline">Create a new account now</a></p>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
