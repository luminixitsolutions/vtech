<?php
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Installation";
$Page = "Installation";

$sql = "SELECT * FROM tbl_rooftop_project_sub_head WHERE id='".(int)$_GET['SubHeadProjectId']."'";
$row = getRecord($sql);
$SubHeadName = $row['Name'] ?? '';
$projId = (int) $_GET['projid'];
$projectHead = getRecord("SELECT Name FROM tbl_rooftop_common_master WHERE id='$projId' AND Roll=24 LIMIT 1");
$ProjectHeadName = $projectHead['Name'] ?? '';
$ReportTitle = trim($ProjectHeadName);
if ($SubHeadName !== '') {
    $ReportTitle = $ReportTitle !== '' ? ($ReportTitle . ' - ' . $SubHeadName) : $SubHeadName;
}
if ($ReportTitle === '' && !empty($_GET['title'])) {
    $ReportTitle = trim((string) $_GET['title']);
}
$capacityColumns = getList("SELECT * FROM tbl_rooftop_common_master WHERE Roll=2 ORDER BY id ASC");
if (!is_array($capacityColumns)) {
    $capacityColumns = [];
}
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">

<head>
    <title><?php echo $Proj_Title; ?>_<?php echo htmlspecialchars($ReportTitle); ?>_project_abstract </title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="" />
   <?php //include_once 'header_script.php'; ?>
   <link rel="icon" type="image/x-icon" href="<?php echo $SiteUrl;?>/assets/img/favicon.ico">
    <!-- Google fonts -->
<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
    <!-- Icon fonts -->
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/fontawesome.css">
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/ionicons.css">
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/linearicons.css">
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/feather.css">
    <!-- Core stylesheets -->
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/css/bootstrap-material2.css">
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/css/shreerang-material.css">
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/css/uikit.css">
<!-- Libs -->
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/libs/perfect-scrollbar/perfect-scrollbar.css">
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/libs/datatables/datatables.css">
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/libs/bootstrap-select/bootstrap-select.css">
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/libs/select2/select2.css">
<!--<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/libs/growl/growl.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/libs/toastr/toastr.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.dataTables.min.css">-->

  
  
</head>
<style>
    
    th, td{
        font-size:11px;
        border:1px solid gray;
        text-align:center;
    }
    th {
        background-color:#fee2d6;
    }

</style>
<body style="background-color:#fff;">



                        <div class="card" style="padding: 10px;">
<div align="center"><h5>ABSTRACT REPORT</h5>
<h5><?php echo htmlspecialchars($ReportTitle); ?> UPDATE AS ON DATE <?php echo date('d.m.Y');?></h5>
</div>
                          
<input type="hidden" id="ProjectId" value="<?php echo $_GET['projid'];?>">

<?php 
    function getDetails($val,$dist,$val2){
        global $conn;
        if($val == 'totapp'){
            $sql2 = "SELECT * FROM tbl_users WHERE District='$dist' AND ProjectType=2 AND Roll=5 AND ProjectId='".$_GET['projid']."' AND ProjectSubHeadId='".$_GET['SubHeadProjectId']."'";
            $rncnt2 = getRow($sql2);
        }
        if($val == 'capacity'){
            $sql2 = "SELECT * FROM tbl_users WHERE (PumpCapacity='$val2' OR RooftopPlantCapacity='$val2') AND ProjectType=2 AND Roll=5 AND District='$dist' AND ProjectId='".$_GET['projid']."' AND ProjectSubHeadId='".$_GET['SubHeadProjectId']."'";
            $rncnt2 = getRow($sql2);
        }
        if($val == 'surveydone'){
            $sql2 = "SELECT * FROM tbl_users WHERE FieldSurveyDetails='$val2' AND ProjectType=2 AND Roll=5 AND District='$dist' AND ProjectId='".$_GET['projid']."' AND ProjectSubHeadId='".$_GET['SubHeadProjectId']."'";
            $rncnt2 = getRow($sql2);
        }
        if($val == 'surveyrejected'){
            $sql2 = "SELECT * FROM tbl_users WHERE SurveyMatch='$val2' AND ProjectType=2 AND Roll=5 AND District='$dist' AND ProjectId='".$_GET['projid']."' AND ProjectSubHeadId='".$_GET['SubHeadProjectId']."'";
            $rncnt2 = getRow($sql2);
        }
        if($val == 'surveypending'){
            $sql2 = "SELECT * FROM tbl_users WHERE FieldSurveyDetails='$val2' AND ProjectType=2 AND Roll=5 AND District='$dist' AND ProjectId='".$_GET['projid']."' AND ProjectSubHeadId='".$_GET['SubHeadProjectId']."'";
            $rncnt2 = getRow($sql2);
        }
        if($val == 'dispatch'){
            $sql2 = "SELECT * FROM tbl_rooftop_sell ts INNER JOIN tbl_users tu ON tu.id=ts.CustId WHERE tu.ProjectType=2 AND tu.Roll=5 AND tu.District='$dist' AND tu.ProjectId='".$_GET['projid']."' AND tu.ProjectSubHeadId='".$_GET['SubHeadProjectId']."'";
            $rncnt2 = getRow($sql2);
        }
        if($val == 'installation'){
            $sql2 = "SELECT * FROM tbl_installations ts INNER JOIN tbl_users tu ON tu.id=ts.CustId WHERE tu.ProjectType=2 AND tu.Roll=5 AND tu.District='$dist' AND ts.InstallStatus='$val2' AND tu.ProjectId='".$_GET['projid']."' AND tu.ProjectSubHeadId='".$_GET['SubHeadProjectId']."'";
            $rncnt2 = getRow($sql2);
        }
        
        
        if($val == 'MeterInstDiscom'){
            $sql2 = "SELECT * FROM tbl_installations ts INNER JOIN tbl_users tu ON tu.id=ts.CustId WHERE tu.ProjectType=2 AND tu.Roll=5 AND tu.District='$dist' AND ts.MeterInstDiscom='$val2' AND tu.ProjectId='".$_GET['projid']."' AND tu.ProjectSubHeadId='".$_GET['SubHeadProjectId']."'";
            $rncnt2 = getRow($sql2);
        }
        if($val == 'dataupload'){
            $sql2 = "SELECT * FROM tbl_installations ts INNER JOIN tbl_users tu ON tu.id=ts.CustId WHERE tu.ProjectType=2 AND tu.Roll=5 AND tu.District='$dist' AND ts.DataUploadStatus='$val2' AND tu.ProjectId='".$_GET['projid']."' AND tu.ProjectSubHeadId='".$_GET['SubHeadProjectId']."'";
            $rncnt2 = getRow($sql2);
        }
        if($val == 'inspection'){
            $sql2 = "SELECT * FROM tbl_installations ts INNER JOIN tbl_users tu ON tu.id=ts.CustId WHERE tu.ProjectType=2 AND tu.Roll=5 AND tu.District='$dist' AND ts.PoInspection='$val2' AND tu.ProjectId='".$_GET['projid']."' AND tu.ProjectSubHeadId='".$_GET['SubHeadProjectId']."'";
            $rncnt2 = getRow($sql2);
        }
        if($val == 'inspectiondis'){
            $sql2 = "SELECT * FROM tbl_installations ts INNER JOIN tbl_users tu ON tu.id=ts.CustId WHERE tu.ProjectType=2 AND tu.Roll=5 AND tu.District='$dist' AND ts.InspectionDiscrepancy='$val2'  AND tu.ProjectId='".$_GET['projid']."' AND tu.ProjectSubHeadId='".$_GET['SubHeadProjectId']."'";
            $rncnt2 = getRow($sql2);
        }
        
        if($val == 'paymentstatus'){
            $sql2 = "SELECT * FROM tbl_installations ts INNER JOIN tbl_users tu ON tu.id=ts.CustId WHERE tu.ProjectType=2 AND tu.Roll=5 AND tu.District='$dist' AND ts.PaymentStatus='$val2' AND tu.ProjectId='".$_GET['projid']."' AND tu.ProjectSubHeadId='".$_GET['SubHeadProjectId']."'";
            $rncnt2 = getRow($sql2);
        }
        
        
        
        //echo $sql2;
        return $rncnt2;
    }

    $capacityTotals = [];
    foreach ($capacityColumns as $capCol) {
        $capacityTotals[(int) $capCol['id']] = 0;
    }
    $totapp = 0;
    $totsurveydone = 0;
    $totsurveyreject = 0;
    $totsurveypending = 0;
    $totdispatch = 0;
    $totinstallationdone = 0;
    $totinstallationpending = 0;
    $totdatauploadone = 0;
    $totdatauploapending = 0;
    $totmeterinstdone = 0;
    $totmeterinstpending = 0;
    $totinspectiondone = 0;
    $totinspectionpending = 0;
    $totinspectiondis = 0;
    $totcompletepayment = 0;
    $totpartialpayment = 0;
    $districtRows = [];

    $subHeadProjectId = (int) $_GET['SubHeadProjectId'];
    $districtSql = "SELECT DISTINCT(District) AS District FROM tbl_users WHERE District!='' AND ProjectType=2 AND Roll=5 AND ProjectId='$projId' AND ProjectSubHeadId='$subHeadProjectId'";
    if (!empty($_REQUEST['District'])) {
        $District = $_REQUEST['District'];
        $ReplaceDistrict = str_replace(",", "','", $District);
        if ($District != 'all') {
            $districtSql .= " AND District IN('$ReplaceDistrict')";
        }
    }
    $districtSql .= " ORDER BY District ASC";
    $districtList = getList($districtSql);
    if (!is_array($districtList)) {
        $districtList = [];
    }

    foreach ($districtList as $districtRow) {
        $distName = $districtRow['District'];
        $rowData = [
            'District' => $distName,
            'totapp' => getDetails('totapp', $distName, ''),
            'capacities' => [],
            'surveydone' => getDetails('surveydone', $distName, '1'),
            'surveyrejected' => getDetails('surveyrejected', $distName, '0'),
            'surveypending' => getDetails('surveypending', $distName, '0'),
            'dispatch' => getDetails('dispatch', $distName, ''),
            'installationdone' => getDetails('installation', $distName, 'Yes'),
            'installationpending' => getDetails('installation', $distName, 'No'),
            'datauploadone' => getDetails('dataupload', $distName, 'Yes'),
            'datauploadpending' => getDetails('dataupload', $distName, 'No'),
            'meterinstdone' => getDetails('MeterInstDiscom', $distName, 'Yes'),
            'meterinstpending' => getDetails('MeterInstDiscom', $distName, 'No'),
            'inspectiondone' => getDetails('inspection', $distName, 'Yes'),
            'inspectionpending' => getDetails('inspection', $distName, 'No'),
            'inspectiondis' => getDetails('inspectiondis', $distName, 'Yes'),
            'completepayment' => getDetails('paymentstatus', $distName, '2'),
            'partialpayment' => getDetails('paymentstatus', $distName, '1'),
        ];

        $totapp += $rowData['totapp'];
        foreach ($capacityColumns as $capCol) {
            $capId = (int) $capCol['id'];
            $capCnt = getDetails('capacity', $distName, (string) $capId);
            $rowData['capacities'][$capId] = $capCnt;
            $capacityTotals[$capId] += $capCnt;
        }
        $totsurveydone += $rowData['surveydone'];
        $totsurveyreject += $rowData['surveyrejected'];
        $totsurveypending += $rowData['surveypending'];
        $totdispatch += $rowData['dispatch'];
        $totinstallationdone += $rowData['installationdone'];
        $totinstallationpending += $rowData['installationpending'];
        $totdatauploadone += $rowData['datauploadone'];
        $totdatauploapending += $rowData['datauploadpending'];
        $totmeterinstdone += $rowData['meterinstdone'];
        $totmeterinstpending += $rowData['meterinstpending'];
        $totinspectiondone += $rowData['inspectiondone'];
        $totinspectionpending += $rowData['inspectionpending'];
        $totinspectiondis += $rowData['inspectiondis'];
        $totcompletepayment += $rowData['completepayment'];
        $totpartialpayment += $rowData['partialpayment'];
        $districtRows[] = $rowData;
    }

    $visibleCapacityColumns = [];
    foreach ($capacityColumns as $capCol) {
        if ((int) ($capacityTotals[(int) $capCol['id']] ?? 0) > 0) {
            $visibleCapacityColumns[] = $capCol;
        }
    }

?>

                            <div>
                              <table id="" style="width:100%">
                <thead>
                <tr>
                    <th>Sr No</th>
                    <th>DISTRICT</th>
                    <th>Total<br> Application<br> Received</th>
                    <?php foreach ($visibleCapacityColumns as $result) { ?>
                    <th><?php echo htmlspecialchars($result['Name']); ?></th>
                    <?php } ?>
                   
                    <th>Survey Done </th>
                    <!--<th>Survey Dicrepancy</th>-->
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
         
                </thead>
                
                <tbody>
                    <?php
                    $i = 1;
                    foreach ($districtRows as $rowData) {
                        $distName = $rowData['District'];
                    ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td style="font-weight:600;text-align:left;padding:3px;"><?php echo htmlspecialchars($distName); ?></td>
                        <td><a href="total-beneficiary.php?roll=totapp&dist=<?php echo urlencode($distName); ?>&projid=<?php echo $_REQUEST['projid']; ?>&val=&title=Total Application Received&subheadid=<?php echo $_REQUEST['SubHeadProjectId']; ?>" target="_blank"><?php echo $rowData['totapp']; ?></a></td>

                         <?php foreach ($visibleCapacityColumns as $result22) {
                            $capId = (int) $result22['id'];
                         ?>
                        <td><a href="total-beneficiary.php?roll=capacity&dist=<?php echo urlencode($distName); ?>&projid=<?php echo $_REQUEST['projid']; ?>&val=<?php echo $capId; ?>&subheadid=<?php echo $_REQUEST['SubHeadProjectId']; ?>&title=<?php echo urlencode($result22['Name']); ?>" target="_blank">
                            <?php echo (int) ($rowData['capacities'][$capId] ?? 0); ?></a></td>
                        <?php } ?>

                        <td><a href="total-beneficiary.php?roll=surveydone&dist=<?php echo urlencode($distName); ?>&projid=<?php echo $_REQUEST['projid']; ?>&val=1&subheadid=<?php echo $_REQUEST['SubHeadProjectId']; ?>&title=Survey Done As Per Portal" target="_blank"><?php echo $rowData['surveydone']; ?></a></td>
                        <td><a href="total-beneficiary.php?roll=surveyrejected&dist=<?php echo urlencode($distName); ?>&projid=<?php echo $_REQUEST['projid']; ?>&val=0&subheadid=<?php echo $_REQUEST['SubHeadProjectId']; ?>&title=Survey Rejected" target="_blank"><?php echo $rowData['surveyrejected']; ?></a></td>
                        <td style="background-color:#fee2d6;"><a href="total-beneficiary.php?roll=surveypending&dist=<?php echo urlencode($distName); ?>&projid=<?php echo $_REQUEST['projid']; ?>&val=0&subheadid=<?php echo $_REQUEST['SubHeadProjectId']; ?>&title=Survey Pending" target="_blank"><?php echo $rowData['surveypending']; ?></a></td>

                        <td><a href="total-beneficiary.php?roll=dispatch&dist=<?php echo urlencode($distName); ?>&projid=<?php echo $_REQUEST['projid']; ?>&val=&subheadid=<?php echo $_REQUEST['SubHeadProjectId']; ?>&title=Material Dispatch" target="_blank"><?php echo $rowData['dispatch']; ?></a></td>
                        <td><a href="total-beneficiary.php?roll=installation&dist=<?php echo urlencode($distName); ?>&projid=<?php echo $_REQUEST['projid']; ?>&val=Yes&subheadid=<?php echo $_REQUEST['SubHeadProjectId']; ?>&title=Installation Done" target="_blank"><?php echo $rowData['installationdone']; ?></a></td>
                        <td style="background-color:#fee2d6;"><a href="total-beneficiary.php?roll=installation&dist=<?php echo urlencode($distName); ?>&projid=<?php echo $_REQUEST['projid']; ?>&val=No&subheadid=<?php echo $_REQUEST['SubHeadProjectId']; ?>&title=Installation Pending" target="_blank"><?php echo $rowData['installationpending']; ?></a></td>

                         <td><a href="total-beneficiary.php?roll=meterinstall&dist=<?php echo urlencode($distName); ?>&projid=<?php echo $_REQUEST['projid']; ?>&subheadid=<?php echo $_REQUEST['SubHeadProjectId']; ?>&val=Yes&title=Meter Installed Done" target="_blank"><?php echo $rowData['meterinstdone']; ?></a></td>

                        <td><a href="total-beneficiary.php?roll=meterinstall&dist=<?php echo urlencode($distName); ?>&projid=<?php echo $_REQUEST['projid']; ?>&subheadid=<?php echo $_REQUEST['SubHeadProjectId']; ?>&val=No&title=Meter Installed Pending" target="_blank"><?php echo $rowData['meterinstpending']; ?></a></td>

                        <td><a href="total-beneficiary.php?roll=dataupload&dist=<?php echo urlencode($distName); ?>&projid=<?php echo $_REQUEST['projid']; ?>&subheadid=<?php echo $_REQUEST['SubHeadProjectId']; ?>&val=Yes&title=Data Upload Done" target="_blank"><?php echo $rowData['datauploadone']; ?></a></td>

                        <td style="background-color:#fee2d6;"><a href="total-beneficiary.php?roll=dataupload&dist=<?php echo urlencode($distName); ?>&projid=<?php echo $_REQUEST['projid']; ?>&val=No&subheadid=<?php echo $_REQUEST['SubHeadProjectId']; ?>&title=Data Upload Pending" target="_blank"><?php echo $rowData['datauploadpending']; ?></a></td>
                        <td><a href="total-beneficiary.php?roll=inspection&dist=<?php echo urlencode($distName); ?>&projid=<?php echo $_REQUEST['projid']; ?>&val=Yes&subheadid=<?php echo $_REQUEST['SubHeadProjectId']; ?>&title=Inspection Done" target="_blank"><?php echo $rowData['inspectiondone']; ?></a></td>
                        <td><a href="total-beneficiary.php?roll=inspectiondis&dist=<?php echo urlencode($distName); ?>&projid=<?php echo $_REQUEST['projid']; ?>&val=Yes&subheadid=<?php echo $_REQUEST['SubHeadProjectId']; ?>&title=Inspection Discrepancy" target="_blank"><?php echo $rowData['inspectiondis']; ?></a></td>
                        <td style="background-color:#fee2d6;"><a href="total-beneficiary.php?roll=inspection&dist=<?php echo urlencode($distName); ?>&projid=<?php echo $_REQUEST['projid']; ?>&val=No&subheadid=<?php echo $_REQUEST['SubHeadProjectId']; ?>&title=Inspection Pending" target="_blank"><?php echo $rowData['inspectionpending']; ?></a></td>

                        <td style="background-color:#fee2d6;"><a href="total-beneficiary.php?roll=paymentstatus&dist=<?php echo urlencode($distName); ?>&projid=<?php echo $_REQUEST['projid']; ?>&val=2&subheadid=<?php echo $_REQUEST['SubHeadProjectId']; ?>&title=Complete Payment Received" target="_blank"><?php echo $rowData['completepayment']; ?></a></td>

                        <td style="background-color:#fee2d6;"><a href="total-beneficiary.php?roll=paymentstatus&dist=<?php echo urlencode($distName); ?>&projid=<?php echo $_REQUEST['projid']; ?>&val=1&subheadid=<?php echo $_REQUEST['SubHeadProjectId']; ?>&title=Partial Payment Received" target="_blank"><?php echo $rowData['partialpayment']; ?></a></td>
                    </tr>
                    <?php $i++; } ?>

                    <tr>
                        <th colspan="2">TOTAL</th>
                        <th><a href="total-beneficiary.php?roll=totapp&dist=&projid=<?php echo $_REQUEST['projid']; ?>&val=&subheadid=<?php echo $_REQUEST['SubHeadProjectId']; ?>" target="_blank"><?php echo $totapp; ?></a></th>
                        <?php foreach ($visibleCapacityColumns as $capCol) {
                            $capId = (int) $capCol['id'];
                        ?>
                        <th><a href="total-beneficiary.php?roll=capacity&dist=&projid=<?php echo $_REQUEST['projid']; ?>&val=<?php echo $capId; ?>&subheadid=<?php echo $_REQUEST['SubHeadProjectId']; ?>&title=<?php echo urlencode($capCol['Name']); ?>" target="_blank"><?php echo (int) ($capacityTotals[$capId] ?? 0); ?></a></th>
                        <?php } ?>
                        <th><a href="total-beneficiary.php?roll=surveydone&dist=&projid=<?php echo $_REQUEST['projid']; ?>&val=1&subheadid=<?php echo $_REQUEST['SubHeadProjectId']; ?>" target="_blank"><?php echo $totsurveydone; ?></a></th>
                        <!--<th></th>-->
                        <th><a href="total-beneficiary.php?roll=surveyrejected&dist=&projid=<?php echo $_REQUEST['projid'];?>&val=0&subheadid=<?php echo $_REQUEST['SubHeadProjectId'];?>" target="_blank"><?php echo $totsurveyreject;?></a></th>
                        <th><a href="total-beneficiary.php?roll=surveypending&dist=&projid=<?php echo $_REQUEST['projid'];?>&val=0&subheadid=<?php echo $_REQUEST['SubHeadProjectId'];?>" target="_blank"><?php echo $totsurveypending;?></a></th>
                        <th><a href="total-beneficiary.php?roll=dispatch&dist=&projid=<?php echo $_REQUEST['projid'];?>&val=&subheadid=<?php echo $_REQUEST['SubHeadProjectId'];?>" target="_blank"><?php echo $totdispatch;?></a></th>
                        <th><a href="total-beneficiary.php?roll=installation&dist=&projid=<?php echo $_REQUEST['projid'];?>&val=Yes&subheadid=<?php echo $_REQUEST['SubHeadProjectId'];?>" target="_blank"><?php echo $totinstallationdone;?></a></th>
                        
                        
                        <th><a href="total-beneficiary.php?roll=installation&dist=&projid=<?php echo $_REQUEST['projid'];?>&val=No&subheadid=<?php echo $_REQUEST['SubHeadProjectId'];?>" target="_blank"><?php echo $totinstallationpending;?></a></th>
                        
                        <th><a href="total-beneficiary.php?roll=meterinstall&dist=&projid=<?php echo $_REQUEST['projid'];?>&val=Yes&subheadid=<?php echo $_REQUEST['SubHeadProjectId'];?>" target="_blank"><?php echo $totmeterinstdone;?></a></th>
                        
                        <th><a href="total-beneficiary.php?roll=meterinstall&dist=&projid=<?php echo $_REQUEST['projid'];?>&val=No&subheadid=<?php echo $_REQUEST['SubHeadProjectId'];?>" target="_blank"><?php echo $totmeterinstpending;?></a></th>
                        
                        <th><a href="total-beneficiary.php?roll=dataupload&dist=&projid=<?php echo $_REQUEST['projid'];?>&val=Yes&subheadid=<?php echo $_REQUEST['SubHeadProjectId'];?>" target="_blank"><?php echo $totdatauploadone;?></a></th>
                        
                        <th><a href="total-beneficiary.php?roll=dataupload&dist=&projid=<?php echo $_REQUEST['projid'];?>&val=No&subheadid=<?php echo $_REQUEST['SubHeadProjectId'];?>" target="_blank"><?php echo $totdatauploapending;?></a></th>
                        <th><a href="total-beneficiary.php?roll=inspection&dist=&projid=<?php echo $_REQUEST['projid'];?>&val=Yes&subheadid=<?php echo $_REQUEST['SubHeadProjectId'];?>" target="_blank"><?php echo $totinspectiondone;?></a></th>
                        <th><a href="total-beneficiary.php?roll=inspectiondis&dist=&projid=<?php echo $_REQUEST['projid'];?>&val=Yes&subheadid=<?php echo $_REQUEST['SubHeadProjectId'];?>" target="_blank"><?php echo $totinspectiondis;?></a></th>
                        <th><a href="total-beneficiary.php?roll=inspection&dist=&projid=<?php echo $_REQUEST['projid'];?>&val=No&subheadid=<?php echo $_REQUEST['SubHeadProjectId'];?>" target="_blank"><?php echo $totinspectionpending;?></a></th>
                        
                        
                        <th><a href="total-beneficiary.php?roll=paymentstatus&dist=&projid=<?php echo $_REQUEST['projid'];?>&val=2&subheadid=<?php echo $_REQUEST['SubHeadProjectId'];?>" target="_blank"><?php echo $totcompletepayment;?></a></th>
                        
                        <th><a href="total-beneficiary.php?roll=paymentstatus&dist=&projid=<?php echo $_REQUEST['projid'];?>&val=1&subheadid=<?php echo $_REQUEST['SubHeadProjectId'];?>" target="_blank"><?php echo $totpartialpayment;?></a></th>
                    </tr>
                </tbody>
                
            </table>
                            </div>
                        </div>
                    </div>


                   


<?php //include_once 'footer_script.php'; ?> 
 
<!--<script type="text/javascript">
  
    $(document).ready(function() {
    $('#example').DataTable({
       "scrollX": true,
        dom: 'Bfrtip',
        buttons: [
            'excelHtml5',
            'pdfHtml5'
        ]
    });
});
</script>-->
   
</body>

</html>