<?php
require_once "conn.php";
$sql = "SELECT distinct BatchName FROM `batchdetails`";
if(!$conn->query($sql)){
    echo "Error in connecting to Database.";
}
else{
    $result = $conn->query($sql);
    if($result->num_rows > 0){
        $return_arr['batchName'] = array();
        while($row = $result->fetch_array()){
            array_push($return_arr['batchName'], array(
                'BatchName'=>$row['BatchName']
            ));
        }
        echo json_encode($return_arr);
    }
}
?>