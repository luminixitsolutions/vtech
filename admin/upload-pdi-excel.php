<?php
session_start();
include_once 'config.php';
include_once 'auth.php';
require_once('vendor/php-excel-reader/excel_reader2.php');
require_once('vendor/SpreadsheetReader.php');
$user_id = $_SESSION['Admin']['id'];
$MainPage = "PDI-Verification";
$Page = "Upload-PDI-Verification";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">

<head>
    <title><?php echo $Proj_Title; ?> - <?php if ($_GET['id']) { ?>Edit <?php } else { ?> Add <?php } ?> Lead
    </title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="" />

    <?php include_once 'header_script.php'; ?>
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

            <?php include_once 'sidebar.php'; ?>


            <div class="layout-container">

                <?php include_once 'top_header.php'; ?>

                <?php
                if (isset($_POST['submit'])) {
                    $project_id = $_POST['project_id'];
                    $project_sub_head_id = $_POST['project_sub_head_id'];
                    $pdidate = $_POST['pdidate'];
                    $report_no = $_POST['report_no'];
                    $pdi_qty = $_POST['pdi_qty'];
                    $CreatedDate = date('Y-m-d H:i:s');

                    $allowedFileType = ['application/vnd.ms-excel', 'text/xls', 'text/xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];

                    if (in_array($_FILES["file"]["type"], $allowedFileType)) {

                        $targetPath = 'uploads/' . $_FILES['file']['name'];
                        move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);

                        $Reader = new SpreadsheetReader($targetPath);
                        $sheetCount = count($Reader->sheets());
                        if ($sheetCount > 0) {
                            $sql = "INSERT INTO tbl_pdi_verification SET project_id='$project_id',
                            project_sub_head_id='$project_sub_head_id',pdidate='$pdidate',
                            report_no='$report_no',pdi_qty='$pdi_qty',created_at='$CreatedDate',
                            created_by='$user_id'";
                            $conn->query($sql);
                            $pdi_id = mysqli_insert_id($conn);
                        }
                        for ($i = 0; $i < $sheetCount; $i++) {

                            $Reader->ChangeSheet($i);

                            foreach ($Reader as $Row) {

                                $serialno = "";
                                if (isset($Row[0])) {
                                    $serialno = mysqli_real_escape_string($conn, $Row[0]);
                                }
                                if (!empty($serialno)) {
                                    $qx = "INSERT INTO tbl_pdi_verification_serialno SET pdi_id='$pdi_id',
                                    project_id='$project_id',project_sub_head_id='$project_sub_head_id',
                                    pdidate='$pdidate',report_no='$report_no',serialno='$serialno',match_status=0";
                                    $conn->query($qx);
                                }
                            }
                        }

                        $sql = "DELETE FROM tbl_pdi_verification_serialno WHERE serialno='serialno'";
                        $conn->query($sql);
                ?>
                        <script>
                            alert("Excel Data Imported into the Database");
                            window.location.href = 'view-uploaded-pdi.php';
                        </script>
                <?php
                    } else {
                        $type = "error";
                        $message = "Invalid File Type. Upload Excel File.";
                    }
                }
                ?>

                <div class="layout-content">

                    <div class="container-fluid flex-grow-1 container-p-y">
                        <h4 class="font-weight-bold py-3 mb-0">Upload PDI Verification Excel</h4>

                        <div class="card mb-4">
                            <div class="card-body">
                                <form id="validation-form" method="post" autocomplete="off" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-lg-8">
                                            <div id="alert_message"></div>
                                            <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" id="userid">
                                            <input type="hidden" name="action" value="Save" id="action">
                                            <div class="form-row">

                                                <div class="form-group col-md-6">
                                                    <label class="form-label">Project <span class="text-danger">*</span></label>
                                                    <select class="form-control" id="ProjectId" name="project_id" required onchange="getSubHead(this.value)">
                                                        <option selected="" disabled="">Select Project</option>
                                                        <?php
                                                        $q = "select * from tbl_common_master WHERE Status='1' AND Roll=24 ORDER BY Name ASC";
                                                        $r = $conn->query($q);
                                                        while ($rw = $r->fetch_assoc()) {
                                                        ?>
                                                            <option <?php if ($row7['ProjectId'] == $rw['id']) { ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
                                                        <?php } ?>
                                                    </select>
                                                </div>

                                                <div class="form-group col-md-6">
                                                    <label class="form-label">Project Sub Head <span class="text-danger">*</span></label>
                                                    <select class="form-control" id="ProjectSubHeadId" name="project_sub_head_id" required>
                                                    <option selected="" disabled="">Select Sub Head </option>
                                                    </select>
                                                </div>

                                                <div class="form-group col-md-4">
                                                    <label class="form-label">Date </label>
                                                    <input type="date" name="pdidate" id="pdidate" class="form-control"
                                                        placeholder="" value="<?php echo $row7["pdidate"]; ?>"
                                                        autocomplete="off">
                                                    <div class="clearfix"></div>
                                                </div>

                                                <div class="form-group col-md-4">
                                                    <label class="form-label">PDI Report No </label>
                                                    <input type="text" name="report_no" id="report_no" class="form-control"
                                                        placeholder="" value="<?php echo $row7["report_no"]; ?>"
                                                        autocomplete="off">
                                                    <div class="clearfix"></div>
                                                </div>

                                                <div class="form-group col-md-4">
                                                    <label class="form-label">PDI Qty </label>
                                                    <input type="text" name="pdi_qty" id="pdi_qty" class="form-control"
                                                        placeholder="" value="<?php echo $row7["pdi_qty"]; ?>"
                                                        autocomplete="off">
                                                    <div class="clearfix"></div>
                                                </div>

                                         
                                                <div class="form-group col-md-12">
                                                    <label class="form-label">Upload Excel File <span class="text-danger">*</span>
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <a href="sample_files/sample_pdi_excel.xlsx" download>Download & Upload Sample Excel File</a>
                                                    </label>
                                                    <input type="file" name="file" id="" class="form-control" placeholder="" autocomplete="off" required>
                                                    <div class="clearfix"></div>
                                                </div>

                                            </div>
                                            <br>

                                            <div class="form-row">
                                                <div class="form-group col-md-2">
                                                    <button type="submit" name="submit" class="btn btn-primary btn-finish" id="submit">Submit</button>
                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                </form>
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
    <script>
        function getSubHead(id) {
            var action = 'getSubHead';
            $.ajax({
                type: "POST",
                url: "ajax_files/ajax_dropdown.php",
                data: {
                    action: action,
                    id: id
                },
                success: function(data) {
                    $('#ProjectSubHeadId').html(data);
                }
            });
        }
        </script>
</body>

</html>