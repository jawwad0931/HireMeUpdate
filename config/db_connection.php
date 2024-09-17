
<?php

error_reporting(0);

define('DB_HOST', 'localhost:3306');
define('DB_USER', 'root'); 
define('DB_PASSWORD', '');
define('DB_NAME', 'HireMe_DB');


$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);


if($mysqli->errno){
    die("ERROR: Could not connect. " . $mysqli->connect_error);
}
// else{
    // echo "Connection successfull";
// }
?>
