<?php session_start();require_once 'config.php';$PageName = "Atootdor";$page="premium";$PageName="Prime Member";
$UserId = $_SESSION['User']['id'];
$sql11 = "SELECT * FROM tbl_users WHERE id='$UserId'";
$row11 = getRecord($sql11);
$Name = $row11['Fname']." ".$row11['Lname'];
$Phone = $row11['Phone'];
$EmailId = $row11['EmailId']; 
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

    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet" id="style">
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay" data-page="homepage">
    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <!-- Fixed navbar -->
        
        
       <?php include_once 'back-header.php'; ?>

        <!-- page content start -->
    

    <!-- Begin page content -->
    <main class="">
        <!-- Fixed navbar -->
        
        
       
        <div class="main-container" style="background-color: #fff;">
            <!-- page content start -->
            

        
            <div class="center">
          

           <!-- Swiper -->
            <div class="container mb-4">
                 
                <div class="swiper-container swiper-users text-center ">
                     <h4 style="color:#000;">Prime Members</h4>
                     <h5 style="color:#000;" class="mb-4">Upgrade to Prime</h5>
                  <div class="swiper-wrapper ">
                    
                       
                       <?php 
        $sql11 = "SELECT * FROM tbl_packages WHERE Status=1";
        $row11 = getList($sql11);
          foreach($row11 as $result){
            if($result['Period'] == '1'){
                  $Period = "Month";
                }
                else{
                  $Period = "Year";
                }
                $PkgId = $result['id'];
           ?> 
                        <div class="swiper-slide">
                            <div class="card">
                                <div class="card-body p-2">
                                    <div style="border-radius: 5%;background: linear-gradient(90deg, rgba(232,37,37,1) 36%, rgba(245,182,51,1) 100%); padding-bottom: 5px; padding-top: 10px;">
                                    <!--<img src="../uploads/<?php echo $result['Photo'];?>" width="150px">-->
                                <?php
                                    $Amount2 = $result['Amount'];
                                    $Amount = round($Amount2 - ($Amount2*($result['Discount']/100)));
                                ?>
                                    <h4 style="padding-top: 5px;color: #000; ">&#8377; <?php echo $Amount2;?>
                                        <!--<del>&#8377; <?php echo substr($result['Amount'],0,-3); ?></del>--></span></h4>
                                    <h5 style="color:#fff;" class="mb-2"><?php echo $result['Duration']." ".$Period; ?> <!--(<?php echo $result['Discount'];?>% Off)--></h5>
                                   
                                    <!--<h4 style="padding-top: 5px;color: #fff; ">&#8377 <?php echo substr($result['Amount'],0,-3); ?></span></h4>
                                    <h5 style="color:#fff;" class="mb-2"><?php echo $result['Duration']." ".$Period; ?> </h5>-->
                                    
                                    </div>

                                    <div style="padding: 10px;width: 270px;">
                                    <?php 
$sql_1 = "SELECT * FROM tbl_package_title WHERE PostId='".$result['id']."'";
$row_1 = getList($sql_1);
foreach($row_1 as $result2){
 ?>
                                    <h6 style="font-size:13px;"><?php echo $result2['Title']; ?></h6>
                                   <?php } ?>
                                    
                                    
                                   <!-- <p class="text-secondary"><small><?php echo $result['Detail1']; ?></small></p>-->
                                    
                                  <!--   <strong><?php echo $result['Title2']; ?></strong><br><br>-->
                                   <!-- <p class="text-secondary"><small><?php echo $result['Detail2']; ?></small></p>-->
                                    
                                  <!--  <strong><?php echo $result['Title3']; ?></strong><br><br>-->
                                   <!-- <p class="text-secondary"><small><?php echo $result['Detail3']; ?></small></p>-->
                                    
                                  <!--  <strong><?php echo $result['Title4']; ?></strong><br><br>-->
                                    <!--<p class="text-secondary"><small><?php echo $result['Detail4']; ?></small></p>-->
                                    
                                  <!--  <strong><?php echo $result['Title5']; ?></strong><br>-->
                                  <!--  <p class="text-secondary"><small><?php echo $result['Detail5']; ?></small></p>-->

                                    <?php if(isset($_SESSION['User'])){?>
                                    <!--<a href="razorpay/pay2.php?amount=<?php echo $Amount; ?>&postid=<?php echo $UserId;?>&name=<?php echo $row110['Fname']." ".$row110['Lname'];?>&phone=<?php echo $row110['Phone'];?>&email=<?php echo $row110['EmailId'];?>&PkgId=<?php echo $result['id']; ?>" class="btn btn-sm btn-default px-4 rounded" id="addtohome">Subscribe Now</a>-->
                                   <!-- <a href="#" class="btn btn-sm btn-default px-4 rounded" id="addtohome" onclick="startPay(<?php echo $Amount.",".$UserId.",".$Phone.",".$result['id'];?>)">Subscribe Now</a>-->
                                    <a href="javascript:void(0)" onclick="startPay(<?php echo $Amount2.",".$UserId.",".$Phone.",".$result['id'];?>)" class="btn btn-sm btn-default px-4 rounded" id="addtohome" >Subscribe Now</a>
                                    <?php } else{?>
                                    <a href="login.php?page=pricing" class="btn btn-sm btn-default px-4 rounded" id="addtohome">Subscribe Now</a>
                                    
                                    <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                         <?php } ?> 
                        
                    </div>
                </div>
            </div>


    </div>
    <br><br>
    <!-- footer-->
     <?php include_once 'footer.php';?>


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

    <!-- PWA app service registration and works -->
    <script src="js/pwa-services.js"></script>

    <!-- page level custom script -->
    <script src="js/app.js"></script>
     <script>
        function startPay(amt,userid,phone,pkgid){
            //alert(amt);
            var flag = 3;
            Android.startPay(''+amt+'',''+userid+'',''+phone+'',''+pkgid+'',''+flag+'')
             //Android.startPay(userid,amt,phone,pkgid);
        }
    </script>
 
</body>

</html>
