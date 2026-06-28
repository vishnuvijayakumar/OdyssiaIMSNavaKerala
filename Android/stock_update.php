<?php
//$StockStatus = "Existing Stock";
//if($_POST['StockStatus']=="New Entry")
//$_POST['StockStatus']=="Existing Stock"
 if($_POST['StockStatus']=="Existing Stock"){
	 require_once "conn.php";
     // if the parameter send from the user id id then
     // we will search the item for specific id.
     $Fullbarcode = $_POST['Fullbarcode'];
	
	 $Itemcode = $_POST['Itemcode'];
	 $CategoryName = $_POST['CategoryName'];
	 $SubCategoryName = $_POST['SubCategoryName'];
	 $StockType = $_POST['StockType'];
	 $Quantity = $_POST['Quantity'];

	 $UomSubType = $_POST['UomSubType'];
	 $LocationName = $_POST['LocationName'];
	 $UnitName = $_POST['UnitName'];
	 $PlanNo = $_POST['PlanNo'];
	 $UserName = $_POST['UserName'];
	$ChallanNo = $_POST['ChallanNo'];
$BatchName = $_POST['BatchName'];
	/*	$StockStatus = "New Entry";
$Fullbarcode = "Rexin_4139";
$Itemcode = "5-NL01-0006";
$CategoryName = "Rexin";
$SubCategoryName = "Upper";
$StockType = "IN - Excess";
$Quantity = 20;
$UomSubType = "MTR";
$LocationName = "SYL 10";
$UnitName = "MACHINE";
$PlanNo = "Test";
$UserName = "test";
$ChallanNo = "Ch002";
$BatchName = "Batch 2 September";*/
	 
	 $sqlselect = "SELECT a.Barcode,b.ProductId,c.CategoryId,d.SubCategoryId,'".$StockType."','".$Quantity."',e.UomId,f.LocationId,g.UnitId,'".$PlanNo."', h.id,
j.ChallanId,k.BatchId
                  FROM barcodedetails  as a inner JOIN productdetails as b 
 INNER JOIN categorydetails as c on b.CategoryId=c.CategoryId
 inner join subcategorydetails as d on d.CategoryId=c.CategoryId
 inner join uomdetails as e on e.UomType = d.UOM
 inner join locationdetails as f
 inner join unitdetails as g
 inner join users as h
inner join challandetails j
inner join batchdetails k
 
 ON 1=1 WHERE a.Fullbarcode = '".$Fullbarcode."' and b.Itemcode = '".$Itemcode."' and c.CategoryName='".$CategoryName."' and d.SubCategoryName = '".$SubCategoryName."'
 and e.UomSubType = '".$UomSubType."' and f.LocationName ='".$LocationName."' and g.UnitName='".$UnitName."' and h.username ='".$UserName."' and j.ChallanName ='".$ChallanNo."' and k.BatchName ='".$BatchName."' ";

if($resultsselect=$conn->query($sqlselect)) {
	$row = $resultsselect -> fetch_array(MYSQLI_ASSOC);
	//echo $row['Barcode'];
	//print_r($resultsselect);die();
} else {
echo "no";die();
}	
	 
        //on below line we are selecting the course detail with below id.
     $sql = "insert into stockdetails (`Barcode`, `ProductId`, `CategoryId`, `SubCategoryId`, `StockType`, `Quantity`, `UomId`, `LocationId`, `UnitId`, `PlanNo`,`id`,`ChallanId`,`BatchId`) 
	 Values(".$row['Barcode'].",".$row['ProductId'].",".$row['CategoryId'].",".$row['SubCategoryId'].",'".$StockType."',".$Quantity.",".$row['UomId'].",".$row['LocationId'].",".$row['UnitId'].",'".$PlanNo."',".$row['id'].",".$row['ChallanId'].",".$row['BatchId'].")";

	 
 $response = array();
	 if($conn->query($sql)){
         $response['error'] = false;
         $response['message'] = "Insert Successful";
    }else{
          $response['error'] = true;
         $response['message'] = "Error in update"; 
    }

 } 
 else if($_POST['StockStatus']=="New Entry")
 {
   
	  require_once "conn.php";
     // if the parameter send from the user id id then
     // we will search the item for specific id.
     $Fullbarcode = $_POST['Fullbarcode'];
	
	 $Itemcode = $_POST['Itemcode'];
	 $CategoryName = $_POST['CategoryName'];
	 $SubCategoryName = $_POST['SubCategoryName'];
	 $StockType = $_POST['StockType'];
	 $Quantity = $_POST['Quantity'];

	 $UomSubType = $_POST['UomSubType'];
	 $LocationName = $_POST['LocationName'];
	 $UnitName = $_POST['UnitName'];
	 $PlanNo = $_POST['PlanNo'];
	 $UserName = $_POST['UserName'];
	$ChallanNo = $_POST['ChallanNo'];
$BatchName = $_POST['BatchName'];
		
		
		
		
		if ($_POST['StockStatus']=="New Entry") {
		   
		  		   // Fetch BatchId based on BatchName
    $sqlBatch = "SELECT BatchId FROM batchdetails WHERE BatchName = '".$BatchName."'";
 		 
    $resultBatch = $conn->query($sqlBatch);
    
      if ($resultBatch->num_rows > 0) {
        $batch = $resultBatch->fetch_assoc();
        $batchId = $batch['BatchId'];
        
        // Check if this batch has already been used on a previous day
           $sqlCheck =  "SELECT StockId FROM `stockdetails` as a inner JOIN
productdetails as b on a.`ProductId` = b.ProductId
where BatchId = $batchId AND b.Itemcode = '$Itemcode' AND a.`StockType` = 'IN - First Entry'
AND DATE(a.`Updated_at`) < CURDATE()";
                     
            
                     
        $resultCheck = $conn->query($sqlCheck);
       // echo "Number of rows found: " . $resultCheck->num_rows . "<br>";
            
            
        if ($resultCheck->num_rows > 0) {
                  $sqlLatestBatch = "SELECT c.BatchName FROM `stockdetails` as a
        INNER JOIN productdetails as b on a.`ProductId` = b.ProductId
        inner join batchdetails as c on a.BatchId = c.BatchId
        WHERE b.Itemcode = '$Itemcode'  
        AND a.`StockType` = 'IN - First Entry'
        ORDER BY a.`Updated_at` DESC LIMIT 1"; // Get the latest batch
 
     $resultLatestBatch = $conn->query($sqlLatestBatch);
      if ($resultLatestBatch->num_rows > 0) {
        // Fetch the latest batch record
        $row = $resultLatestBatch->fetch_assoc();
        $latestBatchId = $row['BatchName'];
    } else {
        // If no batch is found, handle the scenario
        $latestBatchId = "No latest batch found";
    }
            // Batch already used in a previous entry
            $response['error'] = true;
            $response['message'] = "Batch already used";
             $response['latestBatchId'] = $latestBatchId; 
            echo json_encode($response);
            die(); // Stop further execution
        }
       
    } else {
      
    }

}

	
		 $sqlselect = "SELECT a.Barcode,b.ProductId,c.CategoryId,d.SubCategoryId,'".$StockType."','".$Quantity."',e.UomId,f.LocationId,g.UnitId, h.id, i.ChallanId, k.BatchId
                  FROM barcodedetails  as a inner JOIN productdetails as b 
 INNER JOIN categorydetails as c on b.CategoryId=c.CategoryId
 inner join subcategorydetails as d on d.CategoryId=c.CategoryId
 inner join uomdetails as e on e.UomType = d.UOM
 inner join locationdetails as f
 inner join unitdetails as g
 inner join users as h
 inner join challandetails as i
inner join batchdetails as k
 
 ON 1=1 WHERE a.Fullbarcode = '".$Fullbarcode."' and b.Itemcode = '".$Itemcode."' and c.CategoryName='".$CategoryName."' and d.SubCategoryName = '".$SubCategoryName."'
 and e.UomSubType = '".$UomSubType."' and f.LocationName ='".$LocationName."' and g.UnitName='None' and h.username ='".$UserName."' and i.ChallanName ='".$ChallanNo."' and k.BatchName ='".$BatchName."' ";

if($resultsselect=$conn->query($sqlselect)) {
	$row = $resultsselect -> fetch_array(MYSQLI_ASSOC);
	//echo $row['Barcode'];
	//print_r($resultsselect);die();
} else {
echo "no";die();
}	
	 
        //on below line we are selecting the course detail with below id.
     $sql = "insert into stockdetails (`Barcode`, `ProductId`, `CategoryId`, `SubCategoryId`, `StockType`, `Quantity`, `UomId`, `LocationId`, `UnitId`,`id`, `BatchId`, `ChallanId`) 
	 Values(".$row['Barcode'].",".$row['ProductId'].",".$row['CategoryId'].",".$row['SubCategoryId'].",'".$StockType."',".$Quantity.",".$row['UomId'].",".$row['LocationId'].",".$row['UnitId'].",".$row['id'].",".$row['BatchId'].",".$row['ChallanId']."  )";
 file_put_contents("query_log.txt", $sql . "\n", FILE_APPEND);
 
 $response = array();
	 if($conn->query($sql)){
         $response['error'] = false;
         $response['message'] = "Insert Successful";
         
    }
	else{
          $response['error'] = true;
         $response['message'] = "Error in update"; 
    }

 } 
 else{
      // if the user donot adds any parameter while making request
      // then we are displaying the error as insufficient parameters.
      $response['error'] = true;
      $response['message'] = "Insufficient Parameters";
 }
 
 // at last we are printing
 // all the data on below line.
 echo json_encode($response);

?>