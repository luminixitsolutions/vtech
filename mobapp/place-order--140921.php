<?php session_start();
require_once 'config.php';
$user_id = $_SESSION['User']['id'];
$PageName = "Order Summary";
$Page = "Shop"; 
//echo "<pre>";print_r($_SESSION["cart_item"]);
if(empty($_SESSION["cart_item"])){
?>
<script type="text/javascript">
    //window.location.href="shopping-cart.php";
</script>
<?php } ?>
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
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
      <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <?php include_once 'back-header.php'; ?>

<style type="text/css">
.password-tog-info {
display: inline-block;
cursor: pointer;
font-size: 12px;
font-weight: 600;
position: absolute;
right: 50px;
top: 30px;
text-transform: uppercase;
z-index: 2;
}
</style>
        <!-- page content start -->

        <div class="main-container">
            <div class="container" id="show_prod">
                <?php   
                                    foreach ($_SESSION["cart_item"] as $product){
                                        if($product['Type'] == 'Cart'){
                                        $Prod_code = $product["code"];
                                        $SizeId = $product['sizeid'];
                                        
                                        $sql = "SELECT * FROM products WHERE code = '$Prod_code'";
                                        $result = $conn->query($sql);
                                        $row = $result->fetch_assoc();
                                         $sql5 = "SELECT * FROM attribute_value WHERE id='$SizeId'";
                                        $res5 = $conn->query($sql5);
                                        $row5 = $res5->fetch_assoc();
                                        $total_price2 = ($product["price"]*$product["quantity"]);
                                        $total_price3 += ($product["price"]*$product["quantity"]);
                                        $total_price =  number_format($total_price3,2);
                                        if($row5['Name'] == ''){
                                          $attr_name = "";
                                        }
                                        else{
                                        $attr_name = " (".$row5['Name'].")";
                                        }

                                        $SubCatId = $row['SubCatId'];
                                        $sql11 = "SELECT Name FROM sub_category2 WHERE id='$SubCatId'";
                                        $row11 = getRecord($sql11);


                                    ?> 
                <div class="media mb-4 w-100">
                    <div class="avatar avatar-60 mr-3 has-background rounded">
                     
                        <a href="product-details.php" class="background">
                             <?php if($row["Photo"] == '') {?>
                  <img src="no_image.jpg" style="width: 60px;height: 60px;"> 
                 <?php } else if(file_exists('../uploads/'.$row["Photo"])){?>
                 <img src="../uploads/<?php echo $row["Photo"];?>" alt="" style="width: 60px;height: 60px;">
                  <?php }  else{?>
                 <img src="no_image.jpg" style="width: 60px;height: 60px;"> 
             <?php } ?>
                            <img src="img/image9.jpg" class="" alt="">
                        </a>
                    </div>
                     <input type="hidden" id="price<?php echo $row["id"]; ?>" value="<?php echo $product["price"]; ?>">
                     <input type="hidden" id="code<?php echo $row["id"]; ?>" value="<?php echo $product["code"]; ?>">
                    <div class="media-body">
                        <small class="text-secondary"><?php echo $row11['Name']; ?></small>
                        <a href="#">
                            <p class="mb-1"><?php echo $row["ProductName"]; ?></p>
                        </a>
                        <p><span class="text-success">&#8377;<?php echo number_format($product["price"],2); ?></span>
                           <?php if($attr_name != '') {?> 
                         <span class="text-secondary small">Size: <?php echo $attr_name; ?></span><?php } ?></p>
                    </div>
                    <div class="align-self-center">
                        <div class="input-group cart-count">
                            <div class="input-group-prepend">
                                <button class="btn btn-outline-secondary" type="button" onclick="changeMinus(<?php echo $row["id"]; ?>);">-</button>
                            </div>
                            <input type="text" class="form-control" placeholder="1" value="<?php echo $product["quantity"]; ?>"id="qntno<?php echo $row["id"]; ?>" min="1" readonly="">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" onclick="changePlus(<?php echo $row["id"]; ?>);">+</button>
                            </div>
                            
                        </div>
                        <a href="javascript:void(0)" id="<?php echo $product["code"]; ?>" onclick="delete_prod2(this.id)"><i class="fa fa-trash" style="color: red;padding-top: 7px;padding-left: 28px;"></i></a>
                        
                    </div>
                </div>
            <?php } } ?>
                
            </div>
<?php foreach ($_SESSION["cart_item2"] as $product){
     $i=0;
        if($product['Type'] == 'Subscription'){ 
            
        $i++;}
 }
 //echo $i;
 if($i>0){
 ?>
            <hr>
<p style="color: red;font-size: 20px;text-align: center;"><u>Subscription Products</u></p>
<?php } ?>
             <div class="container" id="show_prod2">
              <?php 
                                    foreach ($_SESSION["cart_item2"] as $product){
                                        if($product['Type'] == 'Subscription'){
                                        $Prod_code = $product["code"];
                                        $SizeId = $product['sizeid'];
                                        
                                        $sql = "SELECT * FROM products WHERE code = '$Prod_code'";
                                        $result = $conn->query($sql);
                                        $row = $result->fetch_assoc();
                                         $sql5 = "SELECT * FROM attribute_value WHERE id='$SizeId'";
                                        $res5 = $conn->query($sql5);
                                        $row5 = $res5->fetch_assoc();
                                        $total_price2 = ($product["price"]*$product["quantity"]*$product["Recharge"]);
                                        $total_price3 += ($product["price"]*$product["quantity"]*$product["Recharge"]);
                                        $total_price =  number_format($total_price3,2);
                                        if($row5['Name'] == ''){
                                          $attr_name = "";
                                        }
                                        else{
                                        $attr_name = " (".$row5['Name'].")";
                                        }

                                        $Recharge = $product['Recharge'];
                                        $SubCatId = $row['SubCatId'];
                                        $sql11 = "SELECT Name FROM sub_category2 WHERE id='$SubCatId'";
                                        $row11 = getRecord($sql11);

                                    ?> 
                <div class="media mb-4 w-100">
                    <div class="avatar avatar-60 mr-3 has-background rounded">
                     
                        <a href="product-details.php" class="background">
                             <?php if($row["Photo"] == '') {?>
                  <img src="no_image.jpg" style="width: 60px;height: 60px;"> 
                 <?php } else if(file_exists('../uploads/'.$row["Photo"])){?>
                 <img src="../uploads/<?php echo $row["Photo"];?>" alt="" style="width: 60px;height: 60px;">
                  <?php }  else{?>
                 <img src="no_image.jpg" style="width: 60px;height: 60px;"> 
             <?php } ?>
                            <img src="img/image9.jpg" class="" alt="">
                        </a>
                    </div>
                     <input type="hidden" id="price<?php echo $row["id"]; ?>" value="<?php echo $product["price"]; ?>">
                     <input type="hidden" id="code<?php echo $row["id"]; ?>" value="<?php echo $product["code"]; ?>">
                    <div class="media-body">
                        <small class="text-secondary"><?php echo $row11['Name']; ?></small>
                        <a href="#">
                            <p class="mb-1"><?php echo $row["ProductName"]; ?></p>

                        </a>
                        <p><span class="text-success">&#8377;<?php echo number_format($product["price"],2); ?></span>
                           <?php if($attr_name != '') {?> 
                         <span class="text-secondary small">Size: <?php echo $attr_name; ?></span><?php } ?><br>
                          <span class="text-secondary small"><?php echo $product["quantity"]; ?> Pkt - <?php echo $product['Repeat']; ?> (<?php echo $product['Recharge']; ?> Days)</span>
                     </p>

                    </div>
                    <div class="align-self-center">
                       <a href="subscribe-product.php?id=<?php echo $row["id"]; ?>"><i class="fa fa-edit"></i> Edit Settings</a><br>
                        <a href="javascript:void(0)" id="<?php echo $product["code"]; ?>" onclick="delete_prod2(this.id)"><i class="fa fa-trash" style="color: red;padding-top: 7px;padding-left: 28px;"></i></a>
                        
                    </div>
                </div>
            <?php } } ?>
                
            </div>
            <!-- <div class="container mb-4">
                <div class="form-group float-label position-relative active mb-0">
                    <div class="bottom-right mb-1">
                        <button class="btn btn-sm btn-success rounded">Apply</button>
                    </div>
                    <input type="text" class="form-control" value="KGIDF000120">
                    <label class="form-control-label">Apply Promo Code</label>
                </div>
            </div> -->
            <!-- <div class="container mb-4">
                <div class="alert alert-success">
                    <div class="media">
                        <div class="icon icon-40 bg-white text-success mr-2 rounded-circle"><i class="material-icons">local_offer</i></div>
                        <div class="media-inner">
                            <h6 class="mb-0 font-weight-normal">
                                <b>10%</b> season discount<br>
                                <small class="text-mute">Offer applied you have saved <b>$ 10.9</b></small>
                            </h6>
                        </div>
                    </div>
                </div>
            </div> -->
            <?php 
                $sql22 = "SELECT * FROM shipping_price WHERE Status = '1'";
                 $res22 = $conn->query($sql22);
                 $rncnt22 = mysqli_num_rows($res22);
                $row22 = $res22->fetch_assoc();
                $OrderPrice = $row22['OrderPrice']; 
                if($total_price3 <= $OrderPrice){
                   $ShippingPrice = $row22['ShippingPrice']; 
                } 
                else{ 
                  $ShippingPrice = '0.00';
               } 
                $netamt = $total_price3+$ShippingPrice; 
                if($Member == 0){
                 /* if($netamt >= 1200){
                    $totnetamt =  $total_price3+$ShippingPrice-700;
                    $disc = 700;
                    $per_disc = "";
                  }
                  else{
                    $totnetamt = $total_price3+$ShippingPrice;  
                    $per_disc = "";
                  }*/
                  $disc = $netamt*($DiscPer/100);
                    $totnetamt = $netamt - $disc;
                    $per_disc = "(".substr($DiscPer,0,-3)."%)";
                }
                else{
                    $disc = $netamt*($DiscPer/100);
                    $totnetamt = $netamt - $disc;
                    $per_disc = "(".substr($DiscPer,0,-3)."%)";
                }
             ?>


<?php

$CurrDate = date('Y-m-d');    
$CouponCode = addslashes(trim($_SESSION['CouponCode']));
$GrandTotal = $netamt;
$UserId = $_SESSION['User']['id'];
$sql = "SELECT * FROM tbl_coupon_code WHERE Code='$CouponCode'";
$rncnt = getRow($sql);
if($rncnt > 0){
    $row = getRecord($sql);
    $FromDate = $row['FromDate'];
    $ToDate = $row['ToDate'];
    $MinOrder = $row['MinOrder'];
    $Discount = $row['Discount'];
    $CouponAmt = $GrandTotal * ($Discount/100);
    if($GrandTotal >= $MinOrder){
    $sql3 = "SELECT * FROM tbl_applied_code WHERE UserId='$UserId' AND Code='$CouponCode'";
    $rncnt3 = getRow($sql3);
    $sql2 = "SELECT * FROM tbl_coupon_code WHERE Code='$CouponCode' AND FromDate<='$CurrDate' AND ToDate>='$CurrDate'";
    $rncnt2 = getRow($sql2);
    if($rncnt3 > 0){
        unset($_SESSION['CouponCode']);
        unset($_SESSION['CouponAmt']);
    }
    else if($rncnt2 > 0){
        $_SESSION['CouponCode'] = $CouponCode;
        $_SESSION['CouponAmt'] = $CouponAmt;
    }
    else{
        unset($_SESSION['CouponCode']);
        unset($_SESSION['CouponAmt']);
    }
}
else{
    unset($_SESSION['CouponCode']);
    unset($_SESSION['CouponAmt']);
}
}
else{
    unset($_SESSION['CouponCode']);
    unset($_SESSION['CouponAmt']);
}
?>             
            <div class="container mb-4">
                <div class="row my-3 h6 font-weight-normal">
                    <div class="col">Sub total</div>
                    <div class="col text-right text-mute" id="subtotal">&#8377;<?php echo number_format($total_price3,2); ?></div>
                </div>
                <div class="row my-3 h6 font-weight-normal">
                    <div class="col">Shipping Charges</div>
                    <div class="col text-right text-mute" id="shipping_price">&#8377;<?php echo number_format($ShippingPrice,2); ?></div>
                </div>

               <div class="row my-3 h6 font-weight-normal" style="padding-left: 10px;padding-right: 10px;">
                <div class="input-group mb-3">
                    <input type="text" name="CouponCode" id="CouponCode" class="form-control" value="<?php echo $_SESSION['CouponCode']; ?>" placeholder="Referral Code" oninput="applyCoupon()" <?php if(isset($_SESSION['CouponCode'])){?> disabled <?php } ?>>
                    <div class="input-group-prepend">
                        <span class="input-group-text" onclick="removeCoupon()"><i class="fa fa-times" aria-hidden="true"></i></span>
                    </div>
                </div>
                 <span id="error_msg" style="color:red;padding-left: 10px;"></span>
                    <span id="success_msg" style="color:#46d646;"></span>
                </div>
                <?php if(isset($_SESSION['CouponCode'])){?>
                <div class="row my-3 h6 font-weight-normal" id="discountshow">
                    <div class="col">Discount</div>
                    <div class="col text-right text-mute" id="discount" style="color:red;">-&#8377;<?php echo number_format($_SESSION['CouponAmt'],2); ?></div>
                </div>
                <?php } ?>
                <div class="row my-3 h6 font-weight-normal" id="discountshow2" style="display:none;">
                    <div class="col">Discount</div>
                    <div class="col text-right text-mute" id="discount2" style="color:red;"></div>
                </div>
              
                <hr>
                <div class="row h6 font-weight-bold">
                    <div class="col">Net Payable</div>
                    <div class="col text-right text-mute" id="grand_total"><?php echo number_format($netamt-$_SESSION['CouponAmt'],2); ?></div>
                </div>
                <hr>
                

                   <form method="post" action="" id="validation-form" enctype="multipart/form-data" autocomplete="off">
               <input type="text" name="CouponAmt" id="CouponAmt" value="<?php echo $_SESSION['CouponAmt']; ?>"> 
                <input type="text" name="Coupon_Code" id="Coupon_Code" value="<?php echo $_SESSION['CouponCode']; ?>"> 
                
                 </div>
                <div class="container mb-4">
                <div class="row">
                   <?php 
                                        $q = "select * from payment_method WHERE Status='1' AND id!=2";
                                        $r = $conn->query($q);
                                        while($rw = $r->fetch_assoc())
                                    {
                                ?>
                            <div class="col-6 col-md-auto">
                                <div class="custom-control custom-switch">
                                    <input type="radio" name="PayType" class="custom-control-input PayType" id="menu-overlay<?php echo $rw['id']; ?>" value="<?php echo $rw['id']; ?>" onclick="PaymentMode(<?php echo $rw['id']; ?>)" <?php if($rw['id'] == '1'){?> checked <?php } ?>>
                                    <label class="custom-control-label" for="menu-overlay<?php echo $rw['id']; ?>" style="width: 85%;height: 25px;"><?php echo $rw['Name']; ?></label>
                                </div>
                            </div>
                            <?php } ?>
                            <input type="hidden" name="PaymentMethod" id="PaymentMethod" value="1">
                        
                        <div class="col-12 col-md-auto wallet" style="padding-top: 30px;text-align: center;display: none;">
                            <a href="add-money.php?page=cart&addid=<?php echo $_GET['addid']; ?>" style="font-size:20px;color: blue;">Add Money into Wallet</a>
                             <p class="text-center text-secondary">Available: &#8377;<?php echo number_format($mybalance,2);?> </p>
                        </div>   
                        </div>
            </div>
            
       
            <input type="hidden" name="user_id" id="user_id" value="<?php echo $_SESSION['User']['id']; ?>">
            <input type="hidden" name="action" value="PlaceOrder">
            <input type="hidden" name="addid" id="addid" value="<?php echo $_GET['addid']; ?>">
            <input type="hidden" name="ShippingCharge" id="ShippingCharge" value="<?php echo $ShippingPrice; ?>"> 
             <input type="hidden" name="Discount" id="Discount" value="<?php echo $_SESSION['CouponAmt']; ?>"> 
            <input type="hidden" name="GrandTotal" id="GrandTotal" value="<?php echo $netamt-$_SESSION['CouponAmt']; ?>"> 
            <div class="container">
                <button type="submit" name="submit" id="place_order" class="btn btn-default btn-block rounded" >Place Order</button>
            </div>
            </form>
        </div>
    </main>
<br><br><br><br>
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

<script type="text/javascript">
    function removeCoupon(){
var action = 'removeCoupon';
var CouponAmt = $('#CouponAmt').val();
var GrandTotal = $('#GrandTotal').val();
 $.ajax({
  type: "POST",
  url: "ajax_files/ajax_shop_cart.php",
   data:{action:action},  
  success: function(data){
    var Grand_Total22 = Number(GrandTotal) + Number(CouponAmt);
    var Grand_Total = parseFloat(Grand_Total22).toFixed(2)
    $('#discountshow').hide();
    $('#CouponCode').attr('disabled',false);
    $('#error_msg').html("");
    $('#success_msg').html("");
    $('#CouponCode').val('');
    $('#Coupon_Code').val('');
    $('#GrandTotal').val(Grand_Total);
    $('#grand_total').html("&#8377;"+Grand_Total);
    $('#Discount').val(0);
    $('#CouponAmt').val('');
    }
     });
    }
function applyCoupon(){
        var CouponCode = $('#CouponCode').val();
        var GrandTotal = $('#GrandTotal').val();
        var user_id = $('#user_id').val();
        var action = 'applyCoupon';
         if(CouponCode.length == 0 || CouponCode.length < 0){
          $('#error_msg').html("");
          $('#success_msg').html("");
          $('#CouponCode').attr('disabled',false);
        }
        else{       
      $.ajax({
  type: "POST",
  url: "ajax_files/ajax_shop_cart.php",
   data:{action:action,CouponCode:CouponCode,user_id:user_id,GrandTotal:GrandTotal},  
  success: function(data){
    var res = JSON.parse(data);
    var Status = res.Status;
    var MinOrder = res.MinOrder;
    var CouponAmt = parseFloat(res.CouponAmt).toFixed(2);
    var Grand_Total22 = Number(GrandTotal) - Number(CouponAmt);
    var Grand_Total = parseFloat(Grand_Total22).toFixed(2)
      if(Status == 1){
        $('#success_msg').html("Coupon Applied Successfully!");
        $('#error_msg').html("");
        $('#CouponCode').attr("disabled",true);
        $('#discountshow2').show();
        $('#discount2').html("-&#8377;"+CouponAmt);
        $('#CouponAmt').val(CouponAmt);
        $('#Coupon_Code').val(CouponCode);
        $('#GrandTotal').val(Grand_Total);
        $('#grand_total').html("&#8377;"+Grand_Total);
      }
      else if(Status == 2){
        $('#error_msg').html("Coupon Code Expired!");
        $('#success_msg').html("");
        $('#discountshow2').hide();
        $('#CouponAmt').val('');
        $('#Coupon_Code').val('');
      }
      else if(Status == 3){
        $('#error_msg').html("Coupon Code Already Used!");
        $('#success_msg').html("");
        $('#discountshow2').hide();
        $('#CouponAmt').val('');
        $('#Coupon_Code').val('');
      }
      else if(Status == 4){
        $('#error_msg').html("Min Purchase Order Amount Must Be Greater Than ₹"+MinOrder);
        $('#success_msg').html("");
        $('#discountshow2').hide();
        $('#CouponAmt').val('');
        $('#Coupon_Code').val('');
      }
      else{
        $('#error_msg').html("Invalid Coupon Code!!");
        $('#success_msg').html("");
        $('#discountshow2').hide();
        $('#CouponAmt').val('');
        $('#Coupon_Code').val('');
      }
  }
  });
  }
    }


  function deliveryType(){
      if($('#ByShop').prop('checked') == true) {
      $('#showdelivery1').hide();
      $('#showdelivery2').show();
      $('#DeliveryMethod').val(2);  
      
    }
    else{
       $('#showdelivery1').show();
      $('#showdelivery2').hide();
      $('#DeliveryMethod').val(1);
    }
  }
</script>

    <script type="text/javascript">
        function checkWallBal(){
            var user_id = $('#user_id').val();
            var GrandTotal = $('#GrandTotal').val();
            var action = "checkWallBal";
              $.ajax({
              url:"ajax_files/ajax_shop_cart.php",
              type:"POST",
              data:{action:action,user_id:user_id},
              success:function(data){
               if(Number(data) < Number(GrandTotal)){
                $('#place_order').attr('disabled','disabled');
               }
               else{
                $('#place_order').attr('disabled',false);
               }
              },
              });
        }
      function PaymentMode(id){
        $('#PaymentMethod').val(id);
        if(id == 3){
            checkWallBal();
            $('.wallet').show();
        }
        else{
            $('.wallet').hide();
        }

    }
        function show_cart(){
    var action = "show_cart";
      $.ajax({
      url:"ajax_files/ajax_shop_cart.php",
      type:"POST",
      data:{action:action},
      success:function(data){
        //alert(data);
        $('#show_prod').html(data);

      },

    });
  }

  function sub_show_cart(){
    var action = "sub_show_cart";
      $.ajax({
      url:"ajax_files/ajax_shop_cart.php",
      type:"POST",
      data:{action:action},
      success:function(data){
        //alert(data);
        $('#show_prod2').html(data);
      },

    });
  }

        function changeQty(quantity,price,code,id){
    var action = "shop_cart2";
    var user_id = $('#user_id').val();
          var addid = $('#addid').val();
            $.ajax({
  type: "POST",
  url: "ajax_files/ajax_shop_cart.php",
  data: {action:action,pid:id,quantity:quantity,code:code,price:price},
  success: function(data){
    window.location.href="place-order.php?addid="+addid+"&userid="+user_id;
    res = JSON.parse(data);
    var PrdPrice = res.Price2;
    var total_price = res.total_price;
    var ShippingPrice = res.ShippingPrice;
    var sub_total = res.sub_total;
    var discount = res.discount;
    var discountwo = res.discountwo;    
    $('#grand_total').html('&#8377;'+total_price);
    $('#subtotal').html('&#8377;'+sub_total);
    $('#shipping_price').html('&#8377;'+ShippingPrice);
    $('#discount_price').html('- &#8377;'+discount);
    $('#Discount').val(discountwo);
     show_cart();
     sub_show_cart();
}
});
}

  function delete_prod2(code){
          get_code = code;
          var action = "delete_shop_prod";
          var user_id = $('#user_id').val();
          var addid = $('#addid').val();
if(confirm("Do you want to Delete this product From Cart?")) {
  $.ajax({
  type: "POST",
  url: "ajax_files/ajax_shop_cart.php",
  data: {action:action,code:code},

   success: function(data){
    window.location.href="place-order.php?addid="+addid+"&userid="+user_id;
    res = JSON.parse(data);
    var cnt_val = res.cnt_val;
    var total_price = res.total_price;
    var ShippingPrice = res.ShippingPrice;
    var sub_total = res.sub_total;
    var discount = res.discount;
    var discountwo = res.discountwo;    
       $('#grand_total').html('&#8377;'+total_price);
       $('#subtotal').html('&#8377;'+sub_total);
       $('#shipping_price').html('&#8377;'+ShippingPrice);
       $('#discount_price').html('- &#8377;'+discount);
    $('#Discount').val(discountwo);
      show_cart();
      sub_show_cart();
        
   }
  });
 }
else{}
} 

        function changePlus(id){
            var qntno = $('#qntno'+id).val();
            var result = Number(qntno) + 1;
            $('#qntno'+id).val(result);
            var quantity = $('#qntno'+id).val();
            var price = $('#price'+id).val();
             var code = $('#code'+id).val();
             changeQty(quantity,price,code,id);
            
         }
        function changeMinus(id){
            var qntno = $('#qntno'+id).val();
            if(qntno == 1){}
             else{    
                 var result = Number(qntno) - 1;
                 $('#qntno'+id).val(result);
                 var quantity = $('#qntno'+id).val();
            var price = $('#price'+id).val();
             var code = $('#code'+id).val();
             changeQty(quantity,price,code,id);
                 }
            }

$(document).ready(function(){
  
   $('#validation-form').on('submit', function(e){
      e.preventDefault();  
      $.ajax({
    url:"ajax_files/ajax_shop_cart.php",
    method:"POST",
    data:new FormData(this),  
    contentType:false,  
    processData:false, 
    beforeSend:function(){
     $('#place_order').attr('disabled','disabled');
     $('#place_order').text('Please Wait...');
    },
    success:function(data)
    {
        //alert(data);
        //console.log(data);exit();
      //console.log(data);
      res = JSON.parse(data);
      var status = res.status;
      if(status == 1){
            var email = res.email;
            //console.log(email);
             var msg = res.msg;
             
             toastr.success(msg, 'Success', {timeOut: 5000}); 
         setTimeout(function(){  
            window.location.href="my-orders.php";
        }, 2000);  
        }

        else{

            var userid = res.userid;
            var addid = res.addid;
            var name = res.name;
            var phone = res.phone;
            var email = res.email;
            var amount = res.amount;
            var ship_charge = res.ship_charge;
             var promocode = res.promocode;
            var promoprice = res.promoprice;
            var oid = res.oid;
            var OrderNo = res.OrderNo;
             var url = 'instamojo/pay.php?userid='+userid+'&name='+name+'&phone='+phone+'&email='+email+'&addid='+addid+'&ship_charge='+ship_charge+'&promocode='+promocode+'&promoprice='+promoprice+'&amount='+amount+'&oid='+oid+'&OrderNo='+OrderNo;
            window.location.href=url;
            
        }
      $('#place_order').attr('disabled',false);
      $('#place_order').text('Place Order');
    }
    });
   });

});

    /*function place_order(){
    var action = "PlaceOrder";
    var PaymentMethod = $('#PaymentMethod').val();
    var user_id = $('#user_id').val();
    var addid = $('#addid').val();
    var ShippingCharge = $('#ShippingCharge').val();
    var GrandTotal = $('#GrandTotal').val();
    $.ajax({
    url:"ajax_files/ajax_shop_cart.php",
    method:"POST",
    data : {action:action,PaymentMethod:PaymentMethod,user_id:user_id,addid:addid,ShippingCharge:ShippingCharge,GrandTotal:GrandTotal},
    beforeSend:function(){
     $('#place_order').attr('disabled','disabled');
     $('#place_order').text('Please Wait...');
    },
    success:function(data)
    {
      console.log(data);
      res = JSON.parse(data);
      var status = res.status;
      if(status == 1){
            var email = res.email;
            //console.log(email);
             var msg = res.msg;
             
             toastr.success(msg, 'Success', {timeOut: 5000}); 
         setTimeout(function(){  
            window.location.href="my-orders.php";
        }, 2000);  
        }

        else{

            var userid = res.userid;
            var addid = res.addid;
            var name = res.name;
            var phone = res.phone;
            var email = res.email;
            var amount = res.amount;
            var ship_charge = res.ship_charge;
             var promocode = res.promocode;
            var promoprice = res.promoprice;
             var url = 'instamojo/pay.php?userid='+userid+'&name='+name+'&phone='+phone+'&email='+email+'&addid='+addid+'&ship_charge='+ship_charge+'&promocode='+promocode+'&promoprice='+promoprice+'&amount='+amount;
            window.location.href=url;
            
        }
      $('#place_order').attr('disabled',false);
      $('#place_order').text('Place Order');
    }
    });

    } */       
    </script>
</body>

</html>
