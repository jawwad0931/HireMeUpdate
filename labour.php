<?php
include ("config/db_connection.php");
session_start();
if ($_SESSION['role'] !== 'employee') {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['id'];
// echo $user_id;
// echo $user_id;
$username = $_SESSION['username'];
// echo $username;




// Display session message if it exists
// if (isset($_SESSION['message'])) {
//     echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['message']) . '</div>';
//     unset($_SESSION['message']);
// }

// Fetch user data
$sql = "SELECT username FROM users WHERE id = ?";
if ($stmt = $mysqli->prepare($sql)) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($username);
    $stmt->fetch();
    $stmt->close();
} else {
    echo "Error fetching user data.";
    exit;
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- dobara redirect se bachne ke liye lagaya gaya hai -->
    <!-- <script type="text/javascript">
    window.history.forward();
    </script> -->
    <?php include 'includes/header.php'; ?>
</head>
<style>
    .banner_container {
        backdrop-filter: blur(3px);
        background-color: rgba(255, 255, 255, 0.3);
        /* Optional: To provide a semi-transparent background */
        border-radius: 0px;
        /* Optional: To add rounded corners */
        padding: 20px;
        /* Optional: To add padding inside the container */
        border: none !important;
        color: white !important;
    }

    .card {
            text-align: center;
            width: 450px;
            max-width: 400px;
            height: auto;
            border-radius: 20px;
            overflow: clip;
            display: flex;
            flex-direction: column;
            background-image: url("img/labourBack.jpg");
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            outline: 1px solid #FFFFFF70;
            outline-offset: 0px;
            margin: auto;
            border: 1px dashed black;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        main {
            margin-top: 150px;
            flex: 1;
            clip-path: path("M 0 100 c 150 0 120 -75 200 -75 C 280 25 250 100 400 100 L400 1000 0 1000 Z");
            background-color: white;
            border: none;
        }

        main > img {
            margin-top: 2.25rem;
            border-radius: 50%;
            width: 140px;
            aspect-ratio: 1;
            margin-inline: auto;
            outline: 1px solid #0C4A6E;
            outline-offset: 2px;
        }

        .instructions-section {
            margin: auto;
            padding: 20px;
            border-radius: 10px;
            /* background-color: white; */
            /* box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); */
        }

        .instructions-section h2 {
            text-align: center;
            color: #00B98E;
        }

        .instructions-section ul {
            list-style-type: none;
            padding: 0;
        }

        .instructions-section ul li {
            margin: 10px 0;
            padding: 10px;
            background-color: #fff;
            border-radius: 5px;
            border-left: 5px solid #00B98E;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .instructions-section ul li h3 {
            margin: 0;
            color: #333;
        }

        .instructions-section ul li p {
            margin: 5px 0 0;
            color: #666;
        }

        @media (max-width: 600px) {
            .instructions-section {
                padding: 15px;
            }

            .instructions-section ul li {
                padding: 8px;
            }

            .instructions-section ul li h3 {
                font-size: 1.1rem;
            }

            .instructions-section ul li p {
                font-size: 0.9rem;
            }
        }

        /* review card design */
        .profile-image{
            height: 86%;
            border-radius: 50%;
            margin-left: 7%;
            margin-top: 7%;
            background: #000;
        }
</style>

<body class="bg-white">

            <!--=================================== Here Navbar Start   =================================-->
            <?php include 'includes/labor_nav.php'; ?>
            <!--=================================== Here Navbar Start   =================================-->

            <!--=================================== Here Container Start   =================================-->
            <div class="container-fluid header bg-white p-0">
            <!--=================================== Here Container Start   ==================================-->

            <!--=================================== Header Start ===================================-->
            <div class="row g-0 align-items-center flex-column-reverse flex-md-row">
            <div class="col-md-12 mt-lg-12 wow fadeIn" data-wow-delay="0.1s">
                <div class="owl-carousel header-carousel">
                    <div class="owl-carousel-item" style="height:450px">
                        <img class="img-fluid" src="img/labour.jpg" height="300px" width="100%" alt="">
                        <div class="carousel-caption">
                            <div class="container banner_container my-5">
                                <div class="text-center">
                                    <h1 class="wow fadeInUp text-dark" data-wow-delay="0.1s">Welcome, <?php echo htmlspecialchars($username); ?>!</h1>
                                    <p class="lead text-dark">Make your clients happy.</p>
                                </div>
                            </div>
                        </div>
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


            <!-- ================================== Here Quicklinks start =======================================  -->
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-6 d-flex align-items-center justify-content-center">
                        <div class="quick-links my-5">
                            <h2 class="text-center text-primary text-decoration-underline ">Quick Links</h2>
                            <a href="booking_users.php" class="btn btn-primary m-0 px-2 p-0 fs-6 wow fadeIn">View Bookings</a>
                            <a href="labour_message.php" class="btn btn-secondary m-0 px-2 p-0 fs-6 wow fadeIn">Check Messages</a>
                            <a href="edit_profile.php" class="btn btn-success m-0 px-2 p-0 fs-6 wow fadeIn">Update Profile</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ==================================== Here Quicklinks End ======================================= -->

            <!-- ==================================== Profile details Start ======================================= -->
            <div class="container">
                <div class="row">
                        <div class="col-lg-4 col-md-12 col-sm-12 wow fadeIn" data-wow-delay="0.1s delay">
                                <?php
                                    include 'config/db_connection.php';
                                    $sql = "SELECT * FROM users where id = '$user_id'";
                                    $result = $mysqli->query($sql);
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "
                                            <div class='card my-5'>
                                            <main>
                                                <img src='" . $row['profileimage'] . "'>
                                                <h2 class='text-primary'>" . $row['username'] . "</h2>
                                                <p>" . $row['email'] . "</p>
                                                <p>" . $row['phone'] . "</p>
                                                <p>" . $row['age'] . "</p>
                                                <p>" . $row['contact'] . "</p>
                                                <p>" . $row['profileimage'] . "</p>
                                                <p>" . $row['role'] . "</p>
                                                <p>" . $row['created_at'] . "</p>
                                            </main>
                                        </div>";
                                        }
                                    }
                                    $mysqli->close();
                                    ?>
                        </div>
                        <div class="col-lg-8 col-md-12 col-sm-12 wow fadeIn">
                            <div class="images mt-5">
                                <img src="img/carpentry1.jpg" height="610px" width="100%" alt="">
                            </div>
                        </div>
                </div>
            </div>
            <!-- ==================================== Profile details End ======================================= -->
            
            <!-- ==================================== Employee Instruction start ======================================= -->
            <div class="container-fluid">
                <div class="row my-5">
                    <div class="col-12">
                    <div class="instructions-section wow fadeInUp">
                        <h2 class="text-start">Instructions for Labour Employees</h2>
                        <ul>
                            <li>
                                <p>Always arrive on time for your scheduled work. Punctuality is essential to maintain trust and reliability.</p>
                            </li>
                            <li>
                                <p>Ensure you wear appropriate safety gear at all times. Follow safety protocols to avoid accidents and injuries.</p>
                            </li>
                            <li>
                                <p>Maintain a professional attitude while interacting with clients and colleagues. Respect others and communicate effectively.</p>
                            </li>
                            <li>
                                <p>Focus on delivering high-quality work. Pay attention to details and follow the instructions provided by the client or supervisor.</p>
                            </li>
                            <li>
                                <p>If you encounter any issues or face any challenges during your work, report them to your supervisor or contact support immediately.</p>
                            </li>
                            <li>
                                <p>Take good care of the tools and equipment provided to you. Ensure they are clean and in good working condition before and after use.</p>
                            </li>
                            <li>
                                <p>Take scheduled breaks to rest and rejuvenate. Do not overwork yourself, as it can lead to fatigue and reduced efficiency.</p>
                            </li>
                            <li>
                                <p>Strive to meet and exceed client expectations. A satisfied client is more likely to rehire and recommend your services to others.</p>
                            </li>
                        </ul>
                    </div>
                    </div>
                </div>
            </div>
            <!-- ==================================== Employee Instruction end ======================================= -->

            <!-- ==================================== Reviews start ======================================= -->
            <?php 
            include ("config/db_connection.php");
            // Fetch reviews, created date, username, and profile image from the database
            $sql = "SELECT r.comment, r.created_at, u.username, u.profileimage
            FROM reviews r
            JOIN users u ON r.user_id = u.id";
            $result = $mysqli->query($sql);
            ?>
            <div class="container py-1 px-3">
            <h1 class="mb-4 text-center text-primary">Reviews</h1>
            <div class="row wow fadeIn">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $comment = htmlspecialchars($row['comment']);
                        $created_at = htmlspecialchars($row['created_at']);
                        $username = htmlspecialchars($row['username']);
                        $profileimage = htmlspecialchars($row['profileimage']);

                        echo '<div class="col-md-3">';
                        echo '    <div class="review-card my-5 border rounded-50 overflow-hidden hover-effect">'; // Added rounded-50 and hover-effect
                        echo '        <div class="card-body text-center">';
                        echo '            <div class="mb-3">';
                        echo '                <div class="pt-3 d-flex justify-content-center">';
                        echo '                    <h5 class="card-title mb-0 text-primary">' . $username . '</h5>';
                        echo '                </div>';
                        echo '                <div class="pt-3 d-flex justify-content-center">';
                        echo '                    <small class="text-primary">' . $created_at . '</small>';
                        echo '                </div>';
                        echo '            </div>';
                        echo '            <p class="card-text text-center text-primary">' . $comment . '</p>';
                        echo '        </div>';
                        echo '    </div>';
                        echo '</div>';
                    }
                } else {
                    echo '<div class="col-12">No reviews found.</div>';
                }
                ?>
            </div>
        </div>

        <style>
            .review-card {
                transition: transform 0.3s ease, box-shadow 0.3s ease;
                border-radius: 5%; 
                overflow: hidden; 
                height: 300px;
            }

            .review-card:hover {
                transform: scale(1.05); 
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2); 
                backdrop-filter: blur(5px); 
                z-index: 1; 
            }
        </style>
            <!-- ==================================== Reviews end ======================================= -->        

            <!-- ================================== Here footer start =======================================-->
            <?php include 'includes/footer.php'; ?>
            <!-- ================================== Here footer End =======================================-->

            <!--=================================== Here Container End   =================================-->
            </div>
            <!--=================================== Here Container End   ==================================-->
            <!--=================================== back to top start ==============================================-->
                <?php include 'includes/back_top.php'; ?>
            <!--=================================== back to top end ==============================================-->
</body>
</html>