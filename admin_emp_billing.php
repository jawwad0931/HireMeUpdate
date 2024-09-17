<?php
// Start session and include database connection
session_start();
include 'config/db_connection.php';

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include 'includes/header.php'; ?>
    <title>Hire Me</title>
</head>
<body>
<h2 class="mt-5 text-primary text-decoration-underline text-center">Employee Work Records & Billings</h2>
<hr />
<div class="container">
<div class="row">
  <?php
  // Fetching employee work records and billings
  // Assuming $mysqli is your mysqli connection
  $workResult = $mysqli->query("
      SELECT ew.booking_id, ew.employee_id, ew.user_id, ew.hours_worked, ew.work_status, ew.payment_status, ew.total_amount, ew.created_at,
             u.username AS user_name, e.username AS employee_name, emp.amount AS employee_amount
      FROM employee_work ew
      LEFT JOIN users u ON ew.user_id = u.id
      LEFT JOIN users e ON ew.employee_id = e.id
      LEFT JOIN employees emp ON ew.employee_id = emp.id
  ");

  if (!$workResult) {
      die("Query failed: " . $mysqli->error);
  }
  ?>

  <?php if ($workResult->num_rows > 0): ?>
      <?php while ($row = $workResult->fetch_assoc()): ?>
          <div class="col-lg-3 col-md-4 mb-4">
              <div class="card border-primary">
                  <div class="card-body">
                      <h5 class="card-title mb-4">
                          <span class="badge bg-primary">Booking ID:
                              <?php echo htmlspecialchars($row['booking_id']); ?></span>
                      </h5>
                      <div class="d-flex justify-content-between">
                          <h6 class="card-subtitle mb-2 text-muted">Employee
                              Id: <?php echo htmlspecialchars($row['employee_id']); ?></h6>
                          <h6 class="card-subtitle mb-2 text-muted">User:
                              <?php echo htmlspecialchars($row['user_name']); ?></h6>
                      </div>
                      <hr>
                      <div class="mb-3">
                          <p class="card-text">Hours Worked: <?php echo htmlspecialchars($row['hours_worked']); ?>
                              hours</p>
                          <p class="card-text">Work Status: <span
                                  class="badge <?php echo $row['work_status'] == 'Completed' ? 'bg-success' : 'bg-warning'; ?>">
                                  <?php echo htmlspecialchars($row['work_status']); ?>
                              </span></p>
                          <p class="card-text">Payment Status: <span
                                  class="badge <?php echo $row['payment_status'] == 'Paid' ? 'bg-success' : 'bg-danger'; ?>">
                                  <?php echo htmlspecialchars($row['payment_status']); ?>
                              </span></p>


                          <?php $government_tax = 50; ?>
                          <p class="card-text">
                              <span>
                                  <?php
                                  // Get the total amount from the database row
                                  $total_amount = htmlspecialchars($row['total_amount']);
                                  
                                  echo "<p>Actual Amount: $total_amount</p>";

                                  echo "<p>Government Tax: $government_tax</p>";

                                  // Calculate the total amount including tax
                                  $total_amount_with_tax = $total_amount + $government_tax;
                                  
                                  // Display the total amount with tax
                                  echo 'Total with tax ' . $total_amount_with_tax;
                                  ?>
                              </span>
                          </p>   
                      </div>
                  </div>
                  <div class="card-footer text-end">
                      <span class="">User Invoice</span>
                  </div>
              </div>
          </div>
      <?php endwhile; ?>
  <?php else: ?>
      <p>No records found.</p>
  <?php endif; ?>
</div>
<hr />
</div>
</body>
</html>