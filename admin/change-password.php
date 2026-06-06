<?php
session_start();
include_once 'config.php';
include_once 'auth.php';

$user_id = (int) $_SESSION['Admin']['id'];
$row77 = getRecord("SELECT Fname, Lname, Phone FROM tbl_users WHERE id='$user_id'");
$MainPage = 'Dashboard';
$Page = 'Change-Password';
$displayName = trim(($row77['Fname'] ?? '') . ' ' . ($row77['Lname'] ?? ''));
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | Change Password</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<?php include_once 'header_script.php'; ?>
</head>
<body>
<div class="layout-wrapper layout-2">
<div class="layout-inner">
<?php include_once 'header.php'; ?>
<div class="layout-container">
<?php include_once 'top_header.php'; ?>
<div class="layout-content">
<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Change Password</h4>
<div class="card col-md-6">
<div class="card-body">
<p class="text-muted small">Logged in as <strong><?php echo htmlspecialchars($displayName); ?></strong></p>
<div id="pwd_alert"></div>
<form id="change-password-form" autocomplete="off">
<div class="form-group">
<label class="form-label">Current password <span class="text-danger">*</span></label>
<input type="password" name="OldPassword" class="form-control" required>
</div>
<div class="form-group">
<label class="form-label">New password <span class="text-danger">*</span></label>
<input type="password" name="NewPassword" id="NewPassword" class="form-control" required minlength="4">
</div>
<div class="form-group">
<label class="form-label">Confirm new password <span class="text-danger">*</span></label>
<input type="password" name="ConfirmPassword" class="form-control" required minlength="4">
</div>
<button type="submit" class="btn btn-primary" id="pwd_submit">Update Password</button>
<a href="dashboard.php" class="btn btn-outline-secondary ml-2">Cancel</a>
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
$(function () {
    $('#change-password-form').on('submit', function (e) {
        e.preventDefault();
        var np = $('#NewPassword').val();
        var cp = $('input[name="ConfirmPassword"]').val();
        if (np !== cp) {
            $('#pwd_alert').html('<div class="alert alert-danger">New password and confirm password do not match.</div>');
            return;
        }
        $.ajax({
            url: 'ajax_files/ajax_change_password_user.php',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            beforeSend: function () { $('#pwd_submit').prop('disabled', true).text('Please wait…'); },
            success: function (res) {
                if (res && res.ok) {
                    $('#pwd_alert').html('<div class="alert alert-success">' + (res.message || 'Password updated.') + '</div>');
                    $('#change-password-form')[0].reset();
                } else {
                    $('#pwd_alert').html('<div class="alert alert-danger">' + (res && res.message ? res.message : 'Update failed.') + '</div>');
                }
            },
            error: function () {
                $('#pwd_alert').html('<div class="alert alert-danger">Request failed. Please try again.</div>');
            },
            complete: function () { $('#pwd_submit').prop('disabled', false).text('Update Password'); }
        });
    });
});
</script>
</body>
</html>
