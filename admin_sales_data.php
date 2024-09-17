<?php
include("config/db_connection.php");
session_start();

if ($_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_sale'])) {
        $sales_percentage = $_POST['sales_percentage'];
        $image = $_FILES['image'];

        // Handle image upload
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($image["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is an actual image or fake image
        $check = getimagesize($image["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        // Check file size
        if ($image["size"] > 500000) { // 500KB limit
            // echo "Sorry, your file is too large.";
            echo "<script>alert('Sorry, your file is too large.');</script>";
            
            $uploadOk = 0;
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif") {
                echo "<script>alert('Sorry, only JPG, JPEG, PNG & GIF files are allowed.');</script>";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "<script>alert('Sorry, your file was not uploaded.');</script>";
            // echo "Sorry, your file was not uploaded.";
        } else {
            if (move_uploaded_file($image["tmp_name"], $target_file)) {
                // Insert into database
                $sql = "INSERT INTO sales (image, sales_percentage) VALUES (?, ?)";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("ss", $target_file, $sales_percentage);
                $stmt->execute();
                $stmt->close();
            } else {
                // echo "Sorry, there was an error uploading your file.";
                echo "<script>alert('Sorry, there was an error uploading your file.');</script>";

            }
        }
    } elseif (isset($_POST['delete_sale'])) {
        $sale_id = $_POST['sale_id'];

        // Delete from database
        $sql = "DELETE FROM sales WHERE id = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $sale_id);
        $stmt->execute();
        $stmt->close();
    }
}

// Fetch sales data
$sql = "SELECT * FROM sales";
$result = $mysqli->query($sql);

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
    <link href="img/logo.png" rel="icon" type="image/png">
    <title>HireMe</title>
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
    <style>
        *{
            color: #00B98E;
        }
        body {
            font-family: 'Heebo', sans-serif;
            margin: 0;
            padding: 0;
        }

        .hero-section {
            background-color: #effdf5;
            color: #fff;
            padding: 60px 0;
            text-align: center;
            margin-bottom: 20px;
        }

        .hero-section h1 {
            margin: 0;
            font-size: 2.5em;
            font-weight: 600;
        }

        .container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            border-radius: 8px;
        }

        .card {
            margin-bottom: 20px;
        }

        .card-header {
            background-color: #effdf5;
            color: #fff;
            font-weight: 600;
            font-size: 1.5em;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-group input[type="file"],
        .form-group input[type="number"] {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .btn-submit {
            background-color: #28a745;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-submit:hover {
            background-color: #218838;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th,
        table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #f2f2f2;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        .delete-button {
            background-color: #dc3545;
            border: none;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        .delete-button:hover {
            background-color: #c82333;
        }

        img {
            max-width: 100px;
            height: auto;
        }
        /* for file field */
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
<body>
    <div class="container">
        <div class="card" style="border: 1px dashed #00B98E">
            <div class="card-header text-primary">
                Add New Sale
            </div>
            <div class="card-body">
                <form action="admin_sales_data.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group custom-file-upload">
                        <label for="profile_picture" class="form-label text-primary fs-6 fw-light">Image:</label>
                        <!-- <input type="file" name="profile_picture" id="profile_picture" class="form-control" style="border: none; box-shadow: none;" required> -->
                        <input type="file" name="image" id="profile_picture" class="form-control" style="border: none; box-shadow: none;" required>
                    </div>


                    <div class="form-group">
                        <label for="sales_percentage">Sales Percentage:</label>
                        <input type="number" step="0.01" name="sales_percentage" id="sales_percentage" required>
                    </div>
                    <button type="submit" name="add_sale" class="btn-submit btn-sm px-4 py-1">Add Sale</button>
                </form>
            </div>
        </div>

        <div class="card" style="border: 1px dashed #00B98E">
            <div class="card-header text-primary">
                Current Sales
            </div>
            <div class="card-body">
                <table>
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Sales Percentage</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><img src="<?php echo $row['image']; ?>" height="45px" width="45px" class="rounded-circle" alt="Sale Image"></td>
                                <td><?php echo rtrim(rtrim($row['sales_percentage'], '0'), '.'); ?>%</td>
                                <td>
                                    <form action="admin_sales_data.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="sale_id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" name="delete_sale" class="delete-button btn-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
