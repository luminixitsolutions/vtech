<?php
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Approve-Attendance";
$Page = "Approve-Attendance";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">

<head>
    <title><?php echo $Proj_Title; ?> | Attendance Report</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="" />
    <?php include_once 'header_script.php'; ?>
</head>

<body>

    <div class="layout-wrapper layout-2">
        <div class="layout-inner">

            <?php include_once 'sidebar.php'; ?>


            <div class="layout-container">

                <?php include_once 'top_header.php'; ?>


                <div class="layout-content">

                    <div class="container-fluid flex-grow-1 container-p-y">
                        <h4 class="font-weight-bold py-3 mb-0">Attendance Report</h4>

                        <div class="card" style="padding: 10px;">
                            <div id="accordion2">
                                <div class="card mb-2">

                                    <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                        <div class="" style="padding:5px;">
                                            <form id="validation-form" method="post" enctype="multipart/form-data" action="">
                                                <div class="form-row">



                                                    <div class="form-group col-md-5">
                                                        <label class="form-label">Executive</label>
                                                        <select class="select2-demo form-control" name="ExeId" id="ExeId">
                                                            <option selected="" value="all">All</option>
                                                            <?php
                                                            $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll NOT IN(1,3,4,5,9,10,8,11,34,35,36,37) ";
                                                            if ($Roll == 41) {
                                                                $sql12 .= " AND ImmediateBossId='$user_id'";
                                                            }
                                                            $row12 = getList($sql12);
                                                            foreach ($row12 as $result) {
                                                            ?>
                                                                <option <?php if ($_REQUEST['ExeId'] == $result['id']) { ?> selected <?php } ?>
                                                                    value="<?php echo $result['id']; ?>"><?php echo $result['Fname']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>


                                                    <div class="form-group col-md-2">
                                                        <label class="form-label">From Date </label>
                                                        <input type="date" name="FromDate" id="FromDate" class="form-control" value="<?php echo $_REQUEST['FromDate'] ?>" autocomplete="off">
                                                    </div>
                                                    <div class="form-group col-md-2">
                                                        <label class="form-label">To Date</label>
                                                        <input type="date" name="ToDate" id="ToDate" class="form-control" value="<?php echo $_REQUEST['ToDate'] ?>" autocomplete="off">
                                                    </div>
                                                    <input type="hidden" name="Search" value="Search">
                                                    <div class="form-group col-md-1" style="padding-top:30px;">
                                                        <button type="submit" name="submit" class="btn btn-primary btn-finish">Search</button>
                                                    </div>
                                                    <?php if (isset($_POST['Search'])) { ?>
                                                        <div class="col-md-1">
                                                            <label class="form-label d-none d-md-block">&nbsp;</label>
                                                            <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-info btn-block" data-toggle="tooltip" data-placement="top" data-original-title="Clear Filter">X</a>
                                                        </div>
                                                    <?php } ?>
                                                </div>

                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-datatable table-responsive">
                                <table id="example" class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Status</th>
                                            <th>Approve By</th>
                                            <th>Executive Name</th>
                                            <th>Immediate Boss</th>            

                                            <th>Date</th>
                                            <th>Start Time</th>
                                            <th>Start Latitude</th>
                                            <th>Start Longitude</th>
                                            <th>Start Photo</th>
                                            <th>End Time</th>
                                            <th>End Latitude</th>
                                            <th>End Longitude</th>
                                            <th>End Photo</th>
                                            <th>Report Submitted</th>
                                            <th>View Location</th>


                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php

                                        $i = 1;
                                        $sql = "SELECT ta.*,tu.Fname,tu2.Fname As ApproveName,tu.ImmediateBossId FROM tbl_attendance ta INNER JOIN tbl_users tu ON tu.id=ta.UserId 
            LEFT JOIN tbl_users tu2 ON tu2.id=ta.ApproveBy WHERE tu.Roll NOT IN(1,3,4,5,9,10,8,11,34,35,36,37) ";
                                        if ($ImmediateBoss == 1) {
                                            $sql .= " AND tu.ImmediateBossId='$user_id'";
                                        }
                                        if ($_POST['ExeId']) {
                                            $UserId = $_POST['ExeId'];
                                            if ($UserId == 'all') {
                                                $sql .= " ";
                                            } else {
                                                $sql .= " AND ta.UserId='$UserId'";
                                            }
                                        }

                                        if ($_REQUEST['FromDate']) {
                                            $FromDate = $_REQUEST['FromDate'];
                                            $sql .= " AND ta.CreatedDate>='$FromDate'";
                                        }
                                        if ($_REQUEST['ToDate']) {
                                            $ToDate = $_REQUEST['ToDate'];
                                            $sql .= " AND ta.CreatedDate<='$ToDate'";
                                        }
                                        $sql .= " GROUP BY ta.CreatedDate,ta.UserId ORDER BY tu.Fname";
                                        //echo $sql;
                                        $res = $conn->query($sql);
                                        while ($row = $res->fetch_assoc()) {
                                            $sql4 = "SELECT Fname FROM tbl_users WHERE id='".$row['ImmediateBossId']."'";
                                            $row4 = getRecord($sql4);

                                            $sql33 = "SELECT * FROM tbl_attendance WHERE CreatedDate='" . $row['CreatedDate'] . "' AND UserId='" . $row['UserId'] . "' AND Type=1";
                                            $rncnt33 = getRow($sql33);
                                            $row33 = getRecord($sql33);

                                            $sql34 = "SELECT * FROM tbl_attendance WHERE CreatedDate='" . $row['CreatedDate'] . "' AND UserId='" . $row['UserId'] . "' AND Type=2";
                                            $rncnt34 = getRow($sql34);
                                            $row34 = getRecord($sql34);

                                            if ($rncnt33 > 0) {
                                                $bgcolor = "background-color: #acf3ac;";
                                                $st_time = date("h:i a", strtotime(str_replace('-', '/', $row33['CreatedTime'])));
                                            } else {
                                                $bgcolor = "background-color: #f55d5d;";
                                                $st_time = "";
                                            }

                                            if ($rncnt34 > 0) {
                                                $bgcolor2 = "background-color: #acf3ac;";
                                                $ed_time = date("h:i a", strtotime(str_replace('-', '/', $row34['CreatedTime'])));
                                            } else {
                                                $bgcolor2 = "background-color: #f55d5d;";
                                                $ed_time = "";
                                            }
                                        ?>
                                            <tr>
                                                <td><label class="switcher switcher-success">
                                                        <input type="checkbox" class="switcher-input" id="Check_Id<?php echo $row['id']; ?>" <?php if ($row['ApproveStatus'] == 1) { ?> checked <?php } ?> value="0" onclick="featured(<?php echo $row['id']; ?>)">
                                                        <span class="switcher-indicator">
                                                            <span class="switcher-yes">
                                                                <span class="ion ion-md-checkmark"></span>
                                                            </span>
                                                            <span class="switcher-no">
                                                                <span class="ion ion-md-close"></span>
                                                            </span>
                                                        </span>
                                                        <span class="switcher-label">Approve</span>
                                                    </label></td>

                                                <input type="hidden" value="0" name="CheckId[]" id="CheckId<?php echo $row['id']; ?>">
                                                <td><?php echo $row['ApproveLine'] . " " . $row['ApproveName']; ?></td>
                                                <td><?php echo $row['Fname']; ?></td>
                                                <td><?php echo $row4['Fname'];?></td>
                                                <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/', $row['CreatedDate']))); ?></td>

                                                <td><?php echo $st_time; ?></td>
                                                <td><a href="track-location.php?stlat=<?php echo $row33['Latitude']; ?>&stlang=<?php echo $row33['Longitude']; ?>&edlat=<?php echo $row34['Latitude']; ?>&edlang=<?php echo $row34['Longitude']; ?>" target="_new"><?php echo $row33['Latitude']; ?></a></td>
                                                <td><a href="track-location.php?stlat=<?php echo $row33['Latitude']; ?>&stlang=<?php echo $row33['Longitude']; ?>&edlat=<?php echo $row34['Latitude']; ?>&edlang=<?php echo $row34['Longitude']; ?>" target="_new"><?php echo $row33['Longitude']; ?></a></td>
                                                <td><?php if ($row33["Photo"] == '') { ?>
                                                        <img src="user_icon.jpg" class="d-block ui-w-40 rounded-circle" style="width: 40px;height: 40px;">
                                                    <?php } else if (file_exists('../uploads/' . $row33["Photo"])) { ?>
                                                        <img src="../uploads/<?php echo $row33["Photo"]; ?>" class="d-block ui-w-40 rounded-circle" alt="" style="width: 40px;height: 40px;">
                                                    <?php } else { ?>
                                                        <img src="user_icon.jpg" class="d-block ui-w-40 rounded-circle" style="width: 40px;height: 40px;">
                                                    <?php } ?>
                                                </td>

                                                <td><?php echo $ed_time; ?></td>
                                                <td><a href="track-location.php?stlat=<?php echo $row33['Latitude']; ?>&stlang=<?php echo $row33['Longitude']; ?>&edlat=<?php echo $row34['Latitude']; ?>&edlang=<?php echo $row34['Longitude']; ?>" target="_new"><?php echo $row34['Latitude']; ?></a></td>
                                                <td><a href="track-location.php?stlat=<?php echo $row33['Latitude']; ?>&stlang=<?php echo $row33['Longitude']; ?>&edlat=<?php echo $row34['Latitude']; ?>&edlang=<?php echo $row34['Longitude']; ?>" target="_new"><?php echo $row34['Longitude']; ?></a></td>
                                                <td><?php if ($row34["Photo"] == '') { ?>
                                                        <img src="user_icon.jpg" class="d-block ui-w-40 rounded-circle" style="width: 40px;height: 40px;">
                                                    <?php } else if (file_exists('../uploads/' . $row34["Photo"])) { ?>
                                                        <img src="../uploads/<?php echo $row34["Photo"]; ?>" class="d-block ui-w-40 rounded-circle" alt="" style="width: 40px;height: 40px;">
                                                    <?php } else { ?>
                                                        <img src="user_icon.jpg" class="d-block ui-w-40 rounded-circle" style="width: 40px;height: 40px;">
                                                    <?php } ?>
                                                </td>
                                                <td><?php echo $row34['ReportStatus']; ?></td>
                                                <td><a href="track-location.php?stlat=<?php echo $row33['Latitude']; ?>&stlang=<?php echo $row33['Longitude']; ?>&edlat=<?php echo $row34['Latitude']; ?>&edlang=<?php echo $row34['Longitude']; ?>" target="_new">Track Location</a></td>
                                            </tr>
                                        <?php } ?>

                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>


                    <?php include_once 'footer.php'; ?>

                </div>

            </div>

        </div>

        <div class="layout-overlay layout-sidenav-toggle"></div>
    </div>


    <?php include_once 'footer_script.php'; ?>

    <script type="text/javascript">
        function approve(id, status) {
            var action = "approveAttendance";
            $.ajax({
                url: "ajax_files/ajax_dropdown.php",
                method: "POST",
                data: {
                    action: action,
                    id: id,
                    status: status
                },
                success: function(data) {
                    //alert(data);
                    alert('Status Updated Successfully!');
                    if (data == 1) {
                        $('#Check_Id' + id).prop('checked', true);
                    } else {
                        $('#Check_Id' + id).prop('checked', false);
                    }

                }
            });
        }

        function featured(id) {
            if ($('#Check_Id' + id).prop('checked') == true) {
                $('#CheckId' + id).val(1);
                approve(id, 1);
            } else {
                $('#CheckId' + id).val(0);
                approve(id, 0);
            }
        }
        $(document).ready(function() {
            $('#example').DataTable({
                "scrollX": true,
                dom: 'Bfrtip',
                buttons: [
                    'excelHtml5'
                ]
            });


        });
    </script>
</body>

</html>