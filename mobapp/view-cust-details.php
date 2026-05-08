<?php session_start();
require_once 'config.php';
$user_id = $_SESSION['User']['id'];
$PageName = "Customer Details";
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
    $custid = $_GET['id'];
    $sql22 = "SELECT * FROM customers WHERE id='$custid'";
    $row22 = getRecord($sql22);
    $Roll = $row22['Roll'];
    if($Roll == 4){
        $AccName = "Doctor";
    }
    if($Roll == 5){
        $AccName = "Optician";
    }
    if($Roll == 6){
        $AccName = "Wholesaler";
    }
    if($Roll == 7){
        $AccName = "Customer";
    }
    if($Roll == 8){
        $AccName = "Retailer";
    }
 ?>
        <div class="main-container" style="background-color: #fbfbfb;">
            <div class="container mb-4">
                <span style="text-align: center;">
                <?php if($row22['Status'] == '1') {?>
                  <div class="alert alert-success"><b>Status : Active</b></div>
              <?php } else if($row22['Status'] == '0') {?>
                <div class="alert alert-danger"><b>Status : Pending</b></div>
                <?php } ?></span>
                <div class="row my-3 h6 font-weight-normal">
                    <div class="col">Account Type</div>
                    <div class="col text-right text-mute" id="subtotal"><?php echo $AccName; ?></div>
                </div>
                <div class="row my-3 h6 font-weight-normal">
                    <div class="col">Customer Name</div>
                    <div class="col text-right text-mute" id="subtotal"><?php echo $row22['Fname']." ".$row22['Lname']; ?></div>
                </div>
                <div class="row my-3 h6 font-weight-normal">
                    <div class="col">Contact No</div>
                    <div class="col text-right text-mute" id="shipping_price"><?php echo $row22['Phone']; ?></div>
                </div>
                 <div class="row my-3 h6 font-weight-normal">
                    <div class="col">Whatsapp No</div>
                    <div class="col text-right text-mute" id="shipping_price"><?php echo $row22['Phone2']; ?></div>
                </div>
                 <div class="row my-3 h6 font-weight-normal">
                    <div class="col">Email Id</div>
                    <div class="col text-right text-mute" id="shipping_price"><?php echo $row22['EmailId']; ?></div>
                </div>
                

                <div class="row my-3 h6 font-weight-normal">
                    <div class="col">Address</div>
                    <div class="col text-right text-mute" id="shipping_price"><?php echo $row22['Address']; ?></div>
                </div>

                 <div class="row my-3 h6 font-weight-normal">
                    <div class="col">Pincode</div>
                    <div class="col text-right text-mute" id="shipping_price"><?php echo $row22['Pincode']; ?></div>
                </div>

                <div class="row my-3 h6 font-weight-normal">
                    <div class="col">Registration Date</div>
                    <div class="col text-right text-mute" id="shipping_price"><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row22['CreatedDate'])))?></div>
                </div>

                
                <div class="container">
                <a href="view-customers.php" class="btn btn-default btn-block rounded">Back To Home</a>
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
