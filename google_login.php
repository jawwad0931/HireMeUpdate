
<!------------------------------------- this is view page of update api key in admin panel -------------------------------------------->

<?php
session_start();

include 'config/db_connection.php';

// Fetch the API keys from the database
$sql = "SELECT client_id, client_secret, redirect_uri FROM api_keys WHERE id = 1";
$result = $mysqli->query($sql);
$api_keys = $result->fetch_assoc();

$client_id = $api_keys['client_id'];
$client_secret = $api_keys['client_secret'];
$redirect_uri = $api_keys['redirect_uri'];

// Google OAuth 2.0 endpoint for requesting an access token
$auth_url = 'https://accounts.google.com/o/oauth2/auth?response_type=code&client_id=' . $client_id . '&redirect_uri=' . $redirect_uri . '&scope=email profile';

header('Location: ' . $auth_url);
exit();
?>