<?php session_start();
require_once 'config.php';
require_once 'auth.php';
$PageName = "Leave Requests";
$UserId = $_SESSION['User']['id'];
$sql11 = "SELECT * FROM tbl_users WHERE id='$UserId'";
$row11 = getRecord($sql11);
$Name = $row11['Fname']." ".$row11['Lname'];
$Phone = $row11['Phone'];
$EmailId = $row11['EmailId'];

$uid = (int) $_SESSION['User']['id'];
$rows = array();
$sql = "SELECT * FROM tbl_leave_request WHERE UserId='$uid' ORDER BY CreatedAt DESC";
$res = $conn->query($sql);
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $rows[] = $row;
    }
}
?>
<!doctype html>
<html lang="en" class="h-100">


<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="generator" content="">
    <title><?php echo $Proj_Title; ?></title>

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
    .leave-wrap {
        max-width: 520px;
        margin: 0 auto;
        padding-bottom: 1rem;
    }
    .leave-hero {
        background: linear-gradient(135deg, #405189 0%, #5a6dae 50%, #6b7fc7 100%);
        color: #fff;
        border-radius: 0 0 20px 20px;
        padding: 1.1rem 1.25rem 1.25rem;
        margin: -0.5rem -12px 1.25rem;
        box-shadow: 0 8px 24px rgba(64, 81, 137, 0.25);
    }
    .leave-hero-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
    }
    .leave-hero h1 {
        font-size: 1.25rem;
        font-weight: 600;
        margin: 0;
        letter-spacing: -0.02em;
        flex: 1;
        min-width: 0;
    }
    .leave-hero p {
        margin: 0;
        opacity: 0.88;
        font-size: 0.8125rem;
        font-weight: 400;
    }
    .leave-hero .btn-add-leave {
        background: #fff;
        color: #405189;
        border: none;
        font-weight: 600;
        font-size: 0.875rem;
        padding: 0.45rem 1rem;
        border-radius: 999px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.12);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        margin-top: 0;
        flex-shrink: 0;
        white-space: nowrap;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .leave-hero .btn-add-leave:hover {
        color: #2d3a66;
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
        text-decoration: none;
    }
    .leave-card {
        background: #fff;
        border-radius: 14px;
        padding: 1rem 1rem 0.9rem;
        margin-bottom: 0.85rem;
        box-shadow: 0 2px 12px rgba(15, 23, 42, 0.06);
        border: 1px solid rgba(15, 23, 42, 0.06);
        border-left: 4px solid #c9a227;
        position: relative;
        overflow: hidden;
    }
    .leave-card.status-approved {
        border-left-color: #198754;
    }
    .leave-card.status-rejected {
        border-left-color: #dc3545;
    }
    .leave-card.status-pending {
        border-left-color: #e7a008;
    }
    .leave-card-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 0.75rem;
        margin-bottom: 0.65rem;
    }
    .leave-badge {
        font-size: 0.6875rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        padding: 0.28rem 0.55rem;
        border-radius: 6px;
        white-space: nowrap;
    }
    .leave-badge.pending {
        background: #fff8e6;
        color: #a67c00;
    }
    .leave-badge.approved {
        background: #e8f5ee;
        color: #146c43;
    }
    .leave-badge.rejected {
        background: #fdecee;
        color: #b02a37;
    }
    .leave-days-pill {
        font-size: 0.75rem;
        font-weight: 600;
        color: #405189;
        background: rgba(64, 81, 137, 0.08);
        padding: 0.2rem 0.5rem;
        border-radius: 8px;
    }
    .leave-range {
        font-size: 1.05rem;
        font-weight: 600;
        color: #1e293b;
        line-height: 1.35;
        margin-bottom: 0.35rem;
    }
    .leave-range .material-icons {
        font-size: 1.1rem;
        vertical-align: text-bottom;
        color: #64748b;
        margin-right: 0.15rem;
    }
    .leave-reason {
        font-size: 0.8125rem;
        color: #64748b;
        line-height: 1.45;
        margin: 0.5rem 0 0;
        padding-top: 0.5rem;
        border-top: 1px dashed rgba(148, 163, 184, 0.45);
    }
    .leave-reason strong {
        color: #475569;
        font-weight: 500;
    }
    .leave-foot {
        display: flex;
        align-items: center;
        gap: 0.35rem;
        font-size: 0.75rem;
        color: #94a3b8;
        margin-top: 0.65rem;
    }
    .leave-foot .material-icons {
        font-size: 0.95rem;
    }
    .leave-comment {
        font-size: 0.78rem;
        color: #475569;
        background: #f8fafc;
        border-radius: 8px;
        padding: 0.45rem 0.6rem;
        margin-top: 0.5rem;
    }
    .leave-thumb {
        margin-top: 0.65rem;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid rgba(148, 163, 184, 0.4);
        max-height: 180px;
        background: #f1f5f9;
    }
    .leave-thumb img {
        width: 100%;
        height: auto;
        max-height: 180px;
        object-fit: cover;
        display: block;
    }
    .leave-thumb a {
        display: block;
    }
    .leave-empty {
        text-align: center;
        padding: 2.5rem 1.25rem;
        background: #fff;
        border-radius: 16px;
        border: 1px dashed rgba(64, 81, 137, 0.25);
        margin-top: 0.5rem;
    }
    .leave-empty .material-icons {
        font-size: 3.5rem;
        color: #cbd5e1;
        margin-bottom: 0.75rem;
    }
    .leave-empty h2 {
        font-size: 1rem;
        font-weight: 600;
        color: #334155;
        margin: 0 0 0.35rem;
    }
    .leave-empty p {
        font-size: 0.8125rem;
        color: #94a3b8;
        margin: 0 0 1rem;
        line-height: 1.5;
    }
    .leave-empty a {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        font-weight: 600;
        font-size: 0.875rem;
    }
</style>

<body class="body-scroll d-flex flex-column h-100 menu-overlay">

    <main class="flex-shrink-0 main">
        <?php include_once 'back-header.php'; ?>

        <div class="main-container">

            <div class="layout-content">
                <div class="container-fluid flex-grow-1 container-p-y px-2">

                    <div class="leave-wrap">
                        <div class="leave-hero">
                            <div class="leave-hero-top">
                                <h1>Leave requests</h1>
                                <a href="add-leave-request.php" class="btn-add-leave">Apply</a>
                            </div>
                        </div>

                        <?php if (count($rows) === 0) { ?>
                            <div class="leave-empty">
                                <span class="material-icons">event_busy</span>
                                <h2>No requests yet</h2>
                                <p>When you apply for leave, each request will appear here as a card with status and dates.</p>
                                <a href="add-leave-request.php" class="btn btn-primary btn-sm btn-round">New request</a>
                            </div>
                        <?php } else { ?>
                            <?php foreach ($rows as $row) {
                                $st = $row['Status'];
                                $statusClass = 'status-pending';
                                $badgeClass = 'pending';
                                $badgeLabel = 'Pending';
                                if ($st === 'Approved') {
                                    $statusClass = 'status-approved';
                                    $badgeClass = 'approved';
                                    $badgeLabel = 'Approved';
                                } elseif ($st === 'Rejected') {
                                    $statusClass = 'status-rejected';
                                    $badgeClass = 'rejected';
                                    $badgeLabel = 'Rejected';
                                }
                                $fromD = date('d M Y', strtotime($row['FromDate']));
                                $toD = date('d M Y', strtotime($row['ToDate']));
                                $applied = date('d M Y · g:i A', strtotime($row['CreatedAt']));
                                $daysNum = (float) ($row['LeaveDays'] ?? 1);
                                if ($daysNum == (int) $daysNum) {
                                    $daysStr = (string) (int) $daysNum;
                                } else {
                                    $daysStr = rtrim(rtrim(number_format($daysNum, 2, '.', ''), '0'), '.');
                                }
                                $daysPill = ($daysNum <= 0.5) ? 'Half day' : ($daysNum == 1.0 ? '1 day' : $daysStr . ' days');
                                $reason = trim((string) $row['Reason']);
                                $comment = trim((string) ($row['ApprovedComment'] ?? ''));
                                $halfSess = trim((string) ($row['HalfSession'] ?? ''));
                                $attach = trim((string) ($row['Attachment'] ?? ''));
                                ?>
                                <div class="leave-card <?php echo $statusClass; ?>">
                                    <div class="leave-card-top">
                                        <span class="leave-badge <?php echo $badgeClass; ?>"><?php echo htmlspecialchars($badgeLabel); ?></span>
                                        <span class="leave-days-pill"><?php echo htmlspecialchars($daysPill); ?></span>
                                    </div>
                                    <div class="leave-range">
                                        <span class="material-icons">date_range</span>
                                        <?php echo htmlspecialchars($fromD); ?>
                                        <span style="color:#94a3b8;font-weight:500;"> → </span>
                                        <?php echo htmlspecialchars($toD); ?>
                                    </div>
                                    <?php if ($halfSess !== '') { ?>
                                        <p class="leave-reason" style="border-top:none;padding-top:0;margin-top:0.35rem;"><strong>Session</strong> · <?php echo htmlspecialchars($halfSess); ?></p>
                                    <?php } ?>
                                    <?php if ($reason !== '') { ?>
                                        <p class="leave-reason"><strong>Reason</strong> · <?php echo nl2br(htmlspecialchars($reason)); ?></p>
                                    <?php } ?>
                                    <?php if ($attach !== '') { ?>
                                        <div class="leave-thumb">
                                            <a href="../uploads/<?php echo rawurlencode($attach); ?>" target="_blank" rel="noopener">
                                                <img src="../uploads/<?php echo htmlspecialchars($attach, ENT_QUOTES, 'UTF-8'); ?>" alt="Attachment">
                                            </a>
                                        </div>
                                    <?php } ?>
                                    <?php if ($st === 'Approved' && $comment !== '') { ?>
                                        <div class="leave-comment"><strong>Note</strong> · <?php echo nl2br(htmlspecialchars($comment)); ?></div>
                                    <?php } ?>
                                    <div class="leave-foot">
                                        <span class="material-icons">schedule</span>
                                        Applied <?php echo htmlspecialchars($applied); ?>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php } ?>
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
