<?php
require_once "conn.php";
$sql = "SELECT UnitName FROM `unitdetails`";
if(!$conn->query($sql)){
    echo "Error in connecting to Database.";
}
else{
    $result = $conn->query($sql);
    if($result->num_rows > 0){
        $return_arr['unitdetails'] = array();
        while($row = $result->fetch_array()){
            array_push($return_arr['unitdetails'], array(
                'UnitName'=>$row['UnitName']
            ));
        }
        echo json_encode($return_arr);
    }
}
?>