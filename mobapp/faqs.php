<?php session_start();
require_once 'config.php';
$PageName = "FAQ's";
$UserId = $_SESSION['User']['id'];
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

<body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <!-- Fixed navbar -->
        <?php include 'back-header.php'; ?>
       
        <div class="main-container">
            <div class="container">
                <div class="card mb-4">
                   <div class="accordion" id="accordionExample">
                    <?php 
                    $i=1;
                    $sql = "SELECT * FROM tbl_faqs WHERE Status=1";
                    $row = getList($sql);
                    foreach($row as $result){
                     ?>
                    <div class="card">
                        <div class="card-header" id="headingOne<?php echo $result['id'];?>">
                            <h2 class="mb-0">
                                <button class="btn btn-link btn-block text-left px-0" type="button" data-toggle="collapse" data-target="#collapseOne<?php echo $result['id'];?>" <?php if($i<2){?>aria-expanded="true"<?php } else{?>aria-expanded="false" <?php } ?> aria-controls="collapseOne<?php echo $result['id'];?>">
                                   <?php echo $result['Question'];?>
                                </button>
                            </h2>
                        </div>

                        <div id="collapseOne<?php echo $result['id'];?>" class="collapse <?php if($i<2){?>show<?php } else{} ?> " aria-labelledby="headingOne<?php echo $result['id'];?>" data-parent="#accordionExample">
                            <div class="card-body">
                                <p style="text-align:justify;"><?php echo $result['Answer'];?></p>
                            </div>
                        </div>
                    </div>
                <?php $i++;} ?>
                    
                    
                </div>
                   
                </div>
                
            </div>
        </div>
    </main>

    <!-- footer-->
    

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
