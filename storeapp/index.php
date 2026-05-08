<?php 
echo "<script>window.location.href='login.php';</script>";
exit();
session_start();
$sessionid = session_id();
require_once 'config.php';
$PageName = "Choose Location";
$uid = $_REQUEST['uid'];
$sql11 = "SELECT * FROM customers WHERE id='$uid'";
$rncnt11 = getRow($sql11);
if($rncnt11 > 0){
    $row = getRecord($sql11);
    $_SESSION['User'] = $row;
} 

if($_REQUEST['val'] != ''){}
else{
if(isset($_SESSION['User'])){
    echo "<script>window.location.href='home.php';</script>";
}
}

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
    <link rel="stylesheet" href="dist/aos.css" />
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay" data-page="shop">
    <?php //include_once 'sidebar.php'; ?>

    <!-- Begin page content -->
    <main class="flex-shrink-0 main has-footer">
        <!-- Fixed navbar -->
       <?php //include_once 'back-header.php'; ?> 
 <header class="header">
            <div class="row">
                <div class="col-auto px-0">
                    <button class="btn btn-40 btn-link back-btn" type="button">
                        <span class="material-icons">keyboard_arrow_left</span>
                    </button>
                </div>
                <div class="text-left  align-self-center">
                    <a class="navbar-brand" href="#">
                        <h5 class="mb-0"><?php echo $PageName; ?></h5>
                    </a>
                      
                </div>
              
            </div>
        </header>
        <!-- page content start -->
<!-- page content start -->
   

        <div class="main-container  text-center" style="background-color:#fff;">

            
            
            
             <div class="container mb-2">
                <div class="row">
                    <div class="col  mb-2">
                        <h5 class="subtitle" style="font-size:15px;">Choose Your Location</h5>
                    </div>
                </div>
             <div class="">
                    <div class=" text-center ">
                        <div class="row">
                              <?php 
                            $sql110 = "SELECT * FROM customers WHERE id='$UserId'";
$row110 = getRecord($sql110);
 $CityId = $row110['Location'];
                          $sql2 = "SELECT * FROM city WHERE Status='1' order by srno asc";
                          $res2 = $conn->query($sql2);
                          while($row2 = $res2->fetch_assoc()){
                            $CatId = $row2['id'];
                        ?>
                             <div class="col-4 col-md-4 col-lg-3" data-aos="zoom-in" 
                             <?php if($CityId == $CatId){?>
                                style="background-color: #a4ffb7; height: 160px; border: 1px solid #f9f9f9;"
                             <?php } else{?>   
                             style="background-color: white; height: 160px; border: 1px solid #f9f9f9;"
                         <?php } ?>
                             >
                        <div class=" border-0 mb-2 overflow-hidden" style="padding-top: 20px;">
                            <div class="card-body position-relative avatar avatar-80 mb-1 rounded" >
                              
                                <a href="register.php?city_id=<?php echo $CatId; ?>" class="background">
                                     <?php if($row2["Photo"] == '') {?>
                  <img src="no_image.jpg">
                 <?php } else if(file_exists('../uploads/'.$row2["Photo"])){?>
                 <img src="../uploads/<?php echo $row2["Photo"];?>" alt="">
                  <?php }  else{?>
                 <img src="no_image.jpg"> 
             <?php } ?>
                                   
                                </a>
                            </div>
                          <p class="text-secondary"><small style="font-weight:bold;"><?php echo $row2["Name"];?></small></p>
                        </div>
                        
                    </div>
                    <?php } ?>
                        </div>
                       
                        
                    </div>
                </div>
            </div>
            
            
   


            


            
           
     

        </div>
    </main>

    <!-- footer-->
  <?php //include_once 'footer.php'; ?>


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
   
</body>

</html>
