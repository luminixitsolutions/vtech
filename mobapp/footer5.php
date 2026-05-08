  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <div class="footer">
        <div class="row no-gutters justify-content-center">
            <div class="col-auto">
                <a href="index.php" <?php if($PageName == 'Home') {?> class="active" <?php } ?>>
                    <i class="material-icons">home</i>
                    <p>Home</p>
                </a>
            </div>
           <!-- <div class="col-auto">
                <a href="recharge-category.php" <?php if($Page == 'Recharge') {?> class="active" <?php } ?>>
                    <i class="material-icons">sim_card</i>
                    <p>Recharge</p>
                </a>
            </div>
            <div class="col-auto">
                <a href="#" class="">
                    <i class="material-icons">account_balance_wallet</i>
                    <p>Wallet</p>
                </a>
            </div>-->
             <?php
$session_items = 0;
if(!empty($_SESSION["cart_item"])){
  $session_items = count($_SESSION["cart_item"]);
} 
?>
            <div class="col-auto">
                <a href="shopping-cart.php" <?php if($Page == 'Shop') {?> class="active" <?php } ?>>
                  
            <span style="width:20px; height:20px;" class="counter"><span style="color:#fff;font-size:10px;"><?php echo $session_items; ?></span></span>
                   <i class="fa fa-shopping-cart"  style="font-size:22px;" aria-hidden="true"></i>
                    
                    <p>Cart </p>
                </a>
            </div>
            <div class="col-auto">
                <a href="profile.php" <?php if($PageName == 'Profile') {?> class="active" <?php } ?>>
                    <i class="material-icons">account_circle</i>
                    <p>Profile</p>
                </a>
            </div>
            <div class="col-auto">
                <a href="JavaScript:Void(0);" class="" onclick="onClickWhatsAppCall(+919028005022)">
                    <i class="fa fa-whatsapp" style="font-size: 24px;color:green"></i>
                    <p>Whatsapp</p>
                </a>
            </div>
            <div class="col-auto">
                <a href="JavaScript:Void(0);" class="" onclick="onClickCall(+919028005022)">
                    <i class="material-icons">call</i>
                    <p>Call</p>
                </a>
            </div>
        </div>
    </div>
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
</script>