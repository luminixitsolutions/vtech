<?php
require_once __DIR__ . '/_init.php';

$Page = 'MSEDCL-Smart-Abstract';
msedclSmartRequireOption(MSEDCL_SMART_OPT_ABSTRACT);

msedclSmartEnsureTables();
msedclSmartSyncAllSurveyDoneStatuses(isset($_SESSION['Admin']['id']) ? (int) $_SESSION['Admin']['id'] : 0);

$meta = msedclSmartAbstractFiltersFromRequest();
$filters = $meta['filters'];
$filterDistrict = $meta['District'];
$filterTaluka = $meta['Taluka'];
$filterFromDate = $meta['FromDate'];
$filterToDate = $meta['ToDate'];
$filterDateMode = $meta['DateMode'];
$isSearch = isset($_REQUEST['Search']);

$rows = msedclSmartAbstractByDistrict($filters);
$totals = msedclSmartAbstractTotals($rows);
$totPmsgy = $totals['pmsgy_cnt'];
$totMahadiscom = $totals['mahadiscom_cnt'];
$totPayment = $totals['payment_cnt'];
$totSurvey = $totals['survey_cnt'];
$exportQuery = msedclSmartAbstractExportQueryString($meta);
$abstractMeta = [
    'District' => $filterDistrict,
    'Taluka' => $filterTaluka,
    'FromDate' => $filterFromDate,
    'ToDate' => $filterToDate,
    'DateMode' => $filterDateMode,
];

$districtRows = getList("SELECT DISTINCT TRIM(District) AS District FROM tbl_rooftop_msedcl_smart_customers WHERE TRIM(District)!='' AND Status=1 ORDER BY District ASC");
$talukaRows = getList("SELECT DISTINCT TRIM(Taluka) AS Taluka FROM tbl_rooftop_msedcl_smart_customers WHERE TRIM(Taluka)!='' AND Status=1 ORDER BY Taluka ASC");
if (!is_array($districtRows)) {
    $districtRows = [];
}
if (!is_array($talukaRows)) {
    $talukaRows = [];
}
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | MSEDCL SMART PROJECT ABSTRACT</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<?php include_once __DIR__ . '/../header_script.php'; ?>
<link rel="stylesheet" href="css/msedcl-smart-dashboard.css">
<style>
.msedcl-abstract-print-header { display: none; }
@media print {
    @page {
        size: A4 portrait;
        margin: 8mm 6mm;
    }
    html, body {
        height: auto !important;
        min-height: 0 !important;
        overflow: visible !important;
        color: #000 !important;
        background: #fff !important;
    }
    .msedcl-abstract-no-print,
    #layout-sidenav,
    #layout-navbar,
    .layout-footer,
    .msedcl-abstract-screen-title {
        display: none !important;
    }
    .layout-wrapper,
    .layout-inner,
    .layout-container,
    .layout-content {
        display: block !important;
        flex: none !important;
        padding: 0 !important;
        margin: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
        min-height: 0 !important;
        height: auto !important;
        overflow: visible !important;
    }
    .container-fluid,
    .container-p-y,
    .table-responsive {
        padding: 0 !important;
        margin: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
        min-height: 0 !important;
        height: auto !important;
        overflow: visible !important;
    }
    .layout-navbar-fixed .layout-content { padding-top: 0 !important; }
    .msedcl-abstract-print-header {
        display: block !important;
        text-align: center;
        margin: 0 0 8px;
        color: #000 !important;
    }
    .msedcl-abstract-print-company {
        font-size: 13pt;
        font-weight: 700;
        color: #000 !important;
        margin-bottom: 2px;
    }
    .msedcl-abstract-print-title {
        font-size: 12pt;
        font-weight: 700;
        color: #000 !important;
        margin-bottom: 2px;
    }
    .msedcl-abstract-print-meta,
    .msedcl-abstract-print-filters {
        font-size: 8pt;
        color: #000 !important;
        margin-bottom: 1px;
    }
    .card {
        border: none !important;
        box-shadow: none !important;
        page-break-inside: avoid;
    }
    .card-body { padding: 0 !important; }
    .msedcl-abstract-table {
        width: 100% !important;
        table-layout: fixed;
        page-break-inside: avoid;
    }
    .msedcl-abstract-table,
    .msedcl-abstract-table th,
    .msedcl-abstract-table td {
        border: 1px solid #000 !important;
        color: #000 !important;
        font-size: 7.5pt !important;
        line-height: 1.15 !important;
        padding: 2px 3px !important;
        word-wrap: break-word;
    }
    .msedcl-abstract-table thead th {
        background-color: #e9ecef !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
        color: #000 !important;
        font-weight: 700 !important;
        font-size: 7pt !important;
    }
    .msedcl-abstract-table th:nth-child(1),
    .msedcl-abstract-table td:nth-child(1) { width: 7%; }
    .msedcl-abstract-table th:nth-child(2),
    .msedcl-abstract-table td:nth-child(2) { width: 15%; }
    .msedcl-abstract-table tfoot td {
        background-color: #e9ecef !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
        font-weight: 700 !important;
        color: #000 !important;
    }
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: transparent !important;
    }
    .msedcl-abstract-count-link {
        color: #000 !important;
        text-decoration: none !important;
        font-weight: normal !important;
    }
    a[href]:after { content: none !important; }
}
.msedcl-abstract-count-link {
    color: #2563eb;
    font-weight: 600;
    text-decoration: underline;
    cursor: pointer;
}
.msedcl-abstract-count-link:hover {
    color: #1d4ed8;
}
#msedclAbstractRecordsModal .modal-dialog {
    max-width: 95%;
}
</style>
</head>
<body>
<div class="layout-wrapper layout-2">
<div class="layout-inner">
<?php include_once __DIR__ . '/msedcl-smart-sidebar.php'; ?>
<div class="layout-container">
<?php include_once __DIR__ . '/../top_header.php'; ?>
<div class="layout-content">
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="msedcl-abstract-print-header">
        <div class="msedcl-abstract-print-company"><?php echo htmlspecialchars($Proj_Title); ?></div>
        <div class="msedcl-abstract-print-title">MSEDCL SMART PROJECT ABSTRACT</div>
        <div class="msedcl-abstract-print-meta">Update as on <?php echo date('d.m.Y'); ?></div>
        <?php if ($isSearch) { ?>
        <div class="msedcl-abstract-print-filters">
            District: <?php echo $filterDistrict !== '' ? htmlspecialchars($filterDistrict) : 'All'; ?> |
            Taluka: <?php echo $filterTaluka !== '' ? htmlspecialchars($filterTaluka) : 'All'; ?> |
            From: <?php echo $filterFromDate !== '' ? htmlspecialchars($filterFromDate) : '—'; ?> |
            To: <?php echo $filterToDate !== '' ? htmlspecialchars($filterToDate) : '—'; ?> |
            Date mode: <?php echo $filterDateMode === 'stage' ? 'Stage date' : 'Upload date'; ?>
        </div>
        <?php } ?>
    </div>

    <h4 class="font-weight-bold py-3 mb-0 msedcl-abstract-screen-title">MSEDCL SMART PROJECT ABSTRACT
        <span style="float:right;">
            <a href="export-abstract.php<?php echo $exportQuery !== '' ? '?' . htmlspecialchars($exportQuery) : ''; ?>" class="btn btn-success btn-sm msedcl-abstract-no-print mr-1"><i class="ion ion-md-download mr-1"></i> Export Excel</a>
            <button type="button" class="btn btn-secondary btn-sm msedcl-abstract-no-print" onclick="window.print();"><i class="lnr lnr-printer mr-1"></i> Print</button>
        </span>
    </h4>

    <div class="card msedcl-abstract-no-print mb-3" style="padding:10px;">
        <form method="get" action="">
            <div class="form-row">
                <div class="form-group col-md-2">
                    <label class="form-label">District</label>
                    <select class="form-control" name="District">
                        <option value="">All District</option>
                        <?php foreach ($districtRows as $dr) {
                            $d = $dr['District']; ?>
                        <option value="<?php echo htmlspecialchars($d); ?>" <?php if ($filterDistrict === $d) { ?>selected<?php } ?>><?php echo htmlspecialchars($d); ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <label class="form-label">Taluka</label>
                    <select class="form-control" name="Taluka">
                        <option value="">All Taluka</option>
                        <?php foreach ($talukaRows as $tr) {
                            $t = $tr['Taluka']; ?>
                        <option value="<?php echo htmlspecialchars($t); ?>" <?php if ($filterTaluka === $t) { ?>selected<?php } ?>><?php echo htmlspecialchars($t); ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <label class="form-label">From Date</label>
                    <input type="date" name="FromDate" class="form-control" value="<?php echo htmlspecialchars($filterFromDate); ?>">
                </div>
                <div class="form-group col-md-2">
                    <label class="form-label">To Date</label>
                    <input type="date" name="ToDate" class="form-control" value="<?php echo htmlspecialchars($filterToDate); ?>">
                </div>
                <div class="form-group col-md-2">
                    <label class="form-label">Date applies to</label>
                    <select class="form-control" name="DateMode">
                        <option value="upload" <?php if ($filterDateMode !== 'stage') { ?>selected<?php } ?>>Upload date</option>
                        <option value="stage" <?php if ($filterDateMode === 'stage') { ?>selected<?php } ?>>Stage date (PMSGY / Mahadiscom / Payment / Survey)</option>
                    </select>
                </div>
                <div class="form-group col-md-2" style="padding-top:28px;">
                    <button type="submit" name="Search" value="1" class="btn btn-primary btn-block">Search</button>
                </div>
                <?php if ($isSearch) { ?>
                <div class="form-group col-md-2" style="padding-top:28px;">
                    <a href="abstract.php" class="btn btn-info btn-block">Clear</a>
                </div>
                <?php } ?>
            </div>
        </form>
    </div>

    <div class="alert alert-light border small mb-3 msedcl-abstract-no-print">
        <?php if ($isSearch) { ?>
        <strong>Filters:</strong>
        District: <?php echo $filterDistrict !== '' ? htmlspecialchars($filterDistrict) : 'All'; ?> |
        Taluka: <?php echo $filterTaluka !== '' ? htmlspecialchars($filterTaluka) : 'All'; ?> |
        From: <?php echo $filterFromDate !== '' ? htmlspecialchars($filterFromDate) : '—'; ?> |
        To: <?php echo $filterToDate !== '' ? htmlspecialchars($filterToDate) : '—'; ?> |
        Date mode: <?php echo $filterDateMode === 'stage' ? 'Stage date' : 'Upload date'; ?>
        <br>
        <?php } ?>
        <strong>Counts:</strong> Each column shows customers at that workflow stage (same logic as Dashboard). Stage columns total <?php echo number_format($totPmsgy + $totMahadiscom + $totPayment + $totSurvey); ?> active records.
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped msedcl-abstract-table">
                    <thead class="thead-dark">
                        <tr>
                            <th>Sr no.</th>
                            <th>District</th>
                            <th class="text-right">PMSGY Portal<br><small class="font-weight-normal">Awaiting Mahadiscom</small></th>
                            <th class="text-right">Mahadiscom Portal<br><small class="font-weight-normal">Awaiting Payment</small></th>
                            <th class="text-right">Payment Done<br><small class="font-weight-normal">Survey Pending</small></th>
                            <th class="text-right">Survey Done</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (empty($rows)) {
                            echo '<tr><td colspan="6" class="text-center text-muted">No data for selected filters.</td></tr>';
                        } else {
                            $i = 1;
                            foreach ($rows as $row) {
                                ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td><?php echo htmlspecialchars((string) $row['District']); ?></td>
                            <td class="text-right"><?php echo msedclSmartAbstractCountCell($row['pmsgy_cnt'], 'pmsgy', (string) $row['District'], $abstractMeta); ?></td>
                            <td class="text-right"><?php echo msedclSmartAbstractCountCell($row['mahadiscom_cnt'], 'mahadiscom', (string) $row['District'], $abstractMeta); ?></td>
                            <td class="text-right"><?php echo msedclSmartAbstractCountCell($row['payment_cnt'], 'payment', (string) $row['District'], $abstractMeta); ?></td>
                            <td class="text-right"><?php echo msedclSmartAbstractCountCell($row['survey_cnt'], 'survey', (string) $row['District'], $abstractMeta); ?></td>
                        </tr>
                                <?php
                            }
                        }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2">Total</td>
                            <td class="text-right"><?php echo msedclSmartAbstractCountCell($totPmsgy, 'pmsgy', '', $abstractMeta); ?></td>
                            <td class="text-right"><?php echo msedclSmartAbstractCountCell($totMahadiscom, 'mahadiscom', '', $abstractMeta); ?></td>
                            <td class="text-right"><?php echo msedclSmartAbstractCountCell($totPayment, 'payment', '', $abstractMeta); ?></td>
                            <td class="text-right"><?php echo msedclSmartAbstractCountCell($totSurvey, 'survey', '', $abstractMeta); ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
</div>

<div class="modal fade msedcl-abstract-no-print" id="msedclAbstractRecordsModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Records</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../footer_script.php'; ?>
<script src="js/msedcl-smart-abstract.js"></script>
</div>
</div>
</body>
</html>
