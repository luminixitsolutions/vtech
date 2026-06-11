<?php
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = 'Trip-Details';
$Page = 'Completed-Trips';

$estimateKmCol = $conn->query("SHOW COLUMNS FROM tbl_rooftop_trip_details LIKE 'EstimateKm'");
if (!$estimateKmCol || $estimateKmCol->num_rows === 0) {
    $conn->query("ALTER TABLE tbl_rooftop_trip_details ADD COLUMN EstimateKm DECIMAL(12,2) NOT NULL DEFAULT 0.00 AFTER OpeningReading");
}

$challanPaidByCol = $conn->query("SHOW COLUMNS FROM tbl_rooftop_trip_details LIKE 'ChallanPaidBy'");
if (!$challanPaidByCol || $challanPaidByCol->num_rows === 0) {
    $conn->query("ALTER TABLE tbl_rooftop_trip_details ADD COLUMN ChallanPaidBy VARCHAR(50) NOT NULL DEFAULT '' AFTER Challan");
}

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    header('Location: completed-trips.php');
    exit;
}

$row7 = getRecord("SELECT * FROM tbl_rooftop_trip_details WHERE id='$id'");
if (!$row7) {
    header('Location: completed-trips.php');
    exit;
}

if (empty($row7['OutDate'])) {
    $row7['OutDate'] = date('Y-m-d');
}

if (isset($_POST['submit'])) {
    $OutDate = addslashes(trim($_POST['OutDate'] ?? ''));
    $ClosingReading = addslashes(trim($_POST['ClosingReading'] ?? ''));
    $EstimateKm = trim($_POST['EstimateKm'] ?? '');
    $Fastag = addslashes(trim($_POST['Fastag'] ?? '0'));
    $Challan = addslashes(trim($_POST['Challan'] ?? '0'));
    $ChallanPaidBy = addslashes(trim($_POST['ChallanPaidBy'] ?? ''));
    $EndLattitude = addslashes(trim($_POST['EndLattitude'] ?? ''));
    $EndLongitude = addslashes(trim($_POST['EndLongitude'] ?? ''));
    $CreatedDate = date('Y-m-d');
    $CreatedTime = date('H:i:s');

    if ($OutDate === '') {
        echo "<script>alert('Please enter Out Date.');history.back();</script>";
        exit;
    }
    if ($ClosingReading === '') {
        echo "<script>alert('Please enter Closing Reading.');history.back();</script>";
        exit;
    }
    if ($EstimateKm === '' || !is_numeric($EstimateKm) || (float) $EstimateKm < 0) {
        echo "<script>alert('Please enter Estimate Km.');history.back();</script>";
        exit;
    }
    $EstimateKm = addslashes($EstimateKm);

    $Photo = $_POST['OldPhoto'] ?? '';
    if (!empty($_FILES['Photo']['name'])) {
        $randno = rand(1, 100);
        $src = $_FILES['Photo']['tmp_name'];
        $fnm = substr($_FILES['Photo']['name'], 0, strrpos($_FILES['Photo']['name'], '.'));
        $fnm = str_replace(' ', '_', $fnm);
        $ext = substr($_FILES['Photo']['name'], strpos($_FILES['Photo']['name'], '.'));
        $dest = '../uploads/' . $randno . '_' . $fnm . $ext;
        $imagepath = $randno . '_' . $fnm . $ext;
        if (move_uploaded_file($src, $dest)) {
            $Photo = $imagepath;
        }
    }

    $EndPhoto = $_POST['OldEndPhoto'] ?? '';
    if (!empty($_FILES['EndPhoto']['name'])) {
        $randno = rand(1, 100);
        $src = $_FILES['EndPhoto']['tmp_name'];
        $fnm = substr($_FILES['EndPhoto']['name'], 0, strrpos($_FILES['EndPhoto']['name'], '.'));
        $fnm = str_replace(' ', '_', $fnm);
        $ext = substr($_FILES['EndPhoto']['name'], strpos($_FILES['EndPhoto']['name'], '.'));
        $dest = '../uploads/' . $randno . '_' . $fnm . $ext;
        $imagepath = $randno . '_' . $fnm . $ext;
        if (move_uploaded_file($src, $dest)) {
            $EndPhoto = $imagepath;
        }
    }

    $sql = "UPDATE tbl_rooftop_trip_details SET OutDate='$OutDate',ClosingReading='$ClosingReading',EstimateKm='$EstimateKm',Fastag='$Fastag',Challan='$Challan',ChallanPaidBy='$ChallanPaidBy',ChallanPhoto='$Photo',EndLattitude='$EndLattitude',EndLongitude='$EndLongitude',EndPhoto='$EndPhoto',Status=1,ModifiedBy='$user_id',ModifiedDate='$CreatedDate',ModifiedTime='$CreatedTime' WHERE id='$id'";
    $conn->query($sql);
    echo "<script>alert('Trip updated successfully.');window.location.href='completed-trips.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | Edit End Trip</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
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
<div class="d-flex justify-content-between align-items-center py-3 mb-0">
<h4 class="font-weight-bold mb-0">Edit End Trip</h4>
<a href="completed-trips.php" class="btn btn-secondary btn-sm">Back to Completed Trips</a>
</div>

<div class="card mb-4">
<div class="card-body">
<form id="validation-form" method="post" enctype="multipart/form-data">
<div class="form-row">

<div class="form-group col-md-4">
<label class="form-label">Trip No</label>
<input type="text" class="form-control" value="<?php echo htmlspecialchars($row7['TripNo'] ?? ''); ?>" readonly>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
<label class="form-label">Driver Name</label>
<input type="text" class="form-control" value="<?php echo htmlspecialchars($row7['DriverName'] ?? ''); ?>" readonly>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
<label class="form-label">Vehicle No</label>
<input type="text" class="form-control" value="<?php echo htmlspecialchars($row7['VehicalNo'] ?? ''); ?>" readonly>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-12">
<label class="form-label">Trip Details</label>
<textarea class="form-control" rows="3" readonly><?php echo htmlspecialchars($row7['TripDetails'] ?? ''); ?></textarea>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
<label class="form-label">In Date</label>
<input type="date" class="form-control" value="<?php echo htmlspecialchars($row7['InDate'] ?? ''); ?>" readonly>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
<label class="form-label">In Time</label>
<input type="text" class="form-control" value="<?php echo htmlspecialchars($row7['InTime'] ?? ''); ?>" readonly>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
<label class="form-label">Estimate Km <span class="text-danger">*</span></label>
<input type="number" name="EstimateKm" id="EstimateKm" class="form-control" value="<?php echo htmlspecialchars($row7['EstimateKm'] ?? ''); ?>" required min="0" step="0.01">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
<label class="form-label">Opening Reading</label>
<input type="text" class="form-control" value="<?php echo htmlspecialchars($row7['OpeningReading'] ?? ''); ?>" readonly>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
<label class="form-label">Out Date <span class="text-danger">*</span></label>
<input type="date" name="OutDate" id="OutDate" class="form-control" value="<?php echo htmlspecialchars($row7['OutDate'] ?? ''); ?>" required>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
<label class="form-label">Out Time</label>
<input type="text" class="form-control" value="<?php echo htmlspecialchars($row7['OutTime'] ?? ''); ?>" readonly>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
<label class="form-label">Closing Reading <span class="text-danger">*</span></label>
<input type="number" name="ClosingReading" id="ClosingReading" class="form-control" value="<?php echo htmlspecialchars($row7['ClosingReading'] ?? ''); ?>" required min="0">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
<label class="form-label">Fastag</label>
<input type="number" step="0.01" min="0" name="Fastag" id="Fastag" class="form-control" value="<?php echo htmlspecialchars($row7['Fastag'] ?? '0'); ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
<label class="form-label">Challan Paid By</label>
<select name="ChallanPaidBy" id="ChallanPaidBy" class="form-control">
<option value="">Select</option>
<option value="vtech" <?php if (($row7['ChallanPaidBy'] ?? '') === 'vtech') { echo 'selected'; } ?>>Paid BY VTECH</option>
<option value="transportor" <?php if (($row7['ChallanPaidBy'] ?? '') === 'transportor') { echo 'selected'; } ?>>Paid By transportor</option>
</select>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
<label class="form-label">Challan Amount</label>
<input type="number" step="0.01" min="0" name="Challan" id="Challan" class="form-control" value="<?php echo htmlspecialchars($row7['Challan'] ?? '0'); ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
<label class="form-label">Closing Reading Photo</label>
<input type="file" class="form-control" name="EndPhoto">
<input type="hidden" name="OldEndPhoto" value="<?php echo htmlspecialchars($row7['EndPhoto'] ?? ''); ?>" id="OldEndPhoto">
<?php if (!empty($row7['EndPhoto'])) { ?>
<div class="mt-2">
<img src="../uploads/<?php echo htmlspecialchars($row7['EndPhoto']); ?>" alt="" class="img-fluid border rounded" style="width:96px;height:96px;object-fit:cover;">
</div>
<?php } ?>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
<label class="form-label">Challan Photo</label>
<input type="file" class="form-control" name="Photo">
<input type="hidden" name="OldPhoto" value="<?php echo htmlspecialchars($row7['ChallanPhoto'] ?? ''); ?>" id="OldPhoto">
<?php if (!empty($row7['ChallanPhoto'])) { ?>
<div class="mt-2">
<img src="../uploads/<?php echo htmlspecialchars($row7['ChallanPhoto']); ?>" alt="" class="img-fluid border rounded" style="width:96px;height:96px;object-fit:cover;">
</div>
<?php } ?>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
<label class="form-label">End Latitude</label>
<input type="text" name="EndLattitude" id="EndLattitude" class="form-control" value="<?php echo htmlspecialchars($row7['EndLattitude'] ?? ''); ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
<label class="form-label">End Longitude</label>
<input type="text" name="EndLongitude" id="EndLongitude" class="form-control" value="<?php echo htmlspecialchars($row7['EndLongitude'] ?? ''); ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-12 mt-2">
<button type="submit" name="submit" class="btn btn-primary">Update Trip</button>
<a href="completed-trips.php" class="btn btn-secondary">Cancel</a>
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
