<?php
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Installation";
$Page = "Installation";

$sql = "SELECT * FROM tbl_project_sub_head WHERE id='".$_GET['SubHeadProjectId']."'";
$row = getRecord($sql);
$Projectname = $row['Name'];
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">

<head>
    <title><?php echo $Proj_Title; ?>_<?php echo $Projectname;?>_project_abstract </title>
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
<h5><?php echo $Projectname;?> UPDATE AS ON DATE <?php echo date('d.m.Y');?></h5>
</div>
                          
<input type="hidden" id="ProjectId" value="<?php echo $_GET['projid'];?>">

<?php 
    function getDetails($val,$dist,$val2){
        global $conn;
        if($val == 'totapp'){
            $sql2 = "SELECT * FROM tbl_users WHERE District='$dist' AND ProjectType=1 AND ProjectId='".$_GET['projid']."' AND ProjectSubHeadId='".$_GET['SubHeadProjectId']."'";
            $rncnt2 = getRow($sql2);
        }
        if($val == 'capacity'){
            $sql2 = "SELECT * FROM tbl_users WHERE PumpCapacity='$val2' AND ProjectType=1 AND District='$dist' AND ProjectId='".$_GET['projid']."' AND ProjectSubHeadId='".$_GET['SubHeadProjectId']."'";
            $rncnt2 = getRow($sql2);
        }
        if($val == 'surveydone'){
            $sql2 = "SELECT * FROM tbl_users WHERE FieldSurveyDetails='$val2' AND ProjectType=1 AND District='$dist' AND ProjectId='".$_GET['projid']."' AND ProjectSubHeadId='".$_GET['SubHeadProjectId']."'";
            $rncnt2 = getRow($sql2);
        }
        if($val == 'surveyrejected'){
            $sql2 = "SELECT * FROM tbl_users WHERE SurveyMatch='$val2' AND ProjectType=1 AND District='$dist' AND ProjectId='".$_GET['projid']."' AND ProjectSubHeadId='".$_GET['SubHeadProjectId']."'";
            $rncnt2 = getRow($sql2);
        }
        if($val == 'surveypending'){
            $sql2 = "SELECT * FROM tbl_users WHERE FieldSurveyDetails='$val2' AND ProjectType=1 AND District='$dist' AND ProjectId='".$_GET['projid']."' AND ProjectSubHeadId='".$_GET['SubHeadProjectId']."'";
            $rncnt2 = getRow($sql2);
        }
        if($val == 'dispatch'){
            $sql2 = "SELECT * FROM tbl_sell ts INNER JOIN tbl_users tu ON tu.id=ts.CustId WHERE tu.ProjectType=1 AND tu.District='$dist' AND tu.ProjectId='".$_GET['projid']."' AND tu.ProjectSubHeadId='".$_GET['SubHeadProjectId']."' AND ts.Inst_Dispatcher_Otp_Verify=1";
            $rncnt2 = getRow($sql2);
        }
        if($val == 'installation'){
            if($val2 == 'Yes'){
                 $sql2 = "SELECT * FROM tbl_installations ti 
                    LEFT JOIN tbl_users tu ON ti.CustId=tu.id WHERE tu.ProjectId='".$_GET['projid']."' AND tu.District='$dist' AND tu.Roll=5 AND tu.FieldSurveyDetails=1 AND ti.InstallStatus='Yes' AND ti.Type=2 AND tu.ProjectSubHeadId='".$_GET['SubHeadProjectId']."'";
                $rncnt2 = getRow($sql2);    
            }
            else{
              $sql2 = "SELECT * FROM tbl_users tu WHERE tu.ProjectId = '".$_GET['projid']."' AND tu.District='$dist' AND tu.ProjectSubHeadId = '".$_GET['SubHeadProjectId']."' AND tu.Roll = 5 AND tu.FieldSurveyDetails = 1 AND tu.ProjectType = 1 AND tu.id NOT IN ( SELECT ti.CustId FROM tbl_installations ti WHERE ti.InstallStatus = 'Yes' AND ti.Type = 2 )";  
            $rncnt2 = getRow($sql2);    
                
            }
            
           
        }
        if($val == 'dataupload'){
            $sql2 = "SELECT * FROM tbl_installations ts INNER JOIN tbl_users tu ON tu.id=ts.CustId WHERE tu.ProjectType=1 AND tu.District='$dist' AND ts.DataUploadStatus='$val2' AND tu.ProjectId='".$_GET['projid']."' AND tu.ProjectSubHeadId='".$_GET['SubHeadProjectId']."'";
            $rncnt2 = getRow($sql2);
        }
        if($val == 'inspection'){
           if($val2 == 'Yes'){
                 $sql2 = "SELECT * FROM tbl_installations ti 
                    LEFT JOIN tbl_users tu ON ti.CustId=tu.id WHERE tu.ProjectId='".$_GET['projid']."' AND tu.District='$dist' AND tu.Roll=5 AND tu.FieldSurveyDetails=1 AND ti.PoInspection='Yes' AND ti.Type=2 AND tu.ProjectSubHeadId='".$_GET['SubHeadProjectId']."'";
                $rncnt2 = getRow($sql2);    
            }
            else{
              $sql2 = "SELECT * FROM tbl_users tu WHERE tu.ProjectId = '".$_GET['projid']."' AND tu.District='$dist' AND tu.ProjectSubHeadId = '".$_GET['SubHeadProjectId']."' AND tu.Roll = 5 AND tu.FieldSurveyDetails = 1 AND tu.ProjectType = 1 AND tu.id NOT IN ( SELECT ti.CustId FROM tbl_installations ti WHERE ti.PoInspection = 'Yes' AND ti.Type = 2 )";  
            $rncnt2 = getRow($sql2);    
                
            }
        }
        if($val == 'inspectiondis'){
            $sql2 = "SELECT * FROM tbl_installations ts INNER JOIN tbl_users tu ON tu.id=ts.CustId WHERE tu.ProjectType=1 AND tu.District='$dist' AND ts.InspectionDiscrepancy='$val2'  AND tu.ProjectId='".$_GET['projid']."' AND tu.ProjectSubHeadId='".$_GET['SubHeadProjectId']."'";
            $rncnt2 = getRow($sql2);
        }
        if($val == 'dcr'){
             $sql2 = "SELECT * FROM tbl_installations ts INNER JOIN tbl_users tu ON tu.id=ts.CustId WHERE tu.ProjectType=1 AND tu.District='$dist' AND ts.DcrVerify='$val2' AND tu.ProjectId='".$_GET['projid']."' AND tu.ProjectSubHeadId='".$_GET['SubHeadProjectId']."'";
            $rncnt2 = getRow($sql2);
        }
        
        
        //echo $sql2;
        return $rncnt2;
    }

?>

                            <div>
                              <table id="" style="width:100%">
                <thead>
                <tr>
                    <th>Sr No</th>
                    <th>DISTRICT</th>
                    <th>Total<br> Application<br> Received</th>
                    <th>3 HP</th>
                     <th>5 HP</th>
                    <th>7.5 HP</th>
                    <th>Survey Done As Per Portal</th>
                    <th>Survey Dicrepancy</th>
                    <th>Rejected</th>
                    <th>Survey Pending</th>
                    <th>Material Dispatch</th>
                    <th>Installation Done</th>
                    <th>Installation Pending</th>
                    <th>Data Upload Done</th>
                    <th>Data Upload Pending</th>
                    <th>Inspection Done</th>
                    <th>Inspection Discrepancy</th>
                    <th>Inspection Pending</th>
                     <th>DCR Verification Pending</th>
                <th>DCR Verification Done</th>
                </thead>
                
                <tbody>
                    <?php 
                    $i=1;
                    $sql = "select DISTINCT(District) As District from tbl_users WHERE District!='' ";
                    if($_REQUEST['District']!=''){
                        $District = $_REQUEST['District'];
                        $ReplaceDistrict = str_replace(",","','",$District);

                        if($District == 'all'){
                            $sql.="";
                        }
                        else{
                           $sql.=" AND District IN('$ReplaceDistrict')";
                        }
                    }
                    if($_REQUEST['projid']!=''){
                        $ProjectId = $_REQUEST['projid'];
                        if($ProjectId == 'all'){
                            $sql.="";
                        }
                        else{
                           $sql.=" AND ProjectId='$ProjectId'";
                        }
                    }
                    if($_REQUEST['SubHeadProjectId']!=''){
                        $ProjectSubHeadId = $_REQUEST['SubHeadProjectId'];
                        if($ProjectSubHeadId == 'all'){
                            $sql.="";
                        }
                        else{
                           $sql.=" AND ProjectSubHeadId='$ProjectSubHeadId'";
                        }
                    }
                    $sql.=" ORDER BY District ASC";
                    // /echo $sql;
                    $row = getList($sql);
                    foreach($row as $result){
                        $totapp+=getDetails('totapp',$result['District'],'');
                        $tot3hp+=getDetails('capacity',$result['District'],'18');
                        $tot5hp+=getDetails('capacity',$result['District'],'19');
                        $tot7hp+=getDetails('capacity',$result['District'],'20');
                        $totsurveydone+=getDetails('surveydone',$result['District'],'1');
                        $totsurveyreject+=getDetails('surveyrejected',$result['District'],'2');
                        $totsurveypending+=getDetails('surveypending',$result['District'],'0');
                        $totdispatch+=getDetails('dispatch',$result['District'],'');
                        $totinstallationdone+=getDetails('installation',$result['District'],'Yes');
                        $totinstallationpending+=getDetails('dispatch',$result['District'],'') - getDetails('installation',$result['District'],'Yes');
                        $totdatauploadone+=getDetails('dataupload',$result['District'],'Yes');
                        $totdatauploapending+=getDetails('installation',$result['District'],'Yes') - getDetails('dataupload',$result['District'],'Yes');
                        $totinspectiondone+=getDetails('inspection',$result['District'],'Yes');
                        $totinspectionpending+=getDetails('dataupload',$result['District'],'Yes') - getDetails('inspection',$result['District'],'Yes');
                        $totinspectiondis+=getDetails('inspectiondis',$result['District'],'Yes');
                        
                        $totdcrpending+=getDetails('dcr',$result['District'],'No');
                        $totdcrdone+=getDetails('dcr',$result['District'],'Yes');
                    ?>
                    <tr>
                        <td><?php echo $i;?></td>
                        <td style="font-weight:600;text-align:left;padding:3px;"><?php echo $result['District']; ?></td>
                        <td><a href="total-beneficiary.php?roll=totapp&dist=<?php echo $result['District'];?>&projid=<?php echo $_REQUEST['projid'];?>&val=&title=Total Application Received&subheadid=<?php echo $_REQUEST['SubHeadProjectId'];?>" target="_blank"><?php echo getDetails('totapp',$result['District'],'');?></a></td>
                        <td><a href="total-beneficiary.php?roll=capacity&dist=<?php echo $result['District'];?>&projid=<?php echo $_REQUEST['projid'];?>&val=18&subheadid=<?php echo $_REQUEST['SubHeadProjectId'];?>&title=3 HP" target="_blank"><?php echo getDetails('capacity',$result['District'],'18');?></a></td>
                        <td><a href="total-beneficiary.php?roll=capacity&dist=<?php echo $result['District'];?>&projid=<?php echo $_REQUEST['projid'];?>&val=19&subheadid=<?php echo $_REQUEST['SubHeadProjectId'];?>&title=5 HP" target="_blank"><?php echo getDetails('capacity',$result['District'],'19');?></a></td>
                        <td><a href="total-beneficiary.php?roll=capacity&dist=<?php echo $result['District'];?>&projid=<?php echo $_REQUEST['projid'];?>&val=20&subheadid=<?php echo $_REQUEST['SubHeadProjectId'];?>&title=7.5 HP" target="_blank"><?php echo getDetails('capacity',$result['District'],'20');?></a></td>
                        <td><a href="total-beneficiary.php?roll=surveydone&dist=<?php echo $result['District'];?>&projid=<?php echo $_REQUEST['projid'];?>&val=1&subheadid=<?php echo $_REQUEST['SubHeadProjectId'];?>&title=Survey Done As Per Portal" target="_blank"><?php echo getDetails('surveydone',$result['District'],'1');?></a></td>
                        <td></td>
                        <td><a href="total-beneficiary.php?roll=surveyrejected&dist=<?php echo $result['District'];?>&projid=<?php echo $_REQUEST['projid'];?>&val=0&subheadid=<?php echo $_REQUEST['SubHeadProjectId'];?>&title=Survey Rejected" target="_blank"><?php echo getDetails('surveyrejected',$result['District'],'2');?></a></td>
                        <td style="background-color:#fee2d6;"><a href="total-beneficiary.php?roll=surveypending&dist=<?php echo $result['District'];?>&projid=<?php echo $_REQUEST['projid'];?>&val=0&subheadid=<?php echo $_REQUEST['SubHeadProjectId'];?>&title=Survey Pending" target="_blank"><?php echo getDetails('surveypending',$result['District'],'0');?></a></td>
                        
                        <td><a href="total-beneficiary.php?roll=dispatch&dist=<?php echo $result['District'];?>&projid=<?php echo $_REQUEST['projid'];?>&val=&subheadid=<?php echo $_REQUEST['SubHeadProjectId'];?>&title=Material Dispatch" target="_blank"><?php echo getDetails('dispatch',$result['District'],'');?></a></td>
                        <td><a href="total-beneficiary.php?roll=installation&dist=<?php echo $result['District'];?>&projid=<?php echo $_REQUEST['projid'];?>&val=Yes&subheadid=<?php echo $_REQUEST['SubHeadProjectId'];?>&title=Installation Done" target="_blank"><?php echo getDetails('installation',$result['District'],'Yes');?></a></td>
                        
                        <td style="background-color:#fee2d6;"><a href="total-beneficiary.php?roll=installation&dist=<?php echo $result['District'];?>&projid=<?php echo $_REQUEST['projid'];?>&val=No&subheadid=<?php echo $_REQUEST['SubHeadProjectId'];?>&title=Installation Pending" target="_blank"><?php echo getDetails('dispatch',$result['District'],'') - getDetails('installation',$result['District'],'Yes');?></a></td>
                        <!--<td style="background-color:#fee2d6;"><a href="total-beneficiary.php?roll=installation&dist=<?php echo $result['District'];?>&projid=<?php echo $_REQUEST['projid'];?>&val=No&subheadid=<?php echo $_REQUEST['SubHeadProjectId'];?>&title=Installation Pending" target="_blank"><?php echo getDetails('installation',$result['District'],'No');?></a></td>-->
                        
                        <td><a href="total-beneficiary.php?roll=dataupload&dist=<?php echo $result['District'];?>&projid=<?php echo $_REQUEST['projid'];?>&val=Yes&title=Data Upload Done" target="_blank"><?php echo getDetails('dataupload',$result['District'],'Yes');?></a></td>
                        <td style="background-color:#fee2d6;"><a href="total-beneficiary.php?roll=dataupload&dist=<?php echo $result['District'];?>&projid=<?php echo $_REQUEST['projid'];?>&val=No&subheadid=<?php echo $_REQUEST['SubHeadProjectId'];?>&title=Data Upload Pending" target="_blank"><?php echo getDetails('installation',$result['District'],'Yes') - getDetails('dataupload',$result['District'],'Yes');?></a></td>
                        
                        <td><a href="total-beneficiary.php?roll=inspection&dist=<?php echo $result['District'];?>&projid=<?php echo $_REQUEST['projid'];?>&val=Yes&subheadid=<?php echo $_REQUEST['SubHeadProjectId'];?>&title=Inspection Done" target="_blank"><?php echo getDetails('inspection',$result['District'],'Yes');?></a></td>
                        <td><a href="total-beneficiary.php?roll=inspectiondis&dist=<?php echo $result['District'];?>&projid=<?php echo $_REQUEST['projid'];?>&val=Yes&subheadid=<?php echo $_REQUEST['SubHeadProjectId'];?>&title=Inspection Discrepancy" target="_blank"><?php echo getDetails('inspectiondis',$result['District'],'Yes');?></a></td>
                        <td style="background-color:#fee2d6;"><a href="total-beneficiary.php?roll=inspection&dist=<?php echo $result['District'];?>&projid=<?php echo $_REQUEST['projid'];?>&val=No&subheadid=<?php echo $_REQUEST['SubHeadProjectId'];?>&title=Inspection Pending" target="_blank"><?php echo getDetails('dataupload',$result['District'],'Yes') - getDetails('inspection',$result['District'],'Yes');?></a></td>
                    <td><a href="total-beneficiary.php?roll=dcr&dist=<?php echo $result['District'];?>&projid=<?php echo $_REQUEST['projid'];?>&val=No&subheadid=<?php echo $_REQUEST['SubHeadProjectId'];?>&title=DCR Verification Pending" target="_blank"><?php echo getDetails('dcr',$result['District'],'No');?></a></td>
                    <td><a href="total-beneficiary.php?roll=dcr&dist=<?php echo $result['District'];?>&projid=<?php echo $_REQUEST['projid'];?>&val=Yes&subheadid=<?php echo $_REQUEST['SubHeadProjectId'];?>&title=DCR Verification Done" target="_blank"><?php echo getDetails('dcr',$result['District'],'Yes');?></a></td>
                    </tr>
                    <?php $i++;} ?>
                    
                    <tr>
                        <th colspan="2">TOTAL</th>
                        <th><a href="total-beneficiary.php?roll=totapp&dist=&projid=<?php echo $_REQUEST['projid'];?>&val=&subheadid=<?php echo $_REQUEST['SubHeadProjectId'];?>" target="_blank"><?php echo $totapp;?></a></th>
                        <th><a href="total-beneficiary.php?roll=capacity&dist=&projid=<?php echo $_REQUEST['projid'];?>&val=18&subheadid=<?php echo $_REQUEST['SubHeadProjectId'];?>" target="_blank"><?php echo $tot3hp;?></a></th>
                        <th><a href="total-beneficiary.php?roll=capacity&dist=&projid=<?php echo $_REQUEST['projid'];?>&val=19&subheadid=<?php echo $_REQUEST['SubHeadProjectId'];?>" target="_blank"><?php echo $tot5hp;?></a></th>
                        <th><a href="total-beneficiary.php?roll=capacity&dist=&projid=<?php echo $_REQUEST['projid'];?>&val=20&subheadid=<?php echo $_REQUEST['SubHeadProjectId'];?>" target="_blank"><?php echo $tot7hp;?></a></th>
                        <th><a href="total-beneficiary.php?roll=surveydone&dist=&projid=<?php echo $_REQUEST['projid'];?>&val=1&subheadid=<?php echo $_REQUEST['SubHeadProjectId'];?>" target="_blank"><?php echo $totsurveydone;?></a></th>
                        <th></th>
                        <th><a href="total-beneficiary.php?roll=surveyrejected&dist=&projid=<?php echo $_REQUEST['projid'];?>&val=0&subheadid=<?php echo $_REQUEST['SubHeadProjectId'];?>" target="_blank"><?php echo $totsurveyreject;?></a></th>
                        <th><a href="total-beneficiary.php?roll=surveypending&dist=&projid=<?php echo $_REQUEST['projid'];?>&val=0&subheadid=<?php echo $_REQUEST['SubHeadProjectId'];?>" target="_blank"><?php echo $totsurveypending;?></a></th>
                        <th><a href="total-beneficiary.php?roll=dispatch&dist=&projid=<?php echo $_REQUEST['projid'];?>&val=&subheadid=<?php echo $_REQUEST['SubHeadProjectId'];?>" target="_blank"><?php echo $totdispatch;?></a></th>
                        <th><a href="total-beneficiary.php?roll=installation&dist=&projid=<?php echo $_REQUEST['projid'];?>&val=Yes&subheadid=<?php echo $_REQUEST['SubHeadProjectId'];?>" target="_blank"><?php echo $totinstallationdone;?></a></th>
                        <th><a href="total-beneficiary.php?roll=installation&dist=&projid=<?php echo $_REQUEST['projid'];?>&val=No&subheadid=<?php echo $_REQUEST['SubHeadProjectId'];?>" target="_blank"><?php echo $totinstallationpending;?></a></th>
                        <th><a href="total-beneficiary.php?roll=dataupload&dist=&projid=<?php echo $_REQUEST['projid'];?>&val=Yes&subheadid=<?php echo $_REQUEST['SubHeadProjectId'];?>" target="_blank"><?php echo $totdatauploadone;?></a></th>
                        <th><a href="total-beneficiary.php?roll=dataupload&dist=&projid=<?php echo $_REQUEST['projid'];?>&val=No&subheadid=<?php echo $_REQUEST['SubHeadProjectId'];?>" target="_blank"><?php echo $totdatauploapending;?></a></th>
                        <th><a href="total-beneficiary.php?roll=inspection&dist=&projid=<?php echo $_REQUEST['projid'];?>&val=Yes&subheadid=<?php echo $_REQUEST['SubHeadProjectId'];?>" target="_blank"><?php echo $totinspectiondone;?></a></th>
                        <th><a href="total-beneficiary.php?roll=inspectiondis&dist=&projid=<?php echo $_REQUEST['projid'];?>&val=Yes&subheadid=<?php echo $_REQUEST['SubHeadProjectId'];?>" target="_blank"><?php echo $totinspectiondis;?></a></th>
                        <th><a href="total-beneficiary.php?roll=inspection&dist=&projid=<?php echo $_REQUEST['projid'];?>&val=No&subheadid=<?php echo $_REQUEST['SubHeadProjectId'];?>" target="_blank"><?php echo $totinspectionpending;?></a></th>
                     <th><a href="total-beneficiary.php?roll=dcr&dist=&projid=<?php echo $_REQUEST['projid'];?>&val=No&subheadid=<?php echo $_REQUEST['SubHeadProjectId'];?>" target="_blank"><?php echo $totdcrpending;?></a></th>
                    <th><a href="total-beneficiary.php?roll=dcr&dist=&projid=<?php echo $_REQUEST['projid'];?>&val=Yes&subheadid=<?php echo $_REQUEST['SubHeadProjectId'];?>" target="_blank"><?php echo $totdcrdone;?></a></th>
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