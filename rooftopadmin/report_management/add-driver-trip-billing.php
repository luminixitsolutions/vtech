<?php
session_start();
include_once '../config.php';
include_once '../auth.php';
include_once '../inc-driver-trip-billing.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = 'Reports';
$Page = 'Driver-Trip-Billing-Add';
$id = $_GET['id'] ?? '';
$row7 = [];
if ($id !== '') {
    $row7 = getRecord("SELECT * FROM driver_trip_billings WHERE id='" . $conn->real_escape_string($id) . "'");
    if (!is_array($row7)) {
        $row7 = [];
    }
}
$isEdit = $id !== '' && !empty($row7);
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
    <title><?php echo $Proj_Title; ?> - <?php echo $isEdit ? 'Edit' : 'Add'; ?> Driver Trip Billing</title>
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
<h4 class="font-weight-bold py-3 mb-0"><?php echo $isEdit ? 'Edit' : 'Add'; ?> Driver Trip Billing</h4>
<div class="card mb-4">
<div class="card-body">
<form method="post" action="../ajax_files/ajax_driver_trip_billing.php" id="tripBillingForm">
<input type="hidden" name="action" value="Save">
<input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
<div class="form-row">
<div class="form-group col-md-6">
<label>Trip Details <span class="text-danger">*</span></label>
<input type="text" name="TripDetails" class="form-control calc-trigger" required value="<?php echo htmlspecialchars($row7['TripDetails'] ?? ''); ?>">
</div>
<div class="form-group col-md-3">
<label>Estimated Distance (KM)</label>
<input type="number" step="0.01" name="EstimatedDistanceKm" class="form-control" value="<?php echo htmlspecialchars($row7['EstimatedDistanceKm'] ?? ''); ?>">
</div>
<div class="form-group col-md-3">
<label>Status</label>
<select name="Status" class="form-control">
<option value="1" <?php if (($row7['Status'] ?? '1') == '1') echo 'selected'; ?>>Active</option>
<option value="0" <?php if (($row7['Status'] ?? '') == '0') echo 'selected'; ?>>Inactive</option>
</select>
</div>
<div class="form-group col-md-4">
<label>Transportor</label>
<select name="TransportorId" id="TransportorId" class="form-control select2-demo">
<option value="">Select Transportor</option>
<?php foreach (getList("SELECT id,Fname FROM tbl_users WHERE Roll=46 AND Status=1 ORDER BY Fname") as $t) { ?>
<option value="<?php echo $t['id']; ?>" <?php if (($row7['TransportorId'] ?? '') == $t['id']) echo 'selected'; ?>><?php echo htmlspecialchars($t['Fname']); ?></option>
<?php } ?>
</select>
<input type="hidden" name="TransportName" id="TransportName" value="<?php echo htmlspecialchars($row7['TransportName'] ?? ''); ?>">
</div>
<div class="form-group col-md-4">
<label>Driver <span class="text-danger">*</span></label>
<select name="DriverId" id="DriverId" class="form-control select2-demo" required>
<option value="">Select Driver</option>
<?php foreach (getList("SELECT id,Fname,VehicalNo,UnderUser,PerDayVehicle FROM tbl_users WHERE Roll=39 AND Status=1 ORDER BY Fname") as $d) { ?>
<option value="<?php echo $d['id']; ?>" data-gadi="<?php echo htmlspecialchars($d['VehicalNo']); ?>" data-transportor="<?php echo (int)$d['UnderUser']; ?>" data-rate="<?php echo htmlspecialchars($d['PerDayVehicle']); ?>" <?php if (($row7['DriverId'] ?? '') == $d['id']) echo 'selected'; ?>><?php echo htmlspecialchars($d['Fname']); ?></option>
<?php } ?>
</select>
<input type="hidden" name="DriverName" id="DriverName" value="<?php echo htmlspecialchars($row7['DriverName'] ?? ''); ?>">
</div>
<div class="form-group col-md-4">
<label>Gadi No</label>
<input type="text" name="GadiNo" id="GadiNo" class="form-control" value="<?php echo htmlspecialchars($row7['GadiNo'] ?? ''); ?>">
</div>
<div class="form-group col-md-3">
<label>Out Date <span class="text-danger">*</span></label>
<input type="date" name="OutDate" id="OutDate" class="form-control calc-trigger" required value="<?php echo htmlspecialchars($row7['OutDate'] ?? ''); ?>">
</div>
<div class="form-group col-md-3">
<label>In Date <span class="text-danger">*</span></label>
<input type="date" name="InDate" id="InDate" class="form-control calc-trigger" required value="<?php echo htmlspecialchars($row7['InDate'] ?? ''); ?>">
</div>
<div class="form-group col-md-3">
<label>Opening Reading</label>
<input type="number" step="0.01" name="OpeningReading" id="OpeningReading" class="form-control calc-trigger" value="<?php echo htmlspecialchars($row7['OpeningReading'] ?? ''); ?>">
</div>
<div class="form-group col-md-3">
<label>Closing Reading</label>
<input type="number" step="0.01" name="ClosingReading" id="ClosingReading" class="form-control calc-trigger" value="<?php echo htmlspecialchars($row7['ClosingReading'] ?? ''); ?>">
</div>
<div class="form-group col-md-3">
<label>Fastag</label>
<input type="number" step="0.01" name="Fastag" id="Fastag" class="form-control calc-trigger" value="<?php echo htmlspecialchars($row7['Fastag'] ?? '0'); ?>">
</div>
<div class="form-group col-md-3">
<label>Challan</label>
<input type="number" step="0.01" name="Challan" id="Challan" class="form-control" value="<?php echo htmlspecialchars($row7['Challan'] ?? '0'); ?>">
</div>
<div class="form-group col-md-3">
<label>Diesel Payment</label>
<input type="number" step="0.01" name="DieselPayment" id="DieselPayment" class="form-control calc-trigger" value="<?php echo htmlspecialchars($row7['DieselPayment'] ?? '0'); ?>">
</div>
<div class="form-group col-md-3">
<label>Food</label>
<input type="number" step="0.01" name="Food" id="Food" class="form-control calc-trigger" value="<?php echo htmlspecialchars($row7['Food'] ?? '0'); ?>">
</div>
<div class="form-group col-md-3">
<label>Per Day Rate</label>
<input type="number" step="0.01" name="PerDayRate" id="PerDayRate" class="form-control calc-trigger" value="<?php echo htmlspecialchars($row7['PerDayRate'] ?? '0'); ?>">
</div>
<div class="form-group col-md-3">
<label>Diesel Rate</label>
<input type="number" step="0.01" name="DieselRate" id="DieselRate" class="form-control calc-trigger" value="<?php echo htmlspecialchars($row7['DieselRate'] ?? '93'); ?>">
</div>
<div class="form-group col-md-3">
<label>Total Running KM</label>
<input type="text" id="TotalRunningKm" class="form-control" readonly value="<?php echo htmlspecialchars($row7['TotalRunningKm'] ?? '0'); ?>">
</div>
<div class="form-group col-md-3">
<label>Avg. of Vehicle</label>
<input type="text" id="AvgVehicle" class="form-control" readonly value="<?php echo htmlspecialchars($row7['AvgVehicle'] ?? '0'); ?>">
</div>
<div class="form-group col-md-3">
<label>Total Diesel Used</label>
<input type="text" id="TotalDieselUsed" class="form-control" readonly value="<?php echo htmlspecialchars($row7['TotalDieselUsed'] ?? '0'); ?>">
</div>
<div class="form-group col-md-3">
<label>Days</label>
<input type="text" id="Days" class="form-control" readonly value="<?php echo htmlspecialchars($row7['Days'] ?? '0'); ?>">
</div>
<div class="form-group col-md-3">
<label>Total Amount</label>
<input type="text" id="TotalAmount" class="form-control" readonly value="<?php echo htmlspecialchars($row7['TotalAmount'] ?? '0'); ?>">
</div>
<div class="form-group col-md-3">
<label>Final Billing Amount</label>
<input type="text" id="FinalBillingAmount" class="form-control font-weight-bold" readonly value="<?php echo htmlspecialchars($row7['FinalBillingAmount'] ?? '0'); ?>">
</div>
</div>
<button type="submit" class="btn btn-primary">Save Trip Billing</button>
<a href="driver-trip-billings.php" class="btn btn-secondary">Back to List</a>
</form>
</div>
</div>
</div>
<?php include_once '../footer.php'; ?>
</div>
</div>
</div>
</div>
<?php include_once '../footer_script.php'; ?>
<script>
function recalcTripBilling() {
    var opening = parseFloat($('#OpeningReading').val()) || 0;
    var closing = parseFloat($('#ClosingReading').val()) || 0;
    var fastag = parseFloat($('#Fastag').val()) || 0;
    var dieselPayment = parseFloat($('#DieselPayment').val()) || 0;
    var food = parseFloat($('#Food').val()) || 0;
    var perDayRate = parseFloat($('#PerDayRate').val()) || 0;
    var dieselRate = parseFloat($('#DieselRate').val()) || 93;
    var totalRunningKm = Math.max(0, closing - opening);
    var avgVehicle = totalRunningKm / 12;
    var totalDieselUsed = avgVehicle * dieselRate;
    var days = 0;
    var outDate = $('#OutDate').val();
    var inDate = $('#InDate').val();
    if (outDate && inDate) {
        var outTs = new Date(outDate).getTime();
        var inTs = new Date(inDate).getTime();
        if (!isNaN(outTs) && !isNaN(inTs)) {
            days = Math.floor((inTs - outTs) / 86400000) + 1;
            if (days < 0) days = 0;
        }
    }
    var totalAmount = perDayRate * days;
    var finalBilling = perDayRate - (dieselPayment - totalDieselUsed) + fastag + food;
    $('#TotalRunningKm').val(totalRunningKm.toFixed(2));
    $('#AvgVehicle').val(avgVehicle.toFixed(2));
    $('#TotalDieselUsed').val(totalDieselUsed.toFixed(2));
    $('#Days').val(days);
    $('#TotalAmount').val(totalAmount.toFixed(2));
    $('#FinalBillingAmount').val(finalBilling.toFixed(2));
}
$(document).on('input change', '.calc-trigger', recalcTripBilling);
$('#DriverId').on('change', function() {
    var opt = $(this).find('option:selected');
    $('#DriverName').val(opt.text().trim());
    $('#GadiNo').val(opt.data('gadi') || '');
    if (opt.data('rate')) {
        $('#PerDayRate').val(opt.data('rate'));
    }
    recalcTripBilling();
});
$('#TransportorId').on('change', function() {
    var val = $(this).val();
    var text = $(this).find('option:selected').text().trim();
    $('#TransportName').val(val ? text : '');
    filterDriversByTransportor();
});
var allDrivers = [];
$('#DriverId option').each(function() {
    if (!$(this).val()) {
        return;
    }
    allDrivers.push({
        value: $(this).val(),
        text: $(this).text().trim(),
        gadi: $(this).data('gadi') || '',
        transportor: String($(this).data('transportor') || '0'),
        rate: $(this).data('rate') || ''
    });
});
function filterDriversByTransportor() {
    var transportorId = $('#TransportorId').val();
    var currentDriver = $('#DriverId').val();
    var $driver = $('#DriverId');
    if ($driver.hasClass('select2-hidden-accessible')) {
        $driver.select2('destroy');
    }
    $driver.empty().append('<option value="">Select Driver</option>');
    if (!transportorId) {
        $driver.append('<option value="" disabled>Select Transportor first</option>');
    } else {
        var count = 0;
        allDrivers.forEach(function(d) {
            if (d.transportor === String(transportorId)) {
                $driver.append(
                    $('<option></option>')
                        .val(d.value)
                        .text(d.text)
                        .attr('data-gadi', d.gadi)
                        .attr('data-transportor', d.transportor)
                        .attr('data-rate', d.rate)
                );
                count++;
            }
        });
        if (count === 0) {
            $driver.append('<option value="" disabled>No driver found for this transportor</option>');
        }
    }
    if (currentDriver && $driver.find('option[value="' + currentDriver + '"]').length) {
        $driver.val(currentDriver);
    } else {
        $driver.val('');
        $('#DriverName').val('');
        $('#GadiNo').val('');
    }
    $driver.select2();
    $driver.trigger('change');
}
$(document).ready(function() {
    var transportorText = $('#TransportorId option:selected').text().trim();
    if ($('#TransportorId').val()) {
        $('#TransportName').val(transportorText);
    }
    filterDriversByTransportor();
    recalcTripBilling();
});
</script>
</body>
</html>
