<?php

/**
 * Required stock lines for one done beneficiary (BOM / quotation), same rules as required-stock page.
 *
 * @return list<array{ProductId:int,ProductName:string,ReqQty:float}>
 */
function upb_fetch_required_lines_for_customer($conn, $custId)
{
    $custId = (int) $custId;
    if ($custId <= 0) {
        return [];
    }

    $lines = getList(
        "SELECT cps.ProdId AS ProductId,
                MAX(COALESCE(
                    NULLIF(TRIM(cps.ProdName), ''),
                    NULLIF(TRIM(tp.ProductName), ''),
                    CONCAT('Product #', cps.ProdId)
                )) AS ProductName,
                SUM(COALESCE(CAST(NULLIF(TRIM(cps.Qty), '') AS DECIMAL(12,2)), 0)) AS ReqQty
         FROM tbl_cust_product_specification cps
         LEFT JOIN tbl_products tp ON tp.id = cps.ProdId
         WHERE cps.CustId = '" . $custId . "'
         GROUP BY cps.ProdId
         HAVING SUM(COALESCE(CAST(NULLIF(TRIM(cps.Qty), '') AS DECIMAL(12,2)), 0)) > 0
         ORDER BY MAX(COALESCE(
                    NULLIF(TRIM(cps.ProdName), ''),
                    NULLIF(TRIM(tp.ProductName), ''),
                    CONCAT('Product #', cps.ProdId)
                )) ASC"
    );
    if (!is_array($lines)) {
        $lines = [];
    }
    if (count($lines) === 0) {
        $lines = getList(
            "SELECT qop.ProductId, qop.ProductName, SUM(COALESCE(qop.Qty,0)) AS ReqQty
             FROM tbl_quotation_order_products qop
             INNER JOIN tbl_quotation q ON q.id = qop.SellId AND q.CustId = '" . $custId . "'
             GROUP BY qop.ProductId, qop.ProductName
             ORDER BY qop.ProductName ASC"
        );
        if (!is_array($lines)) {
            $lines = [];
        }
    }

    return $lines;
}

/**
 * Aggregate required stock across multiple customers (sum qty per product).
 *
 * @param int[] $custIds
 * @return list<array{ProductId:int,ProductName:string,ReqQty:float}>
 */
function upb_fetch_combined_required_lines($conn, array $custIds)
{
    $byProduct = [];
    foreach ($custIds as $custId) {
        $custId = (int) $custId;
        if ($custId <= 0) {
            continue;
        }
        foreach (upb_fetch_required_lines_for_customer($conn, $custId) as $ln) {
            $pid = (int) ($ln['ProductId'] ?? 0);
            $name = (string) ($ln['ProductName'] ?? '');
            $qty = (float) ($ln['ReqQty'] ?? 0);
            if ($qty <= 0) {
                continue;
            }
            if (!isset($byProduct[$pid])) {
                $byProduct[$pid] = [
                    'ProductId' => $pid,
                    'ProductName' => $name,
                    'ReqQty' => 0.0,
                ];
            }
            $byProduct[$pid]['ReqQty'] += $qty;
            if ($name !== '' && ($byProduct[$pid]['ProductName'] === '' || strpos($byProduct[$pid]['ProductName'], 'Product #') === 0)) {
                $byProduct[$pid]['ProductName'] = $name;
            }
        }
    }

    $out = array_values($byProduct);
    usort($out, function ($a, $b) {
        return strcasecmp((string) $a['ProductName'], (string) $b['ProductName']);
    });

    return $out;
}

/**
 * Net qty from stock ledger for bulk items (ProdType 0) at optional branch.
 */
function upb_stock_net($conn, $productId, $branchId = null)
{
    $productId = (int) $productId;
    if ($productId <= 0) {
        return 0;
    }
    $where = "Status=1 AND ProductId='" . $productId . "' AND ProdType=0";
    if ($branchId !== null && $branchId !== '' && $branchId !== 'all') {
        $bid = (int) $branchId;
        $where .= " AND BranchId='" . $bid . "'";
    }
    $row = getRecord(
        "SELECT SUM(CASE WHEN CrDr='cr' THEN Qty ELSE 0 END) AS crq,
                SUM(CASE WHEN CrDr='dr' THEN Qty ELSE 0 END) AS drq
         FROM tbl_stocks WHERE " . $where
    );
    $cr = isset($row['crq']) ? (float) $row['crq'] : 0;
    $dr = isset($row['drq']) ? (float) $row['drq'] : 0;
    return (int) max(0, round($cr - $dr));
}

/**
 * Validate customer ids belong to done-beneficiary stock report list.
 *
 * @param int[] $custIds
 * @return int[]
 */
function upb_validate_stock_report_customer_ids($conn, array $custIds)
{
    $ids = [];
    foreach ($custIds as $id) {
        $id = (int) $id;
        if ($id > 0) {
            $ids[$id] = $id;
        }
    }
    if (count($ids) === 0) {
        return [];
    }
    $in = implode(',', array_map('intval', array_values($ids)));
    $sql = "SELECT tp.id
            FROM tbl_users tp
            WHERE tp.id IN (" . $in . ")
            AND tp.SurveyMatch = 1 AND tp.ProjectType = 1 AND tp.UnderProdStatus = '1'
            AND NOT EXISTS (
                SELECT 1 FROM tbl_sell ts
                WHERE ts.CustId = tp.id AND ts.SellType = 'Challan' AND ts.Status = 1
            )";
    $valid = [];
    $res = $conn->query($sql);
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $valid[] = (int) $row['id'];
        }
    }
    return $valid;
}

/**
 * Per-store net qty for a product (bulk / ProdType 0) from stock ledger only.
 */
function upb_stock_by_branch($conn, $productId)
{
    $productId = (int) $productId;
    if ($productId <= 0) {
        return [];
    }
    $sql = "SELECT ts.BranchId,
                   COALESCE(
                       NULLIF(TRIM(MAX(tb.Name)), ''),
                       IF(ts.BranchId = 0, 'Main / central stock (ledger, not assigned to a store)',
                          CONCAT('Branch #', ts.BranchId, ' (no name in master)'))
                   ) AS StoreName,
                   SUM(CASE WHEN ts.CrDr='cr' THEN ts.Qty ELSE 0 END) -
                   SUM(CASE WHEN ts.CrDr='dr' THEN ts.Qty ELSE 0 END) AS AvailQty
            FROM tbl_stocks ts
            LEFT JOIN tbl_branch tb ON tb.id = ts.BranchId
            WHERE ts.Status=1 AND ts.ProductId='" . $productId . "' AND ts.ProdType=0
            GROUP BY ts.BranchId
            HAVING AvailQty > 0
            ORDER BY AvailQty DESC";
    $list = getList($sql);
    return is_array($list) ? $list : [];
}

/**
 * Where bulk qty sits: store net, dispatch officer lines, then ledger fallback.
 *
 * @return list<array{StoreName:string,AvailQty:float|int,row_kind:string,branch_id:int,store_exe_id:int}>
 */
function upb_available_locations($conn, $productId)
{
    $productId = (int) $productId;
    if ($productId <= 0) {
        return [];
    }

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
    $d2JoinOpen = ($hasDispatchTransferTbl && $hasDetail2IdCol)
        ? "LEFT JOIN (SELECT DISTINCT Detail2Id FROM tbl_dispatch_to_store_transfer_details WHERE Detail2Id IS NOT NULL) td_open ON td_open.Detail2Id = d2.id"
        : '';
    $d2WhereOpen = ($hasDispatchTransferTbl && $hasDetail2IdCol) ? 'AND td_open.Detail2Id IS NULL' : '';

    $out = [];

    $sqlStore = "SELECT d.BranchId, MAX(b.Name) AS BranchName,
        (COALESCE(SUM(d.Qty),0) - COALESCE((SELECT SUM(x.Qty) FROM tbl_distibute_item_details2 x
            WHERE x.BranchId = d.BranchId AND x.ProductId = d.ProductId AND x.ProdType = 0), 0)) AS AvailQty
        FROM tbl_distibute_item_details d
        INNER JOIN tbl_distibute_items h ON h.id = d.DistId AND h.Status = 1
        INNER JOIN tbl_branch b ON b.id = d.BranchId
        WHERE d.ProdType = 0 AND d.ProductId='" . $productId . "'
        GROUP BY d.BranchId
        HAVING AvailQty > 0.0001
        ORDER BY MAX(b.Name)";
    foreach (getList($sqlStore) as $r) {
        $bn = isset($r['BranchName']) ? trim((string) $r['BranchName']) : '';
        if ($bn === '') {
            continue;
        }
        $out[] = [
            'StoreName' => 'Store (balance): ' . $bn,
            'AvailQty' => $r['AvailQty'],
            'row_kind' => 'store',
            'branch_id' => (int) ($r['BranchId'] ?? 0),
            'store_exe_id' => 0,
        ];
    }

    $sqlDisp = "SELECT d2.StoreExeId, d2.BranchId,
        COALESCE(u.Fname, CONCAT('User #', d2.StoreExeId)) AS officer_name,
        COALESCE(NULLIF(TRIM(b.Name), ''), 'branch not set') AS assign_branch_name,
        SUM(d2.Qty) AS AvailQty
        FROM tbl_distibute_item_details2 d2
        INNER JOIN tbl_distibute_items2 h ON h.id = d2.DistId AND h.Status = 1
        LEFT JOIN tbl_users u ON u.id = d2.StoreExeId
        LEFT JOIN tbl_branch b ON b.id = d2.BranchId
        " . $d2JoinOpen . "
        WHERE d2.ProdType = 0 AND d2.StoreExeId > 0 AND d2.ProductId='" . $productId . "' " . $d2WhereOpen . "
        GROUP BY d2.StoreExeId, d2.BranchId, u.Fname, b.Name
        HAVING SUM(d2.Qty) > 0.0001
        ORDER BY officer_name, assign_branch_name";
    foreach (getList($sqlDisp) as $r) {
        $on = isset($r['officer_name']) ? trim((string) $r['officer_name']) : '';
        $br = isset($r['assign_branch_name']) ? trim((string) $r['assign_branch_name']) : '';
        if ($br === '') {
            $br = 'branch not set';
        }
        $out[] = [
            'StoreName' => 'Dispatch officer: ' . $on . ' (store: ' . $br . ')',
            'AvailQty' => $r['AvailQty'],
            'row_kind' => 'dispatch',
            'branch_id' => (int) ($r['BranchId'] ?? 0),
            'store_exe_id' => (int) ($r['StoreExeId'] ?? 0),
        ];
    }

    if (count($out) > 0) {
        return $out;
    }

    $ledger = upb_stock_by_branch($conn, $productId);
    if (!is_array($ledger)) {
        return [];
    }
    $wrapped = [];
    foreach ($ledger as $row) {
        $wrapped[] = array_merge($row, [
            'row_kind' => 'ledger',
            'branch_id' => isset($row['BranchId']) ? (int) $row['BranchId'] : 0,
            'store_exe_id' => 0,
        ]);
    }
    return $wrapped;
}

/**
 * Fetch customer rows for stock report / combined view.
 *
 * @param int[] $custIds
 * @return list<array>
 */
function upb_fetch_stock_report_customers($conn, array $custIds)
{
    $custIds = upb_validate_stock_report_customer_ids($conn, $custIds);
    if (count($custIds) === 0) {
        return [];
    }
    $in = implode(',', array_map('intval', $custIds));
    $list = getList(
        "SELECT id, BeneficiaryId, Fname, Phone, Address
         FROM tbl_users
         WHERE id IN (" . $in . ")
         ORDER BY Fname ASC"
    );
    return is_array($list) ? $list : [];
}
