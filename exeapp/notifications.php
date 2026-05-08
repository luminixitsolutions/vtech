<?php session_start();require_once 'config.php';require_once 'auth.php';$pageval="nowshow";$PageName="Notifications";
?>
<!doctype html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="generator" content="">
    <title></title>

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay">
    <!-- screen loader -->


<?php
if($_GET['action']=='clear'){
    $sql = "UPDATE tbl_notifications SET ClearStatus=1 WHERE ReceiverId='$UserId'";
    $conn->query($sql);?>
    <script>
        window.location.href="notifications.php";
    </script>
<?php }
?>

    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <!-- Fixed navbar -->
     <?php include_once 'back-header.php'; ?> 

        <!-- page content start -->
 <div class="main-container">
            <div class="container">
                <div class="card">
                    <?php 
                    $sql = "SELECT * FROM tbl_notifications WHERE ReceiverId='$UserId' AND SenderId!=0 AND ClearStatus!=1";
                    $rncnt = getRow($sql);
                    if($rncnt > 0){?>
                    <div class="card-header">
                        <a href="notifications.php?action=clear" style="color:black;float:right;padding-top: 1px;height: 1px;">Clear All</a>
                    </div><?php } ?>
                    <div class="card-body px-0">
                        <div class="list-group list-group-flush">
                          <?php 
                          if($rncnt > 0){
                          $row = getList($sql);
                          foreach($row as $result){?>
                            <a class="list-group-item" href="<?php echo $result['Url'];?>" style="    padding: 10px 15px;border: 1px solid #f9f9f9;text-decoration:none;">
                                <div class="row">
                                    <div class="col-auto align-self-center">
                                        <i class="material-icons text-default" style="color: #f97e9b;">check_circle</i>
                                    </div>
                                    <div class="col pl-0">
                                        <div class="row mb-1">
                                            <div class="col">
                                                <p class="mb-0"><?php echo $result['Title'];?></p>
                                            </div>
                                            <div class="col-auto pl-0">
                                                <p class="small text-secondary"><?php echo date("d M Y", strtotime(str_replace('-', '/',$result['CreatedDate']))); ?></p>
                                            </div>
                                        </div>
                                        <p class="small text-secondary"><?php echo $result['Message'];?></p>
                                    </div>
                                </div>
                            </a>
                            
                          <?php } } else{ ?>
                          <div style="padding-left: 10px;padding-right: 10px;">
        <div class="alert alert-danger">Oops! No Notification Found.</div>
        </div>
                          
                          <?php } ?>
                    
                    
                        </div>
                    </div>
                </div>
               
            </div>
        </div>
    </main>
       
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
    
   
</body>

</html>
