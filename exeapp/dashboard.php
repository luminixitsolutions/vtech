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

if($Roll == 2){
    echo "<script>window.location.href='view-leads-calling.php';</script>";
    exit();
}
if($Roll == 6){
    echo "<script>window.location.href='view-customers.php';</script>";
    exit();
}
if($Roll == 25){
    echo "<script>window.location.href='view-bill-amount-status.php';</script>";
    exit();
}
if($Roll == 26){
    echo "<script>window.location.href='view-sells.php';</script>";
    exit();
}
if($Roll == 27){
    echo "<script>window.location.href='approve-store-incharge.php';</script>";
    exit();
}
if($Roll == 28){
    echo "<script>window.location.href='view-purchase-order.php';</script>";
    exit();
}




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
                             
                            
               
                          <!-- Staustic card 2 Start -->
                      
                            
                           <div class="col-sm-6 col-xl-3">
                                <a href="view-purchase-order.php">
                               <div class="card bg-primary text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                          <h2><?php  
                                                            $sql4 = "SELECT * FROM tbl_purchase_order";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></h2>
                                        <h6 class="mb-0">Total Purchase Orders</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>
                            
                            
                            <div class="col-sm-6 col-xl-3">
                                <a href="view-purchase-order.php">
                               <div class="card bg-success text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                          <h2><?php  
                                                            $sql4 = "SELECT * FROM tbl_purchase_order WHERE InvoiceDate='".date('Y-m-d')."'";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></h2>
                                        <h6 class="mb-0">Today Purchase Orders</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>

                          
                          <div class="col-sm-6 col-xl-3">
                                <a href="view-sells.php">
                               <div class="card bg-danger text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                          <h2><?php  
                                                            $sql4 = "SELECT * FROM tbl_sell WHERE InvoiceDate='".date('Y-m-d')."'";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></h2>
                                        <h6 class="mb-0">Todays Delivery Challan</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>
                            
                            <div class="col-sm-6 col-xl-3">
                                <a href="view-sells.php">
                               <div class="card bg-warning text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                          <h2><?php  
                                                            $sql4 = "SELECT * FROM tbl_sell";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></h2>
                                        <h6 class="mb-0">Total Delivery Challan</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>
                             

                           <div class="col-sm-6 col-xl-3">
                                <a href="view-quotation.php">
                               <div class="card bg-info text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                          <h2><?php  
                                                            $sql4 = "SELECT * FROM tbl_quotation";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></h2>
                                        <h6 class="mb-0">Total Quotations</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>

<div class="col-sm-6 col-xl-3">
                                <a href="view-work-order.php">
                               <div class="card bg-secondary text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                          <h2><?php  
                                                            $sql4 = "SELECT * FROM tbl_work_order";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></h2>
                                        <h6 class="mb-0">Total Work Order</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>
                             

                            <div class="col-sm-6 col-xl-3">
                                <a href="view-service-module.php">
                               <div class="card bg-primary text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                          <h2><?php  
                                                            $sql4 = "SELECT * FROM tbl_service_complaint";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></h2>
                                        <h6 class="mb-0">Total Service Complaints</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>

                             <div class="col-sm-6 col-xl-3">
                                <a href="view-service-module.php">
                               <div class="card bg-success text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                          <h2><?php  
                                                            $sql4 = "SELECT * FROM tbl_service_complaint WHERE CreatedDate='".date('Y-m-d')."'";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></h2>
                                        <h6 class="mb-0">Today Service Complaints</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>
                            
                           
                             <div class="col-sm-6 col-xl-3">
                                <a href="view-insurance-claim.php">
                               <div class="card bg-danger text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                          <h2><?php  
                                                            $sql4 = "SELECT * FROM tbl_insurance_clain";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></h2>
                                        <h6 class="mb-0">Total Insurance Claim</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>

 
 <div class="col-sm-6 col-xl-3">
                                <a href="view-insurance-claim.php">
                               <div class="card bg-warning text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                          <h2><?php  
                                                            $sql4 = "SELECT * FROM tbl_insurance_clain WHERE CreatedDate='".date('Y-m-d')."'";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></h2>
                                        <h6 class="mb-0">Today Insurance Claim</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>
                            
 <div class="col-sm-6 col-xl-3">
                                <a href="view-products.php">
                               <div class="card bg-info text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                          <h2><?php  
                                                            $sql = "SELECT * FROM tbl_products";
                                                            echo $rncnt = getRow($sql);

                                                        ?></h2>
                                        <h6 class="mb-0">Total Products</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>

                            <div class="col-sm-6 col-xl-3">
                                  <a href="view-customers.php">
                               <div class="card bg-secondary text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                          <h2><?php  
                                                            $sql2 = "SELECT * FROM tbl_users WHERE Roll=5";
                                                            echo $rncnt2 = getRow($sql2);

                                                        ?></h2>
                                        <h6 class="mb-0">Total Customers</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>
                            
                             <div class="col-sm-6 col-xl-3">
                                  <a href="view-manufacture.php">
                               <div class="card bg-primary text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                          <h2><?php  
                                                            $sql3 = "SELECT * FROM tbl_users WHERE Roll=3";
                                                            echo $rncnt3 = getRow($sql3);

                                                        ?></h2>
                                        <h6 class="mb-0">Total Manufacturers</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>
                            
                            <div class="col-sm-6 col-xl-3">
                                  <a href="view-employee.php">
                               <div class="card bg-success text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                          <h2><?php  
                                                            $sql3 = "SELECT * FROM tbl_users WHERE Roll=8";
                                                            echo $rncnt3 = getRow($sql3);

                                                        ?></h2>
                                        <h6 class="mb-0">Total Employee</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>
                            
                          
                              <div class="col-sm-6 col-xl-3">
                                 <a href="view-dealer.php">
                               <div class="card bg-danger text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                          <h2><?php  
                                                            $sql3 = "SELECT * FROM tbl_users WHERE Roll=9";
                                                            echo $rncnt3 = getRow($sql3);

                                                        ?></h2>
                                        <h6 class="mb-0">Total Dealers</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
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
    

  
<script type="text/javascript">
 function take_action(id,url){
     //alert(url);
     setTimeout(function() {
        window.open(
            url+'?id=' + id, 'stickerPrint',
            'toolbar=1, scrollbars=1, location=1,statusbar=0, menubar=1, resizable=1, width=800, height=800,left=250,top=50,right=50'
        );
    }, 1);
 
 }

 function getDiapostion(catid){
            var action = "getDiapostion";
            $.ajax({
                url: "ajax_files/ajax_dropdown.php",
                method: "POST",
                data: {
                    action: action,
                    id: catid
                },
                success: function(data) {
                    $('#Diaposition').html(data);
                  
                }
            });
 }
    $(document).ready(function() {
    $('#example').DataTable({
      "scrollX": true,
        "pageLength":50,
        scrollY: '400px',
        scrollCollapse: true
    });

    $(document).on("change", "#CatId", function(event) {
            var val = this.value;
            getDiapostion(val);
            var action = "getServices";
            $.ajax({
                url: "ajax_files/ajax_dropdown.php",
                method: "POST",
                data: {
                    action: action,
                    id: val
                },
                success: function(data) {
                    $('#Services').html(data);
                  
                }
            });

        });
});
</script>
</body>

</html>