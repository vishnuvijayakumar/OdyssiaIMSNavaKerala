<?php
 if($_POST['SubCategoryName']){
	 require_once "conn.php";
     // if the parameter send from the user id id then
     // we will search the item for specific id.
     $SubCategoryName = $_POST['SubCategoryName'];
	 $UomSubType = $_POST['UomSubType'];
        //on below line we are selecting the course detail with below id.
     $stmt = $conn->prepare("select a.ConversionValue from uomdetails as a 
inner join subcategorydetails as b on a.UomType = b.UOM where a.UomSubType = '".$UomSubType."' and b.SubCategoryName = '".$SubCategoryName."'");
    
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
         $stmt->bind_result($ConversionValue);
         // on below line we are fetching the data.
         $stmt->fetch();
         // after getting all data we are passing this data in our array.
         $response['ConversionValue']=$ConversionValue;
	

		
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