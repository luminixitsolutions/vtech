<?php
session_start();
if (empty($_SESSION['rooftop_login_pending_id']) || empty($_SESSION['rooftop_login_otp_hash'])) {
    header('Location: index.php');
    exit;
}
include_once 'config.php';
$phoneMask = $_SESSION['rooftop_login_phone_mask'] ?? '****';
$otpPrefillJs = '';
if (!empty($RooftopLoginOtpDevPrefill) && isset($_SESSION['rooftop_login_otp_prefill'])) {
    $d = preg_replace('/\D/', '', (string) $_SESSION['rooftop_login_otp_prefill']);
    if (strlen($d) === 6) {
        $otpPrefillJs = $d;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title><?php echo $Proj_Title; ?> - Verify OTP</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="format-detection" content="telephone=no">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/fonts/fontawesome.css">
<link rel="stylesheet" href="assets/libs/growl/growl.css">
<link rel="stylesheet" href="css/admin-auth.css">
<script>window.__ROOFTOP_OTP_PREFILL__=<?php echo json_encode($otpPrefillJs); ?>;</script>
</head>
<body class="admin-auth-page">

<div class="admin-auth-shell">
    <aside class="admin-auth-hero" aria-hidden="true">
        <div class="admin-auth-hero-inner">
            <div class="admin-auth-hero-badge">
                <i class="fas fa-solar-panel"></i>
                Secure verification
            </div>
            <h1>Almost there</h1>
            <p>We sent a one-time code to verify it's you. Enter all 6 digits to access your dashboard.</p>
            <ul class="admin-auth-features">
                <li><span><i class="fas fa-mobile-alt"></i></span> Code sent to your registered mobile</li>
                <li><span><i class="fas fa-clock"></i></span> Code expires after a short time</li>
                <li><span><i class="fas fa-lock"></i></span> Never share your OTP with anyone</li>
            </ul>
        </div>
    </aside>

    <main class="admin-auth-panel">
        <div class="admin-auth-card">
            <div class="admin-auth-shield" aria-hidden="true">
                <i class="fas fa-shield-alt"></i>
            </div>

            <div class="admin-auth-steps" aria-label="Sign in progress">
                <div class="admin-auth-step done">
                    <span class="admin-auth-step-num"><i class="fas fa-check" style="font-size:0.65rem"></i></span>
                    Sign in
                </div>
                <span class="admin-auth-step-line done"></span>
                <div class="admin-auth-step active">
                    <span class="admin-auth-step-num">2</span>
                    Verify OTP
                </div>
            </div>

            <h2>Enter verification code</h2>
            <p class="admin-auth-subtitle">Type each digit in the boxes below</p>

            <div class="admin-auth-phone-hint">
                <i class="fas fa-sms"></i>
                <span>OTP sent to <strong><?php echo htmlspecialchars($phoneMask, ENT_QUOTES, 'UTF-8'); ?></strong></span>
            </div>

            <form id="otp-form" method="post" autocomplete="off" data-lpignore="true">
                <span class="admin-auth-otp-label" id="otp-label">One-time password (6 digits)</span>
                <div class="admin-auth-otp-boxes otp-boxes" role="group" aria-labelledby="otp-label">
                    <?php for ($i = 0; $i < 6; $i++): ?>
                    <input type="tel"
                        class="form-control otp-cell"
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

                <button type="submit" id="submit-otp" class="admin-auth-btn">
                    <span class="btn-spinner" aria-hidden="true"></span>
                    <span class="btn-label">Verify &amp; continue</span>
                </button>

                <div class="admin-auth-footer">
                    <a href="cancel-pending-login.php"><i class="fas fa-arrow-left"></i> Cancel and return to sign in</a>
                </div>
            </form>
        </div>
    </main>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="assets/libs/growl/growl.js"></script>
<script type="text/javascript">
function error_otp(msg){
    var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
    $.growl.error({
        title: 'Error',
        message: msg || 'Invalid OTP',
        location: isRtl ? 'tl' : 'tr'
    });
}
function success_otp(){
    var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
    $.growl.notice({
        title: 'Success',
        message: 'Verified! Please wait...',
        location: isRtl ? 'tl' : 'tr'
    });
}

function syncOtpFilledClass($cells) {
    $cells.each(function(){
        $(this).toggleClass('filled', !!this.value);
    });
}

$(document).ready(function(){
    var $cells = $('.otp-cell');
    var $form = $('#otp-form');
    var $btn = $('#submit-otp');
    var otpSubmitting = false;

    function readOtp() {
        var s = '';
        $cells.each(function(){ s += ($(this).val() || '').replace(/\D/g, ''); });
        return s;
    }

    function verifyOtpCode() {
        if (otpSubmitting) {
            return;
        }
        var code = readOtp();
        if (code.length !== 6) {
            error_otp('Enter all 6 digits.');
            return;
        }
        otpSubmitting = true;
        var fd = new FormData();
        fd.append('Otp', code);
        $.ajax({
            url: 'ajax_files/ajax_verify_rooftop_otp.php',
            method: 'POST',
            data: fd,
            contentType: false,
            processData: false,
            dataType: 'json',
            timeout: 60000,
            beforeSend: function(){
                $btn.prop('disabled', true).addClass('is-loading');
                $btn.find('.btn-label').text('Please wait...');
            },
            success: function(res){
                if (!res || typeof res !== 'object') {
                    error_otp('Something went wrong.');
                    return;
                }
                if (res.Status == 1) {
                    success_otp();
                    var dest = 'dashboard.php';
                    if (res.Redirect && /^[a-z0-9\-_.]+\.php$/i.test(res.Redirect)) {
                        dest = res.Redirect;
                    }
                    setTimeout(function(){ window.location.href = dest; }, 1200);
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
            complete: function(){
                otpSubmitting = false;
                $btn.prop('disabled', false).removeClass('is-loading');
                $btn.find('.btn-label').text('Verify & continue');
            }
        });
    }

    function tryAutoVerify() {
        if (readOtp().length === 6) {
            verifyOtpCode();
        }
    }

    setTimeout(function(){
        $cells.removeAttr('readonly');
        var pre = window.__ROOFTOP_OTP_PREFILL__ || '';
        if (pre.length === 6) {
            for (var j = 0; j < 6; j++) {
                $cells.eq(j).val(pre.charAt(j));
            }
            syncOtpFilledClass($cells);
            $cells.eq(5).focus();
            setTimeout(tryAutoVerify, 200);
        } else {
            $cells.first().focus();
        }
    }, 150);

    $cells.on('focus', function(){ this.removeAttribute('readonly'); });
    $cells.on('keydown', function(e){
        var idx = $cells.index(this);
        if (e.key === 'Backspace' && !this.value && idx > 0) {
            $cells.eq(idx - 1).focus().val('');
            syncOtpFilledClass($cells);
            e.preventDefault();
        }
    });
    $cells.on('input', function(){
        var v = this.value.replace(/\D/g, '').slice(-1);
        this.value = v;
        syncOtpFilledClass($cells);
        if (v) {
            var idx = $cells.index(this);
            if (idx < 5) {
                $cells.eq(idx + 1).focus();
            } else {
                tryAutoVerify();
            }
        }
    });
    $cells.on('paste', function(e){
        e.preventDefault();
        var raw = (e.originalEvent.clipboardData || window.clipboardData).getData('text') || '';
        var d = raw.replace(/\D/g, '').slice(0, 6);
        for (var j = 0; j < 6; j++) {
            $cells.eq(j).val(d.charAt(j) || '');
        }
        syncOtpFilledClass($cells);
        if (d.length === 6) {
            $cells.eq(5).blur();
            setTimeout(tryAutoVerify, 100);
        } else {
            var next = Math.min(Math.max(d.length - 1, 0), 5);
            $cells.eq(next).focus();
        }
    });

    $form.on('submit', function(e){
        e.preventDefault();
        verifyOtpCode();
    });
});
</script>
</body>
</html>
