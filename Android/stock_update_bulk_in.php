<?php
require_once "conn.php";

 $result = array();
// $samp =  $_POST['data'];
 //$arr = json_decode($samp);

$arr = json_decode( file_get_contents('php://input') );
//$data=[{"barcodeB":"Rex_1001","measureB":"t3","name":"Sample","quantityB":"t2"},{"barcodeB":"Rex_1002","measureB":"t3","name":"Sample","quantityB":"t2"}];
//$data= [{"barcodeB":"Rex_1001","measureB":"m","name":"Sample","planNoB":"EF101","quantityB":"15","stockTypeB":"Out - Additional","unitNameB":"None","userB":"admin"}];
//$arr = json_decode($data);
/*$arr =array(
     array(
         'barcodeB' => 'Comp_40001',
		 'code' => '5-NL02-1330',
         'measureB' => 'Pair',
         'name' => '111/HECTO/GREY/1.45 MM',
		  'category' => 'Components',
		   'subCategory' => 'READYMADE UPPER',
		    'stockTypeB' => 'IN - First Entry',
			 'quantityB' => '15',
			  'locationB' => 'B11',
			 'userB' => 'test'
     )
);
*/
for($idx = 0; $idx < count($arr); $idx++){
    $obj = (Array)$arr[$idx];
 
	
	 $Fullbarcode = $obj["barcodeB"];
	 $ProdCode = $obj["code"];
	$ProdName = $obj["name"];
	$Category = $obj["category"];
	$SubCategory = $obj["subCategory"];
	 $StockType = $obj["stockTypeB"];
	 $Quantity = $obj["quantityB"];
	$Location = $obj["locationB"];
	 $UomSubType =$obj["measureB"];
	$PlanNo = $obj["planNoB"];
	$UserDate = $obj["userDate"];
$userDateObject = DateTime::createFromFormat('d/m/Y', $UserDate);
$formattedDate = $userDateObject ? $userDateObject->format('Y-m-d') : null;
		$UserName = $obj["userB"];
$BatchName=$obj["batchName"];
$ChallanName=$obj["challanName"]?? 'None';


	if ($StockType == "IN - First Entry") {
		   // Fetch BatchId based on BatchName
    $sqlBatch = "SELECT BatchId FROM batchdetails WHERE BatchName = '".$BatchName."'";
    $resultBatch = $conn->query($sqlBatch);
    
      if ($resultBatch->num_rows > 0) {
        $batch = $resultBatch->fetch_assoc();
        $batchId = $batch['BatchId'];
        
      // Check if this batch has already been used on a previous day
           $sqlCheck =  "SELECT StockId FROM `stockdetails` as a inner JOIN
productdetails as b on a.`ProductId` = b.ProductId
where BatchId = $batchId AND b.Itemcode = '$ProdCode' AND a.`StockType` = 'IN - First Entry'
AND DATE(a.`Updated_at`) < CURDATE()";


        $resultCheck = $conn->query($sqlCheck);
        
        if ($resultCheck->num_rows > 0) {
             $sqlLatestBatch = "SELECT c.BatchName FROM `stockdetails` as a
        INNER JOIN productdetails as b on a.`ProductId` = b.ProductId
        inner join batchdetails as c on a.BatchId = c.BatchId
        WHERE b.Itemcode = '$ProdCode'  
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
            array_push($result, $response);
            echo json_encode($result);
            die(); // Stop further execution
        }
    } else {
       
    }
}
	

		
		 $sqlselect = "select a.Barcode as Barcode,b.ProductId as ProductId,c.CategoryId as CategoryId, d.SubCategoryId as SubCategoryId, e.UomId as UomId, f.LocationId as LocationId, g.id as userId, h.BatchId as BatchId, i.ChallanId as ChallanId

from ((SELECT Barcode from `barcodedetails` where Fullbarcode = '".$Fullbarcode."') as a
join
(SELECT ProductId FROM `productdetails` WHERE `ItemCode` = '".$ProdCode."') as b on 1=1
  JOIN
 (SELECT CategoryId FROM `categorydetails` WHERE `CategoryName` = '".$Category."') as c 
   on 1=1
   join
   (SELECT SubCategoryId,Uom FROM `subcategorydetails` WHERE SubCategoryName = '".$SubCategory."') as d 
  on 1=1
  JOIN
 (SELECT UomId,UomType FROM `uomdetails` WHERE UomSubType = '".$UomSubType."' ) as e 
              on d.Uom = e.UomType
JOIN
 (SELECT LocationId FROM `locationdetails` WHERE LocationName = '".$Location."') as f 
  on 1=1
  JOIN
  (SELECT id FROM `users` WHERE username = '".$UserName."') as g 
     on 1=1   
JOIN
  (SELECT BatchId FROM `batchdetails` WHERE batchname = '".$BatchName."') as h 
     on 1=1  JOIN
 (SELECT ChallanId FROM `challandetails` WHERE ChallanName = '".$ChallanName."') as i 
  on 1=1     
              )";
			  
 if($resultsselect=$conn->query($sqlselect)) {
	$row = $resultsselect -> fetch_array(MYSQLI_ASSOC);
	//echo $row['Barcode'];
	//print_r($resultsselect);die();
} else {
echo "no";die();
}	


	
        //on below line we are selecting the course detail with below id.
   $sql = "INSERT INTO stockdetails (Barcode, ProductId, CategoryId, SubCategoryId, StockType, Quantity, UomId, LocationId, PlanNo, UserDate, id, BatchId, ChallanId) 
        VALUES (" . $row['Barcode'] . "," . $row['ProductId'] . "," . $row['CategoryId'] . "," . $row['SubCategoryId'] . ",'" . $StockType . "'," . $Quantity . "," . $row['UomId'] . "," . $row['LocationId'] . ",'" . $PlanNo . "','" . $formattedDate . "'," . $row['userId'] . "," . $row['BatchId'] . "," . $row['ChallanId'] . ")";

 $response = array();
	 if($conn->query($sql)){
		 $response['Barcode'] = $row['Barcode'];
		 $response['ItemName'] = $Fullbarcode." -> ".$ProdName." -> ".$Quantity;
         $response['error'] = false;
         $response['message'] = "Update Successful";
		 array_push($result, $response);
		 
    }
	else{
		$response['Barcode'] = $row['Barcode'];
		 $response['ItemName'] = $Fullbarcode." -> ".$ProdName." -> ".$Quantity;
          $response['error'] = true;
         $response['message'] = "Error in update"; 
		 array_push($result, $response);
    }
	 
	
	
	}
	

echo json_encode($result);
?>