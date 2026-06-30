<?php
/**
 * Rooftop admin dashboard — chart & aggregate helpers.
 */

function rooftopDashboardMonthlyCounts($table, $dateColumn, $months = 6)
{
    global $conn;
    $months = max(1, (int) $months);
    $dateColumn = preg_replace('/[^a-zA-Z0-9_]/', '', (string) $dateColumn);
    $table = preg_replace('/[^a-zA-Z0-9_]/', '', (string) $table);
    if ($table === '' || $dateColumn === '') {
        return ['labels' => [], 'counts' => []];
    }
    $sql = "SELECT DATE_FORMAT($dateColumn, '%b %Y') AS label,
            DATE_FORMAT($dateColumn, '%Y-%m') AS ym,
            COUNT(id) AS cnt
            FROM $table
            WHERE $dateColumn >= DATE_SUB(CURDATE(), INTERVAL $months MONTH)
            GROUP BY ym, label
            ORDER BY ym ASC";
    $rows = getList($sql);
    $labels = [];
    $counts = [];
    foreach ($rows as $row) {
        $labels[] = $row['label'];
        $counts[] = (int) $row['cnt'];
    }
    return ['labels' => $labels, 'counts' => $counts];
}

function rooftopDashboardClaimStatusChart()
{
    global $conn;
    $labels = [];
    $counts = [];
    $rows = getList("SELECT Name FROM tbl_common_master WHERE Status='1' AND Roll=6 ORDER BY Name ASC");
    foreach ($rows as $row) {
        $name = $row['Name'];
        $esc = mysqli_real_escape_string($conn, $name);
        $cnt = (int) getRow("SELECT id FROM tbl_rooftop_service_complaint WHERE ClainStatus='$esc'");
        if ($cnt > 0) {
            $labels[] = $name;
            $counts[] = $cnt;
        }
    }
    return ['labels' => $labels, 'counts' => $counts];
}
