<?php
/**
 * Shared logic for dispatch officer stock report (main page + tab view).
 */

function dispatch_officer_dispatch_users_for_branch($conn, $Roll, $userBranchId, $selectedBranchId)
{
    $selectedBranchId = (int) $selectedBranchId;
    $userBranchId = (int) $userBranchId;
    if ($Roll == 1 || $Roll == 7) {
        if ($selectedBranchId < 1) {
            return [];
        }
        $bid = $selectedBranchId;
    } else {
        $bid = $userBranchId;
    }
    $sql = "SELECT id, Fname FROM tbl_users WHERE Status='1' AND Roll=26
        AND (BranchId='$bid' OR FIND_IN_SET('$bid', REPLACE(IFNULL(TRIM(MulBranchId),''),' ','')))
        ORDER BY Fname ASC";
    $list = getList($sql);
    return is_array($list) ? $list : [];
}

function dispatch_officer_stock_allowed($Roll, $sessionBranchId, $requestedBranchId, $requestedStoreExeId)
{
    $requestedBranchId = (int) $requestedBranchId;
    $requestedStoreExeId = (int) $requestedStoreExeId;
    if ($requestedBranchId < 1 || $requestedStoreExeId < 1) {
        return false;
    }
    if ($Roll == 1 || $Roll == 7) {
        return true;
    }
    return $requestedBranchId === (int) $sessionBranchId;
}

/**
 * @return array{rows: list<array>, totCredit: float|int, totDebit: float|int}
 */
function dispatch_officer_stock_compute_rows($conn, $BranchId, $StoreExeId, $FromDate, $ToDate)
{
    $BranchId = (int) $BranchId;
    $StoreExeId = (int) $StoreExeId;
    $FromDate = trim((string) $FromDate);
    $ToDate = trim((string) $ToDate);

    $TotCreditQty = 0;
    $TotDebitQty = 0;
    $rows = [];

    $sql = "SELECT ProductId, ProductName FROM `tbl_distibute_item_details2` WHERE BranchId='$BranchId' AND StoreExeId='$StoreExeId' GROUP BY ProductId";
    $res = $conn->query($sql);
    if (!$res) {
        return ['rows' => [], 'totCredit' => 0, 'totDebit' => 0];
    }

    while ($row = $res->fetch_assoc()) {
        $productId = (int) $row['ProductId'];
        /* Credit: all qty assigned to this dispatch officer at this store (no date filter). */
        $sql2 = "SELECT SUM(Qty) As Qty FROM `tbl_distibute_item_details2` WHERE BranchId='$BranchId' AND ProductId='$productId' AND StoreExeId='$StoreExeId'";
        $row2 = getRecord($sql2);
        $credit = isset($row2['Qty']) ? (float) $row2['Qty'] : 0;

        /* Debit: tbl_stocks for this officer in the selected date range only. */
        $sql3 = "SELECT SUM(Qty) As Qty FROM `tbl_stocks` WHERE BranchId='$BranchId' AND ProductId='$productId' AND CreatedBy='$StoreExeId'";
        if ($FromDate !== '') {
            $fd = mysqli_real_escape_string($conn, $FromDate);
            $sql3 .= " AND CreatedDate>='$fd'";
        }
        if ($ToDate !== '') {
            $td = mysqli_real_escape_string($conn, $ToDate);
            $sql3 .= " AND CreatedDate<='$td'";
        }
        $row3 = getRecord($sql3);
        $debit = ($row3['Qty'] === '' || $row3['Qty'] === null) ? 0 : (float) $row3['Qty'];

        if ($credit <= 0 && $debit <= 0) {
            continue;
        }

        $TotCreditQty += $credit;
        $TotDebitQty += $debit;

        $rows[] = [
            'ProductId' => $productId,
            'ProductName' => $row['ProductName'],
            'CreditQty' => $credit,
            'DebitQty' => $debit,
            'BalanceQty' => $credit - $debit,
        ];
    }

    return [
        'rows' => $rows,
        'totCredit' => $TotCreditQty,
        'totDebit' => $TotDebitQty,
    ];
}
