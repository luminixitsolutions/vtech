<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$MainPage="Dashboard";
$Page = "Dashboard";
$user_id = $_SESSION['Admin']['id'];
$sql77 = "SELECT * FROM tbl_users WHERE id='$user_id'";
$row77 = getRecord($sql77);
$Roll = $row77['Roll'];
$UserCat = $row77['CatId'];
$Options = explode(',',$row77['Options']);
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">

<head>
    <title><?php echo $Proj_Title; ?> - Dashboard</title>
    <meta name="apple-mobile-web-app-capable" content="yes">

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="img/favicon180.png" sizes="180x180">
    <link rel="icon" href="img/favicon32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="img/favicon16.png" sizes="16x16" type="image/png">

    <!-- Material icons-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&amp;display=swap" rel="stylesheet">

    <!-- swiper CSS -->
    <link href="vendor/swiper/css/swiper.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet" id="style">
    <link href="css/toastr.min.css" rel="stylesheet">
    <script src="js/jquery.min3.5.1.js" type="text/javascript"></script>
    <script type="text/javascript" src="js/toastr.min.js"></script>
    <link rel="stylesheet" href="example/css/slim.min.css">
    <?php include_once 'header_script.php'; ?>
</head>

<body>
    <style type="text/css">
    .mr_5 {
        margin-right: 3rem !important;
    }
    </style>
   <body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">

            


            

              


                <div class="layout-content">
                    <div class="container-fluid flex-grow-1 container-p-y">
                        <div class="text-muted small mt-0 mb-4 d-block breadcrumb">
                        </div>

<div class="row">
          <?php if(in_array("2", $Options)) {?>                   
  <!--<div class="col-4 col-md-2" style="background-color: white; height: 130px; border: 1px solid #f9f9f9;">
   <a href="branches.php">
    <div class="icon-style text-center" style="padding-top: 20px;">
       <img  class="zoom" src="icons/course.png" class="img-fluid ui-w-60" style="width: 60px;height: 60px;">
       <h5 style="font-size: 13px;  padding-top:10px;color: #000; text-align: center; text-transform: capitalize;" align="center">
            <span style="color: #000;"> Branches</span>
        </h5>
    </div>
    </a>
</div>
<?php } if(in_array("3", $Options)) {?>
<div class="col-4 col-md-2" style="background-color: white; height: 130px; border: 1px solid #f9f9f9;">
   <a href="issues.php">
    <div class="icon-style text-center" style="padding-top: 20px;">
       <img  class="zoom" src="icons/course.png" class="img-fluid ui-w-60" style="width: 60px;height: 60px;">
       <h5 style="font-size: 13px;  padding-top:10px;color: #000; text-align: center; text-transform: capitalize;" align="center">
            <span style="color: #000;"> Issues</span>
        </h5>
    </div>
    </a>
</div>
<?php } if(in_array("4", $Options)) {?>
<div class="col-4 col-md-2" style="background-color: white; height: 130px; border: 1px solid #f9f9f9;">
   <a href="scheme.php">
    <div class="icon-style text-center" style="padding-top: 20px;">
       <img  class="zoom" src="icons/course.png" class="img-fluid ui-w-60" style="width: 60px;height: 60px;">
       <h5 style="font-size: 13px;  padding-top:10px;color: #000; text-align: center; text-transform: capitalize;" align="center">
            <span style="color: #000;"> Scheme</span>
        </h5>
    </div>
    </a>
</div>
<?php } if(in_array("5", $Options)) {?>
<div class="col-4 col-md-2" style="background-color: white; height: 130px; border: 1px solid #f9f9f9;">
   <a href="user-type.php">
    <div class="icon-style text-center" style="padding-top: 20px;">
       <img  class="zoom" src="icons/course.png" class="img-fluid ui-w-60" style="width: 60px;height: 60px;">
       <h5 style="font-size: 13px;  padding-top:10px;color: #000; text-align: center; text-transform: capitalize;" align="center">
            <span style="color: #000;"> User Type</span>
        </h5>
    </div>
    </a>
</div>
<?php } if(in_array("6", $Options)) {?>
<div class="col-4 col-md-2" style="background-color: white; height: 130px; border: 1px solid #f9f9f9;">
   <a href="common-master.php?pageid=1">
    <div class="icon-style text-center" style="padding-top: 20px;">
       <img  class="zoom" src="icons/course.png" class="img-fluid ui-w-60" style="width: 60px;height: 60px;">
       <h5 style="font-size: 13px;  padding-top:10px;color: #000; text-align: center; text-transform: capitalize;" align="center">
            <span style="color: #000;"> Pump Head</span>
        </h5>
    </div>
    </a>
</div>
<?php } if(in_array("7", $Options)) {?>
<div class="col-4 col-md-2" style="background-color: white; height: 130px; border: 1px solid #f9f9f9;">
   <a href="common-master.php?pageid=2">
    <div class="icon-style text-center" style="padding-top: 20px;">
       <img  class="zoom" src="icons/course.png" class="img-fluid ui-w-60" style="width: 60px;height: 60px;">
       <h5 style="font-size: 13px;  padding-top:10px;color: #000; text-align: center; text-transform: capitalize;" align="center">
            <span style="color: #000;"> Pump Capacity</span>
        </h5>
    </div>
    </a>
</div>
<?php } if(in_array("8", $Options)) {?>
<div class="col-4 col-md-2" style="background-color: white; height: 130px; border: 1px solid #f9f9f9;">
   <a href="common-master.php?pageid=3">
    <div class="icon-style text-center" style="padding-top: 20px;">
       <img  class="zoom" src="icons/course.png" class="img-fluid ui-w-60" style="width: 60px;height: 60px;">
       <h5 style="font-size: 13px;  padding-top:10px;color: #000; text-align: center; text-transform: capitalize;" align="center">
            <span style="color: #000;"> Water Source</span>
        </h5>
    </div>
    </a>
</div>
<?php } if(in_array("9", $Options)) {?>
<div class="col-4 col-md-2" style="background-color: white; height: 130px; border: 1px solid #f9f9f9;">
   <a href="common-master.php?pageid=4">
    <div class="icon-style text-center" style="padding-top: 20px;">
       <img  class="zoom" src="icons/course.png" class="img-fluid ui-w-60" style="width: 60px;height: 60px;">
       <h5 style="font-size: 13px;  padding-top:10px;color: #000; text-align: center; text-transform: capitalize;" align="center">
            <span style="color: #000;"> Surface</span>
        </h5>
    </div>
    </a>
</div>
<?php } if(in_array("12", $Options)) {?>
<div class="col-4 col-md-2" style="background-color: white; height: 130px; border: 1px solid #f9f9f9;">
   <a href="common-master.php?pageid=7">
    <div class="icon-style text-center" style="padding-top: 20px;">
       <img  class="zoom" src="icons/course.png" class="img-fluid ui-w-60" style="width: 60px;height: 60px;">
       <h5 style="font-size: 13px;  padding-top:10px;color: #000; text-align: center; text-transform: capitalize;" align="center">
            <span style="color: #000;"> Bore Dia</span>
        </h5>
    </div>
    </a>
</div>
<?php } if(in_array("13", $Options)) {?>
<div class="col-4 col-md-2" style="background-color: white; height: 130px; border: 1px solid #f9f9f9;">
   <a href="common-master.php?pageid=8">
    <div class="icon-style text-center" style="padding-top: 20px;">
       <img  class="zoom" src="icons/course.png" class="img-fluid ui-w-60" style="width: 60px;height: 60px;">
       <h5 style="font-size: 13px;  padding-top:10px;color: #000; text-align: center; text-transform: capitalize;" align="center">
            <span style="color: #000;"> Customer Type</span>
        </h5>
    </div>
    </a>
</div>-->
<?php } if(in_array("34", $Options)) {?>
<div class="col-4 col-md-2" style="background-color: white; height: 130px; border: 1px solid #f9f9f9;">
   <a href="common-master.php?pageid=9">
    <div class="icon-style text-center" style="padding-top: 20px;">
       <img  class="zoom" src="mob_icons/insrance_agency.png" class="img-fluid ui-w-60" style="width: 60px;height: 60px;">
       <h5 style="font-size: 13px;  padding-top:10px;color: #000; text-align: center; text-transform: capitalize;" align="center">
            <span style="color: #000;"> Insurance Agency</span>
        </h5>
    </div>
    </a>
</div>
<?php } if(in_array("15", $Options)) {?>
<div class="col-4 col-md-2" style="background-color: white; height: 130px; border: 1px solid #f9f9f9;">
   <a href="common-master.php?pageid=5">
    <div class="icon-style text-center" style="padding-top: 20px;">
       <img  class="zoom" src="mob_icons/claim.png" class="img-fluid ui-w-60" style="width: 60px;height: 60px;">
       <h5 style="font-size: 13px;  padding-top:10px;color: #000; text-align: center; text-transform: capitalize;" align="center">
            <span style="color: #000;"> Insurance Claim Reason</span>
        </h5>
    </div>
    </a>
</div>
<?php } if(in_array("16", $Options)) {?>
<div class="col-4 col-md-2" style="background-color: white; height: 130px; border: 1px solid #f9f9f9;">
   <a href="common-master.php?pageid=6">
    <div class="icon-style text-center" style="padding-top: 20px;">
       <img  class="zoom" src="mob_icons/claim_status.png" class="img-fluid ui-w-60" style="width: 60px;height: 60px;">
       <h5 style="font-size: 13px;  padding-top:10px;color: #000; text-align: center; text-transform: capitalize;" align="center">
            <span style="color: #000;"> Insurance Claim Status</span>
        </h5>
    </div>
    </a>
</div>
<?php } if(in_array("17", $Options)) {?>
<div class="col-4 col-md-2" style="background-color: white; height: 130px; border: 1px solid #f9f9f9;">
   <a href="product-specification.php">
    <div class="icon-style text-center" style="padding-top: 20px;">
       <img  class="zoom" src="mob_icons/inspection.png" class="img-fluid ui-w-60" style="width: 60px;height: 60px;">
       <h5 style="font-size: 13px;  padding-top:10px;color: #000; text-align: center; text-transform: capitalize;" align="center">
            <span style="color: #000;"> Product Specification</span>
        </h5>
    </div>
    </a>
</div>
<?php } if(in_array("18", $Options)) {?>
<div class="col-4 col-md-2" style="background-color: white; height: 130px; border: 1px solid #f9f9f9;">
   <a href="view-customers.php">
    <div class="icon-style text-center" style="padding-top: 20px;">
       <img  class="zoom" src="mob_icons/kyc.png" class="img-fluid ui-w-60" style="width: 60px;height: 60px;">
       <h5 style="font-size: 13px;  padding-top:10px;color: #000; text-align: center; text-transform: capitalize;" align="center">
            <span style="color: #000;"> Customers</span>
        </h5>
    </div>
    </a>
</div>
<?php } if(in_array("19", $Options)) {?>
<div class="col-4 col-md-2" style="background-color: white; height: 130px; border: 1px solid #f9f9f9;">
   <a href="view-manufacture.php">
    <div class="icon-style text-center" style="padding-top: 20px;">
       <img  class="zoom" src="mob_icons/manufacturer.png" class="img-fluid ui-w-60" style="width: 60px;height: 60px;">
       <h5 style="font-size: 13px;  padding-top:10px;color: #000; text-align: center; text-transform: capitalize;" align="center">
            <span style="color: #000;"> Manufacture</span>
        </h5>
    </div>
    </a>
</div>
<?php } if(in_array("20", $Options)) {?>
<div class="col-4 col-md-2" style="background-color: white; height: 130px; border: 1px solid #f9f9f9;">
   <a href="view-company.php">
    <div class="icon-style text-center" style="padding-top: 20px;">
       <img  class="zoom" src="mob_icons/organization.png" class="img-fluid ui-w-60" style="width: 60px;height: 60px;">
       <h5 style="font-size: 13px;  padding-top:10px;color: #000; text-align: center; text-transform: capitalize;" align="center">
            <span style="color: #000;"> Company</span>
        </h5>
    </div>
    </a>
</div>
<?php } if(in_array("21", $Options)) {?>
<div class="col-4 col-md-2" style="background-color: white; height: 130px; border: 1px solid #f9f9f9;">
   <a href="view-employee.php">
    <div class="icon-style text-center" style="padding-top: 20px;">
       <img  class="zoom" src="mob_icons/team.png" class="img-fluid ui-w-60" style="width: 60px;height: 60px;">
       <h5 style="font-size: 13px;  padding-top:10px;color: #000; text-align: center; text-transform: capitalize;" align="center">
            <span style="color: #000;"> Employee</span>
        </h5>
    </div>
    </a>
</div>
<?php } if(in_array("22", $Options)) {?>
<div class="col-4 col-md-2" style="background-color: white; height: 130px; border: 1px solid #f9f9f9;">
   <a href="view-dealer.php">
    <div class="icon-style text-center" style="padding-top: 20px;">
       <img  class="zoom" src="mob_icons/dealer.png" class="img-fluid ui-w-60" style="width: 60px;height: 60px;">
       <h5 style="font-size: 13px;  padding-top:10px;color: #000; text-align: center; text-transform: capitalize;" align="center">
            <span style="color: #000;"> Dealer</span>
        </h5>
    </div>
    </a>
</div>
<?php } if(in_array("23", $Options)) {?>
<div class="col-4 col-md-2" style="background-color: white; height: 130px; border: 1px solid #f9f9f9;">
   <a href="view-agency.php">
    <div class="icon-style text-center" style="padding-top: 20px;">
       <img  class="zoom" src="mob_icons/enterprise.png" class="img-fluid ui-w-60" style="width: 60px;height: 60px;">
       <h5 style="font-size: 13px;  padding-top:10px;color: #000; text-align: center; text-transform: capitalize;" align="center">
            <span style="color: #000;"> Agency</span>
        </h5>
    </div>
    </a>
</div>
<?php } if(in_array("24", $Options)) {?>
<div class="col-4 col-md-2" style="background-color: white; height: 130px; border: 1px solid #f9f9f9;">
   <a href="view-products.php">
    <div class="icon-style text-center" style="padding-top: 20px;">
       <img  class="zoom" src="mob_icons/products.png" class="img-fluid ui-w-60" style="width: 60px;height: 60px;">
       <h5 style="font-size: 13px;  padding-top:10px;color: #000; text-align: center; text-transform: capitalize;" align="center">
            <span style="color: #000;"> Products</span>
        </h5>
    </div>
    </a>
</div>


<?php } if(in_array("25", $Options)) {?>
<div class="col-4 col-md-2" style="background-color: white; height: 130px; border: 1px solid #f9f9f9;">
   <a href="view-purchase-order.php">
    <div class="icon-style text-center" style="padding-top: 20px;">
       <img  class="zoom" src="mob_icons/checklist.png" class="img-fluid ui-w-60" style="width: 60px;height: 60px;">
       <h5 style="font-size: 13px;  padding-top:10px;color: #000; text-align: center; text-transform: capitalize;" align="center">
            <span style="color: #000;"> Purchase Order</span>
        </h5>
    </div>
    </a>
</div>
<?php } if(in_array("26", $Options)) {?>
<div class="col-4 col-md-2" style="background-color: white; height: 130px; border: 1px solid #f9f9f9;">
   <a href="view-sells.php">
    <div class="icon-style text-center" style="padding-top: 20px;">
       <img  class="zoom" src="mob_icons/delivery-truck.png" class="img-fluid ui-w-60" style="width: 60px;height: 60px;">
       <h5 style="font-size: 13px;  padding-top:10px;color: #000; text-align: center; text-transform: capitalize;" align="center">
            <span style="color: #000;"> Delivery Challan</span>
        </h5>
    </div>
    </a>
</div>
<?php } if(in_array("27", $Options)) {?>
<div class="col-4 col-md-2" style="background-color: white; height: 130px; border: 1px solid #f9f9f9;">
   <a href="view-quotation.php">
    <div class="icon-style text-center" style="padding-top: 20px;">
       <img  class="zoom" src="icons/course.png" class="img-fluid ui-w-60" style="width: 60px;height: 60px;">
       <h5 style="font-size: 13px;  padding-top:10px;color: #000; text-align: center; text-transform: capitalize;" align="center">
            <span style="color: #000;"> Quotation</span>
        </h5>
    </div>
    </a>
</div>
<?php } if(in_array("28", $Options)) {?>
<div class="col-4 col-md-2" style="background-color: white; height: 130px; border: 1px solid #f9f9f9;">
   <a href="view-service-module.php">
    <div class="icon-style text-center" style="padding-top: 20px;">
       <img  class="zoom" src="icons/course.png" class="img-fluid ui-w-60" style="width: 60px;height: 60px;">
       <h5 style="font-size: 13px;  padding-top:10px;color: #000; text-align: center; text-transform: capitalize;" align="center">
            <span style="color: #000;"> Service Complaint</span>
        </h5>
    </div>
    </a>
</div>
 <?php } if(in_array("29", $Options)) {?>
<div class="col-4 col-md-2" style="background-color: white; height: 130px; border: 1px solid #f9f9f9;">
   <a href="sell-report.php">
    <div class="icon-style text-center" style="padding-top: 20px;">
       <img  class="zoom" src="icons/course.png" class="img-fluid ui-w-60" style="width: 60px;height: 60px;">
       <h5 style="font-size: 13px;  padding-top:10px;color: #000; text-align: center; text-transform: capitalize;" align="center">
            <span style="color: #000;"> Delivery Challan Report</span>
        </h5>
    </div>
    </a>
</div>
<?php } if(in_array("30", $Options)) {?>
<div class="col-4 col-md-2" style="background-color: white; height: 130px; border: 1px solid #f9f9f9;">
   <a href="stock-report2.php">
    <div class="icon-style text-center" style="padding-top: 20px;">
       <img  class="zoom" src="icons/course.png" class="img-fluid ui-w-60" style="width: 60px;height: 60px;">
       <h5 style="font-size: 13px;  padding-top:10px;color: #000; text-align: center; text-transform: capitalize;" align="center">
            <span style="color: #000;"> Stock Report</span>
        </h5>
    </div>
    </a>
</div>
<?php } if(in_array("31", $Options)) {?>
<div class="col-4 col-md-2" style="background-color: white; height: 130px; border: 1px solid #f9f9f9;">
   <a href="stock-report.php">
    <div class="icon-style text-center" style="padding-top: 20px;">
       <img  class="zoom" src="icons/course.png" class="img-fluid ui-w-60" style="width: 60px;height: 60px;">
       <h5 style="font-size: 13px;  padding-top:10px;color: #000; text-align: center; text-transform: capitalize;" align="center">
            <span style="color: #000;"> Outstanding Stock Report</span>
        </h5>
    </div>
    </a>
</div>
<?php } if(in_array("31", $Options)) {?>
<div class="col-4 col-md-2" style="background-color: white; height: 130px; border: 1px solid #f9f9f9;">
   <a href="all-customer-report.php">
    <div class="icon-style text-center" style="padding-top: 20px;">
       <img  class="zoom" src="icons/course.png" class="img-fluid ui-w-60" style="width: 60px;height: 60px;">
       <h5 style="font-size: 13px;  padding-top:10px;color: #000; text-align: center; text-transform: capitalize;" align="center">
            <span style="color: #000;"> Customer Report</span>
        </h5>
    </div>
    </a>
</div>
<?php } if(in_array("32", $Options)) {?>
<div class="col-4 col-md-2" style="background-color: white; height: 130px; border: 1px solid #f9f9f9;">
   <a href="view-insurance-claim.php">
    <div class="icon-style text-center" style="padding-top: 20px;">
       <img  class="zoom" src="icons/course.png" class="img-fluid ui-w-60" style="width: 60px;height: 60px;">
       <h5 style="font-size: 13px;  padding-top:10px;color: #000; text-align: center; text-transform: capitalize;" align="center">
            <span style="color: #000;"> Insurance Claim</span>
        </h5>
    </div>
    </a>
</div>
<?php } if(in_array("33", $Options)) {?>
<div class="col-4 col-md-2" style="background-color: white; height: 130px; border: 1px solid #f9f9f9;">
   <a href="view-purchase-feedback.php">
    <div class="icon-style text-center" style="padding-top: 20px;">
       <img  class="zoom" src="icons/course.png" class="img-fluid ui-w-60" style="width: 60px;height: 60px;">
       <h5 style="font-size: 13px;  padding-top:10px;color: #000; text-align: center; text-transform: capitalize;" align="center">
            <span style="color: #000;"> Calling</span>
        </h5>
    </div>
    </a>
</div>
<?php } ?>
<div class="col-4 col-md-2" style="background-color: white; height: 130px; border: 1px solid #f9f9f9;">
   <a href="change-password.php">
    <div class="icon-style text-center" style="padding-top: 20px;">
       <img  class="zoom" src="icons/course.png" class="img-fluid ui-w-60" style="width: 60px;height: 60px;">
       <h5 style="font-size: 13px;  padding-top:10px;color: #000; text-align: center; text-transform: capitalize;" align="center">
            <span style="color: #000;"> Change Password</span>
        </h5>
    </div>
    </a>
</div>

<div class="col-4 col-md-2" style="background-color: white; height: 130px; border: 1px solid #f9f9f9;">
   <a href="logout.php">
    <div class="icon-style text-center" style="padding-top: 20px;">
       <img  class="zoom" src="icons/course.png" class="img-fluid ui-w-60" style="width: 60px;height: 60px;">
       <h5 style="font-size: 13px;  padding-top:10px;color: #000; text-align: center; text-transform: capitalize;" align="center">
            <span style="color: #000;"> Log Out</span>
        </h5>
    </div>
    </a>
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
    
<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>
    

</body>

</html>