<?php
 if($_POST['barcode']){
	 require_once "conn.php";
     // if the parameter send from the user id id then
     // we will search the item for specific id.
     $barcode = $_POST['barcode'];
        //on below line we are selecting the course detail with below id.
     $stmt = $conn->prepare("select prodDetails.ItemName as ItemName,prodDetails.Itemcode as 'Itemcode',catDetails.CategoryName as 'CategoryName', subCat.SubCategoryName as 'SubCategoryName', locDetails.LocationName as 'LocationName', 
	 b.Quantity as 'AvailableQuantity', uomDet.UomSubType as 'UOMSubType', uomDet.ConversionValue as 'ConversionValue',batchName.BatchName as 'BatchName',
challanName.ChallanName as 'ChallanName'   from stockdetails as a
inner JOIN barcodedetails as b on a.Barcode = b.Barcode
inner join categorydetails as catDetails on a.CategoryId = catDetails.CategoryId
inner JOIN productdetails as prodDetails on a.ProductId = prodDetails.ProductId
INNER JOIN locationdetails as locDetails on a.LocationId = locDetails.LocationId
inner join  subcategorydetails as subCat on a.SubCategoryId = subCat.SubCategoryId
inner join uomdetails as uomDet on a.UomId = uomDet.UomId
inner join batchdetails as batchName on a.BatchId = batchName.BatchId
inner join challandetails as challanName on a.ChallanId = challanName.ChallanId
 where b.Fullbarcode = ? limit 1");
     $stmt->bind_param("s",$barcode);
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
         $stmt->bind_result($ItemName,$Itemcode,$CategoryName,$SubCategoryName,$LocationName,$AvailableQuantity,$UOMSubType,$ConversionValue,$BatchName, $ChallanName);
         // on below line we are fetching the data.
         $stmt->fetch();
         // after getting all data we are passing this data in our array.
         $response['ItemName']=$ItemName;
		  $response['Itemcode']=$Itemcode;
		    $response['CategoryName']=$CategoryName;
		  $response['SubCategoryName']=$SubCategoryName;
		 $response['LocationName'] = $LocationName;
		  $response['AvailableQuantity'] = $AvailableQuantity;
		 $response['UOMSubType'] = $UOMSubType;
		 $response['ConversionValue'] = $ConversionValue;
 $response['BatchName'] = $BatchName;	
 $response['ChallanName'] = $ChallanName;
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