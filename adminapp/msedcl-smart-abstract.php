<?php
session_start();
require_once 'config.php';
require_once 'auth.php';
require_once 'inc-mobile-mgmt.php';

$PageName = 'MSEDCL Smart Abstract';
$abstract = mobileMsedclSmartGetAbstractData();
$abstractRows = $abstract['rows'];
$abstractTotals = $abstract['totals'];
$abstractMeta = $abstract['abstractMeta'];
$totPmsgy = (int) $abstractTotals['pmsgy_cnt'];
$totMahadiscom = (int) $abstractTotals['mahadiscom_cnt'];
$totPayment = (int) $abstractTotals['payment_cnt'];
$totSurvey = (int) $abstractTotals['survey_cnt'];
?>
<!doctype html>
<html lang="en" class="h-100">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $Proj_Title; ?> | MSEDCL Smart Abstract</title>
<link rel="manifest" href="manifest.json">
<link rel="apple-touch-icon" href="img/favicon180.png">
<link rel="icon" href="img/favicon32.png">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
<link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<link href="css/mobile-mgmt.css" rel="stylesheet">
</head>
<body class="body-scroll menu-overlay mob-mgmt-page">

<?php include_once 'sidebar.php'; ?>

<main class="main has-footer">
<?php include_once 'top_header.php'; ?>

<div class="mob-mgmt-heading mob-mgmt-heading-msedcl">MSEDCL SMART PROJECT ABSTRACT</div>

<div class="mob-mgmt-list-wrap" style="padding-top: 8px;">
    <div class="mob-mgmt-table-wrap table-responsive">
        <table class="table table-striped table-bordered mob-mgmt-table mb-0">
            <thead>
                <tr>
                    <th>Sr</th>
                    <th>District</th>
                    <th>PMSGY Portal<br><small>Awaiting Mahadiscom</small></th>
                    <th>Mahadiscom Portal<br><small>Awaiting Payment</small></th>
                    <th>Payment Done<br><small>Survey Pending</small></th>
                    <th>Survey Done</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($abstractRows)) { ?>
                <tr>
                    <td colspan="6" class="text-center text-muted">No data found.</td>
                </tr>
                <?php } else {
                    $i = 1;
                    foreach ($abstractRows as $row) {
                        ?>
                <tr>
                    <td><?php echo $i++; ?></td>
                    <th scope="row" class="mob-mgmt-table-label"><?php echo htmlspecialchars((string) $row['District']); ?></th>
                    <td><?php echo mobileMsedclSmartAbstractCountCell($row['pmsgy_cnt'], 'pmsgy', (string) $row['District'], $abstractMeta); ?></td>
                    <td><?php echo mobileMsedclSmartAbstractCountCell($row['mahadiscom_cnt'], 'mahadiscom', (string) $row['District'], $abstractMeta); ?></td>
                    <td><?php echo mobileMsedclSmartAbstractCountCell($row['payment_cnt'], 'payment', (string) $row['District'], $abstractMeta); ?></td>
                    <td><?php echo mobileMsedclSmartAbstractCountCell($row['survey_cnt'], 'survey', (string) $row['District'], $abstractMeta); ?></td>
                </tr>
                        <?php
                    }
                } ?>
            </tbody>
            <?php if (!empty($abstractRows)) { ?>
            <tfoot>
                <tr class="mob-mgmt-table-total">
                    <td></td>
                    <th scope="row" class="mob-mgmt-table-label">Total</th>
                    <td><?php echo mobileMsedclSmartAbstractCountCell($totPmsgy, 'pmsgy', '', $abstractMeta); ?></td>
                    <td><?php echo mobileMsedclSmartAbstractCountCell($totMahadiscom, 'mahadiscom', '', $abstractMeta); ?></td>
                    <td><?php echo mobileMsedclSmartAbstractCountCell($totPayment, 'payment', '', $abstractMeta); ?></td>
                    <td><?php echo mobileMsedclSmartAbstractCountCell($totSurvey, 'survey', '', $abstractMeta); ?></td>
                </tr>
            </tfoot>
            <?php } ?>
        </table>
    </div>

    <p class="text-muted small px-1 mb-0">Tap a count to view customer details for that stage.</p>
</div>

</main>

<?php include_once 'footer.php'; ?>
</body>
</html>
