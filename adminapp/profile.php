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
     



    <!-- Begin page content -->
    <main class="flex-shrink-0 main has-footer">
        <!-- Fixed navbar -->
         <?php include_once 'back-header.php'; ?> 
    
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
               
            </div>

<div class="container">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">My Account</h6>
                    </div>
                    <div class="card-body px-0 pt-0">
                        <div class="list-group list-group-flush border-top border-color">
                           
                        
                           
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
