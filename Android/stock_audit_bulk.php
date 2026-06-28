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
         'barcodeB' => 'Rexin_1003',
         'measureB' => 'CM',
         'name' => '1209/109/E-60/BLACK/1.45',
		  'planNoB' => 'EF101',
		   'quantityB' => '15',
		    'stockTypeB' => 'Out - Additional',
			 'unitNameB' => 'None',
			 'userB' => 'admin'
     )
);
*/
for($idx = 0; $idx < count($arr); $idx++){
    $obj = (Array)$arr[$idx];
 
	
	 $Fullbarcode = $obj["barcodeB"];
	
	
	 $StockType = $obj["stockTypeB"];
	 $Quantity = $obj["quantityB"];

	 $UomSubType =$obj["measureB"];
	$PlanNo = $obj["planNoB"];
	$UnitName =  $obj["unitNameB"];
		$UserName = $obj["userB"];
		
		 $sqlselect = "select a.barcode as Barcode, b.ProductId as ProductId,d.ItemName as ItemName, c.id as userid from ((Select barcode from barcodedetails where  FullBarcode = '".$Fullbarcode."') as a 
join 
(Select ProductId,barcode from stockdetails ) as b 
on a.barcode = b.barcode
join 
(select ItemName, ProductId from productdetails) as d on b.ProductId = d.ProductId
 join
(Select id from users where  username = '".$UserName."') as c on 1=1) limit 1";

 if($resultsselect=$conn->query($sqlselect)) {
	$row = $resultsselect -> fetch_array(MYSQLI_ASSOC);
	//echo $row['Barcode'];
	//print_r($resultsselect);die();
} else {
echo "no";die();
}	


        //on below line we are selecting the course detail with below id.
     $sql = "INSERT INTO `checkstockdetails` (`id`, `Barcode`, `ProductId`, `Quantity`,`userid`) VALUES (NULL, ".$row['Barcode'].", ".$row['ProductId'].", ".$Quantity.", ".$row['userid'].");";

 $response = array();
 
 try{
	 $conn->query($sql);
	  $response['Barcode'] = $row['Barcode'];
		 $response['ItemName'] = $Fullbarcode." -> ".$row['ItemName']." -> ".$Quantity;
         $response['error'] = false;
         $response['message'] = "Update Successful";
		 array_push($result, $response);
 }
 
// catch(mysqli_sql_exception $e)
catch(Exception $e)
 {
	 $response['Barcode'] = $row['Barcode'];
		 $response['ItemName'] = $Fullbarcode." -> ".$row['ItemName']." -> ".$Quantity;
          $response['error'] = true;
         $response['message'] = "Error in update"; 
		 array_push($result, $response);
 }
	 
	 
	
	
	}
	

echo json_encode($result);
?>