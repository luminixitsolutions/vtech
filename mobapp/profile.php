<?php session_start();
require_once 'config.php';
require_once 'auth.php';
$PageName = "Profile";
$UserId = $_SESSION['User']['id'];
$user_id = $UserId;
$sql11 = "SELECT * FROM tbl_users WHERE id='$UserId'";
$row11 = getRecord($sql11);
$Name = $row11['Fname']." ".$row11['Lname'];
$Phone = $row11['Phone'];
$EmailId = $row11['EmailId'];
$Roll = $row11['Roll'];
//$mycity = $row['CityId'];
$ExpDate = date("d/m/Y", strtotime(str_replace('-', '/',$row11['Validity'])));

$sql88 = "SELECT SUM(creditAmt) As Credit,SUM(debitAmt) As Debit FROM (SELECT (case when Status='Cr' then sum(Amount) else 0 end) as creditAmt,(case when Status='Dr' then sum(Amount) else 0 end) as debitAmt FROM wallet WHERE UserId='$UserId' GROUP BY Status) as a";
    $row88 = getRecord($sql88);
    $Wallet = $row88['Credit'] - $row88['Debit'];
    
    //echo $_GET['city_id'];
    if($_GET['city_id']==0 || $_GET['city_id']==''){
    $city_id = $row11['CityId'];  
    
}
else{
  $city_id = $_REQUEST['city_id'];
}
//echo $city_id;
/* $sql = "UPDATE tbl_users SET Location='$city_id' WHERE id='$UserId'";
    $conn->query($sql);*/
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
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay" data-page="homepage">
    <!-- screen loader -->
    


    <!-- menu main -->
     <?php include_once 'sidebar.php'; ?>



    <!-- Begin page content -->
    <main class="flex-shrink-0 main has-footer">
        <!-- Fixed navbar -->
        <?php include_once 'top_header.php'; ?>
    
        <div class="main-container">
           <div class="container-fluid px-0" style="padding-left: 10px !important;padding-right: 10px !important;">
            <div class="card overflow-hidden">
                <div class="card-body p-0 h-150">
                    <div class="background">
                        <img src="img/image10.jpg" alt="" style="display: none;">
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid top-70 text-center mb-4">
            <div class="avatar avatar-140 rounded-circle mx-auto shadow">
                <div class="background">
                    
                 <img src="user_icon.jpg" alt="" style="width: 140px;height: 140px;">
                
                    
                </div>
            </div>
        </div>

       <div class="container mb-4 text-center text-black">
            <h6 class="mb-1"><?php echo $Name; ?></h6>
             <!-- <h6 class="mb-1">&#8377;<?php echo $Wallet;?></h6> -->
           
             <span><i class="fa fa-map-marker"></i> <?php echo $row11['Address']; ?></span>
            <p class="mb-1"><i class="fa fa-envelope"></i> <?php echo $row11['EmailId']; ?></p>
            <p><i class="fa fa-phone"></i> <?php echo $row11['Phone']; ?></p> 
        </div>
            
           <!--  <div class="container mb-4 text-center">
                <div class="card bg-default-secondary shadow-default">
                    <div class="card-body">
                       
                        <div class="swiper-container addsendcarousel text-center">
                            <div class="swiper-wrapper mb-4">
                                <a href="add-money.php" class="swiper-slide text-white">
                                    <div class="icon icon-50 rounded-circle mb-2 bg-white-light"><span class="material-icons">add</span></div>
                                    <p><small>Add Fund</small></p>
                                </a>
                                <a href="send_money.html" class="swiper-slide text-white">
                                    <div class="icon icon-50 rounded-circle mb-2 bg-white-light"><span class="material-icons">call_made</span></div>
                                    <p><small>Send</small></p>
                                </a>
                                <a href="withdraw.html" class="swiper-slide text-white">
                                    <div class="icon icon-50 rounded-circle mb-2 bg-white-light"><span class="material-icons">call_received</span></div>
                                    <p><small>Withdraw</small></p>
                                </a>
                                <a href="change_currency.html" class="swiper-slide text-white">
                                    <div class="icon icon-50 rounded-circle mb-2 bg-white-light"><span class="material-icons">swap_horiz</span></div>
                                    <p><small>Change</small></p>
                                </a>
                                <a href="transactions.html" class="swiper-slide text-white">
                                    <div class="icon icon-50 rounded-circle mb-2 bg-white-light"><span class="material-icons">class</span></div>
                                    <p><small>Passbuk</small></p>
                                </a>
                                <a href="#" class="swiper-slide text-white">
                                    <div class="icon icon-50 rounded-circle mb-2 bg-white-light"><span class="material-icons">receipt</span></div>
                                    <p><small>Receipt</small></p>
                                </a>
                            </div>
                           
                            <div class="swiper-pagination"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container mb-4">
                <div class="card">
                    <div class="card-body text-center ">
                        <div class="row justify-content-equal no-gutters">
                            <div class="col-4 col-md-2 mb-3">
                                <a href="#" class="icon icon-50 rounded-circle mb-1 bg-default-light text-default"><span class="material-icons">qr_code_2</span></a>
                                <p class="text-secondary"><small>Pay</small></p>
                            </div>
                            <div class="col-4 col-md-2 mb-3">
                                <a href="#" class="icon icon-50 rounded-circle mb-1 bg-default-light text-default"><span class="material-icons">swap_horiz</span></a>
                                <p class="text-secondary"><small>Transfer</small></p>
                            </div>
                            <div class="col-4 col-md-2 mb-3">
                                <a href="recharge-category.php" class="icon icon-50 rounded-circle mb-1 bg-default-light text-default"><span class="material-icons">sim_card</span></a>
                                <p class="text-secondary"><small>Reacharge</small></p>
                            </div>
                            <div class="col-4 col-md-2 mb-3">
                                <a href="#" class="icon icon-50 rounded-circle mb-1 bg-default-light text-default"><span class="material-icons">account_circle</span></a>
                                <p class="text-secondary"><small>Send</small></p>
                            </div>
                            <div class="col-4 col-md-2 mb-3">
                                <div class="icon icon-50 rounded-circle mb-1 bg-default-light text-default"><span class="material-icons">receipt</span></div>
                                <p class="text-secondary"><small>Bill</small></p>
                            </div>
                            <div class="col-4 col-md-2 mb-3">
                                <div class="icon icon-50 rounded-circle mb-1 bg-default-light text-default"><span class="material-icons">wb_incandescent</span></div>
                                <p class="text-secondary"><small>Electricity</small></p>
                            </div>
                        </div>
                        <button class="btn btn-sm btn-outline-secondary rounded" id="more-expand-btn">Show more <span class="ml-2 small material-icons">expand_more</span></button>
                        <div class="row justify-content-equal no-gutters" id="more-expand">
                            <div class="col-4 col-md-2">
                                <div class="icon icon-50 rounded-circle mb-1 bg-default-light text-default"><span class="material-icons">beach_access</span></div>
                                <p class="text-secondary"><small>Insurance</small></p>
                            </div>
                            <div class="col-4 col-md-2">
                                <div class="icon icon-50 rounded-circle mb-1 bg-default-light text-default"><span class="material-icons">drive_eta</span></div>
                                <p class="text-secondary"><small>Car</small></p>
                            </div>
                            <div class="col-4 col-md-2">
                                <div class="icon icon-50 rounded-circle mb-1 bg-default-light text-default"><span class="material-icons">flight</span></div>
                                <p class="text-secondary"><small>Flight</small></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
            <div class="container mb-4">
                <!-- <div class="row mb-4">
                    <div class="col-6">
                        <a href="grocery-cash-transaction.php"><button class="btn btn-outline-default px-2 btn-block rounded" style="font-size: 13px;">Grocery Cash<br>
                            <?php echo $mybalancepnts; ?> Points</button></a>
                    </div>
                    <div class="col-6">
                        <a href="wallet-transaction.php"><button class="btn btn-outline-default px-2 btn-block rounded" style="font-size: 13px;">Wallet Cash<br>
                        &#8377;<?php echo $Wallet;?></button></a>
                    </div>
                </div>

                <div class="row mb-4">
                  <a href="add-money.php" class="btn btn-default mb-2 mx-auto rounded" style="background-color: #e36012;"><i class="material-icons vm text-template">credit_card</i> Add Money In Wallet</a>
                </div> -->
              <!-- <div class="row mb-4">
                    <div class="col-6">
                        <a href="edit-profile.php"><button class="btn btn-outline-default px-2 btn-block rounded" style="font-size: 13px;"><span class="material-icons">account_circle</span> Edit Profile</button></a>
                    </div>
                    <div class="col-6">
                        <a href="change-password.php"><button class="btn btn-outline-default px-2 btn-block rounded" style="font-size: 13px;"> <span class="material-icons">lock_open</span> Change Password</button></a>
                    </div>
                </div> -->
                <?php if($Member == 1){?>
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <h6 class="mb-1" align="center"><span style="color:#ff3399;">Current Plan:</span> <br>Started On <?php echo date("d M, Y", strtotime(str_replace('-', '/',$PkgDate)));?><br>Valid Upto <?php echo date("d M, Y", strtotime(str_replace('-', '/',$Validity)));?></h6>
                                        <h6 class="mb-1"><?php echo $RemainDays;?> left <span style="font-weight: 300;">in Membership</span></h6> 

                                    </div>
                                </div>
                                <div class="progress h-5 mt-3">
                                    <div class="progress-bar bg-default" role="progressbar" style="width:100%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            </div>

<div class="container">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">My Account</h6>
                    </div>
                    <div class="card-body px-0 pt-0">
                        <div class="list-group list-group-flush border-top border-color">
                            <a href="my-orders.php" class="list-group-item list-group-item-action border-color">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="avatar avatar-50 bg-danger-light text-danger rounded">
                                            <span class="material-icons">list_alt</span>
                                        </div>
                                    </div>
                                    <div class="col align-self-center pl-0">
                                        <h6 class="mb-1">My  Orders</h6>
                                        <!-- <p class="text-secondary">Choose preffered language</p> -->
                                        <p class="text-secondary"> Order list</p>
                                    </div>
                                </div>
                            </a>

                          
                            
                          <?php if($Roll == 9){?>
                          <a href="dealer/profile.php" class="list-group-item list-group-item-action border-color">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="avatar avatar-50 bg-danger-light text-danger rounded">
                                            <span class="material-icons">account_circle</span>
                                        </div>
                                    </div>
                                    <div class="col align-self-center pl-0">
                                        <h6 class="mb-1">Account Details</h6>
                                       <p class="text-secondary">Edit Account Details</p>
                                    </div>
                                </div>
                            </a>

                             <a href="dealer/view-my-customers.php" class="list-group-item list-group-item-action border-color">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="avatar avatar-50 bg-danger-light text-danger rounded">
                                            <span class="material-icons">list_alt</span>
                                        </div>
                                    </div>
                                    <div class="col align-self-center pl-0">
                                        <h6 class="mb-1">My Customers</h6>
                                        <!-- <p class="text-secondary">Choose preffered language</p> -->
                                        <p class="text-secondary"> View Customers</p>
                                    </div>
                                </div>
                            </a>

                           <!-- <a href="dealer/completed-customers.php" class="list-group-item list-group-item-action border-color">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="avatar avatar-50 bg-danger-light text-danger rounded">
                                            <span class="material-icons">list_alt</span>
                                        </div>
                                    </div>
                                    <div class="col align-self-center pl-0">
                                        <h6 class="mb-1">Complete Customers</h6>
                                       
                                        <p class="text-secondary"> Complete Customers</p>
                                    </div>
                                </div>
                            </a>
                            
                            <a href="dealer/my-commission-balance.php" class="list-group-item list-group-item-action border-color">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="avatar avatar-50 bg-danger-light text-danger rounded">
                                            <span class="material-icons">list_alt</span>
                                        </div>
                                    </div>
                                    <div class="col align-self-center pl-0">
                                        <h6 class="mb-1">My Commission</h6>
                                       
                                        <p class="text-secondary"> My Commission Balance</p>
                                    </div>
                                </div>
                            </a>
                            
                            <a href="dealer/my-commission.php" class="list-group-item list-group-item-action border-color">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="avatar avatar-50 bg-danger-light text-danger rounded">
                                            <span class="material-icons">list_alt</span>
                                        </div>
                                    </div>
                                    <div class="col align-self-center pl-0">
                                        <h6 class="mb-1">My Commission</h6>
                                      
                                        <p class="text-secondary"> My Commission</p>
                                    </div>
                                </div>
                            </a>-->
                            
                            <a href="dealer/dealer-cust-details.php" class="list-group-item list-group-item-action border-color">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="avatar avatar-50 bg-danger-light text-danger rounded">
                                            <span class="material-icons">list_alt</span>
                                        </div>
                                    </div>
                                    <div class="col align-self-center pl-0">
                                        <h6 class="mb-1">My History</h6>
                                       
                                        <p class="text-secondary"> My History</p>
                                    </div>
                                </div>
                            </a>
                            
                             <!-- <a href="dealer/view-customers.php" class="list-group-item list-group-item-action border-color">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="avatar avatar-50 bg-danger-light text-danger rounded">
                                            <span class="material-icons">list_alt</span>
                                        </div>
                                    </div>
                                    <div class="col align-self-center pl-0">
                                        <h6 class="mb-1">Customers</h6>
                                      
                                        <p class="text-secondary"> View Customers, New Customer</p>
                                    </div>
                                </div>
                            </a> -->
                            
                            <!-- <a href="dealer/view-service-module.php" class="list-group-item list-group-item-action border-color">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="avatar avatar-50 bg-danger-light text-danger rounded">
                                            <span class="material-icons">list_alt</span>
                                        </div>
                                    </div>
                                    <div class="col align-self-center pl-0">
                                        <h6 class="mb-1">Service Complaint</h6>
                                      
                                        <p class="text-secondary"> View Service Complaint, New Service Complaint</p>
                                    </div>
                                </div>
                            </a>
                            
                             <a href="dealer/view-insurance-claim.php" class="list-group-item list-group-item-action border-color">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="avatar avatar-50 bg-danger-light text-danger rounded">
                                            <span class="material-icons">list_alt</span>
                                        </div>
                                    </div>
                                    <div class="col align-self-center pl-0">
                                        <h6 class="mb-1">Insurance Claim</h6>
                                      
                                        <p class="text-secondary"> View Insurance Claim, New Insurance Claim</p>
                                    </div>
                                </div>
                            </a> -->
                            
                          <?php } if($Roll == 3){?>
                          <a href="manufacture/profile.php" class="list-group-item list-group-item-action border-color">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="avatar avatar-50 bg-danger-light text-danger rounded">
                                            <span class="material-icons">account_circle</span>
                                        </div>
                                    </div>
                                    <div class="col align-self-center pl-0">
                                        <h6 class="mb-1">Account Details</h6>
                                       <p class="text-secondary">Edit Account Details</p>
                                    </div>
                                </div>
                            </a>
                            
                             <a href="manufacture/view-purchase-order.php" class="list-group-item list-group-item-action border-color">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="avatar avatar-50 bg-danger-light text-danger rounded">
                                            <span class="material-icons">list_alt</span>
                                        </div>
                                    </div>
                                    <div class="col align-self-center pl-0">
                                        <h6 class="mb-1">Purchase Order</h6>
                                        <!-- <p class="text-secondary">Choose preffered language</p> -->
                                        <p class="text-secondary"> Purchase Order list, Add PO</p>
                                    </div>
                                </div>
                            </a>
                            
                          <?php } if($Roll==5){?>
                           <a href="my-solar-status.php" class="list-group-item list-group-item-action border-color">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="avatar avatar-50 bg-danger-light text-danger rounded">
                                            <span class="material-icons">list_alt</span>
                                        </div>
                                    </div>
                                    <div class="col align-self-center pl-0">
                                        <h6 class="mb-1">My  Solar Status</h6>
                                        <!-- <p class="text-secondary">Choose preffered language</p> -->
                                        <p class="text-secondary"> Check Status</p>
                                    </div>
                                </div>
                            </a>
                            
                            <?php } else { ?>
                          
                          
                            
                            <a href="edit-profile.php" class="list-group-item list-group-item-action border-color">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="avatar avatar-50 bg-danger-light text-danger rounded">
                                            <span class="material-icons">account_circle</span>
                                        </div>
                                    </div>
                                    <div class="col align-self-center pl-0">
                                        <h6 class="mb-1">Account Details</h6>
                                       <p class="text-secondary">Edit Account Details</p>
                                    </div>
                                </div>
                            </a>
<?php } ?>
                             <a href="my-address.php?page=profile" class="list-group-item list-group-item-action border-color">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="avatar avatar-50 bg-danger-light text-danger rounded">
                                            <span class="material-icons">location_city</span>
                                        </div>
                                    </div>
                                    <div class="col align-self-center pl-0">
                                        <h6 class="mb-1">My Address</h6>
                                        <p class="text-secondary">Add, update, delete address</p>
                                    </div>
                                </div>
                            </a>

                          <!-- <a href="#" class="list-group-item list-group-item-action border-color">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="avatar avatar-50 bg-default-light text-default rounded">
                                            <span class="material-icons">account_circle</span>
                                        </div>
                                    </div>
                                    <div class="col align-self-center pl-0">
                                        <h6 class="mb-1">My Wishlist</h6>
                                       
                                    </div>
                                </div>
                            </a>

                             

                             <a href="#" class="list-group-item list-group-item-action border-color">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="avatar avatar-50 bg-default-light text-default rounded">
                                            <span class="material-icons">account_circle</span>
                                        </div>
                                    </div>
                                    <div class="col align-self-center pl-0">
                                        <h6 class="mb-1">My Wallet</h6>
                                       
                                    </div>
                                </div>
                            </a>-->

                             

                             
                             <a href="change-password.php" class="list-group-item list-group-item-action border-color">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="avatar avatar-50 bg-danger-light text-danger rounded">
                                            <span class="material-icons">lock_open</span>
                                        </div>
                                    </div>
                                    <div class="col align-self-center pl-0">
                                        <h6 class="mb-1">Change Password</h6>
                                        <p class="text-secondary">Change Password</p>
                                    </div>
                                </div>
                            </a>

                            <a href="JavaScript:Void(0);" onclick="logout()" class="list-group-item list-group-item-action border-color">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="avatar avatar-50 bg-danger-light text-danger rounded">
                                            <span class="material-icons">power_settings_new</span>
                                        </div>
                                    </div>
                                    <div class="col align-self-center pl-0">
                                        <h6 class="mb-1">Logout</h6>
                                        <p class="text-secondary">Logout from the application</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div><br>
            <div class="container mb-4">

                   
                <div class="card mb-3">
                     <div class="card-header border-bottom">
                        <h6 class="mb-0">My Regular Orders</h6>
                    </div>
                     <?php 
                                     $FromDate2 = $_POST['FromDate'];
                                     $ToDate2 = $_POST['ToDate'];
                                     $FromDate = date("Y-m-d", strtotime(str_replace('/', '-',$_POST['FromDate'])));
                                     $ToDate = date("Y-m-d", strtotime(str_replace('/', '-',$_POST['ToDate'])));
                                    
                                       $sql2 = "SELECT ord.*,os.Name As OrderStatus,pm.Name As Payment_Method FROM orders ord 
                                           LEFT JOIN payment_method pm ON pm.id=ord.PaymentMethod 
                                           LEFT JOIN order_status os ON os.id=ord.OrderProcess
                                           WHERE ord.UserId='$user_id' AND ord.Status=1 AND ord.Type='Cart' ORDER BY ord.srno DESC LIMIT 5"; 
                                     
                                    //echo $sql2;
                                        $res2 = $conn->query($sql2);
                                        $row_cnt = mysqli_num_rows($res2);
                                        if($row_cnt > 0){
                                        while($row = $res2->fetch_assoc()){
                                        if($row['OrderProcess'] == 1){
                                            $OrderStatus = "<span style='color:green;'>Order Delivered</span>";
                                        }
                                        else if($row['OrderProcess'] == 2){
                                            $OrderStatus = "<span style='color:orange;'>In Progress</span>";
                                        }
                                        else if($row['OrderProcess'] == 3){
                                            $OrderStatus = "<span style='color:red;'>Order Cancel</span>";
                                        }
                                        else if($row['OrderProcess'] == 4){
                                           $OrderStatus = "<span style='color:#09dae4;'>Order Confirmed</span>";
                                        }
                                        else if($row['OrderProcess'] == 5){
                                            $OrderStatus = "<span style='color:blue;'>Order Dispatch</span>";
                                        }
                                        else{
                                            $OrderStatus = "";
                                        }
                                        $Total_Order = $row["OrderTotal"]+$row["ShippingCharge"]-$row["Promoprice"];
                                     ?>
                    <div class="card-body position-relative">
                        <div class="row mb-2">
                            <div class="col">
                                <p class="text-secondary small">Order On : <?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['OrderDate'])))." ".$row['OrderTime'];?></p>
                            </div>
                            <div class="col-auto">
                                <p class="text-success"><?php echo $OrderStatus; ?></p>
                            </div>
                        </div>
                        <div class="media">                            
                            <div class="media-body">
                                <h6 class="mb-1 text-default"><a href="my-orders-details.php?oid=<?php echo $row['id'];?>"><?php echo $row['OrderNo'];?></a></h6>
                                <h6 class="mb-1">&#8377;<?php echo number_format($Total_Order,2); ?></h6>
                            </div>
                            <a href="my-orders-details.php?oid=<?php echo $row['id'];?>" style="padding-top: 10px;"><button class="btn btn-sm btn-default rounded">View Details</button></a>                          
                        </div>
                    </div>
                    <hr >
                <?php }} ?>
                    
                </div>
             
                
            </div>
            
            
            
        </div>
    </main>

    <?php include_once 'footer.php';?>


    <!-- color settings style switcher -->
    



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


    <!-- page level custom script -->
    <script src="js/app.js"></script>
    <script>
          function logout(){
       Android.logout();
       window.location.href="logout.php";
  }
      </script>
</body>

</html>
