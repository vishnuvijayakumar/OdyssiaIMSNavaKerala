<?php
require_once "conn.php";
$sql = "SELECT ChallanName FROM `challandetails`";
if(!$conn->query($sql)){
    echo "Error in connecting to Database.";
}
else{
    $result = $conn->query($sql);
    if($result->num_rows > 0){
        $return_arr['challandetails'] = array();
        while($row = $result->fetch_array()){
            array_push($return_arr['challandetails'], array(
                'ChallanName'=>$row['ChallanName']
            ));
        }
        echo json_encode($return_arr);
    }
}
?>