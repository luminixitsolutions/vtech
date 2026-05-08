<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Vendors";
$Page = "View-Vendors";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | View Vendors Account List</title>
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

<?php include_once 'warranty-sidebar.php'; ?>


<div class="layout-container">

<?php include_once 'top_header.php'; ?>

<?php
if($_REQUEST["action"]=="delete")
{
  $id = $_REQUEST["id"];
  $sql11 = "DELETE FROM tbl_warranty_registration WHERE id = '$id'";
  $conn->query($sql11);
  ?>
    <script type="text/javascript">
      alert("Deleted Successfully!");
      window.location.href="view-warranty-registration.php";
    </script>
<?php } ?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">View Warranty Customer List
   
</h4>

<div class="card" style="padding: 10px;">
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
            <tr>
               <th>#</th>
                <th>Customer Name</th>
                <th>Contact No</th>
                <th>Address</th>
                <th>Warranty Start Date</th>
                <th>End Date</th>
               
               
               
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $CurrDate=date('Y-m-d');
            $sql = "SELECT tw.*,tu.* FROM tbl_warranty_registration tw 
                    LEFT JOIN tbl_users tu ON tu.id=tw.CustId WHERE tu.Roll=5 AND tw.EndDate>='$CurrDate' ORDER BY tu.CreatedDate DESC";
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
               
             ?>
            <tr>
               <td><?php echo $i;?></td>
               <td><?php echo $row['Fname']." ".$row['Lname']; ?></td>
              
                <td><?php echo $row['Phone']; ?></td>
             
                  <td><?php echo $row['Address']; ?></td>
              
                    
                 
               
            <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['StartDate']))); ?></td>
            <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['EndDate']))); ?></td>
           
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
    });
});
</script>
</body>
</html>
