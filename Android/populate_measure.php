<?php
require_once "conn.php";
$sql = "SELECT distinct UomSubType FROM `uomdetails`";
if(!$conn->query($sql)){
    echo "Error in connecting to Database.";
}
else{
    $result = $conn->query($sql);
    if($result->num_rows > 0){
        $return_arr['uom'] = array();
        while($row = $result->fetch_array()){
            array_push($return_arr['uom'], array(
                'UomSubType'=>$row['UomSubType']
            ));
        }
        echo json_encode($return_arr);
    }
}
?>