<?php
session_start();
$sessionid = session_id();
require_once 'config.php';
require_once 'auth.php';
require_once 'inc-mobile-mgmt.php';
$PageName = "Home";

$uid = $_REQUEST['uid'];    
if($_REQUEST['uid'] == ''){
  $uid = $_SESSION['User']['id'];
}else{
  $uid = $_REQUEST['uid'];    
  $sql11 = "SELECT * FROM tbl_users WHERE id='$uid'";
  $row = getRecord($sql11);
  $_SESSION['User'] = $row;
}

$sql11 = "SELECT * FROM tbl_users WHERE id='$uid'";
$rncnt11 = getRow($sql11);
$row = getRecord($sql11);
$mycity = $row['CityId'];

if($_REQUEST['city_id']==0 || $_REQUEST['city_id']==''){
    $city_id = $mycity;  
}else{
  $city_id = $_REQUEST['city_id'];
}

if($rncnt11 > 0){
    $_SESSION['User'] = $row;
}

$msedclDash = mobileMsedclSmartGetDashboardData();
?>
<!doctype html>
<html lang="en" class="h-100">

<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $Proj_Title; ?></title>

<link rel="manifest" href="manifest.json">
<link rel="apple-touch-icon" href="img/favicon180.png">
<link rel="icon" href="img/favicon32.png">

<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

<link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">

<style>
body{
    background:#f4f6fb;
    font-family:'Roboto',sans-serif;
}
.dashboard-title{
    font-size:22px;
    font-weight:600;
    color:#083068;
}
.project-card{
    border-radius:18px;
    transition:0.3s;
    padding:22px;
    color:#fff;
    text-decoration:none;
    display:block;
}
.project-card:hover{
    transform:translateY(-6px);
    box-shadow:0 14px 30px rgba(0,0,0,0.18);
}
.card-pump{
    background:linear-gradient(135deg,#1e88e5,#42a5f5);
}
.card-rooftop{
    background:linear-gradient(135deg,#ec407a,#f48fb1);
}
.card-task{
    background:linear-gradient(135deg,#43a047,#81c784);
}
.card-stock{
    background:linear-gradient(135deg,#f57c00,#ffb74d);
}
.card-insurance{
    background:linear-gradient(135deg,#00897b,#4db6ac);
}
.card-service{
    background:linear-gradient(135deg,#5e35b1,#9575cd);
}
.card-billing{
    background:linear-gradient(135deg,#1565c0,#42a5f5);
}
.card-msedcl{
    background:linear-gradient(135deg,#2563eb,#7c3aed);
}
.section-label{
    font-size:14px;
    font-weight:600;
    color:#6b7280;
    margin:20px 0 8px;
    text-align:center;
}
.project-icon{
    font-size:44px;
    opacity:0.9;
}
.project-title{
    font-size:18px;
    font-weight:600;
    margin-top:15px;
}
.project-sub{
    font-size:13px;
    opacity:0.9;
}
@media(max-width:576px){
    .project-title{
        font-size:16px;
    }
}

/* Space below top header */
.main-container,
.main {
    padding-top: 20px;
}

/* Space above cards section */
.dashboard-wrapper,
.container {
    margin-top: 25px;
}

/* Space below cards (before bottom nav) */
.main.has-footer {
    padding-bottom: 90px; /* important for bottom bar */
}

/* Extra spacing between rows on mobile */
@media (max-width: 576px) {
    .row.g-3 {
        row-gap: 20px;
    }
}
</style>
</head>

<body class="body-scroll menu-overlay">

<?php include_once 'sidebar.php'; ?>

<main class="main has-footer">
<?php include_once 'top_header.php'; ?>
<br><br>
<div class="container mt-4 mb-5">
    <div class="text-center mb-4">
        <h2 class="dashboard-title">Project Dashboard</h2>
        <p class="text-muted small">Select a module to continue</p>
    </div>

    <div class="row g-3">

        <div class="col-6 col-md-4">
            <a href="pump-management.php" class="project-card card-pump">
                <span class="material-icons project-icon">water</span>
                <div class="project-title">Pump Project</div>
                <div class="project-sub">Manage pump installations</div>
            </a>
        </div>

        <div class="col-6 col-md-4">
            <a href="rooftop-management.php" class="project-card card-rooftop">
                <span class="material-icons project-icon">solar_power</span>
                <div class="project-title">Rooftop Project</div>
                <div class="project-sub">Solar rooftop systems</div>
            </a>
        </div>

        <div class="col-6 col-md-4">
            <a href="task-management.php" class="project-card card-task">
                <span class="material-icons project-icon">assignment</span>
                <div class="project-title">Task Management</div>
                <div class="project-sub">Track daily tasks</div>
            </a>
        </div>

        <div class="col-6 col-md-4">
            <a href="msedcl-smart-management.php" class="project-card card-msedcl">
                <span class="material-icons project-icon">bolt</span>
                <div class="project-title">MSEDCL Smart Project</div>
                <div class="project-sub"><?php echo number_format((int) $msedclDash['total']); ?> customers</div>
            </a>
        </div>

    </div>

    <p class="section-label">Operations</p>

    <div class="row g-3">

        <div class="col-6 col-md-4">
            <a href="stock-management.php" class="project-card card-stock">
                <span class="material-icons project-icon">inventory_2</span>
                <div class="project-title">Stock Management</div>
                <div class="project-sub">PO, challan &amp; quotations</div>
            </a>
        </div>

        <div class="col-6 col-md-4">
            <a href="insurance-management.php" class="project-card card-insurance">
                <span class="material-icons project-icon">health_and_safety</span>
                <div class="project-title">Insurance Management</div>
                <div class="project-sub">Site insurance status</div>
            </a>
        </div>

        <div class="col-6 col-md-4">
            <a href="service-management.php" class="project-card card-service">
                <span class="material-icons project-icon">build</span>
                <div class="project-title">Service / Maintenance</div>
                <div class="project-sub">Complaints &amp; claims</div>
            </a>
        </div>

        <div class="col-6 col-md-4">
            <a href="mobile-contractor-billing.php" class="project-card card-billing">
                <span class="material-icons project-icon">receipt_long</span>
                <div class="project-title">Contractor Billing</div>
                <div class="project-sub">Project-wise commission</div>
            </a>
        </div>

    </div>
</div>

</main>

<?php include_once 'footer.php'; ?>

<script>
function logout(){
    Android.logout();
    window.location.href="logout.php";
}
</script>

</body>
</html>
