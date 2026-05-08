<?php
session_start();
include_once '../config.php';
include_once '../auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Dispatch-Officer";
$Page = "Add-Dispatch-Officer";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">

<head>
    <title><?php echo $Proj_Title; ?> - <?php if ($_GET['id']) { ?>Edit <?php } else { ?> Add <?php } ?> Store-Incharge Account
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

                <?php
                $id = $_GET['id'];
                $sql7 = "SELECT * FROM tbl_users WHERE id='$id'";
                $row7 = getRecord($sql7);
                $row7['Options'] = explode(',', $row7['Options']);
                $row7['MulRooftopBranchId'] = explode(',', $row7['MulRooftopBranchId']);
                $row7['MulBranchId'] = explode(',', $row7['MulBranchId']);
                ?>

                <div class="layout-content">

                    <div class="container-fluid flex-grow-1 container-p-y">
                        <h4 class="font-weight-bold py-3 mb-0"><?php if ($_GET['id']) { ?>Edit <?php } else { ?> Add
                        <?php } ?> Dispatch Officer Account</h4>

                        <div class="card mb-4">
                            <div class="card-body">
                                <div id="alert_message"></div>
                                <form id="validation-form" method="post" autocomplete="off" action="../ajax_files/ajax_employee.php" enctype="multipart/form-data">
                                    <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" id="userid">
                                    <input type="hidden" name="action" value="Save" id="action">
                                    <div class="form-row">

                                        <div class="form-group col-md-12">
                                            <label class="form-label"> Company<span class="text-danger">*</span></label>
                                            <select class="form-control" name="CompId" id="CompId" required>
                                                <option selected="" value="">Select Company</option>
                                                <?php
                                                $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll=10";
                                                $row12 = getList($sql12);
                                                foreach ($row12 as $result) {
                                                ?>
                                                    <option <?php if ($row7["CompId"] == $result['id']) { ?> selected <?php } ?> value="<?php echo $result['id']; ?>">
                                                        <?php echo $result['Fname']; ?></option>
                                                <?php } ?>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>


                                        <div class="form-group col-md-6">
<label class="form-label"> Pump Store</label>
<select class="select2-demo form-control" multiple name="MulBranchId[]" id="BranchId">
                                                <?php
                                                if ($Roll == 1 || $Roll == 7) {
                                                    $sql12 = "SELECT * FROM tbl_branch WHERE Status='1'";
                                                } else {
                                                    $sql12 = "SELECT * FROM tbl_branch WHERE Status='1' AND id='$BranchId'";
                                                }
                                                $row12 = getList($sql12);
                                                foreach ($row12 as $result) {
                                                ?>
                                                    <option <?php if (in_array($result["id"], $row7['MulBranchId'])) { ?> selected <?php } ?> value="<?php echo $result['id']; ?>">
                                                        <?php echo $result['Name']; ?></option>
                                                <?php } ?>
                                            </select>
<div class="clearfix"></div>
</div> 

 <div class="form-group col-md-6">
<label class="form-label"> Rooftop Store</label>
<select class="select2-demo form-control" multiple name="MulRooftopBranchId[]" id="RooftopBranchId">
                                                <?php
                                                if ($Roll == 1 || $Roll == 7) {
                                                    $sql12 = "SELECT * FROM tbl_rooftop_branch WHERE Status='1'";
                                                } else {
                                                    $sql12 = "SELECT * FROM tbl_rooftop_branch WHERE Status='1' AND id='$BranchId'";
                                                }
                                                $row12 = getList($sql12);
                                                foreach ($row12 as $result) {
                                                ?>
                                                    <option <?php if (in_array($result["id"], $row7['MulRooftopBranchId'])) { ?> selected <?php } ?> value="<?php echo $result['id']; ?>">
                                                        <?php echo $result['Name']; ?></option>
                                                <?php } ?>
                                            </select>
<div class="clearfix"></div>
</div>      
                                        <div class="form-group col-md-12">
                                            <label class="form-label"> Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="Fname" id="Fname" class="form-control"
                                                placeholder="" value="<?php echo $row7["Fname"]; ?>"
                                                autocomplete="off">
                                        </div>

                                        <!-- <div class="form-group col-md-4">
                                            <label class="form-label">Middle Name</label>
                                            <input type="text" name="Mname" id="Mname" class="form-control"
                                                placeholder="" value="<?php echo $row7["Mname"]; ?>"
                                                autocomplete="off">
                                        </div>

                                         <div class="form-group col-md-4">
                                            <label class="form-label">Last Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="Lname" id="Lname" class="form-control"
                                                placeholder="" value="<?php echo $row7["Lname"]; ?>"
                                                autocomplete="off">
                                        </div>-->

                                        <div class="form-group col-md-12">
                                            <label class="form-label">Permanent Address </label>
                                            <textarea name="Address" class="form-control" placeholder="Address"
                                                autocomplete="off"><?php echo $row7["Address"]; ?></textarea>
                                            <div class="clearfix"></div>
                                        </div>


                                         <div class="form-group col-md-3">
                                            <label class="form-label">Password <span
                                                    class="text-danger">*</span></label>
                                            <input type="password" name="Password" id="Password" class="form-control"
                                                placeholder="Password" value="<?php echo $row7["Password"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>
                                       
                                        <div class="form-group col-md-3">
                                            <label class="form-label">Mobile No <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="Phone" id="Phone" class="form-control"
                                                placeholder="Mobile No" value="<?php echo $row7["Phone"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label class="form-label">Another Mobile No</label>
                                            <input type="text" name="Phone2" class="form-control"
                                                placeholder="Another Mobile No" value="<?php echo $row7["Phone2"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Another Father/Mother Contact No</label>
                                            <input type="text" name="FatherPhone" class="form-control"
                                                placeholder="" value="<?php echo $row7["FatherPhone"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label class="form-label">Designation</label>
                                            <input type="text" name="Designation" class="form-control"
                                                placeholder="" value="<?php echo $row7["Designation"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label class="form-label">Date Of Birth</label>
                                            <input type="date" name="Dob" class="form-control"
                                                placeholder="" value="<?php echo $row7["Dob"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label class="form-label">Aadhar Card No</label>
                                            <input type="text" name="AadharNo" class="form-control"
                                                placeholder="" value="<?php echo $row7["AadharNo"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label class="form-label">Blood Group</label>
                                            <input type="text" name="BloodGroup" class="form-control"
                                                placeholder="" value="<?php echo $row7["BloodGroup"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label class="form-label">Date Of Joining</label>
                                            <input type="date" name="JoinDate" class="form-control"
                                                placeholder="" value="<?php echo $row7["JoinDate"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label class="form-label">Email Id </label>
                                            <input type="email" name="EmailId" id="EmailId" class="form-control"
                                                placeholder="Email Id" value="<?php echo $row7["EmailId"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label class="form-label">Company Email Id </label>
                                            <input type="email" name="EmailId2" id="EmailId2" class="form-control"
                                                placeholder="Email Id" value="<?php echo $row7["EmailId2"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label class="form-label">In Time</label>
                                            <input type="time" name="InTime" class="form-control"
                                                placeholder="" value="<?php echo $row7["InTime"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label class="form-label">Out Time</label>
                                            <input type="time" name="OutTime" class="form-control"
                                                placeholder="" value="<?php echo $row7["OutTime"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>




                                        <div class="form-group col-md-8">
                                            <label class="form-label">Photo <span
                                                    class="text-danger">*</span></label>
                                            <label class="custom-file">
                                                <input type="file" class="custom-file-input" name="Photo"
                                                    style="opacity: 1;padding: 4px;">
                                                <input type="hidden" name="OldPhoto"
                                                    value="<?php echo $row7['Photo']; ?>" id="OldPhoto">
                                                <span class="custom-file-label"></span>
                                            </label>
                                            <?php if ($row7['Photo'] == '') {
                                            } else { ?>
                                                <span id="show_photo">
                                                    <div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a
                                                            href="javascript:void(0)"
                                                            class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white"
                                                            id="delete_photo"></a><img
                                                            src="../uploads/<?php echo $row7['Photo']; ?>" alt=""
                                                            class="img-fluid ticket-file-img"
                                                            style="width: 64px;height: 64px;"></div>
                                                </span>
                                            <?php } ?>
                                        </div>








                                    </div>

                                    <br>

                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label class="form-label"> Assign Lead Management</label>
                                        </div>
                                        <?php
                                        $sql33 = "SELECT * FROM tbl_options WHERE id IN(64,44,45,46,47,63,51)";
                                        $row33 = getList($sql33);
                                        foreach ($row33 as $result) {
                                        ?>
                                            <div class="form-group col-md-4">
                                                <label class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" name="Options[]" value="<?php echo $result['id']; ?>" <?php if (in_array($result["id"], $row7['Options'])) { ?>

                                                        checked="checked" <?php } ?>>
                                                    <span class="custom-control-label"><?php echo $result['Name']; ?></span>
                                                </label>
                                            </div>
                                        <?php } ?>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label class="form-label"> Assign Master Management</label>
                                        </div>
                                        <?php
                                        $sql33 = "SELECT * FROM tbl_options WHERE id IN(1,56,2,3,4,5,6,7,72,73,74,75,76,77,97,98,8,9,12,13,34,15,16,53,54,89,90,91,92,117,140,141)";
                                        $row33 = getList($sql33);
                                        foreach ($row33 as $result) {
                                        ?>
                                            <div class="form-group col-md-4">
                                                <label class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" name="Options[]" value="<?php echo $result['id']; ?>" <?php if (in_array($result["id"], $row7['Options'])) { ?>

                                                        checked="checked" <?php } ?>>
                                                    <span class="custom-control-label"><?php echo $result['Name']; ?></span>
                                                </label>
                                            </div>
                                        <?php } ?>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label class="form-label"> Assign User Accounts</label>
                                        </div>
                                        <?php
                                        $sql33 = "SELECT * FROM tbl_options WHERE id IN(18,122,19,20,21,125,126,127,128,116,22,23,129)";
                                        $row33 = getList($sql33);
                                        foreach ($row33 as $result) {
                                        ?>
                                            <div class="form-group col-md-4">
                                                <label class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" name="Options[]" value="<?php echo $result['id']; ?>" <?php if (in_array($result["id"], $row7['Options'])) { ?>

                                                        checked="checked" <?php } ?>>
                                                    <span class="custom-control-label"><?php echo $result['Name']; ?></span>
                                                </label>
                                            </div>
                                        <?php } ?>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label class="form-label"> Assign Other Access</label>
                                        </div>
                                        <?php
                                        $sql33 = "SELECT * FROM tbl_options WHERE id IN(55,79,18,130,131,80,81,132,133,134,25,58,70,60,71,26,82,83,68,84,84,93,94,95,96,118,138,139)";
                                        $row33 = getList($sql33);
                                        foreach ($row33 as $result) {
                                        ?>
                                            <div class="form-group col-md-4">
                                                <label class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" name="Options[]" value="<?php echo $result['id']; ?>" <?php if (in_array($result["id"], $row7['Options'])) { ?>

                                                        checked="checked" <?php } ?>>
                                                    <span class="custom-control-label"><?php echo $result['Name']; ?></span>
                                                </label>
                                            </div>
                                        <?php } ?>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label class="form-label"> Assign Service Complaint Access</label>
                                        </div>
                                        <?php
                                        $sql33 = "SELECT * FROM tbl_options WHERE id IN(28,135,137,136)";
                                        $row33 = getList($sql33);
                                        foreach ($row33 as $result) {
                                        ?>
                                            <div class="form-group col-md-4">
                                                <label class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" name="Options[]" value="<?php echo $result['id']; ?>" <?php if (in_array($result["id"], $row7['Options'])) { ?>

                                                        checked="checked" <?php } ?>>
                                                    <span class="custom-control-label"><?php echo $result['Name']; ?></span>
                                                </label>
                                            </div>
                                        <?php } ?>
                                    </div>




                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label class="form-label"> Assign Reports</label>
                                        </div>
                                        <?php
                                        $sql33 = "SELECT * FROM tbl_options WHERE id IN(29,30,31,38,39,65,99,100,101,102,103,104,105,106,107,108,109,110,111,112,142)";
                                        $row33 = getList($sql33);
                                        foreach ($row33 as $result) {
                                        ?>
                                            <div class="form-group col-md-4">
                                                <label class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" name="Options[]" value="<?php echo $result['id']; ?>" <?php if (in_array($result["id"], $row7['Options'])) { ?>

                                                        checked="checked" <?php } ?>>
                                                    <span class="custom-control-label"><?php echo $result['Name']; ?></span>
                                                </label>
                                            </div>
                                        <?php } ?>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label class="form-label"> Assign Rights</label>
                                        </div>

                                        <?php
                                        $sql33 = "SELECT * FROM tbl_options WHERE id IN(10,11,14)";
                                        $row33 = getList($sql33);
                                        foreach ($row33 as $result) {
                                        ?>
                                            <div class="form-group col-md-4">
                                                <label class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" name="Options[]" value="<?php echo $result['id']; ?>" <?php if (in_array($result["id"], $row7['Options'])) { ?>

                                                        checked="checked" <?php } ?>>
                                                    <span class="custom-control-label"><?php echo $result['Name']; ?></span>
                                                </label>
                                            </div>
                                        <?php } ?>
                                    </div>


                                    <div class="row">
                                        <div class="form-group col-md-4">
                                            <label class="form-label"> Under By Store Incharge</label>
                                            <select class="select2-demo form-control" name="UnderUser" id="UnderUser" onchange="getRoll(this.value)">
                                                <option selected="" value="" disabled>Select</option>
                                                <?php
                                                $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll=27";
                                                $row12 = getList($sql12);
                                                foreach ($row12 as $result) {
                                                ?>
                                                    <option <?php if ($row7["UnderUser"] == $result['id']) { ?> selected <?php } ?> value="<?php echo $result['id']; ?>">
                                                        <?php echo $result['Fname'] . " (" . $result['Phone'] . ")"; ?></option>
                                                <?php } ?>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-4 showall">
                                            <label class="form-label">Roll </label>
                                            <select class="form-control" name="Roll" id="Roll">

                                                <?php
                                                $q = "select * from tbl_user_type WHERE Status=1 AND id=26 ORDER BY Name";
                                                $r = $conn->query($q);
                                                while ($rw = $r->fetch_assoc()) {
                                                ?>
                                                    <option <?php if ($row7['Roll'] == $rw['id']) { ?> selected <?php } ?>
                                                        value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>


                                        <div class="form-group col-md-4">
                                            <label class="form-label">Status <span class="text-danger">*</span></label>
                                            <select class="form-control" id="Status" name="Status" required="">
                                                <option selected="" disabled="" value="">Select Status</option>
                                                <option value="1" <?php if ($row7["Status"] == '1') { ?> selected
                                                    <?php } ?>>Active</option>
                                                <option value="0" <?php if ($row7["Status"] == '0') { ?> selected
                                                    <?php } ?>>Inctive</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>




                                    </div>
                                    <!-- <button id="growl-default" class="btn btn-default">Default</button> -->
                                    <button type="submit" class="btn btn-primary btn-finish" id="submit">Save</button>
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