<?php session_start();
require_once 'config.php';
$id = $_GET['id'];
$PageName = "My Commission";
$UserId = $_SESSION['User']['id'];
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">

<head>
    <title><?php echo $Proj_Title; ?> | View Customer Account List</title>
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
    <link href="css/toastr.min.css" rel="stylesheet">
    <script src="js/jquery.min3.5.1.js" type="text/javascript"></script>
    <script type="text/javascript" src="js/toastr.min.js"></script>
    <link rel="stylesheet" href="example/css/slim.min.css">
    <?php include_once 'header_script.php'; ?>
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay">



    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <!-- Fixed navbar -->
        <?php include_once 'back-header.php'; ?>

        <!-- page content start -->



        <div class="container">
            <br>
            <h4>Pump Applications <span style="float:right;"><a href="create-application-form.php" class="btn btn-info btn-finish">Create New</a></h4>

            <div class="card-body" style="padding: 0px;">
                <div class="tab-content">
                    <div class="tab-pane fade active show" id="tabhome125" role="tabpanel" aria-labelledby="tabhome125-tab">
                        <?php 
        $i = 1;
        $sql = "SELECT * FROM tbl_mp_pump_applications WHERE createdby='$UserId' ORDER BY created_at DESC";
        $res = $conn->query($sql);
        while($row = $res->fetch_assoc()) {
        ?>
                            <div class="card mb-4" id="bgcolor<?php echo $row['id']; ?>">

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <h6 style="margin-bottom: 1px;font-size: 15px;"><?php echo $row['applicant_name']; ?></h6>
                                            <p style="margin-bottom: 1px;"><strong>Contact No :</strong> <?php echo $row['mobile']; ?> </p>
<p style="margin-bottom: 1px;"><strong>District :</strong> <?php echo $row['district']; ?> </p>
<p style="margin-bottom: 1px;"><strong>Tehsil :</strong> <?php echo $row['tehsil']; ?> </p>
<p style="margin-bottom: 1px;"><strong>Village :</strong> <?php echo $row['village']; ?> </p>
                                            <p style="margin-bottom: 1px;"><strong>Application Date :</strong> <?php echo date("d/m/Y", strtotime(str_replace('-', '/', $row['created_at']))); ?> </p>
                                            
                                        </div>
                                        <div class="col-auto pl-0">
                                            <a href="view-application-details.php?id=<?php echo $row['id']; ?>" class="btn btn-success btn-finish">View</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php }   ?>



                    </div>


                </div>
            </div>



        </div>


        </div>
    </main><br><br><br>
    <?php include_once 'footer.php'; ?>

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
    <?php include_once 'footer_script.php'; ?>

</body>

</html>