<?php 
session_start();
include_once '../config.php';
include_once '../auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Reports";
$Page = "Delay-Calculation-Report-2";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | Delay Calculation Report</title>
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
<h4 class="font-weight-bold py-3 mb-0">Delay Calculation Report</h4>

<div class="card" style="padding: 10px;">
     <div id="accordion2">
<div class="card mb-2">
                                        
                                        <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                            <div class="" style="padding:5px;">
                                                <form id="validation-form" method="post" enctype="multipart/form-data" action="">
<div class="form-row">

 
  <div class="form-group col-md-3">
<label class="form-label">Customers</label>
 <select class="select2-demo form-control" name="CustId" id="CustId">
<option selected="" value="all">All</option>
 <?php 
  $sql12 = "SELECT id,Fname,BeneficiaryId FROM tbl_users WHERE Roll = '5' AND ProjectType='1'";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($_REQUEST["CustId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Fname']." (".$result['BeneficiaryId'].")"; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

  
   <div class="form-group col-md-3 Pump">
                                            <label class="form-label">State </label>
                                            <select class="select2-demo form-control" id="StateId" name="StateId" required="">
<option selected=""value="all">All State</option>
 <?php 
        $CountryId = $row7['CountryId'];
        $q = "select * from tbl_state WHERE CountryId='1' ORDER BY Name ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($_POST['StateId']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>
                                           
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                        <div class="form-group col-md-3 Pump">
                                            <label class="form-label">District </label>
                                            <select class="select2-demo form-control" id="District" name="District" required="">
<option selected="" value="all">All District</option>
 <?php 
        $CountryId = $row7['CountryId'];
        $q = "select DISTINCT(District) As District from tbl_users WHERE District!='' ORDER BY District ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($_POST['District']==$rw['District']){ ?> selected <?php } ?> value="<?php echo $rw['District']; ?>"><?php echo $rw['District']; ?></option>
              <?php } ?>
</select>
                                           
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                        <div class="form-group col-md-3 Pump">
                                            <label class="form-label">Taluka </label>
                                            <select class="select2-demo form-control" id="Taluka" name="Taluka" required="">
<option selected="" value="all">All Taluka</option>
 <?php 
        $CountryId = $row7['CountryId'];
        $q = "select DISTINCT(Taluka) As Taluka from tbl_users WHERE Taluka!='' ORDER BY Taluka ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($_POST['Taluka']==$rw['Taluka']){ ?> selected <?php } ?> value="<?php echo $rw['Taluka']; ?>"><?php echo $rw['Taluka']; ?></option>
              <?php } ?>
</select>
                                           
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                        <div class="form-group col-md-3 Pump">
                                            <label class="form-label">Village </label>
                                            <select class="select2-demo form-control" id="Village" name="Village" required="">
<option selected="" value="all">All Village</option>
 <?php 
        $CountryId = $row7['CountryId'];
        $q = "select DISTINCT(Village) As Village from tbl_users WHERE Village!='' ORDER BY Village ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($_POST['Village']==$rw['Village']){ ?> selected <?php } ?> value="<?php echo $rw['Village']; ?>"><?php echo $rw['Village']; ?></option>
              <?php } ?>
</select>
                                           
                                            <div class="clearfix"></div>
                                        </div>

  

  <div class="form-group col-md-3">
            <label class="form-label">Project</label>
<select class="form-control" id="ProjectId" name="ProjectId" onchange="getSubHead(this.value)">
<option selected="" value="all">All Project</option>
  <?php 
        $q = "select * from tbl_common_master WHERE Status='1' AND Roll=24 ORDER BY Name ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($_POST['ProjectId']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>
    </div>

     <div class="form-group col-md-3">
            <label class="form-label">Project Sub Head </label>
<select class="form-control" id="ProjectSubHeadId" name="ProjectSubHeadId" >
<option selected="" value="all">All Sub Head</option>
  <?php 
        $q = "select * from tbl_project_sub_head WHERE Status='1' AND UnderBy='".$_POST['ProjectId']."'";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($_POST['ProjectSubHeadId']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>
    </div>
    
         <div class="form-group col-md-3">
            <label class="form-label">From </label>
<select class="form-control" id="FromId" name="FromId" required>
<option selected="" disabled>Select</option>
<?php 
$sql = "SELECT * FROM tbl_delay_cal_flow";
$row = getList($sql);
foreach($row as $result){?>
  <option value="<?php echo $result['id'];?>" <?php if($_POST['FromId']==$result['id']){ ?> selected <?php } ?>><?php echo $result['name'];?></option>
  <?php } ?>
  
</select>
    </div>
    
    <div class="form-group col-md-3">
            <label class="form-label">To </label>
<select class="form-control" id="ToId" name="ToId" required>
<option selected="" disabled>Select</option>
 <?php 
$sql = "SELECT * FROM tbl_delay_cal_flow";
$row = getList($sql);
foreach($row as $result){?>
  <option value="<?php echo $result['id'];?>" <?php if($_POST['ToId']==$result['id']){ ?> selected <?php } ?>><?php echo $result['name'];?></option>
  <?php } ?>
</select>
    </div>

<!--<div class="form-group col-md-2">
<label class="form-label">From Date </label>
<input type="date" name="FromDate" id="FromDate" class="form-control" value="<?php echo $_POST['FromDate'] ?>" autocomplete="off">
</div>
<div class="form-group col-md-2">
<label class="form-label">To Date</label>
<input type="date" name="ToDate" id="ToDate" class="form-control" value="<?php echo $_POST['ToDate'] ?>" autocomplete="off">
</div>-->
<input type="hidden" name="Search" value="Search">
<div class="form-group col-md-1" style="padding-top:25px;">
<button type="submit" name="submit" class="btn btn-primary btn-finish">Search</button>
</div>
<?php if(isset($_POST['Search'])) {?>
<div class="col-md-1" style="padding-top:6px;">
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
   <?php if(isset($_POST['Search'])) {
        function calDays($fromdate,$todate){
            
                 $date1 = new DateTime($fromdate);
$date2 = new DateTime($todate);

// Calculate the difference
 $interval = $date1->diff($date2);

// Output the number of days
return $interval->days;
            }
            
            function flowName($id){
                global $conn;
                $sql = "SELECT * FROM tbl_delay_cal_flow WHERE id='$id'";
                $res = $conn->query($sql);
	            $row = $res->fetch_assoc();
	            return $row['name'];
            }
   ?>
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
               <th>Sr No</th>
                <th>Beneficiary ID</th>
               <th>Beneficiary Name</th>
                <th>Application No</th>
                <th>District</th>
                <th>Taluka</th>
                <th>Village</th>
                <th>Pump Capacity</th>
                <th>Date of <?php echo flowName($_REQUEST['FromId']);?></th>
                <th>Date of  <?php echo flowName($_REQUEST['ToId']);?></th>
                <th>No. Of Days</th>
                <th>No. Of Days Work</th>
                <th>No. Of Days Delay</th>
                
            </tr>
        </thead>
        <tbody>
            <?php 
           
           
            $i=1;
            $sql = "SELECT tu.*,tc.Name AS Pump_Capacity FROM tbl_users tu LEFT JOIN tbl_common_master tc ON tc.id=tu.PumpCapacity WHERE tu.Roll = '5' AND tu.ProjectType='1' AND tu.SurveyDetails='1' AND tu.ProjectSubHeadId!=0";
             if($_POST['CustId']){
                $CustId = $_POST['CustId'];
                if($CustId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND tu.id='$CustId'";
                }
            }
            
            if($_POST['StateId']){
                $StateId = $_POST['StateId'];
                if($StateId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND tu.StateId='$StateId'";
                }
            }
            
            if($_POST['District']){
                $District = $_POST['District'];
                if($District == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND tu.District='$District'";
                }
            }
            
            if($_POST['Taluka']){
                $Taluka = $_POST['Taluka'];
                if($Taluka == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND tu.Taluka='$Taluka'";
                }
            }
            
            if($_POST['Village']){
                $Village = $_POST['Village'];
                if($Village == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND tu.Village='$Village'";
                }
            }
            
            if($_POST['ProjectId']){
                $ProjectId = $_POST['ProjectId'];
                if($ProjectId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND tu.ProjectId='$ProjectId'";
                }
            }
            
            if($_POST['ProjectSubHeadId']){
                $ProjectSubHeadId = $_POST['ProjectSubHeadId'];
                if($ProjectSubHeadId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND tu.ProjectSubHeadId='$ProjectSubHeadId'";
                }
            }

           
            $sql.=" ORDER BY tu.id DESC";    
            //echo $sql;
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                $CoOrdSurvey = $row['SurveyDetails'];
$FieldSurvey = $row['FieldSurveyDetails'];

$sql88 = "SELECT * FROM tbl_sell WHERE CustId='".$row['id']."'";
$row88 = getRecord($sql88);
$rncnt88 = getRow($sql88);

$sql99 = "SELECT * FROM tbl_installations WHERE CustId='".$row['id']."'";
$row99 = getRecord($sql99);
$rncnt99 = getRow($sql99);

$sql77 = "SELECT Phone FROM tbl_project_sub_head WHERE id='".$row['ProjectSubHeadId']."'";
$row77 = getRecord($sql77);


if($_REQUEST['FromId'] == 1){
    if ($CoOrdSurvey == 1) {
       $fromval = date("d/m/Y", strtotime(str_replace('-', '/', $row['SelectionDate'])));
       $fromval2 = $row['SelectionDate'];
    }
    else{
       $fromval = "Not Yet";
       $fromval2 = date('Y-m-d');
    }
}
if($_REQUEST['ToId'] == 1){
    if ($CoOrdSurvey == 1) {
       $toval = date("d/m/Y", strtotime(str_replace('-', '/', $row['SelectionDate'])));
       $toval2 = $row['SelectionDate'];
    }
    else{
       $toval = "Not Yet";
       $toval2 = date('Y-m-d');
    }
}

if($_REQUEST['FromId'] == 2){
    if ($FieldSurvey == 1) {
       $fromval = date("d/m/Y", strtotime(str_replace('-', '/', $row['FieldSurveyDate'])));
       $fromval2 = $row['FieldSurveyDate'];
    }
    else{
       $fromval = "Not Yet";
       $fromval2 = date('Y-m-d');
    }
}
if($_REQUEST['ToId'] == 2){
    if ($FieldSurvey == 1) {
       $toval = date("d/m/Y", strtotime(str_replace('-', '/', $row['FieldSurveyDate'])));
       $toval2 = $row['FieldSurveyDate'];
    }
    else{
       $toval = "Not Yet";
       $toval2 = date('Y-m-d');
    }
}

if($_REQUEST['FromId'] == 3){
    if ($row88['Inst_Dispatcher_Otp_Verify'] == 1) {
       $fromval = date("d/m/Y", strtotime(str_replace('-', '/', $row88['Inst_Dispatcher_Date'])));
       $fromval2 = $row88['Inst_Dispatcher_Date'];
    }
    else{
       $fromval = "Not Yet";
       $fromval2 = date('Y-m-d');
    }
}
if($_REQUEST['ToId'] == 3){
    if ($row88['Inst_Dispatcher_Otp_Verify'] == 1) {
       $toval = date("d/m/Y", strtotime(str_replace('-', '/', $row88['Inst_Dispatcher_Date'])));
       $toval2 = $row88['Inst_Dispatcher_Date'];
    }
    else{
       $toval = "Not Yet";
       $toval2 = date('Y-m-d');
    }
}

if($_REQUEST['FromId'] == 4){
    if ($row99['InstallStatus'] == 'Yes') {
       $fromval = date("d/m/Y", strtotime(str_replace('-', '/', $row99['InstallationDate'])));
       $fromval2 = $row99['InstallationDate'];
    }
    else{
       $fromval = "Not Yet";
       $fromval2 = date('Y-m-d');
    }
}
if($_REQUEST['ToId'] == 4){
    if ($row99['InstallStatus'] == 'Yes') {
       $toval = date("d/m/Y", strtotime(str_replace('-', '/', $row99['InstallationDate'])));
       $toval2 = $row99['InstallationDate'];
    }
    else{
       $toval = "Not Yet";
       $toval2 = date('Y-m-d');
    }
}

if($_REQUEST['FromId'] == 5){
    if ($row99['PoInspection'] == 'Yes') {
       $fromval = date("d/m/Y", strtotime(str_replace('-', '/', $row99['PoInspectionDate'])));
       $fromval2 = $row99['PoInspectionDate'];
    }
    else{
       $fromval = "Not Yet";
       $fromval2 = date('Y-m-d');
    }
}
if($_REQUEST['ToId'] == 5){
    if ($row99['PoInspection'] == 'Yes') {
       $toval = date("d/m/Y", strtotime(str_replace('-', '/', $row99['PoInspectionDate'])));
       $toval2 = $row99['PoInspectionDate'];
    }
    else{
       $toval = "Not Yet";
       $toval2 = date('Y-m-d');
    }
}

if($_REQUEST['FromId'] == 6){
    if ($row99['DgmApproval'] == 'Yes') {
       $fromval = date("d/m/Y", strtotime(str_replace('-', '/', $row99['DgmApprovalDate'])));
       $fromval2 = $row99['DgmApprovalDate'];
    }
    else{
       $fromval = "Not Yet";
       $fromval2 = date('Y-m-d');
    }
}
if($_REQUEST['ToId'] == 6){
    if ($row99['DgmApproval'] == 'Yes') {
       $toval = date("d/m/Y", strtotime(str_replace('-', '/', $row99['DgmApprovalDate'])));
       $toval2 = $row99['DgmApprovalDate'];
    }
    else{
       $toval = "Not Yet";
       $toval2 = date('Y-m-d');
    }
}

if($_REQUEST['FromId'] == 7){
    if ($row99['Payment90'] == 'Yes') {
       if($row99['PaymentDate']!='0000-00-00'){
           $fromval = date("d/m/Y", strtotime(str_replace('-', '/', $row99['PaymentDate'])));
       }
       else { $fromval =  "Not Yet";}
       $fromval2 = $row99['PaymentDate'];
    }
    else{
       $fromval = "Not Yet";
       $fromval2 = date('Y-m-d');
    }
}
if($_REQUEST['ToId'] == 7){
    if ($row99['Payment90'] == 'Yes') {
       $toval = date("d/m/Y", strtotime(str_replace('-', '/', $row99['PaymentDate'])));
       $toval2 = $row99['PaymentDate'];
    }
    else{
       $toval = "Not Yet";
       $toval2 = date('Y-m-d');
    }
}

             ?>
            <tr>
    <td><?php echo $i; ?></td>
    <td><?php echo $row['BeneficiaryId']; ?></td>
    <td><?php echo $row['Fname']; ?></td>
    <td>NA</td>
    <td><?php echo $row['District']; ?></td>
    <td><?php echo $row['Taluka']; ?></td>
    <td><?php echo $row['Village']; ?></td>
    <td><?php echo $row['Pump_Capacity']; ?></td>
    <td><?php echo $fromval; ?></td>
    <td><?php echo $toval; ?></td>
    
    <td><?php echo calDays($fromval2, $toval2); ?></td>
    <td><?php echo $row77['Phone']; ?></td>
    <td><?php echo calDays($fromval2, $toval2)-$row77['Phone']; ?></td>
    
    
  
    
</tr>

           <?php $i++;} ?>

          
        </tbody>
    </table>
</div>
<?php } ?>
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
 function getSubHead(id){
             var action = 'getSubHead';
      $.ajax({
  type: "POST",
  url: "../ajax_files/ajax_dropdown.php",
   data:{action:action,id:id},  
  success: function(data){
      $('#ProjectSubHeadId').html(data);
  }
  });
        }
        
  function receiptPrint(id){
     setTimeout(function() {
        window.open(
            'receipt.php?id=' + id + '&roll=vendor', 'stickerPrint',
            'toolbar=1, scrollbars=1, location=1,statusbar=0, menubar=1, resizable=1, width=800, height=800'
        );
    }, 1);
 }
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
</script>
</body>
</html>
