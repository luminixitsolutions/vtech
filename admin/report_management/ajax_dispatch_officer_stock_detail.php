<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
include_once '../config.php';
include_once '../auth.php';
include_once 'inc-dispatch-officer-stock.php';

$user_id = (int) $_SESSION['Admin']['id'];
$sql77 = "SELECT * FROM tbl_users WHERE id='$user_id'";
$row77 = getRecord($sql77);
$Roll = (int) ($row77['Roll'] ?? 0);
$sessionBranchId = (int) ($row77['BranchId'] ?? 0);

$type = isset($_POST['type']) ? trim((string) $_POST['type']) : '';
$BranchId = isset($_POST['BranchId']) ? (int) $_POST['BranchId'] : 0;
$StoreExeId = isset($_POST['StoreExeId']) ? (int) $_POST['StoreExeId'] : 0;
$ProductId = isset($_POST['ProductId']) ? (int) $_POST['ProductId'] : 0;
$FromDate = isset($_POST['FromDate']) ? trim((string) $_POST['FromDate']) : '';
$ToDate = isset($_POST['ToDate']) ? trim((string) $_POST['ToDate']) : '';

if (!dispatch_officer_stock_allowed($Roll, $sessionBranchId, $BranchId, $StoreExeId) || $ProductId < 1) {
    echo json_encode(['ok' => false, 'error' => 'Invalid request']);
    exit;
}

if ($type !== 'credit' && $type !== 'debit') {
    echo json_encode(['ok' => false, 'error' => 'Invalid type']);
    exit;
}

$lines = [];
$b = (int) $BranchId;
$p = (int) $ProductId;
$e = (int) $StoreExeId;

if ($type === 'credit') {
    $sql = "SELECT d2.id, d2.Qty, d2.SerialNo, d2.ModelNo, d2.Purity, d2.CreatedDate, d2.VehicalNo, d2.VehicalDate, d2.ProdType,
        h2.Narration AS BatchNarration
        FROM tbl_distibute_item_details2 d2
        LEFT JOIN tbl_distibute_items2 h2 ON h2.id = d2.DistId
        WHERE d2.BranchId='$b' AND d2.ProductId='$p' AND d2.StoreExeId='$e'";
    if ($FromDate !== '') {
        $fd = mysqli_real_escape_string($conn, $FromDate);
        $sql .= " AND d2.CreatedDate>='$fd'";
    }
    if ($ToDate !== '') {
        $td = mysqli_real_escape_string($conn, $ToDate);
        $sql .= " AND d2.CreatedDate<='$td'";
    }
    $sql .= " ORDER BY d2.CreatedDate DESC, d2.id DESC";
    $res = $conn->query($sql);
    if ($res) {
        while ($r = $res->fetch_assoc()) {
            $lines[] = [
                'Qty' => $r['Qty'],
                'SerialNo' => $r['SerialNo'],
                'ModelNo' => $r['ModelNo'],
                'Purity' => $r['Purity'],
                'CreatedDate' => $r['CreatedDate'],
                'VehicalNo' => $r['VehicalNo'],
                'VehicalDate' => $r['VehicalDate'],
                'Narration' => $r['BatchNarration'],
            ];
        }
    }
} else {
    $sql = "SELECT id, Qty, SerialNo, ModelNo, CreatedDate, Narration, VehicalNo, VehicalDate, CrDr, SellType, ProductName
        FROM tbl_stocks
        WHERE BranchId='$b' AND ProductId='$p' AND CreatedBy='$e'";
    if ($FromDate !== '') {
        $fd = mysqli_real_escape_string($conn, $FromDate);
        $sql .= " AND CreatedDate>='$fd'";
    }
    if ($ToDate !== '') {
        $td = mysqli_real_escape_string($conn, $ToDate);
        $sql .= " AND CreatedDate<='$td'";
    }
    $sql .= " ORDER BY CreatedDate DESC, id DESC";
    $res = $conn->query($sql);
    if ($res) {
        while ($r = $res->fetch_assoc()) {
            $lines[] = [
                'Qty' => $r['Qty'],
                'SerialNo' => $r['SerialNo'],
                'ModelNo' => $r['ModelNo'],
                'CreatedDate' => $r['CreatedDate'],
                'Narration' => $r['Narration'],
                'VehicalNo' => $r['VehicalNo'],
                'VehicalDate' => $r['VehicalDate'],
                'CrDr' => $r['CrDr'],
                'SellType' => $r['SellType'],
                'ProductName' => $r['ProductName'],
            ];
        }
    }
}

echo json_encode(['ok' => true, 'lines' => $lines, 'type' => $type]);
