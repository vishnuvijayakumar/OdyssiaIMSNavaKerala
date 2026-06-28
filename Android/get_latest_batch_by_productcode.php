<?php
require_once "conn.php";

// Fetch the product ID from the request
$productId = $_POST['ProductId'];

if ($productId) {
    $sql = "SELECT bd.BatchName 
            FROM stockdetails sd 
            INNER JOIN batchdetails bd ON sd.BatchId = bd.BatchId 
            INNER JOIN productdetails pd ON sd.ProductId = pd.ProductId
            WHERE pd.ItemCode = ? AND sd.StockType = 'IN - First Entry'
            ORDER BY bd.BatchId DESC LIMIT 1";
            
            

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $stmt->bind_result($batchName);
    $stmt->fetch();
    $stmt->close();

    if ($batchName) {
        echo json_encode(["success" => true, "batchName" => $batchName]);
    } else {
        echo json_encode(["success" => false, "message" => "No batch found."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid Product ID."]);
}

$conn->close();
?>
