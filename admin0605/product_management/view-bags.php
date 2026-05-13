<?php 
session_start();
include_once '../config.php';
include_once '../auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Products";
$Page = "View-Products";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | State</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="" />
<meta name="keywords" content="">
<meta name="author" content="" />
<link rel="icon" type="image/x-icon" href="<?php echo $SiteUrl;?>/assets/img/favicon.ico">
    <!-- Google fonts -->
<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
    <!-- Icon fonts -->
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/fontawesome.css">
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/ionicons.css">
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/linearicons.css">
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/feather.css">
    <!-- Core stylesheets -->
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/css/bootstrap-material.css">
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/css/shreerang-material.css">
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/css/uikit.css">
<!-- Libs -->
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/libs/perfect-scrollbar/perfect-scrollbar.css">
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/libs/datatables/datatables.css">
</head>
<body>

<div class="layout-wrapper layout-2">
<div class="layout-inner">

<?php include_once 'product-sidebar.php'; ?>


<div class="layout-container">

<?php include_once '../top_header.php'; ?>

<?php
if($_REQUEST["action"]=="delete")
{
  $id = $_REQUEST["id"];
  $sql11 = "DELETE FROM tbl_products WHERE id = '$id'";
  $conn->query($sql11);
  $sql11 = "DELETE FROM tbl_bag_items WHERE BagId = '$id'";
  $conn->query($sql11);
  ?>
    <script type="text/javascript">
      alert("Deleted Successfully!");
      window.location.href="view-bags.php";
    </script>
<?php } ?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
 <h4 class="font-weight-bold py-3 mb-0">View Bags List
    <?php if(in_array("14", $Options)) {?>   
<span style="float: right;">
<a href="add-bags.php" class="btn btn-secondary btn-round"><i class="ion ion-md-add mr-2"></i> Add New</a></span><?php } ?>
</h4><br>

<div class="card">
<div class="card-datatable table-responsive">
 <table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
            <tr>
               <th>#</th>
                <th>Title</th>
                <th>Total Items</th>
                <th>Status</th>
                <th>Created Date</th>
             <th>Created By</th>
               <th>Action</th>
               
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $CurrDate=date('Y-m-d');
            $sql = "SELECT tw.*,tu.Fname,tu.Lname FROM tbl_products tw 
                    LEFT JOIN tbl_users tu ON tu.id=tw.CreatedBy WHERE tw.Roll=2 ORDER BY tu.CreatedDate DESC";
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
                $sql2 = "SELECT * FROM tbl_bag_items WHERE BagId='".$row['id']."'";
                $rncnt2 = getRow($sql2);
               
             ?>
            <tr>
               <td><?php echo $i;?></td>
               <td><?php echo $row['ProductName']; ?></td>
               <td><?php echo $rncnt2;?></td>
               <td><?php if($row['Status']=='1'){echo "<span style='color:green;'>Active</span>";} else { echo "<span style='color:red;'>Pending</span>";} ?></td>
                 <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['CreatedDate']))); ?></td>
               <td><?php echo $row['Fname']." ".$row['Lname']; ?></td>
                <td><a onClick="return confirm('Are you sure you want delete this record');" href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $row['id']; ?>&action=delete" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><i class="lnr lnr-trash text-danger"></i></a></td>
              
           
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


    <script src="<?php echo $SiteUrl;?>/assets/js/jquery.min.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/datatables.min.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/pace.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/sidenav.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/layout-helpers.js"></script>
    <!-- Libs -->
    <script src="<?php echo $SiteUrl;?>/assets/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <!-- Demo -->
    <script src="<?php echo $SiteUrl;?>/assets/js/demo.js"></script>
   <script>
           $(document).ready(function() {
    $('#example').DataTable({
    });
});
   </script>

</body>
</html>
