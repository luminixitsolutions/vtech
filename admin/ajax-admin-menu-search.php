<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
include_once 'config.php';
include_once 'auth.php';
require_once __DIR__ . '/inc-menu-option-groups.php';
require_once __DIR__ . '/inc-admin-menu-search-data.php';

if (empty($_SESSION['Admin']['id'])) {
    echo json_encode(['ok' => false, 'results' => []]);
    exit;
}

$userId = (int) $_SESSION['Admin']['id'];
$row = getRecord("SELECT Roll, Options FROM tbl_users WHERE id='$userId' LIMIT 1");
$roll = (int) ($row['Roll'] ?? 0);
$options = adminResolveMenuOptionsFromUserRow($row);
$q = trim((string) ($_GET['q'] ?? ''));

$base = '';
if (function_exists('topHeaderAdminBasePath')) {
    $base = topHeaderAdminBasePath();
}

echo json_encode([
    'ok' => true,
    'results' => adminMenuSearchFilter($roll, $options, $q, $base),
]);
