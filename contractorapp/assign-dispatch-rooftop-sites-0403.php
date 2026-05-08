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
                    <h4 class="font-weight-bold py-3 mb-0">Dispatch Sites</h4>

                    <div class="card mb-4">

                        <div class="card-body">
                            <form id="validation-form" method="post" enctype="multipart/form-data" action="">
                                <div class="form-row">
                                    <div class="form-group col-md-3 Pump col-6" id="hidediameter">
                                        <label class="form-label">
                                            Village </label>
                                        <select class="form-control" id="Village" name="Village">
                                            <option selected="" value="">Select Village</option>
                                            <?php
                                            $q = "select DISTINCT(Village) AS Village from tbl_users WHERE Village!=''";
                                            $r = $conn->query($q);
                                            while ($rw = $r->fetch_assoc()) {
                                            ?>
                                                <option <?php if ($_REQUEST['Village'] == $rw['Village']) { ?> selected <?php } ?> value="<?php echo $rw['Village']; ?>"><?php echo $rw['Village']; ?></option>
                                            <?php } ?>
                                        </select>

                                        <div class="clearfix"></div>
                                    </div>

                                    <div class="form-group col-md-3 col-6 Pump" id="hidediameter">
                                        <label class="form-label">
                                            District </label>
                                        <select class="form-control" id="District" name="District">
                                            <option selected="" value="">Select District</option>
                                            <?php
                                            $q = "select DISTINCT(District) AS District from tbl_users WHERE District!=''";
                                            $r = $conn->query($q);
                                            while ($rw = $r->fetch_assoc()) {
                                            ?>
                                                <option <?php if ($_REQUEST['District'] == $rw['District']) { ?> selected <?php } ?> value="<?php echo $rw['District']; ?>"><?php echo $rw['District']; ?></option>
                                            <?php } ?>
                                        </select>

                                        <div class="clearfix"></div>
                                    </div>

                                    <div class="form-group col-md-3 col-6 Pump" id="hidediameter">
                                        <label class="form-label">
                                            Taluka </label>
                                        <select class="form-control" id="Taluka" name="Taluka">
                                            <option selected="" value="">Select Taluka</option>
                                            <?php
                                            $q = "select DISTINCT(Taluka) AS Taluka from tbl_users WHERE Taluka!=''";
                                            $r = $conn->query($q);
                                            while ($rw = $r->fetch_assoc()) {
                                            ?>
                                                <option <?php if ($_REQUEST['Taluka'] == $rw['Taluka']) { ?> selected <?php } ?> value="<?php echo $rw['Taluka']; ?>"><?php echo $rw['Taluka']; ?></option>
                                            <?php } ?>
                                        </select>

                                        <div class="clearfix"></div>
                                    </div>

                                    <div class="form-group col-md-3 col-6 Pump" id="hidediameter" style="    padding-top: 32px;">
                                        <button type="submit" class="btn btn-success btn-finish">Search</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card-header" style="padding: 10px;">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="tabhome125-tab" data-toggle="tab" href="#tabhome125" role="tab" aria-controls="tabhome125" aria-selected="true">
                                    Pending Sites
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tabhome225-tab" data-toggle="tab" href="#tabhome225" role="tab" aria-controls="tabhome225" aria-selected="false">
                                    Allocated Sites
                                </a>
                            </li>

                        </ul>
                    </div>
                    <div class="card-body" style="padding: 0px;">
                        <div class="tab-content">
                            <div class="tab-pane fade active show" id="tabhome125" role="tabpanel" aria-labelledby="tabhome125-tab">
                                

<!-- For Rooftop  -->
<?php

                                /*$sql = "SELECT tdo.*,tu.id AS Cust_Id,tu.Fname,tu.EmailId,tu.Phone,tu.Address,tu.ProjectType,tu.BeneficiaryId FROM tbl_sell tdo 
                    LEFT JOIN tbl_users tu ON tdo.CustId=tu.id WHERE tu.FieldSurveyId='$user_id'";*/
                                $sql = "SELECT tdo.*,tu.id AS Cust_Id,tu.Fname,tu.EmailId,tu.Phone,tu.Address,tu.ProjectType,tu.BeneficiaryId FROM tbl_rooftop_sell tdo 
                    LEFT JOIN tbl_users tu ON tdo.CustId=tu.id WHERE tdo.ContractorAssignId='$user_id'";
                                if ($_REQUEST['Village'] != '') {
                                    $sql .= " AND tu.Village='" . $_REQUEST['Village'] . "'";
                                }
                                if ($_REQUEST['Taluka'] != '') {
                                    $sql .= " AND tu.Taluka='" . $_REQUEST['Taluka'] . "'";
                                }
                                if ($_REQUEST['District'] != '') {
                                    $sql .= " AND tu.District='" . $_REQUEST['District'] . "'";
                                }
                                $sql .= " ORDER BY tdo.id DESC";
                                //echo $sql;

                                $res = $conn->query($sql);
                                while ($row = $res->fetch_assoc()) {
                                    $sql2 = "SELECT * FROM tbl_rooftop_sell WHERE DispatcherStatus=1 AND id='" . $row['id'] . "'";
                                    $rncnt2 = getRow($sql2);
                                    if ($rncnt2 > 0) {
                                    } else {

                                ?>
                                        <div class="card mb-4" id="bgcolor<?php echo $row['id']; ?>">

                                            <div class="card-body">
                                                <h6 style="margin-bottom: 1px;font-size: 15px;"><input type="checkbox" id="Check_Id<?php echo $row['id']; ?>" value="<?php echo $row['id']; ?>" onclick="featured(<?php echo $row['id']; ?>)" class="common_selector Proccedures">&nbsp;&nbsp;&nbsp;<?php echo $row['Fname']; ?></h6>
                                                <input type="hidden" value="0" name="CheckId[]" id="CheckId<?php echo $row['id']; ?>">
                                                <input type="hidden" value="<?php echo $row['CustId']; ?>" name="CustId[]">
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
                                <?php }
                                } ?>

                                <?php
                                if (isset($_POST['submit'])) {

                                    $CustId = implode(",", $_POST['ProcedureId']);
                                    $array =  explode(",", $CustId);
                                    $InstallerId = $_POST['InstallerId'];
                                    $CreatedDate = date('Y-m-d H:i:s');
                                    foreach ($array as $item) {
                                       
                                        $sql = "UPDATE tbl_rooftop_sell SET DispatcherStatus='1',DispatcherId='$InstallerId',DispatcherDate='$CreatedDate',ContractorId='$user_id' WHERE id='$item'";
                                        $conn->query($sql);
                                    }


                                    echo "<script>window.location.href='assign-dispatch-rooftop-sites.php';</script>";
                                }
                                ?>
                                <form method="post">
                                    <input type="hidden" id="ProcId" name="ProcedureId[]" value="">

                                    <div class="form-group col-lg-4">
                                        <label class="form-label"> Dispatcher<span class="text-danger">*</span></label>
                                        <select class="select2-demo form-control" name="InstallerId" id="InstallerId" required>
                                            <option selected="" value="">Select</option>
                                            <?php
                                            $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll IN(34,35,36,37) AND UnderInstallerId='$user_id' AND Options LIKE '%86%'";
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

                                
                                $sql = "SELECT tdo.*,tu.id AS Cust_Id,tu.Fname,tu.EmailId,tu.Phone,tu.Address,tu.ProjectType,tu.BeneficiaryId FROM tbl_rooftop_sell tdo 
                    LEFT JOIN tbl_users tu ON tdo.CustId=tu.id WHERE tdo.ContractorAssignId='$user_id'";
                                if ($_REQUEST['Village'] != '') {
                                    $sql .= " AND tu.Village='" . $_REQUEST['Village'] . "'";
                                }
                                if ($_REQUEST['Taluka'] != '') {
                                    $sql .= " AND tu.Taluka='" . $_REQUEST['Taluka'] . "'";
                                }
                                if ($_REQUEST['District'] != '') {
                                    $sql .= " AND tu.District='" . $_REQUEST['District'] . "'";
                                }
                                $sql .= " ORDER BY tdo.id DESC";
                                $res = $conn->query($sql);
                                while ($row = $res->fetch_assoc()) {
                                    $sql2 = "SELECT * FROM tbl_rooftop_sell WHERE DispatcherStatus=1 AND id='" . $row['id'] . "'";
                                    $rncnt2 = getRow($sql2);
                                    if ($rncnt2 > 0) {
                                        $row2 = getRecord($sql2);
                                        $sql3 = "SELECT Fname FROM tbl_users WHERE id='" . $row2['DispatcherId'] . "'";
                                        $row3 = getRecord($sql3);
                                ?>
                                        <div class="card mb-4" id="bgcolor<?php echo $row['Cust_Id']; ?>">

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

                                                <p style="margin-bottom: 1px;"><strong>Assign To :</strong> <?php echo $row3['Fname']; ?> </p>

                                                <p style="margin-bottom: 1px;"><strong>Project Type :</strong> <?php if ($row["ProjectType"] == '1') { ?> <span style="color:red;font-weight:bold;font-size: 17px;">Pump Projects</span>
                                                    <?php } else { ?> <span style="color:#2000ff;font-weight:bold;font-size: 17px;">Rooftop Projects </span><?php } ?></p>





                                            </div>
                                        </div>
                                <?php }
                                } ?>
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