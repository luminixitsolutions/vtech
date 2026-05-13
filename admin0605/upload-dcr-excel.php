<?php
session_start();
include_once 'config.php';
include_once 'auth.php';
require_once('vendor/php-excel-reader/excel_reader2.php');
require_once('vendor/SpreadsheetReader.php');
$user_id = $_SESSION['Admin']['id'];
$MainPage = "DCR-Verification";
$Page = "Upload-DCR-Verification";
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
                    $dcrdate = $_POST['dcrdate'];
                    $certificate_no = $_POST['certificate_no'];
                    $CreatedDate = date('Y-m-d H:i:s');

                    $allowedFileType = ['application/vnd.ms-excel', 'text/xls', 'text/xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];

                    if (in_array($_FILES["file"]["type"], $allowedFileType)) {

                        $targetPath = 'uploads/' . $_FILES['file']['name'];
                        move_uploaded_file($_FILES['file']['tmp_name'], $targetPath);

                        $Reader = new SpreadsheetReader($targetPath);
                        $sheetCount = count($Reader->sheets());
                        if($sheetCount > 0){
                            $sql = "INSERT INTO tbl_dcr_verification SET dcrdate='$dcrdate',
                            certificate_no='$certificate_no',created_at='$CreatedDate',
                            created_by='$user_id'";
                            $conn->query($sql);
                            $dcr_id = mysqli_insert_id($conn);
                        }
                        for ($i = 0; $i < $sheetCount; $i++) {

                            $Reader->ChangeSheet($i);

                            foreach ($Reader as $Row) {

                                $serialno = "";
                                if (isset($Row[0])) {
                                    $serialno = mysqli_real_escape_string($conn, $Row[0]);
                                }

                                $vattage = "";
                                if (isset($Row[1])) {
                                    $vattage = mysqli_real_escape_string($conn, $Row[1]);
                                }

                                $CreatedTime = date('h:i a');

                                if (!empty($serialno)) {
                                    $sql = "SELECT SerialNo FROM `tbl_stocks` WHERE ProdType='1' 
                                    AND SerialNo!='' AND SellType='Purchase' AND SerialNo='$serialno'";
                                    $rncnt = getRow($sql);
                                    if($rncnt > 0){
                                        $qx = "INSERT INTO tbl_dcr_verification_serialno SET dcr_id='$dcr_id',
                                    dcrdate='$dcrdate',certificate_no='$certificate_no',serialno='$serialno',
                                    vattage='$vattage',match_status=1";
                                    $conn->query($qx);
                                    }
                                    else{
                                        $qx = "INSERT INTO tbl_dcr_verification_serialno SET dcr_id='$dcr_id',
                                    dcrdate='$dcrdate',certificate_no='$certificate_no',serialno='$serialno',
                                    vattage='$vattage',match_status=0";
                                    $conn->query($qx);
                                    }
                                }
                            }
                        }

                        $sql = "DELETE FROM tbl_dcr_verification_serialno WHERE serialno='serialno'";
                        $conn->query($sql);
                ?>
                        <script>
                            alert("Excel Data Imported into the Database");
                            window.location.href = 'view-uploaded-dcr.php';
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
                        <h4 class="font-weight-bold py-3 mb-0">Upload DCR Verification Excel</h4>

                        <div class="card mb-4">
                            <div class="card-body">
                                <form id="validation-form" method="post" autocomplete="off" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div id="alert_message"></div>
                                            <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" id="userid">
                                            <input type="hidden" name="action" value="Save" id="action">
                                            <div class="form-row">

                                                <div class="form-group col-md-3">
                                                    <label class="form-label">Date </label>
                                                    <input type="date" name="dcrdate" id="dcrdate" class="form-control"
                                                        placeholder="" value="<?php echo $row7["dcrdate"]; ?>"
                                                        autocomplete="off">
                                                    <div class="clearfix"></div>
                                                </div>

                                                <div class="form-group col-md-3">
                                                    <label class="form-label">Certificate No </label>
                                                    <input type="text" name="certificate_no" id="certificate_no" class="form-control"
                                                        placeholder="" value="<?php echo $row7["certificate_no"]; ?>"
                                                        autocomplete="off">
                                                    <div class="clearfix"></div>
                                                </div>
                                                <div class="form-group col-md-6"></div>
                                                <div class="form-group col-md-6">
                                                    <label class="form-label">Upload Excel File <span class="text-danger">*</span> 
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <a href="sample_files/sample_dcr_excel.xlsx" download>Download & Upload Sample Excel File</a>
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
</body>
</html>