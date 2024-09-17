<?php
include 'config/db_connection.php';
// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $discount_code = $_POST['discount_code'];
    $discount_percentage = $_POST['discount_percentage'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $created_at = date('Y-m-d H:i:s'); // Current date and time

    // Prepare and bind
    $stmt = $mysqli->prepare("INSERT INTO discounts (discount_code, discount_percentage, start_date, end_date, created_at) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sisss", $discount_code, $discount_percentage, $start_date, $end_date, $created_at);

    // Execute the statement
    if ($stmt->execute()) {
        // echo "New record created successfully";
        echo "<script>alert('New record created successfully.');</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hire Me</title>
    <?php include 'includes/header.php'; ?>
    <!-- Bootstrap CSS -->
    <style>
        body {
            background-color: #effdf5;
        }
        .form-container {
            background: #ffffff; /* white background for the form */
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .container {
            margin-top: 50px;
        }
    </style>
</head>
<body class="">
    <div class="container w-50">
        <div class="row justify-content-center">
            <div class="col-md-6 form-container">
                <h1 class="text-start text-primary">Insert Discount</h1>
                <form action="" method="post">
                    <div class="form-group text-primary">
                        <label for="discount_code">Discount Code:</label>
                        <input type="text" class="form-control" id="discount_code" name="discount_code" required>
                    </div>

                    <div class="form-group text-primary">
                        <label for="discount_percentage">Discount Percentage:</label>
                        <input type="number" class="form-control" id="discount_percentage" name="discount_percentage" required>
                    </div>

                    <div class="form-group text-primary">
                        <label for="start_date">Start Date:</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" required>
                    </div>

                    <div class="form-group text-primary">
                        <label for="end_date">End Date:</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" required>
                    </div>

                    <button type="submit" class="btn btn-primary btn-sm btn-block my-2">Submit</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

