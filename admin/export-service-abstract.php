<?php
session_start();
include_once 'config.php';
include_once 'auth.php';
include_once 'inc-service-abstract-data.php';

$filters = serviceAbstractFiltersFromRequest();
$abstract = getServiceAbstractData($filters);
$rows = $abstract['rows'];
$totals = $abstract['totals'];
$reportTitle = $abstract['title'];
$exportDate = date('d.m.Y');

$filename = 'Service_Abstract_' . date('Y-m-d') . '.xls';
header('Content-Type: application/vnd.ms-excel; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Pragma: no-cache');
header('Expires: 0');
echo "\xEF\xBB\xBF";
?>
<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>
    table { border-collapse: collapse; }
    th, td { border: 1px solid #000000; font-size: 11pt; padding: 4px; text-align: center; }
    th { background: #e9ecef; font-weight: bold; }
    .district { text-align: left; font-weight: bold; }
    .total td { background: #e9ecef; font-weight: bold; }
    .title { font-size: 14pt; font-weight: bold; text-align: center; }
</style>
</head>
<body>
<table>
    <tr><td colspan="6" class="title">VTECH SUNSYSTEMS PVT LTD</td></tr>
    <tr><td colspan="6" class="title"><?php echo htmlspecialchars($reportTitle); ?></td></tr>
    <tr><td colspan="6" class="title">Update as on <?php echo $exportDate; ?></td></tr>
    <tr><td colspan="6"></td></tr>
    <tr>
        <th>DISTRICT</th>
        <th>Total Complaints</th>
        <th>Total complaints closed</th>
        <th>TODAY COMPLAINTS ADD</th>
        <th>Complaints Hold due to the material issue</th>
        <th>Total complaints pending</th>
    </tr>
    <?php foreach ($rows as $row) { ?>
    <tr>
        <td class="district"><?php echo htmlspecialchars($row['district']); ?></td>
        <td><?php echo (int) $row['total_complaints']; ?></td>
        <td><?php echo (int) $row['total_closed']; ?></td>
        <td><?php echo (int) $row['today_added']; ?></td>
        <td><?php echo (int) $row['material_hold']; ?></td>
        <td><?php echo (int) $row['total_pending']; ?></td>
    </tr>
    <?php } ?>
    <?php if (count($rows) > 1) { ?>
    <tr class="total">
        <td class="district"><?php echo htmlspecialchars($totals['district']); ?></td>
        <td><?php echo (int) $totals['total_complaints']; ?></td>
        <td><?php echo (int) $totals['total_closed']; ?></td>
        <td><?php echo (int) $totals['today_added']; ?></td>
        <td><?php echo (int) $totals['material_hold']; ?></td>
        <td><?php echo (int) $totals['total_pending']; ?></td>
    </tr>
    <?php } ?>
</table>
</body>
</html>
