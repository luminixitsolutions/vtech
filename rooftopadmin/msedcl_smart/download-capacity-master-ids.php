<?php
require_once __DIR__ . '/_init.php';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="rooftop_capacity_master_ids.csv"');

$out = fopen('php://output', 'w');
fputcsv($out, ['id', 'Name', 'Status', 'Roll']);

$rows = getList("SELECT id, Name, Status, Roll FROM tbl_rooftop_common_master WHERE Roll='2' AND Status='1' ORDER BY id ASC");
if (is_array($rows)) {
    foreach ($rows as $row) {
        fputcsv($out, [
            (int) ($row['id'] ?? 0),
            (string) ($row['Name'] ?? ''),
            (string) ($row['Status'] ?? ''),
            (string) ($row['Roll'] ?? ''),
        ]);
    }
}

fclose($out);
exit;
