<?php 
session_start();
include_once '../config.php';
include_once '../auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Reports";
$Page = "Delay-Calculation-Report";
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
                <th>Date of Selection</th>
                <th>Date of  Field Survey</th>
                <th>No. Of Days Delay</th>
                <th>Date of Field Survey</th>
                <th>Date of Material Dispatch</th>
                <th>No. Of Days Delay</th>
                <th>Date of material Dispatch</th>
                <th>Date of Installation & Commissioning</th>
                <th>No. Of Days Delay</th>
                <th>Date of Installation & Commissioning</th>
                <th>Date of  Inspection</th>
                <th>No. Of Days Delay</th>
                <th>Date of  Inspection</th>
                <th>Date of DGM Approval</th>
                <th>No. Of Days Delay</th>
                <th>Date of DGM Approval</th>
                <th>Date of Payment received</th>
                <th>No. Of Days Delay</th>
              
            </tr>
        </thead>
        <tbody>
            <?php 
            function calDays($fromdate,$todate){
                 $date1 = new DateTime($fromdate);
$date2 = new DateTime($todate);

// Calculate the difference
$interval = $date1->diff($date2);

// Output the number of days
return $interval->days;
            }
           
            $i=1;
            $sql = "SELECT tu.*,tc.Name AS Pump_Capacity FROM tbl_users tu LEFT JOIN tbl_common_master tc ON tc.id=tu.PumpCapacity WHERE tu.Roll = '5' AND tu.ProjectType='1' AND tu.SurveyDetails='1'";
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
    
    <?php if ($row['SelectionDate'] != '') { ?>
        <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/', $row['SelectionDate']))); ?></td>
    <?php } else { ?>
        <td>Not Yet</td>
    <?php } ?>
    
    <?php if ($FieldSurvey == 1) { ?>
        <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/', $row['FieldSurveyDate']))); ?></td>
    <?php } else { ?>
        <td>Not Yet</td>
    <?php } ?>
    
    <td><?php echo calDays($row['SelectionDate'], $row['FieldSurveyDate']); ?></td>
    
    <?php if ($FieldSurvey == 1) { ?>
        <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/', $row['FieldSurveyDate']))); ?></td>
    <?php } else { ?>
        <td>Not Yet</td>
    <?php } ?>
    
    <?php if ($row88['Inst_Dispatcher_Otp_Verify'] == 1) { ?>
        <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/', $row88['Inst_Dispatcher_Date']))); ?></td>
    <?php } else { ?>
        <td>Not Yet</td>
    <?php } ?>
    
    <td><?php echo calDays($row['FieldSurveyDate'], $row88['Inst_Dispatcher_Date']); ?></td>
    
    <?php if ($row88['Inst_Dispatcher_Otp_Verify'] == 1) { ?>
        <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/', $row88['Inst_Dispatcher_Date']))); ?></td>
    <?php } else { ?>
        <td>Not Yet</td>
    <?php } ?>
    
    <?php if ($row99['InstallStatus'] == 'Yes') { ?>
        <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/', $row99['InstallationDate']))); ?></td>
    <?php } else { ?>
        <td>Not Yet</td>
    <?php } ?>
    
    <td><?php echo calDays($row88['Inst_Dispatcher_Date'], $row99['InstallationDate']); ?></td>
    
    <?php if ($row99['InstallStatus'] == 'Yes') { ?>
        <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/', $row99['InstallationDate']))); ?></td>
    <?php } else { ?>
        <td>Not Yet</td>
    <?php } ?>
    
    <?php if ($row99['PoInspection'] == 'Yes') { ?>
        <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/', $row99['PoInspectionDate']))); ?></td>
    <?php } else { ?>
        <td>Not Yet</td>
    <?php } ?>
    
    <td><?php echo calDays($row99['InstallationDate'], $row99['PoInspectionDate']); ?></td>
    
    <?php if ($row99['PoInspection'] == 'Yes') { ?>
        <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/', $row99['PoInspectionDate']))); ?></td>
    <?php } else { ?>
        <td>Not Yet</td>
    <?php } ?>
    
    <?php if ($row99['DgmApproval'] == 'Yes') { ?>
        <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/', $row99['DgmApprovalDate']))); ?></td>
    <?php } else { ?>
        <td>Not Yet</td>
    <?php } ?>
    
     <td><?php echo calDays($row99['PoInspectionDate'], $row99['DgmApprovalDate']); ?></td>
     
     <?php if ($row99['DgmApproval'] == 'Yes') { ?>
        <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/', $row99['DgmApprovalDate']))); ?></td>
    <?php } else { ?>
        <td>Not Yet</td>
    <?php } ?>
    
    <?php if ($row99['Payment90'] == 'Yes') { ?>
        <td><?php if($row99['PaymentDate']!='0000-00-00'){ echo date("d/m/Y", strtotime(str_replace('-', '/', $row99['PaymentDate'])));} 
        else { echo "Not Yet";}?></td>
    <?php } else { ?>
        <td>Not Yet</td>
    <?php } ?>
    
    <td><?php echo calDays($row99['DgmApprovalDate'], $row99['PaymentDate']); ?></td>
    
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
