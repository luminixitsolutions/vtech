<?php session_start();
require_once 'config.php';
$id = $_GET['id'];
$PageName = "My Commission";
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
    <link href="vendor/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet">
    <link href="vendor/daterangepicker-master/daterangepicker.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
</head>

<body class="body-scroll d-flex flex-column h-100 menu-overlay">



    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <!-- Fixed navbar -->
        <?php include_once 'back-header.php'; ?>

        <!-- page content start -->

<?php 
$id = $_GET['id'];
$sql = "SELECT Amount FROM tbl_dealer_general_ledger WHERE DealerId='$UserId' AND UserId='$id' AND Type='DINV'";
$row = getRecord($sql);
?>
        <div class="container mb-4" style="padding-right: 1px;padding-left: 1px;">


            <div class="card">


                <div class="card-body px-0 pt-0">
                    <span style="text-align: center;font-weight: 500;font-size: 20px;">
                        <div class='alert alert-warning'>Total Amount : &#8377;<?php echo number_format($row['Amount'], 2); ?></div>
                    </span>
                    <ul class="list-group list-group-flush" id="show_prod">
                        <?php
                        $sql2 = "SELECT * FROM tbl_dealer_general_ledger WHERE DealerId='$UserId' AND UserId='$id' AND Type!='DINV' AND CrDr='dr'";
                        $res2 = $conn->query($sql2);
                        $row_cnt = mysqli_num_rows($res2);
                        if ($row_cnt > 0) {
                            while ($row = $res2->fetch_assoc()) {


                                if ($row['CrDr'] == 'dr') {
                                    $Status = '<h6 class="text-success">Cr</h6>';
                                } else {
                                    $Status = '<h6 class="text-danger">Dr</h6>';
                                }
                        ?>
                                <li class="list-group-item">
                                    <div class="row align-items-center">

                                        <div class="col align-self-center pr-0">
                                            <h6>&#8377;<?php echo number_format($row['Amount'], 2); ?></h6>
                                            <p class="text-secondary"><strong>Payment Mode : </strong> <?php echo $row['PayMode']; ?><br>
                                            <strong>Payment Date : </strong><?php echo date("d/m/Y", strtotime(str_replace('-', '/', $row['CreatedDate']))) ?><br>
                                            <strong>Narration : </strong><?php echo $row['Narration']; ?></p>
                                            
                                        </div>
                                        <div class="col-auto">
                                            <?php echo $Status; ?>
                                        </div>
                                    </div>
                                </li>
                            <?php }
                        } else { ?><br>
                            <!--  <div class="col-auto">
                                        <h6 class="text-danger">Sorry! No Customer Found..</h6>
                                    </div> -->
                        <?php } ?>
                    </ul>
                </div>
            </div>



        </div>
    </main>
    <?php include_once 'footer.php'; ?>

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

    <script src="vendor/daterangepicker-master/moment.min.js"></script>
    <script src="vendor/daterangepicker-master/daterangepicker.js"></script>
    <!-- page level custom script -->
    <script src="js/app.js"></script>

</body>

</html>