<?php
if (isset($_POST['scannedBarcode'])|| isset($_GET['scannedBarcode'])) {
    require_once "conn.php";
    
    // Simulate a scanned barcode for debugging
   
    $scannedBarcode = $_POST['scannedBarcode'];

    // Initialize response array
    $response = array();

    // Fetch ProductId and Quantity for the scanned barcode
    $stmt = $conn->prepare("
        SELECT sd.ProductId, bd.Quantity, sd.BatchId
        FROM barcodedetails bd
        JOIN stockdetails sd ON bd.Barcode = sd.Barcode
        WHERE bd.Fullbarcode = ?
    ");
    if (!$stmt) {
        $response['error'] = true;
        $response['message'] = "Failed to prepare statement: " . $conn->error;
        echo json_encode($response);
        exit();
    }

    $stmt->bind_param("s", $scannedBarcode);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        // No record found for the scanned barcode
        $response['error'] = true;
        $response['message'] = "No record found for the scanned barcode";
        echo json_encode($response);
        $stmt->close();
        exit();
    }

    $stmt->bind_result($productId, $quantity, $batchId);
    $stmt->fetch();
    $stmt->close();

    // Check if the quantity for the scanned barcode is greater than zero
    if ($quantity <= 0) {
        $response['error'] = true;
        $response['message'] = "Quantity for the scanned barcode is zero or negative";
        echo json_encode($response);
        exit();
    }

    // Fetch the batch date for the batchId
    $stmt = $conn->prepare("
        SELECT b.BatchDate
        FROM batchdetails b
        WHERE b.BatchId = ?
    ");
    if (!$stmt) {
        $response['error'] = true;
        $response['message'] = "Failed to prepare statement: " . $conn->error;
        echo json_encode($response);
        exit();
    }

    $stmt->bind_param("i", $batchId);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        // No batch date found for the batchId
        $response['error'] = true;
        $response['message'] = "No batch date found for the batch ID";
        echo json_encode($response);
        $stmt->close();
        exit();
    }

    $stmt->bind_result($batchDate);
    $stmt->fetch();
    $stmt->close();

    // Fetch older batches with the same ProductId, with batch dates earlier than the scanned barcode batch date
    $stmt = $conn->prepare("
        SELECT b.BatchName, sd.Barcode, l.LocationName
        FROM stockdetails sd
        JOIN batchdetails b ON sd.BatchId = b.BatchId
        JOIN locationdetails l ON sd.LocationId = l.LocationId
        WHERE sd.ProductId = ? 
        AND b.BatchDate < ? 
        AND sd.Barcode IN (
            SELECT Barcode
            FROM barcodedetails
            WHERE Quantity > 0
        )
        ORDER BY b.BatchDate ASC
    ");
    if (!$stmt) {
        $response['error'] = true;
        $response['message'] = "Failed to prepare statement: " . $conn->error;
        echo json_encode($response);
        exit();
    }

    $stmt->bind_param("ss", $productId, $batchDate);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($batchName, $barcode, $location);

    $batchDetails = array();
    while ($stmt->fetch()) {
        $batchDetails[] = array(
            'BatchName' => $batchName,
            'Barcode' => $barcode,
            'Location' => $location
        );
    }
    $stmt->close();

    // Prepare the response
    if (count($batchDetails) > 0) {
        $response['error'] = false;
        $response['message'] = "Check Successful!";
        $response['batchDetails'] = $batchDetails;
    } else {
        $response['error'] = false;
        $response['message'] = "Check Successful!";
        $response['batchDetails'] = array();
    }

    // Output the response in JSON format
    echo json_encode($response);
} else {
    // Insufficient Parameters
    $response['error'] = true;
    $response['message'] = "Insufficient Parameters";
    echo json_encode($response);
}
