<?php
session_start();
include_once '../config.php';
include_once '../auth.php';

$user_id = (int) ($_SESSION['Admin']['id'] ?? 0);
$row77 = getRecord("SELECT Roll, Options FROM tbl_users WHERE id='$user_id'");
$Roll = (int) ($row77['Roll'] ?? 0);
$Options = isset($row77['Options']) ? explode(',', $row77['Options']) : [];

if (!employeeActivityLogCanViewReport($Roll, $Options)) {
    header('HTTP/1.1 403 Forbidden');
    exit('Access denied.');
}

$params = employeeActivityLogReportParamsFromRequest();
$where = $params['where'];
employeeActivityLogEnsureTable($conn);
$rows = getList("SELECT * FROM tbl_employee_activity_logs WHERE $where ORDER BY id DESC LIMIT 50000");

$filename = 'employee-tracking-' . date('Y-m-d-His') . '.csv';
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');

$out = fopen('php://output', 'w');
fputcsv($out, [
    'Date Time', 'Employee', 'Role', 'Module', 'Page', 'Action', 'Table', 'Record ID', 'IP', 'User Agent',
]);

$labels = employeeActivityLogActionTypeOptions();
foreach ($rows as $r) {
    fputcsv($out, [
        $r['created_at'],
        $r['employee_name'],
        $r['role'],
        $r['module_name'],
        $r['page_name'],
        $labels[$r['action_type']] ?? $r['action_type'],
        $r['record_table'],
        $r['record_id'],
        $r['ip_address'],
        $r['user_agent'],
    ]);
}
fclose($out);
exit;
