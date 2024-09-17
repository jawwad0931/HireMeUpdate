<?php
include 'config/db_connection.php';
include 'includes/header.php';

session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$sql = "SELECT e.id, u.username, u.age, e.skills, c.city, e.status, e.img 
        FROM employees e 
        JOIN users u ON e.user_id = u.id
        JOIN cities c ON e.city_id = c.id";
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
        .table img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <h2 class="text-primary">Employee List</h2>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Age</th>
                <th>Skills</th>
                <th>City</th>
                <th>Status</th>
                <th>Image</th>
                <!-- <th>Actions</th> -->
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['username']}</td>
                            <td>{$row['age']}</td>
                            <td>{$row['skills']}</td>
                            <td>{$row['city']}</td>
                            <td>{$row['status']}</td>
                            <td><img src='{$row['img']}' alt='Profile Image'></td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='8'>No employees found</td></tr>";
            }
            $mysqli->close();
            ?>
            <!-- <td>
            <a href='delete.php?employee_delete_id={$row['id']}' class='btn btn-danger btn-sm btn-action' onclick='return confirm(\"Are you sure you want to delete this employee?\")'>Drop Employee</a>
            </td> -->
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
