<?php 
session_start();
include_once '../config.php';
include_once '../auth.php';

$baseCondition = "ts.Status=1 AND ts.ProjectType=1 AND ts.ProjectId=106";

$totalCustomers = getRow("SELECT * FROM tbl_users ts WHERE $baseCondition");
$pendingSelection = getRow("SELECT * FROM tbl_users ts WHERE $baseCondition AND ts.MpSelectionStatus=0");
$approvedSelection = getRow("SELECT * FROM tbl_users ts WHERE $baseCondition AND ts.MpSelectionStatus=1");
$rejectedSelection = getRow("SELECT * FROM tbl_users ts WHERE $baseCondition AND ts.MpSelectionStatus=2");

$pendingPayments = getRow("SELECT ts.* FROM tbl_users ts WHERE $baseCondition AND ts.MpSelectionStatus=1 AND ts.id NOT IN (SELECT customer_id FROM tbl_customer_payments)");
$partialPayments = getRow("SELECT ts.* FROM tbl_users ts 
    LEFT JOIN tbl_common_master tcm ON tcm.id=ts.PumpCapacity 
    WHERE $baseCondition AND ts.MpSelectionStatus=1 
    AND ts.id IN (SELECT customer_id FROM tbl_customer_payments) 
    AND (SELECT IFNULL(SUM(credit),0) FROM tbl_customer_payment_ledger WHERE customer_id=ts.id) < tcm.Amount");
$fullPayments = getRow("SELECT ts.* FROM tbl_users ts 
    LEFT JOIN tbl_common_master tcm ON tcm.id=ts.PumpCapacity 
    WHERE $baseCondition AND ts.MpSelectionStatus=1 
    AND ts.id IN (SELECT customer_id FROM tbl_customer_payments) 
    AND (SELECT IFNULL(SUM(credit),0) FROM tbl_customer_payment_ledger WHERE customer_id=ts.id) >= tcm.Amount");

$pendingLoa = getRow("SELECT * FROM tbl_users ts WHERE $baseCondition AND ts.MpSelectionStatus=1 AND (ts.LoaReceived IS NULL OR ts.LoaReceived = '' OR ts.LoaReceived = 'no')");
$receivedLoa = getRow("SELECT * FROM tbl_users ts WHERE $baseCondition AND ts.MpSelectionStatus=1 AND ts.LoaReceived = 'yes'");

$pendingNtp = getRow("SELECT * FROM tbl_users ts WHERE $baseCondition AND ts.MpSelectionStatus=1 AND ts.LoaReceived = 'yes' AND (ts.NtpComplete IS NULL OR ts.NtpComplete = '' OR ts.NtpComplete = 'no')");
$completeNtp = getRow("SELECT * FROM tbl_users ts WHERE $baseCondition AND ts.MpSelectionStatus=1 AND ts.NtpComplete = 'yes'");

$pendingAif = getRow("SELECT * FROM tbl_users ts WHERE $baseCondition AND ts.MpSelectionStatus=1 AND ts.NtpComplete = 'yes' AND (ts.AifComplete IS NULL OR ts.AifComplete = '' OR ts.AifComplete = 'no')");
$completeAif = getRow("SELECT * FROM tbl_users ts WHERE $baseCondition AND ts.MpSelectionStatus=1 AND ts.AifComplete = 'yes'");

$pendingCif = getRow("SELECT * FROM tbl_users ts WHERE $baseCondition AND ts.MpSelectionStatus=1 AND ts.AifComplete = 'yes' AND (ts.CifComplete IS NULL OR ts.CifComplete = '' OR ts.CifComplete = 'no')");
$completeCif = getRow("SELECT * FROM tbl_users ts WHERE $baseCondition AND ts.MpSelectionStatus=1 AND ts.CifComplete = 'yes'");

$totalPaymentReceived = 0;
$totalPaymentPending = 0;
$paymentResult = $conn->query("SELECT IFNULL(SUM(l.credit),0) AS TotalPaid, IFNULL(SUM(tcm.Amount),0) AS TotalAmount 
    FROM tbl_users ts 
    LEFT JOIN tbl_common_master tcm ON tcm.id=ts.PumpCapacity 
    LEFT JOIN tbl_customer_payment_ledger l ON l.customer_id=ts.id 
    WHERE $baseCondition AND ts.MpSelectionStatus=1");
if($paymentRow = $paymentResult->fetch_assoc()) {
    $totalPaymentReceived = $paymentRow['TotalPaid'];
    $totalPaymentPending = $paymentRow['TotalAmount'] - $paymentRow['TotalPaid'];
}
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
    <title><?php echo $Proj_Title;?> - MPUVNL Dashboard</title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="" />
    <link rel="icon" type="image/x-icon" href="<?php echo $SiteUrl;?>/assets/img/favicon.ico">

    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/linearicons.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/feather.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/css/bootstrap-material.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/css/shreerang-material.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/css/uikit.css">

    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/libs/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/libs/flot/flot.css">
    
    <style>
        .dashboard-card {
            border-radius: 10px;
            transition: transform 0.2s, box-shadow 0.2s;
            border: none;
            overflow: hidden;
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        .card-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .bg-gradient-success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }
        .bg-gradient-warning {
            background: linear-gradient(135deg, #F2994A 0%, #F2C94C 100%);
        }
        .bg-gradient-danger {
            background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
        }
        .bg-gradient-info {
            background: linear-gradient(135deg, #2193b0 0%, #6dd5ed 100%);
        }
        .bg-gradient-secondary {
            background: linear-gradient(135deg, #636363 0%, #a2ab58 100%);
        }
        .bg-gradient-dark {
            background: linear-gradient(135deg, #232526 0%, #414345 100%);
        }
        .bg-gradient-purple {
            background: linear-gradient(135deg, #7F00FF 0%, #E100FF 100%);
        }
        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #667eea;
            display: inline-block;
        }
        .section-icon {
            margin-right: 10px;
            color: #667eea;
        }
        .stats-number {
            font-size: 32px;
            font-weight: 700;
            color: #333;
        }
        .stats-label {
            font-size: 14px;
            color: #666;
            font-weight: 500;
        }
        .progress-wrapper {
            margin-top: 15px;
        }
        .progress {
            height: 8px;
            border-radius: 4px;
            background-color: #e9ecef;
        }
        .summary-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 25px;
        }
        .summary-card h3 {
            color: white;
            font-weight: 600;
        }
        .summary-card .amount {
            font-size: 28px;
            font-weight: 700;
        }
        .workflow-step {
            position: relative;
            padding-left: 30px;
        }
        .workflow-step::before {
            content: '';
            position: absolute;
            left: 10px;
            top: 0;
            bottom: -20px;
            width: 2px;
            background: #e0e0e0;
        }
        .workflow-step:last-child::before {
            display: none;
        }
        .workflow-step .step-number {
            position: absolute;
            left: 0;
            top: 0;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: #667eea;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div class="page-loader">
        <div class="bg-primary"></div>
    </div>
    
    <div class="layout-wrapper layout-2">
        <div class="layout-inner">

            <?php include_once 'mp-sidebar.php'; ?>

            <div class="layout-container">

                <?php include_once '../top_header.php'; ?>
                
                <div class="layout-content">
                    <div class="container-fluid flex-grow-1 container-p-y">
                        
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h4 class="font-weight-bold mb-1">MPUVNL Dashboard</h4>
                                <p class="text-muted mb-0">Overview of all MPUVNL project activities</p>
                            </div>
                            <div class="text-right">
                                <span class="text-muted">Last Updated: <?php echo date('d M Y, h:i A'); ?></span>
                            </div>
                        </div>

                        <!-- Summary Cards Row -->
                        <div class="row mb-4">
                            <div class="col-xl-3 col-md-6 mb-3">
                                <div class="card dashboard-card h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                    <div class="card-body text-white">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <p class="mb-1" style="opacity: 0.8;">Total Customers</p>
                                                <h2 class="mb-0 text-white"><?php echo $totalCustomers; ?></h2>
                                            </div>
                                            <div class="card-icon" style="background: rgba(255,255,255,0.2);">
                                                <i class="fas fa-users text-white" style="font-size: 24px;"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6 mb-3">
                                <div class="card dashboard-card h-100" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                                    <div class="card-body text-white">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <p class="mb-1" style="opacity: 0.8;">Payment Received</p>
                                                <h2 class="mb-0 text-white">₹<?php echo number_format($totalPaymentReceived, 0); ?></h2>
                                            </div>
                                            <div class="card-icon" style="background: rgba(255,255,255,0.2);">
                                                <i class="fas fa-check-circle text-white" style="font-size: 24px;"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6 mb-3">
                                <div class="card dashboard-card h-100" style="background: linear-gradient(135deg, #F2994A 0%, #F2C94C 100%);">
                                    <div class="card-body text-white">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <p class="mb-1" style="opacity: 0.8;">Payment Pending</p>
                                                <h2 class="mb-0 text-white">₹<?php echo number_format($totalPaymentPending, 0); ?></h2>
                                            </div>
                                            <div class="card-icon" style="background: rgba(255,255,255,0.2);">
                                                <i class="fas fa-clock text-white" style="font-size: 24px;"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6 mb-3">
                                <div class="card dashboard-card h-100" style="background: linear-gradient(135deg, #7F00FF 0%, #E100FF 100%);">
                                    <div class="card-body text-white">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <p class="mb-1" style="opacity: 0.8;">Completed (CIF)</p>
                                                <h2 class="mb-0 text-white"><?php echo $completeCif; ?></h2>
                                            </div>
                                            <div class="card-icon" style="background: rgba(255,255,255,0.2);">
                                                <i class="fas fa-award text-white" style="font-size: 24px;"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Customer Selection Section -->
                        <div class="mb-4">
                            <h5 class="section-title"><i class="fas fa-user-check section-icon"></i>Customer Selection</h5>
                            <div class="row">
                                <div class="col-xl-3 col-md-6 mb-3">
                                    <a href="pending-selection.php" class="text-decoration-none">
                                        <div class="card dashboard-card h-100">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="card-icon bg-gradient-warning text-white mr-3">
                                                        <i class="fas fa-clock"></i>
                                                    </div>
                                                    <div>
                                                        <p class="stats-label mb-1">Pending Selection</p>
                                                        <h3 class="stats-number mb-0"><?php echo $pendingSelection; ?></h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-xl-3 col-md-6 mb-3">
                                    <a href="approve-selection.php" class="text-decoration-none">
                                        <div class="card dashboard-card h-100">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="card-icon bg-gradient-success text-white mr-3">
                                                        <i class="fas fa-check"></i>
                                                    </div>
                                                    <div>
                                                        <p class="stats-label mb-1">Approved Selection</p>
                                                        <h3 class="stats-number mb-0"><?php echo $approvedSelection; ?></h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-xl-3 col-md-6 mb-3">
                                    <a href="reject-selection.php" class="text-decoration-none">
                                        <div class="card dashboard-card h-100">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="card-icon bg-gradient-danger text-white mr-3">
                                                        <i class="fas fa-times"></i>
                                                    </div>
                                                    <div>
                                                        <p class="stats-label mb-1">Rejected Selection</p>
                                                        <h3 class="stats-number mb-0"><?php echo $rejectedSelection; ?></h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-xl-3 col-md-6 mb-3">
                                    <div class="card dashboard-card h-100">
                                        <div class="card-body">
                                            <p class="stats-label mb-2">Selection Progress</p>
                                            <div class="progress-wrapper">
                                                <?php 
                                                $selectionPercent = $totalCustomers > 0 ? round(($approvedSelection / $totalCustomers) * 100) : 0;
                                                ?>
                                                <div class="d-flex justify-content-between mb-1">
                                                    <span class="text-success"><?php echo $selectionPercent; ?>% Approved</span>
                                                </div>
                                                <div class="progress">
                                                    <div class="progress-bar bg-success" style="width: <?php echo $selectionPercent; ?>%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Customer Payments Section -->
                        <div class="mb-4">
                            <h5 class="section-title"><i class="fas fa-credit-card section-icon"></i>Customer Payments</h5>
                            <div class="row">
                                <div class="col-xl-3 col-md-6 mb-3">
                                    <a href="pending-mp-payments.php" class="text-decoration-none">
                                        <div class="card dashboard-card h-100">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="card-icon bg-gradient-danger text-white mr-3">
                                                        <i class="fas fa-exclamation-circle"></i>
                                                    </div>
                                                    <div>
                                                        <p class="stats-label mb-1">Pending Payments</p>
                                                        <h3 class="stats-number mb-0"><?php echo $pendingPayments; ?></h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-xl-3 col-md-6 mb-3">
                                    <a href="partial-mp-payments.php" class="text-decoration-none">
                                        <div class="card dashboard-card h-100">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="card-icon bg-gradient-warning text-white mr-3">
                                                        <i class="fas fa-chart-pie"></i>
                                                    </div>
                                                    <div>
                                                        <p class="stats-label mb-1">Partial Payments</p>
                                                        <h3 class="stats-number mb-0"><?php echo $partialPayments; ?></h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-xl-3 col-md-6 mb-3">
                                    <a href="full-mp-payments.php" class="text-decoration-none">
                                        <div class="card dashboard-card h-100">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="card-icon bg-gradient-success text-white mr-3">
                                                        <i class="fas fa-check-circle"></i>
                                                    </div>
                                                    <div>
                                                        <p class="stats-label mb-1">Full Payments</p>
                                                        <h3 class="stats-number mb-0"><?php echo $fullPayments; ?></h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-xl-3 col-md-6 mb-3">
                                    <div class="card dashboard-card h-100">
                                        <div class="card-body">
                                            <p class="stats-label mb-2">Payment Progress</p>
                                            <div class="progress-wrapper">
                                                <?php 
                                                $totalPaymentCustomers = $pendingPayments + $partialPayments + $fullPayments;
                                                $paymentPercent = $totalPaymentCustomers > 0 ? round(($fullPayments / $totalPaymentCustomers) * 100) : 0;
                                                ?>
                                                <div class="d-flex justify-content-between mb-1">
                                                    <span class="text-success"><?php echo $paymentPercent; ?>% Complete</span>
                                                </div>
                                                <div class="progress">
                                                    <div class="progress-bar bg-success" style="width: <?php echo $paymentPercent; ?>%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- LOA Section -->
                        <div class="mb-4">
                            <h5 class="section-title"><i class="fas fa-file-alt section-icon"></i>LOA (Letter of Award)</h5>
                            <div class="row">
                                <div class="col-xl-4 col-md-6 mb-3">
                                    <a href="pending-loa.php" class="text-decoration-none">
                                        <div class="card dashboard-card h-100">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="card-icon bg-gradient-warning text-white mr-3">
                                                        <i class="fas fa-clock"></i>
                                                    </div>
                                                    <div>
                                                        <p class="stats-label mb-1">Pending LOA</p>
                                                        <h3 class="stats-number mb-0"><?php echo $pendingLoa; ?></h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-xl-4 col-md-6 mb-3">
                                    <a href="received-loa.php" class="text-decoration-none">
                                        <div class="card dashboard-card h-100">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="card-icon bg-gradient-success text-white mr-3">
                                                        <i class="fas fa-check-circle"></i>
                                                    </div>
                                                    <div>
                                                        <p class="stats-label mb-1">Received LOA</p>
                                                        <h3 class="stats-number mb-0"><?php echo $receivedLoa; ?></h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-xl-4 col-md-6 mb-3">
                                    <div class="card dashboard-card h-100">
                                        <div class="card-body">
                                            <p class="stats-label mb-2">LOA Progress</p>
                                            <div class="progress-wrapper">
                                                <?php 
                                                $totalLoa = $pendingLoa + $receivedLoa;
                                                $loaPercent = $totalLoa > 0 ? round(($receivedLoa / $totalLoa) * 100) : 0;
                                                ?>
                                                <div class="d-flex justify-content-between mb-1">
                                                    <span class="text-success"><?php echo $loaPercent; ?>% Received</span>
                                                </div>
                                                <div class="progress">
                                                    <div class="progress-bar bg-success" style="width: <?php echo $loaPercent; ?>%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- NTP Order Section -->
                        <div class="mb-4">
                            <h5 class="section-title"><i class="fas fa-clipboard-list section-icon"></i>NTP (Notice to Proceed)</h5>
                            <div class="row">
                                <div class="col-xl-4 col-md-6 mb-3">
                                    <a href="pending-ntp-order.php" class="text-decoration-none">
                                        <div class="card dashboard-card h-100">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="card-icon bg-gradient-warning text-white mr-3">
                                                        <i class="fas fa-clock"></i>
                                                    </div>
                                                    <div>
                                                        <p class="stats-label mb-1">Pending NTP</p>
                                                        <h3 class="stats-number mb-0"><?php echo $pendingNtp; ?></h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-xl-4 col-md-6 mb-3">
                                    <a href="complete-ntp-order.php" class="text-decoration-none">
                                        <div class="card dashboard-card h-100">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="card-icon bg-gradient-success text-white mr-3">
                                                        <i class="fas fa-check-circle"></i>
                                                    </div>
                                                    <div>
                                                        <p class="stats-label mb-1">Complete NTP</p>
                                                        <h3 class="stats-number mb-0"><?php echo $completeNtp; ?></h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-xl-4 col-md-6 mb-3">
                                    <div class="card dashboard-card h-100">
                                        <div class="card-body">
                                            <p class="stats-label mb-2">NTP Progress</p>
                                            <div class="progress-wrapper">
                                                <?php 
                                                $totalNtp = $pendingNtp + $completeNtp;
                                                $ntpPercent = $totalNtp > 0 ? round(($completeNtp / $totalNtp) * 100) : 0;
                                                ?>
                                                <div class="d-flex justify-content-between mb-1">
                                                    <span class="text-success"><?php echo $ntpPercent; ?>% Complete</span>
                                                </div>
                                                <div class="progress">
                                                    <div class="progress-bar bg-success" style="width: <?php echo $ntpPercent; ?>%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- AIF Section -->
                        <div class="mb-4">
                            <h5 class="section-title"><i class="fas fa-folder-open section-icon"></i>AIF (Approval in Finance)</h5>
                            <div class="row">
                                <div class="col-xl-4 col-md-6 mb-3">
                                    <a href="pending-aif.php" class="text-decoration-none">
                                        <div class="card dashboard-card h-100">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="card-icon bg-gradient-warning text-white mr-3">
                                                        <i class="fas fa-clock"></i>
                                                    </div>
                                                    <div>
                                                        <p class="stats-label mb-1">Pending AIF</p>
                                                        <h3 class="stats-number mb-0"><?php echo $pendingAif; ?></h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-xl-4 col-md-6 mb-3">
                                    <a href="complete-aif.php" class="text-decoration-none">
                                        <div class="card dashboard-card h-100">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="card-icon bg-gradient-success text-white mr-3">
                                                        <i class="fas fa-check-circle"></i>
                                                    </div>
                                                    <div>
                                                        <p class="stats-label mb-1">Complete AIF</p>
                                                        <h3 class="stats-number mb-0"><?php echo $completeAif; ?></h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-xl-4 col-md-6 mb-3">
                                    <div class="card dashboard-card h-100">
                                        <div class="card-body">
                                            <p class="stats-label mb-2">AIF Progress</p>
                                            <div class="progress-wrapper">
                                                <?php 
                                                $totalAif = $pendingAif + $completeAif;
                                                $aifPercent = $totalAif > 0 ? round(($completeAif / $totalAif) * 100) : 0;
                                                ?>
                                                <div class="d-flex justify-content-between mb-1">
                                                    <span class="text-success"><?php echo $aifPercent; ?>% Complete</span>
                                                </div>
                                                <div class="progress">
                                                    <div class="progress-bar bg-success" style="width: <?php echo $aifPercent; ?>%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- CIF Section -->
                        <div class="mb-4">
                            <h5 class="section-title"><i class="fas fa-award section-icon"></i>CIF (Commissioning in Finance)</h5>
                            <div class="row">
                                <div class="col-xl-4 col-md-6 mb-3">
                                    <a href="pending-cif.php" class="text-decoration-none">
                                        <div class="card dashboard-card h-100">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="card-icon bg-gradient-warning text-white mr-3">
                                                        <i class="fas fa-clock"></i>
                                                    </div>
                                                    <div>
                                                        <p class="stats-label mb-1">Pending CIF</p>
                                                        <h3 class="stats-number mb-0"><?php echo $pendingCif; ?></h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-xl-4 col-md-6 mb-3">
                                    <a href="complete-cif.php" class="text-decoration-none">
                                        <div class="card dashboard-card h-100">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="card-icon bg-gradient-success text-white mr-3">
                                                        <i class="fas fa-check-circle"></i>
                                                    </div>
                                                    <div>
                                                        <p class="stats-label mb-1">Complete CIF</p>
                                                        <h3 class="stats-number mb-0"><?php echo $completeCif; ?></h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-xl-4 col-md-6 mb-3">
                                    <div class="card dashboard-card h-100">
                                        <div class="card-body">
                                            <p class="stats-label mb-2">CIF Progress</p>
                                            <div class="progress-wrapper">
                                                <?php 
                                                $totalCif = $pendingCif + $completeCif;
                                                $cifPercent = $totalCif > 0 ? round(($completeCif / $totalCif) * 100) : 0;
                                                ?>
                                                <div class="d-flex justify-content-between mb-1">
                                                    <span class="text-success"><?php echo $cifPercent; ?>% Complete</span>
                                                </div>
                                                <div class="progress">
                                                    <div class="progress-bar bg-success" style="width: <?php echo $cifPercent; ?>%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Overall Workflow Progress -->
                        <div class="mb-4">
                            <h5 class="section-title"><i class="fas fa-chart-line section-icon"></i>Overall Workflow Progress</h5>
                            <div class="card dashboard-card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-2 text-center mb-3">
                                            <div class="mb-2">
                                                <span class="badge badge-pill badge-primary" style="font-size: 14px; padding: 10px 20px;">Step 1</span>
                                            </div>
                                            <h5 class="mb-1"><?php echo $approvedSelection; ?></h5>
                                            <small class="text-muted">Selection</small>
                                        </div>
                                        <div class="col-md-2 text-center mb-3">
                                            <div class="mb-2">
                                                <span class="badge badge-pill badge-info" style="font-size: 14px; padding: 10px 20px;">Step 2</span>
                                            </div>
                                            <h5 class="mb-1"><?php echo $fullPayments; ?></h5>
                                            <small class="text-muted">Payment</small>
                                        </div>
                                        <div class="col-md-2 text-center mb-3">
                                            <div class="mb-2">
                                                <span class="badge badge-pill badge-warning" style="font-size: 14px; padding: 10px 20px;">Step 3</span>
                                            </div>
                                            <h5 class="mb-1"><?php echo $receivedLoa; ?></h5>
                                            <small class="text-muted">LOA</small>
                                        </div>
                                        <div class="col-md-2 text-center mb-3">
                                            <div class="mb-2">
                                                <span class="badge badge-pill badge-secondary" style="font-size: 14px; padding: 10px 20px;">Step 4</span>
                                            </div>
                                            <h5 class="mb-1"><?php echo $completeNtp; ?></h5>
                                            <small class="text-muted">NTP</small>
                                        </div>
                                        <div class="col-md-2 text-center mb-3">
                                            <div class="mb-2">
                                                <span class="badge badge-pill badge-dark" style="font-size: 14px; padding: 10px 20px;">Step 5</span>
                                            </div>
                                            <h5 class="mb-1"><?php echo $completeAif; ?></h5>
                                            <small class="text-muted">AIF</small>
                                        </div>
                                        <div class="col-md-2 text-center mb-3">
                                            <div class="mb-2">
                                                <span class="badge badge-pill badge-success" style="font-size: 14px; padding: 10px 20px;">Step 6</span>
                                            </div>
                                            <h5 class="mb-1"><?php echo $completeCif; ?></h5>
                                            <small class="text-muted">CIF</small>
                                        </div>
                                    </div>
                                    <div class="progress mt-3" style="height: 20px;">
                                        <?php
                                        $step1 = $totalCustomers > 0 ? ($approvedSelection / $totalCustomers) * 16.66 : 0;
                                        $step2 = $approvedSelection > 0 ? ($fullPayments / $approvedSelection) * 16.66 : 0;
                                        $step3 = $approvedSelection > 0 ? ($receivedLoa / $approvedSelection) * 16.66 : 0;
                                        $step4 = $receivedLoa > 0 ? ($completeNtp / $receivedLoa) * 16.66 : 0;
                                        $step5 = $completeNtp > 0 ? ($completeAif / $completeNtp) * 16.66 : 0;
                                        $step6 = $completeAif > 0 ? ($completeCif / $completeAif) * 16.66 : 0;
                                        ?>
                                        <div class="progress-bar bg-primary" style="width: <?php echo $step1; ?>%"></div>
                                        <div class="progress-bar bg-info" style="width: <?php echo $step2; ?>%"></div>
                                        <div class="progress-bar bg-warning" style="width: <?php echo $step3; ?>%"></div>
                                        <div class="progress-bar bg-secondary" style="width: <?php echo $step4; ?>%"></div>
                                        <div class="progress-bar bg-dark" style="width: <?php echo $step5; ?>%"></div>
                                        <div class="progress-bar bg-success" style="width: <?php echo $step6; ?>%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="<?php echo $SiteUrl;?>/assets/js/pace.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/jquery-3.3.1.min.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/libs/popper/popper.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/bootstrap.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/sidenav.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/layout-helpers.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/material-ripple.js"></script>

    <script src="<?php echo $SiteUrl;?>/assets/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    
    <script src="<?php echo $SiteUrl;?>/assets/js/demo.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/analytics.js"></script>
</body>

</html>
