<?php
session_start();
include_once '../config.php';
include_once '../auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = 'Masters';
$Page = 'Project-Cost';
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | Project Cost</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="" />
<meta name="keywords" content="">
<meta name="author" content="" />
    <link rel="icon" type="image/x-icon" href="<?php echo $SiteUrl;?>/assets/img/favicon.ico">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/fontawesome.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/ionicons.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/linearicons.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/feather.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/css/bootstrap-material.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/css/shreerang-material.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/css/uikit.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/libs/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/libs/datatables/datatables.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/libs/growl/growl.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/libs/toastr/toastr.css">
</head>
<body>

<div class="layout-wrapper layout-2">
<div class="layout-inner">

  <?php include_once 'master-sidebar.php'; ?>

<div class="layout-container">

<?php include_once '../top_header.php'; ?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Total Cost
  <?php if (in_array('14', $Options)) { ?>
  <span style="float: right;">
<button type="button" class="btn btn-secondary btn-round" data-toggle="modal" data-target="#modals-default" id="add_button"><i class="ion ion-md-add mr-2"></i> Add More</button></span><?php } ?></h4><br>
<div class="modal fade insert_frm" id="modals-default">
<div class="modal-dialog">
<form class="modal-content" id="validation-form" method="post" novalidate="novalidate" autocomplete="off">
<div class="modal-header">
<h5 class="modal-title">Add <span class="font-weight-light">Total Cost</span></h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
</div>
<div class="modal-body">
  <input type="hidden" name="action" id="action" value="Add">
  <input type="hidden" name="id" id="id" />

<div class="form-row">
<div class="form-group col">
<label class="form-label">Project <span class="text-danger">*</span></label>
<select class="form-control" id="ProjectId" name="ProjectId" required="">
<option selected="" disabled="" value="">Select Project</option>
<?php
        $q = "SELECT id, Name FROM tbl_common_master WHERE Status='1' AND Roll=24 ORDER BY Name ASC";
        $r = $conn->query($q);
        while ($rw = $r->fetch_assoc()) {
            ?>
<option value="<?php echo (int) $rw['id']; ?>"><?php echo htmlspecialchars($rw['Name'], ENT_QUOTES, 'UTF-8'); ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>
</div>

<div class="form-row">
<div class="form-group col">
<label class="form-label">Sub Head <span class="text-danger">*</span></label>
<select class="form-control" id="ProjectSubHeadId" name="ProjectSubHeadId" required="">
<option selected="" disabled="" value="">Select Sub Head</option>
<?php
        $q = "SELECT id, Name, UnderBy FROM tbl_project_sub_head WHERE Status='1' ORDER BY Name ASC";
        $r = $conn->query($q);
        while ($rw = $r->fetch_assoc()) {
            ?>
<option value="<?php echo (int) $rw['id']; ?>" data-project="<?php echo (int) $rw['UnderBy']; ?>"><?php echo htmlspecialchars($rw['Name'], ENT_QUOTES, 'UTF-8'); ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>
</div>

<div class="form-row">
<div class="form-group col">
<label class="form-label">Capacity <span class="text-danger">*</span></label>
<select class="form-control" id="CapacityId" name="CapacityId" required="">
<option selected="" disabled="" value="">Select Capacity</option>
<?php
        $q = "SELECT id, Name FROM tbl_common_master WHERE Status='1' AND Roll=2 ORDER BY Name ASC";
        $r = $conn->query($q);
        while ($rw = $r->fetch_assoc()) {
            ?>
<option value="<?php echo (int) $rw['id']; ?>"><?php echo htmlspecialchars($rw['Name'], ENT_QUOTES, 'UTF-8'); ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>
</div>

<div class="form-row">
<div class="form-group col">
<label class="form-label">Amount <span class="text-danger">*</span></label>
<input type="number" step="0.01" min="0" name="Amount" class="form-control" id="Amount" placeholder="Amount" required>
<div class="clearfix"></div>
</div>
</div>

<div class="form-row">
<div class="form-group col">
<label class="form-label">Status <span class="text-danger">*</span></label>
<select class="form-control" id="Status" name="Status" required="">
<option selected="" disabled="" value="">Select Status</option>
<option value="1">Active</option>
<option value="0">Inactive</option>
</select>
<div class="clearfix"></div>
</div>
</div>

</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
<button type="submit" class="btn btn-danger" id="submit" name="submit">Submit</button>
</div>
</form>
</div>
</div>
<div class="card">
<div class="card-datatable table-responsive" id="custresult">
</div>
</div>
</div>

<?php include_once '../footer.php'; ?>

</div>

</div>

</div>

<div class="layout-overlay layout-sidenav-toggle"></div>
</div>

    <script src="<?php echo $SiteUrl;?>/assets/js/jquery-3.4.1.min.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/bootstrap.min.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/datatables.min.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/pace.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/sidenav.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/layout-helpers.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/libs/validate/validate.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/libs/growl/growl.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/libs/toastr/toastr.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/demo.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/pages/forms_validation.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/pages/ui_notifications.js"></script>
<script type="text/javascript">
function filterSubHeadOptions(selectedProjectId, selectedSubHeadId) {
    var $subHead = $('#ProjectSubHeadId');
    var hasVisible = false;
    $subHead.find('option').each(function() {
        var $opt = $(this);
        if ($opt.val() === '') {
            return;
        }
        var projectId = String($opt.data('project') || '');
        var show = (selectedProjectId === '' || projectId === String(selectedProjectId));
        $opt.prop('disabled', !show).toggle(show);
        if (show) {
            hasVisible = true;
        }
    });
    if (selectedSubHeadId && $subHead.find('option[value="' + selectedSubHeadId + '"]:enabled').length) {
        $subHead.val(selectedSubHeadId);
    } else {
        $subHead.val('');
    }
    if (!hasVisible) {
        $subHead.val('');
    }
}

function product_lists() {
    $.ajax({
        type: 'POST',
        url: '../ajax_files/ajax_project_cost.php',
        data: { action: 'view' },
        success: function(data) {
            $('#custresult').html(data);
        }
    });
}

function error_toast() {
    var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
    $.growl.error({
        title: 'Error',
        message: 'Total Cost record already exists for this Project, Sub Head and Capacity.',
        location: isRtl ? 'tl' : 'tr'
    });
}

function success_toast() {
    var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
    $.growl.notice({
        title: 'Success',
        message: 'Total Cost added successfully!',
        location: isRtl ? 'tl' : 'tr'
    });
}

function update_toast() {
    var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
    $.growl.notice({
        title: 'Success',
        message: 'Total Cost updated successfully!',
        location: isRtl ? 'tl' : 'tr'
    });
}

$(document).ready(function() {
    product_lists();

    $('#ProjectId').on('change', function() {
        filterSubHeadOptions($(this).val(), '');
    });

    $('#add_button').click(function() {
        $('.modal-title').html("Add <span class='font-weight-light'>Total Cost</span>");
        $('#action').val('Add');
        $('#id').val('');
        $('#ProjectId').val('');
        $('#CapacityId').val('');
        $('#Amount').val('');
        $('#Status').val('1');
        filterSubHeadOptions('', '');
        $('#submit').text('Submit');
    });

    $('#validation-form').on('submit', function(e) {
        e.preventDefault();
        var action = $('#action').val();
        if ($('#validation-form').valid()) {
            $.ajax({
                url: '../ajax_files/ajax_project_cost.php',
                method: 'POST',
                data: new FormData(this),
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#submit').attr('disabled', 'disabled').text('Please Wait...');
                },
                success: function(data) {
                    if (data == 1) {
                        if (action === 'Edit') {
                            update_toast();
                        } else {
                            success_toast();
                        }
                        $('.insert_frm').modal('hide');
                    } else {
                        error_toast();
                        $('.insert_frm').modal('show');
                    }
                    product_lists();
                    $('#submit').attr('disabled', false).text(action === 'Edit' ? 'Update' : 'Submit');
                    $('#action').val('Add');
                }
            });
        }
        return false;
    });

    $(document).on('click', '.update', function(event) {
        event.preventDefault();
        event.stopPropagation();
        var id = $(this).attr('data-id');
        $.ajax({
            url: '../ajax_files/ajax_project_cost.php',
            method: 'POST',
            data: { action: 'fetch_record', id: id },
            dataType: 'json',
            success: function(data) {
                $('#ProjectId').val(data.ProjectId);
                filterSubHeadOptions(data.ProjectId, data.ProjectSubHeadId);
                $('#CapacityId').val(data.CapacityId);
                $('#Amount').val(data.Amount);
                $('#Status').val(data.Status);
                $('#action').val('Edit');
                $('#id').val(id);
                $('#submit').text('Update');
                $('.insert_frm').modal('show');
                $('.modal-title').html("Update <span class='font-weight-light'>Total Cost</span>");
            }
        });
    });

    $(document).on('click', '.delete', function(event) {
        event.preventDefault();
        var id = $(this).attr('data-id');
        swal({
            title: 'Are you sure?',
            text: 'This total cost record will be deleted.',
            type: 'warning',
            showCancelButton: true,
            confirmButtonClass: 'btn-danger',
            confirmButtonText: 'Yes, delete',
            cancelButtonText: 'No, cancel',
            closeOnConfirm: false,
            closeOnCancel: false
        }, function(isConfirm) {
            if (isConfirm) {
                $.ajax({
                    url: '../ajax_files/ajax_project_cost.php',
                    method: 'POST',
                    data: { action: 'delete', id: id },
                    success: function() {
                        swal('Deleted!', 'Total Cost record has been deleted.', 'success');
                        product_lists();
                    }
                });
            } else {
                swal('Cancelled', 'Record is safe :)', 'error');
            }
        });
    });
});
</script>
</body>
</html>
