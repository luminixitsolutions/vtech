<!-- screen loader -->
   <!--  <div class="container-fluid h-100 loader-display">
        <div class="row h-100">
            <div class="align-self-center col">
                <div class="logo-loading">
                    <div class="icon icon-100 mb-4 rounded-circle">
                        <img src="img/favicon144.png" alt="" class="w-100">
                    </div>
                    <h4 class="text-default">Near By Store</h4>
                    <p class="text-secondary">Find Best Service Near By You</p>
                    <div class="loader-ellipsis">
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
 -->

<?php 
$UserId = $_SESSION['User']['id'];
$user_id = $_SESSION['User']['id'];
$sql110 = "SELECT * FROM tbl_users WHERE id='$UserId'";
$row110 = getRecord($sql110);
$Name = $row110['Fname']." ".$row110['Lname'];
$Phone = $row110['Phone'];
$EmailId = $row110['EmailId'];
$AccName = $row110['AccName'];
$Roll = $row110['Roll'];
$Member = $row110['Member'];
$PkgDate = $row110['PkgDate'];
$Validity = $row110['Validity'];
$ExpDate = date("d/m/Y", strtotime(str_replace('-', '/',$row110['Validity'])));

$CurrDate = date('Y-m-d');
$diff = strtotime($Validity) - strtotime($CurrDate);
$Days = ($diff / 86400);
$RemainDays2 = $Days + 1;
if($RemainDays2 == 1){
    $RemainDays = "Today";
}
else{
$RemainDays = $RemainDays2." days";
}

$sql11x = "select sum(debit) as debit,sum(credit) as credit from (SELECT (case when Status='Cr' then sum(Amount) else 0 end) as credit,(case when Status='Dr' then sum(Amount) else 0 end) as debit FROM wallet WHERE UserId='$user_id' group by Status) as a";
                        $res11x = $conn->query($sql11x);
                        $row11x = $res11x->fetch_assoc();
$mybalance = $row11x['credit'] - $row11x['debit'];

/*$sql12x = "select sum(debit) as debit,sum(credit) as credit from (SELECT (case when Status='Cr' then sum(Points) else 0 end) as credit,(case when Status='Dr' then sum(Points) else 0 end) as debit FROM tbl_points WHERE UserId='$user_id' group by Status) as a";
$res12x = $conn->query($sql12x);
$row12x = $res12x->fetch_assoc();
$mybalancepnts = $row12x['credit'] - $row12x['debit'];

$sql55 = "SELECT * FROM tbl_offer_percentage WHERE AccountId='7'";
$row55 = getRecord($sql55);
$DiscPer = $row55['Percentage'];

  $Location = $row110['Location'];*/

/*$sql_33 = "SELECT * FROM city WHERE id='$Location'";
$row_33 = getRecord($sql_33);
$FieldName = $row_33['Name'];*/
 ?>
    <!-- menu main -->
    <div class="main-menu">
        <div class="row mb-4 no-gutters">
            <div class="col-auto"><button class="btn btn-link btn-40 btn-close text-white"><span class="material-icons">chevron_left</span></button></div>
            <?php if(isset($_SESSION['User'])) {?>
            <div class="col-auto">
                <div class="avatar avatar-40 rounded-circle position-relative">
                    <figure class="background">
                       <?php 
                        if($row110['Photo'] == ''){
                     ?>
                    <img src="<?php echo $SiteUrl;?>/user_icon.jpg" alt="" style="width: 140px;height: 140px;">
                <?php } else  {?>
                     <img src="<?php echo $Uploadurl;?>/uploads/<?php echo $row110['Photo']; ?>" alt="" style="width: 140px;height: 140px;">
                
                 <?php } ?>
                    </figure>
                </div>
            </div>
            <div class="col pl-3 text-left align-self-center">
                <h6 class="mb-1"><?php echo $Name; ?></h6>
                
                <!--<p class="small text-default-secondary"> (<?php echo number_format($mybalance,2);?>) </p>-->
 
            </div>
        <?php } else{?>
             <div class="col-auto">
                
            </div>
            <div class="col pl-3 text-left align-self-center">
                <h6 class="mb-1"><?php echo $Proj_Title; ?></h6>
                <p class="small text-default-secondary"></p>
            </div>
        <?php } ?>
        </div>
        <div class="menu-container">
              <?php
                if($WallMsg == 'NotShow'){} else{
                    if(isset($_SESSION['User'])){
                    ?>
          <!--  <div class="row">
                <div class="col">
                    <h4 class="mb-1 font-weight-normal">&#8377;<?php echo number_format($mybalance,2);?></h4>
                    <p class="text-default-secondary" style="color:#fff;">Wallet Balance</p>
                </div>
                <div class="col-auto">
                    <a href="add-money.php" class="btn btn-default btn-40 rounded-circle"><i class="material-icons">add</i></a>
                </div>
            </div> -->
        <?php } } ?>
            <ul class="nav nav-pills flex-column ">
                <li class="nav-item">
                    <a class="nav-link active" href="<?php echo $SiteUrl;?>/home.php">
                        <div>
                            <span class="material-icons icon">home</span>
                            Home
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/product-category.php">
                        <div>
                            <span class="material-icons icon">view_list</span>
                            Shop By Category <?php //echo $uid." - ".$city_id;?>
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>
               <!-- <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/product-by-brand.php">
                        <div>
                            <span class="material-icons icon">verified</span>
                            Shop By Brand
                        </div>
                        
                    </a>
                </li>-->
                
                 
                
             <?php if(isset($_SESSION['User'])) {?>    
                <!--  <li class="nav-item">
                    <a class="nav-link" href="my-sub-orders.php">
                        <div>
                            <span class="material-icons icon">date_range</span>
                            My Order Calendar
                        </div>
                        
                    </a>
                </li> -->
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/notifications.php">
                        <div>
                            <span class="material-icons icon">notifications</span>
                            Notifications
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/my-orders.php">
                        <div>
                            <span class="material-icons icon">check_circle</span>
                            My  Order
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>
                
                
                <?php 
                    $refermsg = 'Hey There! Use Referral Code  on your first purchase. Redeem Now! Download and Apply Now:';
                    //echo $refermsg;
                ?>
                <li class="nav-item">
                     <a class="nav-link" href="#" onclick="shareApplication('Hey There! Use Referral Code <?php echo $row110['ReferenceNo'];?> on your first purchase. Redeem Now! Download and Apply Now:');">
                        <div>
                            <span class="material-icons icon">loyalty</span>
                            Refer
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>
                <?php } ?>
                 <!--<li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/pricing.php">
                        <div>
                            <span class="material-icons icon">star_rate</span>
                            Prime Member Access
                        </div>
                      
                    </a>
                </li>-->
                 
                <!-- <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/our-offers.php">
                        <div>
                            <span class="material-icons icon">card_giftcard</span>
                             Offers
                        </div>
                      
                    </a>
                </li>-->
                
                
                
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/faqs.php">
                        <div>
                            <span class="material-icons icon">help_outline</span>
                            FAQ's
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/help-and-support.php">
                        <div>
                            <span class="material-icons icon">support_agent</span>
                            Help & Support
                        </div>
                        
                       <!--<span class="arrow material-icons" style="color:#f2f2f2;">arrow_right</span>-->
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/complaints.php">
                        <div>
                            <span class="material-icons icon">support_agent</span>
                            Complaints
                        </div>
                        
                       <!--<span class="arrow material-icons" style="color:#f2f2f2;">arrow_right</span>-->
                    </a>
                </li>
              <!--  <li class="nav-item">
                    <a class="nav-link" href="recharge-category.php">
                        <div>
                            <span class="material-icons icon">perm_contact_calendar</span>
                            Recharge
                        </div>
                        <span class="arrow material-icons">chevron_right</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <div>
                            <span class="material-icons icon">card_giftcard</span>
                            Service Providers
                        </div>
                        <span class="arrow material-icons">chevron_right</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <div>
                            <span class="material-icons icon">shopping_bag</span>
                            Saloon Booking
                        </div>
                        <span class="arrow material-icons">chevron_right</span>
                    </a>
                </li>-->
                <?php if(!isset($_SESSION['User'])){?>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/login.php">
                        <div>
                            <span class="material-icons icon">login</span>
                            Sign In
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>
               
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $SiteUrl;?>/sign-up.php">
                        <div>
                            <span class="material-icons icon">logout</span>
                            Sign Up
                        </div>
                        <!--<span class="arrow material-icons">chevron_right</span>-->
                    </a>
                </li>
                 <?php } ?>
              <!--  <li class="nav-item">
                    <a class="nav-link" href="https://api.whatsapp.com/send?phone=+919028005022&text=Hi">
                        <div>
                             <i class="fa fa-whatsapp" style="font-size: 20px;width: 32px;padding-left: 3px;"></i>
                            WhatsApp 
                        </div>
                        <span class="arrow material-icons">chevron_right</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="tel:9028005022">
                        <div>
                            <span class="material-icons icon">call</span>
                            Call Us
                        </div>
                        <span class="arrow material-icons">chevron_right</span>
                    </a>
                </li>-->
            </ul>
            <?php if(isset($_SESSION['User'])){?>
            <div class="text-center">
                <a href="JavaScript:Void(0);" onclick="logout()" class="btn btn-outline-danger text-white rounded my-3 mx-auto">Sign out</a>
            </div>
             <?php } ?>
        </div>

    </div>
    <div class="backdrop"></div>
    
        
    <script>
        function shareApplication(msg){
            //alert(msg);
             Android.shareApplication(''+msg+'','Daily Door Services');
             //Android.shareApplication('test','Daily Door Services');
        }
        function logout(){
       Android.logout();
       window.location.href="logout.php";
  }
    </script>
