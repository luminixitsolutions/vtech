<?php session_start();
require_once 'config.php';
$user_id = $_SESSION['User']['id'];
$VedId = $_GET['id'];
$CatId = $_GET['cat_id'];
$SubCatId = $_GET['subid'];
$sql11 = "SELECT * FROM customers WHERE id='$VedId' ORDER BY id DESC";
$row11 = getRecord($sql11);
$VedCatId = $row11['PrdCatId'];
$VedSubCatId = $row11['PrdSubCatId'];
$PageName = $row11['ShopName'];

$Page = "Shop";
    //print_r($_SESSION["cart_item"]);
$sql121 = "SELECT * FROM sub_category2 WHERE id='$SubCatId'";
$row121 = getRecord($sql121);
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="css/toastr.min.css" rel="stylesheet" id="style">
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBemgFWItHwsB5F4m0RMmaRle2AyjeA_vo&callback=initMap">
</script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js">
</script>
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay" data-page="products">
  
   

    <!-- Begin page content -->
    <main class="flex-shrink-0 main has-footer">
        <?php include_once 'back-header.php'; ?>

        <!-- page content start -->
        <div class="container-fluid px-0">
            <div class="card overflow-hidden">
                <div class="card-body p-0 h-150">
                    <div class="background">
                        <?php if($row11['VendorPhoto'] == ''){?>
                        <img src="img/image10.jpg" alt="" style="width: 392px;height: 150px;">
                        <?php } else if(file_exists("../uploads/".$row11['VendorPhoto'])) {?>
                        <img src="../uploads/<?php echo $row11['VendorPhoto']; ?>" alt="" style="width: 392px;height: 150px;">
                        <?php } else {?>
                        <img src="img/image10.jpg" alt="" style="width: 392px;height: 150px;">
                        <?php } ?>    
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid top-70 text-center">
            <div class="avatar avatar-140 rounded-circle mx-auto shadow">
                <div class="background">
                     <?php if($row11['Photo'] == '') {?>
                        <img src="no_image.jpg" alt="" style="width: 140px;height: 140px;">
                     <?php } else{?>
                        <img src="../uploads/<?php echo $row11['Photo']; ?>" alt="" style="width: 140px;height: 140px;">
                     <?php } ?>
                </div>
            </div>
        </div>

        <div class="container mb-4 text-center text-white">
              <p class="small mb-0">
                                <i class="fa fa-star" style="color: #ffa700;"></i>
                                <i class="fa fa-star" style="color: #ffa700;"></i>
                               <i class="fa fa-star" style="color: #ffa700;"></i>
                                <i class="fa fa-star" style="color: #ffa700;"></i>
                                <i class="fa fa-star-half-empty" style="color: #ffa700;"></i>
                                <span class="text-mute">4.85</span>
                            </p>
            <h6 class="mb-1"><?php echo $row11['ShopName']; ?></h6>
            <span><i class="fa fa-map-marker"></i> <?php echo $row11['Address']; ?></span>
            <p class="mb-1"><i class="fa fa-envelope"></i> <?php echo $row11['EmailId']; ?></p>
            <p><i class="fa fa-phone"></i> <?php echo $row11['Phone']; ?></p>
        </div>



        <div class="main-container">
           <div class="container mb-4">
         <div id="map" style="width: 100%; height: 200px;"></div>
       </div>
  <script type="text/javascript">
    var locations = [
   
      ['<?php echo $row11['ShopName'];?>', <?php echo $row11['latitude'];?>, <?php echo $row11['longitude'];?>, <?php echo $row11['id'];?>],
     
    ];

    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 10,
      center: new google.maps.LatLng(21.16268, 79.16041),
      mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    var infowindow = new google.maps.InfoWindow();

    var marker, i;
    var iconBase = 'http://nearbystore.in/';
    for (i = 0; i < locations.length; i++) {  
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
        map: map,
        icon: iconBase + 'googlepointer.png'
      });

      google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
          infowindow.setContent(locations[i][0]);
          infowindow.open(map, marker);
        }
      })(marker, i));
    }
  </script>
  
            <div class="container mb-4 px-0">
            <!-- Swiper -->
            <div class="swiper-container categoriestab1 text-center" style="padding-left: 10px;">
                <div class="swiper-wrapper">
                    <?php 
                    $sql22 = "SELECT * FROM sub_category2 WHERE id IN($VedSubCatId)";
                    $row22 = getList($sql22);
                    foreach($row22 as $result){
                     ?>
                    <div class="swiper-slide">
                        <div class="card <?php if($_GET['subid'] == $result['id']) {?>bg-default-light<?php } ?>">
                            <div class="card-body p-2">
                                <a href="vendor-details.php?id=<?php echo $_GET['id'];?>&cat_id=<?php echo $_GET['cat_id'];?>&subid=<?php echo $result['id'];?>"><div class="avatar avatar-60 mb-1 rounded">
                                    <div class="background">
                                        <?php if($result["Photo"] == '') {?>
                  <img src="no_image.jpg" style="width: 60px;height: 60px;"> 
                 <?php } else if(file_exists('../uploads/'.$result["Photo"])){?>
                 <img src="../uploads/<?php echo $result["Photo"];?>" alt="" style="width: 60px;height: 60px;">
                  <?php }  else{?>
                 <img src="no_image.jpg" style="width: 60px;height: 60px;"> 
             <?php } ?>
                                    </div>
                                </div></a>
                                <p class="text-default"><small><?php echo $result['Name']; ?></small></p>
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
                <?php 
                     $query = "SELECT * FROM products WHERE Status='1' AND VedId='$VedId' AND CatId='$CatId' AND SubCatId IN($SubCatId)"; 
                        $rncnt = getRow($query);
                        if($rncnt > 0){
                       
                     ?>    
                <div class="card">
                    
                    <div class="card-body px-0 pt-0">
                         <div class="row">
                        <?php 
                         $row = getList($query);
                        foreach($row as $result){
                            $Prod_id = $result["id"];
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
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="card border-0 mb-4 overflow-hidden">
                            <div class="card-body h-150 position-relative">
                                <div class="top-right m-2">
                                    <button class="btn btn-sm btn-light btn-rounded btn-40 rounded-circle"><i class="material-icons mt-0 vm">favorite_border</i></button>
                                </div>
                                <div class="bottom-right m-2">
                                    <button class="btn btn-sm btn-default rounded" style="font-size: 12px;" id="add-cart<?php echo $result["id"];?>" onclick="addCart(<?php echo $result["id"];?>);"><i class="material-icons">local_mall</i> Add</button>
                                </div>
                                <a href="product-details.php" class="background">
                                     <?php if($result["Photo"] == '') {?>
                  <img src="no_image.jpg" style="width: 166px;height: 150px;"> 
                 <?php } else if(file_exists('../uploads/'.$result["Photo"])){?>
                 <img src="../uploads/<?php echo $result["Photo"];?>" alt="" style="width: 166px;height: 150px;">
                  <?php }  else{?>
                 <img src="no_image.jpg" style="width: 166px;height: 150px;"> 
             <?php } ?>
                                   
                                </a>
                            </div>
                            <div class="card-body ">
                                <p class="small mb-0">
                                <i class="fa fa-star" style="color: #ffa700;"></i>
                                <i class="fa fa-star" style="color: #ffa700;"></i>
                               <i class="fa fa-star" style="color: #ffa700;"></i>
                                <i class="fa fa-star" style="color: #ffa700;"></i>
                                <i class="fa fa-star-half-empty" style="color: #ffa700;"></i>
                                <span class="text-mute">4.85</span>
                            </p>
                                <p class="mb-0"><small class="text-secondary"><?php echo $row121['Name']; ?></small></p>
                                <a href="product-details.php">
                                    <p class="mb-0"><?php echo $result['ProductName']; ?></p>
                                </a>
                                <h5 class="mb-0" style="font-size: 15px;">&#8377; <?php echo number_format($result["MinPrice"],2); ?>
                                <?php if($result["OfferPer"] == '0.00') {} else{ ?>
                                &nbsp;&nbsp;<del>&#8377; <?php echo number_format($result["MaxPrice"],2); ?></del><?php } ?></h5>
                            </div>
                        </div>
                    </div>
                    <?php } ?>

                    </div>
                    </div>
                </div>
            <?php } else{?>

                        <h5 style="color: red;font-size: 14px;">Oops! Product Not Found...</h5>
                   <?php } ?>
            </div>
            
        </div>
    </main>

    <!-- footer-->
    <?php include_once 'footer.php'; ?>
    <!-- color settings style switcher -->
    

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
        <script src="js/toastr.min.js"></script>
    <script type="text/javascript">
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
    },

  success: function(data){
       toastr.success('Product Added to Cart', 'Success', {timeOut: 1000});
       $('#add-cart'+id).attr('disabled',false);
                       $('#add-cart'+id).text('Added..');
      }
});

        }
    </script>
</body>

</html>
