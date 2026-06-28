<?php
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
        echo json_encode($return_arr);
    }
}
?>