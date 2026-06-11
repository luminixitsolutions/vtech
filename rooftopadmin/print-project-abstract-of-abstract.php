<?php
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = 'Installation';
$Page = 'Installation';

$projId = (int) $_GET['projid'];
$projectHead = getRecord("SELECT Name FROM tbl_rooftop_common_master WHERE id='$projId' AND Roll=24 LIMIT 1");
$ProjectHeadName = $projectHead['Name'] ?? '';
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
    <title><?php echo $Proj_Title; ?>_<?php echo htmlspecialchars($ProjectHeadName); ?>_project_abstract_of_abstracts</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <link rel="icon" type="image/x-icon" href="<?php echo $SiteUrl; ?>/assets/img/favicon.ico">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $SiteUrl; ?>/assets/css/bootstrap-material2.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl; ?>/assets/css/shreerang-material.css">
</head>
<style>
    th, td {
        font-size: 11px;
        border: 1px solid gray;
        text-align: center;
    }
    th {
        background-color: #fee2d6;
    }
</style>
<body style="background-color:#fff;">

<div class="card" style="padding: 10px;">
    <div align="center">
        <h5>ABSTRACT OF ABSTRACTS REPORT</h5>
        <h5><?php echo htmlspecialchars($ProjectHeadName); ?> UPDATE AS ON DATE <?php echo date('d.m.Y'); ?></h5>
    </div>

<?php
function rooftopAbstractSubHeadCount($val, $subheadId, $val2 = '')
{
    global $conn;
    $projId = (int) $_GET['projid'];
    $subheadId = (int) $subheadId;
    $scope = "tu.ProjectType=2 AND tu.Roll=5 AND tu.ProjectId='$projId' AND tu.ProjectSubHeadId='$subheadId'";

    if ($val === 'totapp') {
        $sql2 = "SELECT tu.* FROM tbl_users tu WHERE $scope";
    } elseif ($val === 'surveydone' || $val === 'surveypending') {
        $val2Esc = $conn->real_escape_string((string) $val2);
        $sql2 = "SELECT tu.* FROM tbl_users tu WHERE tu.FieldSurveyDetails='$val2Esc' AND $scope";
    } elseif ($val === 'surveyrejected') {
        $val2Esc = $conn->real_escape_string((string) $val2);
        $sql2 = "SELECT tu.* FROM tbl_users tu WHERE tu.SurveyMatch='$val2Esc' AND $scope";
    } elseif ($val === 'dispatch') {
        $sql2 = "SELECT tu.* FROM tbl_rooftop_sell ts INNER JOIN tbl_users tu ON tu.id=ts.CustId WHERE $scope";
    } elseif ($val === 'installation') {
        $val2Esc = $conn->real_escape_string((string) $val2);
        $sql2 = "SELECT tu.* FROM tbl_installations ts INNER JOIN tbl_users tu ON tu.id=ts.CustId WHERE ts.InstallStatus='$val2Esc' AND $scope";
    } elseif ($val === 'meterinstall') {
        $val2Esc = $conn->real_escape_string((string) $val2);
        $sql2 = "SELECT tu.* FROM tbl_installations ts INNER JOIN tbl_users tu ON tu.id=ts.CustId WHERE ts.MeterInstDiscom='$val2Esc' AND $scope";
    } elseif ($val === 'dataupload') {
        $val2Esc = $conn->real_escape_string((string) $val2);
        $sql2 = "SELECT tu.* FROM tbl_installations ts INNER JOIN tbl_users tu ON tu.id=ts.CustId WHERE ts.DataUploadStatus='$val2Esc' AND $scope";
    } elseif ($val === 'inspection') {
        $val2Esc = $conn->real_escape_string((string) $val2);
        $sql2 = "SELECT tu.* FROM tbl_installations ts INNER JOIN tbl_users tu ON tu.id=ts.CustId WHERE ts.PoInspection='$val2Esc' AND $scope";
    } elseif ($val === 'inspectiondis') {
        $val2Esc = $conn->real_escape_string((string) $val2);
        $sql2 = "SELECT tu.* FROM tbl_installations ts INNER JOIN tbl_users tu ON tu.id=ts.CustId WHERE ts.InspectionDiscrepancy='$val2Esc' AND $scope";
    } elseif ($val === 'paymentstatus') {
        $val2Esc = $conn->real_escape_string((string) $val2);
        $sql2 = "SELECT tu.* FROM tbl_installations ts INNER JOIN tbl_users tu ON tu.id=ts.CustId WHERE ts.PaymentStatus='$val2Esc' AND $scope";
    } else {
        return 0;
    }

    return getRow($sql2);
}

$totals = [
    'totapp' => 0, 'surveydone' => 0, 'surveyrejected' => 0, 'surveypending' => 0,
    'dispatch' => 0, 'installationdone' => 0, 'installationpending' => 0,
    'meterinstdone' => 0, 'meterinstpending' => 0, 'datauploaddone' => 0, 'datauploadpending' => 0,
    'inspectiondone' => 0, 'inspectiondis' => 0, 'inspectionpending' => 0,
    'completepayment' => 0, 'partialpayment' => 0,
];

$sql = "SELECT * FROM tbl_rooftop_project_sub_head WHERE UnderBy='$projId'";
if (!empty($_REQUEST['SubHeadProjectId'])) {
    $subHeadProjectId = $_REQUEST['SubHeadProjectId'];
    $replaceSubHead = str_replace(',', "','", $subHeadProjectId);
    if ($subHeadProjectId !== 'all') {
        $sql .= " AND id IN('$replaceSubHead')";
    }
}
$sql .= ' ORDER BY Name ASC';
$subHeadRows = getList($sql);
if (!is_array($subHeadRows)) {
    $subHeadRows = [];
}
?>

<table style="width:100%">
    <thead>
        <tr>
            <th>Sr No</th>
            <th>Sub Project Head</th>
            <th>Total<br>Application<br>Received</th>
            <th>Survey Done</th>
            <th>Rejected</th>
            <th>Survey Pending</th>
            <th>Material Dispatch</th>
            <th>Installation Done</th>
            <th>Installation Pending</th>
            <th>Meter Installed Done</th>
            <th>Meter Installed Pending</th>
            <th>Data Upload Done</th>
            <th>Data Upload Pending</th>
            <th>Inspection Done</th>
            <th>Inspection Discrepancy</th>
            <th>Inspection Pending</th>
            <th>Complete Payment Received</th>
            <th>Partial Payment Received</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 1;
        foreach ($subHeadRows as $result) {
            $subheadId = (int) $result['id'];
            $rowData = [
                'totapp' => rooftopAbstractSubHeadCount('totapp', $subheadId),
                'surveydone' => rooftopAbstractSubHeadCount('surveydone', $subheadId, '1'),
                'surveyrejected' => rooftopAbstractSubHeadCount('surveyrejected', $subheadId, '0'),
                'surveypending' => rooftopAbstractSubHeadCount('surveypending', $subheadId, '0'),
                'dispatch' => rooftopAbstractSubHeadCount('dispatch', $subheadId),
                'installationdone' => rooftopAbstractSubHeadCount('installation', $subheadId, 'Yes'),
                'installationpending' => rooftopAbstractSubHeadCount('installation', $subheadId, 'No'),
                'meterinstdone' => rooftopAbstractSubHeadCount('meterinstall', $subheadId, 'Yes'),
                'meterinstpending' => rooftopAbstractSubHeadCount('meterinstall', $subheadId, 'No'),
                'datauploaddone' => rooftopAbstractSubHeadCount('dataupload', $subheadId, 'Yes'),
                'datauploadpending' => rooftopAbstractSubHeadCount('dataupload', $subheadId, 'No'),
                'inspectiondone' => rooftopAbstractSubHeadCount('inspection', $subheadId, 'Yes'),
                'inspectiondis' => rooftopAbstractSubHeadCount('inspectiondis', $subheadId, 'Yes'),
                'inspectionpending' => rooftopAbstractSubHeadCount('inspection', $subheadId, 'No'),
                'completepayment' => rooftopAbstractSubHeadCount('paymentstatus', $subheadId, '2'),
                'partialpayment' => rooftopAbstractSubHeadCount('paymentstatus', $subheadId, '1'),
            ];
            foreach ($rowData as $key => $value) {
                $totals[$key] += (int) $value;
            }
        ?>
        <tr>
            <td><?php echo $i; ?></td>
            <td style="font-weight:600;text-align:left;padding:3px;"><?php echo htmlspecialchars($result['Name']); ?></td>
            <td><a href="total-beneficiary.php?roll=totapp&projid=<?php echo $projId; ?>&subheadid=<?php echo $subheadId; ?>&title=Total Application Received" target="_blank"><?php echo $rowData['totapp']; ?></a></td>
            <td><a href="total-beneficiary.php?roll=surveydone&projid=<?php echo $projId; ?>&val=1&subheadid=<?php echo $subheadId; ?>&title=Survey Done" target="_blank"><?php echo $rowData['surveydone']; ?></a></td>
            <td><a href="total-beneficiary.php?roll=surveyrejected&projid=<?php echo $projId; ?>&val=0&subheadid=<?php echo $subheadId; ?>&title=Survey Rejected" target="_blank"><?php echo $rowData['surveyrejected']; ?></a></td>
            <td style="background-color:#fee2d6;"><a href="total-beneficiary.php?roll=surveypending&projid=<?php echo $projId; ?>&val=0&subheadid=<?php echo $subheadId; ?>&title=Survey Pending" target="_blank"><?php echo $rowData['surveypending']; ?></a></td>
            <td><a href="total-beneficiary.php?roll=dispatch&projid=<?php echo $projId; ?>&subheadid=<?php echo $subheadId; ?>&title=Material Dispatch" target="_blank"><?php echo $rowData['dispatch']; ?></a></td>
            <td><a href="total-beneficiary.php?roll=installation&projid=<?php echo $projId; ?>&val=Yes&subheadid=<?php echo $subheadId; ?>&title=Installation Done" target="_blank"><?php echo $rowData['installationdone']; ?></a></td>
            <td style="background-color:#fee2d6;"><a href="total-beneficiary.php?roll=installation&projid=<?php echo $projId; ?>&val=No&subheadid=<?php echo $subheadId; ?>&title=Installation Pending" target="_blank"><?php echo $rowData['installationpending']; ?></a></td>
            <td><a href="total-beneficiary.php?roll=meterinstall&projid=<?php echo $projId; ?>&val=Yes&subheadid=<?php echo $subheadId; ?>&title=Meter Installed Done" target="_blank"><?php echo $rowData['meterinstdone']; ?></a></td>
            <td><a href="total-beneficiary.php?roll=meterinstall&projid=<?php echo $projId; ?>&val=No&subheadid=<?php echo $subheadId; ?>&title=Meter Installed Pending" target="_blank"><?php echo $rowData['meterinstpending']; ?></a></td>
            <td><a href="total-beneficiary.php?roll=dataupload&projid=<?php echo $projId; ?>&val=Yes&subheadid=<?php echo $subheadId; ?>&title=Data Upload Done" target="_blank"><?php echo $rowData['datauploaddone']; ?></a></td>
            <td style="background-color:#fee2d6;"><a href="total-beneficiary.php?roll=dataupload&projid=<?php echo $projId; ?>&val=No&subheadid=<?php echo $subheadId; ?>&title=Data Upload Pending" target="_blank"><?php echo $rowData['datauploadpending']; ?></a></td>
            <td><a href="total-beneficiary.php?roll=inspection&projid=<?php echo $projId; ?>&val=Yes&subheadid=<?php echo $subheadId; ?>&title=Inspection Done" target="_blank"><?php echo $rowData['inspectiondone']; ?></a></td>
            <td><a href="total-beneficiary.php?roll=inspectiondis&projid=<?php echo $projId; ?>&val=Yes&subheadid=<?php echo $subheadId; ?>&title=Inspection Discrepancy" target="_blank"><?php echo $rowData['inspectiondis']; ?></a></td>
            <td style="background-color:#fee2d6;"><a href="total-beneficiary.php?roll=inspection&projid=<?php echo $projId; ?>&val=No&subheadid=<?php echo $subheadId; ?>&title=Inspection Pending" target="_blank"><?php echo $rowData['inspectionpending']; ?></a></td>
            <td><a href="total-beneficiary.php?roll=paymentstatus&projid=<?php echo $projId; ?>&val=2&subheadid=<?php echo $subheadId; ?>&title=Complete Payment Received" target="_blank"><?php echo $rowData['completepayment']; ?></a></td>
            <td><a href="total-beneficiary.php?roll=paymentstatus&projid=<?php echo $projId; ?>&val=1&subheadid=<?php echo $subheadId; ?>&title=Partial Payment Received" target="_blank"><?php echo $rowData['partialpayment']; ?></a></td>
        </tr>
        <?php $i++; } ?>
        <?php if (count($subHeadRows) > 1) { ?>
        <tr>
            <th colspan="2">TOTAL</th>
            <?php foreach ($totals as $totalValue) { ?>
            <th><?php echo (int) $totalValue; ?></th>
            <?php } ?>
        </tr>
        <?php } ?>
    </tbody>
</table>
</div>

</body>
</html>
