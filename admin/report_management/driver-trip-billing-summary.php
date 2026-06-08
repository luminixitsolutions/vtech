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
$MainPage = 'Reports';
$Page = 'Driver-Trip-Billing-Summary';

$filters = driverTripBillingTripFilters(array_merge($_GET, $_POST));
$fromDate = $filters['from_date'];
$toDate = $filters['to_date'];
$sql = driverTripBillingCompletedTripsSummarySql($filters);
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | Driver Trip Billing Summary</title>
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
<h4 class="font-weight-bold py-3 mb-0">Driver Trip Billing Summary Report</h4>
<div class="card mb-3" style="padding:10px;">
<form method="post" class="form-row">
<div class="form-group col-md-3"><label>From Date</label><input type="date" name="FromDate" class="form-control" value="<?php echo htmlspecialchars($fromDate); ?>"></div>
<div class="form-group col-md-3"><label>To Date</label><input type="date" name="ToDate" class="form-control" value="<?php echo htmlspecialchars($toDate); ?>"></div>
<div class="form-group col-md-2" style="padding-top:30px;"><button type="submit" name="Search" value="1" class="btn btn-primary">Search</button></div>
</form>
</div>
<div class="card" style="padding:10px;">
<div class="table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
<thead>
<tr>
<th>Sr</th><th>Driver Name</th><th>Transport Name</th><th>Gadi No</th><th>Trips</th>
<th>Total KM</th><th>Total Diesel Payment</th><th>Total Fastag</th><th>Total Food</th><th>Total Amount</th><th>Final Billing Total</th>
</tr>
</thead>
<tbody>
<?php
$i = 1;
$gKm = $gDiesel = $gFastag = $gFood = $gTotal = $gFinal = 0;
$res = driverTripBillingSafeQuery($sql);
if ($res) {
while ($row = $res->fetch_assoc()) {
    $gKm += (float)$row['TotalKm'];
    $gDiesel += (float)$row['TotalDieselPayment'];
    $gFastag += (float)$row['TotalFastag'];
    $gFood += (float)$row['TotalFood'];
    $gTotal += (float)$row['TotalAmount'];
    $gFinal += (float)$row['FinalBillingTotal'];
?>
<tr>
<td><?php echo $i++; ?></td>
<td><?php echo htmlspecialchars($row['DriverName']); ?></td>
<td><?php echo htmlspecialchars($row['TransportName']); ?></td>
<td><?php echo htmlspecialchars($row['GadiNo']); ?></td>
<td><?php echo (int)$row['TripCount']; ?></td>
<td><?php echo driverTripBillingFormatMoney($row['TotalKm']); ?></td>
<td><?php echo driverTripBillingFormatMoney($row['TotalDieselPayment']); ?></td>
<td><?php echo driverTripBillingFormatMoney($row['TotalFastag']); ?></td>
<td><?php echo driverTripBillingFormatMoney($row['TotalFood']); ?></td>
<td><?php echo driverTripBillingFormatMoney($row['TotalAmount']); ?></td>
<td><?php echo driverTripBillingFormatMoney($row['FinalBillingTotal']); ?></td>
</tr>
<?php } } ?>
</tbody>
<tfoot>
<tr class="table-warning font-weight-bold">
<td colspan="5" class="text-right">Grand Total</td>
<td><?php echo driverTripBillingFormatMoney($gKm); ?></td>
<td><?php echo driverTripBillingFormatMoney($gDiesel); ?></td>
<td><?php echo driverTripBillingFormatMoney($gFastag); ?></td>
<td><?php echo driverTripBillingFormatMoney($gFood); ?></td>
<td><?php echo driverTripBillingFormatMoney($gTotal); ?></td>
<td><?php echo driverTripBillingFormatMoney($gFinal); ?></td>
</tr>
</tfoot>
</table>
</div>
</div>
</div>
<?php include_once '../footer.php'; ?>
</div></div></div></div>
<?php include_once '../footer_script.php'; ?>
<script>$(function(){ $('#example').DataTable({ scrollX: true, dom: 'Bfrtip', buttons: ['excelHtml5'] }); });</script>
</body>
</html>
