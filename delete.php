
<?php 
include 'config/db_connection.php';
// Handle delete request
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    echo $delete_id;
    $sql = "DELETE FROM users WHERE id = $delete_id";
    $check_delete_query = mysqli_query($mysqli, $sql);
    if($check_delete_query){
        echo "Record deleted successfully";
        header("location: admin_user_table.php");
        exit;
    }else{
        echo "Error deleting record: " . $mysqli->error;
    }
}




// Handle delete for emp_details
if (isset($_GET['emp_id'])) {
    $emp_id = $_GET['emp_id'];
    echo $emp_id;
    $sql = "DELETE FROM employees WHERE id = $emp_id";
    $check_emp_delete_query = mysqli_query($mysqli, $sql);
    if($check_emp_delete_query){
        echo "Record deleted successfully";
        header("location: labour_emp_table.php");
        exit;
    }else{
        echo "Error deleting record: " . $mysqli->error;
    }
    // if ($mysqli->query($sql) === TRUE) {
    //     echo "Record deleted successfully";
    // } else {
    //     echo "Error deleting record: " . $mysqli->error;
    // }
}


// employee_delete_id
// Handle delete for Admin emp_details Delete
if(isset($_GET['employee_delete_id'])){
    $admin_emp_delete = $_GET['employee_delete_id'];
    $sql = "DELETE FROM employees WHERE id = $admin_emp_delete";
    $check_delete_query = mysqli_query($mysqli, $sql);
    if($check_delete_query){
        echo "Record deleted successfully";
        header("location: admin_emp_table.php");
        exit;
    }else{
        echo "Error deleting record: " . $mysqli->error;
    }
}

// Handle delete for Admin otp_details Delete
// otp_delete_id
if(isset($_GET['otp_delete_id'])){
    $admin_otp_delete = $_GET['otp_delete_id'];
    $sql = "DELETE FROM users WHERE id = $admin_otp_delete";
    $check_delete_query = mysqli_query($mysqli, $sql);
    if($check_delete_query){
        echo "Record deleted successfully";
        header("location: admin_otp_table.php");
        exit;
    }else{
        echo "Error deleting record: " . $mysqli->error;
    }
}



// Handle delete for Admin otp_details Delete
// sale_id
if(isset($_GET['sale_id'])){
    $admin_sales_delete = $_GET['sale_id'];
    $sql = "DELETE FROM sales WHERE id = $admin_sales_delete";
    $check_delete_query = mysqli_query($mysqli, $sql);
    if($check_delete_query){
        echo "Record deleted successfully";
        header("location: admin_sales_data.php");
        exit;
    }else{
        echo "Error deleting record: " . $mysqli->error;
    }
}

// for booking delete
// emp_booking_id
if(isset($_GET['emp_booking_id'])){
    $admin_booking_delete = $_GET['emp_booking_id'];
    $sqls = "DELETE FROM bookings WHERE booking_id = $admin_booking_delete";
    $checks_delete_query = mysqli_query($mysqli, $sqls);
    if($checks_delete_query){
        echo "Record deleted successfully";
        header("location: admin_booking_table.php");
        exit;
    }else{
        echo "Error deleting record: " . $mysqli->error;
    }
}


?>