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
$MainPage = 'Reports';
$Page = 'Driver-Trip-Billing-Report';

$filters = driverTripBillingTripFilters(array_merge($_GET, $_POST));
$driverId = $filters['driver_id'];
$transportorId = $filters['transportor_id'];
$fromDate = $filters['from_date'];
$toDate = $filters['to_date'];
$reportRows = driverTripBillingGetReportRows($filters);
$paymentModes = driverTripBillingPaymentModes();
$todayDate = date('Y-m-d');
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | Driver Trip Billing Report</title>
<meta charset="utf-8">
<?php include_once '../header_script.php'; ?>
<style>
.trip-billing-report-tfoot td,
.trip-billing-report-tfoot th,
.dataTables_scrollFoot .trip-billing-report-tfoot td,
.dataTables_scrollFoot .trip-billing-report-tfoot th,
#example tfoot.trip-billing-report-tfoot td,
#example tfoot.trip-billing-report-tfoot th {
    background-color: #fff3cd !important;
    color: #000 !important;
}
.trip-billing-paid-status-cell .badge-paid {
    font-size: 11px;
}
</style>
</head>
<body>
<div class="layout-wrapper layout-2">
<div class="layout-inner">
<?php include_once 'report-sidebar.php'; ?>
<div class="layout-container">
<?php include_once '../top_header.php'; ?>
<div class="layout-content">
<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Driver Trip Billing Report</h4>
<div class="card mb-3" style="padding:10px;">
<form method="post" class="form-row">
<div class="form-group col-md-3"><label>Transportor</label>
<select name="TransportorId" class="form-control select2-demo">
<option value="all">All</option>
<?php foreach ((getList("SELECT id,Fname FROM tbl_users WHERE Roll=46 AND Status=1 ORDER BY Fname") ?: []) as $t) { ?>
<option value="<?php echo $t['id']; ?>" <?php if ($transportorId == $t['id']) echo 'selected'; ?>><?php echo htmlspecialchars($t['Fname']); ?></option>
<?php } ?>
</select></div>
<div class="form-group col-md-3"><label>Driver</label>
<select name="DriverId" class="form-control select2-demo">
<option value="all">All</option>
<?php foreach ((getList("SELECT id,Fname FROM tbl_users WHERE Roll=39 AND Status=1 ORDER BY Fname") ?: []) as $d) { ?>
<option value="<?php echo $d['id']; ?>" <?php if ($driverId == $d['id']) echo 'selected'; ?>><?php echo htmlspecialchars($d['Fname']); ?></option>
<?php } ?>
</select></div>
<div class="form-group col-md-2"><label>From Date</label><input type="date" name="FromDate" class="form-control" value="<?php echo htmlspecialchars($fromDate); ?>"></div>
<div class="form-group col-md-2"><label>To Date</label><input type="date" name="ToDate" class="form-control" value="<?php echo htmlspecialchars($toDate); ?>"></div>
<div class="form-group col-md-2" style="padding-top:30px;"><button type="submit" name="Search" value="1" class="btn btn-primary">Search</button></div>
</form>
</div>
<div class="card" style="padding:10px;">
<div class="table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%;font-size:12px;">
<thead>
<tr>
<th>Sr.No</th><th>Trip Details</th><th>Est. KM</th><th>Transport Name</th><th>Gadi No</th><th>Driver Name</th>
<th>Out Date</th><th>In Date</th><th>Opening</th><th>Closing</th><th>Fastag</th><th>Challan</th><th>Challan Paid By</th><th>Diesel Payment</th>
<th>Total Running KM</th><th>Avg Vehicle</th><th>Total Diesel Used</th><th>Food</th><th>Per Day Rate</th><th>Days</th><th>Total Amount</th><th>Final Billing</th><th>Paid Amount</th><th>Paid Date</th><th>Pay Mode</th><th>Paid Status</th>
</tr>
</thead>
<tbody>
<?php
$i = 1;
$sumKm = $sumDiesel = $sumFastag = $sumFood = $sumTotal = $sumFinal = $sumPaid = 0;
foreach ($reportRows as $row) {
    $sumKm += (float)$row['TotalRunningKm'];
    $sumDiesel += (float)$row['DieselPayment'];
    $sumFastag += (float)$row['Fastag'];
    $sumFood += (float)$row['Food'];
    $sumTotal += (float)$row['TotalAmount'];
    $sumFinal += (float)$row['FinalBillingAmount'];
    $sumPaid += (float)($row['PaidAmount'] ?? 0);
    $rowKey = htmlspecialchars($row['source'] . ':' . $row['id']);
    $payTotal = (float)$row['FinalBillingAmount'];
    if ($payTotal <= 0) {
        $payTotal = (float)$row['TotalAmount'];
    }
?>
<tr data-row-key="<?php echo $rowKey; ?>" data-source-type="<?php echo htmlspecialchars($row['source']); ?>" data-source-id="<?php echo (int)$row['id']; ?>" data-total-amount="<?php echo $payTotal; ?>">
<td><?php echo $i++; ?></td>
<td><?php echo htmlspecialchars($row['TripDetails']); ?></td>
<td><?php echo driverTripBillingFormatMoney($row['EstimatedDistanceKm']); ?></td>
<td><?php echo htmlspecialchars($row['TransportName']); ?></td>
<td><?php echo htmlspecialchars($row['GadiNo']); ?></td>
<td><?php echo htmlspecialchars($row['DriverName']); ?></td>
<td><?php echo driverTripBillingFormatDate($row['OutDate']); ?></td>
<td><?php echo driverTripBillingFormatDate($row['InDate']); ?></td>
<td><?php echo driverTripBillingFormatMoney($row['OpeningReading']); ?></td>
<td><?php echo driverTripBillingFormatMoney($row['ClosingReading']); ?></td>
<td><?php echo driverTripBillingFormatMoney($row['Fastag']); ?></td>
<td><?php echo driverTripBillingFormatMoney($row['Challan']); ?></td>
<td><?php echo htmlspecialchars(driverTripBillingFormatChallanPaidBy($row['ChallanPaidBy'] ?? '')); ?></td>
<td><?php echo driverTripBillingFormatMoney($row['DieselPayment']); ?></td>
<td><?php echo driverTripBillingFormatMoney($row['TotalRunningKm']); ?></td>
<td><?php echo driverTripBillingFormatMoney($row['AvgVehicle']); ?></td>
<td><?php echo driverTripBillingFormatMoney($row['TotalDieselUsed']); ?></td>
<td><?php echo driverTripBillingFormatMoney($row['Food']); ?></td>
<td><?php echo driverTripBillingFormatMoney($row['PerDayRate']); ?></td>
<td><?php echo (int)$row['Days']; ?></td>
<td><?php echo driverTripBillingFormatMoney($row['TotalAmount']); ?></td>
<td><?php echo driverTripBillingFormatMoney($row['FinalBillingAmount']); ?></td>
<td class="trip-billing-paid-amount-cell"><?php echo !empty($row['IsPaid']) ? driverTripBillingFormatMoney($row['PaidAmount']) : ''; ?></td>
<td class="trip-billing-paid-date-cell"><?php echo !empty($row['IsPaid']) ? driverTripBillingFormatDate($row['PaymentDate']) : ''; ?></td>
<td class="trip-billing-pay-mode-cell"><?php echo !empty($row['IsPaid']) ? htmlspecialchars($row['PaymentMode']) : ''; ?></td>
<td class="trip-billing-paid-status-cell">
<?php if (!empty($row['IsPaid'])) { ?>
<span class="badge badge-success badge-paid">Payment Paid</span>
<?php } else { ?>
<button type="button" class="btn btn-sm btn-primary btn-trip-pay"
    data-source-type="<?php echo htmlspecialchars($row['source']); ?>"
    data-source-id="<?php echo (int)$row['id']; ?>"
    data-total-amount="<?php echo $payTotal; ?>"
    data-trip-details="<?php echo htmlspecialchars($row['TripDetails']); ?>">
Pay
</button>
<?php } ?>
</td>
</tr>
<?php } ?>
</tbody>
<tfoot class="trip-billing-report-tfoot">
<tr class="font-weight-bold">
<td colspan="14" class="text-right">Total Summary</td>
<td><?php echo driverTripBillingFormatMoney($sumKm); ?></td><td></td><td></td>
<td><?php echo driverTripBillingFormatMoney($sumFood); ?></td><td></td><td></td>
<td><?php echo driverTripBillingFormatMoney($sumTotal); ?></td>
<td><?php echo driverTripBillingFormatMoney($sumFinal); ?></td>
<td><?php echo driverTripBillingFormatMoney($sumPaid); ?></td>
<td></td><td></td><td></td>
</tr>
<tr><td colspan="26">Total Diesel Payment: <?php echo driverTripBillingFormatMoney($sumDiesel); ?> | Total Fastag: <?php echo driverTripBillingFormatMoney($sumFastag); ?></td></tr>
</tfoot>
</table>
</div>
</div>

<div class="modal fade" id="tripPaymentModal" tabindex="-1" role="dialog" aria-labelledby="tripPaymentModalLabel" aria-hidden="true">
<div class="modal-dialog" role="document">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title" id="tripPaymentModalLabel">Trip Payment</h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
<form id="tripPaymentForm">
<div class="modal-body">
<div id="tripPaymentAlert" class="alert alert-danger d-none"></div>
<input type="hidden" name="action" value="saveTripPayment">
<input type="hidden" name="source_type" id="paySourceType" value="">
<input type="hidden" name="source_id" id="paySourceId" value="">
<div class="form-group">
<label>Trip Details</label>
<input type="text" class="form-control" id="payTripDetails" readonly>
</div>
<div class="form-group">
<label>Total Amount</label>
<input type="number" step="0.01" min="0" class="form-control" name="total_amount" id="payTotalAmount" readonly>
</div>
<div class="form-group">
<label>Paid Amount <span class="text-danger">*</span></label>
<input type="number" step="0.01" min="0.01" class="form-control" name="paid_amount" id="payPaidAmount" required>
</div>
<div class="form-group">
<label>Payment Date <span class="text-danger">*</span></label>
<input type="date" class="form-control" name="payment_date" id="payPaymentDate" value="<?php echo htmlspecialchars($todayDate); ?>" required>
</div>
<div class="form-group mb-0">
<label>Payment Mode <span class="text-danger">*</span></label>
<select class="form-control" name="payment_mode" id="payPaymentMode" required>
<option value="">Select Payment Mode</option>
<?php foreach ($paymentModes as $modeKey => $modeLabel) { ?>
<option value="<?php echo htmlspecialchars($modeKey); ?>"><?php echo htmlspecialchars($modeLabel); ?></option>
<?php } ?>
</select>
</div>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
<button type="submit" class="btn btn-primary" id="tripPaymentSubmitBtn">Submit Payment</button>
</div>
</form>
</div>
</div>
</div>

</div>
<?php include_once '../footer.php'; ?>
</div></div></div></div>
<?php include_once '../footer_script.php'; ?>
<script>
$(function(){
    var dt = $('#example').DataTable({
        scrollX: true,
        dom: 'Bfrtip',
        buttons: ['excelHtml5'],
        drawCallback: function() {
            $('.trip-billing-report-tfoot td, .dataTables_scrollFoot .trip-billing-report-tfoot td').css({
                color: '#000',
                backgroundColor: '#fff3cd'
            });
        }
    });
    dt.draw();

    function showPaymentAlert(message) {
        $('#tripPaymentAlert').removeClass('d-none').text(message);
    }

    function hidePaymentAlert() {
        $('#tripPaymentAlert').addClass('d-none').text('');
    }

    function updatePaidColumns($row, payment) {
        $row.find('.trip-billing-paid-amount-cell').text(payment.paid_amount_formatted);
        $row.find('.trip-billing-paid-date-cell').text(payment.payment_date_formatted);
        $row.find('.trip-billing-pay-mode-cell').text(payment.payment_mode || '');
        $row.find('.trip-billing-paid-status-cell').html('<span class="badge badge-success badge-paid">Payment Paid</span>');
    }

    $(document).on('click', '.btn-trip-pay', function() {
        var $btn = $(this);
        hidePaymentAlert();
        $('#paySourceType').val($btn.data('source-type'));
        $('#paySourceId').val($btn.data('source-id'));
        $('#payTripDetails').val($btn.data('trip-details'));
        $('#payTotalAmount').val(parseFloat($btn.data('total-amount')).toFixed(2));
        $('#payPaidAmount').val(parseFloat($btn.data('total-amount')).toFixed(2));
        $('#payPaymentDate').val('<?php echo htmlspecialchars($todayDate); ?>');
        $('#payPaymentMode').val('');
        $('#tripPaymentModal').modal('show');
    });

    $('#tripPaymentForm').on('submit', function(e) {
        e.preventDefault();
        hidePaymentAlert();

        var $submitBtn = $('#tripPaymentSubmitBtn');
        $submitBtn.prop('disabled', true);

        $.ajax({
            url: '../ajax_files/ajax_driver_trip_billing.php',
            type: 'POST',
            dataType: 'json',
            data: $(this).serialize(),
            success: function(res) {
                if (!res || !res.ok) {
                    showPaymentAlert((res && res.message) ? res.message : 'Unable to save payment.');
                    $submitBtn.prop('disabled', false);
                    return;
                }

                var sourceType = $('#paySourceType').val();
                var sourceId = $('#paySourceId').val();
                var $row = $('tr[data-source-type="' + sourceType + '"][data-source-id="' + sourceId + '"]');
                if ($row.length && res.payment) {
                    updatePaidColumns($row, res.payment);
                }

                $('#tripPaymentModal').modal('hide');
                $submitBtn.prop('disabled', false);
            },
            error: function() {
                showPaymentAlert('Unable to save payment. Please try again.');
                $submitBtn.prop('disabled', false);
            }
        });
    });

    $('#tripPaymentModal').on('hidden.bs.modal', function() {
        hidePaymentAlert();
        $('#tripPaymentForm')[0].reset();
        $('#tripPaymentSubmitBtn').prop('disabled', false);
    });
});
</script>
</body>
</html>
