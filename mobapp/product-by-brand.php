<?php session_start();
require_once 'config.php';
$PageName = "Brands"; 
$Page = "Home";?>
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
    <!-- screen loader -->
   



    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <!-- Fixed navbar -->
        <?php include_once 'back-header.php'; ?>
        <!-- page content start -->
        <div class="main-container bg-default-light ">
             <div class="container mb-2">
                <div class="row">
                    <div class="col  mb-2" align="center">
                        <h5 class="subtitle" style="font-size:15px;">Shop by Brand</h5>
                    </div>
                </div>
             <div class="">
                    <div class=" text-center ">
                        <div class="row">
                              <?php 
                          $sql2 = "SELECT * FROM brands WHERE Status='1' ORDER BY id asc";
                          $res2 = $conn->query($sql2);
                          while($row2 = $res2->fetch_assoc()){
                            $CatId = $row2['id'];
                        ?>
                             <div class="col-4 col-md-4 col-lg-3" data-aos="zoom-in">
                        <div class=" border-0 mb-2 overflow-hidden">
                            <div class="card-body position-relative avatar avatar-90 mb-1 rounded" >
                              
                                <a href="product-lists.php?brand_id=<?php echo $row2['id']; ?>" class="background">
                                     <?php if($row2["Photo"] == '') {?>
                  <img src="no_image.jpg" >
                 <?php } else if(file_exists('../uploads/'.$row2["Photo"])){?>
                 <img src="../uploads/<?php echo $row2["Photo"];?>" alt="">
                  <?php }  else{?>
                 <img src="no_image.jpg"> 
             <?php } ?>
                                   
                                </a>
                            </div>
                          <p class="text-secondary"><small><?php echo $row2["Name"];?></small></p>
                        </div>
                        
                    </div>
                    <?php } ?>
                        </div>
                       
                        
                    </div>
                </div>
            </div>
        </div>
    </main>
 <?php include_once 'footer.php'; ?>

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
