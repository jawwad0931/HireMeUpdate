<?php
session_start();
if ($_SESSION['role'] !== 'employee') {
    header("Location: index.php");
    exit;
}

// Get the logged-in employee's user ID from the session
$logged_in_user_id = $_SESSION['id']; // Assuming user_id is stored in session

include 'config/db_connection.php'; // Include the database connection script

// Handle status update if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['status']) && isset($_POST['employee_id'])) {
        $newStatus = ($_POST['status'] == 'activate') ? 'deactivate' : 'activate'; // Toggle status
        $employee_id = $_POST['employee_id'];

        // Update employee status in the database
        $update_sql = "UPDATE employees SET status = '$newStatus' WHERE id = ?";
        $stmt = $mysqli->prepare($update_sql);
        $stmt->bind_param("i", $employee_id);
        if ($stmt->execute()) {
            // Redirect to avoid resubmission on page refresh
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } else {
            echo "Error updating record: " . $mysqli->error;
        }
        $stmt->close();
    }
}

// Fetch employees data including username and age from users table
$sql = "SELECT e.id, e.skills, e.city_id, e.img, e.status, e.amount, c.city, u.username, u.age
        FROM employees e
        LEFT JOIN cities c ON e.city_id = c.id
        LEFT JOIN users u ON e.user_id = u.id
        WHERE e.user_id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $logged_in_user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HireMe</title>
    <?php include 'includes/header.php'; ?>
    <style>
        .card {
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            overflow: hidden;
        }
        .card img {
            max-width: 100%;
            height: auto;
        }
        .card-body {
            text-align: center;
        }
        .card-title {
            font-size: 1.25rem;
            margin-top: 10px;
            margin-bottom: 5px;
        }
        .card-text {
            margin: 5px 0;
        }
        .btn {
            padding: 8px 16px;
            border-radius: 4px;
            text-decoration: none;
            cursor: pointer;
        }
        .btn-success {
            background-color: #28a745;
            color: #fff;
            border: none;
        }
        .btn-danger {
            background-color: #dc3545;
            color: #fff;
            border: none;
        }
        .btn-sm {
            padding: 5px 10px;
        }
        .btn:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body class="bg-white">
    <!-- =================================navbar start ======================================== -->
    <div class="container">
    <!-- =================================alert message start ========================================-->
    <h2 class="my-4 text-start text-primary">Employee Management</h2>
    <div class="alert alert-primary" role="alert">
        <h4 class="alert-heading">Skill Instructions</h4>
        <p class="">
            In this section, you will find information about the skills listed for each employee. The skills are categorized to help you easily identify the expertise of each individual. 
            <strong>Skills</strong> include various professional abilities, certifications, and proficiencies that employees possess. Ensure to review each skill listed to understand the capabilities of the employees better.
        </p>
        <hr>
        <p class="mb-0">For any further assistance or queries regarding skills, please contact the HR department.</p>
    </div>
    <!-- =================================alert message end ========================================-->

    <!-- =================================Employee skill detail cards start ========================================-->    
    <div class="row">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="col-md-3 wow fadeUp">';
                echo '    <div class="card text-center">';
                echo '    <div class="d-flex justify-content-center" style="height:250px; width:100%">';
                echo '        <img src="' . $row['img'] . '" height="100%" width="100%"  alt="Employee Image" class="">';
                echo '    </div>';
                echo '        <div class="card-body">';
                echo '            <h5 class="card-title">' . $row['username'] . '</h5>';
                echo '            <p class="card-text">Age: ' . $row['age'] . '</p>';
                echo '            <p class="card-text">Skills: ' . $row['skills'] . '</p>';
                echo '            <p class="card-text">Amount: ' . $row['amount'] . '</p>';
                // Display status button based on current status
                echo '            <form action="' . $_SERVER['PHP_SELF'] . '" method="POST" class="d-inline">';
                echo '                <input type="hidden" name="employee_id" value="' . $row['id'] . '">';
                echo '                <input type="hidden" name="status" value="' . $row['status'] . '">';
                if ($row['status'] == 'activate') {
                    echo '                <button class="btn btn-success btn-sm" type="submit">Online</button>';
                } else {
                    echo '                <button class="btn btn-danger btn-sm" type="submit">Offline</button>';
                }
                echo '            </form>';
                // echo '            <a href="delete.php?emp_id=' . $row['id'] . '" class="btn btn-danger btn-sm">Delete Identity</a>';
                echo '        </div>';
                echo '    </div>';
                echo '</div>';
            }
        } else {
            echo '<div class="col-12"><p class="text-center">No employees found</p></div>';
        }
        ?>
    </div>
     <!-- =================================Employee skill detail cards end ========================================-->  
    </div>
    <!-- =================================container end ======================================== -->
    <!--=================================== back to top start ==============================================-->
    <?php include 'includes/back_top.php'; ?>
            <!--=================================== back to top end ==============================================-->    
</body>
</html>

<?php
$stmt->close();
$mysqli->close();
?>
