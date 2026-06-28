<?php
if($_POST['barcode']){
require_once "conn.php";
$barcode = $_POST['barcode'];
 $stmt = $conn->prepare("select  a.Barcode as BarcodeBase, b.Barcode as BarcodeStock from barcodedetails as a left join stockdetails as b on a.Barcode = b.Barcode where a.Fullbarcode = ? limit 1");
     $stmt->bind_param("s",$barcode);
     $result = $stmt->execute();
 if($result == TRUE){
         // if we get the response then we are displaying it below.
         $response['error'] = false;
         $response['message'] = "Retrieval Successful!";
         // on below line we are getting our result.
         $stmt->store_result();
         // on below line we are passing parameters which we want to get.
         $stmt->bind_result($BarcodeBase,$BarcodeStock);
         // on below line we are fetching the data.
         $stmt->fetch();
         // after getting all data we are passing this data in our array.
         $response['BarcodeBase']=$BarcodeBase;
		  $response['BarcodeStock']=$BarcodeStock;
	
     } else{
         // if the id entered by user donot exist then
         // we are displaying the error message
         $response['error'] = true;
         $response['message'] = "Incorrect id";
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