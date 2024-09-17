<?php
session_start();
include('config/db_connection.php');

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

// Check if delete_id is set in the GET request
if (isset($_GET['delete_id'])) {
    $profile_delete_id = intval($_GET['delete_id']);
} else {
    echo "No user ID specified.";
    exit();
}

// Fetch the current profile image
$query = "SELECT profileimage FROM users WHERE id = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $profile_delete_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user) {
    $profileimage = $user['profileimage'];

    // Delete the image file from the server if it exists
    if ($profileimage && file_exists($profileimage)) {
        unlink($profileimage);
    }

    // Delete the user record from the database
    $delete_query = "DELETE FROM users WHERE id = ?";
    $stmt_delete = $mysqli->prepare($delete_query);
    $stmt_delete->bind_param("i", $profile_delete_id);

    if ($stmt_delete->execute()) {
        // Destroy the session to log the user out if they deleted their own profile
        if ($_SESSION['id'] == $profile_delete_id) {
            session_unset();
            session_destroy();
            header("Location: index.php");
        } else {
            // Redirect to a different page after deletion
            header("Location: manage_profiles.php"); // Change this to your desired page
        }
        exit();
    } else {
        echo "Error deleting profile: " . $stmt_delete->error;
    }
} else {
    echo "No user found.";
}

// Close prepared statements
$stmt->close();
$stmt_delete->close();

// Close the database connection
$mysqli->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Delete Profile</title>
</head>
<body>
    <p>Your profile has been permanently deleted.</p>
    <a href="index.php">Return to Home Page</a>
</body>
</html>
