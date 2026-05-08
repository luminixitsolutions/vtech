<?php session_start();
require_once 'config.php';
$user_id = $_SESSION['User']['id'];
$PageName = "Cart";
$Page = "Shop"; 
//echo "<pre>";print_r($_SESSION["cart_item"]);
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
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <?php include_once 'back-header.php';?>

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
                                        $total_price3_3 += ($product["price"]*$product["quantity"]);
                                        $total_price =  number_format($total_price3,2);
                                        if($row5['Name'] == ''){
                                          $attr_name = "";
                                        }
                                        else{
                                        $attr_name = " (".$row5['Name'].")";
                                        }

                                        $SubCatId = $row['SubCatId'];
                                        /*$sql11 = "SELECT Name FROM sub_category2 WHERE id='$SubCatId'";
                                        $row11 = getRecord($sql11);*/

                                 $totsaving+=$row["MaxPrice"] - $product['price'];   
                                 $TotServiceFee1 = $ServiceFee;  
                                 $TotService_Fees1 = $Service_Fee; 
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
                        <p>
                            <?php if($row['MaxPrice'] != $product['price']){?>
                            <span class="text-danger"><del>&#8377;<?php echo number_format($row["MaxPrice"],2); ?></del></span>
                            <?php } ?>
                            <span class="text-success">&#8377;<?php echo number_format($product["price"],2); ?></span>
                           <?php if($attr_name != '') {?> 
                         <span class="text-secondary small"><strong>Size: </strong> <?php echo $attr_name; ?></span>
                         <?php } if($row["Points"] != 0){?>
                        <!--  <span class="text-secondary small"><strong>Points:</strong> <?php echo $row["Points"]; ?></span> -->
                         <?php } ?>
                     </p>
                    </div>
                    <div class="align-self-center">
                        <?php if($row['MinQty'] == 1){?>
                        <div class="input-group cart-count">
                            <div class="input-group-prepend">
                                <button class="btn btn-outline-secondary" type="button" disabled>-</button>
                            </div>
                            <input type="text" class="form-control" placeholder="1" value="<?php echo $product["quantity"]; ?>"id="qntno<?php echo $row["id"]; ?>" min="1" readonly="">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" disabled>+</button>
                            </div>
                            
                        </div>
                        <?php } else{?>
                        <div class="input-group cart-count">
                            <div class="input-group-prepend">
                                <button class="btn btn-outline-secondary" type="button" onclick="changeMinus(<?php echo $row["id"]; ?>);">-</button>
                            </div>
                            <input type="text" class="form-control" placeholder="1" value="<?php echo $product["quantity"]; ?>"id="qntno<?php echo $row["id"]; ?>" min="1" readonly="">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" onclick="changePlus(<?php echo $row["id"]; ?>);">+</button>
                            </div>
                            
                        </div>
                        <?php } ?>
                        <a href="javascript:void(0)" id="<?php echo $product["code"]; ?>" onclick="delete_prod2(this.id)"><i class="fa fa-trash" style="color: red;padding-top: 7px;padding-left: 28px;"></i></a>
                        
                    </div>
                </div>
            <?php }} ?>
                
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
<p style="color: red;font-size: 16px;text-align: center;">Subscription Products</p>
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

                                        $SubCatId = $row['SubCatId'];
                                        /*$sql11 = "SELECT Name FROM sub_category2 WHERE id='$SubCatId'";
                                        $row11 = getRecord($sql11);*/
                                        
                                        $totsaving2+=$row["MaxPrice"] - $product['price'];
                             $TotServiceFee2+= $ServiceFeeSub*$product["Recharge"];
                             $TotService_Fees2+= $Service_Fee_Sub*$product["Recharge"];
                             if($product["Recharge"] >= 30){
                                $SevenDaysFreeAmt+= ($product["price"]*$product["quantity"]*7);
                             }
                             
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
                        <p>
                            <?php if($row['MaxPrice'] != $product['price']){?>
                            <span class="text-danger"><del>&#8377;<?php echo number_format($row["MaxPrice"],2); ?></del></span>
                            <?php } ?>
                            <span class="text-success">&#8377;<?php echo number_format($product["price"],2); ?></span>
                           <?php if($attr_name != '') {?> 
                         <span class="text-secondary small"><strong>Size: </strong><?php echo $attr_name; ?></span> <?php } if($row["Points"] != 0){?>
                        <!--  <span class="text-secondary small"><strong>Points: </strong><?php echo $row["Points"]; ?></span> -->
                         <?php } ?><br>
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
            //echo $SevenDaysFreeAmt;
            $TotService_Fees = $TotService_Fees1 + $TotService_Fees2;
if(isset($_SESSION['prime'])){
                $TotService_Fees = 0;
                $PrimeFee = $_SESSION['prime'];
            }
            else{
                $TotService_Fees = $TotService_Fees1 + $TotService_Fees2;
                $PrimeFee = 0;
            }
                $sql22 = "SELECT * FROM tbl_service_fee ";
                 $res22 = $conn->query($sql22);
                 $rncnt22 = mysqli_num_rows($res22);
                $row22 = $res22->fetch_assoc();
                $OrderPrice = $row22['OrderPrice']; 
                if($total_price3_3 <= $OrderPrice){   
                    if($PackageStatus==1){
                        $TotServiceFee1 = 0;
                        $TotServiceFee2 = $TotServiceFee2;
                        $TotServiceFee = $TotServiceFee1 + $TotServiceFee2;
                        $ServiceFee = '0.00';
                    }
                    else{
                        $TotServiceFee = $TotServiceFee1 + $TotServiceFee2;   
                   $ServiceFee = $row22['Fee']; 
                    }
                } 
                else{ 
                    $TotServiceFee1 = 0;
                    $TotServiceFee2 = $TotServiceFee2;
                    $TotServiceFee = $TotServiceFee1 + $TotServiceFee2;
                  $ServiceFee = '0.00';
               } 
                $TotServiceFee = $TotService_Fees;
               if(!empty($_SESSION["cart_item"]) || !empty($_SESSION["cart_item2"])){
                  $netamt = $total_price3+$ShippingPrice; 
                  
                if($Member == 0){
                  /*if($netamt >= 1200){
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
 <input type="hidden" id="coupon" value="<?php echo $_REQUEST['coupon'];?>">
<?php
if($_REQUEST['coupon']!=''){
$_SESSION['CouponCode'] = $_REQUEST['coupon'];
}
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
    //$CouponAmt = $GrandTotal * ($Discount/100);
    $CouponAmt = $row['Discount'];
    if($GrandTotal >= $MinOrder){
    $sql3 = "SELECT * FROM tbl_applied_code WHERE UserId='$UserId' AND Code='$CouponCode'";
    $rncnt3 = getRow($sql3);
    $sql2 = "SELECT * FROM tbl_coupon_code WHERE Code='$CouponCode' AND FromDate<='$CurrDate' AND ToDate>='$CurrDate'";
    $rncnt2 = getRow($sql2);
    if($rncnt3 > 0){
        unset($_SESSION['CouponCode']);
        unset($_SESSION['CouponAmt']);
        $_SESSION['CouponMessage'] = "<span style='color:red;'>Coupon Code Already Used!</span>";
    }
    else if($rncnt2 > 0){
        $_SESSION['CouponCode'] = $CouponCode;
        $_SESSION['CouponAmt'] = $CouponAmt;
        $_SESSION['CouponMessage'] = "<span style='color:#46d646;'>Coupon Applied Successfully!</span>";
    }
    else{
        unset($_SESSION['CouponCode']);
        unset($_SESSION['CouponAmt']);
         $_SESSION['CouponMessage'] = "<span style='color:red;'>Coupon Code Expired!</span>";
    }
}
else{
    unset($_SESSION['CouponCode']);
    unset($_SESSION['CouponAmt']);
    $_SESSION['CouponMessage'] = "<span style='color:red;'>Min Purchase Order Amount Must Be Greater Than ₹".$MinOrder."</span>";
   
}
}
else{
    unset($_SESSION['CouponCode']);
    unset($_SESSION['CouponAmt']);
     $_SESSION['CouponMessage'] = "<span style='color:red;'>Invalid Coupon Code!</span>";
}

             if($_REQUEST['action'] == 'prime'){
    $id = $_REQUEST['id'];
    $addid = $_REQUEST['addid'];
    $userid = $_REQUEST['userid'];
    $amount = $_REQUEST['amount'];
    $_SESSION['prime'] = $amount;

    echo "<script>window.location.href='shopping-cart.php';</script>";
}

if($_REQUEST['action'] == 'removeprime'){
    
    unset($_SESSION['prime']);

    echo "<script>window.location.href='shopping-cart.php';</script>";
}
?>
            <div class="container mb-4">
                <div class="row my-3 h6 font-weight-normal">
                    <div class="col">Sub total</div>
                    <div class="col text-right text-mute" id="subtotal">&#8377;<?php echo number_format($total_price3,2); ?></div>
                </div>
                 <div class="row my-3 h6 font-weight-normal" style="color:#1bdd1b;font-style: italic;">
                    <div class="col">Total Saving</div>
                    <div class="col text-right text-mute" id="subtotal">&#8377;<?php echo number_format($totsaving+$totsaving2,2); ?></div>
                </div>
                <?php if($PackageStatus==1 && $Member == 1){
                    if(isset($_SESSION["cart_item2"])){
                    if($rncnt_22 > 0){
                        $SevenDays_FreeAmt = 0;
                    } else{
                        $SevenDays_FreeAmt = $SevenDaysFreeAmt;
                        ?>
                    <div class="row my-3 h6 font-weight-normal" style="color:orange;">
                    <div class="col">7 Days Free</div>
                    <div class="col text-right text-mute" style="color:red;">- &#8377;<?php echo number_format($SevenDays_FreeAmt,2); ?></div>
                </div> <?php } } ?>
                    <!-- <div class="row my-3 h6 font-weight-normal">
                    <div class="col">Service Fee</div>
                    <div class="col text-right text-mute"><del>&#8377;<?php echo number_format($TotService_Fees,2); ?></del></div>
                </div> 
-->
                <?php } else{?>
              <!-- <div class="row my-3 h6 font-weight-normal">
                    <div class="col">Service Fee</div>
                    <div class="col text-right text-mute" id="shipping_price">&#8377;<?php echo number_format($TotServiceFee,2); ?></div>
                </div> -->
                 <?php 
                if(isset($_SESSION['prime'])){?>
              <!--  <div class="row my-3 h6 font-weight-normal">
                    <div class="col">Prime Member Fee</div>
                    <div class="col text-right text-mute">&#8377;<?php echo number_format($_SESSION['prime'],2); ?>&nbsp;<a href="<?php echo $_SERVER['PHP_SELF']; ?>?id=1&action=removeprime"><i class="fa fa-trash" style="color: red;padding-top: 7px;"></i></a></div>
                </div>-->
            <?php } ?>
                <?php 
                if(isset($_SESSION['prime'])){}
                    else{
                $sql11 = "SELECT id,Amount FROM tbl_packages WHERE id=1";
        $row11 = getRecord($sql11);
        ?>
               <!-- <div class="row my-3 h6 font-weight-normal">
                    <div class="col">Join Prime Member (&#8377; <?php echo $row11['Amount'];?>) & Save Service Fee</div>
                    <a href="<?php echo $_SERVER['PHP_SELF']; ?>?id=1&action=prime&addid=<?php echo $_REQUEST['addid'];?>&userid=<?php echo $_REQUEST['userid'];?>&amount=<?php echo $row11['Amount'];?>" class="col text-right text-mute" style="color: #ff3399;">Add </a>
                </div> -->
                <?php } } ?>
                
                
              
                <!-- <div class="row my-3 h6 font-weight-normal" style="color: red;">
                    <div class="col"><?php echo $AccName;?> Discount <?php echo $per_disc;?></div>
                    <div class="col text-right text-mute" id="discount_price">- &#8377;<?php echo number_format($disc,2); ?></div>
                </div> -->
                
                 <div class="row my-3 h6 font-weight-normal" style="padding-left: 10px;padding-right: 10px;">
              <!--  <div class="input-group mb-3">
                    <input type="text" name="CouponCode" id="CouponCode" class="form-control" value="<?php echo $_SESSION['CouponCode']; ?>" placeholder="Referral Code" oninput="applyCoupon()" <?php if(isset($_SESSION['CouponCode'])){?> disabled <?php } ?>>
                    <div class="input-group-prepend">
                        <span class="input-group-text" onclick="removeCoupon()"><i class="fa fa-times" aria-hidden="true"></i></span>
                    </div>
                </div>-->
                
               
                 <span id="error_msg" style="color:red;padding-left: 10px;"></span>
                    <span id="success_msg" style="color:#46d646;"></span>

                    <?php if($_REQUEST['coupon']!=''){ echo $_SESSION['CouponMessage'];}?>
                </div>
               <!-- <div class="container mb-4" style="padding-right: 1px;padding-left: 1px;">
                        <div class="card border-0 mb-4">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto pr-0">
                                       <div class="avatar avatar-50 border-0 bg-danger-light rounded-circle text-danger">
                                    <i class="material-icons vm text-template">local_offer</i>
                                </div>
                                    </div>
                                    <div class="col-5 align-self-center">
                                        <h6 class="mb-1">Apply Coupon</h6>
                                        <p class="small text-secondary">Exciting offers for you</p>
                                    </div>
                                    <div class="col-auto align-self-center border-left">
                                       
                                         <p class="small text-secondary"><a href="our-offers.php?pageval=shopcart" class="col text-right text-mute" style="color: blue;">VIEW OFFERS</a></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>-->
                    
                <hr>
                <div class="row h6 font-weight-bold">
                    <div class="col">Net Payable</div>
                    <div class="col text-right text-mute" id="grand_total">&#8377;<?php echo number_format($netamt+$TotServiceFee-$SevenDays_FreeAmt+$PrimeFee,2); ?></div>
                </div>
                <hr>

               
                    

            <div class="container">
               <a href="checkout.php" class="btn btn-default btn-block rounded">Checkout</a>
            </div><br><br>
            </form>
          <?php } else if(!isset($_SESSION["cart_item"]) && !isset($_SESSION["cart_item2"])) {?>
            
            <div class="container">
              <h5 style="color: red; text-align:center;">Your Shopping Cart Is Empty!!</h5>
                <a href="home.php?city_id=<?php echo $_SESSION['Location'];?>" class="btn btn-default btn-block rounded"><span class="material-icons">local_mall</span> Shop Now</a>
            </div>
          <?php } ?>
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
        var addid = $('#addid').val();
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
         window.location.href="place-order.php?addid="+addid+"&userid="+user_id;
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
      else if(Status == 5){
        $('#error_msg').html("Coupon Code Not Applied For This Products");
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
            $.ajax({
  type: "POST",
  url: "ajax_files/ajax_shop_cart.php",
  data: {action:action,pid:id,quantity:quantity,code:code,price:price},
  success: function(data){
    //alert(data);
    window.location.href="shopping-cart.php";
    res = JSON.parse(data);
    var PrdPrice = res.Price2;
    var total_price = res.total_price;
    var ShippingPrice = res.ShippingPrice;
    var sub_total = res.sub_total;
    var discount = res.discount;
    $('#grand_total').html('&#8377;'+total_price);
    $('#subtotal').html('&#8377;'+sub_total);
    $('#shipping_price').html('&#8377;'+ShippingPrice);
    $('#discount_price').html('- &#8377;'+discount);

     show_cart();
     sub_show_cart();
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
    window.location.href="shopping-cart.php";
    res = JSON.parse(data);
    var cnt_val = res.cnt_val;
    var total_price = res.total_price;
    var ShippingPrice = res.ShippingPrice;
    var sub_total = res.sub_total;
    var discount = res.discount;    
       $('#grand_total').html('&#8377;'+total_price);
       $('#subtotal').html('&#8377;'+sub_total);
       $('#shipping_price').html('&#8377;'+ShippingPrice);
       $('#discount_price').html('- &#8377;'+discount);
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
    </script>
</body>

</html>
