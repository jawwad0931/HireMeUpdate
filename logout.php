
<?php
// pehlay session start kardiya hai
session_start();
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session
header("Location: index.php"); // Redirect to login page
exit();
?>