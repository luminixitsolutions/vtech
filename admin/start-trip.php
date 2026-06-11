<?php
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = 'Trip-Details';
$Page = 'Running-Trips';

$estimateKmCol = $conn->query("SHOW COLUMNS FROM tbl_trip_details LIKE 'EstimateKm'");
if (!$estimateKmCol || $estimateKmCol->num_rows === 0) {
    $conn->query("ALTER TABLE tbl_trip_details ADD COLUMN EstimateKm DECIMAL(12,2) NOT NULL DEFAULT 0.00 AFTER OpeningReading");
}

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    header('Location: running-trips.php');
    exit;
}

$row7 = getRecord("SELECT * FROM tbl_trip_details WHERE id='$id'");
if (!$row7) {
    header('Location: running-trips.php');
    exit;
}

$drivers = getList("SELECT id, Fname, Lname, VehicalNo FROM tbl_users WHERE Roll=39 AND Status=1 ORDER BY Fname") ?: [];
$driverId = (int) ($row7['DriverId'] ?? 0);
$driverName = trim($row7['DriverName'] ?? '');
$vehicalNo = trim($row7['VehicalNo'] ?? '');

if (isset($_POST['submit'])) {
    $driverId = (int) ($_POST['DriverId'] ?? 0);
    $driver = getRecord("SELECT id, Fname, Lname, VehicalNo FROM tbl_users WHERE id='$driverId' AND Roll=39 AND Status=1 LIMIT 1");
    if (!$driver) {
        echo "<script>alert('Please select a valid driver.');history.back();</script>";
        exit;
    }

    $otherTrip = getRecord("SELECT id FROM tbl_trip_details WHERE DriverId='$driverId' AND Status=0 AND id!='$id' LIMIT 1");
    if ($otherTrip) {
        echo "<script>alert('This driver already has a running trip.');history.back();</script>";
        exit;
    }

    $DriverName = addslashes(trim($driver['Fname'] . ' ' . $driver['Lname']));
    $VehicalNo = addslashes(trim($driver['VehicalNo']));
    $InDate = addslashes(trim($_POST['InDate'] ?? ''));
    $TripDetails = addslashes(trim($_POST['TripDetails'] ?? ''));
    $OpeningReading = addslashes(trim($_POST['OpeningReading'] ?? ''));
    $EstimateKm = trim($_POST['EstimateKm'] ?? '');
    if ($EstimateKm === '' || !is_numeric($EstimateKm) || (float) $EstimateKm < 0) {
        echo "<script>alert('Please enter Estimate Km.');history.back();</script>";
        exit;
    }
    $EstimateKm = addslashes($EstimateKm);
    $StartLattitude = addslashes(trim($_POST['StartLattitude'] ?? ''));
    $StartLongitude = addslashes(trim($_POST['StartLongitude'] ?? ''));
    $CreatedDate = date('Y-m-d');
    $CreatedTime = date('H:i:s');

    $StartPhoto = $_POST['OldStartPhoto'] ?? '';
    if (!empty($_FILES['StartPhoto']['name'])) {
        $randno = rand(1, 100);
        $src = $_FILES['StartPhoto']['tmp_name'];
        $fnm = substr($_FILES['StartPhoto']['name'], 0, strrpos($_FILES['StartPhoto']['name'], '.'));
        $fnm = str_replace(' ', '_', $fnm);
        $ext = substr($_FILES['StartPhoto']['name'], strpos($_FILES['StartPhoto']['name'], '.'));
        $dest = '../uploads/' . $randno . '_' . $fnm . $ext;
        $imagepath = $randno . '_' . $fnm . $ext;
        if (move_uploaded_file($src, $dest)) {
            $StartPhoto = $imagepath;
        }
    }

    $sql = "UPDATE tbl_trip_details SET DriverId='$driverId',DriverName='$DriverName',VehicalNo='$VehicalNo',InDate='$InDate',TripDetails='$TripDetails',OpeningReading='$OpeningReading',EstimateKm='$EstimateKm',StartLattitude='$StartLattitude',StartLongitude='$StartLongitude',StartPhoto='$StartPhoto',ModifiedBy='$user_id',ModifiedDate='$CreatedDate',ModifiedTime='$CreatedTime' WHERE id='$id'";
    $conn->query($sql);
    echo "<script>alert('Trip updated successfully.');window.location.href='running-trips.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | Edit Trip</title>
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
<h4 class="font-weight-bold mb-0">Edit Trip</h4>
<a href="running-trips.php" class="btn btn-secondary btn-sm">Back to Running Trips</a>
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
<label class="form-label">In Time</label>
<input type="text" class="form-control" value="<?php echo htmlspecialchars($row7['InTime'] ?? ''); ?>" readonly>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
<label class="form-label">In Date <span class="text-danger">*</span></label>
<input type="date" name="InDate" id="InDate" class="form-control" value="<?php echo htmlspecialchars($row7['InDate'] ?? ''); ?>" required>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-12">
<label class="form-label">Select Driver <span class="text-danger">*</span></label>
<select name="DriverId" id="DriverId" class="form-control select2-demo" required>
<option value="">Select Driver</option>
<?php foreach ($drivers as $driverRow) {
    $dId = (int) $driverRow['id'];
    $dName = trim($driverRow['Fname'] . ' ' . $driverRow['Lname']);
    $dVeh = trim($driverRow['VehicalNo']);
?>
<option value="<?php echo $dId; ?>"
    data-name="<?php echo htmlspecialchars($dName); ?>"
    data-vehical="<?php echo htmlspecialchars($dVeh); ?>"
    <?php if ($driverId === $dId) { echo 'selected'; } ?>>
<?php echo htmlspecialchars($dName . ' - ' . $dVeh); ?>
</option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
<label class="form-label">Driver Name</label>
<input type="text" name="DriverName" id="DriverName" class="form-control" value="<?php echo htmlspecialchars($driverName); ?>" readonly>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
<label class="form-label">Vehicle No</label>
<input type="text" name="VehicalNo" id="VehicalNo" class="form-control" value="<?php echo htmlspecialchars($vehicalNo); ?>" readonly>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-12">
<label class="form-label">Trip Details <span class="text-danger">*</span></label>
<textarea name="TripDetails" id="TripDetails" class="form-control" rows="3" required><?php echo htmlspecialchars($row7['TripDetails'] ?? ''); ?></textarea>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
<label class="form-label">Opening Reading <span class="text-danger">*</span></label>
<input type="number" name="OpeningReading" id="OpeningReading" class="form-control" value="<?php echo htmlspecialchars($row7['OpeningReading'] ?? ''); ?>" required min="0">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
<label class="form-label">Estimate Km <span class="text-danger">*</span></label>
<input type="number" name="EstimateKm" id="EstimateKm" class="form-control" value="<?php echo htmlspecialchars($row7['EstimateKm'] ?? ''); ?>" required min="0" step="0.01">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
<label class="form-label">Opening Reading Photo</label>
<input type="file" class="form-control" name="StartPhoto">
<input type="hidden" name="OldStartPhoto" value="<?php echo htmlspecialchars($row7['StartPhoto'] ?? ''); ?>" id="OldStartPhoto">
<?php if (!empty($row7['StartPhoto'])) { ?>
<div class="mt-2">
<img src="../uploads/<?php echo htmlspecialchars($row7['StartPhoto']); ?>" alt="" class="img-fluid border rounded" style="width:96px;height:96px;object-fit:cover;">
</div>
<?php } ?>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
<label class="form-label">Latitude</label>
<input type="text" name="StartLattitude" id="StartLattitude" class="form-control" value="<?php echo htmlspecialchars($row7['StartLattitude'] ?? ''); ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
<label class="form-label">Longitude</label>
<input type="text" name="StartLongitude" id="StartLongitude" class="form-control" value="<?php echo htmlspecialchars($row7['StartLongitude'] ?? ''); ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-12 mt-2">
<button type="submit" name="submit" class="btn btn-primary">Update Trip</button>
<a href="running-trips.php" class="btn btn-secondary">Cancel</a>
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
function updateDriverFields() {
    var $opt = $('#DriverId option:selected');
    $('#DriverName').val($opt.data('name') || '');
    $('#VehicalNo').val($opt.data('vehical') || '');
}
$(function() {
    $('#DriverId').on('change', updateDriverFields);
});
</script>
</body>
</html>
