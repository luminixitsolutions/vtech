<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage="Products";
$Page = "Add-Products";

?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> - Product</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="">
<meta name="author" content="" />

<?php include_once 'header_script.php'; ?>
<script src="ckeditor/ckeditor.js"></script>
</head>
<body>
<style type="text/css">
  .password-tog-info {
  display: inline-block;
cursor: pointer;
font-size: 12px;
font-weight: 600;
position: absolute;
right: 50px;
top: 30px;
text-transform: uppercase;
z-index: 2;
}


</style>
<style>
    #map {height: 100%;}
    html, body {
      height: 100%;
      margin: 0;
      padding: 0;
    }
    #floating-panel {
      position: absolute;
      top: 10px;
      right: 1%;
      z-index: 5;
      background-color: #fff;
      border: 1px solid #999;
      text-align: center;
    }
    </style>
<div class="layout-wrapper layout-2">
<div class="layout-inner">

<?php include_once 'sidebar.php'; ?>


<div class="layout-container">

<?php include_once 'top_header.php'; ?>
<?php

$id = $_GET['id'];
$sql = "SELECT tpd.*,tu.VehAverage,tpd.VehAverage AS TpdVehAverage FROM tbl_trip_details tpd INNER JOIN tbl_users tu ON tpd.DriverId=tu.id WHERE tpd.id='$id'";
$row7 = getRecord($sql);
$start = strtotime($row7['InDate']);
$end = strtotime($row7['OutDate']);
$days_between = ceil(abs($end - $start) / 86400);
if($row7['TpdVehAverage']>0){
    $VehAverage = $row7['TpdVehAverage'];
}
else{
   $VehAverage = $row7['VehAverage'];  
}

$sql22 = "SELECT SUM(Amount) AS DieselPayment FROM tbl_diesel_amount WHERE TripId='$id'";
$row22 = getRecord($sql22);
if(isset($_POST['submit'])){
 $DieselPayment = addslashes(trim($_POST['DieselPayment']));
$TotalRunningKm = addslashes(trim($_POST['TotalRunningKm']));
$Fastag = addslashes(trim($_POST['Fastag']));
$Challan = addslashes(trim($_POST['Challan']));
$VehAverage = addslashes(trim($_POST['VehAverage']));
$TotalAvgVehicle = addslashes(trim($_POST['TotalAvgVehicle']));
$DieselRate = addslashes(trim($_POST['DieselRate']));
$TotalDieselUsed = addslashes(trim($_POST['TotalDieselUsed']));
$Days = addslashes(trim($_POST['Days']));
$VehicleRate = addslashes(trim($_POST['VehicleRate']));
$TotalVehicleRate = addslashes(trim($_POST['TotalVehicleRate']));
$TotalAmount = addslashes(trim($_POST['TotalAmount']));
$CreatedDate = date('Y-m-d');
$CreatedTime = date('h:i a');


$sql = "UPDATE tbl_trip_details SET DieselPayment='$DieselPayment',TotalRunningKm='$TotalRunningKm',Fastag='$Fastag',Challan='$Challan',VehAverage='$VehAverage',TotalAvgVehicle='$TotalAvgVehicle',DieselRate='$DieselRate',TotalDieselUsed='$TotalDieselUsed',Days='$Days',VehicleRate='$VehicleRate',TotalVehicleRate='$TotalVehicleRate',TotalAmount='$TotalAmount',CalModifiedBy='$user_id',CalModifiedDate='$CreatedDate',CalModifiedTime='$CreatedTime' WHERE id='$id'";
$conn->query($sql);
echo "<script>alert('Trip Amount Added Successfully');window.location.href='completed-trips.php';</script>";
}
?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Add Trip Calculation</h4>

<div class="row">
    <div class="col-lg-8">
<div class="card mb-4">
<div class="card-body">
<div id="alert_message"></div>
<form id="validation-form" method="post" enctype="multipart/form-data">
<div class="form-row">

 <div class="form-group col-md-12">
                                            <label class="form-label">Trip Details <span
                                                    class="text-danger">*</span> </label>
                                            <textarea name="TripDetails" id="TripDetails" class="form-control"
                                                placeholder=""
                                                autocomplete="off" readonly><?php echo $row7['TripDetails']; ?></textarea>
                                            <div class="clearfix"></div>
                                        </div> 
                                        
      <div class="form-group col-md-3">
<label class="form-label">Driver Name </label>
<input type="text" name="DriverName" id="DriverName" class="form-control" placeholder="" value="<?php echo $row7['DriverName']; ?>" readonly>
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Vehicle No </label>
<input type="text" name="VehicalNo" id="VehicalNo" class="form-control" placeholder="" value="<?php echo $row7['VehicalNo']; ?>" readonly>
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
                                            <label class="form-label">In Date <span
                                                    class="text-danger">*</span></label>
                                            <input type="date" name="InDate" id="InDate" class="form-control"
                                                placeholder="" value="<?php echo $row7['InDate']; ?>"
                                                autocomplete="off" readonly>
                                            <div class="clearfix"></div>
                                        </div> 

<div class="form-group col-md-3"> 
                                            <label class="form-label">In Time <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="InTime" id="InTime" class="form-control"
                                                placeholder="" value="<?php echo date("h:i a", strtotime(str_replace('-', '/',$row7['InTime']))); ?>"
                                                autocomplete="off" readonly>
                                            <div class="clearfix"></div>
                                        </div> 
                                        
   <div class="form-group col-md-3">
<label class="form-label">Opening Reading <span class="text-danger">*</span></label>
<input type="text" name="OpeningReading" id="OpeningReading" class="form-control" placeholder="" value="<?php echo $row7['OpeningReading']; ?>" readonly>
 <div class="clearfix"></div>
</div>
                                     

 



<div class="form-group col-md-3">
                                            <label class="form-label">Out Date <span
                                                    class="text-danger">*</span></label>
                                            <input type="date" name="OutDate" id="OutDate" class="form-control"
                                                placeholder="" value="<?php echo $row7['OutDate']; ?>"
                                                autocomplete="off" readonly>
                                            <div class="clearfix"></div>
                                        </div> 
        
      
     
                                       
<div class="form-group col-md-3">
                                            <label class="form-label">Out Time <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="OutTime" id="OutTime" class="form-control"
                                                placeholder="" value="<?php echo date("h:i a", strtotime(str_replace('-', '/',$row7['OutTime']))); ?>"
                                                autocomplete="off" readonly>
                                            <div class="clearfix"></div>
                                        </div> 

<div class="form-group col-md-3">
<label class="form-label">Closing Reading <span class="text-danger">*</span></label>
<input type="number" name="ClosingReading" id="ClosingReading" class="form-control" placeholder="" value="<?php echo $row7['ClosingReading']; ?>" readonly min="0">
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Fastag <span class="text-danger">*</span></label>
<input type="text" name="Fastag" id="Fastag" class="form-control" placeholder="" value="<?php echo $row7['Fastag']; ?>" required oninput="calTotalAmt(document.getElementById('Fastag').value,document.getElementById('Challan').value,document.getElementById('DieselPayment').value,document.getElementById('TotalRunningKm').value,document.getElementById('VehAverage').value,document.getElementById('DieselRate').value,document.getElementById('Days').value,document.getElementById('VehicleRate').value)">
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Challan  <span class="text-danger">*</span></label>
<input type="text" name="Challan" id="Challan" class="form-control" placeholder="" value="<?php echo $row7['Challan']; ?>" required oninput="calTotalAmt(document.getElementById('Fastag').value,document.getElementById('Challan').value,document.getElementById('DieselPayment').value,document.getElementById('TotalRunningKm').value,document.getElementById('VehAverage').value,document.getElementById('DieselRate').value,document.getElementById('Days').value,document.getElementById('VehicleRate').value)">
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Diesel Payment  <span class="text-danger">*</span></label>
<input type="text" name="DieselPayment" id="DieselPayment" class="form-control" placeholder="" value="<?php echo $row22['DieselPayment']; ?>" readonly >
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Total Running Km  <span class="text-danger">*</span></label>
<input type="text" name="TotalRunningKm" id="TotalRunningKm" class="form-control" placeholder="" value="<?php echo $row7['ClosingReading']-$row7['OpeningReading']; ?>" readonly>
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Vehicle Average  <span class="text-danger">*</span></label>
<input type="text" name="VehAverage" id="VehAverage" class="form-control" placeholder="" value="<?php echo $VehAverage; ?>" required oninput="calTotalAmt(document.getElementById('Fastag').value,document.getElementById('Challan').value,document.getElementById('DieselPayment').value,document.getElementById('TotalRunningKm').value,document.getElementById('VehAverage').value,document.getElementById('DieselRate').value,document.getElementById('Days').value,document.getElementById('VehicleRate').value)">
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Avg Of Vehicle <span class="text-danger">*</span></label>
<input type="text" name="TotalAvgVehicle" id="TotalAvgVehicle" class="form-control" placeholder="" value="<?php echo $row7['TotalAvgVehicle']; ?>" readonly>
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Diesel Rate <span class="text-danger">*</span></label>
<input type="text" name="DieselRate" id="DieselRate" class="form-control" placeholder="" value="<?php echo $row7['DieselRate']; ?>" required oninput="calTotalAmt(document.getElementById('Fastag').value,document.getElementById('Challan').value,document.getElementById('DieselPayment').value,document.getElementById('TotalRunningKm').value,document.getElementById('VehAverage').value,document.getElementById('DieselRate').value,document.getElementById('Days').value,document.getElementById('VehicleRate').value)">
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Total Diesel Used <span class="text-danger">*</span></label>
<input type="text" name="TotalDieselUsed" id="TotalDieselUsed" class="form-control" placeholder="" value="<?php echo $row7['TotalDieselUsed']; ?>" readonly>
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Days <span class="text-danger">*</span></label>
<input type="text" name="Days" id="Days" class="form-control" placeholder="" value="<?php echo $days_between; ?>" required oninput="calTotalAmt(document.getElementById('Fastag').value,document.getElementById('Challan').value,document.getElementById('DieselPayment').value,document.getElementById('TotalRunningKm').value,document.getElementById('VehAverage').value,document.getElementById('DieselRate').value,document.getElementById('Days').value,document.getElementById('VehicleRate').value)">
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Per Day Vehicle Rate <span class="text-danger">*</span></label>
<input type="text" name="VehicleRate" id="VehicleRate" class="form-control" placeholder="" value="<?php echo $row7['VehicleRate']; ?>" required oninput="calTotalAmt(document.getElementById('Fastag').value,document.getElementById('Challan').value,document.getElementById('DieselPayment').value,document.getElementById('TotalRunningKm').value,document.getElementById('VehAverage').value,document.getElementById('DieselRate').value,document.getElementById('Days').value,document.getElementById('VehicleRate').value)">
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Total Vehicle Rate<span class="text-danger">*</span></label>
<input type="text" name="TotalVehicleRate" id="TotalVehicleRate" class="form-control" placeholder="" value="<?php echo $row7['TotalVehicleRate']; ?>" readonly>
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Total Amount <span class="text-danger">*</span></label>
<input type="text" name="TotalAmount" id="TotalAmount" class="form-control" placeholder="" value="<?php echo $row7['TotalAmount']; ?>" readonly>
 <div class="clearfix"></div>
</div>
                                          
</div>
<button type="submit" name="submit" class="btn btn-primary btn-finish">Submit</button>
</form>
</div> 
</div>
</div>

<div class="col-lg-4">
    <div class="form-group">
   <div id="dvMap" style="width: 100%; height: 300px">
    </div>
</div>
</div>
</div>

<?php include_once 'footer.php'; ?>
</div>
</div>
</div>
<div class="layout-overlay layout-sidenav-toggle"></div>
</div>


<?php include_once 'footer_script.php'; ?>
 <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?libraries=places&key=AIzaSyADZAncocVsQMiK8ebIDhli29nk5GWWydk"></script>
<script type="text/javascript">
    var markers = [
        <?php 
        $sql = "SELECT StartLattitude,StartLongitude,EndLattitude,EndLongitude FROM tbl_trip_details WHERE id='".$_GET['id']."'";
        $row = getRecord($sql);?>
        {
            "title": 'Start Trip',
            "latitude": '<?php echo $row['StartLattitude'];?>',
            "longitude": '<?php echo $row['StartLongitude'];?>',
            "description": 'Start Trip'
        },
        
        {
            "title": 'End Trip',
            "latitude": '<?php echo $row['EndLattitude'];?>',
            "longitude": '<?php echo $row['EndLongitude'];?>',
            "description": 'End Trip'
        },

        
    ];
    window.onload = function () {
        var mapOptions = {
            center: new google.maps.LatLng(markers[0].latitude, markers[0].longitude),
            zoom: 10,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var map = new google.maps.Map(document.getElementById("dvMap"), mapOptions);
        var infoWindow = new google.maps.InfoWindow();
        var lat_lng = new Array();
        var latlngbounds = new google.maps.LatLngBounds();
        for (i = 0; i < markers.length; i++) {
            var data = markers[i]
            var myLatlng = new google.maps.LatLng(data.latitude, data.longitude);
            lat_lng.push(myLatlng);
            var marker = new google.maps.Marker({
                position: myLatlng,
                map: map,
                title: data.title
            });

            latlngbounds.extend(marker.position);
            (function (marker, data) {
                google.maps.event.addListener(marker, "click", function (e) {
                    infoWindow.setContent(data.title);
                    infoWindow.open(map, marker);
                });
            })(marker, data);
        }
        map.setCenter(latlngbounds.getCenter());
        map.fitBounds(latlngbounds);

        var service = new google.maps.DirectionsService();

        for (var i = 0; i < lat_lng.length; i++) {
            if ((i + 1) < lat_lng.length) {
                var src = lat_lng[i];
                var des = lat_lng[i + 1];

                service.route({
                    origin: src,
                    destination: des,
                    travelMode: google.maps.DirectionsTravelMode.WALKING
                }, function (result, status) {
                    if (status == google.maps.DirectionsStatus.OK) {
                        var path = new google.maps.MVCArray();
                        var poly = new google.maps.Polyline({
                            map: map,
                            strokeColor: '#4986E7'
                        });
                        poly.setPath(path);
                        for (var i = 0, len = result.routes[0].overview_path.length; i < len; i++) {
                            path.push(result.routes[0].overview_path[i]);
                        }
                    }
                });
            }
        }
    }
</script>
<script>
     function calTotalAmt(Fastag,Challan,DieselPayment,TotalRunningKm,VehAverage,DieselRate,Days,VehicleRate){
        var TotalAvgVehicle =  Number(TotalRunningKm) / Number(VehAverage);
        $('#TotalAvgVehicle').val(parseFloat(TotalAvgVehicle).toFixed(2));

        var TotalDieselUsed =  Number(TotalAvgVehicle) * Number(DieselRate);
        $('#TotalDieselUsed').val(parseFloat(TotalDieselUsed).toFixed(2));

        var TotalVehicleRate =  Number(Days) * Number(VehicleRate);
        $('#TotalVehicleRate').val(parseFloat(TotalVehicleRate).toFixed(2));

        var TotalAmount =  Number(TotalVehicleRate) - (Number(DieselPayment) - Number(TotalDieselUsed)) + Number(Fastag) + Number(Challan);
        $('#TotalAmount').val(parseFloat(TotalAmount).toFixed(2));
     }

     $(document).ready(function() {
        var Fastag = $('#Fastag').val();
        var Challan = $('#Challan').val();
        var DieselPayment = $('#DieselPayment').val();
        var TotalRunningKm = $('#TotalRunningKm').val();
        var VehAverage = $('#VehAverage').val();
        var DieselRate = $('#DieselRate').val();
        var Days = $('#Days').val();
        var VehicleRate = $('#VehicleRate').val();
       calTotalAmt(Fastag,Challan,DieselPayment,TotalRunningKm,VehAverage,DieselRate,Days,VehicleRate);
}); 
</script>
</body>
</html>