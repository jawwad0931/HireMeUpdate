<!-- ========================php start ========================= -->
<?php
include 'config/db_connection.php';
include 'includes/header.php';

// Function to sanitize and validate input
function clean_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Initialize variables for form validation
$username = $email = $password = $age = $contact = $role = $profile_picture = '';
$username_err = $email_err = $password_err = $age_err = $contact_err = $role_err = $terms_accepted_err = '';
$terms_accepted = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate username
    if (empty($_POST['username'])) {
        $username_err = "Please enter a username.";
    } else {
        $username = clean_input($_POST['username']);
    }

    // Validate email
    if (empty($_POST['email'])) {
        $email_err = "Please enter an email.";
    } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $email_err = "Invalid email format.";
    } else {
        $email = clean_input($_POST['email']);
    }

    // Validate password
    if (empty($_POST['password'])) {
        $password_err = "Please enter a password.";
    } else {
        $password = clean_input($_POST['password']);
    }

    // Validate age
    if (empty($_POST['age'])) {
        $age_err = "Please enter your age.";
    } else {
        $age = clean_input($_POST['age']);
    }

    // Validate contact
    if (empty($_POST['contact'])) {
        $contact_err = "Please enter your contact information.";
    } else {
        $contact = clean_input($_POST['contact']);
    }

    // Validate role
    if (empty($_POST['role'])) {
        $role_err = "Please select a role.";
    } else {
        $role = clean_input($_POST['role']);
        if ($role !== 'user' && $role !== 'employee' && $role !== 'admin') {
            $role_err = "Invalid role selected.";
        }
    }

    // Validate terms accepted
    $terms_accepted = isset($_POST['terms_accepted']) ? 1 : 0;

    // Handle profile picture upload
    if (!empty($_FILES['profile_picture']['name'])) {
        $profile_picture = 'uploads/' . basename($_FILES['profile_picture']['name']);
        move_uploaded_file($_FILES['profile_picture']['tmp_name'], $profile_picture);
    }

    // If no errors, proceed with database insertion
    if (empty($username_err) && empty($email_err) && empty($password_err) && empty($age_err) && empty($contact_err) && empty($role_err)) {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Debugging output
        echo "DEBUG: Values being inserted:<br>";
        echo "Username: $username<br>";
        echo "Email: $email<br>";
        echo "Password: $hashed_password<br>";
        echo "Age: $age<br>";
        echo "Contact: $contact<br>";
        echo "Profile Picture: $profile_picture<br>";
        echo "Terms Accepted: $terms_accepted<br>";
        echo "Role: $role<br>";

        // Prepare SQL statement to insert user into database
        $sql = "INSERT INTO users (username, email, password, age, contact, profileimage, AcceptTerm, role) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        // Use prepared statement for security
        if ($stmt = $mysqli->prepare($sql)) {
            // Bind parameters
            $stmt->bind_param("ssssssis", $username, $email, $hashed_password, $age, $contact, $profile_picture, $terms_accepted, $role);

            // Execute statement
            if ($stmt->execute()) {
                echo "result inserted";
                header("location: index.php");
                exit;
            } else {
                echo "<p>Registration failed. Please try again later.</p>";
                echo "<script>alert('Registration failed. Please try again later.')</script";
            }

            // Close statement
            $stmt->close();
        } else {
            echo "<p>Database error. Please try again later.</p>";
            echo "<script>alert('Database error. Please try again later.')</script";
        }
    }

    // Close database connection
    $mysqli->close();
}
?>
<!-- =========================php end ========================= -->
<!-- =========================html start ========================= -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Registration</title>
</head>
<style>
    .custom-file-upload {
            background-color: #f8f9fa;
            border: 2px dashed #00B98E;
            padding: 20px;
            text-align: center;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        .custom-file-upload:hover {
            background-color: #e9ecef;
            border-color: #00A078;
        }
        .form-label {
            font-weight: bold;
            font-size: 1.2em;
            color: #007BFF;
        }
</style>
<body class="bg-white">
    <div class="container">
        <div class="form-container px-3 py-3 border rounded-0">
            <h2 class="mb-1 text-primary text-start">Registration</h2>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="py-3" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-6">
                    <div class="mb-1">
                    <label for="username" class="form-label text-primary fs-6">Username</label>
                    <input type="text" id="username" name="username" class="form-control" value="<?php echo $username; ?>" required>
                    <span class="text-danger"><?php echo $username_err; ?></span>
                </div>  
                    </div>
                    <div class="col-6">
                    <div class="mb-1">
                    <label for="email" class="form-label text-primary fs-6">Email</label>
                    <input type="email" id="email" name="email" class="form-control" value="<?php echo $email; ?>" required>
                    <span class="text-danger"><?php echo $email_err; ?></span>
                </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-6">
                    <div class="mb-1">
                    <label for="password" class="form-label text-primary fs-6">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                    <span class="text-danger"><?php echo $password_err; ?></span>
                </div>
                    </div>
                    <div class="col-6">
                    <div class="mb-1">
                    <label for="age" class="form-label text-primary fs-6">Age</label>
                    <input type="number" id="age" name="age" class="form-control" value="<?php echo $age; ?>" required>
                    <span class="text-danger"><?php echo $age_err; ?></span>
                </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                    <div class="mb-1">
                    <label for="contact" class="form-label text-primary fs-6">Contact</label>
                    <input type="text" id="contact" name="contact" class="form-control" value="<?php echo $contact; ?>" required>
                    <span class="text-danger"><?php echo $contact_err; ?></span>
                </div>
                    </div>
                    <div class="col-6">
                    <div class="mb-1">
                    <label for="role" class="form-label text-primary fs-6">Role</label>
                    <select id="role" name="role" class="form-select" required>
                        <option value="">Select Role</option>
                        <option value="user" <?php if ($role == 'user') echo 'selected'; ?>>User</option>
                        <option value="employee" <?php if ($role == 'employee') echo 'selected'; ?>>Employee</option>
                        <option value="admin" <?php if ($role == 'admin') echo 'selected'; ?>>Admin</option>
                    </select>
                    <span class="text-danger"><?php echo $role_err; ?></span>
                </div>
                    </div>
                </div>
             

            

                <!-- <div class="mb-1">
                    <label for="profile_picture" class="form-label text-primary">Profile Picture</label>
                    <input type="file" id="profile_picture" name="profile_picture" class="form-control">
                </div> -->

                <div class="mb-1 custom-file-upload mt-2">
                    <label for="profile_picture" class="form-label text-primary fs-6 fw-light">Profile Picture</label>
                    <input type="file" id="profile_picture" name="profile_picture" class="form-control" style="border: none; box-shadow: none;">
                </div>

                <div class="mb-1 mt-1 form-check">
                    <input type="checkbox" id="terms_accepted" name="terms_accepted" class="form-check-input" value="1" <?php if ($terms_accepted) echo 'checked'; ?> required>
                    <label for="terms_accepted" class="form-check-label text-primary">Accept Terms</label>
                    <span class="text-danger"><?php echo $terms_accepted_err; ?></span>
                </div>

                <button type="submit" class="btn btn-primary btn-sm w-25 text-white">Register</button><br />
                <a href="index.php" class="text-decoration-underline">Have an account lets login</a>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<!-- ========================html end ========================= -->