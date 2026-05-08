<?php session_start();
require_once '../config.php';
require_once '../auth.php';
$PageName = "Profile";
$UserId = $_SESSION['User']['id'];
$sql11 = "SELECT * FROM tbl_users WHERE id='$UserId'";
$row11 = getRecord($sql11);
$CustomerId = $row11['CustomerId'];
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
    <link rel="apple-touch-icon" href="../img/favicon180.png" sizes="180x180">
    <link rel="icon" href="../img/favicon32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="../img/favicon16.png" sizes="16x16" type="image/png">

    <!-- Material icons-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&amp;display=swap" rel="stylesheet">

    <!-- swiper CSS -->
    <link href="../vendor/swiper/css/swiper.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../css/style.css" rel="stylesheet" id="style">
     <?php include_once 'header_script.php'; ?>
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay" data-page="homepage">
    <!-- screen loader -->
    


    <!-- menu main -->
     <?php include_once '../sidebar.php'; ?>



    <!-- Begin page content -->
    <main class="flex-shrink-0 main has-footer">
        <!-- Fixed navbar -->
        <?php include_once '../top_header.php'; ?>
    
   
    
        <div class="main-container">
           
       <div class="container">
                <div class="card">
                    <div class="card-body px-0">
                        <ul class="list-group list-group-flush">
                            <?php  
                                $sql = "SELECT * FROM tbl_users WHERE DealerCode='$CustomerId' AND LeadCust=0";
                                $rncnt = getRow($sql);
                                if($rncnt > 0){
                                $row = getList($sql);
                                foreach($row as $result){    
                            ?>
                            <li class="list-group-item">
                                <div class="row align-items-center">
                                    <div class="col-auto pr-0">
                                        <div class="avatar avatar-40 rounded">
                                            <div class="background">
                                                <img src="../no_profile.jpg" alt="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col align-self-center pr-0">
                                        <h6 class="font-weight-normal mb-1"><?php echo $result['Fname'];?></h6>
                                        <p class="small text-secondary">Ph : <?php echo $result['Phone'];?><br>Dt: <?php echo date("d-m-Y", strtotime(str_replace('-', '/',$result['CreatedDate']))); ?></p>
                                    </div>
                                    <div class="col-auto">
                                        <h6 class="text-success">Completed</h6>
                                    </div>
                                </div>
                            </li>
                            <?php } } else{ ?>

                                <h5 style="color:red;padding: 11px;">No Record Found!!</h5>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>

            
        </div>
    </main>

    <?php include_once '../footer.php';?>


    <!-- color settings style switcher -->
    



    <!-- Required jquery and libraries -->
   <script src="../js/jquery-3.3.1.min.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- cookie js -->
    <script src="../js/jquery.cookie.js"></script>

    <!-- Swiper slider  js-->
    <script src="../vendor/swiper/js/swiper.min.js"></script>

    <!-- Customized jquery file  -->
    <script src="../js/main.js"></script>
    <script src="../js/color-scheme-demo.js"></script>


    <!-- page level custom script -->
    <script src="../js/app.js"></script>
     <?php include_once 'footer_script.php'; ?>
    <script>
          function logout(){
       Android.logout();
       window.location.href="logout.php";
  }
      </script>
      
    
</body>

</html>
