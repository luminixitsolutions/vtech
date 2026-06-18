<?php
/**
 * DataTables server-side JSON for Serial No Products on beneficiary required-stock page.
 */
session_start();
header('Content-Type: application/json; charset=utf-8');
include_once '../config.php';
require_once __DIR__ . '/../inc-under-production-beneficiary-stock-data.php';

if (empty($_SESSION['Admin']['id'])) {
    echo json_encode(['draw' => 0, 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => [], 'error' => 'Unauthorized']);
    exit;
}

$draw = isset($_POST['draw']) ? (int) $_POST['draw'] : 1;
$start = isset($_POST['start']) ? (int) $_POST['start'] : 0;
$length = isset($_POST['length']) ? (int) $_POST['length'] : 20;
$start = max(0, $start);
$length = max(1, min(100, $length));

$search = '';
if (isset($_POST['search']) && is_array($_POST['search']) && isset($_POST['search']['value'])) {
    $search = trim((string) $_POST['search']['value']);
} elseif (isset($_POST['search'])) {
    $search = trim((string) $_POST['search']);
}

$rawIds = [];
if (!empty($_POST['cust_ids'])) {
    foreach (explode(',', (string) $_POST['cust_ids']) as $part) {
        $id = (int) trim($part);
        if ($id > 0) {
            $rawIds[] = $id;
        }
    }
}

$custIds = upb_validate_stock_report_customer_ids($conn, $rawIds);
if (count($custIds) === 0 && count($rawIds) === 1 && (int) $rawIds[0] > 0) {
    $custIds = [(int) $rawIds[0]];
}

$storeColumns = upb_fetch_customer_store_columns($conn, $custIds);
$recordsTotal = upb_count_serial_product_catalog($conn);
$recordsFiltered = upb_count_serial_product_catalog_filtered($conn, $search);

$pageLines = upb_fetch_serial_product_catalog_page($conn, $start, $length, $search);
$reqMap = count($custIds) > 0
    ? upb_serial_required_qty_map_for_customer_ids($conn, $custIds)
    : [];
$pageLines = upb_overlay_serial_req_map($pageLines, $reqMap);
$rows = upb_build_serial_stock_row_payload($conn, $pageLines, $storeColumns, $start + 1);

$pageReq = 0;
$pageAvail = 0;
foreach ($rows as $r) {
    $pageReq += (int) ($r['req_qty'] ?? 0);
    $pageAvail += (int) ($r['total_serials'] ?? 0);
}

echo json_encode([
    'draw' => $draw,
    'recordsTotal' => $recordsTotal,
    'recordsFiltered' => $recordsFiltered,
    'data' => $rows,
    'page_total_req' => $pageReq,
    'page_total_avail' => $pageAvail,
]);
