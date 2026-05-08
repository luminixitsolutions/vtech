<?php
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Customers";
$Page = "View-Customers";
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

<body>

    <body class="body-scroll d-flex flex-column h-100 menu-overlay">



        <!-- Begin page content -->
        <main class="flex-shrink-0 main">
            <!-- Fixed navbar -->
            <?php include_once 'back-header.php'; ?>


            <div class="main-container" style="background-color: #f1f1f1;">






                <div class="container">


                    <div class="card-header" style="padding: 10px;">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="tabhome125-tab" data-toggle="tab" href="#tabhome125" role="tab" aria-controls="tabhome125" aria-selected="true">
                                    Not Assign Sites
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tabhome225-tab" data-toggle="tab" href="#tabhome225" role="tab" aria-controls="tabhome225" aria-selected="false">
                                    Assign Sites
                                </a>
                            </li>

                        </ul>
                    </div>
                    <div class="card-body" style="padding: 0px;">
                        <div class="tab-content">
                            <div class="tab-pane fade active show" id="tabhome125" role="tabpanel" aria-labelledby="tabhome125-tab">
                                <?php

                                $sql = "SELECT tp.*,tu.Fname As InchargeName FROM tbl_users tp 
                    LEFT JOIN tbl_users tu ON tu.id=tp.DispatchOfficerId 
                    WHERE tp.StoreInchStatus=1 AND tp.ProjectType=1 AND tp.StoreInchId='$BranchId' AND tp.DispatchOfficerStatus=0";

                                $sql .= " ORDER BY tp.CreatedDate DESC";
                                //echo $sql;
                                $res = $conn->query($sql);
                                while ($row = $res->fetch_assoc()) {

                                ?>
                                    <div class="card mb-4" id="bgcolor<?php echo $row['id']; ?>">

                                        <div class="card-body">
                                            <h6 style="margin-bottom: 1px;font-size: 15px;"><input type="checkbox" id="Check_Id<?php echo $row['id']; ?>" value="<?php echo $row['id']; ?>" onclick="featured(<?php echo $row['id']; ?>)" class="common_selector Proccedures">&nbsp;&nbsp;&nbsp;<?php echo $row['Fname']; ?></h6>
                                            <input type="hidden" value="0" name="CheckId[]" id="CheckId<?php echo $row['id']; ?>">
                                            <input type="hidden" value="<?php echo $row['id']; ?>" name="CustId[]">
                                            <p style="margin-bottom: 1px;"><strong>(<?php echo $row['BeneficiaryId']; ?>)</strong></p>
                                            <p style="margin-bottom: 1px;"><strong>Contact No :</strong> <?php echo $row['Phone']; ?> </p>
                                            <?php if ($row['EmailId'] != '') { ?>
                                                <p style="margin-bottom: 1px;"><strong>Email Id :</strong> <?php echo $row['EmailId']; ?></p>
                                            <?php }
                                            if ($row['Address'] != '') { ?>
                                                <p style="margin-bottom: 1px;"><strong>Address :</strong> <?php echo $row['Address']; ?> </p>
                                            <?php } ?>

                                            <p style="margin-bottom: 1px;"><strong>Project Type :</strong> <?php if ($row["ProjectType"] == '1') { ?> <span style="color:red;font-weight:bold;font-size: 17px;">Pump Projects</span>
                                                <?php } else { ?> <span style="color:#2000ff;font-weight:bold;font-size: 17px;">Rooftop Projects </span><?php } ?></p>





                                        </div>
                                    </div>
                                <?php }  ?>

                                <!-- For Rooftop Project -->
                                <?php

                                $sql = "SELECT tp.*,tu.Fname As InchargeName FROM tbl_users tp 
LEFT JOIN tbl_users tu ON tu.id=tp.DispatchOfficerId 
WHERE tp.StoreInchStatus=1 AND tp.ProjectType=2 AND tp.StoreInchId='$RoofBranchId' AND tp.DispatchOfficerStatus=0";

                                $sql .= " ORDER BY tp.CreatedDate DESC";
                                //echo $sql;
                                $res = $conn->query($sql);
                                while ($row = $res->fetch_assoc()) {

                                ?>
                                    <div class="card mb-4" id="bgcolor<?php echo $row['id']; ?>">

                                        <div class="card-body">
                                            <h6 style="margin-bottom: 1px;font-size: 15px;"><input type="checkbox" id="Check_Id<?php echo $row['id']; ?>" value="<?php echo $row['id']; ?>" onclick="featured(<?php echo $row['id']; ?>)" class="common_selector Proccedures">&nbsp;&nbsp;&nbsp;<?php echo $row['Fname']; ?></h6>
                                            <input type="hidden" value="0" name="CheckId[]" id="CheckId<?php echo $row['id']; ?>">
                                            <input type="hidden" value="<?php echo $row['id']; ?>" name="CustId[]">
                                            <p style="margin-bottom: 1px;"><strong>(<?php echo $row['BeneficiaryId']; ?>)</strong></p>
                                            <p style="margin-bottom: 1px;"><strong>Contact No :</strong> <?php echo $row['Phone']; ?> </p>
                                            <?php if ($row['EmailId'] != '') { ?>
                                                <p style="margin-bottom: 1px;"><strong>Email Id :</strong> <?php echo $row['EmailId']; ?></p>
                                            <?php }
                                            if ($row['Address'] != '') { ?>
                                                <p style="margin-bottom: 1px;"><strong>Address :</strong> <?php echo $row['Address']; ?> </p>
                                            <?php } ?>

                                            <p style="margin-bottom: 1px;"><strong>Project Type :</strong> <?php if ($row["ProjectType"] == '1') { ?> <span style="color:red;font-weight:bold;font-size: 17px;">Pump Projects</span>
                                                <?php } else { ?> <span style="color:#2000ff;font-weight:bold;font-size: 17px;">Rooftop Projects </span><?php } ?></p>





                                        </div>
                                    </div>
                                <?php }  ?>

                                <?php
                                if (isset($_POST['submit'])) {

                                    $CustId = implode(",", $_POST['ProcedureId']);
                                    $array =  explode(",", $CustId);
                                    $DispatchOfficerId = $_POST['DispatchOfficerId'];
                                    $CreatedDate = date('Y-m-d H:i:s');
                                    foreach ($array as $item) {

                                        $sql = "UPDATE tbl_users SET DispatchOfficerStatus='1',DispatchOfficerId='$DispatchOfficerId',DispatchOfficerDate='$CreatedDate' WHERE id='$item'";
                                        $conn->query($sql);
                                    }


                                    echo "<script>window.location.href='assign-to-dispatch-officer.php';</script>";
                                }
                                ?>
                                <form method="post">
                                    <input type="hidden" id="ProcId" name="ProcedureId[]" value="">

                                    <div class="form-group col-lg-4">
                                        <label class="form-label"> Dispatch Officer<span class="text-danger">*</span></label>
                                        <select class="select2-demo form-control" name="DispatchOfficerId" id="DispatchOfficerId" required>
                                            <option selected="" value="">Select</option>
                                            <?php
                                            $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll=26";
                                            $row12 = getList($sql12);
                                            foreach ($row12 as $result) {
                                            ?>
                                                <option value="<?php echo $result['id']; ?>">
                                                    <?php echo $result['Fname']; ?></option>
                                            <?php } ?>
                                        </select>
                                        <div class="clearfix"></div>
                                    </div><br>
                                    <div class="form-group col-lg-4">
                                        <button type="submit" name="submit" class="btn btn-primary btn-finish">Submit</button>
                                    </div>
                                </form>

                            </div>
                            <div class="tab-pane fade" id="tabhome225" role="tabpanel" aria-labelledby="tabhome225-tab">
                                <?php

                                $sql = "SELECT tp.*,tu.Fname As InchargeName FROM tbl_users tp 
                    INNER JOIN tbl_users tu ON tu.id=tp.DispatchOfficerId 
                    WHERE tp.StoreInchStatus=1 AND tp.ProjectType=1 AND tp.StoreInchId='$BranchId' AND tp.DispatchOfficerStatus=1";

                                $sql .= " ORDER BY tp.CreatedDate DESC";
                                //echo $sql;
                                $res = $conn->query($sql);
                                while ($row = $res->fetch_assoc()) {

                                ?>
                                    <div class="card mb-4" id="bgcolor<?php echo $row['id']; ?>">

                                        <div class="card-body">
                                            <h6 style="margin-bottom: 1px;font-size: 15px;"><?php echo $row['Fname']; ?></h6>
                                            <p style="margin-bottom: 1px;"><strong>(<?php echo $row['BeneficiaryId']; ?>)</strong></p>
                                            <p style="margin-bottom: 1px;"><strong>Contact No :</strong> <?php echo $row['Phone']; ?> </p>
                                            <?php if ($row['EmailId'] != '') { ?>
                                                <p style="margin-bottom: 1px;"><strong>Email Id :</strong> <?php echo $row['EmailId']; ?></p>
                                            <?php }
                                            if ($row['Address'] != '') { ?>
                                                <p style="margin-bottom: 1px;"><strong>Address :</strong> <?php echo $row['Address']; ?> </p>
                                            <?php } ?>

                                            <p style="margin-bottom: 1px;"><strong>Assign To :</strong> <?php echo $row['InchargeName']; ?> </p>

                                            <p style="margin-bottom: 1px;"><strong>Project Type :</strong> <?php if ($row["ProjectType"] == '1') { ?> <span style="color:red;font-weight:bold;font-size: 17px;">Pump Projects</span>
                                                <?php } else { ?> <span style="color:#2000ff;font-weight:bold;font-size: 17px;">Rooftop Projects </span><?php } ?></p>





                                        </div>
                                    </div>
                                <?php }  ?>

                                <!-- For RoofTop Project -->
                                <?php

                                $sql = "SELECT tp.*,tu.Fname As InchargeName FROM tbl_users tp 
INNER JOIN tbl_users tu ON tu.id=tp.DispatchOfficerId 
WHERE tp.StoreInchStatus=1 AND tp.ProjectType=2 AND tp.StoreInchId='$RoofBranchId' AND tp.DispatchOfficerStatus=1";

                                $sql .= " ORDER BY tp.CreatedDate DESC";
                                //echo $sql;
                                $res = $conn->query($sql);
                                while ($row = $res->fetch_assoc()) {

                                ?>
                                    <div class="card mb-4" id="bgcolor<?php echo $row['id']; ?>">

                                        <div class="card-body">
                                            <h6 style="margin-bottom: 1px;font-size: 15px;"><?php echo $row['Fname']; ?></h6>
                                            <p style="margin-bottom: 1px;"><strong>(<?php echo $row['BeneficiaryId']; ?>)</strong></p>
                                            <p style="margin-bottom: 1px;"><strong>Contact No :</strong> <?php echo $row['Phone']; ?> </p>
                                            <?php if ($row['EmailId'] != '') { ?>
                                                <p style="margin-bottom: 1px;"><strong>Email Id :</strong> <?php echo $row['EmailId']; ?></p>
                                            <?php }
                                            if ($row['Address'] != '') { ?>
                                                <p style="margin-bottom: 1px;"><strong>Address :</strong> <?php echo $row['Address']; ?> </p>
                                            <?php } ?>

                                            <p style="margin-bottom: 1px;"><strong>Assign To :</strong> <?php echo $row['InchargeName']; ?> </p>

                                            <p style="margin-bottom: 1px;"><strong>Project Type :</strong> <?php if ($row["ProjectType"] == '1') { ?> <span style="color:red;font-weight:bold;font-size: 17px;">Pump Projects</span>
                                                <?php } else { ?> <span style="color:#2000ff;font-weight:bold;font-size: 17px;">Rooftop Projects </span><?php } ?></p>





                                        </div>
                                    </div>
                                <?php }  ?>
                            </div>

                        </div>
                    </div>



                </div>


                <br><br>




                <?php include_once 'footer.php'; ?>

            </div>

        </main>
        <br><br>
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
        <?php include_once 'footer_script.php'; ?>
        <script type="text/javascript">
            function nextBtn() {
                var ProcId = $('#ProcId').val();
                window.location.href = "allocate-sites-to-installer.php?siteid=" + ProcId;
            }

            function featured(id) {
                if ($('#Check_Id' + id).prop('checked') == true) {
                    $('#CheckId' + id).val(1);
                    $('#bgcolor' + id).css('background-color', 'turquoise');
                    clickChk();
                } else {
                    $('#CheckId' + id).val(0);
                    $('#bgcolor' + id).css('background-color', '');
                    clickChk();
                }
            }


            function get_filter(class_name) {
                var filter = [];
                $('.' + class_name + ':checked').each(function() {
                    filter.push($(this).val());
                });
                return filter;
            }

            function clickChk() {

                var ProccedureId = get_filter('Proccedures');
                if (ProccedureId == '') {
                    $('#ProcId').val(0);
                } else {
                    $('#ProcId').val(ProccedureId);
                }
            }

            function chageSurveyDetails(val, id) {
                var action = "chageSurveyDetails";
                $.ajax({
                    url: "ajax_files/ajax_customer_account.php",
                    method: "POST",
                    data: {
                        action: action,
                        id: id,
                        val: val
                    },
                    success: function(data) {
                        alert("Survey Details Changed.");

                    }
                });
            }
            $(document).ready(function() {
                $('#example').DataTable({
                    "scrollX": true,
                    paging: false,
                    ordering: false,
                    info: false,
                });
            });
        </script>
    </body>

</html>