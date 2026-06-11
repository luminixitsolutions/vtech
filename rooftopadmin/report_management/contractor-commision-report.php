<?php
session_start();
include_once '../config.php';
include_once '../auth.php';
include_once 'inc-contractor-payment.php';

$user_id = $_SESSION['Admin']['id'];
$MainPage = 'Reports';
$Page = 'Contractor-Commision-Report';
contractorPaymentEnsureTable($conn);

$projectId = isset($_GET['project_id']) ? (int) $_GET['project_id'] : 0;
$subheadId = isset($_GET['subhead_id']) ? (int) $_GET['subhead_id'] : 0;
$projectName = $projectId > 0 ? contractorBillingGetProjectName($conn, $projectId) : '';
$subheadName = $subheadId > 0 ? contractorBillingGetSubHeadName($conn, $subheadId) : '';

if ($projectId > 0 && $projectName === '') {
    header('Location: contractor-commision-report.php');
    exit;
}
if ($subheadId > 0 && ($subheadName === '' || $projectId <= 0)) {
    header('Location: contractor-commision-report.php' . ($projectId > 0 ? '?project_id=' . $projectId : ''));
    exit;
}

$viewLevel = 'projects';
if ($projectId > 0 && $subheadId > 0) {
    $viewLevel = 'contractors';
} elseif ($projectId > 0) {
    $viewLevel = 'subheads';
}

$pageTitle = 'Contractor Billing Report';
if ($viewLevel === 'subheads') {
    $pageTitle = $projectName . ' — Sub Projects';
} elseif ($viewLevel === 'contractors') {
    $pageTitle = $projectName . ' / ' . $subheadName . ' — Contractors';
}
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | Contractor Billing Report</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<?php include_once '../header_script.php'; ?>
</head>
<body>

<div class="layout-wrapper layout-2">
<div class="layout-inner">

<?php include_once 'report-sidebar.php'; ?>

<div class="layout-container">
<?php include_once '../top_header.php'; ?>

<div class="layout-content">
<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0"><?php echo htmlspecialchars($pageTitle); ?></h4>

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item<?php echo $viewLevel === 'projects' ? ' active' : ''; ?>">
            <?php if ($viewLevel === 'projects') { ?>
            All Projects
            <?php } else { ?>
            <a href="contractor-commision-report.php">All Projects</a>
            <?php } ?>
        </li>
        <?php if ($viewLevel !== 'projects') { ?>
        <li class="breadcrumb-item<?php echo $viewLevel === 'subheads' ? ' active' : ''; ?>">
            <?php if ($viewLevel === 'subheads') { ?>
            <?php echo htmlspecialchars($projectName); ?>
            <?php } else { ?>
            <a href="contractor-commision-report.php?project_id=<?php echo $projectId; ?>"><?php echo htmlspecialchars($projectName); ?></a>
            <?php } ?>
        </li>
        <?php } ?>
        <?php if ($viewLevel === 'contractors') { ?>
        <li class="breadcrumb-item active"><?php echo htmlspecialchars($subheadName); ?></li>
        <?php } ?>
    </ol>
</nav>

<p class="text-muted mb-3">
    <a href="contractor-payment-dashboard.php" class="btn btn-sm btn-primary">Contractor Payment Dashboard</a>
    <a href="contractor-payment-add.php" class="btn btn-sm btn-outline-primary ml-1">Record Payment</a>
</p>

<div class="card" style="padding: 10px;">
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
    <thead>
        <tr>
            <th>#</th>
            <?php if ($viewLevel === 'projects') { ?>
            <th>Project Name</th>
            <th>Total Sites</th>
            <th>View</th>
            <?php } elseif ($viewLevel === 'subheads') { ?>
            <th>Sub Project Name</th>
            <th>Total Sites</th>
            <th>View</th>
            <?php } else { ?>
            <th>Contractor Name</th>
            <th>Total Sites</th>
            <th>Total Commission</th>
            <th>View</th>
            <?php } ?>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 1;
        if ($viewLevel === 'projects') {
            $projects = contractorBillingProjectsList($conn);
            foreach ($projects as $row) {
                $pid = (int) $row['id'];
                ?>
        <tr>
            <td><?php echo $i++; ?></td>
            <td><?php echo htmlspecialchars((string) $row['Name']); ?></td>
            <td><?php echo (int) ($row['site_count'] ?? 0); ?></td>
            <td><a href="contractor-commision-report.php?project_id=<?php echo $pid; ?>" class="btn btn-sm btn-outline-primary">Sub Projects</a></td>
        </tr>
                <?php
            }
        } elseif ($viewLevel === 'subheads') {
            $subheads = contractorBillingSubHeadsList($conn, $projectId);
            foreach ($subheads as $row) {
                $sid = (int) $row['id'];
                ?>
        <tr>
            <td><?php echo $i++; ?></td>
            <td><?php echo htmlspecialchars((string) $row['Name']); ?></td>
            <td><?php echo (int) ($row['site_count'] ?? 0); ?></td>
            <td><a href="contractor-commision-report.php?project_id=<?php echo $projectId; ?>&amp;subhead_id=<?php echo $sid; ?>" class="btn btn-sm btn-outline-primary">Contractors</a></td>
        </tr>
                <?php
            }
            if (count($subheads) === 0) {
                ?>
        <tr>
            <td colspan="4" class="text-center text-muted">No sub projects found for this project.</td>
        </tr>
                <?php
            }
        } else {
            $contractors = contractorBillingContractorsByProjectSubHead($conn, $projectId, $subheadId);
            foreach ($contractors as $row) {
                $cid = (int) $row['id'];
                $cname = trim((string) ($row['Fname'] ?? '') . ' ' . (string) ($row['Lname'] ?? ''));
                $detailUrl = 'view-commision-details.php?id=' . $cid
                    . '&amp;project_id=' . $projectId
                    . '&amp;subhead_id=' . $subheadId;
                ?>
        <tr>
            <td><?php echo $i++; ?></td>
            <td><?php echo htmlspecialchars($cname); ?></td>
            <td><?php echo (int) ($row['total_sites'] ?? 0); ?></td>
            <td>&#8377;<?php echo number_format((float) ($row['total_commission'] ?? 0), 0); ?></td>
            <td><a href="<?php echo $detailUrl; ?>" class="btn btn-sm btn-primary">View</a></td>
        </tr>
                <?php
            }
            if (count($contractors) === 0) {
                ?>
        <tr>
            <td colspan="5" class="text-center text-muted">No contractor billing records found for this sub project.</td>
        </tr>
                <?php
            }
        }
        ?>
    </tbody>
</table>
</div>
</div>
</div>

<?php include_once '../footer.php'; ?>
</div>
</div>
</div>

<div class="layout-overlay layout-sidenav-toggle"></div>
</div>

<?php include_once '../footer_script.php'; ?>
<script>
$(function() {
    var orderCol = <?php echo $viewLevel === 'contractors' ? 3 : ($viewLevel === 'projects' ? 2 : 2); ?>;
    $('#example').DataTable({
        order: [[orderCol, 'desc']],
        pageLength: 25,
        dom: 'Bfrtip',
        buttons: ['excelHtml5']
    });
});
</script>
</body>
</html>
