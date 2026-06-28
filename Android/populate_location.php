<?php
require_once "conn.php";
$sql = "SELECT LocationName FROM `locationdetails` ";
if(!$conn->query($sql)){
    echo "Error in connecting to Database.";
}
else{
    $result = $conn->query($sql);
    if($result->num_rows > 0){
        $return_arr['location'] = array();
        while($row = $result->fetch_array()){
            array_push($return_arr['location'], array(
                'LocationName'=>$row['LocationName']
            ));
        }
        echo json_encode($return_arr);
    }
}
?>