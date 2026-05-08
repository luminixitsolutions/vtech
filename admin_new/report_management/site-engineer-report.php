<?php 
session_start();
include_once '../config.php';
include_once '../auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Reports";
$Page = "Site-Engineer-Reports";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | Sell Reports</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="" />
<meta name="keywords" content="">
<meta name="author" content="" />
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
<h4 class="font-weight-bold py-3 mb-0">Site Engineer Report</h4>

<div class="card" style="padding: 10px;">
     <div id="accordion2">
<div class="card mb-2">
                                        
                                        <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                            <div class="" style="padding:5px;">
                                                <form id="validation-form" method="post" enctype="multipart/form-data" action="">
<div class="form-row">

<div class="form-group col-md-3">
<label class="form-label">Installer Employee</label>
 <select class="select2-demo form-control" name="EmpId" id="EmpId">
<option selected="" value="all">All</option>
 <?php 
  $sql12 = "SELECT tu.*,tut.Name FROM tbl_users tu LEFT JOIN tbl_user_type tut ON tut.id=tu.Roll WHERE tu.Roll IN(34,35,36,37) ORDER BY tu.CreatedDate DESC";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($_REQUEST["EmpId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Fname']." (".$result['Name'].")"; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

  <!--<div class="form-group col-md-3">
<label class="form-label">Customers</label>
 <select class="select2-demo form-control" name="CustId" id="CustId">
<option selected="" value="all">All</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_users WHERE Roll = '5' AND Status='1'";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($_REQUEST["CustId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Fname']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>-->

  

  


<div class="form-group col-md-2">
<label class="form-label">From Date </label>
<input type="date" name="FromDate" id="FromDate" class="form-control" value="<?php echo $_REQUEST['FromDate'] ?>" autocomplete="off" required>
</div>
<div class="form-group col-md-2">
<label class="form-label">To Date</label>
<input type="date" name="ToDate" id="ToDate" class="form-control" value="<?php echo $_REQUEST['ToDate'] ?>" autocomplete="off" required>
</div>
<input type="hidden" name="Search" value="Search">
<div class="form-group col-md-1" style="padding-top:30px;">
<button type="submit" name="submit" class="btn btn-primary btn-finish">Search</button>
</div>
<?php if(isset($_REQUEST['Search'])) {?>
<div class="col-md-1">
<label class="form-label d-none d-md-block">&nbsp;</label>
<a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-info btn-block" data-toggle="tooltip" data-placement="top" data-original-title="Clear Filter">X</a>
</div>
<?php } ?>
</div>

</form>
                                            </div>
                                        </div>
                                    </div>
   </div>
 <div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
               <th>Sr No</th>
               <th>From Date</th> 
               <th>To Date</th> 
               <th>Employee Name</th> 
              
               <th>Survey Done</th> 
              <th>Survey Pending</th> 
              <th>Dispatch Done</th> 
              <th>Dispatch Pending</th> 
              <th>Installation Done</th> 
              <th>Installation Pending</th> 
              <th>Inspection Done</th> 
              <th>Inspection Pending</th> 
               
               
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $sql = "SELECT tu.*,tut.Name FROM tbl_users tu LEFT JOIN tbl_user_type tut ON tut.id=tu.Roll WHERE tu.Roll IN(34,35,36,37)";
                    
            if($_POST['EmpId']){
                $EmpId = $_POST['EmpId'];
                if($EmpId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND tu.id='$EmpId'";
                }
            }
            
            $sql.= " ORDER BY tu.id DESC";
             //echo $sql;
             $row = getList($sql);
             foreach($row as $result){
                 
                 //survey query
                $sql2 = "SELECT * FROM tbl_users WHERE FieldSurveyId='".$result['id']."' AND FieldSurveyDetails=1 AND ProjectType=1";
                 if($_POST['FromDate']){
                $FromDate = $_POST['FromDate'];
                $sql2.= " AND FieldSurveyDate>='$FromDate'";
                }
                if($_POST['ToDate']){
                    $ToDate = $_POST['ToDate'];
                    $sql2.= " AND FieldSurveyDate<='$ToDate'";
                }
                 $rncnt2 = getRow($sql2);
                 
                 $sql22 = "SELECT * FROM tbl_users WHERE FieldSurveyId='".$result['id']."' AND FieldSurveyDetails=0 AND ProjectType=1";
                 if($_POST['FromDate']){
                $FromDate = $_POST['FromDate'];
                $sql22.= " AND FieldSurveyDate>='$FromDate'";
                }
                if($_POST['ToDate']){
                    $ToDate = $_POST['ToDate'];
                    $sql22.= " AND FieldSurveyDate<='$ToDate'";
                }
                 $rncnt22 = getRow($sql22);
                 
                 //dispatch query
                 
                 $sql2 = "SELECT tdo.*,tu.Fname,tu.Phone,tu.Address FROM tbl_sell tdo 
                    LEFT JOIN tbl_users tu ON tdo.CustId=tu.id WHERE tdo.DispatcherId='".$result['id']."' AND tdo.Inst_Dispatcher_Otp_Verify=1";
                 if($_POST['FromDate']){
                $FromDate = $_POST['FromDate'];
                $sql2.= " AND tdo.Inst_Dispatcher_Date>='$FromDate'";
                }
                if($_POST['ToDate']){
                    $ToDate = $_POST['ToDate'];
                    $sql2.= " AND tdo.Inst_Dispatcher_Date<='$ToDate'";
                }
                 $rncnt3 = getRow($sql2);
                 
                 $sql22 = "SELECT tdo.*,tu.Fname,tu.Phone,tu.Address FROM tbl_sell tdo 
                    LEFT JOIN tbl_users tu ON tdo.CustId=tu.id WHERE tdo.DispatcherId='".$result['id']."' AND tdo.Inst_Dispatcher_Otp_Verify=0";
                 if($_POST['FromDate']){
                $FromDate = $_POST['FromDate'];
                $sql22.= " AND tdo.Inst_Dispatcher_Date>='$FromDate'";
                }
                if($_POST['ToDate']){
                    $ToDate = $_POST['ToDate'];
                    $sql22.= " AND tdo.Inst_Dispatcher_Date<='$ToDate'";
                }
                 $rncnt23 = getRow($sql22);
                 
                 //installation query
                 $sql2 = "SELECT * FROM tbl_users WHERE InstallerId='".$result['id']."' AND InstOtpVerify=1 AND ProjectType=1";
                 if($_POST['FromDate']){
                $FromDate = $_POST['FromDate'];
                $sql2.= " AND InstallationDate>='$FromDate'";
                }
                if($_POST['ToDate']){
                    $ToDate = $_POST['ToDate'];
                    $sql2.= " AND InstallationDate<='$ToDate'";
                }
                 $rncnt4 = getRow($sql2);
                 
                 $sql22 = "SELECT * FROM tbl_users WHERE InstallerId='".$result['id']."' AND InstOtpVerify=0 AND ProjectType=1";
                 if($_POST['FromDate']){
                $FromDate = $_POST['FromDate'];
                $sql22.= " AND InstallationDate>='$FromDate'";
                }
                if($_POST['ToDate']){
                    $ToDate = $_POST['ToDate'];
                    $sql22.= " AND InstallationDate<='$ToDate'";
                }
                 $rncnt24 = getRow($sql22);
                 
                 //inspection query
                 $sql2 = "SELECT * FROM tbl_users WHERE InspectionId='".$result['id']."' AND InspectionOtpVerify=1 AND ProjectType=1";
                 if($_POST['FromDate']){
                $FromDate = $_POST['FromDate'];
                $sql2.= " AND InstInspectionDate>='$FromDate'";
                }
                if($_POST['ToDate']){
                    $ToDate = $_POST['ToDate'];
                    $sql2.= " AND InstInspectionDate<='$ToDate'";
                }
                 $rncnt5 = getRow($sql2);
                 
                 $sql22 = "SELECT * FROM tbl_users WHERE InspectionId='".$result['id']."' AND InspectionOtpVerify=0 AND ProjectType=1";
                 if($_POST['FromDate']){
                $FromDate = $_POST['FromDate'];
                $sql22.= " AND InstInspectionDate>='$FromDate'";
                }
                if($_POST['ToDate']){
                    $ToDate = $_POST['ToDate'];
                    $sql22.= " AND InstInspectionDate<='$ToDate'";
                }
                 $rncnt25 = getRow($sql22);
             ?>
             <tr>
                 <td><?php echo $i;?></td>
                 <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$_REQUEST['FromDate'])));?></td>
                 <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$_REQUEST['ToDate'])));?></td>
                  <td><?php echo $result['Fname']." (".$result['Name'].")"; ?></td>
                  <td><?php echo $rncnt2;?></td>
                  <td><?php echo $rncnt22;?></td>
                  <td><?php echo $rncnt3;?></td>
                  <td><?php echo $rncnt23;?></td>
                  <td><?php echo $rncnt4;?></td>
                  <td><?php echo $rncnt24;?></td>
                  <td><?php echo $rncnt5;?></td>
                  <td><?php echo $rncnt25;?></td>
             </tr>
             <?php $i++;} ?>
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

<script type="text/javascript">
 
    $(document).ready(function() {
    $('#example').DataTable({
       "scrollX": true,
        dom: 'Bfrtip',
        buttons: [
            'excelHtml5'
           
        ]
    });
});
</script>
</body>
</html>
