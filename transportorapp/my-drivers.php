<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
include_once 'inc-transportor.php';
$user_id = $_SESSION['User']['id'];
$MainPage = "Customers";
$Page = "View-Customers";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | My Drivers</title>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <link rel="apple-touch-icon" href="img/favicon180.png" sizes="180x180">
    <link rel="icon" href="img/favicon32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="img/favicon16.png" sizes="16x16" type="image/png">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&amp;display=swap" rel="stylesheet">
    <link href="vendor/swiper/css/swiper.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet" id="style">
    <link href="css/toastr.min.css" rel="stylesheet">
    <script src="js/jquery.min3.5.1.js" type="text/javascript"></script>
    <script type="text/javascript" src="js/toastr.min.js"></script>
    <?php include_once 'header_script.php'; ?>
</head>
<body class="body-scroll d-flex flex-column h-100 menu-overlay">
    <main class="flex-shrink-0 main">
        <?php include_once 'back-header.php'; ?> 
        <div class="main-container" style="background-color: #f1f1f1;">
<div class="container">
    <h4 class="font-weight-bold py-3 mb-0">My Drivers</h4>
    <?php 
    $sql = "SELECT * FROM tbl_users WHERE UnderUser='$user_id' AND Roll=39 AND Status=1 ORDER BY Fname";
    $res = $conn->query($sql);
    if (!$res || $res->num_rows === 0) {
        echo '<div class="alert alert-info mt-3">No drivers assigned yet.</div>';
    }
    while ($res && ($row = $res->fetch_assoc())) {
        $hasRunningTrip = transportorDriverHasRunningTrip((int) $row['id']);
    ?>
<div class="card mb-4">
    <div class="card-body">
        <h6 style="margin-bottom: 1px;"><?php echo htmlspecialchars($row['Fname'] . ' ' . $row['Lname']); ?></h6>
        <p style="margin-bottom: 1px;"><strong>Phone :</strong> <?php echo htmlspecialchars($row['Phone']); ?></p>
        <p style="margin-bottom: 1px;"><strong>Vehicle No :</strong> <?php echo htmlspecialchars($row['VehicalNo']); ?></p>
        <p style="margin-bottom: 1px;"><strong>Vehicle Model :</strong> <?php echo htmlspecialchars($row['VehicalModel']); ?></p>
        <p style="margin-bottom: 1px;"><strong>Per Day Rate :</strong> &#8377;<?php echo htmlspecialchars($row['PerDayVehicle']); ?></p>
        <?php if ($hasRunningTrip) { ?>
        <span class="badge badge-warning">Trip Running</span>
        <a href="running-trips.php" class="btn btn-primary btn-sm mt-2">View Running Trip</a>
        <?php } else { ?>
        <a href="start-trip.php?driver_id=<?php echo (int) $row['id']; ?>" class="btn btn-success btn-sm mt-2">Start Trip</a>
        <?php } ?>
    </div>
</div>
    <?php } ?>
</div>
<?php include_once 'footer.php'; ?>
</div>
</main>
<br><br>
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="js/jquery.cookie.js"></script>
    <script src="vendor/swiper/js/swiper.min.js"></script>
    <script src="js/main.js"></script>
    <script src="js/color-scheme-demo.js"></script>
    <script src="js/app.js"></script>
    <?php include_once 'footer_script.php'; ?>
</body>
</html>
