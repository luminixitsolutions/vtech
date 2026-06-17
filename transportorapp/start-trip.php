<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
include_once 'inc-transportor.php';
$user_id = $_SESSION['User']['id'];
$transportor_id = $user_id;
$MainPage = "Customers";
$Page = "View-Customers";

$estimateKmCol = $conn->query("SHOW COLUMNS FROM tbl_trip_details LIKE 'EstimateKm'");
if (!$estimateKmCol || $estimateKmCol->num_rows === 0) {
    $conn->query("ALTER TABLE tbl_trip_details ADD COLUMN EstimateKm DECIMAL(12,2) NOT NULL DEFAULT 0.00 AFTER OpeningReading");
}

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$driverId = isset($_REQUEST['driver_id']) ? (int) $_REQUEST['driver_id'] : 0;

if (isset($_POST['submit'])) {
    $postId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
    $driverId = (int) ($_POST['DriverId'] ?? 0);
    $driver = transportorGetDriver($transportor_id, $driverId);
    if (!$driver) {
        transportorFlash('error', 'Please select a valid driver.');
        header('Location: start-trip.php' . ($postId > 0 ? '?id=' . $postId : ''));
        exit;
    }
    if (transportorDriverHasRunningTrip($driverId) && $postId <= 0) {
        transportorFlash('error', 'This driver already has a running trip.');
        header('Location: start-trip.php?driver_id=' . $driverId);
        exit;
    }

    $DriverName = addslashes(trim($driver['Fname'] . ' ' . $driver['Lname']));
    $VehicalNo = addslashes(trim($driver['VehicalNo']));
    $InDate = addslashes(trim($_POST['InDate']));
    $TripDetails = addslashes(trim($_POST['TripDetails']));
    $OpeningReading = addslashes(trim($_POST['OpeningReading']));
    $EstimateKm = trim($_POST['EstimateKm'] ?? '');
    if ($EstimateKm === '' || !is_numeric($EstimateKm) || (float) $EstimateKm < 0) {
        transportorFlash('error', 'Please enter Estimate Km.');
        header('Location: start-trip.php?driver_id=' . $driverId . ($postId > 0 ? '&id=' . $postId : ''));
        exit;
    }
    $EstimateKm = addslashes($EstimateKm);
    $StartLattitude = addslashes(trim($_POST['StartLattitude']));
    $StartLongitude = addslashes(trim($_POST['StartLongitude']));
    $CreatedDate = date('Y-m-d');
    $CreatedTime = date('H:i:s');

    $StartPhoto = isset($_POST['OldStartPhoto']) ? $_POST['OldStartPhoto'] : '';
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

    if ($postId <= 0) {
        $sql = "INSERT INTO tbl_trip_details SET DriverId='$driverId',DriverName='$DriverName',VehicalNo='$VehicalNo',InDate='$InDate',TripDetails='$TripDetails',OpeningReading='$OpeningReading',EstimateKm='$EstimateKm',StartLattitude='$StartLattitude',StartLongitude='$StartLongitude',Status=0,CreatedBy='$transportor_id',ModifiedBy=0,CalModifiedBy=0,CreatedDate='$CreatedDate',CreatedTime='$CreatedTime',StartPhoto='$StartPhoto',InTime='$CreatedTime'";
        $conn->query($sql);
        $PostId = mysqli_insert_id($conn);
        $TripNo = rand(1000, 9999) . '' . $PostId;
        $sql2 = "UPDATE tbl_trip_details SET TripNo='$TripNo' WHERE id='$PostId'";
        $conn->query($sql2);
        transportorFlash('success', 'Trip started successfully!');
        header('Location: running-trips.php');
        exit;
    }

    $sql = "UPDATE tbl_trip_details SET InDate='$InDate',TripDetails='$TripDetails',OpeningReading='$OpeningReading',EstimateKm='$EstimateKm',StartPhoto='$StartPhoto' WHERE id='$postId'";
    $conn->query($sql);
    transportorFlash('success', 'Trip updated successfully!');
    header('Location: running-trips.php');
    exit;
}

$row7 = array();
$drivers = getList("SELECT id, Fname, Lname, VehicalNo FROM tbl_users WHERE UnderUser='$transportor_id' AND Roll=39 AND Status=1 ORDER BY Fname") ?: [];
$selectedDriver = $driverId > 0 ? transportorGetDriver($transportor_id, $driverId) : null;
$Name = '';
$row110 = array('VehicalNo' => '');

if ($id > 0) {
    if (!transportorOwnsTrip($transportor_id, $id)) {
        header('Location: running-trips.php');
        exit;
    }
    $sql = "SELECT * FROM tbl_trip_details WHERE id='$id'";
    $row7 = getRecord($sql);
    $driverId = (int) ($row7['DriverId'] ?? 0);
    $selectedDriver = transportorGetDriver($transportor_id, $driverId);
}

if ($selectedDriver) {
    $Name = trim($selectedDriver['Fname'] . ' ' . $selectedDriver['Lname']);
    $row110 = $selectedDriver;
}

$transportorUserRow = getRecord("SELECT Lattitude, Longitude FROM tbl_users WHERE id='$transportor_id' LIMIT 1") ?: [];
$tripCoords = transportorResolveTripLatLong(
    $row7['StartLattitude'] ?? '',
    $row7['StartLongitude'] ?? '',
    $selectedDriver,
    $transportorUserRow
);
$Latitude = $tripCoords['lat'];
$Longitude = $tripCoords['lng'];

if (empty($row7['InDate'])) {
    $row7['InDate'] = date('Y-m-d');
}
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
<h4 class="font-weight-bold py-3 mb-0">Start Trip

</h4>

<div class="card">

<div class="card-body">
<div id="alert_message"></div>
<form id="validation-form" method="post" enctype="multipart/form-data">
<div class="form-row">

       <div class="form-group col-md-12">
<label class="form-label">Select Driver <span class="text-danger">*</span></label>
<select name="DriverId" id="DriverId" class="form-control" required onchange="if(this.value){window.location.href='start-trip.php?driver_id='+this.value;}">
<option value="">Select Driver</option>
<?php foreach ($drivers as $driverRow) { ?>
<option value="<?php echo (int) $driverRow['id']; ?>" <?php if ($driverId == (int) $driverRow['id']) { echo 'selected'; } ?>>
<?php echo htmlspecialchars(trim($driverRow['Fname'] . ' ' . $driverRow['Lname'] . ' - ' . $driverRow['VehicalNo'])); ?>
</option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

       <div class="form-group col-md-3">
<label class="form-label">Driver Name </label>
<input type="text" name="DriverName" id="DriverName" class="form-control" placeholder="" value="<?php echo htmlspecialchars($Name); ?>" readonly>
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Vehicle No </label>
<input type="text" name="VehicalNo" id="VehicalNo" class="form-control" placeholder="" value="<?php echo $row110['VehicalNo']; ?>" readonly>
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
                                            <label class="form-label">In Date <span
                                                    class="text-danger">*</span></label>
                                            <input type="date" name="InDate" id="InDate" class="form-control"
                                                placeholder="" value="<?php echo $row7['InDate']; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div> 
        
      
     
                                        <div class="form-group col-md-4">
                                            <label class="form-label">Trip Details <span
                                                    class="text-danger">*</span> </label>
                                            <textarea name="TripDetails" id="TripDetails" class="form-control"
                                                placeholder=""
                                                autocomplete="off" required><?php echo $row7['TripDetails']; ?></textarea>
                                            <div class="clearfix"></div>
                                        </div> 

<div class="form-group col-md-3">
<label class="form-label">Opening Reading <span class="text-danger">*</span></label>
<input type="number" name="OpeningReading" id="OpeningReading" class="form-control" placeholder="" value="<?php echo $row7['OpeningReading']; ?>" required min="0">
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Estimate Km <span class="text-danger">*</span></label>
<input type="number" name="EstimateKm" id="EstimateKm" class="form-control" placeholder="" value="<?php echo htmlspecialchars($row7['EstimateKm'] ?? ''); ?>" required min="0" step="0.01">
 <div class="clearfix"></div>
</div>

 <div class="form-group col-md-12">
                                            <label class="form-label">Opening Reading Photo <span class="text-danger">*</span></label>
                                            <label class="custom-file">
                                                <input type="file" class="custom-file-input" name="StartPhoto"
                                                    style="opacity: 1;">
                                                <input type="hidden" name="OldStartPhoto"
                                                    value="<?php echo $row7['StartPhoto'];?>" id="OldStartPhoto">
                                                <span class="custom-file-label"></span>
                                            </label>
                                            <?php if($row7['StartPhoto']=='') {} else{?>
                                            <span id="show_photo">
                                                <div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a
                                                        href="javascript:void(0)"
                                                        class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white"
                                                        id="delete_photo"></a><img
                                                        src="../uploads/<?php echo $row7['StartPhoto'];?>" alt=""
                                                        class="img-fluid ticket-file-img"
                                                        style="width: 64px;height: 64px;"></div>
                                            </span>
                                            <?php } ?>
                                        </div>

                                        <div class="form-group col-md-3">
<label class="form-label">Lattitude </label>
<input type="text" name="StartLattitude" id="StartLattitude" class="form-control" placeholder="" value="<?php echo $Latitude; ?>" readonly>
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Longitude </label>
<input type="text" name="StartLongitude" id="StartLongitude" class="form-control" placeholder="" value="<?php echo $Longitude; ?>" readonly>
<div class="clearfix"></div>
</div>

</div>


<br>
<?php if ($selectedDriver) { ?>
<button type="submit" name="submit" class="btn btn-primary btn-finish">Start Trip</button>
<?php } else { ?>
<p class="text-muted">Select a driver to start trip.</p>
<?php } ?>
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
    if ($('#example').length) {
        $('#example').DataTable({
           "scrollX": true,
             paging: false,
        ordering: false,
        info: false,
        searching: false,
        });
    }
});
</script>
</body>
</html>
