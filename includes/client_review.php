<?php
include 'config/db_connection.php';

// Fetch reviews, created date, username, and profile image from the database
$sql = "SELECT r.comment, r.created_at, u.username, u.profileimage
        FROM reviews r
        JOIN users u ON r.user_id = u.id";
$result = $mysqli->query($sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Customer List</title>
    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/custom.css" rel="stylesheet"> <!-- Your custom CSS file -->
</head>

<style>
    /* Customize the arrows for Owl Carousel */
    .owl-prev, .owl-next {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        display: flex;
        align-items: center;
        justify-content: center;
        width: 45px;
        height: 45px;
        background-color: #00b98e !important; /* Change this to your desired color */
        color: white;
        border-radius: 50%;
        font-size: 20px;
        cursor: pointer;
        z-index: 1000;
        border: none;
    }


    .owl-prev:hover, .owl-next:hover {
        background-color: #00b98e !important; /* Change this to your desired hover color */
    }

    /* Customize the arrow icons */
    .owl-prev::before, .owl-next::before {
        content: '';
        width: 0;
        height: 0;
        border-style: solid;
    }

    .owl-prev::before {
        border-width: 8px 10px 8px 0;
        border-color: transparent white transparent transparent;
    }

    .owl-next::before {
        border-width: 8px 0 8px 10px;
        border-color: transparent transparent transparent white;
    }

    .owl-dot {
        display: none;
    }
</style>

<body>
    <!-- Testimonial Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                <h1 class="mb-3 text-primary">Our Clients Say!</h1>
            </div>
            <div class="owl-carousel testimonial-carousel wow fadeInUp  d-flex align-items-center justify-content-center" data-wow-delay="0.1s">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        ?>
                        <div class="testimonial-item rounded p-3 w-100" style="border: 1px dashed green">
                            <div class="p-4">
                                <p><?php echo htmlspecialchars($row['comment']); ?></p>
                                <div class="d-flex align-items-center">
                                    <img class="img-fluid flex-shrink-0 rounded" src="<?php echo htmlspecialchars($row['profileimage']); ?>" style="width: 45px; height: 45px;">
                                    <div class="ps-3">
                                        <h6 class="fw-bold mb-1"><?php echo htmlspecialchars($row['username']); ?></h6>
                                        <small><?php echo htmlspecialchars($row['created_at']); ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "<p>No testimonials found.</p>";
                }
                $mysqli->close();
                ?>
            </div>
        </div>
    </div>
    <!-- Testimonial End -->

    <!-- Include your JS files here -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/owl.carousel@2.3.4/dist/owl.carousel.min.js"></script>
    <script>
        $(document).ready(function(){
            $(".testimonial-carousel").owlCarousel({
                items: 2, // Show 2 items
                loop: true,
                margin: 10,
                nav: true,
                autoplay: true,
                autoplayTimeout: 1000,
                autoplayHoverPause: true,
                responsive: {
                    0: {
                        items: 1 // Show 1 item on small screens
                    },
                    768: {
                        items: 2 // Show 2 items on medium and larger screens
                    }
                }
            });
        });
    </script>
</body>

</html>
