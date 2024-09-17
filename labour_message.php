<?php
include("config/db_connection.php");
include "includes/header.php";
session_start();
if ($_SESSION['role'] !== 'employee') {
    header("Location: index.php");
    exit;
}

// Fetch only the description column from the bookings table
$sql = "SELECT `description` FROM `bookings` WHERE 1";
$result = $mysqli->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking Descriptions</title>
    <!-- Add your CSS links here -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color:   #effdf5 !important;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: auto;
            background-color:  #effdf5;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-primary">Booking Descriptions</h2>
        <?php
        if ($result->num_rows > 0) {
            echo "<ul>";
            while($row = $result->fetch_assoc()) {
                echo "<li class='bg-primary text-light animated fadeIn'>" . htmlspecialchars($row['description']) . "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No descriptions found.</p>";
        }

        $mysqli->close();
        ?>
    </div>
</body>
</html>
