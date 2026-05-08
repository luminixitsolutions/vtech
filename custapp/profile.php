<?php session_start();
require_once 'config.php';
require_once 'auth.php';
$PageName = "Profile";
$UserId = $_SESSION['User']['id'];
$user_id = $UserId;
$sql11 = "SELECT * FROM tbl_users WHERE id='$UserId'";
$row11 = getRecord($sql11);
$Name = $row11['Fname'] . " " . $row11['Lname'];
$Phone = $row11['Phone'];
$EmailId = $row11['EmailId'];
$Roll = $row11['Roll'];
//$mycity = $row['CityId'];
$ExpDate = date("d/m/Y", strtotime(str_replace('-', '/', $row11['Validity'])));

$sql88 = "SELECT SUM(creditAmt) As Credit,SUM(debitAmt) As Debit FROM (SELECT (case when Status='Cr' then sum(Amount) else 0 end) as creditAmt,(case when Status='Dr' then sum(Amount) else 0 end) as debitAmt FROM wallet WHERE UserId='$UserId' GROUP BY Status) as a";
$row88 = getRecord($sql88);
$Wallet = $row88['Credit'] - $row88['Debit'];

//echo $_GET['city_id'];
if ($_GET['city_id'] == 0 || $_GET['city_id'] == '') {
    $city_id = $row11['CityId'];
} else {
    $city_id = $_REQUEST['city_id'];
}
//echo $city_id;
/* $sql = "UPDATE tbl_users SET Location='$city_id' WHERE id='$UserId'";
    $conn->query($sql);*/
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

<body class="body-scroll d-flex flex-column h-100 menu-overlay" data-page="homepage">
    <!-- screen loader -->



    <!-- menu main -->




    <!-- Begin page content -->
    <main class="flex-shrink-0 main has-footer">
        <!-- Fixed navbar -->
        <?php include_once 'back-header.php'; ?>

        <div class="main-container">
            <div class="container-fluid px-0" style="padding-left: 10px !important;padding-right: 10px !important;">
                <div class="card overflow-hidden">
                    <div class="card-body p-0 h-150">
                        <div class="background">
                            <img src="img/image10.jpg" alt="" style="display: none;">
                        </div>
                    </div>
                </div>
            </div>
            <div class="container-fluid top-70 text-center mb-4">
                <div class="avatar avatar-140 rounded-circle mx-auto shadow">
                    <div class="background">

                        <img src="user_icon.jpg" alt="" style="width: 140px;height: 140px;">


                    </div>
                </div>
            </div>

            <div class="container mb-4 text-center text-black">
                <h6 class="mb-1"><?php echo $Name; ?></h6>
                <span><i class="fa fa-map-marker"></i> <?php echo $row11['Address']; ?></span>
                <p><i class="fa fa-phone"></i> <?php echo $row11['Phone']; ?></p>
            </div>



            <div class="container">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">My Account</h6>
                    </div>
                    <div class="card-body px-0 pt-0">
                        <div class="list-group list-group-flush border-top border-color">
                            <a href="edit-profile.php" class="list-group-item list-group-item-action border-color">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="avatar avatar-50 bg-danger-light text-danger rounded">
                                            <span class="material-icons">account_circle</span>
                                        </div>
                                    </div>
                                    <div class="col align-self-center pl-0">
                                        <h6 class="mb-1">Account Details</h6>
                                        <p class="text-secondary">Edit Account Details</p>
                                    </div>
                                </div>
                            </a>


                            <a href="JavaScript:Void(0);" onclick="logout()" class="list-group-item list-group-item-action border-color">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="avatar avatar-50 bg-danger-light text-danger rounded">
                                            <span class="material-icons">power_settings_new</span>
                                        </div>
                                    </div>
                                    <div class="col align-self-center pl-0">
                                        <h6 class="mb-1">Logout</h6>
                                        <p class="text-secondary">Logout from the application</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div><br>



        </div>
    </main>

    <?php include_once 'footer.php'; ?>


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


    <!-- page level custom script -->
    <script src="js/app.js"></script>
    <script>
        function logout() {
            Android.logout();
            window.location.href = "logout.php";
        }
    </script>
</body>

</html>