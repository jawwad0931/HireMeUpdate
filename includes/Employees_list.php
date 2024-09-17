<?php
// Include the database connection script
include 'config/db_connection.php';

// Query to fetch data from the employees table
$query = "SELECT id, skills, img, status, amount FROM employees";
$result = $mysqli->query($query);

// Check for query errors
if (!$result) {
    die("Query failed: " . $mysqli->error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        .property-item {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease-in-out;
        }

        .property-item:hover {
            transform: scale(1.05);
        }

        .property-item img {
            object-fit: cover;
        }

        .status-indicator {
            position: absolute;
            top: 10px;
            right: 10px;
            display: flex;
            align-items: center;
            padding: 5px 10px;
            border-radius: 15px;
            font-weight: bold;
            color: white;
        }

        .status-indicator.online {
            background-color: #28a745;
            color: white;
        }

        .status-indicator.offline {
            background-color: #dc3545;
            color: white;
        }

        .property-item .text-primary {
            color: #007bff;
        }
    </style>
</head>

<body>
    <!-- Employee List Start -->
    <div class="container my-5">
    <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
        <!-- <h1 class="mb-3">Employee List</h1> -->
    </div>
    <div class="row g-4">
        <?php
        if ($result->num_rows > 0) {
            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                // Determine the status class and text
                $statusClass = $row["status"] === 'activate' ? 'online' : 'offline';
                $statusText = $row["status"] === 'activate' ? 'Online' : 'Offline';

                echo "<div class='col-lg-3 col-md-6'>
                        <div class='property-item rounded overflow-hidden shadow-lg'>
                            <div class='position-relative overflow-hidden'>
                                <img class='img-fluid rounded-top image-hover' src='" . htmlspecialchars($row["img"]) . "' alt='Profile Image'>
                                <div class='status-indicator " . htmlspecialchars($statusClass) . "'>
                                    " . htmlspecialchars($statusText) . "
                                </div>
                            </div>
                            <div class='p-4'>
                                <h5 class='text-primary mb-3 text-center'>" . htmlspecialchars($row["skills"]) . "</h5>
                                <p class='text-primary mb-3 text-center'>Amount: Rs " . htmlspecialchars($row["amount"]) . "</p>
                            </div>
                        </div>
                    </div>";
            }
        } else {
            echo "<div class='col-12'><p class='text-center'>No employees found</p></div>";
        }

        // Close the connection
        $mysqli->close();
        ?>
    </div>
    </div>
    <!-- Employee List End -->

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
</body>

</html>
