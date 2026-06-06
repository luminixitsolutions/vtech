<?php
session_start();
require_once 'config.php';
require_once 'auth.php';
require_once 'inc-mobile-mgmt.php';
require_once __DIR__ . '/../admin/report_management/inc-contractor-payment.php';

$PageName = 'Contractor Billing Report';
contractorPaymentEnsureTable($conn);

$projectId = isset($_GET['project_id']) ? (int) $_GET['project_id'] : 0;
$subheadId = isset($_GET['subhead_id']) ? (int) $_GET['subhead_id'] : 0;
$projectName = $projectId > 0 ? contractorBillingGetProjectName($conn, $projectId) : '';
$subheadName = $subheadId > 0 ? contractorBillingGetSubHeadName($conn, $subheadId) : '';

if ($projectId > 0 && $projectName === '') {
    header('Location: mobile-contractor-billing.php');
    exit;
}
if ($subheadId > 0 && ($subheadName === '' || $projectId <= 0)) {
    header('Location: mobile-contractor-billing.php' . ($projectId > 0 ? '?project_id=' . $projectId : ''));
    exit;
}

$viewLevel = 'summary';
if ($projectId > 0 && $subheadId > 0) {
    $viewLevel = 'contractors';
}

$pageTitle = 'Contractor Billing Report';
$backUrl = 'home.php';
if ($viewLevel === 'summary' && $projectId > 0) {
    $pageTitle = $projectName;
    $backUrl = 'mobile-contractor-billing.php';
} elseif ($viewLevel === 'contractors') {
    $pageTitle = $projectName . ' / ' . $subheadName;
    $backUrl = mobileContractorBillingUrl($projectId);
    $subheadSummary = contractorBillingSubHeadPaymentSummary($conn, $projectId, $subheadId);
}

$summaryRows = array();
$summaryTotals = array(
    'total_sites' => 0,
    'total_contractors' => 0,
    'total_billing' => 0.0,
    'total_advance' => 0.0,
    'total_paid' => 0.0,
    'total_balance' => 0.0,
);

if ($viewLevel === 'summary') {
    $summaryRows = contractorBillingProjectSubHeadSummaryList($conn, $projectId);
    foreach ($summaryRows as $summaryRow) {
        $summaryTotals['total_sites'] += (int) ($summaryRow['total_sites'] ?? 0);
        $summaryTotals['total_contractors'] += (int) ($summaryRow['total_contractors'] ?? 0);
        $summaryTotals['total_billing'] += (float) ($summaryRow['total_billing'] ?? 0);
        $summaryTotals['total_advance'] += (float) ($summaryRow['total_advance'] ?? 0);
        $summaryTotals['total_paid'] += (float) ($summaryRow['total_paid'] ?? 0);
        $summaryTotals['total_balance'] += (float) ($summaryRow['total_balance'] ?? 0);
    }
}
?>
<!doctype html>
<html lang="en" class="h-100">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $Proj_Title; ?> | Contractor Billing Report</title>
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

<div class="mob-mgmt-topbar">
    <a href="<?php echo htmlspecialchars($backUrl, ENT_QUOTES, 'UTF-8'); ?>"><span class="material-icons">arrow_back</span></a>
    <h1><?php echo htmlspecialchars($pageTitle); ?></h1>
    <?php if ($viewLevel === 'summary') { ?>
    <span class="badge bg-primary"><?php echo count($summaryRows); ?></span>
    <?php } ?>
</div>

<?php if ($viewLevel === 'contractors') { ?>
<div class="px-3 mb-2">
    <input type="search" id="contractorSearch" class="form-control form-control-sm" placeholder="Search contractor name..." autocomplete="off">
</div>
<?php } ?>

<div class="mob-mgmt-list-wrap pt-2">
<?php if ($viewLevel === 'summary') {
    if (empty($summaryRows)) {
        ?>
    <div class="mob-mgmt-empty">No contractor billing records found.</div>
        <?php
    } else {
        ?>
    <div class="mob-mgmt-table-wrap mob-mgmt-table-scroll">
        <table class="table table-striped table-bordered mob-mgmt-table mob-mgmt-table-billing mob-mgmt-table-contractor-summary mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Project Name</th>
                    <th>Sub Head Name</th>
                    <th>Sites</th>
                    <th>Contractors</th>
                    <th class="scope-amt">Billing</th>
                    <th class="scope-amt">Advance</th>
                    <th class="scope-amt">Paid</th>
                    <th class="scope-amt">Balance</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                foreach ($summaryRows as $row) {
                    $pid = (int) ($row['project_id'] ?? 0);
                    $sid = (int) ($row['subhead_id'] ?? 0);
                    $href = mobileContractorBillingUrl($pid, $sid);
                    ?>
                <tr class="mob-mgmt-row-link" onclick="window.location.href='<?php echo htmlspecialchars($href, ENT_QUOTES, 'UTF-8'); ?>'">
                    <td><?php echo $i++; ?></td>
                    <td><?php echo htmlspecialchars((string) ($row['project_name'] ?? '')); ?></td>
                    <td><?php echo htmlspecialchars((string) ($row['subhead_name'] ?? '')); ?></td>
                    <td><?php echo (int) ($row['total_sites'] ?? 0); ?></td>
                    <td><?php echo (int) ($row['total_contractors'] ?? 0); ?></td>
                    <td class="scope-amt">&#8377;<?php echo contractorPaymentFormatMoney($row['total_billing'] ?? 0); ?></td>
                    <td class="scope-amt text-info">&#8377;<?php echo contractorPaymentFormatMoney($row['total_advance'] ?? 0); ?></td>
                    <td class="scope-amt text-success">&#8377;<?php echo contractorPaymentFormatMoney($row['total_paid'] ?? 0); ?></td>
                    <td class="scope-amt text-danger fw-bold">&#8377;<?php echo contractorPaymentFormatMoney($row['total_balance'] ?? 0); ?></td>
                </tr>
                    <?php
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" class="text-end">Total</th>
                    <th><?php echo (int) $summaryTotals['total_sites']; ?></th>
                    <th><?php echo (int) $summaryTotals['total_contractors']; ?></th>
                    <th class="scope-amt">&#8377;<?php echo contractorPaymentFormatMoney($summaryTotals['total_billing']); ?></th>
                    <th class="scope-amt">&#8377;<?php echo contractorPaymentFormatMoney($summaryTotals['total_advance']); ?></th>
                    <th class="scope-amt">&#8377;<?php echo contractorPaymentFormatMoney($summaryTotals['total_paid']); ?></th>
                    <th class="scope-amt">&#8377;<?php echo contractorPaymentFormatMoney($summaryTotals['total_balance']); ?></th>
                </tr>
            </tfoot>
        </table>
    </div>
        <?php
    }
} else {
    $items = contractorBillingContractorsByProjectSubHead($conn, $projectId, $subheadId);
    ?>
    <div class="mob-mgmt-table-wrap mob-mgmt-table-scroll">
        <table class="table table-striped table-bordered mob-mgmt-table mob-mgmt-table-billing mob-mgmt-table-contractor-summary mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Contractor Name</th>
                    <th>Sites</th>
                    <th class="scope-amt">Billing</th>
                    <th class="scope-amt">Advance</th>
                    <th class="scope-amt">Paid</th>
                    <th class="scope-amt">Balance</th>
                </tr>
            </thead>
            <tbody id="contractorList">
                <?php
                if (empty($items)) {
                    ?>
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">No contractor billing records found for this sub project.</td>
                </tr>
                    <?php
                }
                $i = 1;
                foreach ($items as $row) {
                    $cid = (int) $row['id'];
                    $cname = trim((string) ($row['Fname'] ?? '') . ' ' . (string) ($row['Lname'] ?? ''));
                    $href = mobileContractorBillingDetailsUrl($cid, $projectId, $subheadId);
                    $searchName = strtolower($cname);
                    ?>
                <tr class="mob-mgmt-row-link contractor-item" data-search-name="<?php echo htmlspecialchars($searchName, ENT_QUOTES, 'UTF-8'); ?>" onclick="window.location.href='<?php echo htmlspecialchars($href, ENT_QUOTES, 'UTF-8'); ?>'">
                    <td><?php echo $i++; ?></td>
                    <td><?php echo htmlspecialchars($cname); ?></td>
                    <td><?php echo (int) ($row['total_sites'] ?? 0); ?></td>
                    <td class="scope-amt">&#8377;<?php echo contractorPaymentFormatMoney($row['total_commission'] ?? 0); ?></td>
                    <td class="scope-amt text-info">&#8377;<?php echo contractorPaymentFormatMoney($row['total_advance'] ?? 0); ?></td>
                    <td class="scope-amt text-success">&#8377;<?php echo contractorPaymentFormatMoney($row['total_paid'] ?? 0); ?></td>
                    <td class="scope-amt text-danger fw-bold">&#8377;<?php echo contractorPaymentFormatMoney($row['balance'] ?? 0); ?></td>
                </tr>
                    <?php
                }
                ?>
            </tbody>
            <?php if (!empty($items)) { ?>
            <tfoot>
                <tr>
                    <th colspan="3" class="text-end">Sub Project Total</th>
                    <th class="scope-amt">&#8377;<?php echo contractorPaymentFormatMoney($subheadSummary['total_commission']); ?></th>
                    <th class="scope-amt">&#8377;<?php echo contractorPaymentFormatMoney($subheadSummary['total_advance']); ?></th>
                    <th class="scope-amt">&#8377;<?php echo contractorPaymentFormatMoney($subheadSummary['total_paid']); ?></th>
                    <th class="scope-amt">&#8377;<?php echo contractorPaymentFormatMoney($subheadSummary['balance']); ?></th>
                </tr>
            </tfoot>
            <?php } ?>
        </table>
    </div>
    <div id="contractorSearchEmpty" class="mob-mgmt-empty" style="display:none;">No contractors match your search.</div>
    <?php
} ?>
</div>

</main>

<?php include_once 'footer.php'; ?>
<?php if ($viewLevel === 'contractors') { ?>
<script>
$(function () {
    var $search = $('#contractorSearch');
    var $items = $('.contractor-item');
    var $empty = $('#contractorSearchEmpty');

    function filterContractors() {
        var query = $.trim($search.val()).toLowerCase();
        var visible = 0;

        $items.each(function () {
            var name = ($(this).attr('data-search-name') || '').toString();
            var match = query === '' || name.indexOf(query) !== -1;
            $(this).toggle(match);
            if (match) {
                visible++;
            }
        });

        $empty.toggle(query !== '' && visible === 0 && $items.length > 0);
    }

    $search.on('input', filterContractors);
});
</script>
<?php } ?>
</body>
</html>
