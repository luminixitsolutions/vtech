<?php
/**
 * JSON: paginated Serial No Products for beneficiary required-stock page.
 */
session_start();
header('Content-Type: application/json; charset=utf-8');
include_once '../config.php';
require_once __DIR__ . '/../inc-under-production-beneficiary-stock-data.php';

if (empty($_SESSION['Admin']['id'])) {
    echo json_encode(['ok' => false, 'message' => 'Unauthorized']);
    exit;
}

$offset = isset($_POST['offset']) ? (int) $_POST['offset'] : 0;
$page = isset($_POST['page']) ? (int) $_POST['page'] : 0;
$limit = isset($_POST['limit']) ? (int) $_POST['limit'] : 20;
$limit = max(1, min(50, $limit));
if ($page > 0) {
    $offset = ($page - 1) * $limit;
} else {
    $page = (int) floor($offset / $limit) + 1;
}
$offset = max(0, $offset);
$page = max(1, $page);
$search = isset($_POST['search']) ? trim((string) $_POST['search']) : '';

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
$total = upb_count_serial_product_catalog($conn);
if ($search !== '') {
    $total = (int) getRow(
        "SELECT COUNT(*) AS c FROM tbl_products
         WHERE Roll = 1 AND Status = 1
         AND ProductName LIKE '%" . $conn->real_escape_string($search) . "%'"
    );
}

$pageLines = upb_fetch_serial_product_catalog_page($conn, $offset, $limit, $search);
$reqMap = count($custIds) > 0
    ? upb_serial_required_qty_map_for_customer_ids($conn, $custIds)
    : [];
$pageLines = upb_overlay_serial_req_map($pageLines, $reqMap);
$rows = upb_build_serial_stock_row_payload($conn, $pageLines, $storeColumns, $offset + 1);

$pageReq = 0;
$pageAvail = 0;
foreach ($rows as $r) {
    $pageReq += (int) ($r['req_qty'] ?? 0);
    $pageAvail += (int) ($r['total_serials'] ?? 0);
}

$loadedThrough = $offset + count($rows);
$totalPages = $total > 0 ? (int) ceil($total / $limit) : 1;

echo json_encode([
    'ok' => true,
    'rows' => $rows,
    'page' => $page,
    'offset' => $offset,
    'limit' => $limit,
    'total' => $total,
    'total_pages' => $totalPages,
    'loaded_through' => $loadedThrough,
    'has_more' => $page < $totalPages,
    'page_total_req' => $pageReq,
    'page_total_avail' => $pageAvail,
    'store_columns' => count($storeColumns),
]);
