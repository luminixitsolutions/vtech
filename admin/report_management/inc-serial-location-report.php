<?php
/**
 * Helpers for serial-wise location report (store / dispatch created / dispatch officer / customer).
 */

function serial_report_transfer_meta($conn)
{
    $hasTbl = false;
    $hasCol = false;
    $t1 = $conn->query("SHOW TABLES LIKE 'tbl_dispatch_to_store_transfer_details'");
    if ($t1 && $t1->num_rows > 0) {
        $hasTbl = true;
        $c = $conn->query("SHOW COLUMNS FROM tbl_dispatch_to_store_transfer_details LIKE 'Detail2Id'");
        if ($c && $c->num_rows > 0) {
            $hasCol = true;
        }
    }
    return array('has_transfer_tbl' => $hasTbl, 'has_detail2_col' => $hasCol);
}

function serial_report_open_transfer_join_sql($hasTbl, $hasCol)
{
    if ($hasTbl && $hasCol) {
        return "LEFT JOIN (SELECT DISTINCT Detail2Id FROM tbl_dispatch_to_store_transfer_details WHERE Detail2Id IS NOT NULL) td_open ON td_open.Detail2Id = d2.id";
    }
    return '';
}

function serial_report_open_transfer_where_sql($hasTbl, $hasCol)
{
    if ($hasTbl && $hasCol) {
        return 'AND td_open.Detail2Id IS NULL';
    }
    return '';
}

/**
 * @return array<int, array<string, mixed>>
 */
function serial_report_fetch_rows($conn, $branchId, $serialQ, $locationFilter)
{
    $meta = serial_report_transfer_meta($conn);
    $d2Join = serial_report_open_transfer_join_sql($meta['has_transfer_tbl'], $meta['has_detail2_col']);
    $d2WhereOpen = serial_report_open_transfer_where_sql($meta['has_transfer_tbl'], $meta['has_detail2_col']);

    $branchId = (int) $branchId;
    $serialQ = trim((string) $serialQ);
    $escQ = $conn->real_escape_string($serialQ);

    $branchFilterDetails = $branchId > 0 ? " AND d.BranchId='" . $branchId . "' " : '';
    $branchFilterDetails2 = $branchId > 0 ? " AND d2.BranchId='" . $branchId . "' " : '';
    $serialLikeDetails = $serialQ !== '' ? " AND d.SerialNo LIKE '%$escQ%' " : '';
    $serialLikeDetails2 = $serialQ !== '' ? " AND d2.SerialNo LIKE '%$escQ%' " : '';
    $serialLikeStocks = $serialQ !== '' ? " AND st.SerialNo LIKE '%$escQ%' " : '';
    $serialLikeUnion = $serialQ !== '' ? " AND TRIM(u.serial_no) LIKE '%$escQ%' " : '';

    $reservedJoin = '';
    $reservedSelect = 'NULL AS reserved_line_id, NULL AS reserved_transfer_id, NULL AS reserved_to_store';
    if ($meta['has_transfer_tbl'] && $meta['has_detail2_col']) {
        $reservedJoin = "LEFT JOIN (
            SELECT td.Detail2Id AS line_id, t.id AS transfer_id, tb.Name AS to_store_name, d2r.SerialNo AS res_serial
            FROM tbl_dispatch_to_store_transfer_details td
            INNER JOIN tbl_dispatch_to_store_transfer t ON t.id = td.TransferId
            INNER JOIN tbl_distibute_item_details2 d2r ON d2r.id = td.Detail2Id
            LEFT JOIN tbl_branch tb ON tb.id = t.ToBranchId
        ) res ON res.res_serial = u.serial_no";
        $reservedSelect = 'res.line_id AS reserved_line_id, res.transfer_id AS reserved_transfer_id, res.to_store_name AS reserved_to_store';
    }

    $sql = "SELECT u.serial_no,
        off_ln.id AS officer_line_id,
        off_ln.StoreExeId,
        off_ln.BranchId AS officer_branch_id,
        off_ln.ProductName AS officer_product,
        off_ln.ModelNo AS officer_model,
        off_ln.CreatedDate AS officer_line_date,
        off_dist.id AS officer_batch_id,
        off_dist.Narration AS officer_batch_narration,
        off_dist.CreatedDate AS officer_batch_date,
        u_off.Fname AS dispatch_officer_name,
        b_off.Name AS officer_branch_name,
        st_ln.id AS store_line_id,
        st_ln.BranchId AS store_branch_id,
        st_ln.ProductName AS store_product,
        st_ln.ModelNo AS store_model,
        st_ln.CreatedDate AS store_line_date,
        st_dist.id AS store_dispatch_id,
        st_dist.Narration AS store_dispatch_narration,
        st_dist.CreatedDate AS store_dispatch_date,
        b_st.Name AS store_branch_name,
        cust.id AS customer_stock_id,
        cust.ProductName AS customer_product,
        cust.ModelNo AS customer_model,
        cust.CreatedDate AS customer_dispatch_date,
        cust.Narration AS customer_narration,
        cust.CreatedBy AS customer_officer_id,
        cust.SellId AS customer_sell_id,
        u_cust.Fname AS customer_officer_name,
        tu.BeneficiaryId AS customer_beneficiary,
        tu.Fname AS customer_name,
        sell.CustName AS sell_customer_name,
        $reservedSelect
    FROM (
        SELECT DISTINCT TRIM(SerialNo) AS serial_no FROM (
            SELECT SerialNo FROM tbl_distibute_item_details d
            WHERE d.ProdType IN (1,2) AND d.SerialNo IS NOT NULL AND TRIM(d.SerialNo) <> '' AND d.SerialNo <> 'N/A'
            $branchFilterDetails $serialLikeDetails
            UNION
            SELECT SerialNo FROM tbl_distibute_item_details2 d2
            WHERE d2.ProdType IN (1,2) AND d2.SerialNo IS NOT NULL AND TRIM(d2.SerialNo) <> '' AND d2.SerialNo <> 'N/A'
            $branchFilterDetails2 $serialLikeDetails2
            UNION
            SELECT SerialNo FROM tbl_stocks st
            WHERE st.ProdType = 1 AND st.CrDr = 'dr' AND st.SerialNo IS NOT NULL AND TRIM(st.SerialNo) <> '' AND st.SerialNo <> 'N/A'
            " . ($branchId > 0 ? " AND st.BranchId='" . $branchId . "' " : '') . "
            $serialLikeStocks
        ) all_serials
    ) u
    LEFT JOIN (
        SELECT d2.* FROM tbl_distibute_item_details2 d2
        $d2Join
        INNER JOIN (
            SELECT SerialNo, MAX(id) AS max_id FROM tbl_distibute_item_details2
            WHERE ProdType IN (1,2) AND StoreExeId > 0 AND SerialNo IS NOT NULL AND TRIM(SerialNo) <> ''
            " . ($branchId > 0 ? " AND BranchId='" . $branchId . "' " : '') . "
            GROUP BY SerialNo
        ) om ON om.max_id = d2.id
        WHERE 1=1 $d2WhereOpen
        AND NOT EXISTS (
            SELECT 1 FROM tbl_stocks sx
            WHERE sx.CrDr='dr' AND sx.ProdType=1 AND sx.SerialNo = d2.SerialNo
        )
    ) off_ln ON off_ln.SerialNo = u.serial_no $serialJoinOff
    LEFT JOIN tbl_distibute_items2 off_dist ON off_dist.id = off_ln.DistId
    LEFT JOIN tbl_users u_off ON u_off.id = off_ln.StoreExeId
    LEFT JOIN tbl_branch b_off ON b_off.id = off_ln.BranchId
    LEFT JOIN (
        SELECT d.* FROM tbl_distibute_item_details d
        INNER JOIN (
            SELECT SerialNo, MAX(id) AS max_id FROM tbl_distibute_item_details
            WHERE ProdType IN (1,2) AND SerialNo IS NOT NULL AND TRIM(SerialNo) <> ''
            " . ($branchId > 0 ? " AND BranchId='" . $branchId . "' " : '') . "
            GROUP BY SerialNo
        ) sm ON sm.max_id = d.id
        WHERE NOT EXISTS (
            SELECT 1 FROM tbl_distibute_item_details2 x
            WHERE x.BranchId = d.BranchId AND x.ProductId = d.ProductId
            AND x.SerialNo = d.SerialNo AND x.ProdType = d.ProdType
        )
        AND NOT EXISTS (
            SELECT 1 FROM tbl_stocks sx
            WHERE sx.CrDr='dr' AND sx.ProdType=1 AND sx.SerialNo = d.SerialNo
        )
    ) st_ln ON st_ln.SerialNo = u.serial_no
    LEFT JOIN tbl_distibute_items st_dist ON st_dist.id = st_ln.DistId
    LEFT JOIN tbl_branch b_st ON b_st.id = st_ln.BranchId
    LEFT JOIN (
        SELECT st.* FROM tbl_stocks st
        INNER JOIN (
            SELECT SerialNo, MAX(id) AS max_id FROM tbl_stocks
            WHERE ProdType=1 AND CrDr='dr' AND SerialNo IS NOT NULL AND TRIM(SerialNo) <> ''
            " . ($branchId > 0 ? " AND BranchId='" . $branchId . "' " : '') . "
            GROUP BY SerialNo
        ) cm ON cm.max_id = st.id
    ) cust ON cust.SerialNo = u.serial_no
    LEFT JOIN tbl_users u_cust ON u_cust.id = cust.CreatedBy
    LEFT JOIN tbl_sell sell ON sell.id = cust.SellId
    LEFT JOIN tbl_users tu ON tu.id = sell.CustId
    $reservedJoin
    WHERE 1=1 $serialLikeUnion
    ORDER BY u.serial_no ASC";

    $rows = array();
    $rs = $conn->query($sql);
    if (!$rs) {
        return $rows;
    }

    while ($r = $rs->fetch_assoc()) {
        $loc = serial_report_resolve_location($r);
        $r['current_location'] = $loc['label'];
        $r['location_key'] = $loc['key'];
        $r['product_name'] = $loc['product'];
        $r['model_no'] = $loc['model'];
        $r['branch_display'] = $loc['branch'];
        $r['detail_note'] = $loc['note'];

        if ($locationFilter !== '' && $locationFilter !== 'all' && $r['location_key'] !== $locationFilter) {
            continue;
        }
        $rows[] = $r;
    }

    return $rows;
}

/**
 * @param array<string, mixed> $r
 * @return array{key: string, label: string, product: string, model: string, branch: string, note: string}
 */
function serial_report_resolve_location($r)
{
    $product = '';
    $model = '';
    $branch = '';
    $note = '';

    if (!empty($r['customer_stock_id'])) {
        $product = (string) ($r['customer_product'] ?? $r['officer_product'] ?? $r['store_product'] ?? '');
        $model = (string) ($r['customer_model'] ?? $r['officer_model'] ?? $r['store_model'] ?? '');
        $branch = (string) ($r['officer_branch_name'] ?? $r['store_branch_name'] ?? '');
        $ben = trim((string) ($r['customer_beneficiary'] ?? ''));
        $name = trim((string) ($r['customer_name'] ?? $r['sell_customer_name'] ?? ''));
        $note = 'Dispatched to customer';
        if (!empty($r['customer_sell_id'])) {
            $note .= ' | Challan #' . (int) $r['customer_sell_id'];
        }
        if ($ben !== '') {
            $note .= ' — Beneficiary: ' . $ben;
        }
        if ($name !== '') {
            $note .= ' (' . $name . ')';
        }
        if (!empty($r['customer_officer_name'])) {
            $note .= ' | Officer: ' . $r['customer_officer_name'];
        }
        return array(
            'key' => 'customer',
            'label' => 'Customer (dispatched)',
            'product' => $product,
            'model' => $model,
            'branch' => $branch,
            'note' => $note,
        );
    }

    if (!empty($r['reserved_line_id'])) {
        $product = (string) ($r['officer_product'] ?? '');
        $model = (string) ($r['officer_model'] ?? '');
        $branch = (string) ($r['reserved_to_store'] ?? $r['officer_branch_name'] ?? '');
        $note = 'Transfer #' . (int) $r['reserved_transfer_id'] . ' pending to store';
        if (!empty($r['dispatch_officer_name'])) {
            $note .= ' | Officer: ' . $r['dispatch_officer_name'];
        }
        return array(
            'key' => 'reserved',
            'label' => 'Reserved (transfer to store)',
            'product' => $product,
            'model' => $model,
            'branch' => $branch,
            'note' => $note,
        );
    }

    if (!empty($r['officer_line_id'])) {
        $product = (string) ($r['officer_product'] ?? '');
        $model = (string) ($r['officer_model'] ?? '');
        $branch = (string) ($r['officer_branch_name'] ?? '');
        $note = '';
        if (!empty($r['dispatch_officer_name'])) {
            $note = 'With officer: ' . $r['dispatch_officer_name'];
        }
        if (!empty($r['officer_batch_id'])) {
            $note .= ($note !== '' ? ' | ' : '') . 'Assign batch #' . (int) $r['officer_batch_id'];
        }
        return array(
            'key' => 'dispatch_officer',
            'label' => 'Dispatch Officer',
            'product' => $product,
            'model' => $model,
            'branch' => $branch,
            'note' => $note,
        );
    }

    if (!empty($r['store_line_id'])) {
        $product = (string) ($r['store_product'] ?? '');
        $model = (string) ($r['store_model'] ?? '');
        $branch = (string) ($r['store_branch_name'] ?? '');
        $note = 'At store, not yet assigned to dispatch officer';
        if (!empty($r['store_dispatch_id'])) {
            $note .= ' | Store inward #' . (int) $r['store_dispatch_id'];
        }
        return array(
            'key' => 'store',
            'label' => 'Store',
            'product' => $product,
            'model' => $model,
            'branch' => $branch,
            'note' => $note,
        );
    }

    return array(
        'key' => 'unknown',
        'label' => 'Not tracked',
        'product' => $product,
        'model' => $model,
        'branch' => $branch,
        'note' => 'Serial found in history only',
    );
}

function serial_report_format_date($dt)
{
    if ($dt === null || $dt === '' || $dt === '0000-00-00 00:00:00') {
        return '—';
    }
    $ts = strtotime((string) $dt);
    if ($ts === false) {
        return (string) $dt;
    }
    return date('d/m/Y H:i', $ts);
}

/**
 * Chronological movement history for a serial (all matching rows).
 *
 * @return array<int, array<string, mixed>>
 */
function serial_report_fetch_track_records($conn, $serialQ, $branchId = 0)
{
    $serialQ = trim((string) $serialQ);
    if ($serialQ === '') {
        return array();
    }

    $branchId = (int) $branchId;
    $escQ = $conn->real_escape_string($serialQ);
    $like = " AND TRIM(d.SerialNo) LIKE '%$escQ%' ";
    $like2 = " AND TRIM(d2.SerialNo) LIKE '%$escQ%' ";
    $likeSt = " AND TRIM(st.SerialNo) LIKE '%$escQ%' ";
    $likeSp = " AND TRIM(sp.SerialNo) LIKE '%$escQ%' ";
    $branchD = $branchId > 0 ? " AND d.BranchId='" . $branchId . "' " : '';
    $branchD2 = $branchId > 0 ? " AND d2.BranchId='" . $branchId . "' " : '';
    $branchSt = $branchId > 0 ? " AND st.BranchId='" . $branchId . "' " : '';
    $branchSp = $branchId > 0 ? " AND sp.BranchId='" . $branchId . "' " : '';

    $events = array();

    $sqlStore = "SELECT d.CreatedDate AS sort_date, d.SerialNo, d.ProductName, d.ModelNo, d.Qty,
        d.DistId AS ref_id, h.Narration AS ref_narration, b.Name AS branch_name,
        'store_inward' AS event_key, 'Store inward (dispatch created)' AS event_label
        FROM tbl_distibute_item_details d
        LEFT JOIN tbl_distibute_items h ON h.id = d.DistId
        LEFT JOIN tbl_branch b ON b.id = d.BranchId
        WHERE d.ProdType IN (1,2) AND d.SerialNo IS NOT NULL AND TRIM(d.SerialNo) <> '' AND d.SerialNo <> 'N/A'
        $like $branchD";
    $rs = $conn->query($sqlStore);
    if ($rs) {
        while ($r = $rs->fetch_assoc()) {
            $r['officer_name'] = '';
            $r['customer_info'] = '';
            $r['ref_label'] = 'Batch #' . (int) ($r['ref_id'] ?? 0);
            $events[] = $r;
        }
    }

    $sqlOfficer = "SELECT d2.CreatedDate AS sort_date, d2.SerialNo, d2.ProductName, d2.ModelNo, d2.Qty,
        d2.DistId AS ref_id, h2.Narration AS ref_narration, b.Name AS branch_name,
        u.Fname AS officer_name,
        'officer_assign' AS event_key, 'Assigned to dispatch officer' AS event_label
        FROM tbl_distibute_item_details2 d2
        LEFT JOIN tbl_distibute_items2 h2 ON h2.id = d2.DistId
        LEFT JOIN tbl_branch b ON b.id = d2.BranchId
        LEFT JOIN tbl_users u ON u.id = d2.StoreExeId
        WHERE d2.ProdType IN (1,2) AND d2.SerialNo IS NOT NULL AND TRIM(d2.SerialNo) <> '' AND d2.SerialNo <> 'N/A'
        $like2 $branchD2";
    $rs = $conn->query($sqlOfficer);
    if ($rs) {
        while ($r = $rs->fetch_assoc()) {
            $r['customer_info'] = '';
            $r['ref_label'] = 'Assign batch #' . (int) ($r['ref_id'] ?? 0);
            if (!empty($r['officer_name'])) {
                $r['ref_label'] .= ' — ' . $r['officer_name'];
            }
            $events[] = $r;
        }
    }

    $sqlChallan = "SELECT COALESCE(s.CreatedDate, CONCAT(s.InvoiceDate, ' 00:00:00'), sp.SellDate) AS sort_date,
        sp.SerialNo, sp.ProductName, sp.ModelNo, sp.Qty,
        s.id AS ref_id, s.Narration AS ref_narration, b.Name AS branch_name,
        uc.Fname AS officer_name, s.CustName AS sell_customer_name, tu.BeneficiaryId AS customer_beneficiary,
        s.InvoiceNo AS challan_invoice_no,
        'delivery_challan' AS event_key, 'Delivery challan created' AS event_label
        FROM tbl_sell_products sp
        INNER JOIN tbl_sell s ON s.id = sp.SellId AND s.Status = 1
        LEFT JOIN tbl_branch b ON b.id = sp.BranchId
        LEFT JOIN tbl_users uc ON uc.id = s.CreatedBy
        LEFT JOIN tbl_users tu ON tu.id = s.CustId
        WHERE sp.SerialNo IS NOT NULL AND TRIM(sp.SerialNo) <> '' AND sp.SerialNo <> 'N/A'
        AND s.SellType = 'Challan'
        $likeSp $branchSp";
    $rs = $conn->query($sqlChallan);
    if ($rs) {
        while ($r = $rs->fetch_assoc()) {
            $inv = trim((string) ($r['challan_invoice_no'] ?? ''));
            $r['ref_label'] = 'Challan #' . (int) ($r['ref_id'] ?? 0);
            if ($inv !== '') {
                $r['ref_label'] .= ' — Invoice ' . $inv;
            }
            $ci = array();
            if (!empty($r['customer_beneficiary'])) {
                $ci[] = 'Beneficiary: ' . $r['customer_beneficiary'];
            }
            if (!empty($r['sell_customer_name'])) {
                $ci[] = $r['sell_customer_name'];
            }
            $r['customer_info'] = implode(' | ', $ci);
            if (!empty($r['officer_name'])) {
                $r['ref_label'] .= ' — Created by: ' . $r['officer_name'];
            }
            $events[] = $r;
        }
    }

    $meta = serial_report_transfer_meta($conn);
    if ($meta['has_transfer_tbl'] && $meta['has_detail2_col']) {
        $sqlRes = "SELECT COALESCE(t.CreatedDate, t.TransferDate) AS sort_date, d2.SerialNo, d2.ProductName, d2.ModelNo, td.Qty,
            t.id AS ref_id, t.Narration AS ref_narration, tb_to.Name AS branch_name,
            u.Fname AS officer_name,
            'transfer_reserved' AS event_key, 'Transfer to store (reserved)' AS event_label
            FROM tbl_dispatch_to_store_transfer_details td
            INNER JOIN tbl_dispatch_to_store_transfer t ON t.id = td.TransferId
            INNER JOIN tbl_distibute_item_details2 d2 ON d2.id = td.Detail2Id
            LEFT JOIN tbl_branch tb_to ON tb_to.id = t.ToBranchId
            LEFT JOIN tbl_users u ON u.id = t.DispatchOfficerId
            WHERE TRIM(d2.SerialNo) LIKE '%$escQ%'
            " . ($branchId > 0 ? " AND t.ToBranchId='" . $branchId . "' " : '');
        $rs = $conn->query($sqlRes);
        if ($rs) {
            while ($r = $rs->fetch_assoc()) {
                $r['customer_info'] = '';
                $r['ref_label'] = 'Transfer #' . (int) ($r['ref_id'] ?? 0) . ' → ' . ($r['branch_name'] ?? '');
                $events[] = $r;
            }
        }
    }

    $sqlCust = "SELECT st.CreatedDate AS sort_date, st.SerialNo, st.ProductName, st.ModelNo, st.Qty,
        st.SellId AS ref_id, st.Narration AS ref_narration, b.Name AS branch_name,
        uo.Fname AS officer_name, sell.CustName AS sell_customer_name, tu.BeneficiaryId AS customer_beneficiary,
        'customer_dispatch' AS event_key, 'Dispatched to customer' AS event_label
        FROM tbl_stocks st
        LEFT JOIN tbl_branch b ON b.id = st.BranchId
        LEFT JOIN tbl_users uo ON uo.id = st.CreatedBy
        LEFT JOIN tbl_sell sell ON sell.id = st.SellId
        LEFT JOIN tbl_users tu ON tu.id = sell.CustId
        WHERE st.ProdType = 1 AND st.CrDr = 'dr' AND st.SerialNo IS NOT NULL AND TRIM(st.SerialNo) <> '' AND st.SerialNo <> 'N/A'
        AND NOT EXISTS (
            SELECT 1 FROM tbl_sell_products sp2
            INNER JOIN tbl_sell s2 ON s2.id = sp2.SellId AND s2.Status = 1
            WHERE sp2.SellId = st.SellId AND TRIM(sp2.SerialNo) = TRIM(st.SerialNo)
            AND s2.SellType = 'Challan'
        )
        $likeSt $branchSt";
    $rs = $conn->query($sqlCust);
    if ($rs) {
        while ($r = $rs->fetch_assoc()) {
            $r['ref_label'] = 'Challan #' . (int) ($r['ref_id'] ?? 0);
            $ci = array();
            if (!empty($r['customer_beneficiary'])) {
                $ci[] = 'Beneficiary: ' . $r['customer_beneficiary'];
            }
            if (!empty($r['sell_customer_name'])) {
                $ci[] = $r['sell_customer_name'];
            }
            $r['customer_info'] = implode(' | ', $ci);
            $events[] = $r;
        }
    }

    usort($events, function ($a, $b) {
        $ta = strtotime((string) ($a['sort_date'] ?? ''));
        $tb = strtotime((string) ($b['sort_date'] ?? ''));
        if ($ta === $tb) {
            return 0;
        }
        return ($ta < $tb) ? 1 : -1;
    });

    return $events;
}

/**
 * When searching by serial, resolve current location without branch-scoped MAX() misses.
 *
 * @return array<int, array<string, mixed>>
 */
function serial_report_fetch_rows_by_serial($conn, $serialQ, $branchId, $locationFilter)
{
    $serialQ = trim((string) $serialQ);
    if ($serialQ === '') {
        return array();
    }

    $escQ = $conn->real_escape_string($serialQ);
    $branchId = (int) $branchId;
    $branchExtra = $branchId > 0 ? " AND BranchId='" . $branchId . "' " : '';

    $sqlSn = "SELECT DISTINCT TRIM(SerialNo) AS serial_no FROM (
        SELECT SerialNo FROM tbl_distibute_item_details
        WHERE ProdType IN (1,2) AND SerialNo LIKE '%$escQ%' $branchExtra
        UNION
        SELECT SerialNo FROM tbl_distibute_item_details2
        WHERE ProdType IN (1,2) AND SerialNo LIKE '%$escQ%' $branchExtra
        UNION
        SELECT SerialNo FROM tbl_stocks
        WHERE ProdType=1 AND CrDr='dr' AND SerialNo LIKE '%$escQ%' $branchExtra
    ) t ORDER BY serial_no";
    $rs = $conn->query($sqlSn);
    if (!$rs) {
        return array();
    }

    $rows = array();
    while ($snRow = $rs->fetch_assoc()) {
        $sn = trim((string) $snRow['serial_no']);
        if ($sn === '') {
            continue;
        }
        $one = serial_report_fetch_rows($conn, $branchId, $sn, 'all');
        foreach ($one as $r) {
            if ($locationFilter === '' || $locationFilter === 'all' || $r['location_key'] === $locationFilter) {
                $rows[] = $r;
            }
        }
    }

    if (count($rows) === 0) {
        return serial_report_fetch_rows($conn, $branchId, $serialQ, $locationFilter);
    }

    return $rows;
}

function serial_report_export_csv($rows)
{
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="serial-location-report-' . date('Y-m-d') . '.csv"');
    $out = fopen('php://output', 'w');
    fputcsv($out, array(
        'Serial No',
        'Current Location',
        'Product',
        'Model',
        'Branch',
        'Dispatch Officer',
        'Store Dispatch Created (#)',
        'Store Dispatch Date',
        'Officer Assign Batch (#)',
        'Officer Assign Date',
        'Customer / Note',
    ));
    foreach ($rows as $r) {
        fputcsv($out, array(
            $r['serial_no'],
            $r['current_location'],
            $r['product_name'],
            $r['model_no'],
            $r['branch_display'],
            $r['dispatch_officer_name'] ?? '',
            $r['store_dispatch_id'] ?? '',
            serial_report_format_date($r['store_dispatch_date'] ?? ''),
            $r['officer_batch_id'] ?? '',
            serial_report_format_date($r['officer_batch_date'] ?? ''),
            $r['detail_note'] ?? '',
        ));
    }
    fclose($out);
    exit;
}
