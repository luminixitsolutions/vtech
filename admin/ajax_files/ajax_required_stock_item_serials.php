<?php
/**
 * JSON: stock lines for a product — serial/bag (ProdType 1–2) plus bulk (ProdType 0) where relevant.
 * Scopes: all | store | dispatch | ledger (branch / dispatch officer filters).
 * "Available by store" uses bulk dispatch/store totals; this endpoint now returns matching bulk rows.
 */
session_start();
header('Content-Type: application/json; charset=utf-8');
include_once '../config.php';

if (empty($_SESSION['Admin']['id'])) {
    echo json_encode(['ok' => false, 'message' => 'Unauthorized']);
    exit;
}

$pid = isset($_POST['product_id']) ? (int) $_POST['product_id'] : 0;
if ($pid <= 0) {
    echo json_encode(['ok' => false, 'message' => 'Invalid product']);
    exit;
}

$scope = isset($_POST['scope']) ? strtolower(trim((string) $_POST['scope'])) : 'all';
if (!in_array($scope, ['all', 'store', 'dispatch', 'ledger'], true)) {
    $scope = 'all';
}
$branch_id = isset($_POST['branch_id']) ? (int) $_POST['branch_id'] : 0;
$store_exe_id = isset($_POST['store_exe_id']) ? (int) $_POST['store_exe_id'] : 0;

$rows = [];

$stkExtra = '';
$d1Extra = '';
$d2Extra = '';
$runStk = true;
$runD1 = true;
$runD2 = true;

/** Same open-transfer exclusion as upb_available_locations / serial D2 query */
$d2JoinOpen = '';
$d2WhereOpen = '';
if (isset($conn) && $conn) {
    $hasDispatchTransferTbl = false;
    $hasDetail2IdCol = false;
    $t1 = $conn->query("SHOW TABLES LIKE 'tbl_dispatch_to_store_transfer_details'");
    if ($t1 && $t1->num_rows > 0) {
        $hasDispatchTransferTbl = true;
        $c = $conn->query("SHOW COLUMNS FROM tbl_dispatch_to_store_transfer_details LIKE 'Detail2Id'");
        if ($c && $c->num_rows > 0) {
            $hasDetail2IdCol = true;
        }
    }
    if ($hasDispatchTransferTbl && $hasDetail2IdCol) {
        $d2JoinOpen = "LEFT JOIN (SELECT DISTINCT Detail2Id FROM tbl_dispatch_to_store_transfer_details WHERE Detail2Id IS NOT NULL) td_open ON td_open.Detail2Id = d2.id";
        $d2WhereOpen = 'AND td_open.Detail2Id IS NULL';
    }
}

if ($scope === 'store') {
    $runD2 = false;
    $stkExtra = " AND ts.BranchId = '" . $branch_id . "'";
    $d1Extra = " AND d.BranchId = '" . $branch_id . "'";
} elseif ($scope === 'dispatch') {
    $runStk = false;
    $runD1 = false;
    $d2Extra = " AND d2.StoreExeId = '" . $store_exe_id . "'";
    if ($branch_id > 0) {
        $d2Extra .= " AND d2.BranchId = '" . $branch_id . "'";
    }
} elseif ($scope === 'ledger') {
    $runD1 = false;
    $runD2 = false;
    $stkExtra = " AND ts.BranchId = '" . $branch_id . "'";
}

if ($runStk) {
    $sqlStk = "SELECT ts.SerialNo AS serial_no,
        ts.ProdType AS prod_type,
        ts.BranchId AS branch_id,
        'Stock ledger' AS source,
        COALESCE(
            NULLIF(TRIM(MAX(tb.Name)), ''),
            IF(ts.BranchId = 0, 'Main / central (ledger)', CONCAT('Branch #', ts.BranchId))
        ) AS location,
        SUM(CASE WHEN ts.CrDr = 'cr' THEN ts.Qty ELSE 0 END) -
        SUM(CASE WHEN ts.CrDr = 'dr' THEN ts.Qty ELSE 0 END) AS net_qty
    FROM tbl_stocks ts
    LEFT JOIN tbl_branch tb ON tb.id = ts.BranchId
    WHERE ts.Status = 1 AND ts.ProductId = '" . $pid . "'
      AND ts.ProdType IN (1, 2)
      AND TRIM(IFNULL(ts.SerialNo, '')) != ''
      AND UPPER(TRIM(ts.SerialNo)) != 'N/A'
      " . $stkExtra . "
    GROUP BY ts.SerialNo, ts.BranchId, ts.ProdType
    HAVING (SUM(CASE WHEN ts.CrDr = 'cr' THEN ts.Qty ELSE 0 END) - SUM(CASE WHEN ts.CrDr = 'dr' THEN ts.Qty ELSE 0 END)) > 0.0001
    ORDER BY ts.SerialNo, ts.BranchId";
    $listStk = getList($sqlStk);
    if (!is_array($listStk)) {
        $listStk = [];
    }
    foreach ($listStk as $r) {
        $rows[] = [
            'serial_no' => (string) ($r['serial_no'] ?? ''),
            'location' => (string) ($r['location'] ?? ''),
            'qty' => round((float) ($r['net_qty'] ?? 0), 4),
            'source' => (string) ($r['source'] ?? ''),
            'prod_type' => (int) ($r['prod_type'] ?? 0),
        ];
    }
}

if ($scope === 'ledger') {
    $sqlStkBulk = "SELECT
        COALESCE(
            NULLIF(TRIM(MAX(tb.Name)), ''),
            IF(ts.BranchId = 0, 'Main / central (ledger)', CONCAT('Branch #', ts.BranchId))
        ) AS location,
        SUM(CASE WHEN ts.CrDr = 'cr' THEN ts.Qty ELSE 0 END) -
        SUM(CASE WHEN ts.CrDr = 'dr' THEN ts.Qty ELSE 0 END) AS net_qty
    FROM tbl_stocks ts
    LEFT JOIN tbl_branch tb ON tb.id = ts.BranchId
    WHERE ts.Status = 1 AND ts.ProductId = '" . $pid . "'
      AND ts.ProdType = 0
      " . $stkExtra . "
    GROUP BY ts.BranchId
    HAVING (SUM(CASE WHEN ts.CrDr = 'cr' THEN ts.Qty ELSE 0 END) - SUM(CASE WHEN ts.CrDr = 'dr' THEN ts.Qty ELSE 0 END)) > 0.0001";
    $listStkBulk = getList($sqlStkBulk);
    if (is_array($listStkBulk)) {
        foreach ($listStkBulk as $r) {
            $rows[] = [
                'serial_no' => '(bulk — no serial line)',
                'location' => (string) ($r['location'] ?? ''),
                'qty' => round((float) ($r['net_qty'] ?? 0), 4),
                'source' => 'Stock ledger (bulk)',
                'prod_type' => 0,
            ];
        }
    }
}

if ($runD1) {
    $sqlD1 = "SELECT d.SerialNo AS serial_no,
        d.ProdType AS prod_type,
        d.BranchId AS branch_id,
        'Store assign' AS source,
        CONCAT('Store: ', COALESCE(NULLIF(TRIM(MAX(b.Name)), ''), CONCAT('#', d.BranchId))) AS location,
        SUM(d.Qty) AS net_qty
    FROM tbl_distibute_item_details d
    INNER JOIN tbl_distibute_items h ON h.id = d.DistId AND h.Status = 1
    LEFT JOIN tbl_branch b ON b.id = d.BranchId
    WHERE d.ProductId = '" . $pid . "'
      AND d.ProdType IN (1, 2)
      AND TRIM(IFNULL(d.SerialNo, '')) != ''
      AND UPPER(TRIM(d.SerialNo)) != 'N/A'
      " . $d1Extra . "
    GROUP BY d.SerialNo, d.BranchId, d.ProdType
    HAVING SUM(d.Qty) > 0.0001
    ORDER BY d.SerialNo";
    $listD1 = getList($sqlD1);
    if (!is_array($listD1)) {
        $listD1 = [];
    }
    foreach ($listD1 as $r) {
        $rows[] = [
            'serial_no' => (string) ($r['serial_no'] ?? ''),
            'location' => (string) ($r['location'] ?? ''),
            'qty' => round((float) ($r['net_qty'] ?? 0), 4),
            'source' => (string) ($r['source'] ?? ''),
            'prod_type' => (int) ($r['prod_type'] ?? 0),
        ];
    }
}

if ($scope === 'store' && $branch_id >= 0) {
    $bid = (int) $branch_id;
    $sqlStoreBulk = "SELECT d.BranchId,
        (COALESCE(SUM(d.Qty), 0) - COALESCE((SELECT SUM(x.Qty) FROM tbl_distibute_item_details2 x
            WHERE x.BranchId = d.BranchId AND x.ProductId = d.ProductId AND x.ProdType = 0), 0)) AS net_qty,
        CONCAT('Store: ', COALESCE(NULLIF(TRIM(MAX(b.Name)), ''), CONCAT('#', d.BranchId))) AS location
    FROM tbl_distibute_item_details d
    INNER JOIN tbl_distibute_items h ON h.id = d.DistId AND h.Status = 1
    LEFT JOIN tbl_branch b ON b.id = d.BranchId
    WHERE d.ProdType = 0 AND d.ProductId = '" . $pid . "' AND d.BranchId = '" . $bid . "'
    GROUP BY d.BranchId
    HAVING (COALESCE(SUM(d.Qty), 0) - COALESCE((SELECT SUM(x.Qty) FROM tbl_distibute_item_details2 x
        WHERE x.BranchId = d.BranchId AND x.ProductId = d.ProductId AND x.ProdType = 0), 0)) > 0.0001";
    $listSb = getList($sqlStoreBulk);
    if (is_array($listSb)) {
        foreach ($listSb as $r) {
            $rows[] = [
                'serial_no' => '(bulk — no serial line)',
                'location' => (string) ($r['location'] ?? ''),
                'qty' => round((float) ($r['net_qty'] ?? 0), 4),
                'source' => 'Store balance (bulk)',
                'prod_type' => 0,
            ];
        }
    }
}

if ($runD2) {
    $sqlD2 = "SELECT d2.SerialNo AS serial_no,
        MAX(d2.ProdType) AS prod_type,
        d2.BranchId AS branch_id,
        'Dispatch / executive' AS source,
        CONCAT(
            COALESCE(NULLIF(TRIM(MAX(u.Fname)), ''), CONCAT('User #', MAX(d2.StoreExeId))),
            ' — ',
            COALESCE(NULLIF(TRIM(MAX(b.Name)), ''), CONCAT('Branch #', d2.BranchId))
        ) AS location,
        SUM(d2.Qty) AS net_qty
    FROM tbl_distibute_item_details2 d2
    INNER JOIN tbl_distibute_items2 h ON h.id = d2.DistId AND h.Status = 1
    LEFT JOIN tbl_users u ON u.id = d2.StoreExeId
    LEFT JOIN tbl_branch b ON b.id = d2.BranchId
    " . $d2JoinOpen . "
    WHERE d2.ProductId = '" . $pid . "'
      AND d2.ProdType IN (1, 2)
      AND TRIM(IFNULL(d2.SerialNo, '')) != ''
      AND UPPER(TRIM(d2.SerialNo)) != 'N/A'
      " . $d2WhereOpen . "
      " . $d2Extra . "
    GROUP BY d2.SerialNo, d2.BranchId, d2.StoreExeId
    HAVING SUM(d2.Qty) > 0.0001
    ORDER BY d2.SerialNo";
    $listD2 = getList($sqlD2);
    if (!is_array($listD2)) {
        $listD2 = [];
    }
    foreach ($listD2 as $r) {
        $rows[] = [
            'serial_no' => (string) ($r['serial_no'] ?? ''),
            'location' => (string) ($r['location'] ?? ''),
            'qty' => round((float) ($r['net_qty'] ?? 0), 4),
            'source' => (string) ($r['source'] ?? ''),
            'prod_type' => (int) ($r['prod_type'] ?? 0),
        ];
    }

    if ($scope === 'dispatch') {
        $sqlD2Bulk = "SELECT d2.StoreExeId, d2.BranchId,
            'Dispatch / executive (bulk)' AS source,
            CONCAT(
                COALESCE(NULLIF(TRIM(MAX(u.Fname)), ''), CONCAT('User #', MAX(d2.StoreExeId))),
                ' — ',
                COALESCE(NULLIF(TRIM(MAX(b.Name)), ''), CONCAT('Branch #', d2.BranchId))
            ) AS location,
            SUM(d2.Qty) AS net_qty
        FROM tbl_distibute_item_details2 d2
        INNER JOIN tbl_distibute_items2 h ON h.id = d2.DistId AND h.Status = 1
        LEFT JOIN tbl_users u ON u.id = d2.StoreExeId
        LEFT JOIN tbl_branch b ON b.id = d2.BranchId
        " . $d2JoinOpen . "
        WHERE d2.ProductId = '" . $pid . "'
          AND d2.ProdType = 0
          AND d2.StoreExeId > 0
          " . $d2WhereOpen . "
          " . $d2Extra . "
        GROUP BY d2.StoreExeId, d2.BranchId, u.Fname, b.Name
        HAVING SUM(d2.Qty) > 0.0001";
        $listD2Bulk = getList($sqlD2Bulk);
        if (is_array($listD2Bulk)) {
            foreach ($listD2Bulk as $r) {
                $rows[] = [
                    'serial_no' => '(bulk — no serial line)',
                    'location' => (string) ($r['location'] ?? ''),
                    'qty' => round((float) ($r['net_qty'] ?? 0), 4),
                    'source' => (string) ($r['source'] ?? ''),
                    'prod_type' => 0,
                ];
            }
        }
    }
}

/**
 * Main grid "View item" uses scope=all — same bulk picture as upb_available_locations
 * (store net, dispatch officer stock, else ledger by branch). Serial/bag lines are already in $rows.
 */
if ($scope === 'all') {
    $distribAny = false;

    $sqlStoreAll = "SELECT d.BranchId, MAX(b.Name) AS BranchName,
        (COALESCE(SUM(d.Qty),0) - COALESCE((SELECT SUM(x.Qty) FROM tbl_distibute_item_details2 x
            WHERE x.BranchId = d.BranchId AND x.ProductId = d.ProductId AND x.ProdType = 0), 0)) AS AvailQty
        FROM tbl_distibute_item_details d
        INNER JOIN tbl_distibute_items h ON h.id = d.DistId AND h.Status = 1
        INNER JOIN tbl_branch b ON b.id = d.BranchId
        WHERE d.ProdType = 0 AND d.ProductId='" . $pid . "'
        GROUP BY d.BranchId
        HAVING AvailQty > 0.0001
        ORDER BY MAX(b.Name)";
    $listStoreAll = getList($sqlStoreAll);
    if (is_array($listStoreAll)) {
        foreach ($listStoreAll as $r) {
            $bn = isset($r['BranchName']) ? trim((string) $r['BranchName']) : '';
            if ($bn === '') {
                continue;
            }
            $distribAny = true;
            $rows[] = [
                'serial_no' => '(bulk — no serial line)',
                'location' => 'Store (balance): ' . $bn,
                'qty' => round((float) ($r['AvailQty'] ?? 0), 4),
                'source' => 'Store balance (bulk)',
                'prod_type' => 0,
            ];
        }
    }

    $sqlDispAll = "SELECT d2.StoreExeId, d2.BranchId,
        COALESCE(u.Fname, CONCAT('User #', d2.StoreExeId)) AS officer_name,
        COALESCE(NULLIF(TRIM(b.Name), ''), 'branch not set') AS assign_branch_name,
        SUM(d2.Qty) AS AvailQty
        FROM tbl_distibute_item_details2 d2
        INNER JOIN tbl_distibute_items2 h ON h.id = d2.DistId AND h.Status = 1
        LEFT JOIN tbl_users u ON u.id = d2.StoreExeId
        LEFT JOIN tbl_branch b ON b.id = d2.BranchId
        " . $d2JoinOpen . "
        WHERE d2.ProdType = 0 AND d2.StoreExeId > 0 AND d2.ProductId='" . $pid . "' " . $d2WhereOpen . "
        GROUP BY d2.StoreExeId, d2.BranchId, u.Fname, b.Name
        HAVING SUM(d2.Qty) > 0.0001
        ORDER BY officer_name, assign_branch_name";
    $listDispAll = getList($sqlDispAll);
    if (is_array($listDispAll)) {
        foreach ($listDispAll as $r) {
            $on = isset($r['officer_name']) ? trim((string) $r['officer_name']) : '';
            $br = isset($r['assign_branch_name']) ? trim((string) $r['assign_branch_name']) : '';
            if ($br === '') {
                $br = 'branch not set';
            }
            $distribAny = true;
            $rows[] = [
                'serial_no' => '(bulk — no serial line)',
                'location' => 'Dispatch officer: ' . $on . ' (store: ' . $br . ')',
                'qty' => round((float) ($r['AvailQty'] ?? 0), 4),
                'source' => 'Dispatch / executive (bulk)',
                'prod_type' => 0,
            ];
        }
    }

    if (!$distribAny) {
        $sqlLedgerBulk = "SELECT ts.BranchId,
            COALESCE(
                NULLIF(TRIM(MAX(tb.Name)), ''),
                IF(ts.BranchId = 0, 'Main / central stock (ledger, not assigned to a store)',
                    CONCAT('Branch #', ts.BranchId, ' (no name in master)'))
            ) AS location,
            SUM(CASE WHEN ts.CrDr='cr' THEN ts.Qty ELSE 0 END) -
            SUM(CASE WHEN ts.CrDr='dr' THEN ts.Qty ELSE 0 END) AS net_qty
            FROM tbl_stocks ts
            LEFT JOIN tbl_branch tb ON tb.id = ts.BranchId
            WHERE ts.Status=1 AND ts.ProductId='" . $pid . "' AND ts.ProdType=0
            GROUP BY ts.BranchId
            HAVING (SUM(CASE WHEN ts.CrDr='cr' THEN ts.Qty ELSE 0 END) - SUM(CASE WHEN ts.CrDr='dr' THEN ts.Qty ELSE 0 END)) > 0.0001
            ORDER BY ts.BranchId";
        $listLb = getList($sqlLedgerBulk);
        if (is_array($listLb)) {
            foreach ($listLb as $r) {
                $rows[] = [
                    'serial_no' => '(bulk — no serial line)',
                    'location' => (string) ($r['location'] ?? ''),
                    'qty' => round((float) ($r['net_qty'] ?? 0), 4),
                    'source' => 'Stock ledger (bulk)',
                    'prod_type' => 0,
                ];
            }
        }
    }
}

echo json_encode(['ok' => true, 'rows' => $rows, 'count' => count($rows), 'scope' => $scope]);
