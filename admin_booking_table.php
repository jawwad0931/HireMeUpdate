<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

include 'config/db_connection.php';

$sql = "SELECT b.*, u.username 
        FROM bookings b 
        LEFT JOIN users u ON b.user_id = u.id
        ORDER BY b.created_at DESC";
$result = $mysqli->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
    <link href="img/logo.png" rel="icon" type="image/png">
    <title>HireMe</title>
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
            color: #00B98E;
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

<div class="container table-container py-5">
    <h2 class="text-primary">Bookings List</h2>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Booking ID</th>
                <th>Employee ID</th>
                <th>User ID</th>
                <th>User Address</th>
                <th>Timing</th>
                <th>Day</th>
                <th>Description</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['booking_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['employee_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['user_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['user_address']); ?></td>
                    <td><?php echo htmlspecialchars($row['timing']); ?></td>
                    <td><?php echo htmlspecialchars($row['day']); ?></td>
                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                    <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                    <!-- <td><a href="delete.php?emp_booking_id=' . $row['booking_id'] . '" class="btn btn-danger btn-sm">Delete Booking</a>'</td> -->
                    <td><a href="delete.php?emp_booking_id=<?php echo urlencode($row['booking_id']); ?>" class="btn btn-danger btn-sm">Delete Booking</a></td>
                   
                </tr>
            <?php } } else { ?>
                <tr><td colspan="9" class="text-center">No bookings found.</td></tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php include 'includes/labor_footer.php'; ?>

<!-- JavaScript Libraries -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="lib/wow/wow.min.js"></script>
<script src="lib/easing/easing.min.js"></script>
<script src="lib/waypoints/waypoints.min.js"></script>
<script src="lib/owlcarousel/owl.carousel.min.js"></script>

<!-- Template Javascript -->
<script src="js/main.js"></script>
</body>
</html>
<?php
$mysqli->close();
?>
