<?php
// Create 4 variables to store these information
$server="192.168.17.209";
$username="root";
$password="Superadmin@123";
$database = "inventory_system";
//$db_port = 80;
// Create connection
$conn = new mysqli($server, $username, $password, $database);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
