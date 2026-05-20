<?php
session_start();
if (empty($_SESSION['admin_login_pending_id']) || empty($_SESSION['admin_login_otp_hash'])) {
    header('Location: index.php');
    exit;
}
include_once 'config.php';
$phoneMask = $_SESSION['admin_login_phone_mask'] ?? '****';
$otpPrefillJs = '';
if (!empty($AdminLoginOtpDevPrefill) && isset($_SESSION['admin_login_otp_prefill'])) {
    $d = preg_replace('/\D/', '', (string) $_SESSION['admin_login_otp_prefill']);
    if (strlen($d) === 6) {
        $otpPrefillJs = $d;
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> - Verify OTP</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="format-detection" content="telephone=no">
 <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
    <link rel="stylesheet" href="assets/fonts/fontawesome.css">
    <link rel="stylesheet" href="assets/fonts/ionicons.css">
    <link rel="stylesheet" href="assets/fonts/linearicons.css">
    <link rel="stylesheet" href="assets/fonts/open-iconic.css">
    <link rel="stylesheet" href="assets/fonts/pe-icon-7-stroke.css">
    <link rel="stylesheet" href="assets/fonts/feather.css">
    <link rel="stylesheet" href="assets/css/bootstrap-material.css">
    <link rel="stylesheet" href="assets/css/shreerang-material.css">
    <link rel="stylesheet" href="assets/css/uikit.css">
    <link rel="stylesheet" href="assets/libs/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="assets/css/pages/authentication.css">
    <link rel="stylesheet" href="assets/libs/growl/growl.css">
    <link rel="stylesheet" href="assets/libs/toastr/toastr.css">
    <script>window.__ADMIN_OTP_PREFILL__=<?php echo json_encode($otpPrefillJs); ?>;</script>
</head>
<body>
    <div class="authentication-wrapper authentication-3">
        <div class="authentication-inner">
            <div class="d-none d-lg-flex col-lg-8 align-items-center ui-bg-cover ui-bg-overlay-container p-5" style="background-image:url(slider_1.jpg);">
                <div class="ui-bg-overlay bg-dark opacity-50"></div>
                <div class="w-100 text-white px-5">
                    <div class="w-100 text-white px-5">
                    <h1 class="display-2 font-weight-bolder mb-4">VTECH SUNSYSTEMS PVT. LTD.</h1>
                </div>
                </div>
            </div>
            <div class="d-flex col-lg-4 align-items-center bg-white p-5">
                <div class="d-flex col-sm-7 col-md-5 col-lg-12 px-0 px-xl-4 mx-auto">
                    <div class="w-100">
                        <div class=" justify-content-center align-items-center">
                            <div>
                                <div class=" position-relative" align="center">
                                    <img src="logo.jpg" alt="Brand Logo" width="250px" >
                                </div>
                            </div>
                        </div>
                        <h4 class="text-center font-weight-normal mt-3 mb-1">Enter verification code</h4>
                        <p class="text-center text-muted small mb-4">Type each digit in the boxes below. When SMS is enabled in config, the code will be sent to <?php echo htmlspecialchars($phoneMask, ENT_QUOTES, 'UTF-8'); ?>.</p>
                        <form id="otp-form" method="post" autocomplete="off" data-lpignore="true">
                            <div class="form-group mb-3">
                                <span class="form-label d-block mb-2" id="otp-label">One-time password (6 digits)</span>
                                <div class="d-flex justify-content-between otp-boxes" style="gap:6px;" role="group" aria-labelledby="otp-label">
                                    <?php for ($i = 0; $i < 6; $i++): ?>
                                    <input type="tel"
                                        class="form-control text-center font-weight-bold otp-cell"
                                        style="font-size:1.2rem;flex:1;min-width:0;padding-left:0.25rem;padding-right:0.25rem;"
                                        maxlength="1"
                                        inputmode="numeric"
                                        pattern="[0-9]*"
                                        autocomplete="off"
                                        autocorrect="off"
                                        autocapitalize="off"
                                        spellcheck="false"
                                        data-lpignore="true"
                                        data-1p-ignore="true"
                                        data-form-type="other"
                                        aria-label="Digit <?php echo $i + 1; ?> of 6"
                                        readonly>
                                    <?php endfor; ?>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center m-0" style="padding-top:10px;">
                                <button type="submit" id="submit-otp" class="btn btn-primary">Verify &amp; continue</button>
                            </div>
                            <p class="text-center mt-3 mb-0"><a href="cancel-pending-login.php" class="text-muted small">Cancel and return to sign in</a></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="assets/js/pace.js"></script>
    <script src="assets/libs/popper/popper.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/libs/growl/growl.js"></script>
<script type="text/javascript">
function error_otp(msg){
    var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
   $.growl.error({
      title:    'Error',
      message:  msg || 'Invalid OTP',
      location: isRtl ? 'tl' : 'tr'
    });
  }
    function success_otp(){
    var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
   $.growl.notice({
      title:    'Success',
      message:  'Verified! Please wait...',
      location: isRtl ? 'tl' : 'tr'
    });
  }
$(document).ready(function(){
    var $cells = $('.otp-cell');
    setTimeout(function () {
        $cells.removeAttr('readonly');
        var pre = window.__ADMIN_OTP_PREFILL__ || '';
        if (pre.length === 6) {
            for (var j = 0; j < 6; j++) {
                $cells.eq(j).val(pre.charAt(j));
            }
            $cells.eq(5).focus();
        } else {
            $cells.first().focus();
        }
    }, 150);
    $cells.on('focus', function () { this.removeAttribute('readonly'); });
    $cells.on('keydown', function (e) {
        var idx = $cells.index(this);
        if (e.key === 'Backspace' && !this.value && idx > 0) {
            $cells.eq(idx - 1).focus();
            $cells.eq(idx - 1).val('');
            e.preventDefault();
        }
    });
    $cells.on('input', function () {
        var v = this.value.replace(/\D/g, '').slice(-1);
        this.value = v;
        if (v) {
            var idx = $cells.index(this);
            if (idx < 5) {
                $cells.eq(idx + 1).focus();
            }
        }
    });
    $cells.on('paste', function (e) {
        e.preventDefault();
        var raw = (e.originalEvent.clipboardData || window.clipboardData).getData('text') || '';
        var d = raw.replace(/\D/g, '').slice(0, 6);
        for (var j = 0; j < 6; j++) {
            $cells.eq(j).val(d.charAt(j) || '');
        }
        var next = Math.min(Math.max(d.length - 1, 0), 5);
        $cells.eq(next).focus();
    });
    function readOtp() {
        var s = '';
        $cells.each(function () { s += ($(this).val() || '').replace(/\D/g, ''); });
        return s;
    }
    $('#otp-form').on('submit', function(e){
        e.preventDefault();
        var code = readOtp();
        if (code.length !== 6) {
            error_otp('Enter all 6 digits.');
            return;
        }
        var fd = new FormData();
        fd.append('Otp', code);
        $.ajax({
            url: 'ajax_files/ajax_verify_admin_otp.php',
            method: 'POST',
            data: fd,
            contentType: false,
            processData: false,
            dataType: 'json',
            timeout: 60000,
            beforeSend: function(){
                $('#submit-otp').prop('disabled', true).text('Please wait...');
            },
            success: function(res){
                if (!res || typeof res !== 'object') {
                    error_otp('Something went wrong.');
                    return;
                }
                if (res.Status == 1) {
                    success_otp();
                    setTimeout(function(){ window.location.href = 'file-submission-reminder.php'; }, 1200);
                } else {
                    error_otp(res.Msg || 'Invalid OTP');
                }
            },
            error: function(xhr){
                var msg = 'Network error.';
                if (xhr.responseJSON && xhr.responseJSON.Msg) {
                    msg = xhr.responseJSON.Msg;
                }
                error_otp(msg);
            },
            complete: function () {
                $('#submit-otp').prop('disabled', false).text('Verify & continue');
            }
        });
    });
});
</script>
</body>
</html>
