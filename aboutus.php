<?php
include 'config/db_connection.php';

session_start();
if ($_SESSION['role'] !== 'user') {
    header("Location: index.php");
    exit;
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
    body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }
        .vision-section {
            text-align: center;
            padding: 50px 20px;
            background: linear-gradient(150deg, #00B98E 0%, #0072ff 160%);
            color: white;
        }
        .vision-section h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .vision-section p {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        .vision-section img {
            max-width: 100%;
            height: auto;
            margin-top: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .vision-section a {
            color: #fff;
            font-weight: ;
            text-decoration: none;
            /* background-color: #0072ff; */
            /* background: linear-gradient(150deg, #00B98E 0%, #0072ff 160%); */
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .vision-section a:hover {
            /* background-color: #005bb5; */
        }
        .goal {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            
        }

        h1 {
            text-align: center;
            color: #00B98E;
        }

        .goals {
            background: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background: linear-gradient(150deg, #00B98E 0%, #0072ff 160%);
            color: white;
        }

        h2 {
            text-align: center;
            color: white;
        }

        ul {
            list-style-type: none;
            padding: 0;
            color: white;
        }

        li {
            margin-bottom: 20px;
            color: white;
        }

        h3 {
            color: white !important;
            margin-bottom: 5px;
        }

        p {
            margin: 0;
            line-height: 1.6;
            color: white;
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
                    <h1 class="display-5 animated fadeIn mb-4 text-primary text-start">About Us</h1> 
                        <nav aria-label="breadcrumb animated fadeIn">
                        <ol class="breadcrumb text-uppercase bg-white">
                            <li class="breadcrumb-item"><a href="user.php">Home</a></li>
                            <li class="breadcrumb-item text-body active" aria-current="page">About Us</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-md-6 animated fadeIn">
                    <img class="img-fluid" src="img/about-us.png" alt="">
                </div>
            </div>
        </div>
        <!-- Header End -->


        <!-- Contact Start -->
            <div class="container-fluid my-3">
                <div class="row">
                <section class="vision-section p-5 wow fadeInUp" data-wow-delay="0.1s">
                    <h1 class="text-light">Our Vision</h1>
                    <p>
                        At "Hire Here," we envision a world where finding skilled labor is seamless and stress-free. Our mission is to connect businesses with the best professionals in the industry, ensuring that every project is executed with excellence. We believe in empowering both laborers and clients through transparency, reliability, and unmatched service quality.
                    </p>
                    <p>
                        We are dedicated to building a community where every individual is valued and every job is handled with the utmost care. Our platform is designed to foster strong relationships between clients and laborers, ensuring that each task is completed to the highest standard. Join us in our journey to redefine labor services and set new benchmarks for success.
                    </p>
                    <!-- <img src="https://via.placeholder.com/800x400" alt="Our Vision"> -->
                    <br>
                    <!-- <a href="/services">Explore Our Services</a> -->
                </section>
                </div>
            </div>
    </div>
    <div class="container goal">
        <section class="goals  wow fadeInUp" data-wow-delay="0.1s">
            <h1 class="text-white">Our Vision and Goals</h1>
            <h2 class="text-light">Our Goals</h2>
            <ul>
                <li>
                    <h3>Expand Our Network</h3>
                    <p>Increase the number of skilled laborers and clients on our platform through targeted marketing and partnerships.</p>
                </li>
                <li>
                    <h3>Enhance Service Quality</h3>
                    <p>Ensure high standards of service and customer satisfaction with regular process reviews and training.</p>
                </li>
                <li>
                    <h3>Promote Fairness</h3>
                    <p>Foster trust with clear policies, transparent communication, and effective dispute resolution.</p>
                </li>
                <li>
                    <h3>Drive Innovation</h3>
                    <p>Invest in new technologies and continually improve our platform to stay ahead in the industry.</p>
                </li>
                <li>
                    <h3>Support Communities</h3>
                    <p>Engage in community outreach and promote ethical labor practices.</p>
                </li>
                <li>
                    <h3>Achieve Sustainable Growth</h3>
                    <p>Build a scalable business model with strategic planning and efficient resource management.</p>
                </li>
            </ul>
        </section>
    </div>
    <!-- Contact End -->
    <!--================================================== Back to Top start ===========================================-->
    <?php include 'includes/back_top.php'; ?>
    <!--================================================== Back to Top end ===========================================-->
    
    <?php include 'includes/footer.php' ?>
</body>
</html>
