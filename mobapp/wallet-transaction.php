<?php session_start();
require_once 'config.php';
$user_id = $_SESSION['User']['id'];
$PageName = "Wallet";
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

        <div class="main-container">
            <div class="container">
                  <div class="alert alert-success">
                   Wallet Amount : <b>&#8377;<?php echo number_format($mybalance,2);?></b>.
                   
                </div>
                <div class="card">
                    <div class="card-body px-0">
                        <ul class="list-group list-group-flush">
                            <?php 
                             $sql = "SELECT * FROM wallet WHERE UserId='$user_id'";
                            $row = getList($sql);
                            foreach($row as $result){
                            ?>
                            <li class="list-group-item">
                                <div class="row align-items-center">
                                    
                                    <div class="col align-self-center pr-0">
                                        <h6 class="font-weight-normal mb-1"><?php echo $result['Narration'];?></h6>
                                        <p class="small text-secondary"><?php echo $result['CreatedDate'].", ".$result['CreatedTime'];?></p>
                                    </div>
                                    <div class="col-auto">
                                        <?php if($result['Status'] == 'Cr'){?>
                                        <h6 class="text-success">&#8377;<?php echo number_format($result['Amount'],2);?></h6>
                                        <?php } else {?>
                                        <h6 class="text-danger">&#8377;<?php echo number_format($result['Amount'],2);?></h6>
                                        <?php } ?>
                                    </div>
                                </div>
                            </li>
                            <?php } ?>
                             
                        </ul>
                    </div>
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
