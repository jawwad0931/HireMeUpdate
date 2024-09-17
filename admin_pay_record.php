<?php
// Include database configuration file
include 'config/db_connection.php'; // Ensure this file contains your database connection details

session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

// Fetch records from the payments table
$sql = "SELECT id, amount, currency, description, stripe_charge_id, receipt_email, created_at FROM payments";
$result = $mysqli->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
    <title>Hire Me</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Inter:wght@700;800&display=swap" rel="stylesheet">
    
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    <style>
        *{
            color: #00b98e;
        }
        .btn-action {
            margin-right: 5px;
            transition: transform 0.3s;
        }
        .btn-action:hover {
            transform: scale(1.1);
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4 text-primary">Payments Records</h2>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Amount</th>
                <th>Currency</th>
                <th>Description</th>
                <th>Stripe Charge ID</th>
                <th class="">Receipt Email</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Check if there are any records
            if ($result->num_rows > 0) {
                // Output data for each row
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                    echo "<td>Rs" . number_format($row['amount'] / 100, 2) . "</td>"; // Amount in dollars
                    echo "<td>" . htmlspecialchars($row['currency']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['stripe_charge_id']) . "</td>";
                    echo "<td class='text-danger fw-bold'>" . htmlspecialchars($row['receipt_email']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No records found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
</body>
</html>

<?php
// Close the database connection
$mysqli->close();
?>
