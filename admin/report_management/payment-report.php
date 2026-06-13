<?php

session_start();

include_once '../config.php';

include_once '../auth.php';

require_once __DIR__ . '/inc-payment-report.php';



$user_id = $_SESSION['Admin']['id'];

$MainPage = 'Reports';

$Page = 'Payment-Report';



$filters = array(

    'ProjectId' => isset($_POST['ProjectId']) ? $_POST['ProjectId'] : 'all',

    'ProjectSubHeadId' => isset($_POST['ProjectSubHeadId']) ? $_POST['ProjectSubHeadId'] : 'all',

    'District' => isset($_POST['District']) ? $_POST['District'] : 'all',

    'Taluka' => isset($_POST['Taluka']) ? $_POST['Taluka'] : 'all',

    'Village' => isset($_POST['Village']) ? $_POST['Village'] : 'all',

);

?>

<!DOCTYPE html>

<html lang="en" class="default-style layout-fixed layout-navbar-fixed">

<head>

<title><?php echo $Proj_Title; ?> | Payment Report</title>

<meta charset="utf-8">

<meta http-equiv="X-UA-Compatible" content="IE=edge" />

<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">

<meta name="description" content="" />

<meta name="keywords" content="">

<meta name="author" content="" />

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

<h4 class="font-weight-bold py-3 mb-0">Payment Report</h4>



<div class="card" style="padding: 10px;">

<div id="accordion2">

<div class="card mb-2">

<div id="accordion2-2" class="collapse show" data-parent="#accordion2">

<div class="" style="padding:5px;">

<form id="validation-form" method="post" enctype="multipart/form-data" action="" onsubmit="return false;">

<div class="form-row">



<div class="form-group col-md-3">

<label class="form-label">Project</label>

<select class="form-control" id="ProjectId" name="ProjectId" onchange="getSubHead(this.value)">

<option selected="" value="all">All Project</option>

<?php

$q = "SELECT id, Name FROM tbl_common_master WHERE Status='1' AND Roll=24 ORDER BY Name ASC";

$r = $conn->query($q);

while ($rw = $r->fetch_assoc()) {

    ?>

<option <?php if ($filters['ProjectId'] == $rw['id']) { ?> selected <?php } ?> value="<?php echo (int) $rw['id']; ?>"><?php echo htmlspecialchars($rw['Name'], ENT_QUOTES, 'UTF-8'); ?></option>

<?php } ?>

</select>

</div>



<div class="form-group col-md-3">

<label class="form-label">Project Sub Head</label>

<select class="form-control" id="ProjectSubHeadId" name="ProjectSubHeadId">

<option selected="" value="all">All Sub Head</option>

<?php

$subHeadSql = "SELECT id, Name FROM tbl_project_sub_head WHERE Status='1'";

if ($filters['ProjectId'] !== '' && $filters['ProjectId'] !== 'all') {

    $subHeadSql .= " AND UnderBy='" . (int) $filters['ProjectId'] . "'";

}

$subHeadSql .= ' ORDER BY Name ASC';

$r = $conn->query($subHeadSql);

while ($rw = $r->fetch_assoc()) {

    ?>

<option <?php if ($filters['ProjectSubHeadId'] == $rw['id']) { ?> selected <?php } ?> value="<?php echo (int) $rw['id']; ?>"><?php echo htmlspecialchars($rw['Name'], ENT_QUOTES, 'UTF-8'); ?></option>

<?php } ?>

</select>

</div>



<div class="form-group col-md-3">

<label class="form-label">District</label>

<select class="select2-demo form-control" id="District" name="District">

<option selected="" value="all">All District</option>

<?php

$r = $conn->query(paymentReportFilterOptionsSql('District'));

while ($rw = $r->fetch_assoc()) {

    ?>

<option <?php if ($filters['District'] == $rw['val']) { ?> selected <?php } ?> value="<?php echo htmlspecialchars($rw['val'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($rw['val'], ENT_QUOTES, 'UTF-8'); ?></option>

<?php } ?>

</select>

</div>



<div class="form-group col-md-3">

<label class="form-label">Taluka</label>

<select class="select2-demo form-control" id="Taluka" name="Taluka">

<option selected="" value="all">All Taluka</option>

<?php

$r = $conn->query(paymentReportFilterOptionsSql('Taluka'));

while ($rw = $r->fetch_assoc()) {

    ?>

<option <?php if ($filters['Taluka'] == $rw['val']) { ?> selected <?php } ?> value="<?php echo htmlspecialchars($rw['val'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($rw['val'], ENT_QUOTES, 'UTF-8'); ?></option>

<?php } ?>

</select>

</div>



<div class="form-group col-md-3">

<label class="form-label">Village</label>

<select class="select2-demo form-control" id="Village" name="Village">

<option selected="" value="all">All Village</option>

<?php

$r = $conn->query(paymentReportFilterOptionsSql('Village'));

while ($rw = $r->fetch_assoc()) {

    ?>

<option <?php if ($filters['Village'] == $rw['val']) { ?> selected <?php } ?> value="<?php echo htmlspecialchars($rw['val'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($rw['val'], ENT_QUOTES, 'UTF-8'); ?></option>

<?php } ?>

</select>

</div>



<div class="form-group col-md-1" style="padding-top:25px;">

<button type="button" id="btnPaymentSearch" class="btn btn-primary btn-finish">Search</button>

</div>

<div class="col-md-1" style="padding-top:6px;">

<label class="form-label d-none d-md-block">&nbsp;</label>

<a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-info btn-block" data-toggle="tooltip" data-placement="top" data-original-title="Clear Filter">X</a>

</div>

</div>

</form>

</div>

</div>

</div>

</div>



<div id="paymentReportHint" class="alert alert-info mb-2">Apply filters and click <strong>Search</strong> to load the report.</div>



<div class="card-datatable table-responsive">

<table id="paymentReportTable" class="table table-striped table-bordered" style="width:100%">

<thead>

<tr>

    <th>#</th>

    <th>Beneficiary ID</th>

    <th>Customer Name</th>

    <th>District</th>

    <th>Village</th>

    <th>Taluka</th>

    <th>Capacity</th>

    <th>Project Head</th>

    <th>Sub Head</th>

    <th>Total Cost</th>

    <th>Payment Released 90%</th>

    <th>90% Payment Amount</th>

    <th>90% Payment Date</th>

    <th>Payment Released 10%</th>

    <th>10% Payment Amount</th>

    <th>10% Payment Date</th>

    <th>Balance Amount</th>

</tr>

</thead>

<tbody></tbody>

</table>

</div>

</div>

</div>



<?php include_once '../footer.php'; ?>



</div>

</div>

</div>



<div class="layout-overlay layout-sidenav-toggle"></div>

</div>



<?php include_once '../footer_script.php'; ?>



<script type="text/javascript">

function getSubHead(id) {

    $.ajax({

        type: 'POST',

        url: '../ajax_files/ajax_dropdown.php',

        data: { action: 'getSubHead', id: id },

        success: function(data) {

            $('#ProjectSubHeadId').html(data);

        }

    });

}



function getPaymentReportFilters() {

    return {

        ProjectId: $('#ProjectId').val() || 'all',

        ProjectSubHeadId: $('#ProjectSubHeadId').val() || 'all',

        District: $('#District').val() || 'all',

        Taluka: $('#Taluka').val() || 'all',

        Village: $('#Village').val() || 'all'

    };

}



function initPaymentReportTable() {

    $('#paymentReportHint').hide();



    if ($.fn.DataTable.isDataTable('#paymentReportTable')) {

        $('#paymentReportTable').DataTable().destroy();

        $('#paymentReportTable tbody').empty();

    }



    $('#paymentReportTable').DataTable({

        processing: true,

        serverSide: true,

        serverMethod: 'post',

        ajax: {

            url: '../pagination/payment-report.php',

            type: 'POST',

            data: function(d) {

                var filters = getPaymentReportFilters();

                d.ProjectId = filters.ProjectId;

                d.ProjectSubHeadId = filters.ProjectSubHeadId;

                d.District = filters.District;

                d.Taluka = filters.Taluka;

                d.Village = filters.Village;

            }

        },

        columns: [

            { data: 'sr', orderable: false, searchable: false },

            { data: 'BeneficiaryId' },

            { data: 'CustomerName' },

            { data: 'District' },

            { data: 'Village' },

            { data: 'Taluka' },

            { data: 'CapacityName' },

            { data: 'ProjectHeadName' },

            { data: 'SubHeadName' },

            { data: 'TotalCost' },

            { data: 'Payment90' },

            { data: 'Payment90Amt' },

            { data: 'Payment90Date' },

            { data: 'Payment10' },

            { data: 'Payment10Amt' },

            { data: 'Payment10Date' },

            { data: 'BalanceAmount' }

        ],

        scrollX: true,

        dom: 'Bfrtip',

        buttons: ['excelHtml5', 'pdfHtml5'],

        pageLength: 50,

        lengthMenu: [[25, 50, 100, 250, 500], [25, 50, 100, 250, 500]],

        order: [[1, 'asc']],

        destroy: true

    });

}



$(document).ready(function() {

    $('#btnPaymentSearch').on('click', function() {

        initPaymentReportTable();

    });

});

</script>

</body>

</html>


