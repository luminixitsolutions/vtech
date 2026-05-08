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
    <?php include_once 'sidebar.php'; ?>



    <!-- Begin page content -->
    <main class="flex-shrink-0 main has-footer">
        <!-- Fixed navbar -->
        <?php include_once 'top_header.php'; ?>

        <style type="text/css">
            .password-tog-info {
                display: inline-block;
                cursor: pointer;
                font-size: 12px;
                font-weight: 600;
                position: absolute;
                right: 50px;
                top: 30px;
                text-transform: uppercase;
                z-index: 2;
            }

            fieldset legend {
                background: inherit;
                font-family: "Lato", sans-serif;
                color: #650812;
                font-size: 15px;
                left: 10px;
                padding: 0 10px;
                position: absolute;
                top: -12px;
                font-weight: 400;
                width: auto !important;
                border: none !important;
            }

            fieldset {
                background: #ffffff;
                border: 1px solid #4FAFB8;
                border-radius: 5px;
                margin: 20px 0 1px 0;
                padding: 20px;
                position: relative;
            }


            .bs-vertical-wizard {
                border-right: 1px solid #eaecf1;
                padding-bottom: 50px;
            }

            .bs-vertical-wizard ul {
                margin: 0;
                padding: 0;
                list-style: none;
            }

            .bs-vertical-wizard ul>li {
                display: block;
                position: relative;
            }

            .bs-vertical-wizard ul>li>a {
                display: block;
                padding: 10px 10px 10px 40px;
                color: #333c4e;
                font-size: 17px;
                font-weight: 400;
                letter-spacing: .8px;
            }

            .bs-vertical-wizard ul>li>a:before {
                content: '';
                position: absolute;
                width: 1px;
                height: calc(100% - 25px);
                background-color: #bdc2ce;
                left: 13px;
                bottom: -9px;
                z-index: 3;
            }

            .bs-vertical-wizard ul>li>a .ico {
                pointer-events: none;
                font-size: 14px;
                position: absolute;
                left: 10px;
                top: 15px;
                z-index: 2;
            }

            .bs-vertical-wizard ul>li>a:after {
                content: '';
                position: absolute;
                border: 2px solid #bdc2ce;
                border-radius: 50%;
                top: 14px;
                left: 6px;
                width: 16px;
                height: 16px;
                z-index: 3;
            }

            .bs-vertical-wizard ul>li>a .desc {
                display: block;
                color: #bdc2ce;
                font-size: 11px;
                font-weight: 400;
                line-height: 1.8;
                letter-spacing: .8px;
            }

            .bs-vertical-wizard ul>li.complete>a:before {
                background-color: #5cb85c;
                opacity: 1;
                height: calc(100% - 25px);
                bottom: -9px;
            }

            .bs-vertical-wizard ul>li.complete>a:after {
                display: none;
            }

            .bs-vertical-wizard ul>li.locked>a:after {
                display: none;
            }

            .bs-vertical-wizard ul>li:last-child>a:before {
                display: none;
            }

            .bs-vertical-wizard ul>li.complete>a .ico {
                left: 8px;
            }

            .bs-vertical-wizard ul>li>a .ico.ico-green {
                color: #5cb85c;
            }

            .bs-vertical-wizard ul>li>a .ico.ico-muted {
                color: #bdc2ce;
            }

            .bs-vertical-wizard ul>li.current {
                background-color: #fff;
            }

            .bs-vertical-wizard ul>li.current>a:before {
                background-color: #ffe357;
                opacity: 1;
            }

            .bs-vertical-wizard ul>li.current>a:after {
                border-color: #ffe357;
                background-color: #ffe357;
                opacity: 1;
            }

            .bs-vertical-wizard ul>li.current:after,
            .bs-vertical-wizard ul>li.current:before {
                left: 100%;
                top: 50%;
                border: solid transparent;
                content: " ";
                height: 0;
                width: 0;
                position: absolute;
                pointer-events: none;
            }

            .bs-vertical-wizard ul>li.current:after {
                border-color: rgba(255, 255, 255, 0);
                border-left-color: #fff;
                border-width: 10px;
                margin-top: -10px;
            }

            .bs-vertical-wizard ul>li.current:before {
                border-color: rgba(234, 236, 241, 0);
                border-left-color: #eaecf1;
                border-width: 11px;
                margin-top: -11px;
            }
        </style>

        <div class="main-container">

        <h5 style="text-align: center;">My Solar Status</h5>
            <div class="row pt-3 pb-4">

                <div class="col">

                    <div class="bs-vertical-wizard" style="padding-bottom: 10px;">
                        <ul>
                            <?php
                            $sql7 = "SELECT tu.*,tb.Name As BranchName,ts.Name As Scheme,tc.Name As Country,ts2.Name As State,tc2.Name As City FROM tbl_users tu 
                            LEFT JOIN tbl_branch tb ON tu.BranchId=tb.id 
                            LEFT JOIN tbl_scheme ts ON tu.SchemeId=ts.id 
                            LEFT JOIN tbl_country tc ON tu.CountryId=tc.id 
                            LEFT JOIN tbl_state ts2 ON tu.StateId=ts2.id 
                            LEFT JOIN tbl_city tc2 ON tu.CityId=tc2.id WHERE tu.id='$UserId'";
                            $row7 = getRecord($sql7);
                            $SellId = $row7['SellId'];
                            $AccountName = $row7['AccountName'];
                            $BankName = $row7['BankName'];
                            $AccountNo = $row7['AccountNo'];
                            $Branch = $row7['Branch'];
                            $IfscCode = $row7['IfscCode'];
                            $UpiNo = $row7['UpiNo'];

                            $CoOrdSurvey = $row7['SurveyDetails'];
                            $FieldSurvey = $row7['FieldSurveyDetails'];

                            $sql88 = "SELECT * FROM tbl_sell WHERE CustId='$UserId'";
                            $row88 = getRecord($sql88);
                            $rncnt88 = getRow($sql88);

                            $sql99 = "SELECT * FROM tbl_installations WHERE CustId='$UserId'";
                            $row99 = getRecord($sql99);
                            $rncnt99 = getRow($sql99);

                            ?>
                            <?php if ($CoOrdSurvey == 1) { ?>
                                <li class="complete">
                                    <a href="javascript:void(0)">Co-ordinator Survey <i class="ico fa fa-check ico-green"></i>
                                        <span class="desc">Created at <?php echo date("d M Y", strtotime(str_replace('-', '/', $row7['TelSurveyDate']))); ?></span>
                                    </a>
                                </li>
                            <?php }
                            if ($FieldSurvey == 1) { ?>
                                <li class="complete">
                                    <a href="javascript:void(0)">Field Survey <i class="ico fa fa-check ico-green"></i>
                                        <span class="desc">Created at <?php echo date("d M Y", strtotime(str_replace('-', '/', $row7['FieldSurveyDate']))); ?></span>
                                    </a>
                                </li>
                            <?php }
                            if ($rncnt88 > 0) { ?>
                                <li class="complete">
                                    <a href="javascript:void(0)">Delivery Challan <i class="ico fa fa-check ico-green"></i>
                                        <span class="desc">Created at <?php echo date("d M Y", strtotime(str_replace('-', '/', $row88['InvoiceDate']))); ?></span>
                                    </a>
                                </li>
                            <?php }
                            if ($row88['Inst_Dispatcher_Otp_Verify'] == 1) { ?>
                                <li class="complete">
                                    <a href="javascript:void(0)">Dispatch <i class="ico fa fa-check ico-green"></i>
                                        <span class="desc">Created at <?php echo date("d/m/Y", strtotime(str_replace('-', '/', $row88['Inst_Dispatcher_Date']))); ?></span>
                                    </a>
                                </li>
                            <?php }
                            if ($row99['InstallStatus'] == 'Yes') { ?>
                                <li class="complete">
                                    <a href="javascript:void(0)">Installation <i class="ico fa fa-check ico-green"></i>
                                        <span class="desc">Created at <?php echo date("d/m/Y", strtotime(str_replace('-', '/', $row99['InstallationDate']))); ?></span>
                                    </a>
                                </li>
                            <?php }
                            if ($row99['DataUploadStatus'] == 'Yes') { ?>
                                <li class="complete">
                                    <a href="javascript:void(0)">Data Upload <i class="ico fa fa-check ico-green"></i>
                                        <span class="desc">Created at <?php echo date("d/m/Y", strtotime(str_replace('-', '/', $row99['DataUploadDate']))); ?></span>
                                    </a>
                                </li>
                            <?php } if($row99['PoInspection'] == 'Yes'){?>
                                <li class="complete">
                                    <a href="javascript:void(0)">Inspection <i class="ico fa fa-check ico-green"></i>
                                        <span class="desc">Created at <?php echo date("d/m/Y", strtotime(str_replace('-', '/', $row99['PoInspectionDate'])));?></span>
                                    </a>
                                </li>
                                <?php } if($row99['InspectionDiscrepancy'] == 'Yes'){?>
                                    <li class="complete">
                                    <a href="javascript:void(0)">Inspection Discrepancy <i class="ico fa fa-check ico-green"></i>
                                        <span class="desc">Created at <?php echo date("d/m/Y", strtotime(str_replace('-', '/', $row99['InspectionDiscrepancyDate'])));?></span>
                                    </a>
                                </li>
                                <?php } if($row99['InsuranceApproval'] == 'Yes'){?>
                                    <li class="complete">
                                    <a href="javascript:void(0)">Insurance Approval <i class="ico fa fa-check ico-green"></i>
                                        <span class="desc">Created at <?php echo date("d/m/Y", strtotime(str_replace('-', '/', $row99['InsuranceApprovalDate'])));?></span>
                                    </a>
                                </li>
                                <?php } ?>
                        </ul>
                    </div>


                </div>
            </div>





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