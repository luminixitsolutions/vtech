<?php
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = (int) $_SESSION['User']['id'];
$MainPage = 'Leave';
$Page = 'Apply Leave';
$todayYmd = date('Y-m-d');
$todayDisplay = date('m/d/Y');

if (isset($_POST['submit'])) {
    $duration = isset($_POST['duration']) ? trim($_POST['duration']) : 'full';
    if ($duration !== 'half') {
        $duration = 'full';
    }
    $FromDate = addslashes(trim($_POST['FromDate']));
    $ToDate = addslashes(trim($_POST['ToDate']));
    $Reason = addslashes(trim($_POST['Reason']));
    $HalfSession = addslashes(trim($_POST['HalfSession'] ?? ''));
    $CreatedAt = date('Y-m-d H:i:s');

    if ($Reason === '') {
        $_SESSION['toastr_leave'] = array('type' => 'error', 'message' => 'Please enter leave reason.');
        header('Location: add-leave-request.php');
        exit;
    }

    if ($duration === 'half') {
        $ToDate = $FromDate;
        if ($FromDate === '' || !strtotime($FromDate)) {
            $_SESSION['toastr_leave'] = array('type' => 'error', 'message' => 'Please choose date for half day leave.');
            header('Location: add-leave-request.php');
            exit;
        }
        if ($HalfSession === '') {
            $_SESSION['toastr_leave'] = array('type' => 'error', 'message' => 'Please select half day session.');
            header('Location: add-leave-request.php');
            exit;
        }
        $LeaveDays = 0.5;
    } else {
        $ts1 = strtotime($FromDate);
        $ts2 = strtotime($ToDate);
        if (!$ts1 || !$ts2 || $ts2 < $ts1) {
            $_SESSION['toastr_leave'] = array('type' => 'error', 'message' => 'Please choose valid from and to dates (to must be on or after from).');
            header('Location: add-leave-request.php');
            exit;
        }
        $LeaveDays = (int) floor(($ts2 - $ts1) / 86400) + 1;
        if ($LeaveDays < 1) {
            $LeaveDays = 1;
        }
        $HalfSession = '';
    }

    $Attachment = '';
    if (!empty($_FILES['Attachment']['name']) && is_uploaded_file($_FILES['Attachment']['tmp_name'])) {
        $origName = $_FILES['Attachment']['name'];
        $extRaw = pathinfo($origName, PATHINFO_EXTENSION);
        $fnm = pathinfo($origName, PATHINFO_FILENAME);
        $fnm = preg_replace('/[^a-zA-Z0-9_-]/', '_', $fnm);
        if ($fnm === '') {
            $fnm = 'leave';
        }
        $ext = $extRaw !== '' ? '.' . preg_replace('/[^a-zA-Z0-9]/', '', $extRaw) : '.jpg';
        $randno = rand(1, 100);
        $dest = dirname(__DIR__) . '/uploads/' . $randno . '_' . $fnm . $ext;
        $imagepath = $randno . '_' . $fnm . $ext;
        if (move_uploaded_file($_FILES['Attachment']['tmp_name'], $dest)) {
            $Attachment = addslashes($imagepath);
        }
    }

    $halfSql = ($HalfSession !== '') ? "'$HalfSession'" : 'NULL';
    $attSql = ($Attachment !== '') ? "'$Attachment'" : 'NULL';
    $LeaveDaysEsc = addslashes((string) $LeaveDays);

    $qx = "INSERT INTO tbl_leave_request SET UserId='$user_id', FromDate='$FromDate', ToDate='$ToDate', LeaveDays='$LeaveDaysEsc', Reason='$Reason', Attachment=$attSql, HalfSession=$halfSql, Status='Pending', CreatedAt='$CreatedAt'";
    if (!$conn->query($qx)) {
        $_SESSION['toastr_leave'] = array('type' => 'error', 'message' => 'Could not save your request. Please try again.');
        header('Location: add-leave-request.php');
        exit;
    }
    $_SESSION['toastr_leave'] = array('type' => 'success', 'message' => 'Leave request submitted successfully!');
    header('Location: view-leave-requests.php');
    exit;
}
?>
<!doctype html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo $Proj_Title; ?> · Apply leave</title>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <link rel="apple-touch-icon" href="img/favicon180.png" sizes="180x180">
    <link rel="icon" href="img/favicon32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="img/favicon16.png" sizes="16x16" type="image/png">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;600&amp;display=swap" rel="stylesheet">
    <link href="vendor/swiper/css/swiper.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet" id="style">
    <link href="css/toastr.min.css" rel="stylesheet">
    <script src="js/jquery.min3.5.1.js" type="text/javascript"></script>
    <script type="text/javascript" src="js/toastr.min.js"></script>
    <link rel="stylesheet" href="example/css/slim.min.css">
    <?php include_once 'header_script.php'; ?>
</head>

<style>
    .al-wrap { max-width: 520px; margin: 0 auto; padding-bottom: 2rem; }
    .al-hero {
        background: linear-gradient(135deg, #405189 0%, #5a6dae 55%, #6b7fc7 100%);
        color: #fff;
        border-radius: 0 0 22px 22px;
        padding: 1.35rem 1.25rem 1.5rem;
        margin: -0.5rem -12px 1.15rem;
        box-shadow: 0 10px 28px rgba(64, 81, 137, 0.28);
    }
    .al-hero h1 { font-size: 1.35rem; font-weight: 600; margin: 0 0 0.35rem; letter-spacing: -0.02em; }
    .al-hero p { margin: 0; opacity: 0.9; font-size: 0.8125rem; line-height: 1.45; }
    .al-card {
        background: #fff;
        border-radius: 18px;
        padding: 1.25rem 1.15rem 1.35rem;
        box-shadow: 0 4px 22px rgba(15, 23, 42, 0.07);
        border: 1px solid rgba(15, 23, 42, 0.06);
    }
    .al-label { font-weight: 600; font-size: 0.8125rem; color: #1e293b; margin-bottom: 0.4rem; display: block; }
    .al-label .req { color: #dc2626; font-weight: 700; }
    .al-input, .al-select, .al-textarea {
        width: 100%;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 0.65rem 0.85rem;
        font-size: 0.9375rem;
        color: #334155;
        background: #fff;
        transition: border-color 0.15s, box-shadow 0.15s;
    }
    .al-input:focus, .al-select:focus, .al-textarea:focus {
        outline: none;
        border-color: #405189;
        box-shadow: 0 0 0 3px rgba(64, 81, 137, 0.15);
    }
    .al-input[readonly] { background: #f1f5f9; color: #64748b; cursor: default; }
    .al-field { margin-bottom: 1.1rem; }
    .al-duration-row { display: flex; gap: 0.65rem; margin-bottom: 0.25rem; }
    .al-dur-card {
        flex: 1;
        border: 2px solid #e2e8f0;
        border-radius: 14px;
        padding: 0.85rem 0.65rem;
        text-align: center;
        cursor: pointer;
        transition: border-color 0.15s, background 0.15s, box-shadow 0.15s;
        background: #fafafa;
    }
    .al-dur-card:hover { border-color: #cbd5e1; }
    .al-dur-card.is-on {
        border-color: #2563eb;
        background: #eff6ff;
        box-shadow: 0 0 0 1px rgba(37, 99, 235, 0.2);
    }
    .al-dur-card input { position: absolute; opacity: 0; pointer-events: none; }
    .al-dur-card .dot {
        width: 18px; height: 18px; border-radius: 50%;
        border: 2px solid #94a3b8; margin: 0 auto 0.45rem;
        display: flex; align-items: center; justify-content: center;
    }
    .al-dur-card.is-on .dot { border-color: #2563eb; background: #2563eb; }
    .al-dur-card.is-on .dot::after {
        content: ''; width: 6px; height: 6px; border-radius: 50%; background: #fff;
    }
    .al-dur-title { font-weight: 600; font-size: 0.8125rem; color: #334155; line-height: 1.25; }
    .al-dur-sub { font-size: 0.6875rem; color: #94a3b8; margin-top: 0.15rem; }
    .al-days-readonly {
        background: #f1f5f9;
        border-radius: 12px;
        padding: 0.65rem 0.85rem;
        font-weight: 600;
        color: #405189;
        font-size: 1rem;
    }
    .al-file-wrap {
        border: 2px dashed #cbd5e1;
        border-radius: 14px;
        padding: 1rem;
        text-align: center;
        background: #f8fafc;
        transition: border-color 0.15s, background 0.15s;
    }
    .al-file-wrap.has-file { border-color: #405189; background: #f1f5f9; }
    .al-file-wrap input[type=file] { position: absolute; width: 0.1px; height: 0.1px; opacity: 0; overflow: hidden; z-index: -1; }
    .al-file-btn {
        display: inline-flex; align-items: center; gap: 0.35rem;
        padding: 0.45rem 0.9rem; border-radius: 999px;
        background: #405189; color: #fff; font-size: 0.8125rem; font-weight: 600;
        cursor: pointer; border: none;
    }
    .al-file-name { font-size: 0.75rem; color: #64748b; margin-top: 0.5rem; word-break: break-all; }
    .al-submit {
        width: 100%;
        border: none;
        border-radius: 999px;
        padding: 0.85rem 1rem;
        font-size: 1rem;
        font-weight: 600;
        color: #fff;
        background: linear-gradient(180deg, #0f766e 0%, #0d5c56 100%);
        box-shadow: 0 6px 18px rgba(15, 118, 110, 0.35);
        margin-top: 0.35rem;
        cursor: pointer;
        transition: transform 0.12s, box-shadow 0.12s;
    }
    .al-submit:active { transform: scale(0.99); }
    .al-cancel {
        display: block; text-align: center; margin-top: 0.85rem;
        font-size: 0.875rem; color: #64748b; text-decoration: none;
    }
    .al-cancel:hover { color: #405189; }
    .al-row-half { display: none; }
    .al-row-half.show { display: block; }
    .al-row-to.hide { display: none; }
</style>

<body class="body-scroll d-flex flex-column h-100 menu-overlay">

<main class="flex-shrink-0 main">
    <?php include_once 'back-header.php'; ?>

    <div class="main-container">

    <div class="layout-content">
        <div class="container-fluid flex-grow-1 container-p-y px-2">

            <div class="al-wrap">
               
                    <h3 class="font-weight-bold py-3 mb-0 text-center">Apply for leave</h3>
                   
                

                <div class="al-card">
                    <form method="post" action="" enctype="multipart/form-data" id="frmLeave">

                        <div class="al-field">
                            <label class="al-label">Request date <span class="req">*</span></label>
                            <input type="text" class="al-input" name="RequestDateDisplay" id="RequestDateDisplay" value="<?php echo htmlspecialchars($todayDisplay); ?>" readonly>
                            <input type="hidden" name="RequestDate" value="<?php echo htmlspecialchars($todayYmd); ?>">
                        </div>

                        <div class="al-field">
                            <span class="al-label">Leave duration <span class="req">*</span></span>
                            <div class="al-duration-row">
                                <label class="al-dur-card is-on" id="cardFull" data-dur="full">
                                    <input type="radio" name="duration" value="full" checked>
                                    <div class="dot"></div>
                                    <div class="al-dur-title">Full day</div>
                                    <div class="al-dur-sub">Date range</div>
                                </label>
                                <label class="al-dur-card" id="cardHalf" data-dur="half">
                                    <input type="radio" name="duration" value="half">
                                    <div class="dot"></div>
                                    <div class="al-dur-title">Half day</div>
                                    <div class="al-dur-sub">4.5 hrs</div>
                                </label>
                            </div>
                        </div>

                        <div class="al-field al-row-half" id="rowHalfSession">
                            <label class="al-label">Half day session <span class="req">*</span></label>
                            <select name="HalfSession" id="HalfSession" class="al-select">
                                <option value="">Select</option>
                                <option value="1st Half (10:00 – 2:30)">1st Half (10:00 – 2:30)</option>
                                <option value="2nd Half (2:30 – 7:00)">2nd Half (2:30 – 7:00)</option>
                            </select>
                        </div>

                        <div class="al-field" id="rowFromDate">
                            <label class="al-label" id="lblFromDate">From date <span class="req">*</span></label>
                            <input type="date" name="FromDate" id="FromDate" class="al-input" required value="<?php echo htmlspecialchars($todayYmd); ?>">
                        </div>

                        <div class="al-field al-row-to" id="rowToDate">
                            <label class="al-label">To date <span class="req">*</span></label>
                            <input type="date" name="ToDate" id="ToDate" class="al-input" required value="<?php echo htmlspecialchars($todayYmd); ?>">
                        </div>

                        <div class="al-field">
                            <label class="al-label">Leave days <span class="req">*</span></label>
                            <div class="al-days-readonly" id="LeaveDaysDisplay">1</div>
                        </div>

                        <div class="al-field">
                            <label class="al-label">Leave reason <span class="req">*</span></label>
                            <textarea name="Reason" id="Reason" class="al-textarea" rows="4" maxlength="500" required placeholder="Describe your leave reason"></textarea>
                        </div>

                        <div class="al-field">
                            <label class="al-label">Attach image <span style="color:#94a3b8;font-weight:500;">(optional)</span></label>
                            <div class="al-file-wrap" id="fileWrap">
                                <input type="file" name="Attachment" id="Attachment" accept="image/*">
                                <label for="Attachment" class="al-file-btn">
                                    <span class="material-icons" style="font-size:1.1rem;">add_photo_alternate</span>
                                    Choose image
                                </label>
                                <div class="al-file-name" id="fileName">No file chosen</div>
                            </div>
                        </div>

                        <button type="submit" name="submit" class="al-submit">Submit request</button>
                        <a href="view-leave-requests.php" class="al-cancel">Cancel</a>
                    </form>
                </div>
            </div>

            <?php include_once 'footer.php'; ?>

        </div>
    </div>
</div>
</main>

<script src="js/jquery-3.3.1.min.js"></script>
<script src="js/popper.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="js/jquery.cookie.js"></script>
<script src="vendor/swiper/js/swiper.min.js"></script>
<script src="js/main.js"></script>
<script src="js/color-scheme-demo.js"></script>
<script src="js/app.js"></script>
<?php include_once 'footer_script.php'; ?>
<script>
(function () {
    var $full = $('#cardFull');
    var $half = $('#cardHalf');
    var $durInputs = $('input[name=duration]');
    var $rowHalf = $('#rowHalfSession');
    var $rowTo = $('#rowToDate');
    var $halfSel = $('#HalfSession');
    var $from = $('#FromDate');
    var $to = $('#ToDate');
    var $disp = $('#LeaveDaysDisplay');
    var $lblFrom = $('#lblFromDate');

    function setDur(mode) {
        if (mode === 'half') {
            $half.addClass('is-on');
            $full.removeClass('is-on');
            $durInputs.filter('[value=half]').prop('checked', true);
            $rowHalf.addClass('show');
            $rowTo.addClass('hide');
            $to.prop('required', false);
            $halfSel.prop('required', true);
            $lblFrom.html('Date <span class="req">*</span>');
        } else {
            $full.addClass('is-on');
            $half.removeClass('is-on');
            $durInputs.filter('[value=full]').prop('checked', true);
            $rowHalf.removeClass('show');
            $rowTo.removeClass('hide');
            $to.prop('required', true);
            $halfSel.prop('required', false).val('');
            $lblFrom.html('From date <span class="req">*</span>');
        }
        updateDays();
    }

    function updateDays() {
        var half = $('input[name=duration]:checked').val() === 'half';
        if (half) {
            $disp.text('0.5');
            return;
        }
        var a = $from.val();
        var b = $to.val();
        if (!a || !b) { $disp.text('—'); return; }
        var d1 = new Date(a + 'T12:00:00');
        var d2 = new Date(b + 'T12:00:00');
        if (d2 < d1) { $disp.text('—'); return; }
        var days = Math.floor((d2 - d1) / 86400000) + 1;
        $disp.text(String(days));
    }

    $full.on('click', function () { setDur('full'); });
    $half.on('click', function () { setDur('half'); });
    $from.add($to).on('change', updateDays);

    $('#Attachment').on('change', function () {
        var f = this.files && this.files[0];
        var $wrap = $('#fileWrap');
        if (f) {
            $wrap.addClass('has-file');
            $('#fileName').text(f.name);
        } else {
            $wrap.removeClass('has-file');
            $('#fileName').text('No file chosen');
        }
    });

    $('#frmLeave').on('submit', function () {
        if ($('input[name=duration]:checked').val() === 'half') {
            $to.val($from.val());
        }
    });

    setDur('full');
})();
</script>
<?php
if (!empty($_SESSION['toastr_leave']) && is_array($_SESSION['toastr_leave'])) {
    $tl = $_SESSION['toastr_leave'];
    unset($_SESSION['toastr_leave']);
    $msg = isset($tl['message']) ? (string) $tl['message'] : '';
    $typ = isset($tl['type']) ? (string) $tl['type'] : 'info';
    if ($msg !== '') {
        $msgJs = json_encode($msg, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_UNESCAPED_UNICODE);
        ?>
<script>
$(function () {
  if (typeof toastr !== 'undefined') {
    toastr.options = { closeButton: true, progressBar: true, positionClass: 'toast-top-center', timeOut: 4000 };
    <?php if ($typ === 'success') { ?>
    toastr.success(<?php echo $msgJs; ?>);
    <?php } elseif ($typ === 'error') { ?>
    toastr.error(<?php echo $msgJs; ?>);
    <?php } else { ?>
    toastr.info(<?php echo $msgJs; ?>);
    <?php } ?>
  }
});
</script>
<?php
    }
}
?>
</body>
</html>
