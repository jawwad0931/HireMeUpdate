<?php
include 'config/db_connection.php';

session_start();
if ($_SESSION['role'] !== 'user') {
    header("Location: index.php");
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['id'];
    $feedback = $_POST['feedback'];

    $insert_sql = "INSERT INTO feedback (user_id, feedback) VALUES ('$user_id', '$feedback')";
    if ($mysqli->query($insert_sql) === TRUE) {
        // Success: Show alert and redirect
        echo "<script>
            alert('Thank you for your feedback!');
            window.location.href = 'user_feedback.php';
        </script>";
    } else {
        echo "Error: " . $insert_sql . "<br>" . $mysqli->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <?php
        include 'includes/header.php';
    ?>
</head>
<style>
    .custom-border-dark {
        border: 2px solid #343a40; /* Dark border with specific thickness */
    }
</style>
<body class="bg-white">
    <div class="container-xxl bg-white p-0">
        <!-- navbar start -->
        <?php 
        include 'includes/navbar.php';
        ?>
        <!-- navbar end -->
        <!-- Header Start -->
        <div class="container-fluid bg-white p-0">
            <div class="row g-0 align-items-center flex-column-reverse flex-md-row">
                <div class="col-md-6 p-5 mt-lg-5">
                    <h1 class="display-5 animated fadeIn mb-4 text-primary">Feedback Us</h1> 
                        <nav aria-label="breadcrumb animated fadeIn">
                        <ol class="breadcrumb text-uppercase bg-white">
                            <li class="breadcrumb-item"><a href="user.php">Home</a></li>
                            <li class="breadcrumb-item text-body active" aria-current="page">Feedback Us</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-md-6 animated fadeIn">
                    <img class="img-fluid" src="img/emp-feeds.png" alt="">
                </div>
            </div>
        </div>
        <!-- Header End -->


        <!-- Contact Start -->
        <div class="container-xxl py-5">
            <div class="container">
                <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                    <h1 class="mb-3 text-primary">Feedback Us</h1>
                    <p class="text-primary">Our team is here to assist you with all your home maintenance needs. We look forward to hearing from you!.</p>
                </div>
                <div class="row g-4">
                    <div class="col-12">
                        <div class="row gy-4">
                            <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay="0.1s">
                                <div class="rounded p-3">
                                    <div class="d-flex align-items-center rounded p-3" style="border: 1px #00B98E dashed">
                                        <div class="me-3" style="width: 45px; height: 45px;border-radius: 50%;border: 1px #00B98E dashed;display: flex;justify-content: center;align-items: center">
                                            <i class="fa fa-map-marker-alt text-primary"></i>
                                        </div>
                                        <span class="text-primary">123 Street, Clifton, Karachi</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay="0.3s">
                                <div class="rounded p-3">
                                    <div class="d-flex align-items-center rounded p-3" style="border: 1px #00B98E dashed">
                                        <div class="me-3" style="width: 45px; height: 45px;border-radius: 50%;border: 1px #00B98E dashed;display: flex;justify-content: center;align-items: center">
                                            <i class="fa fa-envelope-open text-primary"></i>
                                        </div>
                                        <span class="text-primary">HireMe123@gmail.com</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4 wow fadeIn" data-wow-delay="0.5s">
                                <div class="rounded p-3">
                                    <div class="d-flex align-items-center rounded p-3" style="border: 1px #00B98E dashed">
                                        <div class="me-3" style="width: 45px; height: 45px;border-radius: 50%;border: 1px #00B98E dashed;display: flex;justify-content: center;align-items: center">
                                            <i class="fa fa-phone-alt text-primary"></i>
                                        </div>
                                        <span class="text-primary">+92 311-3305273</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                        <iframe class="position-relative rounded w-100 h-100"
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3620.8020002497765!2d67.03102487487902!3d24.83644394621913!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3eb33dc578155555%3A0x3ae3774540a5995b!2sAptech%20Computer%20Education!5e0!3m2!1sen!2s!4v1723148704199!5m2!1sen!2s"
                            frameborder="0" style="min-height: 400px; border:0;" allowfullscreen="" aria-hidden="false"
                            tabindex="0"></iframe>
                    </div>
                    <div class="col-md-6">
                        <div class="wow fadeInUp" data-wow-delay="0.5s">
                            <p class="mb-4"></a></p>
                            <div class="container">
                                <h2 class="mt-4 text-primary">Feedback</h2>
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                                <div class="mb-2">
    <label for="feedback" class="form-label text-primary">Feedback:</label>
    <textarea id="feedback" name="feedback" class="form-control border-dark" rows="8" required></textarea>
</div>

                                    <button type="submit" class="btn btn-primary btn-sm px-4 text-white">Submit Feedback</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Contact End -->
    <!--================================================== Back to Top start ===========================================-->
    <?php include 'includes/back_top.php'; ?>
    <!--================================================== Back to Top end ===========================================-->
    
    <?php include 'includes/footer.php' ?>
</body>
</html>
