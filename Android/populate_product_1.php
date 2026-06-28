<?php
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
        echo json_encode($return_arr);
    }
}
?>