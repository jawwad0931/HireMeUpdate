<?php
include 'config/db_connection.php';
include 'includes/header.php';

session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

// Fetch combined users and OTP messages data
$sql = "SELECT 
            u.id as user_id, 
            u.username, 
            u.email, 
            u.age, 
            u.contact, 
            u.profileimage, 
            u.AcceptTerm, 
            u.created_at as user_created_at, 
            u.role, 
            u.expires_at as user_expires_at, 
            GROUP_CONCAT(o.id) as otp_ids, 
            GROUP_CONCAT(o.otp_code) as otp_codes, 
            MAX(o.expires_at) as otp_expires_at, 
            MAX(o.created_at) as otp_created_at
        FROM users u
        LEFT JOIN otp_messages o ON u.id = o.user_id
        GROUP BY u.id";
$result = $mysqli->query($sql);
?>

<!DOCTYPE html>
<html>
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
        * {
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
<div class="container mt-4">
    <h2 class="text-primary">Users and OTP Messages Data</h2>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>Age</th>
                <th>Contact</th>
                <th>Profile Image</th>
                <th>Accept Terms</th>
                <th>OTP IDs</th>
                <th>OTP Codes</th>
                <th>OTP Expires At</th>
                <th>OTP Created At</th>
                <!-- <th>Actions</th> -->
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['username']}</td>
                            <td>{$row['email']}</td>
                            <td>{$row['age']}</td>
                            <td>{$row['contact']}</td>
                            <td><img src='{$row['profileimage']}' alt='Profile Image' style='width: 50px; height: 50px;' class='rounded-circle'></td>
                            <td>{$row['AcceptTerm']}</td>
                            <td>{$row['otp_ids']}</td>
                            <td>{$row['otp_codes']}</td>
                            <td>{$row['otp_expires_at']}</td>
                            <td>{$row['otp_created_at']}</td>
                            <!-- <td>
                            <a href='delete.php?otp_delete_id=<?php echo $id; ?>' class='btn btn-danger btn-sm px-3 text-white'>Delete Profile</a>
                            </td> -->
                            </tr>";
                }
            } else {
                echo "<tr><td colspan='10'>No users or OTP messages found</td></tr>";
            }
            ?>
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
