<?php
include("config/db_connection.php");
session_start();

if ($_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['assign_discount'])) {
        $user_id = $_POST['user_id'];
        $discount_id = $_POST['discount_id'];

        $sql = "INSERT INTO user_discounts (user_id, discount_id) VALUES (?, ?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("ii", $user_id, $discount_id);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['truncate_discounts'])) {
        $sql = "TRUNCATE TABLE user_discounts";
        $mysqli->query($sql);
    }
}

// Fetch users and discounts
$sql_users = "SELECT id, username FROM users WHERE role = 'user'";
$result_users = $mysqli->query($sql_users);

$sql_discounts = "SELECT id, discount_code FROM discounts";
$result_discounts = $mysqli->query($sql_discounts);

// Fetch assigned discounts for display
$sql_assigned_discounts = "
    SELECT ud.id, u.username AS user_name, d.discount_code 
    FROM user_discounts ud
    JOIN users u ON ud.user_id = u.id
    JOIN discounts d ON ud.discount_id = d.id
";
$result_assigned_discounts = $mysqli->query($sql_assigned_discounts);

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
            font-family: Arial, sans-serif;
            background-color: #effdf5;
            margin: 0;
            padding: 20px;
            color: white;
        }

        .container {
            background-color: #effdf5 !important;
            max-width: 400px;
            margin: auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            color: black;
        }

        h1 {
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        label {
            font-weight: bold;
        }

        select, button {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            margin-top: 10px;
        }

        button:hover {
            background-color: #45a049;
        }

        a {
            display: block;
            margin-top: 20px;
            text-align: center;
            text-decoration: none;
            color: #333;
        }

        a:hover {
            text-decoration: underline;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-start text-primary">Assign Discount</h2>
        <form action="admin_assign_discount.php" method="POST">
            <label for="user_id">User:</label>
            <select name="user_id" required>
                <?php while ($row = $result_users->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo $row['username']; ?></option>
                <?php endwhile; ?>
            </select>
            <label for="discount_id">Discount:</label>
            <select name="discount_id" required>
                <?php while ($row = $result_discounts->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo $row['discount_code']; ?></option>
                <?php endwhile; ?>
            </select>
            <button type="submit" name="assign_discount" class="btn btn-sm btn-primary w-50">Assign Discount</button>
            <button type="submit" name="truncate_discounts" class="btn btn-sm btn-danger w-50 mt-2">Truncate Discounts</button>
        </form>

        <!-- Display the assigned discounts in a table -->
        <h2 class="text-primary mt-4">Assigned Discounts</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Discount Code</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result_assigned_discounts->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['user_name']; ?></td>
                    <td><?php echo $row['discount_code']; ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
