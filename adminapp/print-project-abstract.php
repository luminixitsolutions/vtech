<?php session_start();
$sessionid = session_id();
require_once 'config.php';
require_once 'auth.php';
$PageName = "Home";

$uid = $_REQUEST['uid'];    
//$_SESSION['Location'] = $city_id;
if($_REQUEST['uid'] == ''){
  $uid = $_SESSION['User']['id'];
}
else{
$uid = $_REQUEST['uid'];    
$sql11 = "SELECT * FROM tbl_users WHERE id='$uid'";
$row = getRecord($sql11);
$_SESSION['User'] = $row;
}

?>
<!doctype html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="generator" content="">
    <title><?php echo $Proj_Title; ?></title>

    <!-- manifest meta -->
    <meta name="apple-mobile-web-app-capable" content="yes">

    <!-- Favicons -->
    <link rel="manifest" href="manifest.json" />

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="img/favicon180.png" sizes="180x180">
    <link rel="icon" href="img/favicon32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="img/favicon16.png" sizes="16x16" type="image/png">

    <!-- Material icons-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&amp;display=swap" rel="stylesheet">

    <!-- swiper CSS -->
    <link href="vendor/swiper/css/swiper.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js">
</script>

    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet" id="style">
    <link rel="stylesheet" href="dist/css/styles.css" />
   
</head>

  

<body class="body-scroll d-flex flex-column h-100 menu-overlay" data-page="shop">
    
    
    
 <?php include_once 'sidebar.php'; ?>

    <!-- Begin page content -->
    <main class="flex-shrink-0 main has-footer">
        <!-- Fixed navbar -->
      <?php include_once 'top_header.php'; ?>

        <!-- page content start -->
<!-- page content start -->
   
<style>
  .table-container {
    overflow-x: auto;
    margin: 20px 0;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
  }

  .styled-table {
    border-collapse: collapse;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-size: 13px;
    width: 100%;
    min-width: 1200px;
    background-color: #fff;
    border-radius: 10px;
    overflow: hidden;
  }

  .styled-table thead {
    background-color: #f97316;
    color: white;
    text-transform: uppercase;
    font-size: 12px;
    letter-spacing: 0.5px;
  }

  .styled-table th, .styled-table td {
    padding: 10px 12px;
    text-align: center;
    border: 1px solid #eee;
    white-space: nowrap;
  }

  .styled-table tbody tr:nth-child(even) {
    background-color: #f9f9f9;
  }

  .styled-table tbody tr:hover {
    background-color: #ffe8cc;
  }

  .styled-table td:nth-child(2) {
    font-weight: 600;
    text-align: left;
  }

  .styled-table td[style*="background-color:#fee2d6"] {
    background-color: #ffebd3 !important;
    font-weight: bold;
  }

  @media (max-width: 768px) {
    .styled-table th, .styled-table td {
      font-size: 11px;
      padding: 8px;
    }
  }
  .styled-table td {
  padding: 10px 12px;
  text-align: center;
  border: 1px solid #eee;
  white-space: nowrap;
  color: #000; /* Ensures black text in all cells */
}

.whitecolor{
    color: #fff;
}
</style>
<?php 
$sql = "SELECT * FROM tbl_project_sub_head WHERE id='".$_GET['SubHeadProjectId']."'";
$row = getRecord($sql);
$Projectname = $row['Name'];
?>

        <div class="main-container  text-center" style="background-color:#fff;">

             <div class="container ">
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
                             <div class="table-container">
  <table class="styled-table">
                <thead>
                <tr>
                    <th class="whitecolor">Sr No</th>
                    <th class="whitecolor">DISTRICT</th>
                    <th class="whitecolor">Total<br> Application<br> Received</th>
                    <th class="whitecolor">3 HP</th>
                     <th class="whitecolor">5 HP</th>
                    <th class="whitecolor">7.5 HP</th>
                    <th class="whitecolor">Survey Done As Per Portal</th>
                    <th class="whitecolor">Survey Dicrepancy</th>
                    <th class="whitecolor">Rejected</th>
                    <th class="whitecolor">Survey Pending</th>
                    <th class="whitecolor">Material Dispatch</th>
                    <th class="whitecolor">Installation Done</th>
                    <th class="whitecolor">Installation Pending</th>
                    <th class="whitecolor">Data Upload Done</th>
                    <th class="whitecolor">Data Upload Pending</th>
                    <th class="whitecolor">Inspection Done</th>
                    <th class="whitecolor">Inspection Discrepancy</th>
                    <th class="whitecolor">Inspection Pending</th>
                     <th class="whitecolor">DCR Verification Pending</th>
                <th class="whitecolor">DCR Verification Done</th>
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
                        $totinstallationpending+=getDetails('installation',$result['District'],'No');
                        $totdatauploadone+=getDetails('dataupload',$result['District'],'Yes');
                        $totdatauploapending+=getDetails('dataupload',$result['District'],'No');
                        $totinspectiondone+=getDetails('inspection',$result['District'],'Yes');
                        $totinspectionpending+=getDetails('inspection',$result['District'],'No');
                        $totinspectiondis+=getDetails('inspectiondis',$result['District'],'Yes');
                        
                        $totdcrpending+=getDetails('dcr',$result['District'],'No');
                        $totdcrdone+=getDetails('dcr',$result['District'],'Yes');
                    ?>
                    <tr>
                        <td><?php echo $i;?></td>
                        <td style="font-weight:600;text-align:left;padding:3px;"><?php echo $result['District']; ?></td>
                        <td><?php echo getDetails('totapp',$result['District'],'');?></td>
                        <td><?php echo getDetails('capacity',$result['District'],'18');?></td>
                        <td><?php echo getDetails('capacity',$result['District'],'19');?></td>
                        <td><?php echo getDetails('capacity',$result['District'],'20');?></td>
                        <td><?php echo getDetails('surveydone',$result['District'],'1');?></td>
                        <td></td>
                        <td><?php echo getDetails('surveyrejected',$result['District'],'2');?></td>
                        <td style="background-color:#fee2d6;"><?php echo getDetails('surveypending',$result['District'],'0');?></td>
                        
                        <td><?php echo getDetails('dispatch',$result['District'],'');?></td>
                        <td><?php echo getDetails('installation',$result['District'],'Yes');?></td>
                        <td style="background-color:#fee2d6;"><?php echo getDetails('installation',$result['District'],'No');?></td>
                        <td><?php echo getDetails('dataupload',$result['District'],'Yes');?></td>
                        <td style="background-color:#fee2d6;"><?php echo getDetails('dataupload',$result['District'],'No');?></td>
                        <td><?php echo getDetails('inspection',$result['District'],'Yes');?></td>
                        <td><?php echo getDetails('inspectiondis',$result['District'],'Yes');?></td>
                        <td style="background-color:#fee2d6;"><?php echo getDetails('inspection',$result['District'],'No');?></td>
                    <td><?php echo getDetails('dcr',$result['District'],'No');?></td>
                    <td><?php echo getDetails('dcr',$result['District'],'Yes');?></td>
                    </tr>
                    <?php $i++;} ?>
                    
                    <tr>
                        <th colspan="2">TOTAL</th>
                        <th><?php echo $totapp;?></th>
                        <th><?php echo $tot3hp;?></th>
                        <th><?php echo $tot5hp;?></th>
                        <th><?php echo $tot7hp;?></th>
                        <th><?php echo $totsurveydone;?></th>
                        <th></th>
                        <th><?php echo $totsurveyreject;?></th>
                        <th><?php echo $totsurveypending;?></th>
                        <th><?php echo $totdispatch;?></th>
                        <th><?php echo $totinstallationdone;?></th>
                        <th><?php echo $totinstallationpending;?></th>
                        <th><?php echo $totdatauploadone;?></th>
                        <th><?php echo $totdatauploapending;?></th>
                        <th><?php echo $totinspectiondone;?></th>
                        <th><?php echo $totinspectiondis;?></th>
                        <th><?php echo $totinspectionpending;?></th>
                     <th><?php echo $totdcrpending;?></th>
                    <th><?php echo $totdcrdone;?></th>
                    </tr>
                </tbody>
                
             </table>
</div>
            
            </div>

           
           
                               
            
    </main>

    <!-- footer-->
  <?php include_once 'footer.php'; ?>


<script src="dist/aos.js"></script>
    <script>
      AOS.init({
        easing: 'ease-in-out-sine'
      });
    </script>


    <!-- Required jquery and libraries -->
      <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- cookie js -->
    <script src="js/jquery.cookie.js"></script>

    <!-- Swiper slider  js-->
    <script src="vendor/swiper/js/swiper.min.js"></script>

    <!-- Customized jquery file  -->
    <script src="js/main.js"></script>
    <script src="js/color-scheme-demo.js"></script>

    <!-- PWA app service registration and works -->
    <script src="js/pwa-services.js"></script>

    <!-- page level custom script -->
    <script src="js/app.js"></script>

       <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
      <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
    <script>
        function logout(){
       Android.logout();
       window.location.href="logout.php";
  }
    </script>

</body>

</html>
