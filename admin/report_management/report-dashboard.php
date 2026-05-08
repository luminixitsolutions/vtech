<?php 
session_start();
include_once '../config.php';
include_once '../auth.php';
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
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="" />
    <?php include_once '../header_script.php'; ?>
</head>

<body>
    <style type="text/css">
    .mr_5 {
        margin-right: 3rem !important;
    }
    </style>
   <div class="layout-wrapper layout-2">
        <div class="layout-inner">

            <?php include_once 'report-sidebar.php'; ?>


            <div class="layout-container">

              <?php include_once '../top_header.php'; ?>


                <div class="layout-content">
                    <div class="container-fluid flex-grow-1 container-p-y">
                        <div class="text-muted small mt-0 mb-4 d-block breadcrumb">
                        </div>

<div class="row">
                 <div class="col-xl-12 col-md-12">
                                <div class="card ui-task mb-4">
                                    <h5 class="card-header" style="text-align:center;">REPORT DASHBOARD</h5>
                                    <div class="card-body">             
                    <div class="row">        
               <?php  if(in_array("29", $Options)) {?>
                        <div class="col-sm-6 col-xl-2">
                                <a href="sell-report.php">
                               <div class="card bg-warning text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                       
                                        <h6 class="mb-0">Delivery Challan Report</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>
                                <?php } if(in_array("115", $Options)) {?>   
                          <div class="col-sm-6 col-xl-2">
                                <a href="trip-report.php">
                               <div class="card bg-warning text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                       
                                        <h6 class="mb-0">Trip <br>Report</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>   
                        <?php } if(in_array("30", $Options)) {?>   
                          <div class="col-sm-6 col-xl-2">
                                <a href="stock-report2.php">
                               <div class="card bg-warning text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                       
                                        <h6 class="mb-0">Stock <br>Report</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>   
                        <?php } if(in_array("31", $Options)) {?>    
                            <div class="col-sm-6 col-xl-2">
                                <a href="stock-report.php">
                               <div class="card bg-warning text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                       
                                        <h6 class="mb-0">Outstanding Stock Report</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>   
                            <?php } if(in_array("38", $Options)) {?>
                                <div class="col-sm-6 col-xl-2">
                                <a href="all-customer-report.php">
                               <div class="card bg-warning text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                       
                                        <h6 class="mb-0">Customer Report</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>   
                            <?php } if(in_array("39", $Options)) {?>
                                 <div class="col-sm-6 col-xl-2">
                                <a href="daily-record-report.php">
                               <div class="card bg-warning text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                       
                                        <h6 class="mb-0">Daily Record Report</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>   
                            <?php } if(in_array("99", $Options)) {?>
                             <div class="col-sm-6 col-xl-2">
                                <a href="attendance-report.php">
                               <div class="card bg-warning text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                       
                                        <h6 class="mb-0">Attendance Report</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>  
                            
                            <div class="col-sm-6 col-xl-2">
                                <a href="attendance-report-2.php">
                               <div class="card bg-warning text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                       
                                        <h6 class="mb-0">Attendance Report 2</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>  
                            <?php } if(in_array("100", $Options)) {?>    

                                <div class="col-sm-6 col-xl-2">
                                <a href="vehical-report.php">
                               <div class="card bg-warning text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                       
                                        <h6 class="mb-0">Vehical <br>Report</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>  
                       <?php } if(in_array("65", $Options)) {?>
                        <div class="col-sm-6 col-xl-2">
                                <a href="dealer-report.php">
                               <div class="card bg-warning text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                       
                                        <h6 class="mb-0">Dealer <br>Report</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>  
                            <?php } if(in_array("101", $Options)) {?>
                         <div class="col-sm-6 col-xl-2">
                                <a href="store-stock-report.php">
                               <div class="card bg-warning text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                       
                                        <h6 class="mb-0">Store Stock Report</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>   

                        <div class="col-sm-6 col-xl-2">
                                <a href="store-stock-report-2.php">
                               <div class="card bg-warning text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                       
                                        <h6 class="mb-0">Store Stock Report 2</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>  

                      <?php } if(in_array("102", $Options)) {?>   
                      
                      <div class="col-sm-6 col-xl-2">
                                <a href="store-item-report.php">
                               <div class="card bg-warning text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                       
                                        <h6 class="mb-0">Store Incharge Stock Report</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div> 
                           
<?php } if(in_array("104", $Options)) {?>
                            <div class="col-sm-6 col-xl-2">
                                <a href="field-survey-report.php">
                               <div class="card bg-warning text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                       
                                        <h6 class="mb-0">Field Survey Report</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div> 
<?php } if(in_array("106", $Options)) {?>
    <div class="col-sm-6 col-xl-2">
                                <a href="installation-report.php">
                               <div class="card bg-warning text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                       
                                        <h6 class="mb-0">Installation Report</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div> 
              <?php } if(in_array("107", $Options)) {?>      
<div class="col-sm-6 col-xl-2">
                                <a href="inspection-report.php">
                               <div class="card bg-warning text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                       
                                        <h6 class="mb-0">Inspection Report</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div> 
<?php } if(in_array("108", $Options)) {?>
<div class="col-sm-6 col-xl-2">
                                <a href="site-engineer-report.php?FromDate=<?php echo date('Y-m-d');?>&ToDate=<?php echo date('Y-m-d');?>">
                               <div class="card bg-warning text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                       
                                        <h6 class="mb-0">Site Engineer Report</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div> 
<?php } if(in_array("109", $Options)) {?>
    <div class="col-sm-6 col-xl-2">
                                <a href="dispatch-calling-report.php">
                               <div class="card bg-warning text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                       
                                        <h6 class="mb-0">Dispatch Calling Report</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div> 
                             <?php } if(in_array("103", $Options)) {?>   
                        <div class="col-sm-6 col-xl-2">
                                <a href="dispatch-officer-stock-report.php">
                               <div class="card bg-warning text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                       
                                        <h6 class="mb-0">Dispatch Officer Stock Report</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div> 
<?php } if(in_array("110", $Options)) {?>
<div class="col-sm-6 col-xl-2">
                                <a href="before-installation-calling-report.php">
                               <div class="card bg-warning text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                       
                                        <h6 class="mb-0">Before Installation Calling Report</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div> 
<?php } if(in_array("111", $Options)) {?>
<div class="col-sm-6 col-xl-2">
                                <a href="after-installation-calling-report.php">
                               <div class="card bg-warning text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                       
                                        <h6 class="mb-0">After Installation Calling Report</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div> 
<?php } if(in_array("112", $Options)) {?>
<div class="col-sm-6 col-xl-2">
                                <a href="before-inspection-calling-report.php">
                               <div class="card bg-warning text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                       
                                        <h6 class="mb-0">Before Inspection Calling Report</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div> 
  <?php } if(in_array("119", $Options)) {?>
<div class="col-sm-6 col-xl-2">
                                <a href="beneficiary-selection-calling-report.php">
                               <div class="card bg-warning text-white ui-hover-icon mb-4 bg-pattern-3">
                                        <div class="card-body text-center">
                                       
                                        <h6 class="mb-0">Beneficiary Selection Calling Report</h6>
                                        <i class="lnr lnr-users hov-icon"></i>
                                     </div>
                                </div></a>
                            </div>                           
<?php } ?>                            

    </div>
</div>
                                </div>
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

</body>

</html>