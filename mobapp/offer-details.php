<?php session_start();
require_once 'config.php';
$PageName = "Offer Details";
$Page = "Shop";
$user_id = $_SESSION['User']['id'];?>
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
     <link href="vendor/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet">
        <link href="vendor/daterangepicker-master/daterangepicker.css" rel="stylesheet">
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay" data-page="shop">



    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <!-- Fixed navbar -->
        <?php include_once 'back-header.php'; ?> 

        <!-- page content start -->

        <div class="main-container">
<?php 
$id = $_GET['id'];
$sql2 = "SELECT * FROM tbl_coupon_code WHERE id='$id'";
$row2 = getRecord($sql2);
?>
<div class="container mb-4">
                <div class="card">
                <div class="card-body">
                    <!-- Swiper -->
                    <div class="swiper-container gallery-top" style="height: 100%;">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide has-background">
                                <div class="">
                                <?php if($row2["Photo"] == '') {?>
                  <img src="no_image.jpg" style="width: 100%;height: 100%;"> 
                 <?php } else if(file_exists('../uploads/'.$row2["Photo"])){?>
                 <img src="../uploads/<?php echo $row2["Photo"];?>" alt="" style="width: 100%;height: 100%;">
                  <?php }  else{?>
                 <img src="no_image.jpg"  style="width: 100%;height: 100%;"> 
             <?php } ?>
                                </div>
                            </div>
                        </div>
                     
                    </div>
                </div>
            </div>
            </div>
            
            <div class="container">
               
                <div class="row">
                    <div class="col">
                    <h5 class="text-success"><?php echo $row2['Code'];?></h5>
                   
                    </div>    
                     <div class="col-auto">
                        <?php if(!isset($_SESSION["cart_item"]) && !isset($_SESSION["cart_item2"])) {?>
                       <button type="button" class="btn btn-default rounded" onclick="erroMessage()"> Apply Coupon</button>
                       <?php } else{ 
                        if($_GET['pageval'] == 'shopcart'){
                       ?>    
                       <a href="shopping-cart.php?coupon=<?php echo $row2['Code'];?>" class="btn btn-default rounded"> Apply Coupon</a>
                       <?php } else {?>
                        <a href="place-order.php?addid=<?php echo $_REQUEST['addid'];?>&userid=<?php echo $_REQUEST['userid'];?>&coupon=<?php echo $row2['Code'];?>" class="btn btn-default rounded"> Apply Coupon</a>
                    <?php } } ?>
                    </div>
                </div><br>
                <span style="color:red;display: none;" id="errmsg">Please Add Items In Cart Before Applying Coupon!</span>
                <hr>
                <p class="text-mute"><?php echo $row2['Details'];?></p>
                <br>
 <input type="hidden" value="<?php echo $row2['Code'];?>" id="myInput">
            </div>
        </div>
    </main>
    <br><br>
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

  <script src="vendor/daterangepicker-master/moment.min.js"></script>
    <script src="vendor/daterangepicker-master/daterangepicker.js"></script>
    <!-- page level custom script -->
    <script src="js/app.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
      <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
<script>
    function erroMessage(){
        $('#errmsg').show();
    }
function myFunction() {
  /* Get the text field */
  var copyText = document.getElementById("myInput");

 
  /* Copy the text inside the text field */
  navigator.clipboard.writeText(copyText.value);
  toastr.success('Coupon Copied..', 'Success', {timeOut: 1000});
  /* Alert the copied text */
  //alert("Copied the text: " + copyText.value);
}
</script>
 
</body>

</html>
