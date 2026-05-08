<?php session_start();
require_once 'config.php';
$PageName = "Category"; ?>
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
    <link rel="stylesheet" href="dist/css/styles.css" />
    <link rel="stylesheet" href="dist/aos.css" />
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay" data-page="products">
    <!-- screen loader -->
   



    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <!-- Fixed navbar -->
       <?php include_once 'back-header.php'; ?>
        <!-- page content start -->
        <div class="container mb-2" >
                <!-- Swiper -->
                <div class="swiper-container categoriestab1 text-center">
                    <div class="swiper-wrapper">
                        
                        <?php 
                          $sql2 = "SELECT * FROM category WHERE Status='1' AND Service!=1 ORDER BY id asc";
                          $res2 = $conn->query($sql2);
                          while($row2 = $res2->fetch_assoc()){
                            $CatId = $row2['id'];
                        ?>
                        
                        <div class="swiper-slide">
                            <div class="">
                                <div class="card-body p-2">
                                    <div class="avatar avatar-60  rounded-circle">
                                        <div class="background">
                                            <?php if($row2["Photo2"] == '') {?>
                  <img src="no_image.jpg">
                 <?php } else if(file_exists('../uploads/'.$row2["Photo2"])){?>
                 <img src="../uploads/<?php echo $row2["Photo2"];?>" alt="">
                  <?php }  else{?>
                 <img src="no_image.jpg"> 
             <?php } ?>
             
                                        </div>
                                        
                                    </div>
                                  <!--<p class="text-secondary"><small><?php echo $row2["Name"];?></small></p>-->
                                </div>
                                
                            </div>
                        </div>
                         <?php } ?>
                    </div>
                    <!-- Add Pagination -->
                    <div class="swiper-pagination white-pagination text-left mb-3"></div>
                </div>
            </div>
        <div class="main-container">
<div class="container mb-4">
                <div class="media mb-4 w-100 " style="box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.05);
-webkit-box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.05); -moz-box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.05);
-ms-box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.05); border-radius: 15px; background: #fff;">

                    <div class="avatar avatar-150 mr-3 has-background rounded">
                        <figure class="background">
                            <img src="p1.jpg" class="" alt="">
                        </figure>
                    </div>
                    <div class="media-body " style="padding-top: 5px;">
                        <span style="font-weight: 600; font-size: 15px;">Coconut Water (Nariyal Pani)</span><br>
                        <span style="font-weight: 500;">₹ 40.00 </span><br>
                        <select class="" style="background-color: #fff; background-clip: padding-box;
border: 1px solid #ced4da; border-radius: 0.25rem; transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;">
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                    <option>5</option>
                                </select>
                                <div style="padding-top:3px;">
<button class="btn btn-sm btn-default rounded" style="font-size: 12px;" id="add-cart2104" onclick="addCart(2104);"><i style="font-size:14px;" class="material-icons">local_mall</i> Add</button>

<button class="btn btn-sm btn-default rounded" style="font-size: 12px;" id="add-cart2104" onclick="addCart(2104);"> subscribe</button>
                                </div>
                               
                        <small class="text-secondary">11-1-2020 | 24:00 am</small>
                        
                    </div>
           
                </div>


                

            </div>
       

            <div class="container">
             
                <div class="row">
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
                        $query = "SELECT * FROM products WHERE Status='1' AND CatId='$CatId' ORDER BY id DESC"; 
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
                    <div class="col-6 col-md-4 col-lg-3 aos-item" data-aos="zoom-in">
                        <div class="card border-0 mb-4 overflow-hidden">
                            <div class="card-body h-150 position-relative">
                                <?php if(isset($_SESSION['User'])){
                                    if($DiscPer != 0){
                                    ?>
                                <div class="top-right m-2">
                                    <button class="btn btn-sm btn-light btn-rounded btn-40 rounded-circle"><?php echo substr($DiscPer,0,-3)."%";?></button>
                                </div><?php } } ?>
                                <?php if($result["id"] == 3){} else{?>
                                <div class="bottom-right m-2" id="cart_btn<?php echo $result["id"];?>">
                                    <button class="btn btn-sm btn-default rounded" style="font-size: 12px;" id="add-cart<?php echo $result["id"];?>" onclick="addCart(<?php echo $result["id"];?>);"><i class="material-icons">local_mall</i> Add</button>
                                </div><?php } ?>
                                <a href="product-details.php?id=<?php echo $result["id"];?>" class="background">
                                     <?php if($result["Photo"] == '') {?>
                  <img src="no_image.jpg" style="width: 166px;height: 150px;"> 
                 <?php } else if(file_exists('../uploads/'.$result["Photo"])){?>
                 <img src="../uploads/<?php echo $result["Photo"];?>" alt="" style="width: 166px;height: 150px;">
                  <?php }  else{?>
                 <img src="no_image.jpg" style="width: 166px;height: 150px;"> 
             <?php } ?>
                                   
                                </a>
                            </div>
                            <div class="" align="center" style="padding-bottom:5px;">
                                <div style="padding-bottom:5px;">
                                <p class="mb-0"><small class="text-secondary"><?php echo $row121['Name']; ?></small></p>
                                <a href="product-details.php?id=<?php echo $result["id"];?>">
                                    <p class="mb-0"><?php echo $result['ProductName']; ?></p>
                                </a>
                                <h5 class="mb-0" style="font-size: 15px;" id="MinPrice3<?php echo $result["id"];?>">&#8377; <?php echo number_format($result["MinPrice"],2); ?>
                                <?php if($result["OfferPer"] == '0.00') {} else{ ?>
                                &nbsp;&nbsp;<!--<del>&#8377; <?php echo number_format($result["MaxPrice"],2); ?></del>--><?php } ?></h5>
                                 <?php if($result['Size']=='0'){}else if($result['CatId'] != 0){?>
                               </div>

                                 <select class="form-control" style="height:30px;width:80%; padding: 0 20px;background-color:#fff;border: 1px solid #dddddd; " data-placeholder="Sort Items" onchange="getDiffSize(this.value,<?php echo $result["id"];?>)">
                             <?php 
                                      if($result['Size']=='0') {} else if($result['CatId'] != 0){?>
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
                                   <?php if($result["Subscription"] == 1) {?>
                                   <div style="padding-top:2px;"></div>
                                      <a href="subscribe-product.php?id=<?php echo $result["id"];?>" class="btn btn-sm btn-default rounded" style="font-size: 12px;">Subscribe</a>
                                  <?php } ?>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                     
                   <?php } else{?>

                        <h5 style="color: red;font-size: 14px;padding-left: 25px;">Oops! Product Not Found...</h5>
                   <?php } ?>

                    </div>
                    
                  
                <!-- <div class="row text-center">
                    <div class="col-6 col-md-4 col-lg-3 mx-auto">
                        <button class="btn btn-sm btn-outline-secondary rounded">Load More...</button>
                    </div>
                </div> -->
            </div>
              <nav>
         <ul class="pagination justify-content-center">
          <?php $total_pages = ceil($pagerncnt / $results_per_page); ?>
            <li class="page-item">
              <a class="page-link" href="<?php echo $_SERVER['PHP_SELF']; ?>?page=1&cat_id=<?php echo $_GET['cat_id'];?>&subid=<?php echo $_GET['subid'];?>" aria-label="Previous">
              <span aria-hidden="true">«</span>
              <span class="sr-only">Previous</span>
              </a>
            </li>
            <?php 
            for ($i = 1; $i <= $total_pages; $i++) {
                if($i == 1){
                  if($_GET['page']==''){
                    $activeclass= "page-item active";
                  }
                  else if($_GET['page']==$i){
                    $activeclass= "page-item active";
                  }
                }
                else if($_GET['page']==$i){
                  $activeclass= "page-item active";
                }
                else{
                  $activeclass= "page-item";
                }
             ?>
                                <li class="<?php echo $activeclass ?>">
                                    <a class="page-link" href="<?php echo $_SERVER['PHP_SELF']; ?>?page=<?php echo $i;?>&cat_id=<?php echo $_GET['cat_id'];?>&subid=<?php echo $_GET['subid'];?>"><?php echo $i;?></a>
                                </li>
                                <?php }; ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?php echo $_SERVER['PHP_SELF']; ?>?<?php if($total_pages>=1){?>page=<?php echo $total_pages;?><?php } else{?>&page=<?php echo $total_pages+1;}?>&cat_id=<?php echo $_GET['cat_id'];?>&subid=<?php echo $_GET['subid'];?>" aria-label="Next">
                                        <span aria-hidden="true">»</span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </li>
                            </ul>
                        </nav><br><br><br>
        </div>
    </main>
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
      if(Stock == 1){


/*$('#MaxPrice3'+pid).html('<del>&#8377;'+MaxPrice2+'</del>');*/
$('#MinPrice3'+pid).html('&#8377;'+MinPrice2);
//$('#OfferPer2'+pid).html('-'+OfferPer+'%');
//$('#notify_btn'+pid).hide();
$('#cart_btn'+pid).show();
      }
      else{
toastr.error('Currently, This Size Of Product is Not Available!', 'Error', {timeOut: 3000});
//$('#notify_btn'+pid).show();
$('#cart_btn'+pid).hide();
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
