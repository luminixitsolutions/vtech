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
    function getDetails($val,$subheadid,$val2){
        global $conn;
        if($val == 'totapp'){
            $sql2 = "SELECT * FROM tbl_users WHERE  ProjectType=1 AND ProjectId='".$_GET['projid']."' AND ProjectSubHeadId='".$subheadid."'";
            $rncnt2 = getRow($sql2);
        }
        if($val == 'underproduction'){
            $sql2 = "SELECT * FROM tbl_users WHERE  ProjectType=1 AND ProjectId='".$_GET['projid']."' AND ProjectSubHeadId='".$subheadid."' AND UnderProdStatus IN (1,0) AND SurveyMatch=1";
            $rncnt2 = getRow($sql2);
        }
        if($val == 'capacity'){
            $sql2 = "SELECT * FROM tbl_users WHERE PumpCapacity='$val2' AND ProjectType=1 AND  ProjectId='".$_GET['projid']."' AND ProjectSubHeadId='".$subheadid."'";
            $rncnt2 = getRow($sql2);
        }
        if($val == 'surveydone'){
            $sql2 = "SELECT * FROM tbl_users WHERE FieldSurveyDetails='$val2' AND ProjectType=1 AND  ProjectId='".$_GET['projid']."' AND ProjectSubHeadId='".$subheadid."'";
            $rncnt2 = getRow($sql2);
        }
        
        if($val == 'surveypending'){
            $sql2 = "SELECT * FROM tbl_users WHERE FieldSurveyDetails='$val2' AND ProjectType=1 AND ProjectId='".$_GET['projid']."' AND ProjectSubHeadId='".$subheadid."'";
            $rncnt2 = getRow($sql2);
        }
        if($val == 'dispatch'){
            $sql2 = "SELECT * FROM tbl_sell ts INNER JOIN tbl_users tu ON tu.id=ts.CustId WHERE tu.ProjectType=1 AND tu.ProjectId='".$_GET['projid']."' AND tu.ProjectSubHeadId='".$subheadid."' AND ts.Inst_Dispatcher_Otp_Verify=1";
            $rncnt2 = getRow($sql2);
        }
       
        if($val == 'installation'){
            if($val2 == 'Yes'){
                 $sql2 = "SELECT * FROM tbl_installations ti 
                    LEFT JOIN tbl_users tu ON ti.CustId=tu.id WHERE tu.ProjectId='".$_GET['projid']."' AND tu.Roll=5 AND tu.FieldSurveyDetails=1 AND ti.InstallStatus='Yes' AND ti.Type=2 AND tu.ProjectSubHeadId='".$subheadid."'";
                $rncnt2 = getRow($sql2);    
            }
            else{
              $sql2 = "SELECT * FROM tbl_users tu WHERE tu.ProjectId = '".$_GET['projid']."' AND tu.ProjectSubHeadId = '".$subheadid."' AND tu.Roll = 5 AND tu.FieldSurveyDetails = 1 AND tu.ProjectType = 1 AND tu.id NOT IN ( SELECT ti.CustId FROM tbl_installations ti WHERE ti.InstallStatus = 'Yes' AND ti.Type = 2 )";  
            $rncnt2 = getRow($sql2);    
                
            }
            
        }
        if($val == 'dataupload'){
            $sql2 = "SELECT * FROM tbl_installations ts INNER JOIN tbl_users tu ON tu.id=ts.CustId WHERE tu.ProjectType=1 AND ts.DataUploadStatus='$val2' AND tu.ProjectId='".$_GET['projid']."' AND tu.ProjectSubHeadId='".$subheadid."'";
            $rncnt2 = getRow($sql2);
        }
        if($val == 'inspection'){
           if($val2 == 'Yes'){
                 $sql2 = "SELECT * FROM tbl_installations ti 
                    LEFT JOIN tbl_users tu ON ti.CustId=tu.id WHERE tu.ProjectId='".$_GET['projid']."' AND tu.Roll=5 AND tu.FieldSurveyDetails=1 AND ti.PoInspection='Yes' AND ti.Type=2 AND tu.ProjectSubHeadId='".$subheadid."'";
                $rncnt2 = getRow($sql2);    
            }
            else{
              $sql2 = "SELECT * FROM tbl_users tu WHERE tu.ProjectId = '".$_GET['projid']."' AND tu.ProjectSubHeadId = '".$subheadid."' AND tu.Roll = 5 AND tu.FieldSurveyDetails = 1 AND tu.ProjectType = 1 AND tu.id NOT IN ( SELECT ti.CustId FROM tbl_installations ti WHERE ti.PoInspection = 'Yes' AND ti.Type = 2 )";  
            $rncnt2 = getRow($sql2);    
                
            }
        }
        if($val == 'inspectiondis'){
            $sql2 = "SELECT * FROM tbl_installations ts INNER JOIN tbl_users tu ON tu.id=ts.CustId WHERE tu.ProjectType=1 AND ts.InspectionDiscrepancy='$val2'  AND tu.ProjectId='".$_GET['projid']."' AND tu.ProjectSubHeadId='".$subheadid."'";
            $rncnt2 = getRow($sql2);
        }
        if($val == 'billsubmitted'){
            $sql2 = "SELECT * FROM tbl_installations ts INNER JOIN tbl_users tu ON tu.id=ts.CustId WHERE tu.ProjectType=1 AND ts.CircleOfficeStatus='$val2' AND tu.ProjectId='".$_GET['projid']."' AND tu.ProjectSubHeadId='".$subheadid."'";
            $rncnt2 = getRow($sql2);
        }
        if($val == 'dcr'){
            $sql2 = "SELECT * FROM tbl_installations ts INNER JOIN tbl_users tu ON tu.id=ts.CustId WHERE tu.ProjectType=1 AND ts.DcrVerify='$val2'  AND tu.ProjectId='".$_GET['projid']."' AND tu.ProjectSubHeadId='".$subheadid."'";
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
                    <th>Comapany Name</th>
                    <th>Total<br> Application<br> Received</th>
                   
                    <th>Total Survey Done</th>
                    <th>Total Survey Pending</th>
                    <th>Under Production</th>
         
                    <th>Parital Material Dispatch</th>
                    <th>Material Dispatch</th>
                    <th>Material Dispatch Pending</th>
                    <th>Installation Done</th>
                    <th>Installation Pending</th>
                    <th>Data Upload Done</th>
                    <th>Data Upload Pending</th>
                    <th>Inspection Done</th>
                    <th>Inspection Discrepancy</th>
                    <th>Inspection Pending</th>
                    <th>Bill Submitted</th>
                    <th>Bill in Process</th>
                    <th>DCR Verification Done</th>
                    <th>DCR Verification Pending</th>
         
                </thead>
                
                <tbody>
                    <?php 
                    $i=1;
                    $sql = "SELECT * FROM tbl_project_sub_head WHERE UnderBy='".$_GET['projid']."' ";
                    if($_REQUEST['SubHeadProjectId']!=''){
                        $SubHeadProjectId = $_REQUEST['SubHeadProjectId'];
                        $ReplaceDistrict = str_replace(",","','",$SubHeadProjectId);

                        if($SubHeadProjectId == 'all'){
                            $sql.="";
                        }
                        else{
                           $sql.=" AND id IN('$ReplaceDistrict')";
                        }
                    }
                    $sql.=" ORDER BY Name ASC";
                    // /echo $sql;
                    $row = getList($sql);
                    foreach($row as $result){
                        $totapp+=getDetails('totapp',$result['id'],'');
                        
                        $totsurveydone+=getDetails('surveydone',$result['id'],'1');
                        
                        $totunderproduction+=getDetails('underproduction',$result['id'],'1');
                       
                        $totsurveypending+=getDetails('surveypending',$result['id'],'0');
                        $totdispatch+=getDetails('dispatch',$result['id'],'');
                        $totinstallationdone+=getDetails('installation',$result['id'],'Yes');
                        $totinstallationpending+=getDetails('dispatch',$result['id'],'') - getDetails('installation',$result['id'],'Yes');
                        $totdatauploadone+=getDetails('dataupload',$result['id'],'Yes');
                        $totdatauploapending+=getDetails('installation',$result['id'],'Yes') - getDetails('dataupload',$result['id'],'Yes');
                        $totinspectiondone+=getDetails('inspection',$result['id'],'Yes');
                        $totinspectionpending+=getDetails('dataupload',$result['id'],'Yes') - getDetails('inspection',$result['id'],'Yes');
                        $totinspectiondis+=getDetails('inspectiondis',$result['id'],'Yes');
                        
                        $totbillsubmitted+=getDetails('billsubmitted',$result['id'],'Yes');
                        $totbillpending+=getDetails('billsubmitted',$result['id'],'No');
                        
                        $totdcrdone+=getDetails('dcr',$result['id'],'Yes');
                        $totdcrpending+=getDetails('dcr',$result['id'],'No');
                    ?>
                    <tr>
                        <td><?php echo $i;?></td>
                        <td style="font-weight:600;text-align:left;padding:3px;"><?php echo $result['Name']; ?></td>
                        <td><a href="total-beneficiary.php?roll=totapp&projid=<?php echo $_REQUEST['projid'];?>&val=&title=Total Application Received&subheadid=<?php echo $result['id'];?>" target="_blank"><?php echo getDetails('totapp',$result['id'],'');?></a></td>
                        
                        <td><a href="total-beneficiary.php?roll=surveydone&projid=<?php echo $_REQUEST['projid'];?>&val=1&subheadid=<?php echo $result['id'];?>&title=Survey Done As Per Portal" target="_blank"><?php echo getDetails('surveydone',$result['id'],'1');?></a></td>
                      
                        <td style="background-color:#fee2d6;"><a href="total-beneficiary.php?roll=surveypending&projid=<?php echo $_REQUEST['projid'];?>&val=0&subheadid=<?php echo $result['id'];?>&title=Survey Pending" target="_blank"><?php echo getDetails('surveypending',$result['id'],'0');?></a></td>
                        
                        <td style="background-color:#fee2d6;"><a href="total-beneficiary.php?roll=underproduction&projid=<?php echo $_REQUEST['projid'];?>&val=&subheadid=<?php echo $result['id'];?>&title=Under Production" target="_blank"><?php echo getDetails('underproduction',$result['id'],'0');?></a></td>
                       
                        <td>0</td>
                        <td><a href="total-beneficiary.php?roll=dispatch&projid=<?php echo $_REQUEST['projid'];?>&val=&subheadid=<?php echo $result['id'];?>&title=Material Dispatch" target="_blank"><?php echo getDetails('dispatch',$result['id'],'');?></a></td>
                        
                        <td><?php echo getDetails('surveydone',$result['id'],'1') - getDetails('dispatch',$result['id'],'');?></td>
                        
                        <td><a href="total-beneficiary.php?roll=installation&projid=<?php echo $_REQUEST['projid'];?>&val=Yes&subheadid=<?php echo $result['id'];?>&title=Installation Done" target="_blank"><?php echo getDetails('installation',$result['id'],'Yes');?></a></td>
                        
                        <td style="background-color:#fee2d6;"><a href="total-beneficiary.php?roll=installation&projid=<?php echo $_REQUEST['projid'];?>&val=No&subheadid=<?php echo $result['id'];?>&title=Installation Pending" target="_blank"><?php echo getDetails('dispatch',$result['id'],'') - getDetails('installation',$result['id'],'Yes');?></a></td>
                        
                        <td><a href="total-beneficiary.php?roll=dataupload&projid=<?php echo $_REQUEST['projid'];?>&val=Yes&title=Data Upload Done" target="_blank"><?php echo getDetails('dataupload',$result['id'],'Yes');?></a></td>
                        
                        <td style="background-color:#fee2d6;"><a href="total-beneficiary.php?roll=dataupload&projid=<?php echo $_REQUEST['projid'];?>&val=No&subheadid=<?php echo $result['id'];?>&title=Data Upload Pending" target="_blank"><?php echo getDetails('installation',$result['id'],'Yes') - getDetails('dataupload',$result['id'],'Yes');?></a></td>
                        
                        <td><a href="total-beneficiary.php?roll=inspection&projid=<?php echo $_REQUEST['projid'];?>&val=Yes&subheadid=<?php echo $result['id'];?>&title=Inspection Done" target="_blank"><?php echo getDetails('inspection',$result['id'],'Yes');?></a></td>
                        
                        <td><a href="total-beneficiary.php?roll=inspectiondis&projid=<?php echo $_REQUEST['projid'];?>&val=Yes&subheadid=<?php echo $result['id'];?>&title=Inspection Discrepancy" target="_blank"><?php echo getDetails('inspectiondis',$result['id'],'Yes');?></a></td>
                        
                        <td style="background-color:#fee2d6;"><a href="total-beneficiary.php?roll=inspection&projid=<?php echo $_REQUEST['projid'];?>&val=No&subheadid=<?php echo $result['id'];?>&title=Inspection Pending" target="_blank"><?php echo getDetails('dataupload',$result['id'],'Yes') - getDetails('inspection',$result['id'],'Yes');?></a></td>
                        
                        <td><a href="total-beneficiary.php?roll=billsubmitted&projid=<?php echo $_REQUEST['projid'];?>&val=Yes&subheadid=<?php echo $result['id'];?>&title=Bill Submitted" target="_blank"><?php echo getDetails('billsubmitted',$result['id'],'Yes');?></a></td>
                        
                        <td style="background-color:#fee2d6;"><a href="total-beneficiary.php?roll=billsubmitted&projid=<?php echo $_REQUEST['projid'];?>&val=No&subheadid=<?php echo $result['id'];?>&title=Bill in Process" target="_blank"><?php echo getDetails('billsubmitted',$result['id'],'No');?></a></td>
                    
                     <td><a href="total-beneficiary.php?roll=dcr&projid=<?php echo $_REQUEST['projid'];?>&val=Yes&subheadid=<?php echo $result['id'];?>&title=DCR Verification Done" target="_blank"><?php echo getDetails('dcr',$result['id'],'Yes');?></a></td>
                      
                        <td style="background-color:#fee2d6;"><a href="total-beneficiary.php?roll=dcr&projid=<?php echo $_REQUEST['projid'];?>&val=No&subheadid=<?php echo $result['id'];?>&title=DCR Verification Pending" target="_blank"><?php echo getDetails('dcr',$result['id'],'No');?></a></td>
                        
                    
                    </tr>
                    <?php $i++;} ?>
                    
                    <tr>
                        <th colspan="2">TOTAL</th>
                        <th><a href="total-beneficiary.php?roll=totapp&dist=&projid=<?php echo $_REQUEST['projid'];?>&val=&subheadid=<?php echo $result['id'];?>" target="_blank"><?php echo $totapp;?></a></th>
                       
                        <th><a href="total-beneficiary.php?roll=surveydone&dist=&projid=<?php echo $_REQUEST['projid'];?>&val=1&subheadid=<?php echo $result['id'];?>" target="_blank"><?php echo $totsurveydone;?></a></th>
                       
                        <th><a href="total-beneficiary.php?roll=surveypending&dist=&projid=<?php echo $_REQUEST['projid'];?>&val=0&subheadid=<?php echo $result['id'];?>" target="_blank"><?php echo $totsurveypending;?></a></th>
                        
                        
                         <th><a href="total-beneficiary.php?roll=underproduction&dist=&projid=<?php echo $_REQUEST['projid'];?>&val=1&subheadid=<?php echo $result['id'];?>" target="_blank"><?php echo $totunderproduction;?></a></th>
                        <th>0</th>
                       
                        
                        <th><a href="total-beneficiary.php?roll=dispatch&dist=&projid=<?php echo $_REQUEST['projid'];?>&val=" target="_blank"><?php echo $totdispatch;?></a></th>
                        
                        <th><?php echo $totsurveydone-$totdispatch;?></th>
                        
                        <th><a href="total-beneficiary.php?roll=installation&dist=&projid=<?php echo $_REQUEST['projid'];?>&val=Yes" target="_blank"><?php echo $totinstallationdone;?></a></th>
                        
                        <th><a href="total-beneficiary.php?roll=installation&dist=&projid=<?php echo $_REQUEST['projid'];?>&val=No" target="_blank"><?php echo $totinstallationpending;?></a></th>
                        
                        <th><a href="total-beneficiary.php?roll=dataupload&dist=&projid=<?php echo $_REQUEST['projid'];?>&val=Yes" target="_blank"><?php echo $totdatauploadone;?></a></th>
                        
                        <th><a href="total-beneficiary.php?roll=dataupload&dist=&projid=<?php echo $_REQUEST['projid'];?>&val=No" target="_blank"><?php echo $totdatauploapending;?></a></th>
                        
                        <th><a href="total-beneficiary.php?roll=inspection&dist=&projid=<?php echo $_REQUEST['projid'];?>&val=Yes" target="_blank"><?php echo $totinspectiondone;?></a></th>
                        
                        <th><a href="total-beneficiary.php?roll=inspectiondis&dist=&projid=<?php echo $_REQUEST['projid'];?>&val=Yes" target="_blank"><?php echo $totinspectiondis;?></a></th>
                        
                        <th><a href="total-beneficiary.php?roll=inspection&dist=&projid=<?php echo $_REQUEST['projid'];?>&val=No" target="_blank"><?php echo $totinspectionpending;?></a></th>
                        
                        <th><a href="total-beneficiary.php?roll=billsubmitted&dist=&projid=<?php echo $_REQUEST['projid'];?>&val=Yes" target="_blank"><?php echo $totbillsubmitted;?></a></th>
                        
                        <th><a href="total-beneficiary.php?roll=billsubmitted&dist=&projid=<?php echo $_REQUEST['projid'];?>&val=No" target="_blank"><?php echo $totbillpending;?></a></th>
                    
                      <th><a href="total-beneficiary.php?roll=dcr&dist=&projid=<?php echo $_REQUEST['projid'];?>&val=Yes" target="_blank"><?php echo $totdcrdone;?></a></th>
                        
                        <th><a href="total-beneficiary.php?roll=dcr&dist=&projid=<?php echo $_REQUEST['projid'];?>&val=No" target="_blank"><?php echo $totdcrpending;?></a></th>
                 
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