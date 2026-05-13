<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
    <title></title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="Codedthemes" />
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">

    <!-- Google fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
    <!-- Icon fonts -->
    <link rel="stylesheet" href="assets/fonts/fontawesome.css">
    <link rel="stylesheet" href="assets/fonts/ionicons.css">
    <link rel="stylesheet" href="assets/fonts/linearicons.css">
    <link rel="stylesheet" href="assets/fonts/open-iconic.css">
    <link rel="stylesheet" href="assets/fonts/pe-icon-7-stroke.css">
    <link rel="stylesheet" href="assets/fonts/feather.css">

    <!-- Core stylesheets -->
    <link rel="stylesheet" href="assets/css/bootstrap-material.css">
    <link rel="stylesheet" href="assets/css/shreerang-material.css">
    <link rel="stylesheet" href="assets/css/uikit.css">

    <!-- Libs -->
    <link rel="stylesheet" href="assets/libs/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="assets/libs/flot/flot.css">
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

            <?php include_once 'header.php'; ?>


            <div class="layout-container">

              <?php include_once 'top_header.php'; ?>
                <!-- [ Layout content ] Start -->
 <?php 
 if($Roll == 26){
    echo "<script>window.location.href='dispatch-dashboard.php';</script>";exit();
}
else if($Roll != 1){
    echo "<script>window.location.href='emp-dashboard.php';</script>";exit();
}else{}
?>                
                <div class="layout-content">
                    <!-- [ content ] Start -->
                    <div class="container flex-grow-1 container-p-y">
                        <h5 class="font-weight-bold py-3 mb-0">Dashboard</h4>
                        
                         <div class="row">
                         <div class="col-sm-6 col-xl-2">
                                <a href="#">
                               <div class="card mb-4 bg-pattern-3-dark">
                                    <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;">Total Purchase Orders</h6>
                                        <div class="text-large"><?php  
                                                            $sql4 = "SELECT * FROM tbl_purchase_order";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></div>
                                        
                                     </div>
                                        </div>
                                     </div>
                                </div></a>
                            </div>

                            <div class="col-sm-6 col-xl-2">
                                <a href="#">
                               <div class="card mb-4 bg-pattern-3-dark">
                                    <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;">Today Purchase Orders</h6>
                                          <div class="text-large"><?php  
                                                            $sql4 = "SELECT * FROM tbl_purchase_order WHERE InvoiceDate='".date('Y-m-d')."'";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></div>
                                        
                                     </div>
                                        </div>
                                     </div>
                                </div></a>
                            </div>

                            <div class="col-sm-6 col-xl-2">
                                <a href="#">
                               <div class="card mb-4 bg-pattern-3-dark">
                                    <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                 <h6 class="mb-0" style="color: black;">Todays Delivery Challan</h6>
                                          <div class="text-large"><?php  
                                                $sql4 = "SELECT * FROM tbl_sell WHERE InvoiceDate='".date('Y-m-d')."'";
                                                echo $rncnt4 = getRow($sql4);
                                                ?></div>
                                       
                                     </div>
                                        </div>
                                     </div>
                                </div></a>
                            </div>

                             <div class="col-sm-6 col-xl-2">
                                <a href="#">
                               <div class="card mb-4 bg-pattern-3-dark">
                                    <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;">Total Delivery Challan</h6>
                                          <div class="text-large"><?php  
                                                $sql4 = "SELECT * FROM tbl_sell";
                                                echo $rncnt4 = getRow($sql4);
                                                ?></div>
                                        
                                     </div>
                                        </div>
                                     </div>
                                </div></a>
                            </div>

                            <div class="col-sm-6 col-xl-2">
                                <a href="#">
                               <div class="card mb-4 bg-pattern-3-dark">
                                    <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;">Total <br>Quotations</h6>
                                          <div class="text-large"><?php  
                                                $sql4 = "SELECT * FROM tbl_quotation";
                                                echo $rncnt4 = getRow($sql4);
                                                ?></div>
                                        
                                     </div>
                                        </div>
                                     </div>
                                </div></a>
                            </div>

                            <div class="col-sm-6 col-xl-2">
                                <a href="#">
                               <div class="card mb-4 bg-pattern-3-dark">
                                    <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;">Total <br>Quotations</h6>
                                          <div class="text-large"><?php  
                                                $sql4 = "SELECT * FROM tbl_quotation";
                                                echo $rncnt4 = getRow($sql4);
                                                ?></div>
                                        
                                     </div>
                                        </div>
                                     </div>
                                </div></a>
                            </div>

                            <div class="col-sm-6 col-xl-2">
                                <a href="#">
                               <div class="card mb-4 bg-pattern-3-dark">
                                    <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;">Total Work Order</h6>
                                          <div class="text-large"><?php  
                                                $sql4 = "SELECT * FROM tbl_work_order";
                                                echo $rncnt4 = getRow($sql4);
                                                ?></div>
                                        
                                     </div>
                                        </div>
                                     </div>
                                </div></a>
                            </div>

                            <div class="col-sm-6 col-xl-2">
                                <a href="#">
                               <div class="card mb-4 bg-pattern-3-dark">
                                    <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;">Total Service Complaints</h6>
                                          <div class="text-large"><?php  
                                                $sql4 = "SELECT * FROM tbl_service_complaint";
                                                echo $rncnt4 = getRow($sql4);
                                                ?></div>
                                        
                                     </div>
                                        </div>
                                     </div>
                                </div></a>
                            </div>

                              <div class="col-sm-6 col-xl-2">
                                <a href="#">
                               <div class="card mb-4 bg-pattern-3-dark">
                                    <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;">Today Service Complaints</h6>
                                          <div class="text-large"><?php  
                                                $sql4 = "SELECT * FROM tbl_service_complaint WHERE CreatedDate='".date('Y-m-d')."'";
                                                echo $rncnt4 = getRow($sql4);
                                                ?></div>
                                        
                                     </div>
                                        </div>
                                     </div>
                                </div></a>
                            </div>

  <?php 
  $sql12 = "SELECT * FROM tbl_common_master WHERE Status='1' AND Roll=6";
  $row12 = getList($sql12);
  foreach($row12 as $result){
    $sql55 = "SELECT * FROM tbl_service_complaint WHERE ClainStatus='".$result['Name']."'";
    $rncnt55 = getRow($sql55);
     ?>
                             <div class="col-sm-6 col-xl-2">
                                <a href="#">
                               <div class="card mb-4 bg-pattern-3-dark">
                                    <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;"><?php echo $result['Name']; ?> Service Complaints</h6>
                                          <div class="text-large"><?php echo $rncnt55;?></div>
                                        
                                     </div>
                                        </div>
                                     </div>
                                </div></a>
                            </div>
                             <?php } ?>

                              <div class="col-sm-6 col-xl-2">
                                <a href="#">
                               <div class="card mb-4 bg-pattern-3-dark">
                                    <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                 <h6 class="mb-0" style="color: black;">Total Insurance Claim</h6>
                                          <div class="text-large"><?php  
                                                $sql4 = "SELECT * FROM tbl_service_complaint WHERE ServiceType='Insurance'";
                                                echo $rncnt4 = getRow($sql4);
                                                ?></div>
                                       
                                     </div>
                                        </div>
                                     </div>
                                </div></a>
                            </div>

                            <div class="col-sm-6 col-xl-2">
                                <a href="#">
                               <div class="card mb-4 bg-pattern-3-dark">
                                    <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;">Today Insurance Claim</h6>
                                          <div class="text-large"><?php  
                                                $sql4 = "SELECT * FROM tbl_service_complaint WHERE ServiceType='Insurance' AND CreatedDate='".date('Y-m-d')."'";
                                                echo $rncnt4 = getRow($sql4);
                                                ?></div>
                                        
                                     </div>
                                        </div>
                                     </div>
                                </div></a>
                            </div>

                            <div class="col-sm-6 col-xl-2">
                                <a href="#">
                               <div class="card mb-4 bg-pattern-3-dark">
                                    <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;">Total Products</h6>
                                          <div class="text-large"><?php  
                                               $sql = "SELECT * FROM tbl_products";
                                                echo $rncnt4 = getRow($sql);
                                                ?></div>
                                        
                                     </div>
                                        </div>
                                     </div>
                                </div></a>
                            </div>

                            <div class="col-sm-6 col-xl-2">
                                <a href="#">
                               <div class="card mb-4 bg-pattern-3-dark">
                                    <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;">Total Customers</h6>
                                          <div class="text-large"><?php  
                                             $sql2 = "SELECT * FROM tbl_users WHERE Roll=5";
                                                            echo $rncnt2 = getRow($sql2);
                                                ?></div>
                                        
                                     </div>
                                        </div>
                                     </div>
                                </div></a>
                            </div>

                            <div class="col-sm-6 col-xl-2">
                                <a href="#">
                               <div class="card mb-4 bg-pattern-3-dark">
                                    <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;">Total Manufacturers</h6>
                                          <div class="text-large"><?php  
                                              $sql3 = "SELECT * FROM tbl_users WHERE Roll=3";
                                                            echo $rncnt3 = getRow($sql3);
                                                ?></div>
                                        
                                     </div>
                                        </div>
                                     </div>
                                </div></a>
                            </div>

                             <div class="col-sm-6 col-xl-2">
                                <a href="#">
                               <div class="card mb-4 bg-pattern-3-dark">
                                    <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;">Total Employee</h6>
                                          <div class="text-large"><?php  
                                              $sql3 = "SELECT * FROM tbl_users WHERE Roll=8";
                                                            echo $rncnt3 = getRow($sql3);

                                                ?></div>
                                        
                                     </div>
                                        </div>
                                     </div>
                                </div></a>
                            </div>

                            <div class="col-sm-6 col-xl-2">
                                <a href="#">
                               <div class="card mb-4 bg-pattern-3-dark">
                                    <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;">Total Dealers</h6>
                                          <div class="text-large"><?php  
                                              $sql3 = "SELECT * FROM tbl_users WHERE Roll=9";
                                                            echo $rncnt3 = getRow($sql3);

                                                ?></div>
                                        
                                     </div>
                                        </div>
                                     </div>
                                </div></a>
                            </div>
</div>
    
    <h5 class="font-weight-bold py-3 mb-0">Scheme/Yojna</h4>
 <div class="row">
                            <?php 
        $q = "select * from tbl_scheme WHERE Status='1' ORDER BY Name ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                            <div class="col-sm-6 col-xl-2">
                                <a href="#">
                               <div class="card mb-4 bg-pattern-3-dark">
                                    <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;"><?php echo $rw['Name']; ?></h6>
                                          <div class="text-large"><?php  
                                             $sql2 = "SELECT * FROM tbl_users WHERE SchemeId='".$rw['id']."'";
                                        echo $rncnt2 = getRow($sql2);

                                                ?></div>
                                        
                                     </div>
                                        </div>
                                     </div>
                                </div></a>
                            </div>
                             <?php } ?>

                            </div>
                        
                        
                        <h5 class="font-weight-bold py-3 mb-0">Project</h4>
 <div class="row">
                            <?php 
        $q = "select * from tbl_common_master WHERE Status='1' AND Roll=24 ORDER BY Name ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                            <div class="col-sm-6 col-xl-2">
                                <a href="#">
                               <div class="card mb-4 bg-pattern-3-dark">
                                    <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;"><?php echo $rw['Name']; ?></h6>
                                          <div class="text-large"><?php  
                                             $sql2 = "SELECT * FROM tbl_users WHERE ProjectId='".$rw['id']."'";
                                        echo $rncnt2 = getRow($sql2);

                                                ?></div>
                                        
                                     </div>
                                        </div>
                                     </div>
                                </div></a>
                            </div>
                             <?php } ?>

                            </div>
                            
                            <h5 class="font-weight-bold py-3 mb-0">Project Sub Head</h4>
 <div class="row">
                            <?php 
        $q = "select * from tbl_project_sub_head WHERE Status='1' ORDER BY Name ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                            <div class="col-sm-6 col-xl-2">
                                <a href="#">
                               <div class="card mb-4 bg-pattern-3-dark">
                                    <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;"><?php echo $rw['Name']; ?></h6>
                                          <div class="text-large"><?php  
                                             $sql2 = "SELECT * FROM tbl_users WHERE ProjectSubHeadId='".$rw['id']."'";
                                        echo $rncnt2 = getRow($sql2);

                                                ?></div>
                                        
                                     </div>
                                        </div>
                                     </div>
                                </div></a>
                            </div>
                             <?php } ?>

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
    <script src="assets/js/pace.js"></script>
    <script src="assets/js/jquery-3.3.1.min.js"></script>
    <script src="assets/libs/popper/popper.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/sidenav.js"></script>
    <script src="assets/js/layout-helpers.js"></script>
    <script src="assets/js/material-ripple.js"></script>

    <!-- Libs -->
    <script src="assets/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    
    <!-- Demo -->
    <script src="assets/js/demo.js"></script>
    <script src="assets/js/analytics.js"></script>
</body>

</html>
