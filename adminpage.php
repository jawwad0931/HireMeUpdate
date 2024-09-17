<!-- this is index.php page for admin here define some div boxes-->
<?php
// includes files here
session_start();
include('config/db_connection.php');
include('Admin-includes/header.php');
include('Admin-includes/sidebar.php');
include('Admin-includes/topbar.php');



if ($_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

?>
<div class="content-wrapper" style="height: auto;">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Dashboard</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="index.php" class="text-dark">Back Home</a></li>
          </ol>
        </div>
      </div>
    </div>
  </div>
  <section class="content">
    <div class="container-fluid">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-md-12">

        <!-- ./col -->
        <div class="col-lg-6 col-md-6 col-sm-12 p-3">
          <!-- small box -->
          <div class="small-box bg-danger">
            <div class="inner p-4">
              <h4>Appointment</h4>
              <p>Lawyer Appointment Details</p>
            </div>
            <div class="icon">
              <i class="ion-clock"></i>
            </div>
            <a href="AppointLawyer.php" class="small-box-footer">Click <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
      </div>
      <!-- /.row -->
      <!-- Main row -->
      <!-- /.row (main row) -->
    </div><!-- /.container-fluid -->
  </section>
</div>
<!-- Boostrap version 5 code use for main purposes -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>