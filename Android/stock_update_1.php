<?php
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
	 
	 $sqlselect = "SELECT a.Barcode,b.ProductId,c.CategoryId,d.SubCategoryId,'".$StockType."','".$Quantity."',e.UomId,f.LocationId,g.UnitId,'".$PlanNo."', h.id
                  FROM barcodedetails  as a inner JOIN productdetails as b 
 INNER JOIN categorydetails as c
 inner join subcategorydetails as d
 inner join uomdetails as e
 inner join locationdetails as f
 inner join unitdetails as g
 inner join users as h
 
 ON 1=1 WHERE a.Fullbarcode = '".$Fullbarcode."' and b.Itemcode = '".$Itemcode."' and c.CategoryName='".$CategoryName."' and d.SubCategoryName = '".$SubCategoryName."'
 and e.UomSubType = '".$UomSubType."' and f.LocationName ='".$LocationName."' and g.UnitName='".$UnitName."' and h.username ='".$UserName."' ";

if($resultsselect=$conn->query($sqlselect)) {
	$row = $resultsselect -> fetch_array(MYSQLI_ASSOC);
	//echo $row['Barcode'];
	//print_r($resultsselect);die();
} else {
echo "no";die();
}	
	 
        //on below line we are selecting the course detail with below id.
     $sql = "insert into stockdetails (`Barcode`, `ProductId`, `CategoryId`, `SubCategoryId`, `StockType`, `Quantity`, `UomId`, `LocationId`, `UnitId`, `PlanNo`,`id`) 
	 Values(".$row['Barcode'].",".$row['ProductId'].",".$row['CategoryId'].",".$row['SubCategoryId'].",'".$StockType."',".$Quantity.",".$row['UomId'].",".$row['LocationId'].",".$row['UnitId'].",'".$PlanNo."',".$row['id'].")";

	 
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
		$UserName = $_POST['UserName'];
		 $sqlselect = "SELECT a.Barcode,b.ProductId,c.CategoryId,d.SubCategoryId,'".$StockType."','".$Quantity."',e.UomId,f.LocationId,g.UnitId, h.id
                  FROM barcodedetails  as a inner JOIN productdetails as b 
 INNER JOIN categorydetails as c
 inner join subcategorydetails as d
 inner join uomdetails as e
 inner join locationdetails as f
 inner join unitdetails as g
 inner join users as h
 
 ON 1=1 WHERE a.Fullbarcode = '".$Fullbarcode."' and b.Itemcode = '".$Itemcode."' and c.CategoryName='".$CategoryName."' and d.SubCategoryName = '".$SubCategoryName."'
 and e.UomSubType = '".$UomSubType."' and f.LocationName ='".$LocationName."' and g.UnitName='None' and h.username ='".$UserName."' ";

if($resultsselect=$conn->query($sqlselect)) {
	$row = $resultsselect -> fetch_array(MYSQLI_ASSOC);
	//echo $row['Barcode'];
	//print_r($resultsselect);die();
} else {
echo "no";die();
}	
	 
        //on below line we are selecting the course detail with below id.
     $sql = "insert into stockdetails (`Barcode`, `ProductId`, `CategoryId`, `SubCategoryId`, `StockType`, `Quantity`, `UomId`, `LocationId`, `UnitId`,`id`) 
	 Values(".$row['Barcode'].",".$row['ProductId'].",".$row['CategoryId'].",".$row['SubCategoryId'].",'".$StockType."',".$Quantity.",".$row['UomId'].",".$row['LocationId'].",".$row['UnitId'].",".$row['id'].")";
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