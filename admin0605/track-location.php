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


<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Track Location</h4>

<div class="row">
   

<div class="col-lg-12">
    <div class="form-group">
   <div id="dvMap" style="width: 100%; height: 500px">
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
        {
            "title": 'Start Attendance',
            "latitude": '<?php echo $_GET['stlat'];?>',
            "longitude": '<?php echo $_GET['stlang'];?>',
            "description": 'Start Attendance'
        },
        
        {
            "title": 'End Attendance',
            "latitude": '<?php echo $_GET['edlat'];?>',
            "longitude": '<?php echo $_GET['edlang'];?>',
            "description": 'End Attendance'
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

</body>
</html>