<?php
 $result = array();
require_once "conn.php";
 $SubCategoryName = $_POST['SubCategoryName'];
$sql = "select uomDetails.UomSubType,  uomDetails.ConversionValue from subcategorydetails as subDetails 
inner join uomdetails as uomDetails on subDetails.UOM = uomDetails.UomType where subDetails.SubCategoryName = '".$SubCategoryName."'";
if(!$conn->query($sql)){
    echo "Error in connecting to Database.";
}
else{
    $result = $conn->query($sql);
    if($result->num_rows > 0){
        $return_arr['uom'] = array();
        while($row = $result->fetch_array()){
            array_push($return_arr['uom'], array(
                'UomSubType'=>$row['UomSubType'],
				'ConversionValue'=>$row['ConversionValue']
            ));
        }
		
    }
	$sql1 = "SELECT UnitName FROM `unitdetails`";
	 $result1 = $conn->query($sql1);
    if($result1->num_rows > 0){
        $return_arr1['unitdetails'] = array();
        while($row = $result1->fetch_array()){
            array_push($return_arr1['unitdetails'], array(
                'UnitName'=>$row['UnitName']
            ));
        }
     
    }
}
 $arr = array ("Measures" =>$return_arr,"Units" =>$return_arr1);
        echo json_encode($arr);
?>