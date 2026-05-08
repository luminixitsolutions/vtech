<?php session_start();
$sessionid = session_id();
require_once 'config.php';
$PageName = "Home";
if($_REQUEST['uid'] == ''){
  $uid = $_SESSION['User']['id'];
}
else{
$uid = $_REQUEST['uid'];    
}
$sql11 = "SELECT * FROM tbl_users WHERE id='$uid'";
$row = getRecord($sql11);
$_SESSION['User'] = $row;
require_once 'auth.php';
?>
<!doctype html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="generator" content="">
    <title><?php echo $Proj_Title; ?></title>

    <!-- manifest meta -->
    <meta name="apple-mobile-web-app-capable" content="yes">

    <!-- Favicons -->
    <link rel="manifest" href="manifest.json" />

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js">
</script>

    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet" id="style">
    <link rel="stylesheet" href="dist/css/styles.css" />
   
</head>

  

<body class="body-scroll d-flex flex-column h-100 menu-overlay" data-page="shop">
    
    
    
 <?php include_once 'sidebar.php'; ?>

    <!-- Begin page content -->
    <main class="flex-shrink-0 main has-footer">
        <!-- Fixed navbar -->
      <?php include_once 'top_header.php'; ?>

        <!-- page content start -->
<!-- page content start -->
   

        <div class="main-container  text-center" style="background-color:#fff;">

            
           
           <div class="row justify-content-equal no-gutters mt-4">
                            
                            <div class="col-4 col-md-2 mb-3">
                                <a href="attendance.php">
                                    <div class="avatar avatar-70 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="mob_icons/attendance.png" alt="">
                                          
                                        </div>
                                    </div>
                                </a>
                                <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Attendance NEW</small></p>
                            </div>

                            <div class="col-4 col-md-2 mb-3">
                                <a href="vehical-entry.php">
                                    <div class="avatar avatar-70 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="mob_icons/scooter.jpg" alt="">
                                          
                                        </div>
                                    </div>
                                </a>
                                <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Vehical Entry</small></p>
                            </div>

                            <div class="col-4 col-md-2 mb-3">
                                <a href="view-vehical-entry.php">
                                    <div class="avatar avatar-70 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="mob_icons/vehical.png" alt="">
                                          
                                        </div>
                                    </div>
                                </a>
                                <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Vehical Entries</small></p>
                            </div>

                            <div class="col-4 col-md-2 mb-3">
                                <a href="add-expenses.php">
                                    <div class="avatar avatar-70 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="mob_icons/8.jpg" alt="">
                                          
                                        </div>
                                    </div>
                                </a>
                                <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Expense Request</small></p>
                            </div>

                            <div class="col-4 col-md-2 mb-3">
                                <a href="view-expenses.php">
                                    <div class="avatar avatar-70 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="mob_icons/4.jpg" alt="">
                                          
                                        </div>
                                    </div>
                                </a>
                                <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;"> Expenses</small></p>
                            </div>
                            
                            <div class="col-4 col-md-2 mb-3">
                                <a href="view-tasks.php">
                                    <div class="avatar avatar-70 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="mob_icons/4.jpg" alt="">
                                          
                                        </div>
                                    </div>
                                </a>
                                <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;"> Task</small></p>
                            </div>
                            
                             <div class="col-4 col-md-2 mb-3">
                                <a href="view-application-form.php">
                                    <div class="avatar avatar-70 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="mob_icons/scooter.jpg" alt="">
                                          
                                        </div>
                                    </div>
                                </a>
                                <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Create <br>Application</small></p>
                            </div>

                                 <?php  if(in_array("56", $Options)) {?>
                                <div class="col-4 col-md-2 mb-3">
                                <a href="view-quotation-products.php">
                                    <div class="avatar avatar-70 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="mob_icons/2.jpg" alt="">
                                          
                                        </div>
                                    </div>
                                </a>
                                <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">PI/Quotation Products</small></p>
                            </div>


                            <?php } if(in_array("2", $Options)) {?>
                            <div class="col-4 col-md-2 mb-3">
                                <a href="branches.php">
                                    <div class="avatar avatar-70 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="mob_icons/store.jpg" alt="">
                                          
                                        </div>
                                    </div>
                                </a>
                                <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Store</small></p>
                            </div>
                            <?php } if(in_array("3", $Options)) {?>
                            <div class="col-4 col-md-2 mb-3">
                                <a href="issues.php">
                                    <div class="avatar avatar-70 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="mob_icons/issue.jpg" alt="">
                                          
                                        </div>
                                    </div>
                                </a>
                                <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Issues</small></p>
                            </div>
                            <?php } if(in_array("4", $Options)) {?>
                            <div class="col-4 col-md-2 mb-3">
                                <a href="scheme.php">
                                    <div class="avatar avatar-70 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="mob_icons/5.jpg" alt="">
                                          
                                        </div>
                                    </div>
                                </a>
                                <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Scheme</small></p>
                            </div>
                        <?php } if(in_array("5", $Options)) {?>
                            <div class="col-4 col-md-2 mb-3">
                                <a href="user-type.php">
                                    <div class="avatar avatar-70 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="mob_icons/user.jpg" alt="">
                                          
                                        </div>
                                    </div>
                                </a>
                                <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">User Type</small></p>
                            </div>
                            <?php } if(in_array("6", $Options)) {?>
                                <div class="col-4 col-md-2 mb-3">
                                <a href="common-master.php?pageid=1">
                                    <div class="avatar avatar-70 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="mob_icons/air-compressor.jpg" alt="">
                                          
                                        </div>
                                    </div>
                                </a>
                                <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Pump Head</small></p>
                            </div>
                        <?php } if(in_array("7", $Options)) {?>
                                <div class="col-4 col-md-2 mb-3">
                                <a href="common-master.php?pageid=2">
                                    <div class="avatar avatar-70 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="mob_icons/pump.jpg" alt="">
                                          
                                        </div>
                                    </div>
                                </a>
                                <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Pump Capacity</small></p>
                            </div>
                            <?php } if(in_array("8", $Options)) {?>
                                <div class="col-4 col-md-2 mb-3">
                                <a href="common-master.php?pageid=3">
                                    <div class="avatar avatar-70 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="mob_icons/WATER_SOURCE.png" alt="">
                                          
                                        </div>
                                    </div>
                                </a>
                                <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Water Source</small></p>
                            </div>
                            <?php } if(in_array("9", $Options)) {?>
                                <div class="col-4 col-md-2 mb-3">
                                <a href="common-master.php?pageid=4">
                                    <div class="avatar avatar-70 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="mob_icons/Pump_Surface.png" alt="">
                                          
                                        </div>
                                    </div>
                                </a>
                                <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Pump Surface</small></p>
                            </div>
                            <?php } if(in_array("12", $Options)) {?>
                                <div class="col-4 col-md-2 mb-3">
                                <a href="common-master.php?pageid=7">
                                    <div class="avatar avatar-70 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="mob_icons/Bore_Dia.jpg" alt="">
                                          
                                        </div>
                                    </div>
                                </a>
                                <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Bore Dia</small></p>
                            </div>
                        <?php } if(in_array("13", $Options)) {?>
                                <div class="col-4 col-md-2 mb-3">
                                <a href="common-master.php?pageid=8">
                                    <div class="avatar avatar-70 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="mob_icons/cust1.jpg" alt="">
                                          
                                        </div>
                                    </div>
                                </a>
                                <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Customer Type</small></p>
                            </div>
                            <?php } if(in_array("34", $Options)) {?>
                                <div class="col-4 col-md-2 mb-3">
                                <a href="common-master.php?pageid=9">
                                    <div class="avatar avatar-70 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="mob_icons/insurance.jpg" alt="">
                                          
                                        </div>
                                    </div>
                                </a>
                                <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Insurance Agency</small></p>
                            </div>
                            <?php } if(in_array("15", $Options)) {?>
                                <div class="col-4 col-md-2 mb-3">
                                <a href="common-master.php?pageid=5">
                                    <div class="avatar avatar-70 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="mob_icons/claim.jpg" alt="">
                                          
                                        </div>
                                    </div>
                                </a>
                                <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Insurance Claim Reason</small></p>
                            </div>
                            <?php } if(in_array("16", $Options)) {?>
                                <div class="col-4 col-md-2 mb-3">
                                <a href="common-master.php?pageid=6">
                                    <div class="avatar avatar-70 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="mob_icons/cr.jpg" alt="">
                                          
                                        </div>
                                    </div>
                                </a>
                                <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Insurance Claim Status</small></p>
                            </div>
                            <?php } if(in_array("53", $Options)) {?>
                                <div class="col-4 col-md-2 mb-3">
                                <a href="common-master.php?pageid=10">
                                    <div class="avatar avatar-70 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="mob_icons/lead.jpg" alt="">
                                          
                                        </div>
                                    </div>
                                </a>
                                <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Lead Source</small></p>
                            </div>
                            <?php } if(in_array("54", $Options)) {?>
                                <div class="col-4 col-md-2 mb-3">
                                <a href="common-master.php?pageid=11">
                                    <div class="avatar avatar-70 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="mob_icons/lds.jpg" alt="">
                                          
                                        </div>
                                    </div>
                                </a>
                                <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Lead Status</small></p>
                            </div>
                            <?php } if(in_array("44", $Options)) {?>
                                <div class="col-4 col-md-2 mb-3">
                                <a href="add-lead.php">
                                    <div class="avatar avatar-70 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="mob_icons/lc.jpg" alt="">
                                          
                                        </div>
                                    </div>
                                </a>
                                <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Lead Creation</small></p>
                            </div>
                            <?php } if(in_array("45", $Options)) {?>
                                <div class="col-4 col-md-2 mb-3">
                                <a href="view-leads.php">
                                    <div class="avatar avatar-70 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="mob_icons/lc1.jpg" alt="">
                                          
                                        </div>
                                    </div>
                                </a>
                                <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">View Leads</small></p>
                            </div>
                            <?php } if(in_array("46", $Options)) {?>
                                <div class="col-4 col-md-2 mb-3">
                                <a href="assign-leads.php">
                                    <div class="avatar avatar-70 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="mob_icons/Lead_Assign.png" alt="">
                                          
                                        </div>
                                    </div>
                                </a>
                                <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Lead Assign</small></p>
                            </div>
                            <?php } if(in_array("47", $Options)) {?>
                                <div class="col-4 col-md-2 mb-3">
                                <a href="view-leads-calling.php">
                                    <div class="avatar avatar-70 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="mob_icons/daily-calendar.jpg" alt="">
                                          
                                        </div>
                                    </div>
                                </a>
                                <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">To do Activity</small></p>
                            </div>
                            <?php } if(in_array("63", $Options)) {?>
                                <div class="col-4 col-md-2 mb-3">
                                <a href="lead-completed-customers.php">
                                    <div class="avatar avatar-70 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="mob_icons/pros.jpg" alt="">
                                          
                                        </div>
                                    </div>
                                </a>
                                <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Prospects Customers</small></p>
                            </div>
                            <?php } if(in_array("50", $Options)) {?>
                                <div class="col-4 col-md-2 mb-3">
                                <a href="lead-quotation.php">
                                    <div class="avatar avatar-70 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="mob_icons/quotation.png" alt="">
                                          
                                        </div>
                                    </div>
                                </a>
                                <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Quotation</small></p>
                            </div>
                            <?php } if(in_array("49", $Options)) {?>
                                <div class="col-4 col-md-2 mb-3">
                                <a href="opportunity.php">
                                    <div class="avatar avatar-70 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="mob_icons/opportunity.jpg" alt="">
                                          
                                        </div>
                                    </div>
                                </a>
                                <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Opportunity</small></p>
                            </div>
                            <?php } if(in_array("51", $Options)) {?>
                                <div class="col-4 col-md-2 mb-3">
                                <a href="opportunity-convert-to-order.php">
                                    <div class="avatar avatar-70 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="mob_icons/Opportunity2.png" alt="">
                                          
                                        </div>
                                    </div>
                                </a>
                                <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Opportunity Convert To Order</small></p>
                            </div>
                        <?php } if(in_array("52", $Options)) {?>
                        <div class="col-4 col-md-2 mb-3">
                                <a href="social-media-marketing.php">
                                    <div class="avatar avatar-70 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="mob_icons/Social_Media_Marketing.png" alt="">
                                          
                                        </div>
                                    </div>
                                </a>
                                <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Social Media Marketing</small></p>
                            </div>
 <?php } if(in_array("24", $Options)) {?>
                            <div class="col-4 col-md-2 mb-3">
                                <a href="view-products.php">
                                    <div class="avatar avatar-70 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="mob_icons/products.png" alt="">
                                          
                                        </div>
                                    </div>
                                </a>
                                <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Product</small></p>
                            </div>
 <?php } if(in_array("17", $Options)) {?>
                            <div class="col-4 col-md-2 mb-3">
                                <a href="product-specification.php">
                                    <div class="avatar avatar-70 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="mob_icons/inspection.png" alt="">
                                          
                                        </div>
                                    </div>
                                </a>
                                <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Product Specification</small></p>
                            </div>
<?php } if(in_array("18", $Options)) {?>
                            <div class="col-4 col-md-2 mb-3">
                                <a href="view-customers.php">
                                    <div class="avatar avatar-70 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="mob_icons/kyc.png" alt="">
                                          
                                        </div>
                                    </div>
                                </a>
                                <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Customers</small></p>
                            </div>
<?php } if(in_array("19", $Options)) {?>
                            <div class="col-4 col-md-2 mb-3">
                                <a href="view-manufacture.php">
                                    <div class="avatar avatar-70 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="mob_icons/manufacturer.png" alt="">
                                          
                                        </div>
                                    </div>
                                </a>
                                <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Manufacture</small></p>
                            </div>
<?php } if(in_array("20", $Options)) {?>
                             <div class="col-4 col-md-2 mb-3">
                                <a href="view-company.php">
                                    <div class="avatar avatar-70 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="mob_icons/organization.png" alt="">
                                          
                                        </div>
                                    </div>
                                </a>
                                <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Company</small></p>
                            </div>
<?php } if(in_array("21", $Options)) {?>
                             <div class="col-4 col-md-2 mb-3">
                                <a href="view-employee.php">
                                    <div class="avatar avatar-70 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="mob_icons/team.png" alt="">
                                          
                                        </div>
                                    </div>
                                </a>
                                <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Employee</small></p>
                            </div>
<?php } if(in_array("22", $Options)) {?>
                            <div class="col-4 col-md-2 mb-3">
                                <a href="view-dealer.php">
                                    <div class="avatar avatar-70 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="mob_icons/dealer.png" alt="">
                                          
                                        </div>
                                    </div>
                                </a>
                                <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Dealer</small></p>
                            </div>

<?php } if(in_array("23", $Options)) {?>
                            <div class="col-4 col-md-2 mb-3">
                                <a href="view-agency.php">
                                    <div class="avatar avatar-70 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="mob_icons/enterprise.png" alt="">
                                          
                                        </div>
                                    </div>
                                </a>
                                <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Agency</small></p>
                            </div>

<?php } if(in_array("55", $Options)) {?>
                            <div class="col-4 col-md-2 mb-3">
                                <a href="assign-customers-to-co-ordinator.php">
                                    <div class="avatar avatar-70 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="mob_icons/assign_customer.png" alt="">
                                          
                                        </div>
                                    </div>
                                </a>
                                <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Assign Customers To Co-ordinator</small></p>
                            </div>
<?php } if(in_array("27", $Options)) {?>
                            <div class="col-4 col-md-2 mb-3">
                                <a href="view-quotation.php">
                                    <div class="avatar avatar-70 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="mob_icons/PERFORMA_INVOICE.png" alt="">
                                          
                                        </div>
                                    </div>
                                </a>
                                <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Performa Invoice</small></p>
                            </div>
<?php } if(in_array("57", $Options)) {?>
                             <div class="col-4 col-md-2 mb-3">
                                <a href="view-bill-amount-status.php">
                                    <div class="avatar avatar-70 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="mob_icons/Bill_Amount_Status.png" alt="">
                                          
                                        </div>
                                    </div>
                                </a>
                                <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Bill Amount Status</small></p>
                            </div>
<?php } if(in_array("58", $Options)) {?>
                              <div class="col-4 col-md-2 mb-3">
                                <a href="assign-to-store-incharge.php">
                                    <div class="avatar avatar-70 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="mob_icons/storeincharge.jpg" alt="">
                                          
                                        </div>
                                    </div>
                                </a>
                                <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Assign To Store Incharge</small></p>
                            </div>
<?php } if(in_array("59", $Options)) {?>
                             <div class="col-4 col-md-2 mb-3">
                                <a href="approve-store-incharge.php">
                                    <div class="avatar avatar-70 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="mob_icons/approve.png" alt="">
                                          
                                        </div>
                                    </div>
                                </a>
                                <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Approve By Store Incharge</small></p>
                            </div>
<?php } if(in_array("25", $Options)) {?>
                            <div class="col-4 col-md-2 mb-3">
                                <a href="view-purchase-order.php">
                                    <div class="avatar avatar-70 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="mob_icons/purchase.jpg" alt="">
                                          
                                        </div>
                                    </div>
                                </a>
                                <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Purchase Order</small></p>
                            </div>
                            <?php } if(in_array("70", $Options)) {?>
                             <div class="col-4 col-md-2 mb-3">
                                <a href="view-distribute-item-store.php">
                                    <div class="avatar avatar-70 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="mob_icons/delivery-boy.jpg" alt="">
                                          
                                        </div>
                                    </div>
                                </a>
                                <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Assign Item To Store</small></p>
                            </div>
                            <?php } if(in_array("71", $Options)) {?>
                             <div class="col-4 col-md-2 mb-3">
                                <a href="view-distribute-item-store-executive.php">
                                    <div class="avatar avatar-70 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="mob_icons/delivery-boy.jpg" alt="">
                                          
                                        </div>
                                    </div>
                                </a>
                                <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Assign Items To Dispatch Officier</small></p>
                            </div>
<?php } if(in_array("60", $Options)) {?>
                             <div class="col-4 col-md-2 mb-3">
                                <a href="assign-to-dispatch-officer.php">
                                    <div class="avatar avatar-70 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="mob_icons/delivery-boy.jpg" alt="">
                                          
                                        </div>
                                    </div>
                                </a>
                                <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Assign Order To Dispatch Officer</small></p>
                            </div>
<?php } if(in_array("26", $Options)) {?>
                            <div class="col-4 col-md-2 mb-3">
                                <a href="view-sells.php">
                                    <div class="avatar avatar-70 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="mob_icons/fast-delivery.jpg" alt="">
                                          
                                        </div>
                                    </div>
                                </a>
                                <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Delivery Challan</small></p>
                            </div>
<?php } if(in_array("42", $Options)) {?>
                             <div class="col-4 col-md-2 mb-3">
                                <a href="delivery-customers.php">
                                    <div class="avatar avatar-70 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="mob_icons/delcust.jpg" alt="">
                                          
                                        </div>
                                    </div>
                                </a>
                                <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Delivery Customers</small></p>
                            </div>

                            <div class="col-4 col-md-2 mb-3">
                                <a href="pending-customers.php">
                                    <div class="avatar avatar-70 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="mob_icons/comp.jpg" alt="">
                                          
                                        </div>
                                    </div>
                                </a>
                                <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Pending Customers</small></p>
                            </div>

                            <div class="col-4 col-md-2 mb-3">
                                <a href="completed-customers.php">
                                    <div class="avatar avatar-70 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="mob_icons/cust.jpg" alt="">
                                          
                                        </div>
                                    </div>
                                </a>
                                <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Completed Customers</small></p>
                            </div>
  <?php } if(in_array("67", $Options)) {?>
                             <div class="col-4 col-md-2 mb-3">
                                <a href="rooftop-installation.php">
                                    <div class="avatar avatar-70 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="mob_icons/Rooftop_Installation.png" alt="">
                                          
                                        </div>
                                    </div>
                                </a>
                                <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Rooftop Installation</small></p>
                            </div>
<?php } if(in_array("68", $Options)) {?>
                            <div class="col-4 col-md-2 mb-3">
                                <a href="pump-installation.php">
                                    <div class="avatar avatar-70 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="mob_icons/pump.jpg" alt="">
                                          
                                        </div>
                                    </div>
                                </a>
                                <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Pump Installation</small></p>
                            </div>
<?php } if(in_array("28", $Options)) {?>
                            <div class="col-4 col-md-2 mb-3">
                                <a href="view-service-module.php">
                                    <div class="avatar avatar-70 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="mob_icons/1.jpg" alt="">
                                          
                                        </div>
                                    </div>
                                </a>
                                <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Service Complaint</small></p>
                            </div>
<?php }  if(in_array("69", $Options)) {?>
                              <div class="col-4 col-md-2 mb-3">
                                <a href="dealer-commission.php">
                                    <div class="avatar avatar-70 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="mob_icons/deler.jpg" alt="">
                                          
                                        </div>
                                    </div>
                                </a>
                                <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Dealer Commission</small></p>
                            </div>
<?php }  if(in_array("61", $Options)) {?>
                             <div class="col-4 col-md-2 mb-3">
                                <a href="view-warranty-registration.php">
                                    <div class="avatar avatar-70 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="mob_icons/3.jpg" alt="">
                                          
                                        </div>
                                    </div>
                                </a>
                                <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Warranty Registration</small></p>
                            </div>

                            <div class="col-4 col-md-2 mb-3">
                                <a href="warranty-customers.php">
                                    <div class="avatar avatar-70 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="mob_icons/user.jpg" alt="">
                                          
                                        </div>
                                    </div>
                                </a>
                                <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">Warranty Customers</small></p>
                            </div>

                            <div class="col-4 col-md-2 mb-3">
                                <a href="no-warranty-customers.php">
                                    <div class="avatar avatar-70 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="mob_icons/warrenny.jpg" alt="">
                                          
                                        </div>
                                    </div>
                                </a>
                                <p class="text-secondary"><small style="font-size: 12px;font-weight: 700;font-family: Sans-serif;text-transform: uppercase;color:#000;">No Warranty Customers</small></p>
                            </div>
<?php } ?>
                             <!--<div class="col-4 col-md-2 mb-3">
                                <a href="">
                                    <div class="avatar avatar-70 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="mob_icons/search.jpg" alt="">
                                          
                                        </div>
                                    </div>
                                </a>
                                <p class="text-secondary"><small style="font-size: 12px;
font-weight: 700;
font-family: Sans-serif;
text-transform: uppercase;
color:#000;">Change Password</small></p>
                            </div>-->

                             <div class="col-4 col-md-2 mb-3">
                                <a href="JavaScript:Void(0);" onclick="logout()">
                                    <div class="avatar avatar-70 mb-1 rounded">
                                        <div class="background">
                                           
                                            
                                            <img src="mob_icons/shutdown.png" alt="">
                                          
                                        </div>
                                    </div>
                                </a>
                                <p class="text-secondary"><small style="font-size: 12px;
font-weight: 700;
font-family: Sans-serif;
text-transform: uppercase;
color:#000;">Logout</small></p>
                            </div>

                            </div>
                               
            
    </main>

    <!-- footer-->
  <?php include_once 'footer.php'; ?>


<script src="dist/aos.js"></script>
    <script>
      AOS.init({
        easing: 'ease-in-out-sine'
      });
    </script>


    <!-- Required jquery and libraries -->
      <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- cookie js -->
    <script src="js/jquery.cookie.js"></script>

    <!-- Swiper slider  js-->
    <script src="vendor/swiper/js/swiper.min.js"></script>

    <!-- Customized jquery file  -->
    <script src="js/main.js"></script>
    <script src="js/color-scheme-demo.js"></script>

    <!-- PWA app service registration and works -->
    <script src="js/pwa-services.js"></script>

    <!-- page level custom script -->
    <script src="js/app.js"></script>

       <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
      <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
    <script>
        function logout(){
       Android.logout();
       window.location.href="logout.php";
  }
    </script>

</body>

</html>
