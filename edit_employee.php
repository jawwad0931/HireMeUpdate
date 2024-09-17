<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

include 'config/db_connection.php'; // Include the database connection script

// Handle form submission to update role
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $role = $_POST['role'];

    // Update the role in the users table
    $update_sql = "UPDATE users SET role = '$role' WHERE id = $user_id";

    if ($mysqli->query($update_sql) === TRUE) {
        echo "Role updated successfully";
        header("location: admin_user_table.php");
        exit;
    } else {
        echo "Error updating role: " . $mysqli->error;
    }

    $mysqli->close();
    exit; // Stop further execution after handling POST request
}

// Fetch user data based on user_id for initial display
$user_id = $_GET['id'];
$sql = "SELECT id, username, role FROM users WHERE id = $user_id";
$result = $mysqli->query($sql);
$user = $result->fetch_assoc();

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User - <?php echo $user['username']; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <?php include 'includes/header.php'; ?>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #effdf5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 400px;
            width: 100%;
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
        }
        label {
            display: block;
            margin-bottom: 10px;
            font-weight: 500;
            color: #555;
            text-align: left;
        }
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            background-color: #f9f9f9;
        }
        
        
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-primary">Edit User - <?php echo $user['username']; ?></h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
        <label for="role" class="text-primary">Role</label>
        <select id="role" name="role" class="text-primary">
            <option value="labour" class="text-primary" <?php echo ($user['role'] === 'labour') ? 'selected' : ''; ?>>Labour</option>
            <option value="admin" class="text-primary" <?php echo ($user['role'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
            <option value="user" class="text-primary" <?php echo ($user['role'] === 'user') ? 'selected' : ''; ?>>User</option>
        </select>
        <input type="submit" class="btn btn-sm btn-primary d-flex justify-content-start" value="Update Role">
    </form>
</div>

</body>
</html>
