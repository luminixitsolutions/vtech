<?php
session_start();
include_once __DIR__ . '/../config.php';
include_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../inc-msedcl-smart-site.php';

if (!isset($_SESSION['Admin']['id'])) {
    header('Location: ../index.php');
    exit;
}

msedclSmartInitUserAccess();
if (!msedclSmartCanAccessOption(MSEDCL_SMART_OPT_ABSTRACT)) {
    header('Location: ' . msedclSmartFirstAccessiblePage());
    exit;
}

$meta = msedclSmartAbstractFiltersFromRequest();
$rows = msedclSmartAbstractByDistrict($meta['filters']);
$totals = msedclSmartAbstractTotals($rows);
$exportDate = date('d.m.Y');

$filename = 'MSEDCL_SMART_PROJECT_Abstract_' . date('Y-m-d') . '.xls';
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
    .label-col { text-align: left; }
    .total td { background: #e9ecef; font-weight: bold; }
    .title { font-size: 14pt; font-weight: bold; text-align: center; }
    .meta { font-size: 10pt; text-align: center; }
</style>
</head>
<body>
<table>
    <tr><td colspan="6" class="title"><?php echo htmlspecialchars($Proj_Title); ?></td></tr>
    <tr><td colspan="6" class="title">MSEDCL SMART PROJECT ABSTRACT</td></tr>
    <tr><td colspan="6" class="meta">Update as on <?php echo $exportDate; ?></td></tr>
    <tr><td colspan="6" class="meta">
        District: <?php echo $meta['District'] !== '' ? htmlspecialchars($meta['District']) : 'All'; ?> |
        Taluka: <?php echo $meta['Taluka'] !== '' ? htmlspecialchars($meta['Taluka']) : 'All'; ?> |
        From: <?php echo $meta['FromDate'] !== '' ? htmlspecialchars($meta['FromDate']) : '—'; ?> |
        To: <?php echo $meta['ToDate'] !== '' ? htmlspecialchars($meta['ToDate']) : '—'; ?> |
        Date mode: <?php echo $meta['DateMode'] === 'stage' ? 'Stage date' : 'Upload date'; ?>
    </td></tr>
    <tr><td colspan="6"></td></tr>
    <tr>
        <th>Sr no.</th>
        <th>District</th>
        <th>PMSGY Portal (Awaiting Mahadiscom)</th>
        <th>Mahadiscom Portal (Awaiting Payment)</th>
        <th>Payment Done (Survey Pending)</th>
        <th>Survey Done</th>
    </tr>
    <?php
    if (empty($rows)) {
        echo '<tr><td colspan="6">No data for selected filters.</td></tr>';
    } else {
        $i = 1;
        foreach ($rows as $row) {
            ?>
    <tr>
        <td><?php echo $i++; ?></td>
        <td class="label-col"><?php echo htmlspecialchars((string) $row['District']); ?></td>
        <td><?php echo (int) $row['pmsgy_cnt']; ?></td>
        <td><?php echo (int) $row['mahadiscom_cnt']; ?></td>
        <td><?php echo (int) $row['payment_cnt']; ?></td>
        <td><?php echo (int) $row['survey_cnt']; ?></td>
    </tr>
            <?php
        }
    }
    ?>
    <tr class="total">
        <td colspan="2">Total</td>
        <td><?php echo (int) $totals['pmsgy_cnt']; ?></td>
        <td><?php echo (int) $totals['mahadiscom_cnt']; ?></td>
        <td><?php echo (int) $totals['payment_cnt']; ?></td>
        <td><?php echo (int) $totals['survey_cnt']; ?></td>
    </tr>
</table>
</body>
</html>
