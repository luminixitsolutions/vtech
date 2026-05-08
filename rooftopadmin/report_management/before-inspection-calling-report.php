<?php 
session_start();
include_once '../config.php';
include_once '../auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Before-Inspection-Calling-Report";
$Page = "Before-Inspection-Calling-Report";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | Dispatch Reports</title>
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
<h4 class="font-weight-bold py-3 mb-0">Before Inspection Calling Report</h4>

<div class="card" style="padding: 10px;">
     <div id="accordion2">
<div class="card mb-2">
                                        
                                        <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                            <div class="" style="padding:5px;">
                                                <form id="validation-form" method="post" enctype="multipart/form-data" action="">
<div class="form-row">

 
  <div class="form-group col-md-4">
<label class="form-label">Customer</label>
 <select class="select2-demo form-control" name="CustId" id="CustId">
<option selected="" value="all">All</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_rooftop_before_inspection_calling_info GROUP BY CustId";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($_REQUEST["CustId"] == $result['CustId']) {?> selected <?php } ?> value="<?php echo $result['CustId'];?>">
    <?php echo $result['CustName']." (".$result['CellNo'].")"; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

  

   <div class="form-group col-md-3">
		<label class="form-label">State <span class="text-danger">*</span></label>
<select class="select2-demo form-control" id="StateId" name="StateId">
<option selected="" value="all">All State</option>
 <?php 
        $CountryId = $row7['CountryId'];
        $q = "select * from tbl_state WHERE CountryId='1' ORDER BY Name ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($_REQUEST['StateId']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>
	</div>
	
                                             <div class="form-group col-md-3">      
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


<div class="form-group col-md-2">
<label class="form-label">From Date </label>
<input type="date" name="FromDate" id="FromDate" class="form-control" value="<?php echo $_POST['FromDate'] ?>" autocomplete="off">
</div>
<div class="form-group col-md-2">
<label class="form-label">To Date</label>
<input type="date" name="ToDate" id="ToDate" class="form-control" value="<?php echo $_POST['ToDate'] ?>" autocomplete="off">
</div>
<input type="hidden" name="Search" value="Search">
<div class="form-group col-md-1" style="padding-top:30px;">
<button type="submit" name="submit" class="btn btn-primary btn-finish">Search</button>
</div>
<?php if(isset($_POST['Search'])) {?>
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
               <th>#</th>
               <th>Beneficiary ID</th>
               <th>Customer Name</th>
                                            <th>Contact No</th>
                                            
                                             <th>State</th>
                                             <th>Taluka</th>
                                            <th>District</th>
                                            <th>Pump Capacity</th>
                                            <th>Calling Date</th>
                                            <th>Subject</th>
                                            <?php  
$sql = "SELECT * FROM tbl_common_master WHERE Status=1 AND Roll=21";
$row = getList($sql);
foreach($row as $result){?>

                                            <th><?php echo $result['Name'];?></th>
                                         <!--    <th> <?php echo $result['ExpTitle'];?> </th> -->
                                        <?php } ?>
                                           
                        
              
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $sql = "SELECT tdo.*,tu.Address,tu.BeneficiaryId,ts.Name As StateName FROM tbl_rooftop_before_inspection_calling_info tdo 
                    LEFT JOIN tbl_users tu ON tdo.CustId=tu.id 
                    LEFT JOIN tbl_state ts ON tu.StateId=ts.id 
                   WHERE 1 ";
             if($_POST['CustId']){
                $CustId = $_POST['CustId'];
                if($CustId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND tdo.CustId='$CustId'";
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
           
           if($_POST['StateId']){
            $StateId = $_POST['StateId'];
            if($StateId == 'all'){
                $sql.= " ";
            }
            else{
               $sql.= " AND tu.StateId='$StateId'";
            }
        }
        
        
         
            if($_POST['FromDate']){
                $FromDate = $_POST['FromDate'];
                $sql.= " AND tdo.CreatedDate>='$FromDate'";
            }
            if($_POST['ToDate']){
                $ToDate = $_POST['ToDate'];
                $sql.= " AND tdo.CreatedDate<='$ToDate'";
            }
            $sql.=" GROUP BY tdo.CustId ORDER BY tdo.id DESC";    
            //echo $sql;
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {   
                
             ?>
            <tr>
               <td><?php echo $i; ?></td>
                <td><?php echo $row['BeneficiaryId']; ?></td>
                <td><?php echo $row['CustName']; ?></td>
               <td><?php echo $row['CellNo']; ?></td>
               <td><?php echo $row['StateName']; ?></td>
              <td><?php echo $row['Taluka']; ?></td>
              <td><?php echo $row['District']; ?></td>
               <td><?php echo $row['PumpCapacity']; ?></td>
              <td><?php if($row['CreatedDate']==''){ echo "-"; } else { echo date("d/m/Y", strtotime(str_replace('-', '/',$row['CreatedDate']))); } ?></td>
<td><?php echo $row['Subjects']; ?></td>
                <?php  
$sql2 = "SELECT * FROM tbl_common_master WHERE Status=1 AND Roll=21";
$row2 = getList($sql2);
foreach($row2 as $result){
        $sql3 = "SELECT Answer,Specify FROM tbl_rooftop_before_inspection_calling_info WHERE QuesId='".$result['id']."'";
        $row3 = getRecord($sql3);
    ?>

                                            <td><?php echo $row3['Answer'];
                                            if($row['Specify'] != ''){?>
                                            <br>
                                            Specify : <?php echo $row['Specify']; } ?></td>
                                         <!--    <th> <?php echo $result['ExpTitle'];?> </th> -->
                                        <?php } ?>
           
              
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
    
     $(document).on("change", "#StateId", function(event){
  var val = this.value;
   var action = "getDistrict";
    $.ajax({
    url:"ajax_files/ajax_dropdown.php",
    method:"POST",
    data : {action:action,id:val},
    success:function(data)
    {
        console.log(data);
      $('#District').html(data);
    }
    });

 });
});
</script>
</body>
</html>
