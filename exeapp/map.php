<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Installation";
$Page = "Rooftop-Installation";
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport"
	      content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Google Maps JavaScript API</title>
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
    <link href="css/toastr.min.css" rel="stylesheet">
    <script src="js/jquery.min3.5.1.js" type="text/javascript"></script>
    <script type="text/javascript" src="js/toastr.min.js"></script>
    <link rel="stylesheet" href="example/css/slim.min.css">
    <?php include_once 'header_script.php'; ?>
	<link rel="stylesheet" href="style.css">
</head>
<body class="body-scroll d-flex flex-column h-100 menu-overlay">


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">

            
<?php include 'back-header.php';?>



<div class="layout-content">

                    <div class="container-fluid flex-grow-1 container-p-y">
                      <input id="map-search" class="controls" type="hidden" placeholder="Search Box" size="104">
<input type="hidden" class="latitude">
<input type="hidden" class="longitude">
<input type="hidden" class="reg-input-city" placeholder="City">
                                </div>
                                </div>

<input type="hidden" id="mylat" value="<?php echo $Latitude;?>">
<input type="hidden" id="mylang" value="<?php echo $Longitude;?>">
<div id="map-canvas"></div>
<?php include_once 'footer.php'; ?>
               

             </main>
             <script src="js/main.js"></script>
<script src="javascript.js"></script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyADZAncocVsQMiK8ebIDhli29nk5GWWydk&libraries=places&callback=initialize"></script>
  <script src="js/app.js"></script>
       <?php include_once 'footer_script.php'; ?>
</body>
</html>
