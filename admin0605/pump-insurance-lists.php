<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Pump-Insurance-List";
$Page = "Pump-Insurance-List";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | Pump Insurance List</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="" />
<meta name="keywords" content="">
<meta name="author" content="" />
<?php include_once 'header_script.php'; ?>
</head>
<body>

<div class="layout-wrapper layout-2">
<div class="layout-inner">

<?php include_once 'sidebar.php'; ?>


<div class="layout-container">

<?php include_once 'top_header.php'; ?>


<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Pump Insurance List</h4>

<div class="card" style="padding: 10px;">
     <div id="accordion2">
<div class="card mb-2">
                                        
                                        <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                            <div class="" style="padding:5px;">
                                                <form id="validation-form" method="post" enctype="multipart/form-data" action="">
<div class="form-row">

 
  <div class="form-group col-md-4">
<label class="form-label">Customer</label>
 <select class="select2-demo form-control" name="DriverId" id="DriverId">
<option selected="" value="all">All</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_users WHERE Roll = '5' AND Status='1' AND ProjectType=1";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($_REQUEST["DriverId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Fname']." (".$result['BeneficiaryId'].")"; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

  

  


<div class="form-group col-md-2">
<label class="form-label">In Date </label>
<input type="date" name="FromDate" id="FromDate" class="form-control" value="<?php echo $_POST['FromDate'] ?>" autocomplete="off">
</div>
<div class="form-group col-md-2">
<label class="form-label">Out Date</label>
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
                <th>Sr No</th>
                <th>Beneficiary ID</th>
                <th>Customer Name</th>
                <th>Contact No</th>
                <th>Taluka</th>
                <th>Village</th>
                <th>District</th>
                <th>Address</th>
                <th>Dispatcher Date</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $sql = "SELECT tdo.*,tu.BeneficiaryId,tu.Taluka,tu.Village,tu.District FROM tbl_sell tdo 
                    LEFT JOIN tbl_users tu ON tdo.CustId=tu.id WHERE tdo.Inst_Dispatcher_Otp_Verify=1 AND tu.ProjectType=1";
             if($_POST['DriverId']){
                $DriverId = $_POST['DriverId'];
                if($DriverId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND tdo.CustId='$DriverId'";
                }
            }

            if($_POST['FromDate']){
                $FromDate = $_POST['FromDate'];
                $sql.= " AND tdo.Inst_Dispatcher_Date>='$FromDate'";
            }
            if($_POST['ToDate']){
                $ToDate = $_POST['ToDate'];
                $sql.= " AND tdo.Inst_Dispatcher_Date<='$ToDate'";
            }
            $sql.=" ORDER BY tdo.id DESC";    
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
              <td><?php echo $row['Taluka']; ?></td>
              <td><?php echo $row['Village']; ?></td>
              <td><?php echo $row['District']; ?></td>
              <td><?php echo $row['Address']; ?></td>
             <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['Inst_Dispatcher_Date']))); ?></td>
             
                        </tr>
           <?php $i++;} ?>

       
        </tbody>
    </table>
</div>
</div>
</div>


<?php include_once 'footer.php'; ?>

</div>

</div>

</div>

<div class="layout-overlay layout-sidenav-toggle"></div>
</div>


<?php include_once 'footer_script.php'; ?>

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
