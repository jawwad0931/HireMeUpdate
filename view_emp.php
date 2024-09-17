<?php
include 'config/db_connection.php';

session_start();
if ($_SESSION['role'] !== 'user') {
    header('Location: index.php');
    exit();
}
// This is the logged-in user
$user_id = $_SESSION['id'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link href="img/logo.png" rel="icon" type="image/png">
    <title>HireMe</title>
    <?php include 'includes/header.php'; ?>
    <style>
        .property-item {
            transition: all 0.3s ease;
            border: 1px dashed purple;
            height: 500px;
            width: 100%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .property-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .property-item img {
            height: 250px;
            width: 100%;
            object-fit: cover;
        }

        .status-indicator {
            width: 20px;
            height: 20px;
            border-radius: 50%;
        }

        .status-indicator.online {
            background-color: green;
            box-shadow: 0 0 15px green;
        }

        .status-indicator.offline {
            background-color: red;
            box-shadow: 0 0 15px red;
        }

        .rating i {
            color: #ffc107;
        }

        .fadeIn {
            animation: fadeIn 1s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        /* for button styling */
        .btn-water {
        position: relative;
        display: inline-block;
        padding: 12px 24px;
        font-size: 16px;
        font-weight: bold;
        color: #fff;
        text-transform: uppercase;
        text-decoration: none;
        border-radius: 8px;
        background: linear-gradient(45deg, #00c6ff, #0072ff); /* Gradient background */
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        transition: all 0.4s ease-in-out;
        text-align: center;
        z-index: 1;
    }

    .btn-water::before {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.3);
        border-radius: 8px;
        transform: translateY(100%);
        transition: transform 0.4s ease-in-out;
        z-index: -1;
    }

    .btn-water:hover::before {
        transform: translateY(0);
    }

    .btn-water:hover {
        color: #fff;
        background: linear-gradient(45deg, #0072ff, #00c6ff);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.4);
    }
    </style>
</head>

<body class="bg-white">
    <!-- =================================== navbar start ====================================-->
    <?php 
    include 'includes/navbar.php';
    ?>
    <!-- =================================== navbar end ====================================-->

    <!--==================================== Header Start ====================================-->
    <div class="container-fluid bg-white p-0">
        <div class="row g-0 align-items-center flex-column-reverse flex-md-row">
            <div class="col-md-6 p-5 mt-lg-5">
                <h1 class="display-5 animated fadeIn mb-4 text-primary">Working Employees</h1>
                <nav aria-label="breadcrumb animated fadeIn">
                    <ol class="breadcrumb text-uppercase bg-white">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item text-body active" aria-current="page">Employees</li>
                    </ol>
                </nav>
            </div>
            <div class="col-md-6 animated fadeIn">
                <img class="img-fluid" src="img/labours.png" alt="">
            </div>
        </div>
    </div>
    <!--====================================== Header End ======================================-->

    <!--==================================== search Start ====================================-->
    <?php
    // Include database connection
    include 'config/db_connection.php';
    // Initialize variables
    $search_keyword = '';
    $location = '';

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $search_keyword = isset($_POST['search_keyword']) ? $_POST['search_keyword'] : '';
        $location = isset($_POST['location']) ? $_POST['location'] : '';
    }

    // Fetch skills and cities for dropdowns
    $skills_query = 'SELECT DISTINCT skills FROM employees';
    $cities_query = 'SELECT DISTINCT city FROM cities';
    $skills_result = $mysqli->query($skills_query);
    $cities_result = $mysqli->query($cities_query);

    // Prepare SQL query
    $sql = "SELECT e.id, e.skills, e.city_id, e.img, e.status, e.user_id, e.amount, c.city, u.username, u.age, AVG(r.rating) AS average_rating
            FROM employees e
            LEFT JOIN cities c ON e.city_id = c.id
            LEFT JOIN reviews r ON e.id = r.employee_id
            LEFT JOIN users u ON e.user_id = u.id
            WHERE 1=1"; // 1=1 is used to simplify appending conditions
    
    if (!empty($search_keyword)) {
        $sql .= ' AND e.skills LIKE ?';
        $search_keyword = "%$search_keyword%";
    }

    if (!empty($location)) {
        $sql .= ' AND c.city = ?';
    }

    $sql .= ' GROUP BY e.id, e.skills, e.city_id, e.img, e.status, e.user_id, e.amount, c.city, u.username, u.age
              ORDER BY average_rating DESC'; // Order by average rating

    // Prepare and execute statement
    $stmt = $mysqli->prepare($sql);

    if (!empty($search_keyword) && !empty($location)) {
        $stmt->bind_param('ss', $search_keyword, $location);
    } elseif (!empty($search_keyword)) {
        $stmt->bind_param('s', $search_keyword);
    } elseif (!empty($location)) {
        $stmt->bind_param('s', $location);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    ?>
    <!-- HTML Code for Search Form and Results -->
    <div class="container-fluid bg-primary mb-5 wow fadeIn" data-wow-delay="0.1s" style="padding: 15px;">
        <div class="container">
            <form method="POST" action="">
                <div class="row g-2">
                    <div class="col-md-10">
                        <div class="row g-2">
                            <div class="col-md-6">
                                <select name="search_keyword" class="form-select border-0 py-2">
                                    <option value="">Select Skill</option>
                                    <?php while ($row = $skills_result->fetch_assoc()) { ?>
                                        <option value="<?php echo htmlspecialchars($row['skills']); ?>" <?php echo $search_keyword == $row['skills'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($row['skills']); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <select name="location" class="form-select border-0 py-2">
                                    <option value="">Select Location</option>
                                    <?php while ($row = $cities_result->fetch_assoc()) { ?>
                                        <option value="<?php echo htmlspecialchars($row['city']); ?>" <?php echo $location == $row['city'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($row['city']); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-dark border-0 w-100 py-2">Search</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- HTML Code for Displaying Search Results -->

    <!--====================================== Counter start ======================================-->
    <!-- counter stopped for danny muaff -->
    <?php
    include 'includes/user_counter.php'; 
    ?>
    <!--====================================== Counter end ======================================-->       
    
    <!--======================================  Important Message start ====================================== -->
    <div class="container">
        <hr>
        <div class="row">
            <div class="col-12">
                <div class="text-center w-100">
                    <p class="fs-5 py-5 fw-bold text-primary wow fadeIn" data-wow-delay="0.1s"">Our laborers are not just workers they are craftsmen who bring life to the structures we inhabit and the services <br/> we rely on daily. They transform challenges into solutions, turning raw materials into meaningful results that shape our communities<br/> and improve our lives.</p>
                </div>
            </div>
        </div>
        <hr>
    </div>
     <!--======================================  Important Message end ====================================== -->

    <!--====================================== Employee Bookings cards start ======================================-->  
    <div class="container-xxl py-5">
        <div class="container">
            <div class="row g-0 gx-1 align-items-end">
                <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                </div>
                <div class="tab-content">
                    <div id="tab-1" class="tab-pane fade show p-0 active">
                        <div class="row g-4">
                            <?php
                            if ($result->num_rows > 0) {
                                // Output data of each row
                                while ($row = $result->fetch_assoc()) {
                                    // Determine the status class
                                    $statusClass = $row['status'] === 'activate' ? 'online' : 'offline';

                                    echo "<div class='col-lg-3 col-md-6 wow fadeInUp' data-wow-delay='0.1s'>
                                        <div class='property-item rounded overflow-hidden'>
                                            <div class='position-relative overflow-hidden'>
                                                <a href='emp_biography.php?employee_id=" . $row['id'] . "'><img class='img-fluid' src='" . $row['img'] . "' alt='Profile Image'></a>
                                                <div class='rounded text-white position-absolute start-0 top-0 m-4 py-1 px-3'>
                                                    <div class='status-indicator " . $statusClass . "'></div>
                                                </div>
                                            </div>
                                            <div class='p-4 pb-0'>
                                                <a href='emp_biography.php?employee_id=" . $row['id'] . "'>
                                                    <h5 class='text-primary mb-3 text-center'>" . ucfirst($row['skills']) . "</h5>
                                                </a>
                                                <a class='d-block h5 mb-2 text-center' href='emp_biography.php?employee_id=" . $row['id'] . "'>Charges Per Hour: " . "<br /> Rs " . $row['amount'] . "</a>
                                                <p class='card-text'>Town: " . $row['city'] . "</p>
                                                <p class='card-text'>Employee Name : " . $row['username'] . "</p>
                                                <p class='card-text'>Age: " . $row['age'] . "</p>
                                                <div class='mt-3 rating text-center'>";

                                    // Display average rating as stars if available
                                    if ($row['average_rating']) {
                                        $rating = round($row['average_rating']);
                                        for ($i = 0; $i < $rating; $i++) {
                                            echo "<i class='bi bi-star-fill'></i>";
                                        }
                                    } else {
                                        echo 'No reviews yet';
                                    }

                                    echo "</div>
                                                <div class='col-12 text-end  pb-5'>
                                                    <a href='emp_biography.php?employee_id=" . $row['id'] . "' class='btn btn-primary btn-sm px-3 mb-3'>Hire Now</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>";
                                }
                            } else {
                                echo "<p class='text-center text-danger'>No employees found matching your criteria.</p>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--====================================== Employee Bookings cards end ======================================--> 
    
    <!--======================================= back to top start =====================================================-->
    <?php include 'includes/back_top.php'; ?>
    <!--======================================= back to top end =====================================================-->

    <!--===================================== Footer Start ==================================================-->
    <?php include 'includes/footer.php'; ?>
    <!--======================================= Footer End =======================================================-->
</body>
</html>
