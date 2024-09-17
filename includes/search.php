<?php

// Include the database connection script
include 'config/db_connection.php';


$search_keyword = "";
$location = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search_keyword = isset($_POST['search_keyword']) ? $_POST['search_keyword'] : "";
    $location = isset($_POST['location']) ? $_POST['location'] : "";
}


$skills_query = "SELECT DISTINCT skills FROM employees";
$cities_query = "SELECT DISTINCT city FROM cities";

$skills_result = $mysqli->query($skills_query);
$cities_result = $mysqli->query($cities_query);


$sql = "SELECT e.id, e.skills, e.city_id, e.img, e.status, e.user_id, e.amount, c.city, u.username, u.age, AVG(r.rating) AS average_rating
        FROM employees e
        LEFT JOIN cities c ON e.city_id = c.id
        LEFT JOIN reviews r ON e.id = r.employee_id
        LEFT JOIN users u ON e.user_id = u.id
        WHERE 1";


if (!empty($search_keyword)) {
    $sql .= " AND e.skills LIKE ?";
    $search_keyword = "%$search_keyword%";
}

if (!empty($location)) {
    $sql .= " AND c.city = ?";
}


$sql .= " GROUP BY e.id, e.skills, e.city_id, e.img, e.status, e.user_id, e.amount, c.city, u.username, u.age";

$stmt = $mysqli->prepare($sql);

if (!empty($search_keyword) && !empty($location)) {
    $stmt->bind_param("ss", $search_keyword, $location);
} elseif (!empty($search_keyword)) {
    $stmt->bind_param("s", $search_keyword);
} elseif (!empty($location)) {
    $stmt->bind_param("s", $location);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Customer List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="search.css">
</head>

<body>

    <!-- Search Start -->
    <div class="container-fluid bg-primary mb-5 wow fadeIn" data-wow-delay="0.1s" style="padding: 35px;">
    <div class="container">
        <form method="POST" action="">
            <div class="row g-2">
                <div class="col-md-10">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <select name="search_keyword" class="form-select border-0 py-3">
                                <option value="">Select Skill</option>
                                <?php while ($row = $skills_result->fetch_assoc()) { ?>
                                    <option value="<?php echo htmlspecialchars($row['skills']); ?>" <?php echo ($search_keyword == $row['skills']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($row['skills']); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <select name="location" class="form-select border-0 py-3">
                                <option value="">Select Location</option>
                                <?php while ($row = $cities_result->fetch_assoc()) { ?>
                                    <option value="<?php echo htmlspecialchars($row['city']); ?>" <?php echo ($location == $row['city']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($row['city']); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-dark border-0 w-100 py-3">Search</button>
                </div>
            </div>
        </form>
    </div>
</div>

    <!-- Search End -->

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
</body>

</html>
