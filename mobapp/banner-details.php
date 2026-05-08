<?php session_start();
require_once 'config.php';
require_once 'auth.php';
$PageName = "Exam";
$uid = $_REQUEST['uid'];
$sql11 = "SELECT * FROM tbl_users WHERE id='$uid'";
$rncnt11 = getRow($sql11);
if($rncnt11 > 0){
    $row = getRecord($sql11);
    $_SESSION['User'] = $row;
}    
$UserId = $_SESSION['User']['id'];
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
    <meta name="apple-mobile-web-app-capable" content="yes">
    <link rel="apple-touch-icon" href="img/favicon180.png" sizes="180x180">
    <link rel="icon" href="img/favicon32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="img/favicon16.png" sizes="16x16" type="image/png">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&amp;display=swap" rel="stylesheet">
    <link href="vendor/swiper/css/swiper.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet" id="style">
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay">
    <main class="flex-shrink-0 main">
        <?php include_once 'back-header.php'; ?> 
        <div class="main-container">
            <div class="container">
               
                  
                 
                <div class="row  mt-3">
                 
                   
                    <div class="col-12 col-md-12">
                        <div class="card border-0 mb-4">
                            <div class="card-body">
                                <img src="https://rjorg.in/yashacademy/uploads/27_77.jpg">
                                
                                <h3 class="mb-0 font-weight-normal" style="font-size: 16px;color: black;">ddddddddddd</h3>
                                
                                <p>THE YASH ACADEMY is established in 2014 . Since 2014, THE YASH ACADEMY, Nagpur has been at the forefront for providing quality coaching for CIVIL SERVICE EXAMINATION like ALL INDIA SERVICES – IAS/IPS/IFS and Centre Services – IFS, IRS, IRTS, IDES etc. conducted by the UPSC and State Services Exam – Dy. Collector, Dy. SP, Tahsildar conducted by the MPSC. Since its inception, our goal has been to provide the most comprehensive and fluid learning experience so as to inculcate the requisite attitude in the ASPIRANTS. We, at the Yash Academy, always strive to provide very precise and accurate knowledge to the Aspirants which not only helps them to crack the Examinations in the very first go itself but also helps them in terms of concept building</p>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                    
                       
               
            </div>
        </div>
    </main>
 <?php include_once 'footer.php'; ?>
<script src="js/jquery-3.3.1.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="js/jquery.cookie.js"></script>
<script src="vendor/swiper/js/swiper.min.js"></script>
<script src="js/main.js"></script>
<script src="js/color-scheme-demo.js"></script>
<script src="js/app.js"></script>
<script>
    function onDownloadFileFromUrl(url,filename){
         Android.onDownloadFileFromUrl(''+url+'',''+filename+'');
    }
</script>
</body>
</html>
