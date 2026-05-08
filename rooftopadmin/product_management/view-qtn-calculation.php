<?php 
session_start();
include_once '../config.php';
include_once '../auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Lead";
$Page = "View-Lead";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
    <title><?php echo $Proj_Title;?></title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="Codedthemes" />
    <link rel="icon" type="image/x-icon" href="<?php echo $SiteUrl;?>/assets/img/favicon.ico">

    <!-- Google fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
    <!-- Icon fonts -->
    
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
    <!-- [ Preloader ] Start -->
    <div class="page-loader">
        <div class="bg-primary"></div>
    </div>
    <!-- [ Preloader ] Ebd -->
    <!-- [ Layout wrapper ] Start -->
    <div class="layout-wrapper layout-2">
        <div class="layout-inner">

        <?php include_once 'product-sidebar.php'; ?>


            <div class="layout-container">

              <?php include_once '../top_header.php'; ?>

              <?php
if($_REQUEST["action"]=="delete")
{
  $id = $_REQUEST["id"];
  $sql11 = "DELETE FROM tbl_rooftop_rate_calculation WHERE id = '$id'";
  $conn->query($sql11);
  ?>
    <script type="text/javascript">
      alert("Deleted Successfully!");
      window.location.href="view-qtn-calculation.php";
    </script>
<?php } ?>           
                <!-- [ Layout content ] Start -->
                <div class="layout-content">
                    <!-- [ content ] Start -->
                    <div class="container flex-grow-1 container-p-y">
                        <h5 class="font-weight-bold py-3 mb-0">Rate Calculation For Quotation
                         <?php if(in_array("14", $Options)) {?>   
<span style="float: right;">
<a href="add-qtn-calculation.php" class="btn btn-secondary btn-round"><i class="ion ion-md-add mr-2"></i> Add New</a></span><?php } ?></h5>
<br>


                    <div class="card mb-4">
                             <div class="card-datatable table-responsive">
                              <table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
               <th>Sr No</th>
               <th>Brand</th> 
               <th>Category</th> 
                <th>Product Name</th> 
                <th>Capacity </th>
               
                <th>Make Of Module</th>
                <th>Qty</th>
                <th>Rate</th>
                <th>Cost Of Item</th>
                 <?php if(in_array("10", $Options) || in_array("11", $Options)) {?>
               <th>Action</th>
               <?php } ?>
               
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
             $sql = "SELECT tp.*,tb.Name AS BrandName,tqc.Name AS CatName FROM tbl_rooftop_rate_calculation tp 
            LEFT JOIN tbl_brand tb ON tb.id=tp.BrandId 
            LEFT JOIN tbl_qtn_category tqc ON tp.CatId=tqc.id 
            ORDER BY tp.id DESC";
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
               
             ?>
            <tr>
               <td><?php echo $i; ?> </td>
               <td><?php echo $row['BrandName']; ?></td> 
                 
             <td><?php echo $row['CatName']; ?></td> 
              
                <td><?php echo $row['ProductName']; ?></td>
                 <td><?php echo $row['Capacity']; ?></td>
                <td><?php echo $row['MakeModule']; ?></td>
                <td><?php echo $row['Qty']; ?></td>
                <td><?php echo $row['Rate']; ?></td>
                <td><?php echo $row['CostOfItem']; ?></td>
           
           <?php if(in_array("10", $Options) || in_array("11", $Options)) {?>
            <td>
              <?php if(in_array("10", $Options)){?>
              <a href="add-qtn-calculation.php?id=<?php echo $row['id'];?>"><i class="lnr lnr-pencil mr-2"></i></a>
             <?php } if(in_array("11", $Options)){?>
              &nbsp;&nbsp;<a onClick="return confirm('Are you sure you want delete this record');" href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $row['id']; ?>&action=delete" ><i class="lnr lnr-trash text-danger"></i></a>
             <?php } ?>
            </td> <?php } ?>
        
              
            </tr>
           <?php $i++;} ?>
        </tbody>
    </table>
                            </div>
                    </div>
                        



					</div>
                    <!-- [ content ] End -->
                    <!-- [ Layout footer ] Start -->
                    
                    <!-- [ Layout footer ] End -->
                </div>
                <!-- [ Layout content ] Start -->
            </div>
            <!-- [ Layout container ] End -->
        </div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core scripts -->
    <script src="<?php echo $SiteUrl;?>/assets/js/jquery.min.js"></script>
   <script type="text/javascript" src="<?php echo $SiteUrl;?>/assets/js/datatables.min.js"></script>
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
        "scrollX": true

    });
});
        </script>
</body>

</html>
