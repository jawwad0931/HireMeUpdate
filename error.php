<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include 'includes/header.php'; ?>
    <title>Error Page</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            color: #fff;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            text-align: center;
        }

        .error-title {
            font-size: 5rem;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .error-message {
            font-size: 1.5rem;
            margin-bottom: 20px;
        }

        .error-link {
            color: #00B98E;
            text-decoration: none;
            font-size: 1.2rem;
            font-weight: bold;
        }

        .error-link:hover {
            text-decoration: underline;
        }

        .error-img {
            width: 200px;
            height: auto;
            margin: 20px auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-title text-danger">404</div>
        <div class="error-message text-danger">Oops! The page you are looking for does not exist.</div>
        <a href="index.php" class="error-link text-decoration-underline">Return to Home</a>
    </div>
</body>
</html>
