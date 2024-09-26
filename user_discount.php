<?php
include "config/db_connection.php";
session_start();

if ($_SESSION['role'] !== 'user') {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['id'];

// Fetch user's discounts
$sql = "
    SELECT d.discount_code, d.discount_percentage, d.start_date, d.end_date
    FROM user_discounts ud
    INNER JOIN discounts d ON ud.discount_id = d.id
    WHERE ud.user_id = ?
";
$stmt = $mysqli->prepare($sql);
if (!$stmt) {
    die('Error in preparing SQL statement: ' . $mysqli->error);
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
if (!$result) {
    die('Error in fetching results: ' . $stmt->error);
}

$stmt->close();
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HireMe</title>
    <?php include 'includes/header.php'; ?>
    <link rel="stylesheet" href="path/to/your/css/styles.css"> <!-- Add your CSS file for custom styles -->
</head>
<body class="bg-white">
    <?php include 'includes/navbar.php'; ?>
    <div class="container my-5 py-5">
        <div class="row">
            <div class="col-lg-8">
                <div class="px-4">
                <h3 class="display-5 animated fadeIn mb-4 text-danger">Discounts</h3>
                <h4 class="text-primary">Important Notice: Exclusive Discount Alert!</h4>
                <p class="text-primary">We are offering limited-time discounts for customers who pay their bills online!</p>
                <p class="text-primary">Don't miss out on this opportunity to save!</p>
                <p class="text-primary">Pay online and enjoy instant savings on your next payment.</p>
                <p class="text-primary">Act fast—this offer won't last long!</p>
                </div>
            </div>
            <div class="col-lg-4">
                <h1 class="display-5 animated fadeIn mb-4 text-primary">
                    <img src="img/dis.jpg" height="300px" width="300px" alt="">
                </h1>
            </div>
        </div>
        <div class="row ml-3">
            <div class="alert alert-custom w-75 mx-4" role="alert">
                <h4 class="alert-heading text-danger">Important Note</h4>
                <p class="text-danger  wow fadeInUp" data-wow-delay="0.1s">
                    We want to inform you that the discount offer that was previously available has now ended.<br /> Unfortunately, this means that the discount can no longer be applied to any new purchases.
                </p>
                <hr>
                <p class="mb-0 text-danger wow fadeIn">For any issues,<a href="user_feedback.php" class="text-danger text-decoration-underline">please contact the support team</a>.</p>
            </div>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col-md-6 col-lg-6 col-sm-12 mb-4">
                    <div class="card coupon-card wow fadeInUp"  data-wow-delay="0.1s" style="margin-left:25px !important;">
                        <!-- <img src="https://i.postimg.cc/KvTqpZq9/uber.png" class="logo"> -->
                        <div class="card-body">
                            <h3 class="card-title text-light"><?php echo round($row['discount_percentage']); ?>% off</h3>
                            <div class="coupon-row">
                                <span class="cpnCode text-white"><?php echo $row['discount_code']; ?></span>
                                <button class="cpnBtn btn btn-light btn-sm px-1 py-1" data-code="<?php echo $row['discount_code']; ?>">Copy Code</button>
                            </div>
                            <p class="text-white">Valid from <?php echo date('M d, Y', strtotime($row['start_date'])); ?> to <?php echo date('M d, Y', strtotime($row['end_date'])); ?></p>
                        </div>
                        <div class="circle1 bg-white"></div>
                        <div class="circle2 bg-white"></div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <script>
        document.querySelectorAll('.cpnBtn').forEach(function(button) {
            button.addEventListener('click', function() {
                var code = this.getAttribute('data-code');
                navigator.clipboard.writeText(code).then(() => {
                    this.innerText = "COPIED";
                    setTimeout(() => {
                        this.innerText = "Copy Code";
                    }, 3000);
                });
            });
        });
    </script>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
