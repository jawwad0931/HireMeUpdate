<!-- yeh original nahi hai bulkay yahan mai kaam kar raha hon -->

<!-- this is index.php page for admin here define some div boxes-->
<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}
$user_id = $_SESSION['id'];
// echo $user_id;
// $username = $_SESSION['username'];

// Display session message if it exists
// yeh message alert mai dikhana hai
$message = '';
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    // echo $message = $_SESSION['message'];
    echo "<script>alert($message)</script>";
    unset($_SESSION['message']);
}

// includes files here
include('config/db_connection.php');
include('Admin-includes/header.php');
include('Admin-includes/sidebar.php');
include('Admin-includes/topbar.php');
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                3

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- dobara redirect se bachne ke liye lagaya gaya hai -->
    <script type="text/javascript">
    window.history.forward();
    </script>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>HireMe</title>
</head>
<body                                                                      >
<div class="content-wrapper" style="height: auto;background-color:#effdf5;">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="index.php" class="text-primary fs-4"><i class="ion-ios-home"></i></a></li>
          </ol>
        </div>
      </div>
    </div>
  </div>
  <section class="content">
    <div class="container-fluid">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <!-- ./col -->
        <div class="col-lg-3 col-md-6 col-sm-12 p-3">
          <!-- small box -->
          <div class="small-box" style="border:1px dashed #00b98e">
            <div class="inner p-4">
              <h4 class="text-primary">Registered</h4>
              <p class="text-primary">Users Details</p>
            </div>
            <div class="icon">
              <i class="ion-person-stalker"></i>
            </div>
            <a href="admin_user_table.php" class="small-box-footer">Click <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12 p-3">
          <!-- small box -->
          <div class="small-box" style="border:1px dashed #00b98e">
            <div class="inner p-4">
              <h4 class="text-primary">Discounts</h4>
              <p class="text-primary">Assign Discount</p>
            </div>
            <div class="icon">
              <i class="ion-pricetags"></i>
            </div>
            <a href="admin_assign_discount.php" class="small-box-footer">Click <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12 p-3">
          <!-- small box -->
          <div class="small-box" style="border:1px dashed #00b98e">
            <div class="inner p-4">
              <h4 class="text-primary">Invoices</h4>
              <p class="text-primary">Employees Invoices</p>
            </div>
            <div class="icon">
              <i class="ion-document-text"></i>
            </div>
            <a href="admin_emp_billing.php" class="small-box-footer">Click <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12 p-3">
          <!-- small box -->
          <div class="small-box" style="border:1px dashed #00b98e">
            <div class="inner p-4">
              <h4 class="text-primary">Stripe pay</h4>
              <ac class="text-primary">stripe pay accounts</p>
            </div>
            <div class="icon">
              <i class="ion-cash"></i>
            </div>
            <a href="admin_pay_record.php" class="small-box-footer">Click <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      <!-- /.row -->
      <!-- Main row -->
      <!-- /.row (main row) -->
    </div><!-- /.container-fluid -->
  </section>
</div>

<!-- footer -->
<?php
    include('Admin-includes/footer.php');
?>
<!-- Boostrap version 5 code use for main purposes -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</body>
</html>




