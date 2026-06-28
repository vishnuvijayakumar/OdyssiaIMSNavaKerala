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
         'barcodeB' => 'Rexin_1001',
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
	$UserDate = $obj["userDate"];
$userDateObject = DateTime::createFromFormat('d/m/Y', $UserDate);
$formattedDate = $userDateObject ? $userDateObject->format('Y-m-d') : null;
		$UserName = $obj["userB"];
$ChallanName = $obj["challanNoB"];
		
 $sqlselect = "SELECT a.Barcode,st.ProductId,b.ItemName,st.CategoryId,st.SubCategoryId,'".$StockType."' as StockType,'".$Quantity."' as 'Quantity',e.UomId,f.LocationId,g.UnitId,'".$PlanNo."' as PlanNo, h.id,a.Quantity as 'AvailableQ',e.ConversionValue as 'ConvVal',st.BatchId,i.ChallanId as 'ChallanId'

         FROM stockdetails as st inner join 
         barcodedetails  as a on st.Barcode = a.Barcode
         inner JOIN productdetails as b on st.ProductId = b.ProductId
INNER JOIN categorydetails as c on st.CategoryId = c.CategoryId
inner join subcategorydetails as d on st.SubCategoryId = d.SubCategoryId
inner join uomdetails as e on d.UOM=e.UomType
inner join locationdetails as f on st.LocationId = f.LocationId
inner join unitdetails as g 
inner join users as h 
inner join challandetails as i 
 
  WHERE a.Fullbarcode = '".$Fullbarcode."'
 and e.UomSubType = '".$UomSubType."' and g.UnitName='".$UnitName."' and h.username ='".$UserName."' and i.ChallanName = '".$ChallanName."'  limit 1";
 if($resultsselect=$conn->query($sqlselect)) {
	$row = $resultsselect -> fetch_array(MYSQLI_ASSOC);
	//echo $row['Barcode'];
	//print_r($resultsselect);die();
} else {
echo "no";die();
}	


	if(($row['Quantity']*$row['ConvVal'])>$row['AvailableQ'])
	{
		 $response['Barcode'] = $row['Barcode'];
		 $response['ItemName'] = $row['ItemName'];
         $response['error'] = true;
         $response['message'] = "Not updated: Quantity entered more than available";
		 array_push($result, $response);
	}
	 else{
        //on below line we are selecting the course detail with below id.
     $sql = "insert into stockdetails (`Barcode`, `ProductId`, `CategoryId`, `SubCategoryId`, `StockType`, `Quantity`, `UomId`, `LocationId`, `UnitId`, `PlanNo`,`UserDate`,`id`, `BatchId`,`ChallanId`) 
	 Values(".$row['Barcode'].",".$row['ProductId'].",".$row['CategoryId'].",".$row['SubCategoryId'].",'".$StockType."',".$Quantity.",".$row['UomId'].",".$row['LocationId'].",".$row['UnitId'].",'".$PlanNo."','".$formattedDate."',".$row['id'].",".$row['BatchId'].",".$row['ChallanId'].")";

 $response = array();
	 if($conn->query($sql)){
		 $response['Barcode'] = $row['Barcode'];
		 $response['ItemName'] = $row['ItemName'];
         $response['error'] = false;
         $response['message'] = "Update Successful";
		 array_push($result, $response);
		 
    }
	else{
		$response['Barcode'] = $row['Barcode'];
		 $response['ItemName'] = $row['ItemName'];
          $response['error'] = true;
         $response['message'] = "Error in update"; 
		 array_push($result, $response);
    }
	 }
	
	
	}
	

echo json_encode($result);
?>