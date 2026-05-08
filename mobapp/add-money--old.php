<?php session_start();
require_once 'config.php';
require_once 'auth.php';
$PageName = "Add Money";
$WallMsg = "NotShow";
$UserId = $_SESSION['User']['id'];
$sql10 = "SELECT * FROM customers WHERE id='$UserId'";
$row10 = getRecord($sql10);
$Name = $row10['Fname']." ".$row10['Lname'];
$Phone1 = $row10['Phone'];
$EmailId = $row10['EmailId'];
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
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <!-- Fixed navbar -->
        <?php include_once 'back-header.php'; ?> 
    
    <?php
   if(isset($_POST['submit'])){
    $Amount = trim($_POST['Amount']);
    $pageval = addslashes(trim($_POST['pageval']));
    $addid = $_POST['addid'];
    ?>
    <script type="text/javascript">
        window.location.href="instamojo/pay2.php?userid=<?php echo $user_id;?>&name=<?php echo $Name;?>&phone=<?php echo $Phone1;?>&email=<?php echo $EmailId;?>&amount=<?php echo $Amount;?>&pageval=<?php echo $pageval;?>&addid=<?php echo $addid;?>";
    </script>
   <?php }
   ?>    
        <div class="main-container">
            <div class="container mb-4">
                <form action="" method="post" id="validation-form" autocomplete="off">
                <input type="hidden" name="id" value="<?php echo $user_id; ?>" id="userid"> 
                <input type="hidden" name="pageval" value="<?php echo $_GET['page']; ?>" id="pageval"> 
                <input type="hidden" name="addid" value="<?php echo $_GET['addid']; ?>" id="addid"> 
                <p class="text-center text-secondary mb-1">Enter Amount to Add</p>
                <div class="form-group mb-1" style="padding-top:15px;padding-bottom:15px;">
                    <input type="number" style="background-color: #fff;" class="form-control large-gift-card" value="500" name="Amount" id="Amount" placeholder="00.00">
                </div>
                <p class="text-center text-secondary mb-4" style="font-weight:bold;font-size:17px;">Available: &#8377;<?php echo number_format($mybalance,2);?> </p>
                
                 <div class="row mb-4">
                    <div class="col-4">
                        <button type="button" class="btn btn-outline-default px-2 btn-block rounded" style="font-size: 13px;" onclick="getMoney(500)">&#8377;500</button>
                    </div>
                    <div class="col-4">
                        <button type="button" class="btn btn-outline-default px-2 btn-block rounded" style="font-size: 13px;" onclick="getMoney(1000)">&#8377;1000</button>
                    </div>
                     <div class="col-4">
                        <button type="button" class="btn btn-outline-default px-2 btn-block rounded" style="font-size: 13px;" onclick="getMoney(2000)">&#8377;2000</button>
                    </div>
                </div>
                <div class="container text-center">
                <button type="submit" name="submit" class="btn btn-default mb-2 mx-auto rounded" style="background-color: #e36012;">Add Money</button>
            </div>
               </form>
            </div>
        </div>
    </main>

    <!-- footer-->
    

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
   
   <script>
       function getMoney(val){
           $('#Amount').val(val);
       }
   </script>
</body>

</html>
