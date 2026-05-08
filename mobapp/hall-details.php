<?php session_start();
require_once 'config.php';
$PageName = "Product Details"; ?>
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

<body class="body-scroll d-flex flex-column h-100 menu-overlay" data-page="productdetails">
   



    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        
  <?php include_once 'back-header.php'; ?>
        <!-- page content start -->
<?php 
$id = $_GET['id'];
$cat_id = $_GET['cat_id'];
$sql7 = "SELECT p.*,c.Name As Category,p.Color As ColorId,av.Name As Size,p.Size As SizeId,p.Ram As RamId,p.Storage As StorageId,av2.Name As Ram,av3.Name As Storage,sb.Name As SubCategory,b.Name As Brand 
                    FROM products p 
                    LEFT JOIN category c ON c.id=p.CatId
                    LEFT JOIN sub_category sb ON sb.id=p.SubCatId
                    LEFT JOIN attribute_value av ON p.Size = av.id
                    LEFT JOIN attribute_value av2 ON p.Ram = av2.id
                    LEFT JOIN attribute_value av3 ON p.Storage = av3.id
                    LEFT JOIN brands b ON b.id=p.BrandId WHERE p.id='$id'";
$res7 = $conn->query($sql7);
$row7 = $res7->fetch_assoc();
$ColorId = $row7['ColorId'];
$row7['ColorId'] = explode(",", $row7['ColorId']);
if($row7['Stock']=='1'){
  $ItemStock = "<span style='color:green;' class='ps-tag--in-stock stock'>IN STOCK</span>";
} 
  else { 
    $ItemStock =  "<span style='color:#ec0101;' class='ps-tag--in-stock stock'>OUT OF STOCK</span>";
}
$Prod_id = $_GET['id'];
$cat_id = $row7['CatId'];
                        $SizeId = $row7['SizeId'];
                        $ItemStock = $row7['Stock'];
                        $sql5 = "SELECT * FROM attribute_value WHERE id='$SizeId'";
                        $res5 = $conn->query($sql5);
                        $row5 = $res5->fetch_assoc();
 ?>
 <input type="hidden" id="Prod_Id" value="<?php echo $_GET['id'];?>">
        <div class="main-container">
            <div class="container mb-4">
                <div class="card">
                <div class="card-body">
                    <!-- Swiper -->
                    <div class="swiper-container gallery-top">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide has-background">
                                <div class="background">
                                    <img src="../uploads/<?php echo $row7['Photo'];?>" alt="">
                                </div>
                            </div>
                            <?php 
                                 $id = $_GET['id'];
                                  $sql2 = "SELECT * FROM product_images WHERE ProductId='$id'";
                                  $res2 = $conn->query($sql2);
                                  $rncnt = mysqli_num_rows($res2);
                                  if($rncnt > 0){
                                    while($row2 = $res2->fetch_assoc()){?>
                            <div class="swiper-slide has-background">
                                <div class="background">
                                    <img src="../uploads/<?php echo $row2['Files'];?>" alt="">
                                </div>
                            </div>
                            <?php }} ?>
                            
                        </div>
                        <!-- Add Arrows -->
                        <div class="swiper-button-next swiper-button-white"></div>
                        <div class="swiper-button-prev swiper-button-white"></div>
                    </div>
                    <div class="swiper-container gallery-thumbs">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide has-background">
                                <div class="background">
                                    <img src="../uploads/<?php echo $row7['Photo'];?>" alt="">
                                </div>
                            </div>
                            <?php 
                                 $id = $_GET['id'];
                                  $sql2 = "SELECT * FROM product_images WHERE ProductId='$id'";
                                  $res2 = $conn->query($sql2);
                                  $rncnt = mysqli_num_rows($res2);
                                  if($rncnt > 0){
                                    while($row2 = $res2->fetch_assoc()){?>
                            <div class="swiper-slide has-background">
                                <div class="background">
                                    <img src="../uploads/<?php echo $row2['Files'];?>" alt="">
                                </div>
                            </div>
                            <?php }} ?>
                            
                        </div>
                    </div>

                </div>
            </div>
            </div>
            <div class="container">
                <p class="text-mute mb-1"><?php echo $row7['Category']; ?></p>
                <h5><?php echo $row7["ProductName"]; ?></h5>
                <h5 class="text-success"><span id="MinPrice3">&#8377; <?php echo number_format($row7["MinPrice"],2); ?></span>
                                <?php if($row7["OfferPer"] == '0.00') {} else{ ?>
                                &nbsp;&nbsp;<!--<del>&#8377; <?php echo number_format($row7["MaxPrice"],2); ?></del>--><?php } ?>
                                <?php if(isset($_SESSION['User'])){
                                     if($DiscPer != 0){
                                    ?>
                                <span style='color:red;font-size:12px;'>(<?php echo substr($DiscPer,0,-3)."%";?> Discount On Every Product)</span>
                                <?php }} ?>
                                </h5>
                <hr>
                <!--<div class="row">
                    <div class="col">
                        <div class="input-group cart-count cart-count-lg rounded">
                            <div class="input-group-prepend">
                                <button class="btn btn-outline-secondary rounded" type="button" onclick="changeMinus();">-</button>
                            </div>
                            <input type="text" class="form-control rounded" placeholder="1" readonly="" id="qntno" value="1" min="1">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary rounded" type="button" onclick="changePlus();">+</button>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-default rounded" id="add-cart"><i class="material-icons">local_mall</i> Add to Cart</button>
                    </div>
                </div>
                <hr>-->
                <p class="text-mute"><?php echo $row7["Details"]; ?></p>
                <br>

 <input type="hidden" id="pid" value="<?php echo $_GET['id']; ?>">
  <input type="hidden" id="sizeid" value="<?php echo $row7['SizeId']; ?>">
  <input type="hidden" id="ramid" value="<?php echo $row7['RamId']; ?>">
   <input type="hidden" id="storageid" value="<?php echo $row7['StorageId']; ?>">
   <input type="hidden" id="code" value="<?php echo $row7['code']; ?>">
<input type="hidden" id="prd_price" value="<?php echo $row7['MinPrice']; ?>">
               <!-- <h6 class="page-title">Customer Review</h6>
                <div class="card my-3">
                    <div class="card-body">
                        <h6>"Best product item and taste very fast and excellent packaging I am loving it. Thats awesome set for packaging with sppon and fork."</h6>
                    </div>
                    <div class="card-footer">
                        <div class="media">
                            <div class="avatar avatar-40 rounded-circle mr-2">
                                <figure class="background">
                                    <img src="img/image4.jpg" alt="Generic placeholder image">
                                </figure>
                            </div>
                            <div class="media-body">
                                <h6 class="mb-1">Johnson Johny</h6>
                                <p class="text-mute small">
                                    Vennama, USA | 
                                    <span class="text-warning material-icons small vm">star</span>
                                    <span class="text-warning material-icons small vm">star</span>
                                    <span class="text-warning material-icons small vm">star</span>
                                    <span class="text-warning material-icons small vm">star</span>
                                    <span class="text-warning material-icons small vm">star</span> 
                                    5.0 </p>
                            </div>
                        </div>
                    </div>
                </div>-->
            </div>

            <!-- page content ends -->
        </div>
    </main>

<div class="footer" style="height: 55px;">
    <div class="row no-gutters" style="padding-top: 10px;">
                     <div class="col-auto">
                        <div class="input-group cart-count cart-count-lg rounded">
                            <div class="input-group-prepend">
                                <button class="btn btn-outline-secondary rounded" type="button" onclick="changeMinus();">-</button>
                            </div>
                            <input type="text" class="form-control rounded" placeholder="1" readonly="" id="qntno" value="1" min="1">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary rounded" type="button" onclick="changePlus();">+</button>
                            </div>
                        </div>
                    </div>
                    <?php if($SizeId!='0'){?>
                    &nbsp;&nbsp;&nbsp;
                     <div class="col-auto">
                        <?php if($row7['SizeId']=='0'){}else if($row7['CatId'] != 0){?>
                              
                                 <select class="form-control" data-placeholder="Sort Items" onchange="getDiffSize(this.value,<?php echo $row7["id"];?>)">
                             <?php 
                                      if($row7['SizeId']=='0') {} else if($row7['CatId'] != 0){?>
                                   <option value="<?php echo $row7['SizeId'];?>" selected><?php echo $row5['Name'];?></option>
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
                    </div><?php } ?>&nbsp;&nbsp;&nbsp;
                    <div class="col-auto" id="cart_btn">
                        <button class="btn btn-default rounded" id="add-cart"><i class="material-icons">local_mall</i> Book Hall</button>
                    </div>
                </div>
      <!--  <div class="row no-gutters justify-content-center">
            <div class="col-auto">
                <a href="index.php">
                    <i class="material-icons">home</i>
                    <p>Home</p>
                </a>
            </div>
          
        </div>-->
    </div>
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
      $('#sizeid').val(id);
    var sizeid = $('#sizeid').val();
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
        $('#prd_price').val(MinPrice);
      if(Stock == 1){
$('#MinPrice3').html('&#8377;'+MinPrice2);
$('#cart_btn').show();
      }
      else{
toastr.error('Currently, This Size Of Product is Not Available!', 'Error', {timeOut: 3000});
$('#cart_btn').hide();
      }
    }
                  });
   } 
    
         $(document).ready(function(){
$(document).on('click','#add-cart', function(event){
event.preventDefault();
var action = "shop_cart";
var quantity = $('#qntno').val();
var code = $('#code').val();
var pid = $('#pid').val();
var sizeid = $('#sizeid').val();
var ramid = $('#ramid').val();
var storageid = $('#storageid').val();
var colorid = $('#colorid').val();
var price = $('#prd_price').val();

$.ajax({
  type: "POST",
  url: "ajax_files/ajax_hall_cart.php",
  data: {action:action,pid:pid,quantity:quantity,code:code,sizeid:sizeid,ramid:ramid,storageid:storageid,colorid:colorid,price:price},
   beforeSend:function(){
     $('#add-cart').attr('disabled','disabled');
     $('#add-cart').text('Adding...');
    },
  success: function(data){
       window.location.href="book-hall.php";
       /*toastr.success('Product Added to Cart', 'Success', {timeOut: 1000});
       $('#add-cart').attr('disabled',false);
                       $('#add-cart').html('<i class="material-icons">local_mall</i> Add Cart');*/
      }
});

   });
    });
        function changePlus(){
            var qntno = $('#qntno').val();
            var result = Number(qntno) + 1;
            $('#qntno').val(result);
         }
        function changeMinus(){
            var qntno = $('#qntno').val();
            if(qntno == 1){}
             else{    
                 var result = Number(qntno) - 1;
                 $('#qntno').val(result);
                 }
            }
        </script>
</body>

</html>
