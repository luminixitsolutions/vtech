<?php
session_start();
require_once 'config.php';
require_once 'auth.php';

$PageName = "My leave";
$UserId = (int) $_SESSION['User']['id'];
$year = isset($_GET['y']) ? (int) $_GET['y'] : (int) date('Y');
$month = isset($_GET['m']) ? (int) $_GET['m'] : (int) date('n');
if ($month < 1 || $month > 12) {
    $month = (int) date('n');
}
if ($year < 2000 || $year > 2100) {
    $year = (int) date('Y');
}

$rows = getList("SELECT * FROM tbl_leave_request WHERE UserId='$UserId' ORDER BY CreatedAt DESC, id DESC");

?>
<!doctype html>
<html lang="en" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo $Proj_Title; ?> — My leave requests</title>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <link rel="icon" href="img/favicon32.png" sizes="32x32" type="image/png">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&amp;family=Roboto:wght@300;400;500;600&amp;display=swap" rel="stylesheet">
    <link href="vendor/swiper/css/swiper.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet" id="style">
    <link href="css/toastr.min.css" rel="stylesheet">
    <script src="js/jquery.min3.5.1.js" type="text/javascript"></script>
    <script type="text/javascript" src="js/toastr.min.js"></script>
    <style>
        .lr-page {
            font-family: "DM Sans", "Roboto", system-ui, sans-serif;
            background: linear-gradient(165deg, #e8ecf4 0%, #f4f6fa 38%, #faf7f5 100%);
            min-height: 100%;
            padding-bottom: 2rem;
        }
        .lr-wrap { max-width: 560px; margin: 0 auto; }
        .lr-filter {
            background: #fff;
            border-radius: 16px;
            border: 1px solid rgba(0,0,0,0.06);
            padding: 1.05rem 1.1rem;
            box-shadow: 0 4px 20px rgba(17, 34, 68, 0.06);
        }
        .lr-filter-head {
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            color: #868e96;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.35rem;
        }
        .lr-filter-head .material-icons { font-size: 1rem; color: #adb5bd; }
        .lr-filter label { display: block; font-size: 0.68rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #868e96; margin-bottom: 0.35rem; }
        .lr-filter .form-control { border-radius: 12px; border-color: #e2e6ea; min-height: 40px; }
        .lr-btn-apply {
            background: linear-gradient(180deg, #234a6f 0%, #1a3a5c 100%);
            color: #fff;
            font-weight: 600;
            border: none;
            border-radius: 12px;
            padding: 0.55rem 1.25rem;
            box-shadow: 0 4px 14px rgba(26, 58, 92, 0.25);
        }
        .lr-btn-apply:hover { color: #fff; filter: brightness(1.06); }
        .lr-section-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: #212529;
            margin: 1.15rem 0 0.65rem;
            letter-spacing: -0.01em;
        }
        .lr-card {
            background: #fff;
            border-radius: 16px;
            border: 1px solid rgba(0,0,0,0.06);
            padding: 1rem 1.05rem 1rem 1rem;
            margin-bottom: 0.75rem;
            box-shadow: 0 4px 18px rgba(17, 34, 68, 0.06);
            border-left: 4px solid #dee2e6;
            transition: box-shadow 0.2s ease, transform 0.2s ease;
        }
        .lr-card:hover { box-shadow: 0 8px 28px rgba(17, 34, 68, 0.1); transform: translateY(-1px); }
        .lr-card.lr-card--ok { border-left-color: #43a047; }
        .lr-card.lr-card--no { border-left-color: #e53935; }
        .lr-card.lr-card--pending { border-left-color: #ffa000; }
        .lr-badge {
            display: inline-block;
            font-size: 0.62rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            padding: 0.28rem 0.65rem;
            border-radius: 999px;
        }
        .lr-badge--pending { background: linear-gradient(180deg, #fff8e1 0%, #ffecb3 100%); color: #795548; border: 1px solid rgba(255, 193, 7, 0.35); }
        .lr-badge--ok { background: linear-gradient(180deg, #e8f5e9 0%, #c8e6c9 100%); color: #1b5e20; border: 1px solid rgba(67, 160, 71, 0.35); }
        .lr-badge--no { background: linear-gradient(180deg, #ffebee 0%, #ffcdd2 100%); color: #b71c1c; border: 1px solid rgba(229, 57, 53, 0.3); }
        .lr-dates { font-weight: 700; font-size: 0.92rem; color: #1a237e; }
        .lr-dates .material-icons { font-size: 1.05rem; vertical-align: -4px; color: #5c6bc0; }
        .lr-days-pill {
            font-size: 0.7rem;
            font-weight: 600;
            color: #37474f;
            background: linear-gradient(180deg, #eceff1 0%, #e0e0e0 100%);
            padding: 0.2rem 0.55rem;
            border-radius: 999px;
            border: 1px solid rgba(0,0,0,0.06);
        }
        .lr-reason {
            font-size: 0.84rem;
            color: #424242;
            line-height: 1.5;
            margin-top: 0.55rem;
            padding: 0.55rem 0.65rem;
            background: #f8f9fb;
            border-radius: 10px;
            border: 1px solid #eee;
        }
        .lr-meta { font-size: 0.7rem; color: #9e9e9e; margin-top: 0.55rem; font-weight: 500; }
        .lr-meta .material-icons { font-size: 0.95rem; vertical-align: -3px; margin-right: 3px; color: #bdbdbd; }
        .lr-empty {
            text-align: center;
            padding: 2rem 1.25rem 1.75rem;
            color: #868e96;
            background: #fff;
            border-radius: 16px;
            border: 2px dashed #dee2e6;
            box-shadow: none;
        }
        .lr-empty .material-icons { font-size: 3.25rem; color: #cfd8dc; margin-bottom: 0.65rem; display: block; margin-left: auto; margin-right: auto; }
        .lr-cta {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            width: 100%;
            background: linear-gradient(135deg, #e74623 0%, #c62828 100%);
            color: #fff !important;
            font-weight: 600;
            font-size: 0.95rem;
            border: none;
            border-radius: 14px;
            padding: 0.95rem 1rem;
            box-shadow: 0 8px 24px rgba(231, 70, 35, 0.38);
        }
        .lr-cta:hover, .lr-cta:focus { color: #fff !important; filter: brightness(1.06); text-decoration: none; }
        .lr-cta .material-icons { font-size: 1.3rem; }
    </style>
</head>
<body class="body-scroll d-flex flex-column h-100 menu-overlay">
    <main class="flex-shrink-0 main">
        <?php include_once 'back-header.php'; ?>
        <div class="main-container lr-page">
            <div class="container lr-wrap">

                <form class="lr-filter mb-3 pt-3" method="get" action="">
                    <div class="lr-filter-head">
                        <span class="material-icons">tune</span> Filter period
                    </div>
                    <div class="row no-gutters" style="margin: 0 -6px;">
                        <div class="col-7 col-sm-8" style="padding: 0 6px;">
                            <label for="lr_m">Month</label>
                            <select name="m" id="lr_m" class="form-control form-control-sm" onchange="this.form.submit();">
                                <?php for ($i = 1; $i <= 12; $i++) { ?>
                                <option value="<?php echo $i; ?>" <?php echo $month === $i ? 'selected' : ''; ?>><?php echo date('F', mktime(0,0,0,$i,1)); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-5 col-sm-4" style="padding: 0 6px;">
                            <label for="lr_y">Year</label>
                            <input type="number" name="y" id="lr_y" class="form-control form-control-sm" value="<?php echo (int) $year; ?>" min="2000" max="2100" />
                        </div>
                    </div>
                    <button type="submit" class="btn btn-sm lr-btn-apply btn-block mt-2">Apply period</button>
                </form>

                <h2 class="lr-section-title">Request history</h2>

                <?php if (empty($rows)) { ?>
                <div class="lr-card lr-empty">
                    <span class="material-icons" aria-hidden="true">inbox</span>
                    <p class="mb-0 small" style="max-width: 280px; margin: 0 auto; line-height: 1.5;">You have not raised any leave requests yet. When you do, they will appear here with status.</p>
                </div>
                <?php } else { foreach ($rows as $lr) {
                    $cardMod = 'lr-card--pending';
                    $badgeClass = 'lr-badge--pending';
                    if ($lr['Status'] === 'Approved') {
                        $cardMod = 'lr-card--ok';
                        $badgeClass = 'lr-badge--ok';
                    } elseif ($lr['Status'] === 'Rejected') {
                        $cardMod = 'lr-card--no';
                        $badgeClass = 'lr-badge--no';
                    }
                ?>
                <div class="lr-card <?php echo $cardMod; ?>">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="pr-2">
                            <span class="lr-dates d-inline-flex align-items-center flex-wrap" style="gap: 0.35rem;">
                                <span class="material-icons">event</span>
                                <span><?php echo date('d M Y', strtotime($lr['FromDate'])); ?></span>
                                <?php if ($lr['ToDate'] != $lr['FromDate']) { ?>
                                <span class="text-muted" style="font-weight:600; font-size:0.78rem;">to</span>
                                <span><?php echo date('d M Y', strtotime($lr['ToDate'])); ?></span>
                                <?php } ?>
                            </span>
                            <span class="lr-days-pill ml-0 mt-1 d-inline-block"><?php echo (int) $lr['LeaveDays']; ?> day<?php echo (int) $lr['LeaveDays'] !== 1 ? 's' : ''; ?></span>
                        </div>
                        <span class="lr-badge <?php echo $badgeClass; ?>"><?php echo htmlspecialchars($lr['Status']); ?></span>
                    </div>
                    <?php if (!empty($lr['Reason'])) { ?>
                    <p class="lr-reason mb-0"><?php echo nl2br(htmlspecialchars($lr['Reason'])); ?></p>
                    <?php } ?>
                    <div class="lr-meta">
                        <span class="material-icons">schedule</span>
                        Submitted <?php
                        $ca = $lr['CreatedAt'] ?? '';
                        echo $ca !== '' ? date('d M Y, h:i a', strtotime($ca)) : '';
                        ?>
                    </div>
                </div>
                <?php } } ?>

                <a href="leave-request.php" class="lr-cta text-decoration-none mt-3 mb-1">
                    <span class="material-icons" aria-hidden="true">add_circle_outline</span>
                    Apply for leave
                </a>
            </div>
        </div>
    </main>
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>
