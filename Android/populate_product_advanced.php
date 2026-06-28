<?php
 $result = array();
require_once "conn.php";

// Existing query to fetch product details
$sql = "SELECT prodDetails.Itemcode as 'Itemcode',prodDetails.ItemName as 'ItemName',catDetails.CategoryName as 'CategoryName', subDetails.SubCategoryName as 'SubCategoryName', uomDetails.UomSubType as 'Measure'  
        FROM productdetails as prodDetails
        INNER JOIN subcategorydetails as subDetails ON prodDetails.SubCategoryId = subDetails.SubCategoryId
        INNER JOIN categorydetails as catDetails ON prodDetails.CategoryId = catDetails.CategoryId
        INNER JOIN uomdetails as uomDetails ON subDetails.UOM = uomDetails.UomType 
        WHERE uomDetails.BaseUomFlag = 1";

if(!$conn->query($sql)){
    echo "Error in connecting to Database.";
} else {
    $result = $conn->query($sql);

    if($result->num_rows > 0){
        $return_arr['product'] = array();
        while($row = $result->fetch_array()){
            array_push($return_arr['product'], array(
                'Itemcode' => $row['Itemcode'],
                'ItemName' => $row['ItemName'],
                'CategoryName' => $row['CategoryName'],
                'SubCategoryName' => $row['SubCategoryName'],
                'Measure' => $row['Measure']
            ));
        }
    }

    // Query for location details
    $sql1 = "SELECT LocationName FROM `locationdetails`";
    $result1 = $conn->query($sql1);
    if($result1->num_rows > 0){
        $return_arr1['location'] = array();
        while($row = $result1->fetch_array()){
            array_push($return_arr1['location'], array(
                'LocationName' => $row['LocationName']
            ));
        }
    }

    // Query for UOM details
    $sql2 = "SELECT distinct UomSubType FROM `uomdetails`";
    $result2 = $conn->query($sql2);
    if($result2->num_rows > 0){
        $return_arr2['uom'] = array();
        while($row = $result2->fetch_array()){
            array_push($return_arr2['uom'], array(
                'UomSubType' => $row['UomSubType']
            ));
        }
    }

    // Query for unit details
    $sql3 = "SELECT UnitName FROM `unitdetails`";
    $result3 = $conn->query($sql3);
    if($result3->num_rows > 0){
        $return_arr3['unitdetails'] = array();
        while($row = $result3->fetch_array()){
            array_push($return_arr3['unitdetails'], array(
                'UnitName' => $row['UnitName']
            ));
        }
    }

    // Query for batch details
    $sql4 = "SELECT distinct BatchName FROM `batchdetails`";
    $result4 = $conn->query($sql4);
    if($result4->num_rows > 0){
        $return_arr4['batchname'] = array();
        while($row = $result4->fetch_array()){
            array_push($return_arr4['batchname'], array(
                'BatchName' => $row['BatchName']
            ));
        }
    }

    // Query for challan details
    $sql5 = "SELECT distinct ChallanName FROM `challandetails`";
    $result5 = $conn->query($sql5);
    if($result5->num_rows > 0){
        $return_arr5['challanname'] = array();
        while($row = $result5->fetch_array()){
            array_push($return_arr5['challanname'], array(
                'ChallanName' => $row['ChallanName']
            ));
        }
    }

    // New query to fetch the last batch name with stocktype as "IN - First Entry"
   /* $sql6 = "SELECT b.BatchName 
             FROM stockdetails s
             INNER JOIN batchdetails b ON s.BatchId = b.BatchId
             WHERE s.StockType = 'IN - First Entry'
             ORDER BY s.Updated_at DESC 
             LIMIT 1";

    $result6 = $conn->query($sql6);
    if($result6->num_rows > 0){
        $return_arr6['lastbatch'] = array();
        while($row = $result6->fetch_array()){
            array_push($return_arr6['lastbatch'], array(
                'BatchName' => $row['BatchName']
            ));
        }
    }*/
}

// Final array to include all data
$arr = array (
    "Products" => $return_arr, 
    "LocationNames" => $return_arr1,
    "Measures" => $return_arr2,
    "UnitNames" => $return_arr3,
    "BatchName" => $return_arr4,
    "ChallanName" => $return_arr5
    //"LastBatch" => $return_arr6
);

// Encode the array to JSON format and output
$json = json_encode($arr, JSON_INVALID_UTF8_IGNORE);
if ($json !== false) {
    echo $json;
} else {
    echo 'json_encode failed: ' . json_last_error_msg();
}
?>