<?php
include_once 'config.php';

$custId = $_POST['custId'] ?? 0;

$data = [];
$BagName = '';

if ($custId) {
    // --- Get Bag Name ---
    $sql2 = "SELECT tp.ProductName 
             FROM tbl_users tu 
             INNER JOIN tbl_products tp ON tp.id = tu.BagId 
             WHERE tu.id = '$custId'";
    $row2 = getRecord($sql2);
    if ($row2) {
        $BagName = ($row2['ProductName'] ?? '') . " (Bag)";
    }

    // --- Get Specification Records ---
    $sql = "SELECT * FROM tbl_cust_product_specification 
            WHERE CustId = '$custId' AND SpecType IN(2,1) ORDER BY SpecType DESC";
    $res = $conn->query($sql);
    while ($row = $res->fetch_assoc()) {
        $data[] = [
            'ItemName' => $row['ProdName'] ?? '',
            'Qty'      => $row['Qty'] ?? '',
            'Unit'     => $row['Unit'] ?? ''
        ];
    }
}

// ✅ Return structured JSON with BagName and Specification Data
echo json_encode([
    //'BagName' => $BagName,
    'Specification' => $data
]);
?>
