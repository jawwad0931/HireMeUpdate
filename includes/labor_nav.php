<!DOCTYPE html>
<html lang="en">
<head>
</head>
<body>
    <!-- agar background ki need ho tou yahan apply karna bg-white-->
    <div class="container-xxl pt-0 mt-0">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->



        <style>
            .nav-link :hover{
                color: yellow !important;
            }
        </style>
        <!-- Navbar Start -->
        <div class="container-fluid nav-bar py-0">
            <nav class="navbar navbar-expand-lg bg-white py-1 px-4"  style="height:50px !important">
                <a href="index.php" class="navbar-brand d-flex align-items-center text-center">
                    <div class="p-2 me-2">
                        <img class="img-fluid" src="img/logo.png" height="40px" width="40px" alt="Icon" style="">
                    </div>
                    <!-- <h1 class="m-0 text-primary fs-3">HireMe</h1> -->
                </a>
                <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav ms-auto">
                        <a href="labour_emp_table.php" class="nav-item nav-link text-primary">Employee Manage</a>              
                        <a href="booking_users.php" class="nav-item nav-link text-primary">Users Details</a>
                        <a href="admin_emp_add.php" class="nav-item nav-link text-primary">Employee Skills</a>
                        <a href="labour_work.php" class="nav-item nav-link text-primary">Invoices</a>
                        <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-primary" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php echo $_SESSION['username']; ?>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item text-primary" href="edit_profile.php?id=<?php echo $user_id; ?>" class="nav-item nav-link active">Edit Profile</a></li>
                            <li><a class="dropdown-item text-primary" href="logout.php">Logout</a></li>
                        </ul>
                        </li>
                    </div>
                    </nav>
                    </div>   
</body>
</html>


