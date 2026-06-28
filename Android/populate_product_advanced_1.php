<?php
 $result = array();
require_once "conn.php";
$sql = "SELECT prodDetails.Itemcode as 'Itemcode',prodDetails.ItemName as 'ItemName',catDetails.CategoryName as 'CategoryName', subDetails.SubCategoryName as 'SubCategoryName', uomDetails.UomSubType as 'Measure'  from productdetails as prodDetails
inner join subcategorydetails as subDetails on prodDetails.SubCategoryId = subDetails.SubCategoryId
inner join categorydetails as catDetails on prodDetails.CategoryId = catDetails.CategoryId
inner join uomdetails as uomDetails on subDetails.UOM = uomDetails.UomType where uomDetails.BaseUomFlag = 1";

if(!$conn->query($sql)){
    echo "Error in connecting to Database.";
}
else{
    $result = $conn->query($sql);
    if($result->num_rows > 0){
        $return_arr['product'] = array();
        while($row = $result->fetch_array()){
            array_push($return_arr['product'], array(
                'Itemcode'=>$row['Itemcode'],
                'ItemName'=>$row['ItemName'],
				'CategoryName'=>$row['CategoryName'],
				'SubCategoryName'=>$row['SubCategoryName'],
				'Measure'=>$row['Measure']
            ));
        }
        
    }
	
	$sql1 = "SELECT LocationName FROM `locationdetails` ";
	 $result1 = $conn->query($sql1);
    if($result1->num_rows > 0){
        $return_arr1['location'] = array();
        while($row = $result1->fetch_array()){
            array_push($return_arr1['location'], array(
                'LocationName'=>$row['LocationName']
            ));
        }
       
    }
	
	$sql2 = "SELECT distinct UomSubType FROM `uomdetails`";
	 $result2 = $conn->query($sql2);
    if($result2->num_rows > 0){
        $return_arr2['uom'] = array();
        while($row = $result2->fetch_array()){
            array_push($return_arr2['uom'], array(
                'UomSubType'=>$row['UomSubType']
            ));
        }
       // echo json_encode($return_arr);
    }
	$sql3 = "SELECT UnitName FROM `unitdetails`";
	  $result3 = $conn->query($sql3);
    if($result3->num_rows > 0){
        $return_arr3['unitdetails'] = array();
        while($row = $result3->fetch_array()){
            array_push($return_arr3['unitdetails'], array(
                'UnitName'=>$row['UnitName']
            ));
        }
        //echo json_encode($return_arr3);
    }
}
$arr = array ("Products" =>$return_arr, "LocationNames"=>$return_arr1,"Measures"=>$return_arr2,"UnitNames"=>$return_arr3);
echo json_encode($arr);
?>