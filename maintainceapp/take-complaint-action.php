<?php
session_start();
include_once 'config.php';
include_once 'auth.php';
require_once __DIR__ . '/../admin/inc-complaint-engg-action-report-fields.php';
complaintEnggActionReportEnsureSchema($conn);

$user_id = (int) ($_SESSION['Admin']['id'] ?? 0);
$MainPage = 'Customers';
$Page = 'View-Customers';
$id = isset($_REQUEST['id']) ? (int) $_REQUEST['id'] : 0;
$formError = '';

if ($id <= 0) {
    header('Location: pending-complaints.php');
    exit;
}

if (isset($_POST['submit'])) {
    $actionId = (int) ($_POST['action_id'] ?? 0);
    $CustId = $conn->real_escape_string(trim((string) ($_POST['CustId'] ?? '')));
    $CreatedDate = date('Y-m-d H:i:s');
    $BeneficiaryId = $conn->real_escape_string(trim((string) ($_POST['BeneficiaryId'] ?? '')));
    $Lattitude = $conn->real_escape_string(trim((string) ($_POST['Lattitude'] ?? '')));
    $Longitude = $conn->real_escape_string(trim((string) ($_POST['Longitude'] ?? '')));
    $CustName = $conn->real_escape_string(trim((string) ($_POST['CustName'] ?? '')));
    $Specify = $conn->real_escape_string(trim((string) ($_POST['Specify'] ?? '')));
    $ServiceDate = $conn->real_escape_string(trim((string) ($_POST['ServiceDate'] ?? '')));
    $RelatedIssue = $conn->real_escape_string(trim((string) ($_POST['RelatedIssue'] ?? '')));
    $IssueId = (int) ($_POST['Issue'] ?? 0);
    if ($IssueId <= 0) {
        $complaintIssue = getRecord("SELECT Issue FROM tbl_service_complaint WHERE id='$id'");
        $IssueId = (int) ($complaintIssue['Issue'] ?? 0);
    }
    $ClainStatus = $conn->real_escape_string(trim((string) ($_POST['ClainStatus'] ?? '')));
    $Remark = $conn->real_escape_string(trim((string) ($_POST['Remark'] ?? '')));
    $SerialNo = $conn->real_escape_string(trim((string) ($_POST['SerialNo'] ?? '')));
    $Problem = $conn->real_escape_string(trim((string) ($_POST['Problem'] ?? '')));
    $reportFields = complaintEnggActionReportCollectPost($conn);
    $reportSql = complaintEnggActionReportSqlSet($reportFields);

    $uploadedPhotos = [];
    if (!empty($_FILES['Photo'])) {
        $uploadedPhotos = complaintEnggActionUploadPhotos($_FILES['Photo'], dirname(__DIR__) . '/uploads');
    }
    $existingPhotos = trim((string) ($_POST['OldPhoto'] ?? ''));
    $photoDbValue = complaintEnggActionMergePhotos($existingPhotos, $uploadedPhotos);
    $Photo = $conn->real_escape_string($photoDbValue);

    $previousPhotoValue = '';
    if ($actionId > 0) {
        $prevAction = getRecord("SELECT Photo FROM tbl_complaint_engg_actions WHERE id='$actionId' AND CompId='$id'");
        $previousPhotoValue = $prevAction['Photo'] ?? '';
    }

    $actionSet = "EnggId='$user_id',CustId='$CustId',BeneficiaryId='$BeneficiaryId',CustName='$CustName',ServiceDate='$ServiceDate',RelatedIssue='$RelatedIssue',Issue='$IssueId',ClainStatus='$ClainStatus',Specify='$Specify',Remark='$Remark',Photo='$Photo',Lattitude='$Lattitude',Longitude='$Longitude',Problem='$Problem',SerialNo='$SerialNo', $reportSql";

    if ($actionId > 0) {
        $sql = "UPDATE tbl_complaint_engg_actions SET $actionSet, ModifiedDate='$CreatedDate', ModifiedBy='$user_id' WHERE id='$actionId' AND CompId='$id'";
    } else {
        $sql = "INSERT INTO tbl_complaint_engg_actions SET EnggId='$user_id',CompId='$id',CustId='$CustId',BeneficiaryId='$BeneficiaryId',CustName='$CustName',ServiceDate='$ServiceDate',RelatedIssue='$RelatedIssue',Issue='$IssueId',ClainStatus='$ClainStatus',Specify='$Specify',Remark='$Remark',Photo='$Photo',Lattitude='$Lattitude',Longitude='$Longitude',CreatedBy='$user_id',CreatedDate='$CreatedDate',Problem='$Problem',SerialNo='$SerialNo', $reportSql";
    }

    try {
        $saved = $conn->query($sql);
        $saveError = '';
    } catch (mysqli_sql_exception $e) {
        $saved = false;
        $saveError = $e->getMessage();
    }

    if (!$saved) {
        $_SESSION['complaint_action_error'] = 'Could not save complaint action. ' . ($saveError !== '' ? $saveError : $conn->error);
        header('Location: take-complaint-action.php?id=' . $id);
        exit;
    }

    complaintEnggActionDeleteRemovedPhotoFiles(dirname(__DIR__) . '/uploads', $previousPhotoValue, $photoDbValue);

    if ($ClainStatus === 'Not Solved') {
        $conn->query("UPDATE tbl_service_complaint SET ClainStatus='$ClainStatus',EnggSolveStatus='Not Solved',EnggAssignStatus='0',EnggAssignId=0,EnggAssignDate='' WHERE id='$id'");
    } elseif ($ClainStatus === 'Under Maintaince') {
        $conn->query("UPDATE tbl_service_complaint SET EnggSolveStatus='Under Maintaince' WHERE id='$id'");
    } else {
        $conn->query("UPDATE tbl_service_complaint SET ClainStatus='$ClainStatus',EnggSolveStatus='$ClainStatus',EnggAssignStatus='1' WHERE id='$id'");
    }

    header('Location: pending-complaints.php');
    exit;
}

$sql = "SELECT tp.*,tc.Name As IssueName,tu.BeneficiaryId,tu.EmailId,tu.ProjectType FROM tbl_service_complaint tp
    LEFT JOIN tbl_issues tc ON tc.id=tp.Issue
    LEFT JOIN tbl_users tu ON tu.id=tp.CustId WHERE tp.id='$id'";
$row7 = getRecord($sql);
if (empty($row7)) {
    header('Location: pending-complaints.php');
    exit;
}

if (!empty($_SESSION['complaint_action_error'])) {
    $formError = $_SESSION['complaint_action_error'];
    unset($_SESSION['complaint_action_error']);
}

$rowAction = complaintEnggActionGetLatest($conn, $id);
if (!is_array($rowAction)) {
    $rowAction = [];
}
$actionId = (int) ($rowAction['id'] ?? 0);
$savedPhotos = complaintEnggActionPhotoList($rowAction['Photo'] ?? '');
$serviceDateValue = complaintEnggActionFormValue($rowAction, 'ServiceDate', date('Y-m-d'));
$relatedIssueValue = complaintEnggActionFormValue($rowAction, 'RelatedIssue', $row7['RelatedIssue'] ?? '');
$problemValue = complaintEnggActionFormValue($rowAction, 'Problem', $row7['Problem'] ?? '');
$clainStatusValue = complaintEnggActionFormValue($rowAction, 'ClainStatus', $row7['ClainStatus'] ?? '');
$specifyValue = complaintEnggActionFormValue($rowAction, 'Specify', $row7['Specify'] ?? '');
$remarkValue = complaintEnggActionFormValue($rowAction, 'Remark', '');
$latitudeValue = complaintEnggActionFormValue($rowAction, 'Lattitude', $Latitude ?? '');
$longitudeValue = complaintEnggActionFormValue($rowAction, 'Longitude', $Longitude ?? '');
$oldPhotoValue = complaintEnggActionPhotosToDbValue($savedPhotos);

$CellNo = $row7['CellNo'] ?? '';
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

        <div class="main-container">

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Take Complaint Action

</h4>

<div class="card">

<div class="card-body">
<div id="alert_message"></div>
<?php if ($formError !== '') { ?>
<div class="alert alert-danger"><?php echo htmlspecialchars($formError); ?></div>
<?php } ?>
<form id="validation-form" method="post" enctype="multipart/form-data">
<div class="form-row">

   <input type="hidden" name="CustId" id="CustId" class="form-control" placeholder="" value="<?php echo $row7['CustId']; ?>" autocomplete="off" readonly>
   <input type="hidden" name="Issue" value="<?php echo (int) ($row7['Issue'] ?? 0); ?>">
   <input type="hidden" name="action_id" value="<?php echo $actionId; ?>">
<div class="form-group col-md-4">
                                            <label class="form-label">Beneficiary ID </label>
                                            <input type="text" name="BeneficiaryId" class="form-control"
                                                placeholder="" value="<?php echo $row7['BeneficiaryId']; ?>"
                                                autocomplete="off" readonly>
                                            <div class="clearfix"></div>
                                        </div> 

<div class="form-group col-md-4" style="padding-bottom: 20px;">
                                            <label class="form-label">Customer Name </label>
                                            <input type="text" name="CustName" class="form-control"
                                                placeholder="" value="<?php echo $row7['CustName']; ?>"
                                                autocomplete="off" readonly>
                                            <div class="clearfix"></div>
                                        </div> 
                                        
                <style>
.device-table {
    width: 100%;
    border-collapse: collapse;
    background: #ffffff;
    font-family: "Segoe UI", Arial, sans-serif;
    box-shadow: 0 6px 18px rgba(0,0,0,0.08);
    border-radius: 10px;
    overflow: hidden;
}

.device-table th {
    background: linear-gradient(135deg, #0d6efd, #084298);
    color: #fff;
    padding: 12px 14px;
    text-align: left;
    font-size: 15px;
    font-weight: 600;
    width: 35%;
    letter-spacing: 0.5px;
}

.device-table td {
    padding: 12px 14px;
    font-size: 14px;
    color: #333;
    border-bottom: 1px solid #e9ecef;
    background: #fdfdfd;
}

.device-table tr:nth-child(even) td {
    background: #f8f9fa;
}

.device-table tr:hover td {
    background: #eef4ff;
}

.device-table .empty {
    color: #999;
    font-style: italic;
}

.device-title {
    font-size: 18px;
    font-weight: 700;
    margin-bottom: 10px;
    color: #0d6efd;
    border-left: 5px solid #0d6efd;
    padding-left: 10px;
}

.saved-photo-item {
    position: relative;
    display: inline-block;
}
.saved-photo-thumb {
    width: 80px;
    height: 80px;
    object-fit: cover;
}
.saved-photo-item .delete-saved-photo {
    position: absolute;
    top: -6px;
    right: -6px;
    width: 22px;
    height: 22px;
    padding: 0;
    line-height: 18px;
    border-radius: 50%;
    font-size: 16px;
    font-weight: bold;
}

/* ✅ Make table mobile responsive */
.device-table {
    width: 100%;
    border-collapse: collapse;
}

/* ✅ Prevent select from overflowing */
.device-table td {
    max-width: 100%;
    overflow: hidden;
}

/* ✅ Proper select sizing on mobile */
.device-table select.form-control {
    width: 100% !important;
    min-width: 110px;
    max-width: 100%;
    padding: 6px 8px;
    font-size: 14px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* ✅ Mobile specific fix */
@media (max-width: 768px) {

    .device-table th {
        width: 35% !important;
        font-size: 13px;
        padding: 8px;
    }

    .device-table td {
        width: 65% !important;
        padding: 8px;
    }

    .device-table td:last-child {
        width: 100% !important;
        display: block;
        margin-top: 6px;
    }

    .device-table select.form-control {
        width: 100% !important;
        font-size: 13px;
    }
}

</style>
<br><br><br>
<div class="device-title">Installed Equipment Details</div>

<table class="device-table">

    <!-- ✅ Pump -->
    <tr>
        <th>Pump No</th>
        <?php 
        $pumpSql = "SELECT SerialNo FROM tbl_sell_products 
                    WHERE SerialNo != 'N/A' 
                    AND ProductId != 0 
                    AND UserId = '{$row7['CustId']}' 
                    AND ProductName LIKE '%PUMPSET%' 
                    LIMIT 1";
        $pumpRows = getList($pumpSql);
        $pumpOld = !empty($pumpRows) ? $pumpRows[0]['SerialNo'] : "";
        ?>
        <td><?= !empty($pumpOld) ? $pumpOld : "<span class='empty'>Not Available</span>" ?></td>
        <td>
            <select name="new_pump_serial" class="form-control">
                <option value="">Select New Pump Serial</option>
                <?php
                $pumpList = getList("SELECT tsp.SerialNo FROM tbl_sell_products tsp INNER JOIN tbl_sell ts ON ts.id = tsp.SellId WHERE ts.ChallanType = 2 AND ts.CustId = '{$row7['CustId']}' AND tsp.SerialNo IS NOT NULL AND tsp.SerialNo != '' AND tsp.SerialNo != 'N/A' AND tsp.ProductName LIKE '%PUMPSET%'");
                foreach($pumpList as $p){
                    echo "<option value='{$p['SerialNo']}'>{$p['SerialNo']}</option>";
                }
                ?>
            </select>
        </td>
    </tr>

    <!-- ✅ Controller -->
    <tr>
        <th>Controller No</th>
        <?php
        $controllerSql = "SELECT SerialNo FROM tbl_sell_products 
                          WHERE SerialNo != 'N/A' 
                          AND ProductId != 0 
                          AND UserId = '{$row7['CustId']}' 
                          AND ProductName LIKE '%CONTROLLER%' 
                          LIMIT 1";
        $controllerRows = getList($controllerSql);
        $controllerOld = !empty($controllerRows) ? $controllerRows[0]['SerialNo'] : "";
        ?>
        <td><?= !empty($controllerOld) ? $controllerOld : "<span class='empty'>Not Available</span>" ?></td>
        <td>
            <select name="new_controller_serial" class="form-control">
                <option value="">Select New Controller Serial</option>
                <?php
                $controllerList = getList("SELECT tsp.SerialNo FROM tbl_sell_products tsp INNER JOIN tbl_sell ts ON ts.id = tsp.SellId WHERE ts.ChallanType = 2 AND ts.CustId = '{$row7['CustId']}' AND tsp.SerialNo IS NOT NULL AND tsp.SerialNo != '' AND tsp.SerialNo != 'N/A' AND tsp.ProductName LIKE '%CONTROLLER%'");
                foreach($controllerList as $c){
                    echo "<option value='{$c['SerialNo']}'>{$c['SerialNo']}</option>";
                }
                ?>
            </select>
        </td>
    </tr>

    <!-- ✅ Panels -->
    <?php
    $panelSql = "SELECT SerialNo FROM tbl_sell_products 
                 WHERE SerialNo != 'N/A' 
                 AND ProductId != 0 
                 AND UserId = '{$row7['CustId']}' 
                 AND ProductName LIKE '%PV MODULE%'";
    $panelRows = getList($panelSql);

    if (!empty($panelRows)) {
        $i = 1;
        foreach ($panelRows as $panel) {
            ?>
            <tr>
                <th>Panel No <?= $i ?></th>
                <td><?= $panel['SerialNo'] ?></td>
                <td>
                    <select name="new_panel_serial[]" class="form-control">
                        <option value="">Select New Panel Serial</option>
                        <?php
                        $panelList = getList("SELECT tsp.SerialNo FROM tbl_sell_products tsp INNER JOIN tbl_sell ts ON ts.id = tsp.SellId WHERE ts.ChallanType = 2 AND ts.CustId = '{$row7['CustId']}' AND tsp.SerialNo IS NOT NULL AND tsp.SerialNo != '' AND tsp.SerialNo != 'N/A' AND tsp.ProductName LIKE '%PV MODULE%'");
                        foreach($panelList as $p){
                            echo "<option value='{$p['SerialNo']}'>{$p['SerialNo']}</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <?php
            $i++;
        }
    } else {
        ?>
        <tr>
            <th>Panel No</th>
            <td><span class='empty'>Not Available</span></td>
            <td>
                <select class="form-control" disabled>
                    <option>No Panels Available</option>
                </select>
            </td>
        </tr>
        <?php
    }
    ?>

</table>

                       
       
<div class="form-group col-md-4">
                                            <label class="form-label"> Date </label>
                                            <input type="date" name="ServiceDate" id="ServiceDate" class="form-control"
                                                placeholder="" value="<?php echo htmlspecialchars($serviceDateValue); ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div> 
        
      
                                       
<div class="form-group col-md-4">
<label class="form-label"> Service Related Issue<span class="text-danger">*</span></label>
 <select class="form-control" name="RelatedIssue" id="RelatedIssue" required>

<option selected="" value="">Select Related Issue</option>

  <option value="Repair" <?php if($relatedIssueValue == 'Repair'){?> selected <?php } ?>>Repair</option>
    <option value="Replacement" <?php if($relatedIssueValue == 'Replacement'){?> selected <?php } ?>>Replacement</option>
</select>
<div class="clearfix"></div>
</div>

<!-- <div class="form-group col-lg-4">
<label class="form-label"> Issue<span class="text-danger">*</span></label>
 <select class="form-control" name="Issue" id="Issue" required>
<option selected="" value="">Select Issue</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_issues WHERE Status='1'";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7['Issue'] == $result['id']){?> selected <?php } ?> value="<?php echo $result['id'];?>">
    <?php echo $result['Name']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div> -->

<div class="form-group col-md-4">
                                            <label class="form-label">Problems </label>
                                            <textarea class="form-control"
                                                placeholder="" name="Problem"
                                                autocomplete="off" ><?php echo htmlspecialchars($problemValue); ?></textarea>
                                            <div class="clearfix"></div>
                                        </div> 

<!--<div class="form-group col-md-4">
                                            <label class="form-label"> Serial No </label>
                                            <input type="text" name="SerialNo" id="SerialNo" class="form-control"
                                                placeholder="" value=""
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div> -->

 <div class="form-group col-md-3 Pump">
                                            <label class="form-label">Status </label>
                                            <select class="form-control" id="ClainStatus" name="ClainStatus">
<option <?php if($clainStatusValue=='Under Maintaince'){ ?> selected <?php } ?> value="Under Maintaince">Under Maintaince</option> 
<option <?php if($clainStatusValue=='Issue Solved'){ ?> selected <?php } ?> value="Issue Solved">Issue Solved</option>
 <option <?php if($clainStatusValue=='Not Solved'){ ?> selected <?php } ?> value="Not Solved">Not Solved</option>
          
</select>
                                           
                                            <div class="clearfix"></div>
                                        </div>  

                                        <div class="form-group col-md-4">
                                            <label class="form-label">If Not Solved, Specify </label>
                                            <textarea name="Specify" id="Specify" class="form-control"
                                                placeholder=""
                                                autocomplete="off"><?php echo htmlspecialchars($specifyValue); ?></textarea>
                                            <div class="clearfix"></div>
                                        </div> 

                                           <div class="form-group col-md-4">
                                            <label class="form-label">Remark </label>
                                            <textarea name="Remark" id="Remark" class="form-control"
                                                placeholder=""
                                                autocomplete="off"><?php echo htmlspecialchars($remarkValue); ?></textarea>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="device-title">Field Service Report Details</div>
                                        </div>
                                        <?php foreach (complaintEnggActionReportFieldDefinitions() as $fieldName => $fieldDef) {
                                            $inputType = $fieldDef['input'] ?? 'text';
                                            $colClass = $inputType === 'textarea' ? 'col-md-6' : 'col-md-4';
                                            $fieldValue = complaintEnggActionFormValue($rowAction, $fieldName);
                                        ?>
                                        <div class="form-group <?php echo $colClass; ?>">
                                            <label class="form-label"><?php echo htmlspecialchars($fieldDef['label']); ?></label>
                                            <?php if ($inputType === 'textarea') { ?>
                                            <textarea name="<?php echo htmlspecialchars($fieldName); ?>" class="form-control" rows="2" autocomplete="off"><?php echo htmlspecialchars($fieldValue); ?></textarea>
                                            <?php } else { ?>
                                            <input type="text" name="<?php echo htmlspecialchars($fieldName); ?>" class="form-control" value="<?php echo htmlspecialchars($fieldValue); ?>" autocomplete="off">
                                            <?php } ?>
                                            <div class="clearfix"></div>
                                        </div>
                                        <?php } ?> 

                                          <div class="form-group col-md-12">
                                            <label class="form-label">Photo from site</label>
                                            <?php if (!empty($savedPhotos)) { ?>
                                            <div class="mb-2" id="saved-photos-section">
                                                <small class="text-muted d-block mb-2">Saved photos (click × to remove):</small>
                                                <div class="d-flex flex-wrap" id="saved-photos-wrap">
                                                <?php foreach ($savedPhotos as $photoFile) { ?>
                                                    <div class="saved-photo-item mr-2 mb-2" data-photo="<?php echo htmlspecialchars($photoFile); ?>">
                                                        <a href="../uploads/<?php echo htmlspecialchars($photoFile); ?>" target="_blank">
                                                            <img src="../uploads/<?php echo htmlspecialchars($photoFile); ?>" alt="Site photo" class="img-thumbnail saved-photo-thumb">
                                                        </a>
                                                        <button type="button" class="btn btn-danger btn-sm delete-saved-photo" title="Delete photo">&times;</button>
                                                    </div>
                                                <?php } ?>
                                                </div>
                                            </div>
                                            <?php } ?>
                                            <small class="text-muted d-block mb-2">Upload one or more images. Use the button below to add more photo fields.</small>
                                            <div id="photo-inputs">
                                                <div class="photo-input-row mb-2 d-flex align-items-center">
                                                    <input type="file" name="Photo[]" accept="image/jpeg,image/png,image/*,.jpg,.jpeg,.png" capture="environment" class="form-control site-photo-input ignore" style="opacity: 1;">
                                                    <button type="button" class="btn btn-outline-danger btn-sm ml-2 remove-photo-input" style="display:none;">Remove</button>
                                                </div>
                                            </div>
                                            <button type="button" id="addPhotoBtn" class="btn btn-outline-primary btn-sm mt-1">+ Add more photos</button>
                                            <input type="hidden" name="OldPhoto" value="<?php echo htmlspecialchars($oldPhotoValue); ?>" id="OldPhoto">
                                        </div>


                                        <div class="form-group col-md-3">
<label class="form-label">Lattitude </label>
<input type="text" name="Lattitude" id="Lattitude" class="form-control" placeholder="" value="<?php echo htmlspecialchars($latitudeValue); ?>" readonly>
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Longitude </label>
<input type="text" name="Longitude" id="Longitude" class="form-control" placeholder="" value="<?php echo htmlspecialchars($longitudeValue); ?>" readonly>
<div class="clearfix"></div>
</div>

</div>


<br>
<button type="submit" name="submit" class="btn btn-primary btn-finish">Submit</button>
</form>
</div>
</div>
</div>
<br><br>

<?php include_once 'footer.php'; ?>

</div>

</main>

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

<script>
    function featured(id){
        if($('#Check_Id'+id).prop('checked') == true) {
            $('#CheckId'+id).val(1);
        }
        else{
           $('#CheckId'+id).val(0);
            }
        }


    function getItemLists(id){
        window.location.href="dispatch-order.php?CustId="+id;
    }

    $(document).ready(function() {
    $('#example').DataTable({
       "scrollX": true,
         paging: false,
    ordering: false,
    info: false,
    searching: false,
    });

    function getOldPhotoList() {
        var val = $.trim($('#OldPhoto').val());
        if (!val) {
            return [];
        }
        return val.split(',').map(function(item) {
            return $.trim(item);
        }).filter(Boolean);
    }

    function setOldPhotoList(list) {
        $('#OldPhoto').val(list.join(','));
    }

    $(document).on('click', '.delete-saved-photo', function(e) {
        e.preventDefault();
        e.stopPropagation();
        if (!confirm('Remove this photo?')) {
            return;
        }
        var $item = $(this).closest('.saved-photo-item');
        var filename = String($item.data('photo') || '');
        var list = getOldPhotoList().filter(function(file) {
            return file !== filename;
        });
        setOldPhotoList(list);
        $item.remove();
        if ($('#saved-photos-wrap .saved-photo-item').length === 0) {
            $('#saved-photos-section').remove();
        }
    });

    function refreshPhotoRemoveButtons() {
        var rows = $('#photo-inputs .photo-input-row');
        rows.find('.remove-photo-input').toggle(rows.length > 1);
    }

    $('#addPhotoBtn').on('click', function() {
        var row = $('<div class="photo-input-row mb-2 d-flex align-items-center">' +
            '<input type="file" name="Photo[]" accept="image/jpeg,image/png,image/*,.jpg,.jpeg,.png" capture="environment" class="form-control site-photo-input ignore" style="opacity: 1;">' +
            '<button type="button" class="btn btn-outline-danger btn-sm ml-2 remove-photo-input">Remove</button>' +
            '</div>');
        $('#photo-inputs').append(row);
        refreshPhotoRemoveButtons();
    });

    $(document).on('click', '.remove-photo-input', function() {
        $(this).closest('.photo-input-row').remove();
        refreshPhotoRemoveButtons();
    });

});
</script>
</body>
</html>
