<?php 
session_start();
include_once '../config.php';
include_once '../auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Reports";
$Page = "Trip-Reports";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | Trip Reports</title>
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
<h4 class="font-weight-bold py-3 mb-0">Trip Report</h4>

<div class="card" style="padding: 10px;">
     <div id="accordion2">
<div class="card mb-2">
                                        
                                        <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                            <div class="" style="padding:5px;">
                                                <form id="validation-form" method="post" enctype="multipart/form-data" action="">
<div class="form-row">

 
  <div class="form-group col-md-4">
<label class="form-label">Driver</label>
 <select class="select2-demo form-control" name="DriverId" id="DriverId">
<option selected="" value="all">All</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_users WHERE Roll = '39' AND Status='1'";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($_REQUEST["DriverId"] == $result['id']) {?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Fname']; ?></option>
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
                <th>Trip Details</th>
               <th>Gadi No</th>
                <th>Driver Name</th>
                <th>Out Date</th>
                
                <th>In Date</th>
               
                <th>Opening Reading</th>
                <th>Closing Reading</th>
                <th>Fastag</th>
                <th>Challan</th>
                <th>Diesel Payment</th>
             <th>Total Running KM</th>
             <th>Avg Of Vehicle</th>
              <th>Total Diesel Used</th>
              <th>Days</th>
              <th>Per Day Vehicle</th>
              <th>Total Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $sql = "SELECT ts.* FROM tbl_trip_details ts WHERE ts.Status=1 ";
             if($_POST['DriverId']){
                $DriverId = $_POST['DriverId'];
                if($DriverId == 'all'){
                    $sql.= " ";
                }
                else{
                $sql.= " AND ts.DriverId='$DriverId'";
                }
            }

            if($_POST['FromDate']){
                $FromDate = $_POST['FromDate'];
                $sql.= " AND ts.InDate>='$FromDate'";
            }
            if($_POST['ToDate']){
                $ToDate = $_POST['ToDate'];
                $sql.= " AND ts.OutDate<='$ToDate'";
            }
            $sql.=" ORDER BY ts.id DESC";    
            //echo $sql;
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                    $TotalAmount+=$row['TotalAmount'];
             ?>
            <tr>
               <td><?php echo $i; ?></td>
                <td><?php echo $row['TripDetails']; ?></td>
               <td><?php echo $row['VehicalNo']; ?></td>
              <td><?php echo $row['DriverName']; ?></td>
             
             
               <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['OutDate']))); ?></td>
              
                 <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['InDate']))); ?></td>
            <td><?php echo $row['OpeningReading']; ?></td>
            <td><?php echo $row['ClosingReading']; ?></td>
            <td><?php echo $row['Fastag']; ?></td>
            <td><?php echo $row['Challan']; ?></td>
            <td><?php echo $row['DieselPayment']; ?></td>
            <td><?php echo $row['TotalRunningKm']; ?></td>
            <td><?php echo $row['TotalAvgVehicle']; ?></td>
            <td><?php echo $row['TotalDieselUsed']; ?></td>
            <td><?php echo $row['Days']; ?></td>
            <td><?php echo $row['TotalVehicleRate']; ?></td>
            <td><?php echo $row['TotalAmount']; ?></td>
              
            </tr>
           <?php $i++;} ?>

          <tr>
             <td><?php echo $i; ?></td>
             <?php for($i=1;$i<=14;$i++){?>
               <td></td>
               <?php } ?>
            <th>Total</th>
            <th><?php echo $TotalAmount; ?></th>
              
            </tr>
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
