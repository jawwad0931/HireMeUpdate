<?php
include 'config/db_connection.php';
include 'includes/header.php';

session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

// Handle delete operation
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM feedback WHERE id = '$delete_id'";
    if ($mysqli->query($delete_sql) === TRUE) {
        echo "<div class='alert alert-success'>Feedback deleted successfully.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error deleting feedback: " . $mysqli->error . "</div>";
    }
}

// Fetch all feedback
$sql = "SELECT * FROM feedback";
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
        *{
            color: #00B98E;
        }
        .btn-danger {
            background-color: #dc3545;
            border: none;
        }
        .btn-danger:hover {
            background-color: #c82333;
        }
        .alert {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4 text-primary">Feedback List</h2>
        <?php
        if (isset($delete_msg)) {
            echo $delete_msg;
        }
        ?>
        <table class="table">
            <thead>
                <tr>
                    <!-- <th>ID</th> -->
                    <th>User ID</th>
                    <th>Feedback</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- <td>" . htmlspecialchars($row["id"]) . "</td> -->
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <td>" . htmlspecialchars($row["user_id"]) . "</td>
                            <td>" . htmlspecialchars($row["feedback"]) . "</td>
                            <td>" . htmlspecialchars($row["created_at"]) . "</td>
                            <td><a href='admin_feedback_table.php?delete_id=" . htmlspecialchars($row["id"]) . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this feedback?\");'>Delete</a></td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center'>No feedback found</td></tr>";
                }
                $mysqli->close();
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
