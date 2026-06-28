<?php

// Create 4 variables to store these information
$server="localhost";
$username="ripplesoft_odyssiauser";
$password="J4Jasmine@123";
$database = "ripplesoft_odyssiaims";
//$db_port = 80;
// Create connection
$conn = new mysqli($server, $username, $password, $database);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
