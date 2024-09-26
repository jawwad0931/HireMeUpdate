<?php
session_start();
include('config/db_connection.php');

// Check if user is logged in
// if (!isset($_SESSION['id'])) {
//     header("Location: index.php");
//     exit();
// }

// ye user ke table se id aa raha hai
$id = $_SESSION['id'];

// Fetch user details from the database
$query = "SELECT * FROM users WHERE id = $id";
$result = mysqli_query($mysqli, $query);
$user = mysqli_fetch_assoc($result);

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $age = $_POST['age']; 
    $contact = $_POST['contact'];
    $profileimage = $_FILES['profileimage']['name'];
    $AcceptTerm = isset($_POST['AcceptTerm']) ? 1 : 0;

    // Hash the password if it's not empty
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $password_query = "password='$hashed_password', ";
    } else {
        $password_query = "";
    }

    // Update user details in the database
    $update_query = "UPDATE users SET 
        username='$username', 
        email='$email', 
        $password_query
        age='$age', 
        contact='$contact', 
        profileimage='$profileimage', 
        AcceptTerm='$AcceptTerm' 
        WHERE id = $id";
    
    if (mysqli_query($mysqli, $update_query)) {
        echo "<script>alert('Profile updated successfully.'); window.location.href='user.php';</script>";
    } else {
        echo "Error updating profile: " . mysqli_error($mysqli);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>HireMe</title>
    <!-- Favicon -->
    <link href="img/logo.png" rel="icon" type="image/png">

    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Inter:wght@700;800&display=swap" rel="stylesheet">
    
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">

</head>
<style>
   <style>
     .custom-file-upload {
            background-color: #f8f9fa;
            border: 2px dashed #00B98E;
            padding: 20px;
            text-align: center;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        .custom-file-upload:hover {
            background-color: #e9ecef;
            border-color: #00A078;
        }
        .form-label {
            font-weight: bold;
            font-size: 1.2em;
            color: #007BFF;
        }
</style>
<body class="bg-white">
    <?php include 'includes/lobor_nav.php'; ?>
    <div class="container mt-5">
        <div class="row m-5">
            <h2 class="text-primary mb-4">Edit Profile</h2>
            <div class="col-lg-6">
            <form method="post" action="edit_profile.php" enctype="multipart/form-data">
            <div class="row">
                <div class="col-6">
                <div class="mb-3">
                <label for="username" class="form-label text-primary">Username:</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>">
            </div>
                </div>
                <div class="col-6">
                <div class="mb-3">
                <label for="email" class="form-label text-primary">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">
            </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                <div class="mb-3">
                <label for="password" class="form-label text-primary">Password:</label>
                <input type="password" class="form-control" id="password" name="password">
                <small class="form-text text-danger">Leave blank if you don't want to change the password.</small>
            </div>
                </div>
                <div class="col-6">
                <div class="mb-3">
                <label for="age" class="form-label text-primary">Age:</label>
                <input type="text" class="form-control" id="age" name="age" value="<?php echo htmlspecialchars($user['age']); ?>">
            </div>
                </div>
            </div>
            <div class="mb-3">
                <label for="contact" class="form-label text-primary">Update New Contact:</label>
                <input type="text" class="form-control" id="contact" name="contact" value="<?php echo htmlspecialchars($user['contact']); ?>">
            </div>
            <div class="mb-3 custom-file-upload">
                <label for="profileimage" class="form-label text-primary">Profile Image:</label>
                <input type="file" class="form-control" id="profileimage" name="profileimage" style="border: none; box-shadow: none;">
            </div>
            <button type="submit" class="btn btn-primary btn-sm px-3 text-white">Update Profile</button>
            <a href="javascript:void(0);" onclick="confirmDelete()" class="btn btn-danger btn-sm px-3 text-white">Delete Profile</a>
        </form>
            </div>
            <div class="col-lg-6 d-flex justify-content-center">
                <img src="img/profile.png" height="400px" width="400px" class=""   alt="">
            </div>
        </div>
    </div>
     <!-- JavaScript Libraries -->
     <script>
        function confirmDelete() {
            if (confirm('Are you sure you want to delete your profile?')) {
                window.location.href = 'delete_profile.php?delete_id=<?php echo $id; ?>';
            } else {
                window.location.href = 'edit_profile.php';
            }
        }
    </script>
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="lib/wow/wow.min.js"></script>
<script src="lib/easing/easing.min.js"></script>
<script src="lib/waypoints/waypoints.min.js"></script>
<script src="lib/owlcarousel/owl.carousel.min.js"></script>

<!-- Template Javascript -->
<script src="js/main.js"></script>
</body>
</html>
