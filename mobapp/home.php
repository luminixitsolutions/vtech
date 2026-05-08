<?php session_start();
$sessionid = session_id();
require_once 'config.php';
//require_once 'auth.php';
$PageName = "Home";

$uid = $_REQUEST['uid'];    
//$_SESSION['Location'] = $city_id;
if($_REQUEST['uid'] == ''){
  $uid = $_SESSION['User']['id'];
}
else{
$uid = $_REQUEST['uid'];    
$sql11 = "SELECT * FROM tbl_users WHERE id='$uid'";
$row = getRecord($sql11);
$_SESSION['User'] = $row;
}

$sql11 = "SELECT * FROM tbl_users WHERE id='$uid'";
$rncnt11 = getRow($sql11);
$row = getRecord($sql11);
$mycity = $row['CityId'];

if($_REQUEST['city_id']==0){
    $city_id = $mycity;  
    
}
else if($_REQUEST['city_id']==''){
    $city_id = $mycity;  
    
}
else{
  $city_id = $_REQUEST['city_id'];
}
if($rncnt11 > 0){
    $_SESSION['User'] = $row;
    // $sql = "UPDATE tbl_users SET Location='$city_id' WHERE id='$uid'";
    // $conn->query($sql);
    
} 
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
   
</head>

  

<body class="body-scroll d-flex flex-column h-100 menu-overlay" data-page="shop">
    
    
    <?php include_once 'sidebar.php'; ?>

    <!-- Begin page content -->
    <main class="flex-shrink-0 main has-footer">
        <!-- Fixed navbar -->
      <?php include_once 'top_header.php'; ?>

        <!-- page content start -->
<!-- page content start -->
   

        <div class="main-container  text-center" style="background-color:#fff;">

            <div class="container mb-2">
                
                <div class="text-center" style="background-color:#fff;">
            
            <script type="text/javascript" src="js/slider/jquery-1.9.1.min.js"></script>
            <script type="text/javascript" src="js/slider/jssor.core.js"></script>
    <script type="text/javascript" src="js/slider/jssor.utils.js"></script>
    <script type="text/javascript" src="js/slider/jssor.slider.js"></script>
    <script>

        jQuery(document).ready(function ($) {

            var _SlideshowTransitions = [
            //Fade
            { $Duration: 500, $Opacity: 2 }
            ];

            var options = {
                $AutoPlay: true,                                    //[Optional] Whether to auto play, to enable slideshow, this option must be set to true, default value is false
                $AutoPlaySteps: 1,                                  //[Optional] Steps to go for each navigation request (this options applys only when slideshow disabled), the default value is 1
                $AutoPlayInterval: 500,                            //[Optional] Interval (in milliseconds) to go for next slide since the previous stopped if the slider is auto playing, default value is 3000
                $PauseOnHover: 1,                               //[Optional] Whether to pause when mouse over if a slider is auto playing, 0 no pause, 1 pause for desktop, 2 pause for touch device, 3 pause for desktop and touch device, default value is 1

                $ArrowKeyNavigation: true,   			            //[Optional] Allows keyboard (arrow key) navigation or not, default value is false
                $SlideDuration: 100,                                //[Optional] Specifies default duration (swipe) for slide in milliseconds, default value is 500
                $MinDragOffsetToSlide: 20,                          //[Optional] Minimum drag offset to trigger slide , default value is 20
                //$SlideWidth: 600,                                 //[Optional] Width of every slide in pixels, default value is width of 'slides' container
                //$SlideHeight: 300,                                //[Optional] Height of every slide in pixels, default value is height of 'slides' container
                $SlideSpacing: 0, 					                //[Optional] Space between each slide in pixels, default value is 0
                $DisplayPieces: 1,                                  //[Optional] Number of pieces to display (the slideshow would be disabled if the value is set to greater than 1), the default value is 1
                $ParkingPosition: 0,                                //[Optional] The offset position to park slide (this options applys only when slideshow disabled), default value is 0.
                $UISearchMode: 1,                                   //[Optional] The way (0 parellel, 1 recursive, default value is 1) to search UI components (slides container, loading screen, navigator container, arrow navigator container, thumbnail navigator container etc).
                $PlayOrientation: 1,                                //[Optional] Orientation to play slide (for auto play, navigation), 1 horizental, 2 vertical, 5 horizental reverse, 6 vertical reverse, default value is 1
                $DragOrientation: 3,                                //[Optional] Orientation to drag slide, 0 no drag, 1 horizental, 2 vertical, 3 either, default value is 1 (Note that the $DragOrientation should be the same as $PlayOrientation when $DisplayPieces is greater than 1, or parking position is not 0)

                $SlideshowOptions: {                                //[Optional] Options to specify and enable slideshow or not
                    $Class: $JssorSlideshowRunner$,                 //[Required] Class to create instance of slideshow
                    $Transitions: _SlideshowTransitions,            //[Required] An array of slideshow transitions to play slideshow
                    $TransitionsOrder: 1,                           //[Optional] The way to choose transition to play slide, 1 Sequence, 0 Random
                    $ShowLink: true                                    //[Optional] Whether to bring slide link on top of the slider when slideshow is running, default value is false
                },

                $BulletNavigatorOptions: {                                //[Optional] Options to specify and enable navigator or not
                    $Class: $JssorBulletNavigator$,                       //[Required] Class to create navigator instance
                    $ChanceToShow: 2,                               //[Required] 0 Never, 1 Mouse Over, 2 Always
                    $AutoCenter: 1,                                 //[Optional] Auto center navigator in parent container, 0 None, 1 Horizontal, 2 Vertical, 3 Both, default value is 0
                    $Steps: 1,                                      //[Optional] Steps to go for each navigation request, default value is 1
                    $Lanes: 1,                                      //[Optional] Specify lanes to arrange items, default value is 1
                    $SpacingX: 10,                                   //[Optional] Horizontal space between each item in pixel, default value is 0
                    $SpacingY: 10,                                   //[Optional] Vertical space between each item in pixel, default value is 0
                    $Orientation: 1                                 //[Optional] The orientation of the navigator, 1 horizontal, 2 vertical, default value is 1
                },

                $ArrowNavigatorOptions: {
                    $Class: $JssorArrowNavigator$,              //[Requried] Class to create arrow navigator instance
                    $ChanceToShow: 2,                               //[Required] 0 Never, 1 Mouse Over, 2 Always
                    $Steps: 1                                       //[Optional] Steps to go for each navigation request, default value is 1
                }
            };
            var jssor_slider1 = new $JssorSlider$("slider1_container", options);

            //responsive code begin
            //you can remove responsive code if you don't want the slider scales while window resizes
            function ScaleSlider() {
                var parentWidth = jssor_slider1.$Elmt.parentNode.clientWidth;
                if (parentWidth)
                    jssor_slider1.$SetScaleWidth(Math.min(parentWidth, 600));
                else
                    window.setTimeout(ScaleSlider, 20);
            }

            ScaleSlider();

            if (!navigator.userAgent.match(/(iPhone|iPod|iPad|BlackBerry|IEMobile)/)) {
                $(window).bind('resize', ScaleSlider);
            }


            //if (navigator.userAgent.match(/(iPhone|iPod|iPad)/)) {
            //    $(window).bind("orientationchange", ScaleSlider);
            //}
            //responsive code end
        });
    </script>
    <!-- Jssor Slider Begin -->
    <!-- You can move inline styles to css file or css block. -->
    <div id="slider1_container" style="position: relative; top: 0px; left: 0px; width: 600px; height: 300px; overflow: hidden;border-radius: 10px !important; ">

        <!-- Loading Screen -->
        <div u="loading" style="position: absolute; top: 0px; left: 0px;">
            <div style="filter: alpha(opacity=70); opacity:0.7; position: absolute; display: block;
                background-color: #000000; top: 0px; left: 0px;width: 100%;height:100%;">
            </div>
            <div style="position: absolute; display: block; background: url(../img/loading.gif) no-repeat center center;
                top: 0px; left: 0px;width: 100%;height:100%;">
            </div>
        </div>

        <!-- Slides Container -->
        <div u="slides" style="cursor: move; position: absolute; left: 0px; top: 0px; width: 600px; height: 300px; overflow: hidden;">
            <?php 
                           $sql = "SELECT Photo FROM home_sliders ORDER BY id ASC";
                           $rx = $conn->query($sql);
                          while($nx = $rx->fetch_assoc()){

                          ?>
            <div>
                <img u="image" src="../uploads/<?php echo $nx["Photo"];?>" />
            </div>
            <?php } ?>
        </div>

        <!-- Bullet Navigator Skin Begin -->
        <style>
            /* jssor slider bullet navigator skin 05 css */
            /*
            .jssorb05 div           (normal)
            .jssorb05 div:hover     (normal mouseover)
            .jssorb05 .av           (active)
            .jssorb05 .av:hover     (active mouseover)
            .jssorb05 .dn           (mousedown)
            */
            .jssorb05 div, .jssorb05 div:hover, .jssorb05 .av {
                background: url(js/slider/b05.png) no-repeat;
                overflow: hidden;
                cursor: pointer;
            }

            .jssorb05 div {
                background-position: -7px -7px;
            }

                .jssorb05 div:hover, .jssorb05 .av:hover {
                    background-position: -37px -7px;
                }

            .jssorb05 .av {
                background-position: -67px -7px;
            }

            .jssorb05 .dn, .jssorb05 .dn:hover {
                background-position: -97px -7px;
            }
        </style>
        <!-- bullet navigator container -->
        <div u="navigator" class="jssorb05" style="position: absolute; bottom: 16px; right: 6px;">
            <!-- bullet navigator item prototype -->
            <div u="prototype" style="POSITION: absolute; WIDTH: 16px; HEIGHT: 16px;"></div>
        </div>
        <!-- Bullet Navigator Skin End -->
        <!-- Arrow Navigator Skin Begin -->
        <style>
            /* jssor slider arrow navigator skin 12 css */
            /*
            .jssora12l              (normal)
            .jssora12r              (normal)
            .jssora12l:hover        (normal mouseover)
            .jssora12r:hover        (normal mouseover)
            .jssora12ldn            (mousedown)
            .jssora12rdn            (mousedown)
            */
            .jssora12l, .jssora12r, .jssora12ldn, .jssora12rdn {
                position: absolute;
                cursor: pointer;
                display: block;
                background: url(../img/a12.png) no-repeat;
                overflow: hidden;
            }

            .jssora12l {
                background-position: -16px -37px;
            }

            .jssora12r {
                background-position: -75px -37px;
            }

            .jssora12l:hover {
                background-position: -136px -37px;
            }

            .jssora12r:hover {
                background-position: -195px -37px;
            }

            .jssora12ldn {
                background-position: -256px -37px;
            }

            .jssora12rdn {
                background-position: -315px -37px;
            }
        </style>
        <!-- Arrow Left -->
        <span u="arrowleft" class="jssora12l" style="width: 30px; height: 46px; top: 123px; left: 0px;">
        </span>
        <!-- Arrow Right -->
        <span u="arrowright" class="jssora12r" style="width: 30px; height: 46px; top: 123px; right: 0px">
        </span>
        <!-- Arrow Navigator Skin End -->
        <a style="display: none" href="http://www.jssor.com">jquery content slider</a>
    </div>
    
               
                </div>
            </div>
            
           
            
             <div class="container mb-2">
                <div class="row">
                    <div class="col  mb-2">
                        <h5 class="subtitle" style="font-size:15px;">Shop by Category</h5>
                    </div>
                </div>
             <div class="">
                    <div class=" text-center ">
                        <div class="row">
                              <?php 
                          $sql2 = "SELECT * FROM category WHERE Status='1' AND Service!=1 ORDER BY id asc";
                          $res2 = $conn->query($sql2);
                          while($row2 = $res2->fetch_assoc()){
                            $CatId = $row2['id'];
                        ?>
                             <div class="col-4 col-md-4 col-lg-3" data-aos="zoom-in">
                        <div class=" border-0 mb-2 overflow-hidden">
                            <div class="card-body position-relative avatar avatar-80 mb-1 rounded" >
                              
                                <a href="sub-category.php?cat_id=<?php echo $CatId; ?>&val=category" class="background">
                                     <?php if($row2["Photo"] == '') {?>
                  <img src="no_image.jpg">
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
                
            
   


            


            
   <div class=" mb-4 px-0">
                <!-- Swiper -->
                <div class="swiper-container offerslide2tab1 text-center">
                    <div class="swiper-wrapper">
                         <?php 
                           $sql = "SELECT * FROM home_banners ORDER BY id ASC";
                           $rx = $conn->query($sql);
                          while($nx = $rx->fetch_assoc()){

                          ?>
                        <div class="swiper-slide">
                            <div class=" overflow-hidden">
                                <div class="background">
                                    <img src="../uploads/<?php echo $nx["Photo"];?>" alt="">
                                </div>
                               
                            </div>
                        </div>
                        <?php } ?>
                        
                    </div>
                    <!-- Add Pagination -->
                    <div class="swiper-pagination white-pagination text-left mb-3"></div>
                </div>
            </div>         
      
 
 <div class="container mb-4">
     <div class="row">
                    <div class="col  mb-2">
                        <h5 class="subtitle" style="font-size:15px;font-weight:bold;">Bestseller Products</h5>
                    </div>
                </div> 
    <?php 
                        $results_per_page = 50; // number of results per page
      if (isset($_GET["page"])) {
        $page = $_GET["page"];
      } else {
        $page = 1;
        };
     $start_from = ($page - 1) * $results_per_page;  
                        $CatId = $_GET['cat_id'];
                        $SubCatId = $_GET['subid'];
                        $query = "SELECT * FROM products WHERE Status='1' AND Bestseller='1'  ORDER BY id DESC";
                        //echo $query;
                        $pagerncnt = getRow($query);
                        $query.="  LIMIT $start_from,$results_per_page";    
                        $rncnt = getRow($query);
                        if($rncnt > 0){
                         $row = getList($query);
                        foreach($row as $result){
                            $Prod_id = $result["id"];
                        $cat_id = $result['CatId'];
                        $SizeId = $result['Size'];
                        $ItemStock = $result['Stock'];
                        $sql5 = "SELECT * FROM attribute_value WHERE id='$SizeId'";
                        $res5 = $conn->query($sql5);
                        $row5 = $res5->fetch_assoc();
                        $w = "SELECT * FROM wishlist WHERE UserId = '$user_id' AND ProductId = '$Prod_id'";
                        $ws = getRecord($w);
                            if($ws['Value'] == 1){
                              $value = 0;
                            }
                            else{
                              $value = 1;
                            }
                         ?>
        <input type="hidden" id="user_id" value="<?php echo $user_id; ?>">
  <input type="hidden" value="<?php echo $value;?>" id="wishid<?php echo $result["id"];?>">
   <input type="hidden" id="pid<?php echo $result["id"];?>" value="<?php echo $result["id"];?>">
    <input type="hidden" id="sizeid<?php echo $result["id"];?>" value="<?php echo $result['Size'];?>">
   <input type="hidden" id="ramid<?php echo $result["id"];?>" value="<?php echo $result['Ram'];?>">
    <input type="hidden" id="storageid<?php echo $result["id"];?>" value="<?php echo $result['Storage'];?>">
    <input type="hidden" id="code<?php echo $result["id"];?>" value="<?php echo $result['code'];?>">
     <input type="hidden" id="prd_price<?php echo $result["id"];?>" value="<?php echo $result['MinPrice'];?>"> 
      <input type="hidden" id="qntno<?php echo $result["id"];?>" value="1">     
          
                <div class="media mb-2 w-100 " data-aos="zoom-in" style="box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.05);
-webkit-box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.05); -moz-box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.05);
-ms-box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.05); border-radius: 15px; background: #fff;">

                    <div class="avatar avatar-150 mr-2 has-background rounded">
                        <a href="product-details.php?id=<?php echo $result["id"];?>"><figure class="">
                           <?php if($result["Photo"] == '') {?>
                  <img src="no_image.jpg" style="width: 150px;height: 150px;"> 
                 <?php } else if(file_exists('../uploads/'.$result["Photo"])){?>
                 <img src="../uploads/<?php echo $result["Photo"];?>" alt="" style="width: 150px;height: 150px;">
                  <?php }  else{?>
                 <img src="no_image.jpg" style="width: 150px;height: 150px;"> 
             <?php } ?>
                        </figure></a>
                          <?php if($result['OfferPer'] != 0){?>
                        <div class="top-right m-2">
                                    <button class="btn btn-sm btn-light btn-rounded btn-30 rounded-circle" style="border-radius: 20px !important;height: 40px;width: 60px;"><?php echo $result['OfferPer'];?>% Off</button>
                                </div>
                                <?php } ?>
                    </div>
                    <div class="media-body " style="padding-top: 15px; padding-right:3px;">
                         <a href="product-details.php?id=<?php echo $result["id"];?>"><span style="font-weight: 600; font-size: 15px;"><?php echo $result['ProductName']; ?></span></a><br>
                        <?php if($result['MaxPrice'] != $result['MinPrice']){?>
                        <span id="MaxPrice3<?php echo $result["id"];?>"><del>&#8377;<?php echo number_format($result["MaxPrice"],2);?> </del></span>
                        <?php } ?>
                        <span style="font-weight: 500;" id="MinPrice3<?php echo $result["id"];?>">&#8377; <?php echo number_format($result["MinPrice"],2); ?> </span>
                        
                        <br>
                        <?php if($result['Size']=='0'){}else if($result['CatId'] != 0){?>
                        <select class="" style="display: block;

padding: 0.375rem 0.75rem;
font-size: 12px;
font-weight: 400;
line-height: 1.5;
color: #495057;
background-color: #fff;
background-clip: padding-box;
border: 1px solid #ced4da;
border-radius: 0.25rem;
transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;" onchange="getDiffSize(this.value,<?php echo $result["id"];?>)">
 <?php if($result['Size']=='0') {} else if($result['CatId'] != 0){?>
                                  <option value="<?php echo $result['Size'];?>" selected><?php echo $row5['Name'];?></option>
                                    <?php }
                                    $sql41 = "SELECT DISTINCT(AttrValueSize) as sizeid FROM `related_products`  WHERE ProdId = '$Prod_id' AND AttrValueSize != '0'";
                                    $res41 = $conn->query($sql41);
                                    $rncnt41 = mysqli_num_rows($res41);
                                    if($rncnt41 > 0){ 
                                    $i= 2;
                                    while($row41 = $res41->fetch_assoc()){
                                    $Size_Id = $row41['sizeid'];
                                    $sql32 = "SELECT * FROM attribute_value WHERE id='$Size_Id'";
                                    $res32 = $conn->query($sql32);
                                    $row32 = $res32->fetch_assoc();
                                    if($row2['Size'] == $Size_Id){} else{?>
                                    <option value="<?php echo $row32['id'];?>"><?php echo $row32['Name'];?></option>
                                   <?php } $i++;} } ?>
                                  </select>
                                   <?php } ?>
                                <div style="padding-top:5px;">
<button class="btn btn-sm btn-default rounded" style="font-size: 12px;" id="add-cart<?php echo $result["id"];?>" onclick="addCart(<?php echo $result["id"];?>);"><i style="font-size:14px;" class="material-icons">local_mall</i> Add</button>

<?php if($result["Subscription"] == 1) {?>
<a href="subscribe-product.php?id=<?php echo $result["id"];?>" class="btn btn-sm btn-default rounded" style="font-size: 12px;"> subscribe</a>
 <?php } ?>
                                </div>
                               
                      <!--   <small class="text-secondary">11-1-2020 | 24:00 am</small> -->
                        
                    </div>
           
                </div>

<?php } } else{?>
  <h5 style="color: red;font-size: 14px;padding-left: 25px;">Oops! Product Not Found...</h5>
                   <?php } ?>

                

            </div>
            
 <div class="container-fluid bg-default-light text-center py-0 mb-2">
    <br>
       <a href="JavaScript:Void(0);" onclick="onClickWhatsAppCall(+918446107890)"><img src="5_whatsapp2.png" alt="" style="width: 100%;"></a>
    </div>


<!--
<div class="container mb-4 px-0">-->
                <!-- Swiper -->
                <!--<div class="swiper-container offerslide2tab1 text-center">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <div class="card overflow-hidden">
                                <div class="background">
                                    <img src="img/image6.jpg" alt="">
                                </div>
                                <div class="card-body py-4 text-white">
                                    <h3 class="font-weight-normal">Woman's<br>Collections</h3>
                                    <p class="text-mute">Upto 70% off</p>
                                    <a href="#" class="btn btn-sm btn-default rounded mt-3">Show Now</a>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="card overflow-hidden">
                                <div class="background">
                                    <img src="img/image4.jpg" alt="">
                                </div>
                                <div class="card-body py-4 text-white">
                                    <h3 class="font-weight-normal">Men's<br>Collections</h3>
                                    <p class="text-mute">Upto 70% off</p>
                                    <a href="#" class="btn btn-sm btn-default rounded mt-3">Show Now</a>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="card overflow-hidden">
                                <div class="background">
                                    <img src="img/image1.jpg" alt="">
                                </div>
                                <div class="card-body py-4 text-white">
                                    <h3 class="font-weight-normal">Kid's<br>Collections </h3>
                                    <p class="text-mute">Upto 70% off</p>
                                    <a href="#" class="btn btn-sm btn-default rounded mt-3">Show Now</a>
                                </div>
                            </div>
                        </div>
                    </div>-->
                    <!-- Add Pagination -->
                    <!--<div class="swiper-pagination white-pagination text-left mb-3"></div>
                </div>
            </div>
            
        </div>-->
    </main>

    <!-- footer-->
  <?php include_once 'footer.php'; ?>


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
    <script type="text/javascript">
          function getDiffSize(id,pid){
      $('#sizeid'+pid).val(id);
    var sizeid = $('#sizeid'+pid).val();
    getDiffPrice2(id,pid);
   }   
   function getDiffSize3(id,pid){
      $('#sizeid2'+pid).val(id);
    var sizeid = $('#sizeid2'+pid).val();
    getDiffPrice2(id,pid);
   }   
     function getDiffPrice2(sizeid,pid){
     var action = 'getDiffPrice2';
            $.ajax({
  type: "POST",
  url: "ajax_files/ajax_product.php",
   data:{action:action,pid:pid,sizeid:sizeid},  
  success: function(data){
    res = JSON.parse(data);
      var MinPrice = res.MinPrice;
      var MaxPrice = res.MaxPrice;
      var OfferPrice = res.OfferPrice;
      var OfferPer = res.OfferPer;
      var MinPrice2 = res.MinPrice2;
      var MaxPrice2 = res.MaxPrice2;
      var OfferPrice2 = res.OfferPrice2;
      var status = res.status;
      var Stock = res.Stock;
      var ErrorMsg = res.ErrorMsg;
        $('#prd_price'+pid).val(MinPrice);
         $('#prd_price2'+pid).val(MinPrice);
         $('#sizeid'+pid).val(sizeid);
         $('#sizeid2'+pid).val(sizeid);
      if(Stock == 1){


/*$('#MaxPrice3'+pid).html('<del>&#8377;'+MaxPrice2+'</del>');*/
$('#MinPrice'+pid).html('&#8377;'+MinPrice2);
$('#MinPrice2'+pid).html('&#8377;'+MinPrice2);
//$('#OfferPer2'+pid).html('-'+OfferPer+'%');
//$('#notify_btn'+pid).hide();
$('#cart_btn'+pid).show();
$('#cart_btn2'+pid).show();
$('#Size_Id'+pid).attr("selected",true).val(sizeid);
$('#Size_Id2'+pid).attr("selected",true).val(sizeid);
      }
      else{
toastr.error('Currently, This Size Of Product is Not Available!', 'Error', {timeOut: 3000});
//$('#notify_btn'+pid).show();
$('#cart_btn'+pid).hide();
$('#cart_btn2'+pid).hide();
      }
    }
                  });
   } 
        function addCart(id){
var action = "shop_cart";
var quantity = $('#qntno'+id).val();
var code = $('#code'+id).val();
var pid = $('#pid'+id).val();
var sizeid = $('#sizeid'+id).val();
var ramid = $('#ramid'+id).val();
var storageid = $('#storageid'+id).val();
var price = $('#prd_price'+id).val();
$.ajax({
  type: "POST",
  url: "ajax_files/ajax_shop_cart.php",
  data: {action:action,pid:pid,quantity:quantity,code:code,sizeid:sizeid,ramid:ramid,storageid:storageid,price:price},
   beforeSend:function(){
     $('#add-cart'+id).attr('disabled','disabled');
     $('#add-cart'+id).text('Adding To Cart...');
      $('#add-cart2'+id).attr('disabled','disabled');
     $('#add-cart2'+id).text('Adding To Cart...');
    },

  success: function(data){
       toastr.success('Product Added to Cart', 'Success', {timeOut: 1000});
       $('#add-cart'+id).attr('disabled',false);
       $('#add-cart'+id).text('Added..');
       $('#add-cart2'+id).attr('disabled',false);
       $('#add-cart2'+id).text('Added..');
      }
});

        }
        
         $(document).ready(function(){
          //$('#myModal').modal("show");
            });
    </script>
    <script>
     function onClickCall(mob){
        //alert(mob);
        Android.onClickCall(''+mob+'');
    }

    function onClickWhatsAppCall(mob){
        //alert(mob);
        Android.onClickWhatsAppCall(''+mob+'');
    }
</script>
</body>

</html>
