
 <style>
.phone {
  width: 100%;
  margin: auto;
  display: flex;
  align-items: flex-end;
  position: relative;
  justify-content: center;
   background-color:blue;
}
.phone::before {
  content: "";
  position: absolute;
  width: 84%;
  height: 0px;
  bottom: -10px;
  /*box-shadow: 0 0 25px 9px rgba(255, 0, 0, 0.33), 50px 10px 25px 8px rgba(18, 255, 0, 0.33), -40px 8px 25px 9px rgba(242, 255, 0, 0.33);*/
  left: 0;
  right: 0;
  margin: auto;
 
  
}
.phone::after {
  content: "";
}
.phone_content {
  width: 100%;
background: linear-gradient(5turn, #B83B5D, #B83B5D, #B83B5D);
  overflow: hidden;
  position: absolute;
  border-top: 1px solid #fff;
border-radius: 20px 20px 0 0;
}
.phone_bottom {
  width: 100%;
  height: 56px;
  background: #B83B5D;
  display: flex;
  justify-content: center;
  filter: blur(10px);
}

input {
  display: none;
}



.circle {
  width: 45px;
  height: 45px;
  background: #ff3399;
  position: absolute;
  top: -30px;
  z-index: 1;
  border-radius: 50%;
  left: 0;
  right: 0;
  margin: auto;
  transition: 200ms cubic-bezier(0.14, -0.08, 0.74, 1.4);
}

.indicator {
  width: 60px;
  height: 60px;


/*background-image: linear-gradient(0deg, #f7b0b0, rgba(183, 255, 154, 0)), linear-gradient(0deg, rgba(158, 255, 151, 0.75), rgba(183, 255, 154, 0)), linear-gradient(0deg, #b4fffb, rgba(183, 255, 154, 0));*/

  background-size: cover;
  background-position: 0 1px;
  border-radius: 100%;
  position: absolute;
  left: 0;
  top: -20px;
  right: 0;
  margin: auto;
  transition: 200ms cubic-bezier(0.14, -0.08, 0.74, 1.4);
}

#s1:checked ~ [for=s1] > img {
  top: -55px;
}
#s1:checked ~ .circle,
#s1:checked ~ div div .indicator {
  left: -64%;
}

#s2:checked ~ [for=s2] > img {
  top: -55px;
}
#s2:checked ~ .circle,
#s2:checked ~ div div .indicator {
  left: -32%;
}

#s3:checked ~ [for=s3] > img {
  top: -55px;
}
#s3:checked ~ .circle,
#s3:checked ~ div div .indicator {
  left: 0;
}
#s4:checked ~ [for=s4] > img {
  top: -55px;
}
#s4:checked ~ .circle,
#s4:checked ~ div div .indicator {
  left: 32%;
}
#s5:checked ~ [for=s5] > img {
  top: -55px;
}
#s5:checked ~ .circle,
#s5:checked ~ div div .indicator {
  left: 64%;
}
</style>
 <!-- footer -->
        <div class="footer">
            <div class="phone">
  <input type="radio" name="s" id="s1" <?php if($PageName == 'Category') {?> checked="checked" <?php } ?>>
  <input type="radio" name="s" id="s2" <?php if($Page == 'Shop') {?> checked="checked" <?php } ?>>
  <input type="radio" name="s" id="s3" <?php if($PageName == 'Home') {?> checked="checked" <?php } ?>>
  <input type="radio" name="s" id="s4" <?php if($PageName == 'chat') {?> checked="checked" <?php } ?>>
  <input type="radio" name="s" id="s5" <?php if($PageName == 'Profile') {?> checked="checked" <?php } ?>>
  
  
  <label for="s1"><img src="icons/cat.png" alt=""><a href="product-category.php" style="z-index:20;color:transparent;">HOME</a></label>
  <label for="s2"><img src="icons/cart.png" alt=""><a href="shopping-cart.php" style="z-index:20;color:transparent;">HOME</a></label>
  <label for="s3"><img src="icons/homek.png" alt=""><a href="index.php" style="z-index:20;color:transparent;">HOME</a></label>
  <label for="s4"><img src="icons/search.png" alt=""><a href="#" style="z-index:20;color:transparent;">HOME</a></label>
  <label for="s5"><img src="icons/user.png" alt=""><a href="profile.php" style="z-index:20;color:transparent;">HOME</a></label>
  <div class="circle"></div>
  <div class="phone_content">
    <div class="phone_bottom">
      <span class="indicator"></span>
    </div>
  </div>
</div>
        </div>
        <!-- footer ends -->
    </div>
    <!-- wrapper ends -->
    
 <!--<div class="footer">
        <div class="row no-gutters justify-content-center">
            <div class="col-auto">
                <a href="home.php" style="margin-top: 20px;" <?php if($page == 'home') {?> class='#' <?php } ?>>
                    <i style="font-size:27px;"  class="material-icons">manage_accounts</i>
                    <p>My Account</p>
                </a>
            </div>
            <div class="col-auto">
                <a href="new-matches.php"  style="margin-top: 20px;" <?php if($footpage == 'matches') {?> class='active' <?php } ?>>
                    <!--<span style="height:20px;right: 13px;" class="counter"><span style="color:#fff;font-size:10px;"><?php echo $footer_rncnt;?></span></span>-->
                    <!--<i  style="font-size:27px;"  class="material-icons">portrait</i>
                    <p>Matches</p>
                </a>
            </div>
            <div class="col-auto">
                <a href="inbox.php"  style="margin-top: 20px;" <?php if($footpage == 'inbox') {?> class='active' <?php } ?>>
                     <?php if($rncnt_13 > 0){?>
                   <!-- <span style="width:20px; height:20px;" class="counter"><span style="color:#fff;font-size:10px;"><?php echo $rncnt_13; ?></span></span>--><?php } ?>
                    <!--<i class="fa fa-envelope-o"  style="font-size:27px;" aria-hidden="true"></i>
                    <p>Inbox</p>
                </a>
            </div>
            <div class="col-auto ">
                <a href="chat.php"  style="margin-top: 20px;" <?php if($page == 'chat') {?> class='active' <?php } ?>>
                     <?php if($rncnt_12 > 0){?>    
            <span style="width:20px; height:20px;" class="counter"><span style="color:#fff;font-size:10px;"><?php echo $rncnt_12; ?></span></span><?php } ?>
                   <i class="fa fa-comments-o"  style="font-size:27px;" aria-hidden="true"></i>
                    
                    <p>Chat </p>
                </a>
            </div>
            <?php if($row7['PackageStatus'] == 1){?>
            <div class="">
                <a href="my-profile.php"  style="margin-bottom: 25px;" <?php if($PageName == 'Profile') {?> class='active' <?php } ?>>
  <!--<div style="padding-top:30px;border-radius: 0 0 150px 150px;
background-color: #f0f3f7;
width: 60px;
height: 30px; ">-->
                   <!--<div><i  style="font-size:27px; border-radius: 50%;
background-color: #dc1c74;
width: 45px;
height: 45px; padding-top:9px; color:#fff;" class="material-icons">account_circle</i>
</div>



                    <!--<p>Profile</p>-->
                <!--</a>
            </div>
            <?php } else {?>
            <div class="col-auto">
                <a href="pricing.php" <?php if($page == 'premium') {?> class='active' <?php } ?>>
                    <i class="fa fa-star-o"  style="font-size:27px;" aria-hidden="true"></i>
                    <p>Premium</p>
                </a>
            </div>
            <?php } ?>
        </div>
    </div>-->
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">