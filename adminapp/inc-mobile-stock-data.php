<?php

/**
 * Available stock: store balance (distribute details − assign to dispatch) and
 * dispatch officer balance (assigned − issued to customer via tbl_stocks).
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

    list($dateSqlDist, $dateSqlStockCr, $dateSqlStockDr) = mobileStockBuildReport2DateSql($fromDate, $toDate);

    $storeName = '';
    if (!$allBranches && $branchId > 0) {
        $storeRow = getRecord("SELECT Name FROM tbl_branch WHERE id='$branchId'");
        $storeName = is_array($storeRow) ? ($storeRow['Name'] ?? '') : '';
    } elseif ($allBranches) {
        $storeName = 'All Stores';
    }

    $rows = array();
    $totInward = 0.0;
    $totOutward = 0.0;
    $totBalance = 0.0;

    $sql = "SELECT p.ProductId, p.BranchId,
            MAX(tb.Name) AS Branch,
            MAX(tp.ProductName) AS Product_Name,
            (
                COALESCE((
                    SELECT SUM(Qty) FROM tbl_distibute_item_details
                    WHERE BranchId = p.BranchId AND ProductId = p.ProductId $dateSqlDist
                ), 0)
                + COALESCE((
                    SELECT SUM(Qty) FROM tbl_stocks
                    WHERE Status = 1 AND BranchId = p.BranchId AND ProductId = p.ProductId AND CrDr = 'cr' $dateSqlStockCr
                ), 0)
            ) AS inward_qty,
            COALESCE((
                SELECT SUM(Qty) FROM tbl_stocks
                WHERE Status = 1 AND BranchId = p.BranchId AND ProductId = p.ProductId AND CrDr = 'dr' $dateSqlStockDr
            ), 0) AS outward_qty
        FROM (
            SELECT ProductId, BranchId FROM tbl_distibute_item_details WHERE 1=1 $dateSqlDist
            UNION
            SELECT ProductId, BranchId FROM tbl_stocks WHERE Status = 1 AND CrDr = 'cr' $dateSqlStockCr
            UNION
            SELECT ProductId, BranchId FROM tbl_stocks WHERE Status = 1 AND CrDr = 'dr' $dateSqlStockDr
        ) p
        INNER JOIN tbl_products tp ON p.ProductId = tp.id
        LEFT JOIN tbl_branch tb ON p.BranchId = tb.id
        WHERE tp.ProductName != ''";

    if (!$allBranches && $branchId > 0) {
        $sql .= " AND p.BranchId='$branchId'";
    }
    if ($productId > 0) {
        $sql .= " AND p.ProductId='$productId'";
    }

    $sql .= " GROUP BY p.ProductId, p.BranchId
        HAVING inward_qty > 0 OR outward_qty > 0
        ORDER BY Product_Name ASC";

    $res = $conn->query($sql);
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $inward = (float) $row['inward_qty'];
            $outward = (float) $row['outward_qty'];
            $balance = $inward - $outward;
            $totInward += $inward;
            $totOutward += $outward;
            $totBalance += $balance;
            $rows[] = array(
                'ProductId' => (int) $row['ProductId'],
                'BranchId' => (int) $row['BranchId'],
                'Branch' => (string) ($row['Branch'] ?? ''),
                'ProductName' => (string) ($row['Product_Name'] ?? ''),
                'inward_qty' => $inward,
                'outward_qty' => $outward,
                'balance_qty' => $balance,
            );
        }
    }

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
    $dateSqlStock = mobileStockApplyReport2LineDateSql($fromDate, $toDate, 's.CreatedDate');

    $rows = array();
    $sumQty = 0.0;

    $sqlDist = "SELECT d.id, d.DistId, d.ProductName, d.Qty, d.SerialNo, d.ModelNo, d.Purity, d.CreatedDate, d.VehicalNo, d.VehicalDate,
        h.Narration AS HeaderNarration, 'Store allotment' AS LineType
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

    $sqlStock = "SELECT s.id, s.SellId AS DistId, s.ProductName, s.Qty, s.SerialNo, s.ModelNo, '' AS Purity, s.CreatedDate, s.VehicalNo, s.VehicalDate,
        s.Narration AS HeaderNarration, CONCAT('Purchase / ', COALESCE(s.SellType, '')) AS LineType
        FROM tbl_stocks s
        WHERE s.Status=1 AND s.BranchId='$branchId' AND s.ProductId='$productId' AND s.CrDr='cr' $dateSqlStock
        ORDER BY s.CreatedDate DESC, s.id DESC";
    $res = $conn->query($sqlStock);
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
    $dateSql = mobileStockApplyReport2LineDateSql($fromDate, $toDate, 'CreatedDate');

    $sqlLines = "SELECT id, Qty, SerialNo, ModelNo, CreatedDate, Narration, VehicalNo, VehicalDate, CrDr, SellType, ProductName, SellId
        FROM tbl_stocks
        WHERE Status=1 AND BranchId='$branchId' AND ProductId='$productId' AND CrDr='dr' $dateSql
        ORDER BY CreatedDate DESC, id DESC";

    $rows = array();
    $sumQty = 0.0;
    $res = $conn->query($sqlLines);
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $rows[] = $row;
            $sumQty += (float) $row['Qty'];
        }
    }

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
