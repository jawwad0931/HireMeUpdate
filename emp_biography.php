<?php
include 'config/db_connection.php';
include "includes/header.php";

session_start();

// Check if user is logged in; otherwise, redirect to login page
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header("Location: index.php");
    exit;
}

// Fetch logged-in user details
$user_id = $_SESSION['id'];
$user_name = $_SESSION['username']; // Assuming you store the username in the session

// Fetch employee details if employee_id is provided via GET parameter
if (isset($_GET['employee_id'])) {
    $employee_id = $_GET['employee_id'];
    $_SESSION['emp'] = $employee_id;

    // Fetch employee details using the employee_id
    $sql = "SELECT e.*, u.username, u.age, c.city
    FROM employees e
    JOIN users u ON e.user_id = u.id
    JOIN cities c ON e.city_id = c.id
    WHERE e.id = '$employee_id'";

    $result = $mysqli->query($sql);

    if ($result->num_rows > 0) {
        $employee = $result->fetch_assoc();
        ?>

        <!-- Display employee details in a Bootstrap card -->
        <div class="container mt-5">
            <div class="row justify-content-start align-items-center">
                <div class="col-md-6 text-start wow fadeInUp" data-wow-delay="0.1s">
                    <img src="<?php echo $employee['img']; ?>" alt="Profile Image" class="img-fluid rounded" style="height: 400px; width: auto;">
                </div>
                <div class="col-md-6 text-start mt-0 wow fadeInUp py-5" data-wow-delay="0.1s">
                    <h5 class="card-title text-primary mb-4"><?php echo $employee['username']; ?></h5>
                    <p class="card-text text-primary mb-4"><?php echo "Age: " . $employee['age']; ?></p>
                    <p class="card-text text-primary mb-4"><?php echo "Town: " . $employee['city']; ?></p>
                    <p class="card-text text-primary mb-4"><?php echo "Skills: " . $employee['skills']; ?></p>
                    <p class="card-text text-primary mb-4"><?php echo "Amount Per Hour: Rs " . $employee['amount']; ?></p>
                    <p class="card-text text-primary mb-4"><?php echo "Status: " . ucfirst($employee['status']); ?></p>
                    <a href="booking_labour.php?employee_id=<?php echo $employee_id; ?>" class="btn btn-primary mt-3 animated-btn mb-4">Book Employee</a>
                </div>
            </div>
        </div>
        <?php
    } else {
        echo "<div class='container mt-5'><p class='text-primary'>No employee found with ID " . $employee_id . "</p></div>";
    }
}

// Handling form submission to insert review
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employee_id = $_POST['employee_id'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    // Insert review into the database
    $insert_sql = "INSERT INTO reviews (employee_id, user_id, rating, comment) VALUES ('$employee_id', '$user_id', '$rating', '$comment')";
    if ($mysqli->query($insert_sql) === TRUE) {
        echo "<script>
            alert('Thank you for your review!');
            window.location.href = 'emp_biography.php?employee_id=$employee_id';
        </script>";
        exit();
    } else {
        echo "<div class='container mt-5'><p class='text-primary'>Error: " . $insert_sql . "<br>" . $mysqli->error . "</p></div>";
    }
}
?>

<!-- html part -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body class="bg-white">
<div class="container mt-5">
    <!-- Form to submit a review -->
    <h1 class="text-primary wow fadeInUp" data-wow-delay="0.1s">Submit a Review</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="wow fadeInUp" data-wow-delay="0.2s">
        <!-- Hidden field to pass employee_id -->
        <input type="hidden" name="employee_id" value="<?php echo $_GET['employee_id']; ?>">
        <!-- Display logged-in user's name -->
        <p class="text-primary">Reviewing as: <?php echo $user_name; ?></p>

        <div class="form-group">
            <label for="rating" class="text-danger">Rating</label>
            <div id="starRating" class="">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <span class="star" data-value="<?php echo $i; ?>">&star;</span>
                <?php endfor; ?>
            </div>
            <input type="hidden" id="rating" name="rating" value="0" required>
        </div>
        
        <div class="form-group">
            <label for="comment" class="text-primary">Comment:</label>
            <textarea id="comment" name="comment" class="form-control" rows="4" required></textarea>
        </div>
        
        <button type="submit" class="btn btn-primary mt-2 animated-btn">Submit Review</button>
    </form>

    <!-- Display reviews for the selected employee -->
    <?php 
    // Fetch and display reviews for a specific employee if employee_id is provided via GET parameter
    if (isset($_GET['employee_id'])) {
        $employee_id = $_GET['employee_id'];

        // Fetch reviews for the selected employee
        $select_sql = "SELECT r.rating, r.comment, r.created_at, u.username
        FROM reviews r
        JOIN users u ON r.user_id = u.id
        WHERE r.employee_id = '$employee_id'";
        $result = $mysqli->query($select_sql);
        if ($result->num_rows > 0) {
            ?>
            <!--======================================= Testimonial Start ==========================================-->
            <div class="container-xxl py-5 px-5 bg-white my-5" style="border:1px dashed green">
                <div class="container">
                    <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                        <h1 class="mb-3 text-primary">Our Clients Say!</h1>
                    </div>
                    <div class="owl-carousel testimonial-carousel wow fadeInUp" data-wow-delay="0.1s">
                        <?php
                        while ($row = $result->fetch_assoc()) {
                            ?>
                            <div class="testimonial-item rounded p-3">
                                <div class="p-4" style="border: 1px dashed green">
                                    <p class="text-primary"><?php echo $row["created_at"]; ?></p>
                                    <div class="d-flex align-items-center">
                                        <div class="ps-3">
                                            <div class="mb-1">
                                                <?php
                                                // Display stars based on rating
                                                $rating = round($row["rating"]);
                                                for ($i = 0; $i < $rating; $i++) {
                                                    echo "<i class='bi bi-star-fill text-warning'></i>";
                                                }
                                                ?>
                                            </div>
                                            <h6 class="fw-bold mb-1 text-primary"><?php echo $row["comment"]; ?></h6>
                                            <small class="text-secondary"><?php echo htmlspecialchars($row['username']); ?></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php
        } else {
            echo "<div class='container mt-5'><p class='text-primary text-center'>No testimonials found.</p></div>";
        }
    }
    $mysqli->close();
    ?>
    <!--====================================== Testimonial End ====================================================-->  
</div>
<!-- Include your JS files here -->
<!-- For example, jQuery, Bootstrap JS, and Owl Carousel JS files -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/owl.carousel@2.3.4/dist/owl.carousel.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const stars = document.querySelectorAll('#starRating .star');
        const ratingInput = document.getElementById('rating');
        
        stars.forEach(star => {
            star.addEventListener('click', function() {
                const value = this.getAttribute('data-value');
                ratingInput.value = value;

                // Reset all stars
                stars.forEach(s => s.style.color = 'black');
                
                // Set clicked stars to yellow
                for (let i = 0; i < value; i++) {
                    stars[i].style.color = 'yellow';
                }
            });
        });
    });

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

<style>
    #starRating .star {
        font-size: 2rem;
        cursor: pointer;
        transition: color 0.3s ease;
    }

    .animated-btn {
        position: relative;
        overflow: hidden;
        padding: 10px 20px;
        font-size: 16px;
        color: #fff;
        background: #007bff;
        border: none;
        border-radius: 5px;
        transition: background-color 0.3s ease, transform 0.3s ease;
    }

    .animated-btn:hover {
        background-color: #0056b3;
        transform: scale(1.05);
    }

    .animated-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: 50%;
        width: 300%;
        height: 300%;
        background: rgba(255, 255, 255, 0.2);
        transition: width 0.3s ease, height 0.3s ease, left 0.3s ease, top 0.3s ease;
        border-radius: 50%;
        z-index: 0;
        transform: translateX(-50%);
    }

    .animated-btn:hover::before {
        width: 500%;
        height: 500%;
        top: -150%;
        left: 50%;
    }

    .animated-btn span {
        position: relative;
        z-index: 1;
    }
    
    .owl-carousel .testimonial-item {
        position: relative;
        padding: 20px;
        border-radius: 10px;
        transition: background-color 0.3s ease;
    }

    .owl-carousel .testimonial-item:hover {
        background-color: #eaeaea;
    }

    .owl-carousel .testimonial-item p {
        color: #333;
        transition: color 0.3s ease;
    }

    .owl-carousel .testimonial-item:hover p {
        color: #007bff;
    }
</style>

<?php 
include "includes/footer.php";
?>

</body>
</html>

