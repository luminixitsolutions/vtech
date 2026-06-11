<?php
session_start();
include_once '../config.php';
include_once '../auth.php';
$driverTripBillingInc = dirname(__DIR__) . '/inc-driver-trip-billing.php';
if (!is_file($driverTripBillingInc)) {
    http_response_code(500);
    exit('Driver trip billing module file is missing on server. Please upload admin/inc-driver-trip-billing.php');
}
include_once $driverTripBillingInc;
driverTripBillingEnsureSchema();
$user_id = $_SESSION['Admin']['id'];
$MainPage = 'Reports';
$Page = 'Driver-Trip-Billing-List';

if (($_REQUEST['action'] ?? '') === 'delete') {
    $id = (int) $_REQUEST['id'];
    $conn->query("DELETE FROM driver_trip_billings WHERE id=$id");
    echo "<script>alert('Deleted successfully');window.location.href='driver-trip-billings.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | Driver Trip Billing List</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
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
<h4 class="font-weight-bold py-3 mb-0">Driver Trip Billing List
<span style="float:right;">
<a href="add-driver-trip-billing.php" class="btn btn-primary btn-round"><i class="ion ion-md-add mr-2"></i> Add Trip</a>
</span>
</h4>
<div class="card" style="padding:10px;">
<div class="table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
<thead>
<tr>
<th>Sr</th><th>Trip Details</th><th>Transport Name</th><th>Gadi No</th><th>Driver</th>
<th>Out Date</th><th>In Date</th><th>Total KM</th><th>Total Amount</th><th>Final Billing</th><th>Action</th>
</tr>
</thead>
<tbody>
<?php
$i = 1;
$sumKm = $sumDiesel = $sumFastag = $sumFood = $sumTotal = $sumFinal = 0;
$filters = driverTripBillingTripFilters([]);
$reportRows = driverTripBillingGetReportRows($filters);
foreach ($reportRows as $row) {
    $sumKm += (float)$row['TotalRunningKm'];
    $sumDiesel += (float)$row['DieselPayment'];
    $sumFastag += (float)$row['Fastag'];
    $sumFood += (float)$row['Food'];
    $sumTotal += (float)$row['TotalAmount'];
    $sumFinal += (float)$row['FinalBillingAmount'];
?>
<tr>
<td><?php echo $i++; ?></td>
<td><?php echo htmlspecialchars($row['TripDetails']); ?></td>
<td><?php echo htmlspecialchars($row['TransportName']); ?></td>
<td><?php echo htmlspecialchars($row['GadiNo']); ?></td>
<td><?php echo htmlspecialchars($row['DriverName']); ?></td>
<td><?php echo driverTripBillingFormatDate($row['OutDate']); ?></td>
<td><?php echo driverTripBillingFormatDate($row['InDate']); ?></td>
<td><?php echo driverTripBillingFormatMoney($row['TotalRunningKm']); ?></td>
<td><?php echo driverTripBillingFormatMoney($row['TotalAmount']); ?></td>
<td><?php echo driverTripBillingFormatMoney($row['FinalBillingAmount']); ?></td>
<td>
<?php if (($row['source'] ?? '') === 'billing') { ?>
<a href="view-driver-trip-billing.php?id=<?php echo (int)$row['id']; ?>"><i class="lnr lnr-eye"></i></a>
<a href="add-driver-trip-billing.php?id=<?php echo (int)$row['id']; ?>"><i class="lnr lnr-pencil"></i></a>
<a href="?action=delete&id=<?php echo (int)$row['id']; ?>" onclick="return confirm('Delete this trip billing?');"><i class="lnr lnr-trash text-danger"></i></a>
<?php } else { ?>
<a href="../add-calculation.php?id=<?php echo (int)$row['id']; ?>" title="Trip Calculation"><i class="lnr lnr-eye"></i></a>
<?php } ?>
</td>
</tr>
<?php } ?>
</tbody>
<tfoot>
<tr class="table-warning font-weight-bold">
<td colspan="7" class="text-right">Total Summary</td>
<td><?php echo driverTripBillingFormatMoney($sumKm); ?></td>
<td><?php echo driverTripBillingFormatMoney($sumTotal); ?></td>
<td><?php echo driverTripBillingFormatMoney($sumFinal); ?></td>
<td></td>
</tr>
<tr class="table-light">
<td colspan="7" class="text-right">Total Diesel Payment / Fastag / Food</td>
<td colspan="3"><?php echo driverTripBillingFormatMoney($sumDiesel); ?> / <?php echo driverTripBillingFormatMoney($sumFastag); ?> / <?php echo driverTripBillingFormatMoney($sumFood); ?></td>
<td></td>
</tr>
</tfoot>
</table>
</div>
</div>
</div>
<?php include_once '../footer.php'; ?>
</div>
</div>
</div>
</div>
<?php include_once '../footer_script.php'; ?>
<script>$(function(){ $('#example').DataTable({ scrollX: true, dom: 'Bfrtip', buttons: ['excelHtml5'] }); });</script>
</body>
</html>
