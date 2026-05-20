<?php
session_start();
include_once 'config.php';
include_once 'auth.php';
include_once 'inc-service-abstract-data.php';

$user_id = $_SESSION['Admin']['id'];
$MainPage = 'Service';
$Page = 'Service-Abstract';

$filters = serviceAbstractFiltersFromRequest();
$abstract = getServiceAbstractData($filters);
$rows = $abstract['rows'];
$totals = $abstract['totals'];
$reportTitle = $abstract['title'];

$projectHeads = serviceAbstractGetProjectHeads();
$subHeads = serviceAbstractGetSubHeads($filters['projid']);
$districtOptions = serviceAbstractGetDistrictOptions($filters);
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
    <title><?php echo $Proj_Title; ?> | Service Abstract</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <?php include_once 'header_script.php'; ?>
</head>
<style>
    .service-abstract-table th,
    .service-abstract-table td {
        font-size: 11px;
        border: 1px solid #333;
        text-align: center;
        vertical-align: middle;
        padding: 6px 4px;
    }
    .service-abstract-table th {
        background-color: #e9ecef;
        font-weight: 700;
    }
    .service-abstract-table td.district-col {
        text-align: left;
        font-weight: 600;
    }
    .service-abstract-table tr.total-row td {
        background-color: #e9ecef;
        font-weight: 700;
    }
    .service-abstract-table a {
        color: inherit;
        text-decoration: underline;
    }
    .service-abstract-title {
        text-align: center;
        font-weight: 700;
        margin-bottom: 4px;
    }
    .service-abstract-subtitle {
        text-align: center;
        font-size: 13px;
        color: #555;
        margin-bottom: 16px;
    }
</style>
<body>
<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <?php include_once 'sidebar.php'; ?>
        <div class="layout-container">
            <?php include_once 'top_header.php'; ?>
            <div class="layout-content">
                <div class="container-fluid flex-grow-1 container-p-y">
                    <h4 class="font-weight-bold py-3 mb-0">Service Abstract</h4>

                    <div class="card mb-3" style="padding: 12px;">
                        <form method="get" id="abstractFilterForm" class="form-row align-items-end">
                            <div class="form-group col-md-3 mb-2">
                                <label class="form-label">Project scope</label>
                                <select name="scope" class="form-control">
                                    <option value="mtskpy" <?php if ($filters['scope'] === 'mtskpy') { ?>selected<?php } ?>>MTSKPY (MSEDCL)</option>
                                    <option value="msedcl" <?php if ($filters['scope'] === 'msedcl') { ?>selected<?php } ?>>All MSEDCL</option>
                                    <option value="all" <?php if ($filters['scope'] === 'all') { ?>selected<?php } ?>>All service projects</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3 mb-2">
                                <label class="form-label">Project head</label>
                                <select name="projid" id="projid" class="form-control select2-demo" onchange="loadSubHeads(this.value)">
                                    <option value="">All Project Head</option>
                                    <?php foreach ($projectHeads as $ph) { ?>
                                    <option value="<?php echo (int) $ph['id']; ?>" <?php if ($filters['projid'] == $ph['id']) { ?>selected<?php } ?>><?php echo htmlspecialchars($ph['Name']); ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group col-md-3 mb-2">
                                <label class="form-label">Sub project head</label>
                                <select name="subheadid" id="subheadid" class="form-control select2-demo">
                                    <option value="">All Sub Project Head</option>
                                    <?php foreach ($subHeads as $sh) { ?>
                                    <option value="<?php echo (int) $sh['id']; ?>" <?php if ($filters['subheadid'] == $sh['id']) { ?>selected<?php } ?>><?php echo htmlspecialchars($sh['Name']); ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group col-md-3 mb-2">
                                <label class="form-label">District</label>
                                <select name="district" id="district" class="form-control select2-demo">
                                    <option value="">All District</option>
                                    <?php foreach ($districtOptions as $distOpt) { ?>
                                    <option value="<?php echo htmlspecialchars($distOpt); ?>" <?php if ($filters['district'] === $distOpt) { ?>selected<?php } ?>><?php echo htmlspecialchars($distOpt); ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group col-md-12 mb-0">
                                <button type="submit" class="btn btn-primary">Search</button>
                                <a href="service-abstract.php" class="btn btn-secondary ml-1">Clear</a>
                                <a href="<?php echo htmlspecialchars(serviceAbstractExportUrl($filters)); ?>" class="btn btn-success ml-1">Export to Excel</a>
                            </div>
                        </form>
                    </div>

                    <div class="card" style="padding: 12px;">
                        <div class="service-abstract-title">VTECH SUNSYSTEMS PVT LTD</div>
                        <div class="service-abstract-title"><?php echo htmlspecialchars($reportTitle); ?></div>
                        <div class="service-abstract-subtitle">Update as on <?php echo date('d.m.Y'); ?></div>

                        <div class="table-responsive">
                            <table class="table table-bordered service-abstract-table mb-0">
                                <thead>
                                    <tr>
                                        <th>DISTRICT</th>
                                        <th>Total Complaints</th>
                                        <th>Total complaints closed</th>
                                        <th>TODAY COMPLAINTS ADD</th>
                                        <th>Complaints Hold due to the material issue</th>
                                        <th>Total complaints pending</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($rows as $row) {
                                        $dist = $row['district'];
                                    ?>
                                    <tr>
                                        <td class="district-col"><?php echo htmlspecialchars($dist); ?></td>
                                        <td><a href="<?php echo serviceAbstractListUrl($dist, $filters, ''); ?>" target="_blank"><?php echo (int) $row['total_complaints']; ?></a></td>
                                        <td><a href="<?php echo serviceAbstractListUrl($dist, $filters, 'closed'); ?>" target="_blank"><?php echo (int) $row['total_closed']; ?></a></td>
                                        <td><a href="<?php echo serviceAbstractListUrl($dist, $filters, 'today'); ?>" target="_blank"><?php echo (int) $row['today_added']; ?></a></td>
                                        <td><a href="<?php echo serviceAbstractListUrl($dist, $filters, 'material'); ?>" target="_blank"><?php echo (int) $row['material_hold']; ?></a></td>
                                        <td><a href="<?php echo serviceAbstractListUrl($dist, $filters, 'pending'); ?>" target="_blank"><?php echo (int) $row['total_pending']; ?></a></td>
                                    </tr>
                                    <?php } ?>
                                    <?php if (count($rows) > 1) { ?>
                                    <tr class="total-row">
                                        <td class="district-col"><?php echo htmlspecialchars($totals['district']); ?></td>
                                        <td><a href="<?php echo serviceAbstractListUrl('', $filters, ''); ?>" target="_blank"><?php echo (int) $totals['total_complaints']; ?></a></td>
                                        <td><a href="<?php echo serviceAbstractListUrl('', $filters, 'closed'); ?>" target="_blank"><?php echo (int) $totals['total_closed']; ?></a></td>
                                        <td><a href="<?php echo serviceAbstractListUrl('', $filters, 'today'); ?>" target="_blank"><?php echo (int) $totals['today_added']; ?></a></td>
                                        <td><a href="<?php echo serviceAbstractListUrl('', $filters, 'material'); ?>" target="_blank"><?php echo (int) $totals['material_hold']; ?></a></td>
                                        <td><a href="<?php echo serviceAbstractListUrl('', $filters, 'pending'); ?>" target="_blank"><?php echo (int) $totals['total_pending']; ?></a></td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <p class="text-muted small mt-3 mb-0">
                            Filters apply to beneficiary complaints (Project Type = Pump). Project head / sub head override scope when selected.
                            <strong>Close</strong> = closed; <strong>In Process</strong> = material hold; total pending = not closed.
                        </p>
                    </div>
                </div>
                <?php include_once 'footer.php'; ?>
            </div>
        </div>
    </div>
    <div class="layout-overlay layout-sidenav-toggle"></div>
</div>
<?php include_once 'footer_script.php'; ?>
<script type="text/javascript">
function loadSubHeads(projId) {
    var $sub = $('#subheadid');
    if (!projId) {
        $sub.html('<option value="">All Sub Project Head</option>');
        return;
    }
    $.ajax({
        type: 'POST',
        url: 'ajax_files/ajax_dropdown.php',
        data: { action: 'getSubHead', id: projId },
        success: function (html) {
            var opts = '<option value="">All Sub Project Head</option>';
            $(html).find('option').each(function () {
                if (!$(this).prop('disabled')) {
                    opts += this.outerHTML;
                }
            });
            $sub.html(opts);
        }
    });
}
</script>
</body>
</html>
