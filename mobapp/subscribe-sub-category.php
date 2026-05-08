<?php session_start();
require_once 'config.php';
$cat_id = $_GET['cat_id'];
$sql3 = "SELECT * FROM category WHERE id='$cat_id'";
$row3 = getRecord($sql3);
$PageName = $row3['Name'];
$Page = "Shop"; ?>
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
        <div class="main-container">
             <div class="container mb-4">
              
                <div class="card">
                    <div class="card-body text-center ">
                        <div class="card-header">
                                <h6><?php echo $row3['Name']; ?></h6>
                            </div>
                        <div class="row justify-content-equal no-gutters">
                             <?php 
                            $sql31 = "SELECT * FROM sub_category WHERE Status='1' AND CatId='$cat_id'";
                            $res31 = $conn->query($sql31);
                            $rncnt31 = mysqli_num_rows($res31);
                              if($rncnt31 > 0){
                            while($row31 = $res31->fetch_assoc()){
                    ?>
                            <div class="col-4 col-md-2 mb-3">
                               <a href="sub-product-lists.php?cat_id=<?php echo $cat_id;?>&subid=<?php echo $row31['id']; ?>"> <div class="avatar avatar-60 mb-1 rounded">
                                    <div class="background">
                                        <?php if($row31["Photo"] == '') {?>
                  <img src="no_image.jpg" style="width: 60px;height: 60px;"> 
                 <?php } else if(file_exists('../uploads/'.$row31["Photo"])){?>
                 <img src="../uploads/<?php echo $row31["Photo"];?>" alt="" style="width: 60px;height: 60px;">
                  <?php }  else{?>
                 <img src="no_image.jpg" style="width: 60px;height: 60px;"> 
             <?php } ?>
                                    </div>
                                </div></a>
                                <p class="text-secondary"><small><?php echo $row31['Name']; ?></small></p>
                            </div>
                             <?php }} ?>
                           
                        </div>
                        
                    </div>
                </div>
                <br> 
               

            </div>
        </div>
    </main>
 <?php include_once 'footer.php';?>

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
