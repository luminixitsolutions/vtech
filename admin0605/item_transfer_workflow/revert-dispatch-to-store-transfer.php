<?php
session_start();
include_once '../config.php';
include_once '../auth.php';
$user_id = $_SESSION['Admin']['id'];

$row77 = getRecord("SELECT Roll, Options FROM tbl_users WHERE id='$user_id'");
$Roll = $row77['Roll'] ?? 0;
$Options = isset($row77['Options']) ? explode(',', $row77['Options']) : array();
$is_allowed = ($Roll == 26 || $Roll == 1 || $Roll == 7 || in_array('72', $Options));
if (!$is_allowed) {
    echo "<script>alert('Access denied.'); window.location.href='../dashboard.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['detail_ids']) || !is_array($_POST['detail_ids'])) {
    echo "<script>alert('Please select at least one line to revert.'); window.location.href='view-dispatch-to-store-transfers.php';</script>";
    exit;
}

$hasDetail2Id = false;
$chkCol = $conn->query("SHOW COLUMNS FROM tbl_dispatch_to_store_transfer_details LIKE 'Detail2Id'");
if ($chkCol && $chkCol->num_rows > 0) {
    $hasDetail2Id = true;
}

/**
 * Remove the store row created for this dispatch line (code ends with Detail2Id + TransferId when possible).
 * Returns true if a row was deleted, or if there is nothing left at the store to delete (idempotent).
 */
function delete_matching_store_row($conn, $ToBranchId, $transferId, $detail, $hasDetail2Id) {
    $ProductId = (int)$detail['ProductId'];
    $Qty = (float)$detail['Qty'];
    $ProdType = (int)$detail['ProdType'];
    $SerialNo = $detail['SerialNo'] ?? '';
    $ModelNo = $detail['ModelNo'] ?? '';
    $Detail2Id = ($hasDetail2Id && isset($detail['Detail2Id'])) ? (int)$detail['Detail2Id'] : 0;

    $escBranch = (int)$ToBranchId;
    $suffix = (string)$Detail2Id . (string)$transferId;
    $lenSuffix = strlen($suffix);

    $serialClause = (string)$SerialNo !== ''
        ? " AND SerialNo='" . $conn->real_escape_string((string)$SerialNo) . "'"
        : " AND (SerialNo IS NULL OR SerialNo='')";

    $tryDeleteById = function ($id) use ($conn) {
        $id = (int)$id;
        if ($id <= 0) {
            return false;
        }
        if (!$conn->query("DELETE FROM tbl_distibute_item_details WHERE id='$id' LIMIT 1")) {
            return false;
        }
        $aff = (int)mysqli_affected_rows($conn);
        if ($aff >= 1) {
            return true;
        }
        $chk = getRecord("SELECT id FROM tbl_distibute_item_details WHERE id='$id'");
        return empty($chk) || empty($chk['id']);
    };

    $pickIds = array();

    if ($Detail2Id > 0 && $lenSuffix > 0) {
        $sufEsc = $conn->real_escape_string($suffix);
        $q = "SELECT id FROM tbl_distibute_item_details WHERE BranchId='$escBranch' AND ProductId='$ProductId' AND ProdType='$ProdType' $serialClause
            AND code IS NOT NULL AND TRIM(code)<>'' AND CHAR_LENGTH(code) >= $lenSuffix
            AND RIGHT(TRIM(code), $lenSuffix) = '$sufEsc'";
        $res = $conn->query($q);
        if ($res) {
            while ($r = $res->fetch_assoc()) {
                $pickIds[] = (int)$r['id'];
            }
        }
        if (empty($pickIds)) {
            $q2 = "SELECT id, code FROM tbl_distibute_item_details WHERE BranchId='$escBranch' AND ProductId='$ProductId' AND ProdType='$ProdType' $serialClause";
            $res2 = $conn->query($q2);
            if ($res2) {
                while ($r = $res2->fetch_assoc()) {
                    $code = isset($r['code']) ? (string)$r['code'] : '';
                    if ($code !== '' && strlen($code) >= $lenSuffix && substr($code, -$lenSuffix) === $suffix) {
                        $pickIds[] = (int)$r['id'];
                    }
                }
            }
        }
        $pickIds = array_values(array_unique(array_filter($pickIds)));
        if (count($pickIds) === 1) {
            return $tryDeleteById($pickIds[0]) ? true : false;
        }
        if (count($pickIds) > 1) {
            return $tryDeleteById(max($pickIds)) ? true : false;
        }
    }

    $escSerial = $conn->real_escape_string((string)$SerialNo);
    $escModel = $conn->real_escape_string((string)$ModelNo);

    $baseWhere = "BranchId='$escBranch' AND ProductId='$ProductId' AND ProdType='$ProdType' AND ABS(Qty-$Qty)<0.0001";
    if ((string)$SerialNo !== '') {
        $baseWhere .= " AND SerialNo='$escSerial'";
    } else {
        $baseWhere .= " AND (SerialNo IS NULL OR SerialNo='')";
    }

    $q = "SELECT id FROM tbl_distibute_item_details WHERE $baseWhere";
    if ((string)$ModelNo !== '') {
        $q .= " AND ModelNo='$escModel'";
    } else {
        $q .= " AND (ModelNo IS NULL OR ModelNo='')";
    }
    $q .= " ORDER BY id DESC LIMIT 1";
    $row = getRecord($q);
    if ($row && !empty($row['id'])) {
        return $tryDeleteById((int)$row['id']) ? true : false;
    }

    $q2 = "SELECT id FROM tbl_distibute_item_details WHERE $baseWhere ORDER BY id DESC LIMIT 1";
    $row2 = getRecord($q2);
    if ($row2 && !empty($row2['id'])) {
        return $tryDeleteById((int)$row2['id']) ? true : false;
    }

    $cntRow = getRecord("SELECT COUNT(*) AS c FROM tbl_distibute_item_details WHERE BranchId='$escBranch' AND ProductId='$ProductId' AND ProdType='$ProdType' AND ABS(Qty-$Qty)<0.01" . $serialClause);
    $c = isset($cntRow['c']) ? (int)$cntRow['c'] : 0;
    if ($c === 0) {
        return true;
    }

    return false;
}

$ids = array();
foreach ($_POST['detail_ids'] as $raw) {
    $ids[] = (int)$raw;
}
$ids = array_filter(array_unique($ids));
if (empty($ids)) {
    echo "<script>alert('Invalid selection.'); window.location.href='view-dispatch-to-store-transfers.php';</script>";
    exit;
}

$idList = implode(',', $ids);
$transferWhere = ($Roll == 26) ? "t.DispatchOfficerId='" . (int)$user_id . "'" : "1=1";

$sql = "SELECT d.id AS detail_pk, d.TransferId, d.ProductId, d.ProductName, d.Qty, d.SerialNo, d.ProdType, d.Unit, d.ModelNo, t.ToBranchId, t.DispatchOfficerId";
if ($hasDetail2Id) {
    $sql .= ", d.Detail2Id";
}
$sql .= " FROM tbl_dispatch_to_store_transfer_details d
INNER JOIN tbl_dispatch_to_store_transfer t ON d.TransferId = t.id
WHERE d.id IN ($idList) AND $transferWhere";

$res = $conn->query($sql);
if (!$res || $res->num_rows === 0) {
    echo "<script>alert('No valid lines to revert or access denied.'); window.location.href='view-dispatch-to-store-transfers.php';</script>";
    exit;
}

$lines = array();
while ($r = $res->fetch_assoc()) {
    $lines[] = $r;
}
if (count($lines) !== count($ids)) {
    echo "<script>alert('Some selected lines could not be verified. Nothing was reverted.'); window.location.href='view-dispatch-to-store-transfers.php';</script>";
    exit;
}

$uniqueTransferIds = array_unique(array_map(function ($d) {
    return (int)$d['TransferId'];
}, $lines));
if (count($uniqueTransferIds) !== 1) {
    echo "<script>alert('Invalid selection.'); window.location.href='view-dispatch-to-store-transfers.php';</script>";
    exit;
}

$conn->begin_transaction();
$ok = true;
$transferIdsTouched = array();

foreach ($lines as $detail) {
    $detailPk = (int)$detail['detail_pk'];
    $transferId = (int)$detail['TransferId'];
    $ToBranchId = (int)$detail['ToBranchId'];
    $transferIdsTouched[$transferId] = true;

    if (!delete_matching_store_row($conn, $ToBranchId, $transferId, $detail, $hasDetail2Id)) {
        $ok = false;
        break;
    }
    $ok = $ok && $conn->query("DELETE FROM tbl_dispatch_to_store_transfer_details WHERE id='$detailPk' LIMIT 1");
}

if ($ok) {
    foreach (array_keys($transferIdsTouched) as $tid) {
        $tid = (int)$tid;
        $c = getRecord("SELECT COUNT(*) AS c FROM tbl_dispatch_to_store_transfer_details WHERE TransferId='$tid'");
        if ($c && (int)$c['c'] === 0) {
            $conn->query("DELETE FROM tbl_dispatch_to_store_transfer WHERE id='$tid' LIMIT 1");
        }
    }
    $conn->commit();
    echo "<script>alert('Revert completed. Stock was removed from the destination store and returned to your dispatch list (Transfer to Store screen).'); window.location.href='view-dispatch-to-store-transfers.php';</script>";
} else {
    $conn->rollback();
    echo "<script>alert('Revert failed. Please try again.'); window.location.href='view-dispatch-to-store-transfers.php';</script>";
}
exit;
