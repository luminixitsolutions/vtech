<?php include_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<title><?php echo $Proj_Title; ?> - Login</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="VTECH Rooftop Admin Sign In" />
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/fonts/fontawesome.css">
<link rel="stylesheet" href="assets/libs/growl/growl.css">
<link rel="stylesheet" href="css/admin-auth.css">
</head>
<body class="admin-auth-page">

<div class="admin-auth-shell">
    <aside class="admin-auth-hero" aria-hidden="true">
        <div class="admin-auth-hero-inner">
            <div class="admin-auth-hero-badge">
                <i class="fas fa-solar-panel"></i>
                Rooftop Admin Portal
            </div>
            <h1>VTECH Sunsystems Pvt. Ltd.</h1>
            <p>Secure access to rooftop solar operations — manage installations, customers, leads, and field teams in one place.</p>
            <ul class="admin-auth-features">
                <li><span><i class="fas fa-shield-alt"></i></span> Two-step verification for every sign in</li>
                <li><span><i class="fas fa-chart-line"></i></span> Real-time project &amp; installation tracking</li>
                <li><span><i class="fas fa-users"></i></span> Role-based team access control</li>
            </ul>
        </div>
    </aside>

    <main class="admin-auth-panel">
        <div class="admin-auth-card">
            <img src="logo.jpg" alt="VTECH" class="admin-auth-logo" width="200" height="auto">
            <h2>Welcome back</h2>
            <p class="admin-auth-subtitle">Sign in to continue to your rooftop admin account</p>

            <form id="validation-form" method="post" novalidate>
                <div class="admin-auth-field">
                    <label for="Username">Username</label>
                    <div class="admin-auth-input-wrap">
                        <i class="fas fa-user field-icon"></i>
                        <input type="text" id="Username" name="Username" placeholder="Enter username" required autocomplete="username">
                    </div>
                </div>

                <div class="admin-auth-field">
                    <label for="Password">Password</label>
                    <div class="admin-auth-input-wrap has-toggle">
                        <i class="fas fa-lock field-icon"></i>
                        <input type="password" id="Password" name="Password" placeholder="Enter password" required autocomplete="current-password">
                        <button type="button" class="admin-auth-toggle-pw" id="togglePassword" aria-label="Show password">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" id="submit" class="admin-auth-btn">
                    <span class="btn-spinner" aria-hidden="true"></span>
                    <span class="btn-label">Sign In</span>
                </button>
            </form>
        </div>
    </main>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="assets/libs/growl/growl.js"></script>
<script type="text/javascript">
function error_toast(){
    var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
    $.growl.error({
        title: 'Error',
        message: 'Invalid Username / Password',
        location: isRtl ? 'tl' : 'tr'
    });
}
function otp_sent_toast(){
    var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
    $.growl.notice({
        title: 'OTP sent',
        message: 'Enter the code on the next screen.',
        location: isRtl ? 'tl' : 'tr'
    });
}

$(document).ready(function(){
    $('#togglePassword').on('click', function(){
        var $pw = $('#Password');
        var $icon = $(this).find('i');
        if ($pw.attr('type') === 'password') {
            $pw.attr('type', 'text');
            $icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            $pw.attr('type', 'password');
            $icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    $('#validation-form').on('submit', function(e){
        e.preventDefault();
        var username = $.trim($('#Username').val());
        var password = $('#Password').val();
        if (!username || !password) {
            error_toast();
            return;
        }
        var $btn = $('#submit');
        $.ajax({
            url: 'ajax_files/ajax_login.php',
            method: 'POST',
            data: new FormData(this),
            contentType: false,
            processData: false,
            timeout: 60000,
            beforeSend: function(){
                $btn.prop('disabled', true).addClass('is-loading');
                $btn.find('.btn-label').text('Please wait...');
            },
            success: function(data){
                var res;
                try {
                    res = typeof data === 'object' ? data : JSON.parse(data);
                } catch (e) {
                    error_toast();
                    return;
                }
                if (res.Status == 2) {
                    otp_sent_toast();
                    setTimeout(function(){
                        window.location.href = 'verify-login-otp.php';
                    }, 1200);
                } else {
                    error_toast();
                }
            },
            error: function(){
                error_toast();
            },
            complete: function(){
                $btn.prop('disabled', false).removeClass('is-loading');
                $btn.find('.btn-label').text('Sign In');
            }
        });
    });
});
</script>
</body>
</html>
