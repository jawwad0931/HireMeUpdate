<?php
include 'config/db_connection.php';
session_start();

// Check if there's a message to display
$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
// Clear the message after displaying it
unset($_SESSION['message']); 




?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- dobara redirect se bachne ke liye lagaya gaya hai -->
    <script type="text/javascript">
    window.history.forward();
    </script>
    <?php
    include 'includes/header.php';
    ?>
</head>

<body class="bg-white">
        <!--=================================== Navbar Start ===================================-->
        <?php include 'includes/navbar.php'; ?>
        <!--=================================== Navbar End ===================================-->


        <!--=================================== Here Container Start   =================================-->
        <?php if (!empty($message)): ?>
            <script>
                alert('<?php echo addslashes($message); ?>');
            </script>
        <?php endif; ?>
        <div class="container-fluid header bg-white p-0">
        <!--=================================== Here Container Start   ==================================-->

        <!--=================================== Header Start ===================================-->
        <?php
        include 'config/db_connection.php';
        // Check if user is logged in; otherwise, redirect to login page
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
            header("Location: index.php");
            exit;
        }
        $sql = "SELECT `id`, `image`, `sales_percentage`, `created_at` FROM `sales`";
        $result = $mysqli->query($sql);
        ?>
        <div class="row g-0 align-items-center flex-column-reverse flex-md-row">
            <div class="col-md-6 p-5 mt-lg-5">
                <h1 class="display-5 animated fadeIn mb-4">Expert <span class="text-primary">Home Maintenance</span> at
                    Your Doorstep</h1>
                <p class="animated fadeIn mb-4 pb-2">Are you tired of dealing with endless home repairs and maintenance
                    tasks?
                    Look no further! As a dedicated and skilled home maintenance professional, I am here to provide
                    top-quality services right at your doorstep.</p>
                <a href="view_emp.php" class="btn btn-primary btn-sm px-5 me-3 animated fadeIn text-white">Book Now</a>
            </div>
            <div class="col-md-6 mt-lg-6 animated fadeIn">
                <div class="owl-carousel header-carousel">
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            ?>
                            <div class="owl-carousel-item">
                                <img class="img-fluid" src="<?php echo htmlspecialchars($row['image']); ?>" height="200px"
                                    width="100%" alt="">
                                <div class="carousel-caption">
                                    <h5>Discount: <?php echo htmlspecialchars($row['sales_percentage']); ?>%</h5>
                            </div>
                            <?php
                        }
                    } else {
                        echo "<p>No sales data found.</p>";
                    }
                    $mysqli->close();
                    ?>
                    <!-- running for owl carousel -->
                    <script>
                        <script>
                            $(document).ready(function(){
                                $(".header-carousel").owlCarousel({
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
                    </script>
                </div>
            </div>
        </div>
        <!--=================================== Header End =======================================-->

        <!--=================================== Category Start ===================================-->
        <div class="container-xxl py-5">
    <div class="container">
        <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
            <!-- <h1 class="mb-3 text-primary">OUR SERVICES</h1> -->
        </div>
        <div class="row g-4">
            <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.1s">
                <a class="cat-item d-block text-center rounded p-3" href="view_emp.php">
                    <div class="rounded bg-primary p-4">
                        <div class="icon mb-3">
                            <img class="img-fluid" src="img/electrician1.png" alt="Icon">
                        </div>
                        <h6>Electrician</h6>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.3s">
                <a class="cat-item d-block text-center rounded p-3" href="view_emp.php">
                    <div class="rounded bg-primary p-4">
                        <div class="icon mb-3">
                            <img class="img-fluid" src="img/technician.png" alt="Icon">
                        </div>
                        <h6>Plumber</h6>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.5s">
                <a class="cat-item d-block text-center rounded p-3" href="view_emp.php">
                    <div class="rounded bg-primary p-4">
                        <div class="icon mb-3">
                            <img class="img-fluid" src="img/carpenter.png" alt="Icon">
                        </div>
                        <h6>Carpenter</h6>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.7s">
                <a class="cat-item d-block text-center rounded p-3" href="view_emp.php">
                    <div class="rounded bg-primary p-4">
                        <div class="icon mb-3">
                            <img class="img-fluid" src="img/painter.png" alt="Icon">
                        </div>
                        <h6>Painter</h6>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

        <!--=================================== Category End ======================================-->

                
        <!--=================================== Property List Start ===================================-->
        <?php include 'includes/Employees_list.php'; ?>
        <!--=================================== Property List End =====================================-->

        <!--=================================== Call to Action Start ===================================-->
        <div class="container-xxl py-5">
            <div class="container">
                <div class="rounded p-3">
                    <div class="rounded p-4" style="border: 1px dashed green">
                        <div class="row g-5 align-items-center">
                            <div class="col-lg-6 wow fadeIn" data-wow-delay="0.1s">
                                <img class="img-fluid rounded w-100" src="img/call-to-action.jpg" alt="">
                            </div>
                            <div class="col-lg-6 wow fadeIn" data-wow-delay="0.5s">
                                <div class="mb-4">
                                    <h1 class="mb-3 text-primary">Contact With Our Certified Agent</h1>
                                    <p class="text-dark">Our certified agents are here to assist you with all your needs. Whether you have
                                        questions about our services, need help with a product, or require expert advice,
                                        our knowledgeable and friendly agents are ready to provide you with top-notch
                                        support.</p>
                                </div>
                                <a href="tel:+923113305273" class="btn btn-primary btn-sm  px-3 me-2 text-white"><i class="fa fa-phone-alt me-2"></i>+92
                                    3113305273</a>
                                <a href="mailto:support@example.com?subject=Support%20Request&body=Hi%20Support,%0A%0AI%20need%20assistance%20with..." class="btn btn-primary btn-sm  px-3 text-white"><i class="fa fa-envelope me-2 text-white"></i>HireMe123@gmail.com</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--=================================== Call to Action End ===================================-->
        
        <!--=================================== Review & ratings Start ===================================-->
        <!-- yahan footer ka div work kar raha hai -->
        <div class="container-xxl py-5"></div>
        <?php
        include "includes/client_review.php";
        ?>
                </div>
        <!--=================================== Review & ratings End ===================================-->

        

        <!--=================================== scroll to up start =====================================-->
        <!-- Back to Top -->
        <?php include 'includes/back_top.php'; ?>
        <!--=================================== scroll to up end =====================================-->

        <!--=================================== Here Container End   ====================================-->
        </div>                    
        <!--=================================== Here Container End   ====================================-->
        <!--=================================== footer start ===================================-->
        <?php
        include 'includes/footer.php';
        ?>
        <!-- Include your JS files here -->
        <!-- For example, jQuery, Bootstrap JS, and Owl Carousel JS files -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/owl.carousel@2.3.4/dist/owl.carousel.min.js"></script>
        <script>
            $(document).ready(function(){
                $(".testimonial-carousel").owlCarousel({
                    // Your Owl Carousel settings here
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
        <!--=================================== footer end =====================================-->
</body>

</html>