<?php
require_once "conn.php";
$sql = "SELECT SubCategoryName FROM `subcategorydetails`";
if(!$conn->query($sql)){
    echo "Error in connecting to Database.";
}
else{
    $result = $conn->query($sql);
    if($result->num_rows > 0){
        $return_arr['SubCategory'] = array();
        while($row = $result->fetch_array()){
            array_push($return_arr['SubCategory'], array(
                'SubCategoryName'=>$row['SubCategoryName']
            ));
        }
        echo json_encode($return_arr);
    }
}
?>