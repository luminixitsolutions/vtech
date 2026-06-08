<?php
session_start();
include_once '../config.php';
include_once '../auth.php';
include_once '../inc-driver-trip-billing.php';
$MainPage = 'Reports';
$Page = 'Driver-Trip-Billing-View';
$id = (int) ($_GET['id'] ?? 0);
$row = getRecord("SELECT * FROM driver_trip_billings WHERE id='$id'");
if (!$row) {
    echo "<script>alert('Record not found');window.location.href='driver-trip-billings.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | View Driver Trip Billing</title>
<meta charset="utf-8">
<?php include_once '../header_script.php'; ?>
</head>
<body>
<div class="layout-wrapper layout-2">
<div class="layout-inner">
<?php include_once 'report-sidebar.php'; ?>
<div class="layout-container">
<?php include_once '../top_header.php'; ?>
<div class="layout-content">
<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">View Driver Trip Billing
<span style="float:right;">
<a href="add-driver-trip-billing.php?id=<?php echo $id; ?>" class="btn btn-primary btn-sm">Edit</a>
<a href="driver-trip-billings.php" class="btn btn-secondary btn-sm">Back</a>
</span>
</h4>
<div class="card mb-4"><div class="card-body">
<div class="table-responsive">
<table class="table table-bordered">
<tr><th width="25%">Trip Details</th><td><?php echo htmlspecialchars($row['TripDetails']); ?></td><th width="25%">Estimated Distance KM</th><td><?php echo driverTripBillingFormatMoney($row['EstimatedDistanceKm']); ?></td></tr>
<tr><th>Transport Name</th><td><?php echo htmlspecialchars($row['TransportName']); ?></td><th>Gadi No</th><td><?php echo htmlspecialchars($row['GadiNo']); ?></td></tr>
<tr><th>Driver Name</th><td><?php echo htmlspecialchars($row['DriverName']); ?></td><th>Out Date</th><td><?php echo driverTripBillingFormatDate($row['OutDate']); ?></td></tr>
<tr><th>In Date</th><td><?php echo driverTripBillingFormatDate($row['InDate']); ?></td><th>Days</th><td><?php echo (int)$row['Days']; ?></td></tr>
<tr><th>Opening Reading</th><td><?php echo driverTripBillingFormatMoney($row['OpeningReading']); ?></td><th>Closing Reading</th><td><?php echo driverTripBillingFormatMoney($row['ClosingReading']); ?></td></tr>
<tr><th>Fastag</th><td><?php echo driverTripBillingFormatMoney($row['Fastag']); ?></td><th>Challan</th><td><?php echo driverTripBillingFormatMoney($row['Challan']); ?></td></tr>
<tr><th>Diesel Payment</th><td><?php echo driverTripBillingFormatMoney($row['DieselPayment']); ?></td><th>Food</th><td><?php echo driverTripBillingFormatMoney($row['Food']); ?></td></tr>
<tr><th>Total Running KM</th><td><?php echo driverTripBillingFormatMoney($row['TotalRunningKm']); ?></td><th>Avg. of Vehicle</th><td><?php echo driverTripBillingFormatMoney($row['AvgVehicle']); ?></td></tr>
<tr><th>Total Diesel Used</th><td><?php echo driverTripBillingFormatMoney($row['TotalDieselUsed']); ?></td><th>Diesel Rate</th><td><?php echo driverTripBillingFormatMoney($row['DieselRate']); ?></td></tr>
<tr><th>Per Day Rate</th><td><?php echo driverTripBillingFormatMoney($row['PerDayRate']); ?></td><th>Total Amount</th><td><?php echo driverTripBillingFormatMoney($row['TotalAmount']); ?></td></tr>
<tr class="table-success"><th>Final Billing Amount</th><td colspan="3"><strong><?php echo driverTripBillingFormatMoney($row['FinalBillingAmount']); ?></strong></td></tr>
</table>
</div>
</div></div>
</div>
<?php include_once '../footer.php'; ?>
</div></div></div></div>
<?php include_once '../footer_script.php'; ?>
</body>
</html>
