<?php

/**
 * Store stock report:
 * Inward  = store receipt (allotment + dispatch-to-store + store-to-store in) via tbl_distibute_item_details
 * Outward = delivery challans + store-to-store transfer out
 * Balance = Inward − Outward
 */

function mobileStockFormatQty($qty)
{
    $qty = (float) $qty;
    if ($qty == floor($qty)) {
        return number_format($qty, 0);
    }

    return number_format($qty, 2);
}

function mobileStockGetStoreBalancesByBranch()
{
    global $conn;

    $sql = "SELECT branch_id, branch_name, SUM(line_avail) AS avail_qty
        FROM (
            SELECT b.id AS branch_id, b.Name AS branch_name, d.ProductId,
                (COALESCE(SUM(d.Qty), 0) - COALESCE((
                    SELECT SUM(x.Qty) FROM tbl_distibute_item_details2 x
                    WHERE x.BranchId = d.BranchId AND x.ProductId = d.ProductId AND x.ProdType = 0
                ), 0)) AS line_avail
            FROM tbl_distibute_item_details d
            INNER JOIN tbl_branch b ON b.id = d.BranchId
            WHERE d.ProdType = 0 AND b.Status = 1
            GROUP BY b.id, b.Name, d.BranchId, d.ProductId
            HAVING line_avail > 0.0001
        ) t
        GROUP BY branch_id, branch_name
        ORDER BY branch_name ASC";

    $rows = array();
    $res = $conn->query($sql);
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $rows[] = $row;
        }
    }

    return $rows;
}

function mobileStockGetDispatchBalancesByOfficer()
{
    global $conn;

    $sql = "SELECT branch_id, branch_name, store_exe_id, officer_name, SUM(line_bal) AS avail_qty
        FROM (
            SELECT d2.BranchId AS branch_id, b.Name AS branch_name, d2.StoreExeId AS store_exe_id,
                COALESCE(NULLIF(TRIM(u.Fname), ''), CONCAT('Officer #', d2.StoreExeId)) AS officer_name,
                d2.ProductId,
                COALESCE(SUM(d2.Qty), 0) - COALESCE((
                    SELECT SUM(s.Qty) FROM tbl_stocks s
                    WHERE s.BranchId = d2.BranchId AND s.ProductId = d2.ProductId AND s.CreatedBy = d2.StoreExeId
                ), 0) AS line_bal
            FROM tbl_distibute_item_details2 d2
            LEFT JOIN tbl_branch b ON b.id = d2.BranchId
            LEFT JOIN tbl_users u ON u.id = d2.StoreExeId
            WHERE d2.ProdType = 0 AND d2.StoreExeId > 0
            GROUP BY d2.BranchId, b.Name, d2.StoreExeId, u.Fname, d2.ProductId
            HAVING line_bal > 0.0001
        ) t
        GROUP BY branch_id, branch_name, store_exe_id, officer_name
        ORDER BY branch_name ASC, officer_name ASC";

    $rows = array();
    $res = $conn->query($sql);
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $rows[] = $row;
        }
    }

    return $rows;
}

function mobileStockGetTotalAvailableQty()
{
    $total = 0.0;
    foreach (mobileStockGetStoreBalancesByBranch() as $row) {
        $total += (float) $row['avail_qty'];
    }
    foreach (mobileStockGetDispatchBalancesByOfficer() as $row) {
        $total += (float) $row['avail_qty'];
    }

    return $total;
}

function mobileStockGetStoreItemStock($branchId)
{
    global $conn;

    $branchId = (int) $branchId;
    if ($branchId < 1) {
        return array('items' => array(), 'branch_name' => '');
    }

    $branch = getRecord("SELECT Name FROM tbl_branch WHERE id='$branchId'");
    $branchName = $branch['Name'] ?? '';

    $sql = "SELECT d.ProductId, MAX(d.ProductName) AS ProductName,
            MAX(COALESCE(tp.Unit, d.Purity, '')) AS unit_label,
            (COALESCE(SUM(d.Qty), 0) - COALESCE((
                SELECT SUM(x.Qty) FROM tbl_distibute_item_details2 x
                WHERE x.BranchId = d.BranchId AND x.ProductId = d.ProductId AND x.ProdType = 0
            ), 0)) AS avail_qty
        FROM tbl_distibute_item_details d
        LEFT JOIN tbl_products tp ON tp.id = d.ProductId
        WHERE d.ProdType = 0 AND d.BranchId = '$branchId'
        GROUP BY d.ProductId
        HAVING avail_qty > 0.0001
        ORDER BY ProductName ASC";

    $items = array();
    $res = $conn->query($sql);
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $items[] = $row;
        }
    }

    return array('items' => $items, 'branch_name' => $branchName);
}

function mobileStockGetDispatchItemStock($branchId, $storeExeId)
{
    global $conn;

    $branchId = (int) $branchId;
    $storeExeId = (int) $storeExeId;
    if ($branchId < 1 || $storeExeId < 1) {
        return array('items' => array(), 'branch_name' => '', 'officer_name' => '');
    }

    $branch = getRecord("SELECT Name FROM tbl_branch WHERE id='$branchId'");
    $officer = getRecord("SELECT Fname FROM tbl_users WHERE id='$storeExeId'");
    $branchName = $branch['Name'] ?? '';
    $officerName = trim($officer['Fname'] ?? '') !== '' ? $officer['Fname'] : ('Officer #' . $storeExeId);

    $sql = "SELECT d2.ProductId, MAX(d2.ProductName) AS ProductName,
            MAX(COALESCE(d2.Purity, '')) AS unit_label,
            COALESCE(SUM(d2.Qty), 0) - COALESCE((
                SELECT SUM(s.Qty) FROM tbl_stocks s
                WHERE s.BranchId = d2.BranchId AND s.ProductId = d2.ProductId AND s.CreatedBy = d2.StoreExeId
            ), 0) AS avail_qty
        FROM tbl_distibute_item_details2 d2
        WHERE d2.ProdType = 0 AND d2.BranchId = '$branchId' AND d2.StoreExeId = '$storeExeId'
        GROUP BY d2.ProductId
        HAVING avail_qty > 0.0001
        ORDER BY ProductName ASC";

    $items = array();
    $res = $conn->query($sql);
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $items[] = $row;
        }
    }

    return array(
        'items' => $items,
        'branch_name' => $branchName,
        'officer_name' => $officerName,
    );
}

function mobileStockGetSessionUser()
{
    $session = null;
    if (!empty($_SESSION['User']['id'])) {
        $session = $_SESSION['User'];
    } elseif (!empty($_SESSION['Admin']['id'])) {
        $session = $_SESSION['Admin'];
        if (empty($_SESSION['User']['id'])) {
            $_SESSION['User'] = $session;
        }
    }
    if (!$session) {
        return null;
    }

    $uid = (int) $session['id'];

    return getRecord("SELECT * FROM tbl_users WHERE id='$uid' LIMIT 1");
}

function mobileStockGetBranchList($roll, $userBranchId)
{
    if ((int) $roll === 1 || (int) $roll === 7) {
        $sql = "SELECT id, Name FROM tbl_branch WHERE Status='1' ORDER BY Name ASC";
    } else {
        $userBranchId = (int) $userBranchId;
        $sql = "SELECT id, Name FROM tbl_branch WHERE Status='1' AND id='$userBranchId' ORDER BY Name ASC";
    }

    $list = getList($sql);

    return is_array($list) ? $list : array();
}

function mobileStockCanAccessBranch($roll, $userBranchId, $branchId)
{
    $branchId = (int) $branchId;
    if ($branchId < 1) {
        return false;
    }
    if ((int) $roll === 1 || (int) $roll === 7) {
        return true;
    }

    return $branchId === (int) $userBranchId;
}

function mobileStockBuildReport2DateSql($fromDate, $toDate)
{
    global $conn;

    $dateSqlDist = '';
    $dateSqlStockCr = '';
    $dateSqlStockDr = '';

    if ($fromDate !== '') {
        $fd = mysqli_real_escape_string($conn, $fromDate);
        $dateSqlDist .= " AND CreatedDate>='$fd'";
        $dateSqlStockCr .= " AND CreatedDate>='$fd'";
        $dateSqlStockDr .= " AND CreatedDate>='$fd'";
    }
    if ($toDate !== '') {
        $td = mysqli_real_escape_string($conn, $toDate);
        $dateSqlDist .= " AND CreatedDate<='$td'";
        $dateSqlStockCr .= " AND CreatedDate<='$td'";
        $dateSqlStockDr .= " AND CreatedDate<='$td'";
    }

    return array($dateSqlDist, $dateSqlStockCr, $dateSqlStockDr);
}

function mobileStockBuildChallanDateSql($fromDate, $toDate, $column = 'sp.SellDate')
{
    global $conn;

    $sql = '';
    if ($fromDate !== '') {
        $fd = mysqli_real_escape_string($conn, $fromDate);
        $sql .= " AND $column>='$fd'";
    }
    if ($toDate !== '') {
        $td = mysqli_real_escape_string($conn, $toDate);
        $sql .= " AND $column<='$td'";
    }

    return $sql;
}

/**
 * Resolve challan line to tbl_products.id.
 * sp.ProductId is only trusted when it matches the master product name (avoids
 * dispatch-row id collisions with unrelated product ids).
 */
function mobileStockResolveChallanProductIdExpr()
{
    return "CASE
        WHEN tp_by_id.id IS NOT NULL AND TRIM(tp_by_id.ProductName) = TRIM(sp.ProductName) THEN tp_by_id.id
        ELSE tp_by_name.id
    END";
}

/**
 * Serial challan lines count as outward only when the same serial exists in store inward.
 * Non-serial lines (blank / N/A) keep quantity-based matching.
 */
function mobileStockLoadStoreInwardSerialKeys($branchSqlDist, $productSqlDist)
{
    global $conn;

    $keys = array();
    $sql = "SELECT BranchId, ProductId, SerialNo
        FROM tbl_distibute_item_details
        WHERE TRIM(SerialNo) NOT IN ('', 'N/A') $branchSqlDist $productSqlDist";
    $res = $conn->query($sql);
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $serial = trim((string) $row['SerialNo']);
            if ($serial === '') {
                continue;
            }
            $key = (int) $row['BranchId'] . '_' . (int) $row['ProductId'] . '_' . $serial;
            $keys[$key] = true;
        }
    }

    return $keys;
}

function mobileStockChallanLineMatchesStoreInward($branchId, $productId, $serialNo, array $serialKeys)
{
    $serial = trim((string) $serialNo);
    if ($serial === '' || strcasecmp($serial, 'N/A') === 0) {
        return true;
    }

    $key = (int) $branchId . '_' . (int) $productId . '_' . $serial;

    return isset($serialKeys[$key]);
}

function mobileStockTransferTablesReady()
{
    global $conn;

    static $ready = null;
    if ($ready !== null) {
        return $ready;
    }

    $ready = false;
    $t1 = $conn->query("SHOW TABLES LIKE 'tbl_store_to_store_transfer'");
    $t2 = $conn->query("SHOW TABLES LIKE 'tbl_dispatch_to_store_transfer'");
    if ($t1 && $t1->num_rows > 0 && $t2 && $t2->num_rows > 0) {
        $ready = true;
    }

    return $ready;
}

function mobileStockApplyTransferDateSql($fromDate, $toDate, $column = 't.TransferDate')
{
    return mobileStockApplyReport2LineDateSql($fromDate, $toDate, $column);
}

function mobileStockAppendStoreToStoreOutward(&$lines, array $serialKeys, $branchSql, $productId, $fromDate, $toDate)
{
    global $conn;

    if (!mobileStockTransferTablesReady()) {
        return;
    }

    $dateSql = mobileStockApplyTransferDateSql($fromDate, $toDate);
    $productSql = $productId > 0 ? " AND td.ProductId='" . (int) $productId . "'" : '';

    $sql = "SELECT t.FromBranchId AS BranchId, td.ProductId, td.Qty, td.SerialNo
        FROM tbl_store_to_store_transfer_details td
        INNER JOIN tbl_store_to_store_transfer t ON t.id = td.TransferId
        WHERE 1=1 $branchSql $dateSql $productSql";
    $res = $conn->query($sql);
    if (!$res) {
        return;
    }

    while ($row = $res->fetch_assoc()) {
        $bid = (int) $row['BranchId'];
        $pid = (int) $row['ProductId'];
        $lineKey = $bid . '_' . $pid;
        if (!isset($lines[$lineKey])) {
            continue;
        }
        if (!mobileStockChallanLineMatchesStoreInward($bid, $pid, $row['SerialNo'], $serialKeys)) {
            continue;
        }
        $lines[$lineKey]['outward_qty'] += (float) $row['Qty'];
    }
}

function mobileStockGetStoreReportData($filters)
{
    global $conn;

    if (!is_array($filters)) {
        $filters = array('branch_id' => (int) $filters);
    }

    $branchId = isset($filters['branch_id']) ? (int) $filters['branch_id'] : 0;
    $productId = isset($filters['product_id']) ? (int) $filters['product_id'] : 0;
    $fromDate = isset($filters['from_date']) ? trim((string) $filters['from_date']) : '';
    $toDate = isset($filters['to_date']) ? trim((string) $filters['to_date']) : '';
    $allBranches = !empty($filters['all_branches']);

    list($dateSqlDist) = mobileStockBuildReport2DateSql($fromDate, $toDate);
    $dateSqlChallan = mobileStockBuildChallanDateSql($fromDate, $toDate);

    $storeName = '';
    if (!$allBranches && $branchId > 0) {
        $storeRow = getRecord("SELECT Name FROM tbl_branch WHERE id='$branchId'");
        $storeName = is_array($storeRow) ? ($storeRow['Name'] ?? '') : '';
    } elseif ($allBranches) {
        $storeName = 'All Stores';
    }

    $branchSqlDist = '';
    $branchSqlChallan = '';
    $branchSqlS2SOut = '';
    if (!$allBranches && $branchId > 0) {
        $branchSqlDist = " AND BranchId='$branchId'";
        $branchSqlChallan = " AND sp.BranchId='$branchId' AND ts.BranchId='$branchId'";
        $branchSqlS2SOut = " AND t.FromBranchId='$branchId'";
    }
    $productSqlDist = $productId > 0 ? " AND ProductId='$productId'" : '';
    $resolveProductId = mobileStockResolveChallanProductIdExpr();

    $lines = array();

    $sqlInward = "SELECT BranchId, ProductId, SUM(Qty) AS inward_qty
        FROM tbl_distibute_item_details
        WHERE 1=1 $branchSqlDist $dateSqlDist $productSqlDist
        GROUP BY BranchId, ProductId";
    $resIn = $conn->query($sqlInward);
    if ($resIn) {
        while ($row = $resIn->fetch_assoc()) {
            $bid = (int) $row['BranchId'];
            $pid = (int) $row['ProductId'];
            $key = $bid . '_' . $pid;
            if (!isset($lines[$key])) {
                $lines[$key] = array(
                    'BranchId' => $bid,
                    'ProductId' => $pid,
                    'inward_qty' => 0.0,
                    'outward_qty' => 0.0,
                );
            }
            $lines[$key]['inward_qty'] = (float) $row['inward_qty'];
        }
    }

    $serialKeys = mobileStockLoadStoreInwardSerialKeys($branchSqlDist, $productSqlDist);

    $sqlOutward = "SELECT sp.BranchId, sp.Qty, sp.SerialNo,
            ($resolveProductId) AS resolved_product_id
        FROM tbl_sell_products sp
        INNER JOIN tbl_sell ts ON ts.id = sp.SellId AND ts.Status = 1 AND ts.SellType = 'Challan'
        LEFT JOIN tbl_products tp_by_id ON tp_by_id.id = sp.ProductId
        LEFT JOIN tbl_products tp_by_name ON TRIM(tp_by_name.ProductName) = TRIM(sp.ProductName)
        WHERE sp.ProductName != ''
        AND ($resolveProductId) IS NOT NULL
        $branchSqlChallan
        $dateSqlChallan";
    $resOut = $conn->query($sqlOutward);
    if ($resOut) {
        while ($row = $resOut->fetch_assoc()) {
            $bid = (int) $row['BranchId'];
            $pid = (int) $row['resolved_product_id'];
            if ($productId > 0 && $pid !== $productId) {
                continue;
            }
            $lineKey = $bid . '_' . $pid;
            if (!isset($lines[$lineKey])) {
                continue;
            }
            if (!mobileStockChallanLineMatchesStoreInward($bid, $pid, $row['SerialNo'], $serialKeys)) {
                continue;
            }
            if (!isset($lines[$lineKey]['outward_qty'])) {
                $lines[$lineKey]['outward_qty'] = 0.0;
            }
            $lines[$lineKey]['outward_qty'] += (float) $row['Qty'];
        }
    }

    mobileStockAppendStoreToStoreOutward($lines, $serialKeys, $branchSqlS2SOut, $productId, $fromDate, $toDate);

    $productNames = array();
    $branchNames = array();
    if (!empty($lines)) {
        $productIds = array();
        $branchIds = array();
        foreach ($lines as $line) {
            $productIds[(int) $line['ProductId']] = true;
            $branchIds[(int) $line['BranchId']] = true;
        }
        if (!empty($productIds)) {
            $idList = implode(',', array_map('intval', array_keys($productIds)));
            $resProd = $conn->query("SELECT id, ProductName FROM tbl_products WHERE id IN ($idList) AND ProductName != ''");
            if ($resProd) {
                while ($prod = $resProd->fetch_assoc()) {
                    $productNames[(int) $prod['id']] = (string) $prod['ProductName'];
                }
            }
        }
        if ($allBranches && !empty($branchIds)) {
            $branchList = implode(',', array_map('intval', array_keys($branchIds)));
            $resBranch = $conn->query("SELECT id, Name FROM tbl_branch WHERE id IN ($branchList)");
            if ($resBranch) {
                while ($branch = $resBranch->fetch_assoc()) {
                    $branchNames[(int) $branch['id']] = (string) $branch['Name'];
                }
            }
        }
    }

    $rows = array();
    $totInward = 0.0;
    $totOutward = 0.0;
    $totBalance = 0.0;

    foreach ($lines as $line) {
        $pid = (int) $line['ProductId'];
        if (!isset($productNames[$pid])) {
            continue;
        }
        $inward = (float) $line['inward_qty'];
        $outward = (float) $line['outward_qty'];
        if ($inward <= 0) {
            continue;
        }
        $balance = $inward - $outward;
        $totInward += $inward;
        $totOutward += $outward;
        $totBalance += $balance;
        $bid = (int) $line['BranchId'];
        $rows[] = array(
            'ProductId' => $pid,
            'BranchId' => $bid,
            'Branch' => $allBranches ? ($branchNames[$bid] ?? '') : $storeName,
            'ProductName' => $productNames[$pid],
            'inward_qty' => $inward,
            'outward_qty' => $outward,
            'balance_qty' => $balance,
        );
    }

    usort($rows, function ($a, $b) {
        return strcasecmp($a['ProductName'], $b['ProductName']);
    });

    return array(
        'store_name' => $storeName,
        'all_branches' => $allBranches,
        'rows' => $rows,
        'tot_inward' => $totInward,
        'tot_outward' => $totOutward,
        'tot_balance' => $totBalance,
        'from_date' => $fromDate,
        'to_date' => $toDate,
    );
}

function mobileStockApplyReport2LineDateSql($fromDate, $toDate, $column = 'CreatedDate')
{
    global $conn;

    $sql = '';
    if ($fromDate !== '' && preg_match('/^\d{4}-\d{2}-\d{2}$/', $fromDate)) {
        $fromEsc = mysqli_real_escape_string($conn, $fromDate);
        $sql .= " AND $column >= '$fromEsc'";
    }
    if ($toDate !== '' && preg_match('/^\d{4}-\d{2}-\d{2}$/', $toDate)) {
        $toEsc = mysqli_real_escape_string($conn, $toDate);
        $sql .= " AND $column <= '$toEsc'";
    }

    return $sql;
}

function mobileStockGetCreditDetailRows($branchId, $productId, $fromDate = '', $toDate = '')
{
    global $conn;

    $branchId = (int) $branchId;
    $productId = (int) $productId;
    $dateSqlDist = mobileStockApplyReport2LineDateSql($fromDate, $toDate, 'd.CreatedDate');

    $rows = array();
    $sumQty = 0.0;

    $lineTypeSql = "'Assigned to store'";
    if (mobileStockTransferTablesReady()) {
        $lineTypeSql = "CASE
            WHEN EXISTS (
                SELECT 1 FROM tbl_store_to_store_transfer_details td
                INNER JOIN tbl_store_to_store_transfer t ON t.id = td.TransferId
                WHERE t.ToBranchId = d.BranchId AND td.ProductId = d.ProductId
                AND (
                    (TRIM(IFNULL(td.SerialNo, '')) NOT IN ('', 'N/A') AND TRIM(td.SerialNo) = TRIM(d.SerialNo))
                    OR (TRIM(IFNULL(td.SerialNo, '')) IN ('', 'N/A') AND TRIM(IFNULL(d.SerialNo, '')) IN ('', 'N/A') AND td.Qty = d.Qty)
                )
            ) THEN 'Store to store transfer (in)'
            WHEN EXISTS (
                SELECT 1 FROM tbl_dispatch_to_store_transfer_details td
                INNER JOIN tbl_dispatch_to_store_transfer t ON t.id = td.TransferId
                WHERE t.ToBranchId = d.BranchId AND td.ProductId = d.ProductId
                AND (
                    (TRIM(IFNULL(td.SerialNo, '')) NOT IN ('', 'N/A') AND TRIM(td.SerialNo) = TRIM(d.SerialNo))
                    OR (TRIM(IFNULL(td.SerialNo, '')) IN ('', 'N/A') AND TRIM(IFNULL(d.SerialNo, '')) IN ('', 'N/A') AND td.Qty = d.Qty)
                )
            ) THEN 'Dispatch to store transfer (in)'
            ELSE 'Assigned to store'
        END";
    }

    $sqlDist = "SELECT d.id, d.DistId, d.ProductName, d.Qty, d.SerialNo, d.ModelNo, d.Purity, d.CreatedDate, d.VehicalNo, d.VehicalDate,
        h.Narration AS HeaderNarration, ($lineTypeSql) AS LineType
        FROM tbl_distibute_item_details d
        LEFT JOIN tbl_distibute_items h ON h.id = d.DistId
        WHERE d.BranchId='$branchId' AND d.ProductId='$productId' $dateSqlDist
        ORDER BY d.CreatedDate DESC, d.id DESC";
    $res = $conn->query($sqlDist);
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $rows[] = $row;
            $sumQty += (float) $row['Qty'];
        }
    }

    return array('rows' => $rows, 'sum_qty' => $sumQty);
}

function mobileStockGetDebitDetailRows($branchId, $productId, $fromDate = '', $toDate = '')
{
    global $conn;

    $branchId = (int) $branchId;
    $productId = (int) $productId;
    $dateSql = mobileStockBuildChallanDateSql($fromDate, $toDate, 'sp.SellDate');

    $prodRow = getRecord("SELECT ProductName FROM tbl_products WHERE id='$productId' LIMIT 1");
    if (!is_array($prodRow) || trim((string) ($prodRow['ProductName'] ?? '')) === '') {
        return array('rows' => array(), 'sum_qty' => 0.0);
    }
    $resolveProductId = mobileStockResolveChallanProductIdExpr();
    $serialKeys = mobileStockLoadStoreInwardSerialKeys(" AND BranchId='$branchId'", " AND ProductId='$productId'");

    $sqlLines = "SELECT sp.id, sp.SellId AS DistId, sp.ProductName, sp.Qty, sp.SerialNo, sp.ModelNo, sp.Purity,
        sp.SellDate AS CreatedDate, ts.InvoiceNo, ts.CustName,
        'Delivery challan' AS SellType,
        CONCAT('Challan ', IFNULL(ts.InvoiceNo, sp.SellId), IF(ts.CustName IS NULL OR TRIM(ts.CustName) = '', '', CONCAT(' — ', ts.CustName))) AS Narration
        FROM tbl_sell_products sp
        INNER JOIN tbl_sell ts ON ts.id = sp.SellId AND ts.Status = 1 AND ts.SellType = 'Challan'
        LEFT JOIN tbl_products tp_by_id ON tp_by_id.id = sp.ProductId
        LEFT JOIN tbl_products tp_by_name ON TRIM(tp_by_name.ProductName) = TRIM(sp.ProductName)
        WHERE sp.BranchId='$branchId' AND ts.BranchId='$branchId'
        AND sp.ProductName != ''
        AND ($resolveProductId) = '$productId'
        $dateSql
        ORDER BY sp.SellDate DESC, sp.id DESC";

    $rows = array();
    $sumQty = 0.0;
    $res = $conn->query($sqlLines);
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            if (!mobileStockChallanLineMatchesStoreInward($branchId, $productId, $row['SerialNo'], $serialKeys)) {
                continue;
            }
            $rows[] = $row;
            $sumQty += (float) $row['Qty'];
        }
    }

    if (mobileStockTransferTablesReady()) {
        $dateSqlTransfer = mobileStockApplyTransferDateSql($fromDate, $toDate);
        $sqlS2S = "SELECT td.id, td.TransferId AS DistId, td.ProductName, td.Qty, td.SerialNo, td.ModelNo, td.Unit AS Purity,
            t.TransferDate AS CreatedDate, tb_to.Name AS ToStoreName, t.Narration AS TransferNarration,
            'Store to store transfer' AS SellType,
            CONCAT('Transfer to ', IFNULL(tb_to.Name, 'store'), IF(t.Narration IS NULL OR TRIM(t.Narration) = '', '', CONCAT(' — ', t.Narration))) AS Narration
            FROM tbl_store_to_store_transfer_details td
            INNER JOIN tbl_store_to_store_transfer t ON t.id = td.TransferId
            LEFT JOIN tbl_branch tb_to ON tb_to.id = t.ToBranchId
            WHERE t.FromBranchId='$branchId' AND td.ProductId='$productId' $dateSqlTransfer
            ORDER BY t.TransferDate DESC, td.id DESC";
        $resS2S = $conn->query($sqlS2S);
        if ($resS2S) {
            while ($row = $resS2S->fetch_assoc()) {
                if (!mobileStockChallanLineMatchesStoreInward($branchId, $productId, $row['SerialNo'], $serialKeys)) {
                    continue;
                }
                $rows[] = $row;
                $sumQty += (float) $row['Qty'];
            }
        }
    }

    usort($rows, function ($a, $b) {
        $da = strtotime((string) ($a['CreatedDate'] ?? ''));
        $db = strtotime((string) ($b['CreatedDate'] ?? ''));
        if ($da === $db) {
            return ((int) ($b['id'] ?? 0)) <=> ((int) ($a['id'] ?? 0));
        }

        return $db <=> $da;
    });

    return array('rows' => $rows, 'sum_qty' => $sumQty);
}

function mobileStockGetDispatchOfficersForBranch($roll, $userBranchId, $selectedBranchId)
{
    global $conn;

    $selectedBranchId = (int) $selectedBranchId;
    $userBranchId = (int) $userBranchId;
    if ((int) $roll === 1 || (int) $roll === 7) {
        if ($selectedBranchId < 1) {
            return array();
        }
        $bid = $selectedBranchId;
    } else {
        $bid = $userBranchId;
    }

    $sql = "SELECT id, Fname FROM tbl_users WHERE Status='1' AND Roll=26
        AND (BranchId='$bid' OR FIND_IN_SET('$bid', REPLACE(IFNULL(TRIM(MulBranchId),''),' ','')))
        ORDER BY Fname ASC";
    $list = getList($sql);

    return is_array($list) ? $list : array();
}

function mobileStockDispatchOfficerAllowed($roll, $userBranchId, $requestedBranchId, $requestedStoreExeId)
{
    $requestedBranchId = (int) $requestedBranchId;
    $requestedStoreExeId = (int) $requestedStoreExeId;
    if ($requestedBranchId < 1 || $requestedStoreExeId < 1) {
        return false;
    }
    if ((int) $roll === 1 || (int) $roll === 7) {
        return true;
    }

    return $requestedBranchId === (int) $userBranchId;
}

function mobileStockGetDispatchOfficerReportData($branchId, $storeExeId, $fromDate = '', $toDate = '')
{
    global $conn;

    $branchId = (int) $branchId;
    $storeExeId = (int) $storeExeId;
    $fromDate = trim((string) $fromDate);
    $toDate = trim((string) $toDate);

    $storeRow = getRecord("SELECT Name FROM tbl_branch WHERE id='$branchId'");
    $storeName = is_array($storeRow) ? ($storeRow['Name'] ?? '') : '';
    $officerRow = getRecord("SELECT Fname FROM tbl_users WHERE id='$storeExeId'");
    $officerName = trim($officerRow['Fname'] ?? '') !== '' ? $officerRow['Fname'] : ('Officer #' . $storeExeId);

    $rows = array();
    $totCredit = 0.0;
    $totDebit = 0.0;

    $sql = "SELECT ProductId, MAX(ProductName) AS ProductName
        FROM tbl_distibute_item_details2
        WHERE BranchId='$branchId' AND StoreExeId='$storeExeId'
        GROUP BY ProductId";
    $res = $conn->query($sql);
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $productId = (int) $row['ProductId'];
            $creditRow = getRecord("SELECT SUM(Qty) AS Qty FROM tbl_distibute_item_details2 WHERE BranchId='$branchId' AND ProductId='$productId' AND StoreExeId='$storeExeId'");
            $credit = isset($creditRow['Qty']) ? (float) $creditRow['Qty'] : 0;

            $sqlDebit = "SELECT SUM(Qty) AS Qty FROM tbl_stocks WHERE BranchId='$branchId' AND ProductId='$productId' AND CreatedBy='$storeExeId'";
            if ($fromDate !== '') {
                $fd = mysqli_real_escape_string($conn, $fromDate);
                $sqlDebit .= " AND CreatedDate>='$fd'";
            }
            if ($toDate !== '') {
                $td = mysqli_real_escape_string($conn, $toDate);
                $sqlDebit .= " AND CreatedDate<='$td'";
            }
            $debitRow = getRecord($sqlDebit);
            $debit = ($debitRow['Qty'] === '' || $debitRow['Qty'] === null) ? 0 : (float) $debitRow['Qty'];

            if ($credit <= 0 && $debit <= 0) {
                continue;
            }

            $totCredit += $credit;
            $totDebit += $debit;
            $rows[] = array(
                'ProductId' => $productId,
                'ProductName' => (string) ($row['ProductName'] ?? ''),
                'credit_qty' => $credit,
                'debit_qty' => $debit,
                'balance_qty' => $credit - $debit,
            );
        }
    }

    return array(
        'store_name' => $storeName,
        'officer_name' => $officerName,
        'rows' => $rows,
        'tot_credit' => $totCredit,
        'tot_debit' => $totDebit,
        'tot_balance' => $totCredit - $totDebit,
        'from_date' => $fromDate,
        'to_date' => $toDate,
    );
}
