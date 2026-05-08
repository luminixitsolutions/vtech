<?php
session_start();
include_once '../config.php';
include_once '../auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Installer";
$Page = "View-Contractor-Commission";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">

<head>
    <title><?php echo $Proj_Title; ?> - <?php if ($_GET['id']) { ?>Edit <?php } else { ?> Add <?php } ?> Contractor Account
    </title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="" />

    <?php include_once '../header_script.php'; ?>

</head>

<body>
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
    </style>
    <div class="layout-wrapper layout-2">
        <div class="layout-inner">

            <?php include_once 'account-sidebar.php'; ?>


            <div class="layout-container">

                <?php include_once '../top_header.php'; ?>

                <style>
                    table,
                    td,
                    th {
                        border: 1px solid #ddd;
                        text-align: left;
                    }

                    table {
                        border-collapse: collapse;
                        width: 100%;
                    }

                    th,
                    td {
                        padding: 15px;
                    }
                </style>

                <?php
                $UserId = $_GET['UserId'];
                $ProjectHeadId = $_GET['ProjectHeadId'];
                $ProjectSubHeadId = $_GET['ProjectSubHeadId'];
                $sql7 = "SELECT tu.* FROM tbl_rooftop_contractor_commision tu WHERE tu.UserId='$UserId' AND tu.ProjectHeadId='$ProjectHeadId' AND tu.ProjectSubHeadId='$ProjectSubHeadId' GROUP BY tu.UserId,tu.ProjectHeadId,tu.ProjectSubHeadId";
                $row7 = getRecord($sql7);
                //$row7['Options'] = explode(',', $row7['Options']);

                if (isset($_POST['submit'])) {
                    $UserId = $_POST['UserId'];
                    $ProjectId = $_POST['ProjectId'];
                    $ProjectSubHeadId = $_POST['ProjectSubHeadId'];
                    $CreatedDate = date('Y-m-d H:i:s');
                    if ($_GET['UserId'] == '') {
                        if ($_POST["Capacity"] != '') {
                            $number = count($_POST["Capacity"]);
                            if ($number > 0) {
                                for ($i = 0; $i < $number; $i++) {
                                    if ($_POST["Capacity"][$i] != '') {
                                        $Capacity = $_POST['Capacity'][$i];
                                        $SelectionVal = $_POST['SelectionVal'][$i];
                                        $FieldSurveyVal = $_POST['FieldSurveyVal'][$i];
                                        $DispatchVal = $_POST['DispatchVal'][$i];
                                        $FoundationVal = $_POST['FoundationVal'][$i];
                                        $InstallationVal = $_POST['InstallationVal'][$i];
                                        $InspectionVal = $_POST['InspectionVal'][$i];
                                        $DocumentationVal = $_POST['DocumentationVal'][$i];

                                        $sql = "INSERT INTO tbl_rooftop_contractor_commision SET CreatedBy='$user_id',CreatedDate='$CreatedDate',ProjectHeadId='$ProjectId',ProjectSubHeadId='$ProjectSubHeadId',UserId='$UserId',Capacity='$Capacity',SelectionVal='$SelectionVal',FieldSurveyVal='$FieldSurveyVal',DispatchVal='$DispatchVal',FoundationVal='$FoundationVal',InstallationVal='$InstallationVal',InspectionVal='$InspectionVal',DocumentationVal='$DocumentationVal'";
                                        $conn->query($sql);
                                    }
                                }
                            }
                        }
                    } else {
                        $sql = "DELETE FROM tbl_rooftop_contractor_commision WHERE UserId='$UserId' AND ProjectHeadId='$ProjectHeadId' AND ProjectSubHeadId='$ProjectSubHeadId'";
                        $conn->query($sql);
                        if ($_POST["Capacity"] != '') {
                            $number = count($_POST["Capacity"]);
                            if ($number > 0) {
                                for ($i = 0; $i < $number; $i++) {
                                    if ($_POST["Capacity"][$i] != '') {
                                        $Capacity = $_POST['Capacity'][$i];
                                        $SelectionVal = $_POST['SelectionVal'][$i];
                                        $FieldSurveyVal = $_POST['FieldSurveyVal'][$i];
                                        $DispatchVal = $_POST['DispatchVal'][$i];
                                        $FoundationVal = $_POST['FoundationVal'][$i];
                                        $InstallationVal = $_POST['InstallationVal'][$i];
                                        $InspectionVal = $_POST['InspectionVal'][$i];
                                        $DocumentationVal = $_POST['DocumentationVal'][$i];

                                        $sql = "INSERT INTO tbl_rooftop_contractor_commision SET CreatedBy='$user_id',CreatedDate='$CreatedDate',ProjectHeadId='$ProjectId',ProjectSubHeadId='$ProjectSubHeadId',UserId='$UserId',Capacity='$Capacity',SelectionVal='$SelectionVal',FieldSurveyVal='$FieldSurveyVal',DispatchVal='$DispatchVal',FoundationVal='$FoundationVal',InstallationVal='$InstallationVal',InspectionVal='$InspectionVal',DocumentationVal='$DocumentationVal'";
                                        $conn->query($sql);
                                    }
                                }
                            }
                        }
                    }
                    echo "<script>alert('Record Updated Successfully!');window.location.href='view-contractor-commision.php';</script>";
                }
                ?>

                <div class="layout-content">

                    <div class="container-fluid flex-grow-1 container-p-y">
                        <h4 class="font-weight-bold py-3 mb-0"><?php if ($_GET['id']) { ?>Edit <?php } else { ?> Add
                        <?php } ?> Contractor Commission</h4>

                        <div class="card mb-4">
                            <div class="card-body">
                                <div id="alert_message"></div>
                                <form id="validation-form" method="post" autocomplete="off" action="" enctype="multipart/form-data">
                                    <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" id="userid">
                                    <input type="hidden" name="action" value="Save" id="action">
                                    <div class="form-row">

                                        <div class="form-group col-md-4">
                                            <label class="form-label">Contractor <span class="text-danger">*</span></label>
                                            <select class="form-control" id="UserId" name="UserId" required>
                                                <option selected="" disabled="">Select Contractor</option>
                                                <?php
                                                $q = "select * from tbl_users WHERE Status='1' AND Roll=40 ORDER BY Fname ASC";
                                                $r = $conn->query($q);
                                                while ($rw = $r->fetch_assoc()) {
                                                ?>
                                                    <option <?php if ($row7['UserId'] == $rw['id']) { ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Fname']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label class="form-label">Project <span class="text-danger">*</span></label>
                                            <select class="form-control" id="ProjectId" name="ProjectId" required onchange="getSubHead(this.value)">
                                                <option selected="" disabled="">Select Project</option>
                                                <?php
                                                $q = "select * from tbl_rooftop_common_master WHERE Status='1' AND Roll=24 ORDER BY Name ASC";
                                                $r = $conn->query($q);
                                                while ($rw = $r->fetch_assoc()) {
                                                ?>
                                                    <option <?php if ($row7['ProjectHeadId'] == $rw['id']) { ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label class="form-label">Project Sub Head <span class="text-danger">*</span></label>
                                            <select class="form-control" id="ProjectSubHeadId" name="ProjectSubHeadId" required>
                                                <option selected="" disabled="">Select Sub Head</option>
                                                <?php
                                                $q = "select * from tbl_rooftop_project_sub_head WHERE Status='1' AND UnderBy='" . $row7['ProjectHeadId'] . "'";
                                                $r = $conn->query($q);
                                                while ($rw = $r->fetch_assoc()) {
                                                ?>
                                                    <option <?php if ($row7['ProjectSubHeadId'] == $rw['id']) { ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>



                                        <?php
                                        function getCommision($val, $capacity, $uid, $ProjectHeadId, $ProjectSubHeadId)
                                        {
                                            global $conn;
                                            $sql = "SELECT $val AS Value FROM tbl_rooftop_contractor_commision WHERE Capacity='$capacity' AND UserId='$uid' AND ProjectHeadId='$ProjectHeadId' AND ProjectSubHeadId='$ProjectSubHeadId'";
                                            $row = getRecord($sql);
                                            return $row['Value'];
                                        }
                                        ?>
                                        <table>
                                            <tr>
                                                <th>Sr.no.</th>
                                                <th>Scope of Work</th>
                                                <?php
                                                $sql = "SELECT * FROM tbl_common_master WHERE Roll=2";
                                                $row = getList($sql);
                                                foreach ($row as $result) {
                                                ?>
                                                    <input type="hidden" class="form-control" name="Capacity[]" value="<?php echo $result['id']; ?>">
                                                    <th><?php echo $result['Name']; ?></th>
                                                <?php } ?>
                                            </tr>
                                            <tr>
                                                <td>1</td>
                                                <td>Selection</td>
                                                <?php foreach ($row as $result) {
                                                    if ($_GET['UserId'] != '') {
                                                        $val1 = getCommision('SelectionVal', $result['id'], $_GET['UserId'], $_GET['ProjectHeadId'], $_GET['ProjectSubHeadId']);
                                                    } else {
                                                        $val1 = 0;
                                                    }
                                                ?>
                                                    <td><input type="text" class="form-control" name="SelectionVal[]" value="<?php echo $val1; ?>"></td>
                                                <?php } ?>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>Field Survey</td>
                                                <?php foreach ($row as $result) {
                                                    if ($_GET['UserId'] != '') {
                                                        $val2 = getCommision('FieldSurveyVal', $result['id'], $_GET['UserId'], $_GET['ProjectHeadId'], $_GET['ProjectSubHeadId']);
                                                    } else {
                                                        $val2 = 0;
                                                    }
                                                ?>

                                                    <td><input type="text" class="form-control" value="<?php echo $val2; ?>" name="FieldSurveyVal[]"></td>
                                                <?php } ?>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>Material Dispatch</td>
                                                <?php foreach ($row as $result) {
                                                    if ($_GET['UserId'] != '') {
                                                        $val3 = getCommision('DispatchVal', $result['id'], $_GET['UserId'], $_GET['ProjectHeadId'], $_GET['ProjectSubHeadId']);
                                                    } else {
                                                        $val3 = 0;
                                                    }
                                                ?>

                                                    <td><input type="text" class="form-control" value="<?php echo $val3; ?>" name="DispatchVal[]"></td>
                                                <?php } ?>
                                            </tr>
                                            <tr>
                                                <td>4</td>
                                                <td>Foundation</td>
                                                <?php foreach ($row as $result) {
                                                    if ($_GET['UserId'] != '') {
                                                        $val4 = getCommision('FoundationVal', $result['id'], $_GET['UserId'], $_GET['ProjectHeadId'], $_GET['ProjectSubHeadId']);
                                                    } else {
                                                        $val4 = 0;
                                                    }
                                                ?>

                                                    <td><input type="text" class="form-control" value="<?php echo $val4; ?>" name="FoundationVal[]"></td>
                                                <?php } ?>
                                            </tr>
                                            <tr>
                                                <td>5</td>
                                                <td>Installation </td>
                                                <?php foreach ($row as $result) {
                                                    if ($_GET['UserId'] != '') {
                                                        $val5 = getCommision('InstallationVal', $result['id'], $_GET['UserId'], $_GET['ProjectHeadId'], $_GET['ProjectSubHeadId']);
                                                    } else {
                                                        $val5 = 0;
                                                    }
                                                ?>

                                                    <td><input type="text" class="form-control" value="<?php echo $val5; ?>" name="InstallationVal[]"></td>
                                                <?php } ?>
                                            </tr>
                                            <tr>
                                                <td>6</td>
                                                <td>Inspection</td>
                                                <?php foreach ($row as $result) {
                                                    if ($_GET['UserId'] != '') {
                                                        $val6 = getCommision('InspectionVal', $result['id'], $_GET['UserId'], $_GET['ProjectHeadId'], $_GET['ProjectSubHeadId']);
                                                    } else {
                                                        $val6 = 0;
                                                    }
                                                ?>

                                                    <td><input type="text" class="form-control" value="<?php echo $val6; ?>" name="InspectionVal[]"></td>
                                                <?php } ?>
                                            </tr>
                                            <tr>
                                                <td>7</td>
                                                <td>Documentation</td>
                                                <?php foreach ($row as $result) {
                                                    if ($_GET['UserId'] != '') {
                                                        $val7 = getCommision('DocumentationVal', $result['id'], $_GET['UserId'], $_GET['ProjectHeadId'], $_GET['ProjectSubHeadId']);
                                                    } else {
                                                        $val7 = 0;
                                                    }
                                                ?>

                                                    <td><input type="text" class="form-control" value="<?php echo $val7; ?>" name="DocumentationVal[]"></td>
                                                <?php } ?>
                                            </tr>
                                        </table>
                                    </div>
                                    <br>
                                    <!-- <button id="growl-default" class="btn btn-default">Default</button> -->
                                    <button type="submit" name="submit" class="btn btn-primary btn-finish" id="submit">Save</button>
                                </form>
                            </div>
                        </div>






                    </div>


                    <?php include_once '../footer.php'; ?>
                </div>

            </div>

        </div>

        <div class="layout-overlay layout-sidenav-toggle"></div>
    </div>


    <?php include_once '../footer_script.php'; ?>

    <script type="text/javascript">
        function getSubHead(id) {
            var action = 'getSubHead';
            $.ajax({
                type: "POST",
                url: "../ajax_files/ajax_dropdown.php",
                data: {
                    action: action,
                    id: id
                },
                success: function(data) {
                    $('#ProjectSubHeadId').html(data);
                }
            });
        }

        function getRoll(val) {
            if (val == '0') {
                //$('.showall').show();
                // $('.showonly').hide();
            } else {
                // $('.showonly').show();
                // $('.showall').hide();
            }
        }

        function myFunction2() {

            var x = document.getElementById("Password");
            if (x.type === "password") {
                x.type = "text";
                $('.show2').html('<i class="fa fa-eye-slash" aria-hidden="true"></i>');
            } else {
                x.type = "password";
                $('.show2').html('<i class="fa fa-eye" aria-hidden="true"></i>');
            }
        }

        function error_toast() {
            var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
            $.growl.error({
                title: 'Error',
                message: 'Email Id / Phone No Already Exists',
                location: isRtl ? 'tl' : 'tr'
            });
        }

        function success_toast() {
            var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
            $.growl.success({
                title: 'Success',
                message: 'Saved Successfully...',
                location: isRtl ? 'tl' : 'tr'
            });
        }
        $(document).ready(function() {
            //$(document).on("click", ".btn-finish", function(event){
            $('#validation-form').on('submit', function(e) {
                exit();
                e.preventDefault();
                if ($('#validation-form').valid()) {

                    $.ajax({
                        url: "../ajax_files/ajax_employee.php",
                        method: "POST",
                        data: new FormData(this),
                        contentType: false,
                        processData: false,
                        beforeSend: function() {
                            $('#submit').attr('disabled', 'disabled');
                            $('#submit').text('Please Wait...');
                        },
                        success: function(data) {

                            if (data == 0) {
                                error_toast();

                            } else {
                                success_toast();
                                setTimeout(function() {
                                    window.location.href = 'view-manufacture.php';
                                }, 2000);
                            }
                            $('#submit').attr('disabled', false);
                            $('#submit').text('Save');
                        }
                    })



                } else {
                    //$('#Fname').focus();
                    return false;
                }
            });

            $(document).on("click", "#delete_photo", function(event) {
                event.preventDefault();
                if (confirm("Are you sure you want to delete Profile Photo?")) {
                    var action = "deletePhoto";
                    var id = $('#userid').val();
                    var Photo = $('#OldPhoto').val();
                    $.ajax({
                        url: "../ajax_files/ajax_employee.php",
                        method: "POST",
                        data: {
                            action: action,
                            id: id,
                            Photo: Photo
                        },
                        success: function(data) {

                            $('#show_photo').hide();
                            var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr(
                                'dir') === 'rtl';
                            $.growl.success({
                                title: 'Success',
                                message: data,
                                location: isRtl ? 'tl' : 'tr'
                            });

                        }
                    });
                }

            });
            $(document).on("change", "#CountryId", function(event) {
                var val = this.value;
                var action = "getState";
                $.ajax({
                    url: "../ajax_files/ajax_dropdown.php",
                    method: "POST",
                    data: {
                        action: action,
                        id: val
                    },
                    success: function(data) {
                        $('#StateId').html(data);
                    }
                });

            });

            $(document).on("change", "#StateId", function(event) {
                var val = this.value;
                var action = "getCity";
                $.ajax({
                    url: "../ajax_files/ajax_dropdown.php",
                    method: "POST",
                    data: {
                        action: action,
                        id: val
                    },
                    success: function(data) {
                        $('#CityId').html(data);
                    }
                });

            });
        });
    </script>

</body>

</html>