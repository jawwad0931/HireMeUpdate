<?php

include("../config/db_connection.php");
session_start();

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if user is logged in as 'user'
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header("Location: index.php");
    exit;
}

// Count the number of users
$sql_users = "SELECT COUNT(*) AS user_count FROM users WHERE role = 'user'";
$result_users = $mysqli->query($sql_users);
if (!$result_users) {
    die("Error executing query: " . $mysqli->error);
}
$users_count = $result_users->fetch_assoc()['user_count'];

// Count the number of employees
$sql_employees = "SELECT COUNT(*) AS employee_count FROM employees";
$result_employees = $mysqli->query($sql_employees);
if (!$result_employees) {
    die("Error executing query: " . $mysqli->error);
}
$employee_count = $result_employees->fetch_assoc()['employee_count'];

// Close the database connection
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include 'header.php'; ?>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <title>Counts</title>
    <style>
        /* Add your CSS styling here */
        .counter-wrapper {
            display: flex;
            justify-content: space-around;
            align-items: center;
            padding: 20px;
        }

        .counter-item {
            text-align: center;
            padding: 20px;
            background-color: #fff;
            transition: transform 0.3s;
        }

        .counter-item:hover {
            transform: scale(1.05);
        }

        .count-wrapper h4 {
            font-size: 36px;
            color: #333;
            margin: 0;
            font-weight: bold;
        }

        .count-wrapper p {
            font-size: 18px;
            color: #777;
            margin: 10px 0 0;
        }

        .counterup {
            color: #3498db;
        }

        /* for text shadow */
        .count-wrapper h4 {
            font-size: 2em;
    color: #39ff14;
    text-shadow: 
       font-size: 2em;
            color: #fff;
            text-shadow: 
                1px 1px 2px rgba(0, 0, 0, 0.5),
                0 0 25px rgba(0, 0, 255, 0.5),
                0 0 5px rgba(0, 0, 255, 0.5);

        }

        @media (max-width: 768px) {
            .counter-wrapper {
                flex-direction: column;
            }
            .counter-item {
                margin-bottom: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="counter-wrapper">
        <div class="counter-item">
            <div class="count-wrapper">
                <h4 class="counterup text-primary"><?php echo (int)$users_count;  ?></h4>
                <p class="text-primary">Users</p>
            </div>
        </div>
        <div class="counter-item">
            <div class="count-wrapper">
                <h4 class="counterup text-primary"><?php echo (int)$employee_count;  ?></h4>
                <p class="text-primary">Employees</p>
            </div>
        </div>
        <div class="counter-item">
            <div class="count-wrapper">
                <h4 class="counterup text-primary d-inline-block">95</h4><span class="text-primary fs-3 fw-bolder">%</span>
                <p class="text-primary">Positive Review</p>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            // Create new intersection observer
            var observer = new IntersectionObserver(function (entries, observer) {
                entries.forEach(function (entry) {
                    // If the element is in view, start counter animation
                    if (entry.isIntersecting) {
                        $(entry.target).prop("Counter", 0).animate(
                            {
                                Counter: $(entry.target).text(),
                            },
                            {
                                duration: 4000,
                                easing: "swing",
                                step: function (now) {
                                    $(entry.target).text(Math.ceil(now));
                                },
                            }
                        );
                        // Stop observing the element to prevent duplicate animations
                        observer.unobserve(entry.target);
                    }
                });
            });

            // Observe each .counterup element
            $(".counterup").each(function () {
                observer.observe(this);
            });
        });
    </script>
</body>
</html>
