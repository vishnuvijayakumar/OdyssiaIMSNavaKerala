<?php
 if(1==1){
	 require_once "conn.php";
     // if the parameter send from the user id id then
     // we will search the item for specific id.
    /* $SubCategoryName = $_POST['SubCategoryName'];
	 $UomSubType = $_POST['UomSubType'];
	 $StockType = "IN - First Entry";
	 $Barcode = "Rexin_1008";
	 $Quantity = "100";*/
        //on below line we are selecting the course detail with below id.
     $stmt = $conn->prepare("CALL Uom_Conversion_validation('100','OUT - PLAN No','INSOLE','MTR','Rexin_1008');");
    
     $result = $stmt->execute();
   // on below line we are checking if our
   // table is having data with specific id.
   if($result == TRUE){
         // if we get the response then we are displaying it below.
         $response['error'] = false;
         $response['message'] = "Retrieval Successful!";
         // on below line we are getting our result.
         $stmt->store_result();
         // on below line we are passing parameters which we want to get.
         $stmt->bind_result($StatusCode);
         // on below line we are fetching the data.
         $stmt->fetch();
         // after getting all data we are passing this data in our array.
         $response['StatusCode']=$StatusCode;
	//$sd = $stmt->fetch_array(MYSQLI_NUM );
print_r($result);
		
     } else{
         // if the id entered by user donot exist then
         // we are displaying the error message
         $response['error'] = true;
         $response['message'] = "Incorrect SubCategoryName";
     }
 } else{
      // if the user donot adds any parameter while making request
      // then we are displaying the error as insufficient parameters.
      $response['error'] = true;
      $response['message'] = "Insufficient Parameters";
 }
 // at last we are printing
 // all the data on below line.
 echo json_encode($response);
?>