<?php
session_start();
require_once 'config.php';
require_once 'auth.php';
require_once 'inc-mobile-mgmt.php';

$type = isset($_GET['type']) ? trim($_GET['type']) : 'po';
$today = mobileMgmtToday();

$labels = array(
    'po' => 'Purchase Orders',
    'po_today' => 'Today Purchase Orders',
    'dc' => 'Delivery Challan',
    'dc_today' => 'Today Delivery Challan',
    'quotation' => 'Quotations',
    'quotation_today' => 'Today Quotations',
    'work_order' => 'Work Orders',
    'dispatched' => 'Dispatched Sites',
);

$title = isset($labels[$type]) ? $labels[$type] : 'Stock List';
$rows = array();

switch ($type) {
    case 'po_today':
        $sql = "SELECT ts.id, ts.InvoiceNo, ts.InvoiceDate, ts.NetAmount, tu.Fname AS CompanyName
            FROM tbl_purchase_order ts
            LEFT JOIN tbl_users tu ON ts.CompId = tu.id
            WHERE ts.Status=1 AND ts.InvoiceDate='$today'
            ORDER BY ts.id DESC LIMIT 200";
        break;
    case 'po':
        $sql = "SELECT ts.id, ts.InvoiceNo, ts.InvoiceDate, ts.NetAmount, tu.Fname AS CompanyName
            FROM tbl_purchase_order ts
            LEFT JOIN tbl_users tu ON ts.CompId = tu.id
            WHERE ts.Status=1
            ORDER BY ts.id DESC LIMIT 200";
        break;
    case 'dc_today':
        $sql = "SELECT ts.id, ts.InvoiceNo, ts.InvoiceDate, ts.CustName, ts.CellNo, tb.Name AS BranchName
            FROM tbl_sell ts
            LEFT JOIN tbl_branch tb ON ts.BranchId = tb.id
            WHERE ts.Status=1 AND ts.InvoiceDate='$today'
            ORDER BY ts.id DESC LIMIT 200";
        break;
    case 'dc':
        $sql = "SELECT ts.id, ts.InvoiceNo, ts.InvoiceDate, ts.CustName, ts.CellNo, tb.Name AS BranchName
            FROM tbl_sell ts
            LEFT JOIN tbl_branch tb ON ts.BranchId = tb.id
            WHERE ts.Status=1
            ORDER BY ts.id DESC LIMIT 200";
        break;
    case 'quotation_today':
        $sql = "SELECT tp.id, tp.InvoiceNo, tp.InvoiceDate, tp.CustName, tp.CellNo, tp.NetAmount
            FROM tbl_quotation tp
            WHERE tp.InvoiceDate='$today'
            ORDER BY tp.id DESC LIMIT 200";
        break;
    case 'quotation':
        $sql = "SELECT tp.id, tp.InvoiceNo, tp.InvoiceDate, tp.CustName, tp.CellNo, tp.NetAmount
            FROM tbl_quotation tp
            ORDER BY tp.id DESC LIMIT 200";
        break;
    case 'work_order':
        $sql = "SELECT tw.id, tw.RefEnqNo, tw.InvoiceDate, tw.CustName, tw.CellNo, tw.Status
            FROM tbl_work_order tw
            ORDER BY tw.id DESC LIMIT 200";
        break;
    case 'dispatched':
        $sql = "SELECT ts.id, ts.InvoiceNo, ts.InvoiceDate, ts.CustName, ts.CellNo, ts.Inst_Dispatcher_Date
            FROM tbl_sell ts
            WHERE ts.Status=1 AND ts.Inst_Dispatcher_Otp_Verify=1
            ORDER BY ts.id DESC LIMIT 200";
        break;
    default:
        $sql = '';
}

if ($sql !== '') {
    $list = getList($sql);
    if (is_array($list)) {
        $rows = $list;
    }
}
?>
<!doctype html>
<html lang="en" class="h-100">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $Proj_Title; ?> | <?php echo htmlspecialchars($title); ?></title>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<link href="css/mobile-mgmt.css" rel="stylesheet">
</head>
<body class="mob-mgmt-page">
<?php include_once __DIR__ . '/inc-app-loader.php'; ?>

<div class="mob-mgmt-topbar">
    <a href="stock-management.php"><span class="material-icons">arrow_back</span></a>
    <h1><?php echo htmlspecialchars($title); ?></h1>
    <span class="badge bg-primary"><?php echo count($rows); ?></span>
</div>

<div class="mob-mgmt-list-wrap">
<?php if (empty($rows)) { ?>
    <div class="mob-mgmt-empty">No records found.</div>
<?php } else {
    foreach ($rows as $row) {
        $cardTitle = '';
        if (!empty($row['CustName'])) {
            $cardTitle = $row['CustName'];
        } elseif (!empty($row['CompanyName'])) {
            $cardTitle = $row['CompanyName'];
        } else {
            $cardTitle = 'Record #' . (int) $row['id'];
        }
        ?>
    <div class="mob-mgmt-card">
        <div class="mob-mgmt-card-title"><?php echo htmlspecialchars($cardTitle); ?></div>
        <?php if (!empty($row['InvoiceNo'])) { ?>
        <div class="mob-mgmt-card-row"><span>No</span><span><?php echo htmlspecialchars($row['InvoiceNo']); ?></span></div>
        <?php } elseif (!empty($row['RefEnqNo'])) { ?>
        <div class="mob-mgmt-card-row"><span>Ref No</span><span><?php echo htmlspecialchars($row['RefEnqNo']); ?></span></div>
        <?php } ?>
        <?php if (isset($row['Status'])) { ?>
        <div class="mob-mgmt-card-row"><span>Status</span><span><?php echo ((int) $row['Status'] === 1) ? 'Approved' : 'Pending'; ?></span></div>
        <?php } ?>
        <?php if (!empty($row['CellNo'])) { ?>
        <div class="mob-mgmt-card-row"><span>Contact</span><span><?php echo htmlspecialchars($row['CellNo']); ?></span></div>
        <?php } ?>
        <?php if (!empty($row['BranchName'])) { ?>
        <div class="mob-mgmt-card-row"><span>Store</span><span><?php echo htmlspecialchars($row['BranchName']); ?></span></div>
        <?php } ?>
        <?php if (!empty($row['InvoiceDate'])) { ?>
        <div class="mob-mgmt-card-row"><span>Date</span><span><?php echo mobileMgmtFormatDate($row['InvoiceDate']); ?></span></div>
        <?php } ?>
        <?php if (!empty($row['Inst_Dispatcher_Date'])) { ?>
        <div class="mob-mgmt-card-row"><span>Dispatch Date</span><span><?php echo mobileMgmtFormatDate($row['Inst_Dispatcher_Date']); ?></span></div>
        <?php } ?>
        <?php if (isset($row['NetAmount']) && $row['NetAmount'] !== '') { ?>
        <div class="mob-mgmt-card-row"><span>Amount</span><span><?php echo htmlspecialchars($row['NetAmount']); ?></span></div>
        <?php } ?>
    </div>
        <?php
    }
} ?>
</div>

</body>
</html>
