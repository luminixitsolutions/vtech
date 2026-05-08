<?php
session_start();
include_once 'config.php';
include_once 'auth.php';

$user_id  = $_SESSION['Admin']['id'];
$MainPage = "Installation";
$Page     = "Installation-Dashboard";

/* =====================
   COUNTERS (SAFE)
===================== */
$totalActive = getRow("
    SELECT id FROM tbl_installation_flow
    WHERE is_completed=0
    AND status='ACTIVE'
");

$completed = getRow("
    SELECT id FROM tbl_installation_flow
    WHERE is_completed=1
");

$coordPending = getRow("
    SELECT id FROM tbl_installation_flow
    WHERE current_stage='COORDINATOR'
    AND is_completed=0
");

$managerPending = getRow("
    SELECT id FROM tbl_installation_flow
    WHERE current_stage='MANAGER'
    AND is_completed=0
");

$gmPending = getRow("
    SELECT id FROM tbl_installation_flow
    WHERE current_stage='GENERAL_MANAGER'
    AND is_completed=0
");

$bhPending = getRow("
    SELECT id FROM tbl_installation_flow
    WHERE current_stage='BUSINESS_HEAD'
    AND is_completed=0
");

$disputed = getRow("
    SELECT id FROM tbl_installation_flow
    WHERE status='DISPUTED'
");

$extensionPending = getRow("
    SELECT id FROM tbl_installation_extensions
    WHERE status='PENDING'
");
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title>Installation Workflow Dashboard</title>
<?php include_once 'header_script.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
.dashboard-section{margin-bottom:40px}
.section-title{font-weight:700;margin-bottom:20px}

.workflow-grid{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(260px,1fr));
    gap:24px
}
.quick-card{
    border-radius:18px;
    padding:22px;
    color:#fff;
    min-height:150px;
    box-shadow:0 10px 28px rgba(0,0,0,.15);
    transition:.3s;
    position:relative
}
.quick-card:hover{transform:translateY(-6px)}
.quick-card i{
    position:absolute;
    right:20px;
    top:20px;
    font-size:38px;
    opacity:.25
}
.quick-card h4{margin-top:12px;font-size:28px;font-weight:800}
.quick-card p{margin:0;font-size:14px;opacity:.9}

.bg-green{background:linear-gradient(135deg,#7AC943,#4CAF50)}
.bg-blue{background:linear-gradient(135deg,#00B4DB,#0083B0)}
.bg-orange{background:linear-gradient(135deg,#F7971E,#FFD200)}
.bg-purple{background:linear-gradient(135deg,#8E2DE2,#4A00E0)}
.bg-red{background:linear-gradient(135deg,#ED213A,#93291E)}
.bg-dark{background:linear-gradient(135deg,#232526,#414345)}

a.card-link{color:#fff;text-decoration:none}
.graph-section .card{border-radius:16px}
</style>
</head>

<body>

<div class="layout-wrapper layout-2">
<div class="layout-inner">

<?php include_once 'sidebar.php'; ?>

<div class="layout-container">
<?php include_once 'top_header.php'; ?>

<div class="layout-content">
<div class="container-fluid container-p-y">

<h4 class="font-weight-bold mb-4">Installation Workflow Dashboard</h4>

<!-- ================= QUICK ACCESS ================= -->
<div class="dashboard-section">
<h5 class="section-title">Workflow Quick Access</h5>

<div class="workflow-grid">

<a href="pending-installations.php" class="card-link">
<div class="quick-card bg-green">
<i class="feather icon-user-plus"></i>
<p>Assign Coordinator</p>
<h4><?php echo (int)$totalActive; ?> Pending</h4>
</div>
</a>

<a href="coordinator-assigned-sites.php" class="card-link">
<div class="quick-card bg-blue">
<i class="feather icon-users"></i>
<p>Coordinator Action</p>
<h4><?php echo (int)$coordPending; ?> Sites</h4>
</div>
</a>

<a href="manager-pending-installations.php" class="card-link">
<div class="quick-card bg-orange">
<i class="feather icon-briefcase"></i>
<p>Manager Action</p>
<h4><?php echo (int)$managerPending; ?> Sites</h4>
</div>
</a>

<a href="gm-pending-installations.php" class="card-link">
<div class="quick-card bg-purple">
<i class="feather icon-shield"></i>
<p>GM Action</p>
<h4><?php echo (int)$gmPending; ?> Sites</h4>
</div>
</a>

<a href="business-head-pending.php" class="card-link">
<div class="quick-card bg-red">
<i class="feather icon-flag"></i>
<p>Business Head</p>
<h4><?php echo (int)$bhPending; ?> Sites</h4>
</div>
</a>

<a href="dispute-sites.php" class="card-link">
<div class="quick-card bg-dark">
<i class="feather icon-alert-triangle"></i>
<p>Dispute Sites</p>
<h4><?php echo (int)$disputed; ?> Cases</h4>
</div>
</a>

</div>
</div>

<!-- ================= GRAPHS ================= -->
<div class="dashboard-section graph-section">
<div class="row">

<div class="col-lg-6 mb-4">
<div class="card p-3">
<h6 class="font-weight-bold mb-3">Stage-wise Pending Sites</h6>
<div style="height:320px">
<canvas id="stageBarChart"></canvas>
</div>
</div>
</div>

<div class="col-lg-6 mb-4">
<div class="card p-3">
<h6 class="font-weight-bold mb-3">Overall Installation Status</h6>
<div style="height:320px">
<canvas id="summaryBarChart"></canvas>
</div>
</div>
</div>

</div>
</div>

</div>
<?php include_once 'footer.php'; ?>
</div>
</div>
</div>

<?php include_once 'footer_script.php'; ?>

<script>
new Chart(document.getElementById('stageBarChart'),{
type:'bar',
data:{
labels:['Coordinator','Manager','GM','Business Head'],
datasets:[{
data:[
<?php echo (int)$coordPending; ?>,
<?php echo (int)$managerPending; ?>,
<?php echo (int)$gmPending; ?>,
<?php echo (int)$bhPending; ?>
],
backgroundColor:['#03A9F4','#FF9800','#9C27B0','#F44336'],
borderRadius:10
}]
},
options:{
maintainAspectRatio:false,
plugins:{legend:{display:false}},
scales:{y:{beginAtZero:true,ticks:{precision:0}}}
}
});

new Chart(document.getElementById('summaryBarChart'),{
type:'bar',
data:{
labels:['Active','Completed','Disputed'],
datasets:[{
data:[
<?php echo (int)$totalActive; ?>,
<?php echo (int)$completed; ?>,
<?php echo (int)$disputed; ?>
],
backgroundColor:['#2196F3','#4CAF50','#424242'],
borderRadius:10
}]
},
options:{
maintainAspectRatio:false,
plugins:{legend:{display:false}},
scales:{y:{beginAtZero:true,ticks:{precision:0}}}
}
});
</script>

</body>
</html>
