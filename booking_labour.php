<?php
session_start();
include 'config/db_connection.php';
include "includes/header.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header("Location: index.php");
    exit;
}

$employee_id = null;
$employee_username = "";

if (isset($_GET['employee_id'])) {
    $emp_id = $_GET['employee_id'];

    $employee_sql = "
        SELECT e.id, u.username
        FROM employees e
        JOIN users u ON e.user_id = u.id
        WHERE e.id = ?
    ";
    $stmt_employee = $mysqli->prepare($employee_sql);
    $stmt_employee->bind_param("i", $emp_id);
    if ($stmt_employee->execute()) {
        $stmt_employee->bind_result($employee_id, $employee_username);
        if (!$stmt_employee->fetch()) {
            echo "No employee found with ID " . htmlspecialchars($emp_id);
            exit;
        }
    } else {
        echo "Error fetching employee details: " . $stmt_employee->error;
        exit;
    }
    $stmt_employee->close();
} else {
    header("Location: index.php");
    exit;
}

$logged_in_user_id = $_SESSION['id'];
$user_sql = "SELECT id, username FROM users WHERE id = ? AND role = 'user'";
$stmt_user = $mysqli->prepare($user_sql);
$stmt_user->bind_param("i", $logged_in_user_id);
if ($stmt_user->execute()) {
    $stmt_user->bind_result($user_id, $username);
    if (!$stmt_user->fetch()) {
        echo "Error fetching user details.";
        exit;
    }
} else {
    echo "Error fetching user details: " . $stmt_user->error;
    exit;
}
$stmt_user->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $user_address = $_POST['user_address'];
    $timing = $_POST['timing'];
    $day = $_POST['day'];
    $description = $_POST['description'];

    $insert_sql = "INSERT INTO bookings (employee_id, user_id, user_address, timing, day, description, created_at)
    VALUES (?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $mysqli->prepare($insert_sql);
    $stmt->bind_param("iissss", $emp_id, $user_id, $user_address, $timing, $day, $description);

    if ($stmt->execute()) {
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Booking Successful!',
                    text: 'Your booking has been placed successfully.',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'user.php';
                    }
                });
              </script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    exit;
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Book a Labour</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/owl.carousel@2.3.4/dist/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/owl.carousel@2.3.4/dist/owl.theme.default.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/owl.carousel@2.3.4/dist/owl.carousel.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .form-floating {
            margin-bottom: 1rem;
        }
        .btn-primary {
            transition: background-color 0.3s, transform 0.3s;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }
        .about-img img {
            animation: fadeIn 2s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .btn-primary {
            position: relative;
            overflow: hidden;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .animation-container {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
            height: 100%;
            pointer-events: none;
            display: none;
        }

        .employee {
            position: absolute;
            bottom: -100px;
            left: 50%;
            transform: translateX(-50%);
            animation: walk 2s forwards;
        }

        @keyframes walk {
            0% {
                bottom: -100px;
                opacity: 1;
            }
            100% {
                bottom: 50px;
                opacity: 0;
            }
        }

        .btn-primary.active .animation-container {
            display: block;
        }
    </style>
    <script>
        $(document).ready(function(){
            $(".testimonial-carousel").owlCarousel({
                items: 1,
                loop: true,
                margin: 10,
                nav: true,
                autoplay: true,
                autoplayTimeout: 3000,
                autoplayHoverPause: true
            });
        });
    </script>
</head>
<body class="bg-white">
    <div class="container-xxl py-5">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6 wow fadeIn" data-wow-delay="0.1s">
                    <div class="about-img position-relative overflow-hidden p-5 pe-0">
                        <img class="img-fluid w-100" src="img/book banner.jpg" alt="Book Banner">
                    </div>
                </div>
                <div class="col-lg-6 wow fadeIn" data-wow-delay="0.5s">
                    <h1 class="mb-4 text-start text-primary">Book Service</h1>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?employee_id=" . urlencode($employee_id); ?>" method="POST">
                        <input type="hidden" name="employee_id" value="<?php echo htmlspecialchars($employee_id); ?>">
                        <input type="hidden" id="user_id" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control" style="height:50px;" id="user_address" name="user_address" placeholder="Your Address" required>
                                    <label for="user_address">User Address</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="date" class="form-control" style="height:50px;" id="day" name="day" placeholder="Date" required>
                                    <label for="day">Day</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="time" class="form-control" style="height:50px;" id="timing" name="timing" placeholder="Time" required>
                                    <label for="timing">Time</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea class="form-control" style="height: 120px;" placeholder="Leave a description here" id="description" name="description" required></textarea>
                                    <label for="description">Description</label>
                                </div>
                            </div>
                            <div class="col-12 text-start">
                                <button class="btn btn-primary px-5" type="submit">Book Now</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php include "includes/footer.php"; ?>
</body>
</html>
