<?php
session_start();
require_once 'config.php';
require_once 'auth.php';
require_once 'leave_request_helpers.php';

$PageName = "Leave";
$UserId = (int) $_SESSION['User']['id'];
$sql11 = "SELECT * FROM tbl_users WHERE id='$UserId'";
$row11 = getRecord($sql11);
$Name = $row11['Fname'] . " " . $row11['Lname'];
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_leave'])) {
    $from = trim($_POST['FromDate'] ?? '');
    $to = trim($_POST['ToDate'] ?? '');
    if ($to === '') {
        $to = $from;
    }
    $reason = $conn->real_escape_string(substr(trim($_POST['Reason'] ?? ''), 0, 500));
    if ($from === '' || $to === '') {
        $error = 'Please select at least the leave from date.';
    } else {
        $days = leave_request_inclusive_span_days($from, $to);
        if ($days < 1) {
            $error = 'To date must be the same or after the from date.';
        } else {
            $f = $conn->real_escape_string($from);
            $t = $conn->real_escape_string($to);
            $at = $conn->real_escape_string(date('Y-m-d H:i:s'));
            $sql = "INSERT INTO tbl_leave_request (UserId, FromDate, ToDate, LeaveDays, Reason, Status, CreatedAt) VALUES ($UserId, '$f', '$t', " . (int) $days . ", '" . $reason . "', 'Pending', '$at')";
            if ($conn->query($sql)) {
                header('Location: my-leave-requests.php?ok=1');
                exit;
            } else {
                $error = 'Could not save request. Please try again.';
            }
        }
    }
}

$fromInit = $_POST['FromDate'] ?? date('Y-m-d');
$toInit = $_POST['ToDate'] ?? '';
$toForTotal = $toInit !== '' ? $toInit : $fromInit;
$totalInit = leave_request_inclusive_span_days($fromInit, $toForTotal);
if ($totalInit < 1) {
    $totalInit = 0;
}
?>
<!doctype html>
<html lang="en" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo $Proj_Title; ?> — Apply leave</title>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <link rel="apple-touch-icon" href="img/favicon180.png" sizes="180x180">
    <link rel="icon" href="img/favicon32.png" sizes="32x32" type="image/png">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&amp;display=swap" rel="stylesheet">
    <link href="vendor/swiper/css/swiper.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet" id="style">
    <link href="css/toastr.min.css" rel="stylesheet">
    <script src="js/jquery.min3.5.1.js" type="text/javascript"></script>
    <script type="text/javascript" src="js/toastr.min.js"></script>
    <style>
        .leave-form .form-group label { display: block; font-size: 0.8rem; font-weight: 500; margin-bottom: 0.35rem; color: #333; }
        .leave-form .form-control { min-height: 48px; }
        .leave-form textarea.form-control { min-height: 100px; }
        .lr-total-box {
            background: linear-gradient(135deg, #fff5f2 0%, #f8fbff 100%);
            border: 1px solid rgba(231, 70, 35, 0.2);
            border-radius: 12px;
            padding: 0.75rem 1rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
        }
        .lr-total-box .lr-total-lbl { font-size: 0.8rem; color: #555; font-weight: 500; }
        .lr-total-box .lr-total-n {
            font-size: 1.5rem;
            font-weight: 600;
            color: #e74623;
            line-height: 1;
        }
        .lr-total-box.is-invalid { border-color: #dc3545; background: #fff5f5; }
        .lr-total-box.is-invalid .lr-total-n { color: #dc3545; font-size: 0.95rem; }
    </style>
</head>
<body class="body-scroll d-flex flex-column h-100 menu-overlay">
    <main class="flex-shrink-0 main">
        <?php include_once 'back-header.php'; ?>
        <div class="main-container">
            <div class="container">
                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="mb-0" style="color:#e74623;">Apply for leave</h6>
                    </div>
                    <div class="card-body">
                        <?php if ($error) { ?><p class="text-danger small mb-2"><?php echo htmlspecialchars($error); ?></p><?php } ?>
                        <p class="small text-muted">Submit a request for one day or a date range. It stays <strong>Pending</strong> until an admin approves or rejects it.</p>
                        <form method="post" action="" autocomplete="off" class="mt-2 leave-form">
                            <input type="hidden" name="submit_leave" value="1" />
                            <div class="form-group">
                                <label for="lr_from">From date <span class="text-danger">*</span></label>
                                <input type="date" name="FromDate" id="lr_from" class="form-control" required
                                    value="<?php echo htmlspecialchars($fromInit); ?>" />
                            </div>
                            <div class="form-group">
                                <label for="lr_to">To date (optional)</label>
                                <input type="date" name="ToDate" id="lr_to" class="form-control"
                                    value="<?php echo htmlspecialchars($toInit); ?>" />
                                <small class="form-text text-muted d-block mt-1">Leave empty for a single day — only the from date is used.</small>
                            </div>
                            <div class="lr-total-box" id="lr_total_box" aria-live="polite">
                                <span class="lr-total-lbl">Total days <span class="text-muted" style="font-weight:400;">(inclusive)</span></span>
                                <span class="lr-total-n" id="lr_total_n"><?php echo (int) $totalInit; ?></span>
                            </div>
                            <div class="form-group">
                                <label for="lr_reason">Reason (optional)</label>
                                <textarea name="Reason" id="lr_reason" class="form-control" rows="3" maxlength="500"><?php echo htmlspecialchars($_POST['Reason'] ?? ''); ?></textarea>
                            </div>
                            <button type="submit" class="btn btn-default btn-block rounded">Submit request</button>
                        </form>
                    </div>
                </div>
                <a href="my-leave-requests.php" class="btn btn-sm btn-link">View my leave requests</a>
            </div>
        </div>
    </main>
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
    <script>
    (function () {
        function parseYmd(s) {
            if (!s || typeof s !== 'string') return null;
            var p = s.split('-');
            if (p.length !== 3) return null;
            var y = parseInt(p[0], 10), m = parseInt(p[1], 10) - 1, d = parseInt(p[2], 10);
            if (isNaN(y) || isNaN(m) || isNaN(d)) return null;
            return new Date(y, m, d);
        }
        function daysInclusive(fromStr, toStr) {
            var d1 = parseYmd(fromStr);
            if (!d1) return { ok: false, days: 0, msg: '—' };
            var endStr = toStr && toStr.length ? toStr : fromStr;
            var d2 = parseYmd(endStr);
            if (!d2) return { ok: false, days: 0, msg: '—' };
            if (d2 < d1) return { ok: false, days: -1, msg: 'To date before from' };
            var days = Math.round((d2 - d1) / 864e5) + 1;
            return { ok: true, days: days, msg: String(days) };
        }
        function updateTotal() {
            var from = document.getElementById('lr_from');
            var to = document.getElementById('lr_to');
            var box = document.getElementById('lr_total_box');
            var out = document.getElementById('lr_total_n');
            if (!from || !to || !box || !out) return;
            var r = daysInclusive(from.value, to.value);
            box.classList.remove('is-invalid');
            if (!from.value) {
                out.textContent = '—';
                return;
            }
            if (!r.ok || r.days < 0) {
                box.classList.add('is-invalid');
                out.textContent = r.days < 0 ? 'Fix dates' : '—';
                return;
            }
            out.textContent = r.days;
        }
        var fromEl = document.getElementById('lr_from');
        var toEl = document.getElementById('lr_to');
        if (fromEl) { fromEl.addEventListener('change', updateTotal); fromEl.addEventListener('input', updateTotal); }
        if (toEl) { toEl.addEventListener('change', updateTotal); toEl.addEventListener('input', updateTotal); }
        updateTotal();
    })();
    </script>
</body>
</html>
