<?php
session_start();
include_once 'config.php';
include_once 'auth.php';

$user_id = $_SESSION['Admin']['id'];
$row77 = getRecord("SELECT Roll, Options, BranchId FROM tbl_users WHERE id='$user_id'");
$Roll = isset($row77['Roll']) ? (int) $row77['Roll'] : 0;
$Options = isset($row77['Options']) ? explode(',', $row77['Options']) : [];
$canAssignDispatch = ($Roll == 1 || $Roll == 7 || in_array('10', $Options) || in_array('11', $Options));
if (!$canAssignDispatch) {
    echo "<script>alert('Access denied.');window.location.href='view-distribute-item-store.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "<script>alert('Invalid request.');window.location.href='view-distribute-item-store.php';</script>";
    exit;
}

$StoreExeId = isset($_POST['StoreExeId']) ? (int) $_POST['StoreExeId'] : 0;
$CreatedDate = isset($_POST['CreatedDate']) ? trim($_POST['CreatedDate']) : '';
$distIds = isset($_POST['dist_ids']) && is_array($_POST['dist_ids']) ? $_POST['dist_ids'] : [];
$distIds = array_values(array_filter(array_map('intval', $distIds), function ($v) {
    return $v > 0;
}));

if ($StoreExeId <= 0 || $CreatedDate === '' || empty($distIds)) {
    echo "<script>alert('Please choose dispatch officer, assignment date, and at least one store assignment.');window.location.href='view-distribute-item-store.php';</script>";
    exit;
}

$chk = getRecord("SELECT id FROM tbl_users WHERE id='$StoreExeId' AND Status='1' AND Roll=26 LIMIT 1");
if (empty($chk['id'])) {
    echo "<script>alert('Invalid dispatch officer.');window.location.href='view-distribute-item-store.php';</script>";
    exit;
}

$headers = [];
foreach ($distIds as $did) {
    $h = getRecord("SELECT * FROM tbl_distibute_items WHERE id='$did' AND Status=1 LIMIT 1");
    if (!is_array($h) || empty($h['id'])) {
        echo "<script>alert('One of the selected assignments was not found or is inactive.');window.location.href='view-distribute-item-store.php';</script>";
        exit;
    }
    $headers[] = $h;
}

foreach ($distIds as $did) {
    $didInt = (int) $did;
    $alreadyAssigned = getRecord("
        SELECT h2.id, u2.Fname AS OfficerName
        FROM tbl_distibute_items2 h2
        LEFT JOIN tbl_users u2 ON u2.id = h2.StoreExeId
        WHERE h2.Status='1'
          AND h2.Narration LIKE '%DistId(s):%'
          AND h2.Narration REGEXP CONCAT('(^|[^0-9])', '$didInt', '([^0-9]|$)')
        ORDER BY h2.id DESC
        LIMIT 1
    ");
    if (!empty($alreadyAssigned['id'])) {
        $officer = isset($alreadyAssigned['OfficerName']) ? trim((string) $alreadyAssigned['OfficerName']) : '';
        $msg = 'Store assignment #' . $didInt . ' is already assigned to dispatch';
        if ($officer !== '') {
            $msg .= ' (' . $officer . ')';
        }
        $msg .= '.';
        echo "<script>alert(" . json_encode($msg) . ");window.location.href='view-distribute-item-store.php';</script>";
        exit;
    }
}

$branchIds = array_unique(array_map(function ($h) {
    return (int) $h['BranchId'];
}, $headers));
if (count($branchIds) !== 1) {
    echo "<script>alert('Please select assignments for the same store only.');window.location.href='view-distribute-item-store.php';</script>";
    exit;
}
$BranchId = (int) $branchIds[0];

$VehicalDate = null;
$VehicalNo = '';
foreach ($headers as $h) {
    if (!empty($h['VehicalDate']) && $VehicalDate === null) {
        $VehicalDate = $h['VehicalDate'];
    }
    if ($VehicalNo === '' && !empty($h['VehicalNo'])) {
        $VehicalNo = $h['VehicalNo'];
    }
}

$CreatedDateEsc = mysqli_real_escape_string($conn, $CreatedDate);
$Narration = 'Dispatch handoff from store assign ¡¤ DistId(s): ' . implode(',', $distIds);
$NarrationEsc = mysqli_real_escape_string($conn, $Narration);
$VehicalDateSql = ($VehicalDate !== null && $VehicalDate !== '') ? "'" . mysqli_real_escape_string($conn, $VehicalDate) . "'" : 'NULL';
$VehicalNoEsc = mysqli_real_escape_string($conn, $VehicalNo);
$VehicalNoSql = ($VehicalNo !== '') ? "'$VehicalNoEsc'" : 'NULL';

$idList = implode(',', $distIds);
$conn->begin_transaction();
try {
    $sqlIns = "INSERT INTO tbl_distibute_items2 SET BranchId='$BranchId',StoreInchId='0',StoreExeId='$StoreExeId',CreatedDate='$CreatedDateEsc',Narration='$NarrationEsc',Status='1',VehicalDate=$VehicalDateSql,VehicalNo=$VehicalNoSql";
    if (!$conn->query($sqlIns)) {
        throw new Exception($conn->error);
    }
    $newDist2Id = (int) mysqli_insert_id($conn);
    if ($newDist2Id <= 0) {
        throw new Exception('Could not create dispatch batch header.');
    }

    $q = "SELECT * FROM tbl_distibute_item_details WHERE DistId IN ($idList)";
    $res = $conn->query($q);
    if (!$res) {
        throw new Exception($conn->error);
    }
    $inserted = 0;
    while ($d = $res->fetch_assoc()) {
        $ProductId = (int) $d['ProductId'];
        $SerialNo = isset($d['SerialNo']) ? trim((string) $d['SerialNo']) : '';
        if ($ProductId < 1 && $SerialNo === '') {
            continue;
        }
        $ProductName = mysqli_real_escape_string($conn, (string) ($d['ProductName'] ?? ''));
        $Purity = mysqli_real_escape_string($conn, (string) ($d['Purity'] ?? ''));
        if ($Purity === '') {
            $Purity = '-';
        }
        $Qty = mysqli_real_escape_string($conn, (string) ($d['Qty'] ?? '0'));
        $ModelNo = mysqli_real_escape_string($conn, (string) ($d['ModelNo'] ?? ''));
        $SerialEsc = mysqli_real_escape_string($conn, $SerialNo !== '' ? $SerialNo : 'N/A');
        $ProdType = isset($d['ProdType']) ? (int) $d['ProdType'] : 0;
        if ($ProdType !== 1 && $ProdType !== 2) {
            $ProdType = 0;
        }
        $vd = !empty($d['VehicalDate']) ? "'" . mysqli_real_escape_string($conn, $d['VehicalDate']) . "'" : 'NULL';
        $vn = !empty($d['VehicalNo']) ? "'" . mysqli_real_escape_string($conn, $d['VehicalNo']) . "'" : 'NULL';
        $codeEsc = mysqli_real_escape_string($conn, substr(bin2hex(random_bytes(8)) . $newDist2Id . $inserted, 0, 100));

        $sql22 = "INSERT INTO tbl_distibute_item_details2 SET BranchId='$BranchId',StoreExeId='$StoreExeId',DistId='$newDist2Id',StoreInchId='0',
            ProductName='$ProductName',Purity='$Purity',Qty='$Qty',ProductId='$ProductId',ModelNo='$ModelNo',CreatedDate='$CreatedDateEsc',
            SerialNo='$SerialEsc',ProdType='$ProdType',VehicalDate=$vd,VehicalNo=$vn,SellId='0',SellStatus='0',code='$codeEsc'";
        if (!$conn->query($sql22)) {
            throw new Exception($conn->error);
        }
        $inserted++;
    }
    if ($inserted === 0) {
        throw new Exception('No line items to assign.');
    }

    $conn->commit();
    echo "<script>alert('Assigned " . (int) $inserted . " line(s) to dispatch officer.');window.location.href='view-distribute-item-store-executive.php';</script>";
    exit;
} catch (Exception $e) {
    $conn->rollback();
    $msg = $e->getMessage();
    echo "<script>alert(" . json_encode('Assign failed: ' . $msg) . ");window.location.href='view-distribute-item-store.php';</script>";
    exit;
}
