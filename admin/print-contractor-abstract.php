<?php
if (isset($_GET['debug'])) {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
}

session_start();
include_once 'config.php';
include_once 'auth.php';

$user_id = isset($_SESSION['Admin']['id']) ? $_SESSION['Admin']['id'] : 0;
$MainPage = "Installation";
$Page = "Installation";

$projectId = isset($_GET['projid']) ? (int) $_GET['projid'] : 0;
$subheadId = isset($_GET['SubHeadProjectId']) ? (int) $_GET['SubHeadProjectId'] : 0;
$districtFilter = isset($_GET['District']) ? (string) $_GET['District'] : 'all';

$incProject = __DIR__ . '/inc-project-abstract-queries.php';
$incContractor = __DIR__ . '/inc-contractor-abstract-queries.php';
if (!is_file($incProject) || !is_file($incContractor)) {
    header('Content-Type: text/plain; charset=utf-8');
    http_response_code(500);
    echo "Contractor abstract files missing on server.\n";
    if (!is_file($incProject)) {
        echo "- inc-project-abstract-queries.php\n";
    }
    if (!is_file($incContractor)) {
        echo "- inc-contractor-abstract-queries.php\n";
    }
    exit;
}

require_once $incProject;
require_once $incContractor;

$sql = "SELECT * FROM tbl_project_sub_head WHERE id='$subheadId'";
$row = getRecord($sql);
$Projectname = (is_array($row) && isset($row['Name'])) ? $row['Name'] : '';

@set_time_limit(300);
@ini_set('memory_limit', '512M');

$totalSites = 0;
$districtRows = array();

try {
    $totalSites = contractorAbstractTotalSites($conn, $projectId, $subheadId, $districtFilter);
    $districtRows = contractorAbstractDistricts($conn, $projectId, $subheadId, $districtFilter);
    if (empty($districtRows) && $totalSites > 0) {
        $districtRows = contractorAbstractDistricts($conn, $projectId, $subheadId, 'all');
    }
} catch (Exception $e) {
    header('Content-Type: text/plain; charset=utf-8');
    http_response_code(500);
    echo 'Contractor abstract error: ' . $e->getMessage();
    exit;
}

if (!is_array($districtRows)) {
    $districtRows = array();
}
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">

<head>
    <title><?php echo $Proj_Title; ?>_<?php echo $Projectname;?>_contractor_abstract </title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="" />
   <link rel="icon" type="image/x-icon" href="<?php echo $SiteUrl;?>/assets/img/favicon.ico">
<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/fontawesome.css">
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/ionicons.css">
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/linearicons.css">
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/feather.css">
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/css/bootstrap-material2.css">
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/css/shreerang-material.css">
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/css/uikit.css">
</head>
<style>
    th, td{
        font-size:10px;
        border:1px solid gray;
        text-align:center;
        vertical-align:middle;
        padding:3px;
    }
    th {
        background-color:#fee2d6;
    }
    .district-name {
        font-weight:600;
        text-align:left;
    }
    .contractor-name {
        text-align:left;
    }
    .pending-col {
        background-color:#fee2d6;
    }
    td {
        color:#000;
    }
    .count-value {
        color:#000;
        text-decoration:none;
        font-weight:600;
    }
    td a.count-value:hover {
        color:#007bff;
        text-decoration:underline;
    }
</style>
<body style="background-color:#fff;">

<div class="card" style="padding: 10px;">
<div align="center">
    <h5><?php echo strtoupper(htmlspecialchars($Projectname)); ?> - CONTRACTORS ABSTRACT (<?php echo $totalSites; ?> SITES) AS ON DATE <?php echo date('d-m-Y'); ?></h5>
</div>

<div style="overflow-x:auto;">
<table style="width:100%; border-collapse:collapse;">
    <thead>
        <tr>
            <th>SR NO</th>
            <th>DISTRICT</th>
            <th>APPLICATION<br>RECEIVED</th>
            <th>WORK ORDER<br>DONE</th>
            <th>WORK ORDER<br>PENDING</th>
            <th>JSR<br>PENDING</th>
            <th>DISPATCH<br>PENDING</th>
            <th>CONTRACTORS<br>NAME</th>
            <th>MATERIAL<br>DISPATCH</th>
            <th>CANCLE SITES<br>OF LOT 3</th>
            <th>I &amp; C<br>DONE</th>
            <th>I &amp; C<br>PENDING</th>
            <th>DATA UPLOAD<br>DONE</th>
            <th>DATA UPLOAD<br>PENDING</th>
            <th>INSPECTION<br>DONE</th>
            <th>INSPECTION<br>PENDING</th>
            <th>TODAY<br>INSTALLATION<br>DONE</th>
            <th>I &amp; C PLANNING<br>TODAY</th>
            <th>DATA<br>UPLOAD</th>
            <th>INSPECTION<br>DONE</th>
            <th>REMARK</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $srNo = 1;
        foreach ($districtRows as $districtRow) {
            $dist = trim((string) (isset($districtRow['District']) ? $districtRow['District'] : ''));
            if ($dist === '') {
                continue;
            }

            $contractors = contractorAbstractContractorsByDistrict($conn, $projectId, $subheadId, $dist);
            if (!is_array($contractors) || empty($contractors)) {
                $contractors = array(array('id' => 0, 'Fname' => '-', 'Lname' => ''));
            }

            $rowCount = count($contractors);
            $appReceived = contractorAbstractCount($conn, 'app_received', $projectId, $subheadId, $dist);
            $workOrderDone = contractorAbstractCount($conn, 'work_order_done', $projectId, $subheadId, $dist);
            $workOrderPending = contractorAbstractCount($conn, 'work_order_pending', $projectId, $subheadId, $dist);
            $jsrPending = contractorAbstractCount($conn, 'jsr_pending', $projectId, $subheadId, $dist);
            $dispatchPending = contractorAbstractCount($conn, 'dispatch_pending', $projectId, $subheadId, $dist);

            foreach ($contractors as $index => $contractor) {
                $contractorId = (int) (isset($contractor['id']) ? $contractor['id'] : 0);
                $contractorLabel = contractorAbstractContractorName($contractor);
                if ($contractorLabel === '' || $contractorLabel === '-') {
                    $contractorDisplay = '-';
                    $contractorLinkName = '';
                } else {
                    $contractorDisplay = strtoupper($contractorLabel);
                    $contractorLinkName = $contractorDisplay;
                }

                $materialDispatch = $contractorId > 0 ? contractorAbstractCount($conn, 'material_dispatch', $projectId, $subheadId, $dist, $contractorId) : 0;
                $cancelSites = $contractorId > 0 ? contractorAbstractCount($conn, 'cancel_sites', $projectId, $subheadId, $dist, $contractorId) : 0;
                $icDone = $contractorId > 0 ? contractorAbstractCount($conn, 'ic_done', $projectId, $subheadId, $dist, $contractorId) : 0;
                $icPending = $contractorId > 0 ? contractorAbstractCount($conn, 'ic_pending', $projectId, $subheadId, $dist, $contractorId) : 0;
                $dataUploadDone = $contractorId > 0 ? contractorAbstractCount($conn, 'data_upload_done', $projectId, $subheadId, $dist, $contractorId) : 0;
                $dataUploadPending = $contractorId > 0 ? contractorAbstractCount($conn, 'data_upload_pending', $projectId, $subheadId, $dist, $contractorId) : 0;
                $inspectionDone = $contractorId > 0 ? contractorAbstractCount($conn, 'inspection_done', $projectId, $subheadId, $dist, $contractorId) : 0;
                $inspectionPending = $contractorId > 0 ? contractorAbstractCount($conn, 'inspection_pending', $projectId, $subheadId, $dist, $contractorId) : 0;
                $todayInstallation = $contractorId > 0 ? contractorAbstractCount($conn, 'today_installation', $projectId, $subheadId, $dist, $contractorId) : 0;
                $icPlanningToday = $contractorId > 0 ? contractorAbstractCount($conn, 'ic_planning_today', $projectId, $subheadId, $dist, $contractorId) : 0;
        ?>
        <tr>
            <?php if ($index === 0) { ?>
            <td rowspan="<?php echo $rowCount; ?>"><?php echo $srNo; ?></td>
            <td rowspan="<?php echo $rowCount; ?>" class="district-name"><?php echo htmlspecialchars($dist); ?></td>
            <td rowspan="<?php echo $rowCount; ?>"><?php echo contractorAbstractCountLink($appReceived, 'app_received', $projectId, $subheadId, $dist); ?></td>
            <td rowspan="<?php echo $rowCount; ?>"><?php echo contractorAbstractCountLink($workOrderDone, 'work_order_done', $projectId, $subheadId, $dist); ?></td>
            <td rowspan="<?php echo $rowCount; ?>"><?php echo contractorAbstractCountLink($workOrderPending, 'work_order_pending', $projectId, $subheadId, $dist); ?></td>
            <td rowspan="<?php echo $rowCount; ?>" class="pending-col"><?php echo contractorAbstractCountLink($jsrPending, 'jsr_pending', $projectId, $subheadId, $dist); ?></td>
            <td rowspan="<?php echo $rowCount; ?>" class="pending-col"><?php echo contractorAbstractCountLink($dispatchPending, 'dispatch_pending', $projectId, $subheadId, $dist); ?></td>
            <?php } ?>
            <td class="contractor-name"><?php echo htmlspecialchars($contractorDisplay); ?></td>
            <td><?php echo contractorAbstractCountLink($materialDispatch, 'material_dispatch', $projectId, $subheadId, $dist, $contractorId, $contractorLinkName); ?></td>
            <td><?php echo contractorAbstractCountLink($cancelSites, 'cancel_sites', $projectId, $subheadId, $dist, $contractorId, $contractorLinkName); ?></td>
            <td><?php echo contractorAbstractCountLink($icDone, 'ic_done', $projectId, $subheadId, $dist, $contractorId, $contractorLinkName); ?></td>
            <td class="pending-col"><?php echo contractorAbstractCountLink($icPending, 'ic_pending', $projectId, $subheadId, $dist, $contractorId, $contractorLinkName); ?></td>
            <td><?php echo contractorAbstractCountLink($dataUploadDone, 'data_upload_done', $projectId, $subheadId, $dist, $contractorId, $contractorLinkName); ?></td>
            <td class="pending-col"><?php echo contractorAbstractCountLink($dataUploadPending, 'data_upload_pending', $projectId, $subheadId, $dist, $contractorId, $contractorLinkName); ?></td>
            <td><?php echo contractorAbstractCountLink($inspectionDone, 'inspection_done', $projectId, $subheadId, $dist, $contractorId, $contractorLinkName); ?></td>
            <td class="pending-col"><?php echo contractorAbstractCountLink($inspectionPending, 'inspection_pending', $projectId, $subheadId, $dist, $contractorId, $contractorLinkName); ?></td>
            <td><?php echo contractorAbstractCountLink($todayInstallation, 'today_installation', $projectId, $subheadId, $dist, $contractorId, $contractorLinkName); ?></td>
            <td><?php echo contractorAbstractCountLink($icPlanningToday, 'ic_planning_today', $projectId, $subheadId, $dist, $contractorId, $contractorLinkName); ?></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <?php
            }
            $srNo++;
        }
        ?>
    </tbody>
</table>
</div>
</div>

</body>
</html>
