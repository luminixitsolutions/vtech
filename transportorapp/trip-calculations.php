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
<title><?php echo $Proj_Title; ?> | Trip Calculations</title>
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
    <h4 class="font-weight-bold py-3 mb-0">Trip Calculations</h4>
    <?php 
    $sql = "SELECT * FROM tbl_trip_details WHERE " . transportorTripWhere($user_id, "Status=1 AND TotalAmount > 0");
    $sql .= " ORDER BY OutDate DESC, InDate DESC";
    $res = $conn->query($sql);
    $trips = [];
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $trips[] = $row;
        }
    }
    $trips = transportorAttachTripPayments($trips);
    if (empty($trips)) {
        echo '<div class="alert alert-info mt-3">No trip calculations available yet.</div>';
    }
    foreach ($trips as $row) {
        $sql22 = "SELECT SUM(Amount) AS DieselPayment FROM tbl_diesel_amount WHERE TripId='" . $row['id'] . "'";
        $row22 = getRecord($sql22);
    ?>
<div class="card mb-4">
    <div class="card-body">
        <h6 style="margin-bottom: 1px;"><?php echo htmlspecialchars($row['TripDetails']); ?></h6>
        <p style="margin-bottom: 1px;"><strong>Driver :</strong> <?php echo htmlspecialchars($row['DriverName']); ?> | <strong>Veh No :</strong> <?php echo htmlspecialchars($row['VehicalNo']); ?></p>
        <p style="margin-bottom: 1px;"><strong>In Date :</strong> <?php echo date("d/m/Y", strtotime(str_replace('-', '/', $row['InDate']))); ?> | <strong>Out Date :</strong> <?php echo date("d/m/Y", strtotime(str_replace('-', '/', $row['OutDate']))); ?></p>
        <p style="margin-bottom: 1px;"><strong>Running KM :</strong> <?php echo htmlspecialchars($row['TotalRunningKm']); ?> | <strong>Days :</strong> <?php echo htmlspecialchars($row['Days']); ?></p>
        <p style="margin-bottom: 1px;"><strong>Diesel Payment :</strong> &#8377;<?php echo htmlspecialchars($row22['DieselPayment'] ?? $row['DieselPayment']); ?> | <strong>Fastag :</strong> &#8377;<?php echo htmlspecialchars($row['Fastag']); ?> | <strong>Challan :</strong> &#8377;<?php echo htmlspecialchars($row['Challan']); ?></p>
        <p style="margin-bottom: 1px;"><strong>Food :</strong> &#8377;<?php echo htmlspecialchars($row['Food'] ?? 0); ?> | <strong>Vehicle Rate :</strong> &#8377;<?php echo htmlspecialchars($row['TotalVehicleRate'] ?? 0); ?></p>
        <p style="margin-bottom: 1px;"><strong>Total Amount :</strong> <span style="color:green;font-weight:bold;">&#8377;<?php echo htmlspecialchars($row['TotalAmount']); ?></span></p>
        <?php echo transportorTripPaymentStatusHtml($row); ?>
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
