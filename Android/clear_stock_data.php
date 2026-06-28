<?php
// Check if email and password are set

    // Include the necessary files
    require_once "conn.php";
  
  
    // Create the SQL query string
    $sql = "truncate table `checkstockdetails`";
	
	try{
		 // Execute the query
    $result = $conn->query($sql);
	echo "success";
	}
	catch(mysqli_sql_exception $e)
	{
		  echo "failure";
	}
   
?>