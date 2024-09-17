<?php
session_start();
if ($_SESSION['role'] !== 'employee') {
    header("Location: index.php");
    exit;
}

include 'config/db_connection.php'; // Include the database connection script

// Fetch users for the dropdown excluding 'user' and 'admin' roles
$users_sql = "SELECT id, username FROM users WHERE role NOT IN ('user', 'admin')";
$users_result = $mysqli->query($users_sql);

// Variables to retain form data on failure
$selected_skills = [];
$selected_city_id = '';
$selected_user_id = '';
$selected_status = '';
$amount = '';

$notice = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $selected_skills = isset($_POST['skills']) ? $_POST['skills'] : [];
    $skills = implode(",", $selected_skills); // Convert array to comma-separated string
    $selected_city_id = isset($_POST['city_id']) ? (int)$_POST['city_id'] : '';
    $selected_status = isset($_POST['status']) ? $mysqli->real_escape_string($_POST['status']) : '';
    $selected_user_id = isset($_POST['user_id']) ? (int)$_POST['user_id'] : '';
    $amount = isset($_POST['amount']) ? (float)$_POST['amount'] : '';

    // Handle file upload
    $target_dir = "uploads/images/";
    $target_file = $target_dir . basename($_FILES["img"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $uploadOk = 1;

    // Check if image file is an actual image or fake image
    $check = getimagesize($_FILES["img"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        $notice = "<div class='alert alert-danger' role='alert'>File is not an image.</div>";
        $uploadOk = 0;
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        // $notice = "<div class='alert alert-danger' role='alert'>Sorry, file already exists.</div>";
        echo "<script>alert('Sorry, file already exists.')</script>";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["img"]["size"] > 500000) {
        $notice = "<div class='alert alert-danger' role='alert'>Sorry, your file is too large.</div>";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        $notice = "<div class='alert alert-danger' role='alert'>Sorry, only JPG, JPEG, PNG & GIF files are allowed.</div>";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "<script>alert('Sorry, your file was not uploaded.')<script>";
        // $notice .= "<div class='alert alert-danger' role='alert'>Sorry, your file was not uploaded.</div>";
    } else {
        if (move_uploaded_file($_FILES["img"]["tmp_name"], $target_file)) {
            // Insert the employee record using prepared statements
            $stmt = $mysqli->prepare("INSERT INTO employees (skills, city_id, img, status, user_id, amount) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sissid", $skills, $selected_city_id, $target_file, $selected_status, $selected_user_id, $amount);

            if ($stmt->execute()) {
                $notice = "<div class='alert alert-success' role='alert'>New record created successfully.</div>";
                // Redirect to the same page to clear form fields
                echo "<script>alert('New skills created successfully.')</script>";
                header("Location: " . $_SERVER['PHP_SELF']);
                exit;
            } else {
                $notice = "<div class='alert alert-danger w-50' role='alert'>Error: " . $stmt->error . "</div>";
            }

            $stmt->close();
        } else {
            $notice = "<div class='alert alert-danger w-50' role='alert'>Sorry, there was an error uploading your file.</div>";
        }
    }

    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HireMe</title>
    <?php include 'includes/header.php'; ?>
    <style>
        .form-container {
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .form-control, .form-select {
            border-radius: 4px;
        }

        .btn-custom {
            background: linear-gradient(to right, #00c6ff, #0072ff);
            color: white;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            cursor: pointer;
        }

        .btn-custom:hover {
            background: linear-gradient(to right, #0072ff, #00c6ff);
        }

        .file-input {
            margin-top: 10px;
        }

        .alert-custom {
            /* border: 1px dashed #d6d8db; */
            color: #495057;
        }

        .alert-custom h4 {
            margin-bottom: 10px;
        }
        .container_top{
            display: flex;
            justify-content: end;
        }

        /* for image */
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
</head>
<body class="bg-white">

<div class="container">
    <div class="row container_top">
        <div class="col-lg-5 my-5 w-25">
<?php if ($notice) echo $notice; ?>

<form class="wow fadeIn" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
    <h2 class="text-primary">Add Further Yourself</h2>    
    <div class="form-group">
        <label for="skills">Skills:</label>
        <select id="skills" name="skills[]" class="form-control" required>
            <option value="Plumber" <?php echo in_array("Plumber", $selected_skills) ? 'selected' : ''; ?>>Plumber</option>
            <option value="Carpenter" <?php echo in_array("Carpenter", $selected_skills) ? 'selected' : ''; ?>>Carpenter</option>
            <option value="Electrician" <?php echo in_array("Electrician", $selected_skills) ? 'selected' : ''; ?>>Electrician</option>
            <option value="Painter" <?php echo in_array("Painter", $selected_skills) ? 'selected' : ''; ?>>Painter</option>
            <option value="Mechanic" <?php echo in_array("Mechanic", $selected_skills) ? 'selected' : ''; ?>>Mechanic</option>
            <option value="Welder" <?php echo in_array("Welder", $selected_skills) ? 'selected' : ''; ?>>Welder</option>
            <option value="Mason" <?php echo in_array("Mason", $selected_skills) ? 'selected' : ''; ?>>Mason</option>
            <option value="Landscaper" <?php echo in_array("Landscaper", $selected_skills) ? 'selected' : ''; ?>>Landscaper</option>
            <option value="Roofing Specialist" <?php echo in_array("Roofing Specialist", $selected_skills) ? 'selected' : ''; ?>>Roofing Specialist</option>
            <option value="HVAC Technician" <?php echo in_array("HVAC Technician", $selected_skills) ? 'selected' : ''; ?>>HVAC Technician</option>
            <option value="Locksmith" <?php echo in_array("Locksmith", $selected_skills) ? 'selected' : ''; ?>>Locksmith</option>
            <option value="Flooring Specialist" <?php echo in_array("Flooring Specialist", $selected_skills) ? 'selected' : ''; ?>>Flooring Specialist</option>
            <option value="Plasterer" <?php echo in_array("Plasterer", $selected_skills) ? 'selected' : ''; ?>>Plasterer</option>
            <option value="Glass Installer" <?php echo in_array("Glass Installer", $selected_skills) ? 'selected' : ''; ?>>Glass Installer</option>
            <option value="Drywaller" <?php echo in_array("Drywaller", $selected_skills) ? 'selected' : ''; ?>>Drywaller</option>
            <option value="Tile Installer" <?php echo in_array("Tile Installer", $selected_skills) ? 'selected' : ''; ?>>Tile Installer</option>
            <option value="Concrete Finisher" <?php echo in_array("Concrete Finisher", $selected_skills) ? 'selected' : ''; ?>>Concrete Finisher</option>
            <option value="Insulation Installer" <?php echo in_array("Insulation Installer", $selected_skills) ? 'selected' : ''; ?>>Insulation Installer</option>
            <option value="Pressure Washer" <?php echo in_array("Pressure Washer", $selected_skills) ? 'selected' : ''; ?>>Pressure Washer</option>
            <option value="Painter" <?php echo in_array("Painter", $selected_skills) ? 'selected' : ''; ?>>Painter</option>
        </select>
    </div>

    <div class="form-group">
        <label for="city_id">Town:</label>
        <select id="city_id" name="city_id" class="form-control" required>
            <?php
            $cities_sql = "SELECT id, city FROM cities";
            $cities_result = $mysqli->query($cities_sql);
            while ($row = $cities_result->fetch_assoc()) {
                echo "<option value='{$row['id']}'" . ($row['id'] == $selected_city_id ? ' selected' : '') . ">{$row['city']}</option>";
            }
            ?>
        </select>
    </div>

    <div class="form-group">
        <label for="status">Status:</label>
        <select id="status" name="status" class="form-control" required>
            <option value="active" <?php echo $selected_status == 'active' ? 'selected' : ''; ?>>Active</option>
            <option value="inactive" <?php echo $selected_status == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
        </select>
    </div>

    <div class="form-group">
        <label for="user_id">Assign User:</label>
        <select id="user_id" name="user_id" class="form-control" required>
            <?php
            while ($user = $users_result->fetch_assoc()) {
                echo "<option value='{$user['id']}'" . ($user['id'] == $selected_user_id ? ' selected' : '') . ">{$user['username']}</option>";
            }
            ?>
        </select>
    </div>

    <div class="form-group">
        <label for="amount">Hourly Amount:</label>
        <input type="number" id="amount" name="amount" class="form-control" step="0.01" value="<?php echo htmlspecialchars($amount); ?>" required>
    </div>
    
    <label for="profile_picture" class="mt-1">Upload Image:</label>
    <div class="form-group file-input custom-file-upload">
        <input type="file" id="profile_picture" name="img" class="form-control-file" style="border: none; box-shadow: none;" required>
    </div>

    <button type="submit" class="btn btn-primary btn-sm my-2">Submit</button>
</form>
        </div>
        <div class="col-lg-7 my-0 py-5">
        <!-- Important Note Alert Message -->
        <div class="alert alert-custom" role="alert">
            <h4 class="alert-heading text-danger">Important Note</h4>
            <p class="text-danger  wow fadeInUp" data-wow-delay="0.1s">
                Please review and manage your bookings carefully. You can accept or reject<br/> bookings based on your availability. 
                Make sure to  update the status of each <br/> booking promptly to reflect accurate information.
            </p>
            <hr>
    <p class="mb-0 text-danger wow fadeIn">For any issues, please contact the support team.</p>
        </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!--=================================== back to top start ==============================================-->
<?php include 'includes/back_top.php'; ?>
<?php include 'includes/footer.php'; ?>
            <!--=================================== back to top end ==============================================-->
</body>
</html>
