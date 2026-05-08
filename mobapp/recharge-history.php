<?php session_start();
require_once 'config.php';
$user_id = $_SESSION['User']['id'];
$PageName = "Recharge History";
$Page = "Recharge";
$WallMsg = "NotShow"; ?>
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
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <?php include_once 'back-header.php'; ?>

        <!-- page content start -->
<?php 
    $rechid = $_GET['rechid'];
    $sql22 = "SELECT rd.*,tro.Name FROM recharge_details rd LEFT JOIN tbl_rec_operator tro ON rd.OperatorId=tro.Code WHERE rd.id='$rechid'";
    $row22 = getRecord($sql22);
 ?>
        <div class="main-container" style="background-color: #fbfbfb;">
            <div class="container mb-4">
                <span style="text-align: center;">
                <?php if($row22['Status'] == 'Success') {?>
                  <div class="alert alert-success"><b>Recharge <?php echo $row22['Status']; ?></b><br><?php echo $row22['Response']; ?></div>
              <?php } else if($row22['Status'] == 'Pending') {?>
                <div class="alert alert-warning"><b>Recharge <?php echo $row22['Status']; ?></b><br><?php echo $row22['Response']; ?></div>
                <?php } else if($row22['Status'] == 'Failed') {?>
                    <div class="alert alert-danger"><b>Recharge <?php echo $row22['Status']; ?></b><br><?php echo $row22['Response']; ?></div>
                <?php } ?></span>
                <div class="row my-3 h6 font-weight-normal">
                    <div class="col">Order Id</div>
                    <div class="col text-right text-mute" id="subtotal"><?php echo $row22['OrderId']; ?></div>
                </div>
                <div class="row my-3 h6 font-weight-normal">
                    <div class="col">Operator Id</div>
                    <div class="col text-right text-mute" id="shipping_price"><?php echo $row22['Name']; ?></div>
                </div>
                 <div class="row my-3 h6 font-weight-normal">
                    <div class="col">Mobile No</div>
                    <div class="col text-right text-mute" id="shipping_price"><?php echo $row22['MobileNo']; ?></div>
                </div>
                 <div class="row my-3 h6 font-weight-normal">
                    <div class="col">Amount</div>
                    <div class="col text-right text-mute" id="shipping_price">&#8377;<?php echo number_format($row22['Amount'],2); ?></div>
                </div>
                 <!-- <div class="row my-3 h6 font-weight-normal">
                    <div class="col">Status</div>
                    <div class="col text-right text-mute" id="shipping_price"><?php echo $row22['Status']; ?></div>
                </div>
                 <div class="row my-3 h6 font-weight-normal">
                    <div class="col">Response Message</div>
                    <div class="col text-right text-mute" id="shipping_price"><?php echo $row22['Response']; ?></div>
                </div> -->

                <div class="row my-3 h6 font-weight-normal">
                    <div class="col">Recharge Id</div>
                    <div class="col text-right text-mute" id="shipping_price"><?php echo $row22['RechId']; ?></div>
                </div>

                 <div class="row my-3 h6 font-weight-normal">
                    <div class="col">Transaction Id</div>
                    <div class="col text-right text-mute" id="shipping_price"><?php echo $row22['Transid']; ?></div>
                </div>

                <div class="row my-3 h6 font-weight-normal">
                    <div class="col">Your Recharge Id</div>
                    <div class="col text-right text-mute" id="shipping_price"><?php echo $row22['Yourrchid']; ?></div>
                </div>

                <div class="row my-3 h6 font-weight-normal">
                    <div class="col">Recharge Date</div>
                    <div class="col text-right text-mute" id="shipping_price"><?php echo $row22['RechargeDate']; ?></div>
                </div>
                
                <div class="container">
                <a href="recharge-category.php" class="btn btn-default btn-block rounded">Rechage Again</a>
            </div>
                 </div>


          
        </div>
    </main>


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

   
</body>

</html>
