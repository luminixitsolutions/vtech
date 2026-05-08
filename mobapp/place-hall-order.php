<?php session_start();
require_once 'config.php';
$user_id = $_SESSION['User']['id'];
$PageName = "Order Summary";
$Page = "Shop"; 
if(empty($_SESSION["hall_item"])){
?>
<script type="text/javascript">
    window.location.href="book-hall.php";
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

        <!-- page content start -->

        <div class="main-container">
            <div class="container" id="show_prod">
                <?php   
                                    foreach ($_SESSION["hall_item"] as $product){
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
                        <a href="product.html">
                            <p class="mb-1"><?php echo $row["ProductName"]; ?></p>
                        </a>
                        <p><span class="text-success">&#8377;<?php echo number_format($product["price"],2); ?></span>
                           <?php if($attr_name != '') {?> 
                         <span class="text-secondary small">Hrs: <?php echo $attr_name; ?></span><?php } ?></p>
                    </div>
                   <!-- <div class="align-self-center">
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
                        
                    </div>-->
                </div>
            <?php } ?>
                
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
                  $DiscPer = 25;
                  $disc = $netamt*($DiscPer/100);
                    $totnetamt = $netamt - $disc;
                    $per_disc = "(".substr($DiscPer,0,-3)."%)";
                }
                else{
                    $DiscPer = 25;
                    $disc = $netamt*($DiscPer/100);
                    $totnetamt = $netamt - $disc;
                    $per_disc = "(".substr($DiscPer,0,-3)."%)";
                }
             ?>
            <div class="container mb-4">
                <div class="row my-3 h6 font-weight-normal">
                    <div class="col">Sub total</div>
                    <div class="col text-right text-mute" id="subtotal">&#8377;<?php echo number_format($total_price3,2); ?></div>
                </div>
               
                 <div class="row my-3 h6 font-weight-normal" style="color: red;">
                    <div class="col">Discount 25%</div>
                    <div class="col text-right text-mute" id="discount_price">- &#8377;<?php echo number_format($disc,2); ?></div>
                </div>
               
                <hr>
                <div class="row h6 font-weight-bold">
                    <div class="col">Net Payable</div>
                    <div class="col text-right text-mute" id="grand_total"><?php echo number_format($totnetamt,2); ?></div>
                </div>
                <div class="row my-3 h6 font-weight-normal">
                    <div class="col">Booking Amount<br> <span style="color: red;">(Non Refundable)</span></div>
                    <div class="col text-right text-mute">&#8377;1,000</div>
                </div>
                <hr>
                

                   <form method="post" action="" id="validation-form" enctype="multipart/form-data" autocomplete="off">
             
                    <!-- <input type="hidden" name="RightSph" id="RightSph" class="form-control" value="<?php echo $_SESSION['RightSph'];?>">
                  
                    <input type="hidden" name="RightCyl" class="form-control" value="<?php echo $_SESSION['RightCyl'];?>">
                 
                    <input type="hidden" name="RightAxis" class="form-control" value="<?php echo $_SESSION['RightAxis'];?>">
                  
                    <input type="hidden" name="LeftSph" class="form-control" value="<?php echo $_SESSION['LeftSph'];?>">
                
                    <input type="hidden" name="LeftCyl" class="form-control" value="<?php echo $_SESSION['LeftCyl'];?>">
                 
                    <input type="hidden" name="LeftAxis" class="form-control" value="<?php echo $_SESSION['LeftAxis'];?>">
                
                    <input type="hidden" name="File" class="form-control" value="<?php echo $_SESSION['File'];?>"> -->
                   
                
                 </div>
                <div class="container mb-4">
                <div class="row">
                   <?php 
                                        $q = "select * from payment_method WHERE Status='1' AND id NOT IN(1,3)";
                                        $r = $conn->query($q);
                                        while($rw = $r->fetch_assoc())
                                    {
                                ?>
                            <div class="col-6 col-md-auto">
                                <div class="custom-control custom-switch">
                                    <input type="radio" name="PayType" class="custom-control-input PayType" id="menu-overlay<?php echo $rw['id']; ?>" value="<?php echo $rw['id']; ?>" onclick="PaymentMode(<?php echo $rw['id']; ?>)" <?php if($rw['id'] == '2'){?> checked <?php } ?>>
                                    <label class="custom-control-label" for="menu-overlay<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></label>
                                </div>
                            </div>
                            <?php } ?>
                            <input type="hidden" name="PaymentMethod" id="PaymentMethod" value="2">
                           
                        </div><br>
                        <span style="color: red;">*Booking Amount Not Refundable</span>
            </div>
            
           <!-- <div class="container mb-4">
               <div class="row">
                
                           <div class="col-6 col-md-auto">
                                <div class="custom-control custom-switch">
                                    <input type="radio" name="DeliveryMethod" class="custom-control-input DeliveryType" id="HomeDelivery" value="1" onclick="deliveryType(1)" checked>
                                    <label class="custom-control-label" for="HomeDelivery">Home Delivery</label>
                                </div>
                            </div>

                            <div class="col-6 col-md-auto">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" name="DeliveryMethod" class="custom-control-input DeliveryType" id="ByShop" value="2" onclick="deliveryType()" >
                                    <label class="custom-control-label" for="ByShop">PickUp By Shop</label>
                                </div>
                            </div>
                      
                            <input type="hidden" name="DeliveryMethod" id="DeliveryMethod" value="1">
                           
                        </div>  
                        <hr>
                <?php 
                  $sql21 = "SELECT * FROM tbl_delivery_methods WHERE id=1";
                  $row21 = getRecord($sql21);

                  $sql23 = "SELECT * FROM tbl_delivery_methods WHERE id=2";
                  $row23 = getRecord($sql23);
                 ?>        
                 <span id="showdelivery1"><?php echo $row21['Details']; ?></span>       
                 <span style="display: none;" id="showdelivery2"><?php echo $row23['Address']; ?></span>
                     
            </div>-->
            <input type="hidden" name="user_id" id="user_id" value="<?php echo $_SESSION['User']['id']; ?>">
            <input type="hidden" name="action" value="PlaceOrder">
            <input type="hidden" name="addid" id="addid" value="<?php echo $_GET['addid']; ?>">
            <input type="hidden" name="ShippingCharge" id="ShippingCharge" value="<?php echo $ShippingPrice; ?>"> 
             <input type="hidden" name="Discount" id="Discount" value="<?php echo $disc; ?>"> 
            <input type="hidden" name="GrandTotal" id="GrandTotal" value="<?php echo $totnetamt; ?>"> 
            <div class="container">
                <button type="submit" name="submit" id="place_order" class="btn btn-default btn-block rounded" >Pay &#8377;1,000 & Book</button>
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
      function PaymentMode(id){
        $('#PaymentMethod').val(id);
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

        function changeQty(quantity,price,code,id){
    var action = "shop_cart2";
            $.ajax({
  type: "POST",
  url: "ajax_files/ajax_shop_cart.php",
  data: {action:action,pid:id,quantity:quantity,code:code,price:price},
  success: function(data){
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
}
});
}

  function delete_prod2(code){
          get_code = code;
          var action = "delete_shop_prod";
if(confirm("Do you want to Delete this product From Cart?")) {
  $.ajax({
  type: "POST",
  url: "ajax_files/ajax_shop_cart.php",
  data: {action:action,code:code},

   success: function(data){
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
    url:"ajax_files/ajax_hall_cart.php",
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
       // alert(data);exit();
      //console.log(data);
      res = JSON.parse(data);
      var status = res.status;
      if(status == 1){
            var email = res.email;
            //console.log(email);
             var msg = res.msg;
             
             toastr.success(msg, 'Success', {timeOut: 5000}); 
         setTimeout(function(){  
            window.location.href="my-hall-bookings.php";
        }, 2000);  
        }

        else{
           
            var userid = res.userid;
            var addid = res.addid;
            var name = res.name;
            var phone = res.phone;
            var email = res.email;
            //var amount = res.amount;
            var amount = 1000;
           var discount = res.discount;
            window.location.href='instamojo/pay3.php?userid='+userid+'&name='+name+'&phone='+phone+'&email='+email+'&addid='+addid+'&amount='+amount+'&discount='+discount;
           
            
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
