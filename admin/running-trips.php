<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Trip-Details";
$Page = "Running-Trips";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> </title>
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
<h4 class="font-weight-bold py-3 mb-0">View Running Trips
  
</h4>

<div class="card" style="padding: 10px;">
   
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>#</th>
                 <th>Add Diesel</th>
                <th>Status</th>
                <th>Driver Name</th>
                <th>Vehicle No</th>
                <th>Trip Details</th>
                <th>In Date</th>
                <th>Opening Reading</th>
                <?php if(in_array("10", $Options) || in_array("11", $Options)) {?>
               <th>Action</th>
               <?php } ?>
               
            </tr>
        </thead>
        <tbody>
            <?php
            $i=1; 
            $sql = "SELECT te.* FROM tbl_trip_details te LEFT JOIN tbl_users tu ON tu.id=te.DriverId WHERE te.Status=0";
            $sql.= " ORDER BY te.CreatedDate DESC";
            //echo $sql;
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
               
             ?>
            <tr>
                <td><?php echo $i;?></td>
                 <td><a href="view-diesel.php?id=<?php echo $row['id']; ?>" class="btn btn-secondary btn-round">Add Diesel</a></td>
                <td><a class="badge badge-pill badge-primary" style="color: white;">Running</a></td>
                <td><?php echo $row['DriverName']; ?></td>
                <td><?php echo $row['VehicalNo']; ?></td>
                <td><?php echo $row['TripDetails']; ?></td>
                <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['InDate']))); ?></td>
                <td><?php echo $row['OpeningReading']; ?></td>
                <?php if(in_array("10", $Options) || in_array("11", $Options)) {?>
            <td>
                <?php if(in_array("10", $Options)){?>
              <a href="start-trip.php?id=<?php echo $row['id']; ?>"><i class="lnr lnr-pencil mr-2"></i></a>&nbsp;&nbsp;
             <?php } if(in_array("11", $Options)){?>
              <a onClick="return confirm('Are you sure you want delete this trip?\nNote : Delete all record related this trip (Y/N)');" href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $row['id']; ?>&action=delete"><i class="lnr lnr-trash text-danger"></i></a>
             <?php } ?>
            </td><?php } ?>
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
        
    });
});
</script>
</body>
</html>
