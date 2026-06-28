<?php
require_once "conn.php";

header('Content-Type: application/json');

// Initialize an empty result array
$result = array();

// Decode the incoming JSON data
$arr = json_decode(file_get_contents('php://input'));

if (!$arr) {
    echo json_encode(['error' => true, 'message' => 'Invalid input']);
    exit;
}

// Process each entry in the array
foreach ($arr as $obj) {
    $barcode = $obj->barcodeB ?? null;
    $location = $obj->locationB ?? null;

    if (!$barcode || !$location) {
        $response = [
            'Barcode' => $barcode ?? 'N/A',
            'error' => true,
            'message' => 'Invalid barcode or location'
        ];
        array_push($result, $response);
        continue;
    }

    // Fetch the LocationId based on the provided location name
    $locationQuery = "SELECT LocationId FROM locationdetails WHERE LocationName = ?";
    $stmtLocation = $conn->prepare($locationQuery);
    $stmtLocation->bind_param('s', $location);
    $stmtLocation->execute();
    $locationResult = $stmtLocation->get_result();

    if ($locationResult->num_rows === 0) {
        $response = [
            'Barcode' => $barcode,
            'error' => true,
            'message' => "Location '$location' not found"
        ];
        array_push($result, $response);
        continue;
    }

    $locationRow = $locationResult->fetch_assoc();
    $locationId = $locationRow['LocationId'];
    
    
        // Fetch the BarcodeId based on the provided barcode name
    $barcodeQuery = "SELECT Barcode FROM barcodedetails WHERE Fullbarcode = ?";
    $stmtBarcode = $conn->prepare($barcodeQuery);
    $stmtBarcode->bind_param('s', $barcode);
    $stmtBarcode->execute();
    $barcodeResult = $stmtBarcode->get_result();

    if ($barcodeResult->num_rows === 0) {
        $response = [
            'Barcode' => $barcode,
            'error' => true,
            'message' => "Barcode '$location' not found"
        ];
        array_push($result, $response);
        continue;
    }

    $barcodeRow = $barcodeResult->fetch_assoc();
    $barcodeId = $barcodeRow['Barcode'];
    
    
    

    // Update the stockdetails table for the given barcode
    $updateQuery = "UPDATE stockdetails SET LocationId = ?, Updated_at = NOW() WHERE Barcode = ?";
    $stmtUpdate = $conn->prepare($updateQuery);
    $stmtUpdate->bind_param('is', $locationId, $barcodeId);

    if ($stmtUpdate->execute()) {
        $response = [
            'Barcode' => $barcode,
            'error' => false,
            'message' => 'Location updated successfully'
        ];
    } else {
        $response = [
            'Barcode' => $barcode,
            'error' => true,
            'message' => 'Failed to update location'
        ];
    }

    array_push($result, $response);
}

// Output the result as a JSON response
echo json_encode($result);
?>
