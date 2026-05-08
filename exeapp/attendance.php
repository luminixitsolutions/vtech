<?php session_start();
require_once 'config.php';
require_once 'auth.php';
$PageName = "Attendance";
$UserId = $_SESSION['User']['id'];
$sql11 = "SELECT * FROM tbl_users WHERE id='$UserId'";
$row11 = getRecord($sql11);
$Name = $row11['Fname']." ".$row11['Lname'];
$Phone = $row11['Phone'];
$EmailId = $row11['EmailId'];
$Latitude = $row11['Lattitude']; 
$Longitude = $row11['Longitude'];
$EmployeeProfileLat = null;
$EmployeeProfileLng = null;
if (isset($row11['Lattitude'], $row11['Longitude'])
    && $row11['Lattitude'] !== '' && $row11['Lattitude'] !== null
    && $row11['Longitude'] !== '' && $row11['Longitude'] !== null
    && is_numeric($row11['Lattitude']) && is_numeric($row11['Longitude'])) {
    $EmployeeProfileLat = (float) $row11['Lattitude'];
    $EmployeeProfileLng = (float) $row11['Longitude'];
}
$OfficeEmployee = 0;
$escUid = mysqli_real_escape_string($conn, (string) $UserId);
$u2OfficeRow = getRecord("SELECT OfficeEmployee FROM tbl_user2 WHERE id='$escUid' LIMIT 1");
if ($u2OfficeRow && isset($u2OfficeRow['OfficeEmployee']) && (int) $u2OfficeRow['OfficeEmployee'] === 1) {
    $OfficeEmployee = 1;
}
$CompId = isset($row11['CompId']) ? (int) $row11['CompId'] : 0;
$CompanyOfficeLat = null;
$CompanyOfficeLng = null;
$CompanyName = '';
if ($CompId > 0) {
    $escComp = mysqli_real_escape_string($conn, (string) $CompId);
    $sqlCo = "SELECT Fname, Lattitude, Longitude FROM tbl_users WHERE id='$escComp' AND Roll=10 LIMIT 1";
    $rowCo = getRecord($sqlCo);
    if ($rowCo) {
        $CompanyName = isset($rowCo['Fname']) ? $rowCo['Fname'] : '';
    }
    if ($rowCo && isset($rowCo['Lattitude'], $rowCo['Longitude'])
        && $rowCo['Lattitude'] !== '' && $rowCo['Lattitude'] !== null
        && $rowCo['Longitude'] !== '' && $rowCo['Longitude'] !== null
        && is_numeric($rowCo['Lattitude']) && is_numeric($rowCo['Longitude'])) {
        $CompanyOfficeLat = (float) $rowCo['Lattitude'];
        $CompanyOfficeLng = (float) $rowCo['Longitude'];
    }
}
$attendanceOfficeGeofence = array(
    'enforce' => ($OfficeEmployee === 1 && $CompanyOfficeLat !== null && $CompanyOfficeLng !== null),
    'lat' => $CompanyOfficeLat,
    'lng' => $CompanyOfficeLng,
    'radiusM' => 100,
    'employeeProfileLat' => $EmployeeProfileLat,
    'employeeProfileLng' => $EmployeeProfileLng,
);

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

    <!-- manifest meta -->
    <meta name="apple-mobile-web-app-capable" content="yes">

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="img/favicon180.png" sizes="180x180">
    <link rel="icon" href="img/favicon32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="img/favicon16.png" sizes="16x16" type="image/png">

    <!-- Material icons-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&amp;display=swap" rel="stylesheet">

    <!-- swiper CSS -->
    <link href="vendor/swiper/css/swiper.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet" id="style">
    <link href="css/toastr.min.css" rel="stylesheet">
      <script src="js/jquery.min3.5.1.js" type="text/javascript"></script>
    <script type="text/javascript" src="js/toastr.min.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
      <link rel="stylesheet" href="example/css/slim.min.css">
</head>

<style>
    .custom-control {
  line-height: 24px;
  padding-top: 5px;
}
    .attendance-loc-card .loc-row { font-size: 0.88rem; }
    .attendance-loc-card .loc-label { color: #888; font-size: 0.75rem; text-transform: uppercase; letter-spacing: .03em; }
    .attendance-inline-error {
        display: none;
        padding: 0.5rem 0.65rem;
        margin-top: 0.5rem;
        border-radius: 4px;
        background: #fdecea;
        border: 1px solid #f5c6cb;
        color: #721c24;
        font-size: 0.85rem;
        line-height: 1.35;
    }
</style>
<body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <!-- Fixed navbar -->
        <?php include_once 'back-header.php'; ?> 
        
<?php 
$CurrDate = date('Y-m-d');

$sql8 = "SELECT ta.*,tu.Fname FROM tbl_attendance ta INNER JOIN tbl_users tu ON ta.UserId=tu.id WHERE ta.UserId='$UserId' AND ta.CreatedDate='$CurrDate' AND Type=1";
$rncnt8 = getRow($sql8);
$row8 = getRecord($sql8);

$sql9 = "SELECT ta.*,tu.Fname FROM tbl_attendance ta INNER JOIN tbl_users tu ON ta.UserId=tu.id WHERE ta.UserId='$UserId' AND ta.CreatedDate='$CurrDate' AND Type=2";
$rncnt9 = getRow($sql9);
$row9 = getRecord($sql9);
     
$sql7 = "SELECT DISTINCT ta.CreatedDate, tu.Fname FROM tbl_attendance ta INNER JOIN tbl_users tu ON ta.UserId=tu.id WHERE ta.UserId='$UserId' AND ta.CreatedDate='$CurrDate' ORDER BY ta.CreatedDate DESC";
$rncnt7 = getRow($sql7);
$row7 = getRecord($sql7);
?>
        <div class="main-container">
            <div class="container">
               

             <?php include 'start-attendance.php';include 'end-attendance.php';?>

                <?php if ($OfficeEmployee === 1 && $CompanyOfficeLat !== null && $CompanyOfficeLng !== null) { ?>
                <div class="card mb-3 attendance-loc-card border-0 bg-light">
                    <div class="card-body py-3">
                        <h6 class="mb-2 font-weight-bold" style="color:#e74623;">Office location</h6>
                        <?php if ($CompanyName !== '') { ?>
                        <p class="small text-muted mb-2"><?php echo htmlspecialchars($CompanyName); ?></p>
                        <?php } ?>
                        <div class="loc-row mb-2">
                            <div class="loc-label">Company (lat / long)</div>
                            <div><span id="company_office_lat_display"><?php echo htmlspecialchars(number_format($CompanyOfficeLat, 7, '.', '')); ?></span>,
                                <span id="company_office_lng_display"><?php echo htmlspecialchars(number_format($CompanyOfficeLng, 7, '.', '')); ?></span></div>
                        </div>
                        <div class="loc-row mb-2">
                            <div class="loc-label">You — saved on your account (database)</div>
                            <div>Lat <span id="employee_current_lat"><?php echo $EmployeeProfileLat !== null ? htmlspecialchars(number_format($EmployeeProfileLat, 7, '.', '')) : '—'; ?></span> · Lng <span id="employee_current_lng"><?php echo $EmployeeProfileLng !== null ? htmlspecialchars(number_format($EmployeeProfileLng, 7, '.', '')) : '—'; ?></span></div>
                        </div>
                        <div class="loc-row font-weight-medium">
                            <span class="loc-label d-block">Distance (company ↔ your profile)</span>
                            <span id="distance_company_employee_km" class="text-dark">—</span>
                        </div>
                    </div>
                </div>
                <?php } elseif ($OfficeEmployee === 1 && $CompId > 0) { ?>
                <div class="card mb-3 border-0">
                    <div class="card-body py-2 small text-muted">Your company has no map coordinates yet. Ask admin to set latitude and longitude on the company profile to see distance here.</div>
                </div>
                <?php } ?>

<form id="attendance-hiddens-form" method="post" autocomplete="off">
               <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0" style="color:#e74623;">Today Attendance</h6>
                    </div>
                    
                    

                        <div style="float:right;padding-left: 10px;">
                                      <div class="row">
                                      <div class="col-lg-6 col-6">                             
                 <button type="button" class="btn btn-sm btn-default rounded" data-toggle="modal" data-target="#myModal" <?php if($rncnt8 > 0){?> disabled <?php } ?>>Start Attendance</button>
                 </div>
                 <div class="col-lg-6 col-6">
                 <button type="button" class="btn btn-sm btn-default rounded" data-toggle="modal" data-target="#myModal2" <?php if($rncnt9 > 0){?> disabled <?php } else if($rncnt8 > 0) {} else {?> disabled <?php } ?>>End Attendance</button>
                 </div>
                 </div>
                        <div id="attendance-main-inline-error" class="attendance-inline-error w-100 clearfix" role="alert" style="display: none;"></div>
            
                                
                                                                </div><br><br>
                                                         
                   
                </div>

              
               <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0" style="color:#e74623;">Today Attendance</h6>
                    </div>
                 
                    <div class="card-body">



<div class="row text-center mt-3">

    <div class="col-4 col-md-3" style="padding-right: 5px;padding-left: 5px;">
                       
                       

                    </div>
                    <div class="col-4 col-md-3">
                       
                        Start Time
                        
                    </div>
                    <div class="col-4 col-md-3">
                      
                      End Time
                       
                    </div>

    <?php 
            $i=1;
            $row77 = getList($sql7);
            foreach($row77 as $result){

                    if($result['Type'] == 1){
                        $Name = "Start";
                    }
                    else{
                        $Name = "End";
                    }

                    $sql33 = "SELECT * FROM tbl_attendance WHERE CreatedDate='".$result['CreatedDate']."' AND UserId='$UserId' AND Type=1";
                    $rncnt33 = getRow($sql33);
                    $row33 = getRecord($sql33);

                    $sql34 = "SELECT * FROM tbl_attendance WHERE CreatedDate='".$result['CreatedDate']."' AND UserId='$UserId' AND Type=2";
                    $rncnt34 = getRow($sql34);
                    $row34 = getRecord($sql34);

                    if($rncnt33 > 0){
                        $bgcolor = "background-color: #acf3ac;";
                        $st_time = date("h:i a", strtotime(str_replace('-', '/',$row33['CreatedTime'])));
                    }
                    else{
                        $bgcolor = "background-color: #f55d5d;";
                        $st_time = "";
                    }

                    if($rncnt34 > 0){
                        $bgcolor2 = "background-color: #acf3ac;";
                        $ed_time = date("h:i a", strtotime(str_replace('-', '/',$row34['CreatedTime'])));
                    }
                    else{
                        $bgcolor2 = "background-color: #f55d5d;";
                        $ed_time = "";
                    }

            ?>
                        <div class="col-4 col-md-3" style="padding-right: 5px;padding-left: 5px;">
                       
                        <div class="card border-0 mb-4">
                            <div class="card-body" style="padding-top: 1px;">
                                
                                <h3 class="mt-3 mb-0 font-weight-normal" style="font-size: 14px;"><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$result['CreatedDate']))); ?></h3>
                              
                            </div>
                        </div>

                    </div>
                    <div class="col-4 col-md-3">
                       
                        <div class="card border-0 mb-4" style="<?php echo $bgcolor;?>">
                            <div class="card-body" style="padding-top: 1px;">
                                
                                <h3 class="mt-3 mb-0 font-weight-normal" style="font-size: 14px;"><?php echo $st_time;?></h3>
                              
                            </div>
                        </div>
                        <?php if($rncnt33 > 0 && !empty($row33['Photo'])){?>
                        <div class="avatar avatar-80 rounded">
                                            <div class="background">
                                                <img src="../uploads/<?php echo $row33['Photo'];?>" alt="">
                                            </div>
                                        </div>
                        <?php } ?>
                    </div>
                    <div class="col-4 col-md-3">
                      
                        <div class="card border-0 mb-4" style="<?php echo $bgcolor2;?>">
                            <div class="card-body" style="padding-top: 1px;">
                                
                                <h3 class="mt-3 mb-0 font-weight-normal" style="font-size: 14px;"><?php echo $ed_time;?></h3>
                              
                            </div>
                        </div>
                         <?php if($rncnt34 > 0 && !empty($row34['Photo'])){?>
                        <div class="avatar avatar-80 rounded">
                                            <div class="background">
                                                <img src="../uploads/<?php echo $row34['Photo'];?>" alt="">
                                            </div>
                                        </div>
                       <?php } ?>
                    </div>
                     <?php $i++;} ?>
                    </div>


                        

                       
                      
                       
                    </div>
                </div>

              <input type="hidden" id="srno" value="<?php echo $i;?>">
<input type="hidden" id="CurrDate" value="<?php echo date('Y-m-d');?>">
<input type="hidden" id="userid" value="<?php echo $UserId;?>">

<input type="hidden" id="SourceLat" name="SourceLat" value="<?php echo $Latitude;?>">
        <input type="hidden" id="SourceLong" name="SourceLong" value="<?php echo $Longitude;?>">
        <input type="hidden" id="SourceAddress" name="SourceAddress" value="">

               
             </form>
        </div>
    </main>

    <!-- footer-->
    


    <!-- Required jquery and libraries -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- cookie js -->
    <script src="js/jquery.cookie.js"></script>

    <!-- Swiper slider  js-->
    <script src="vendor/swiper/js/swiper.min.js"></script>

    <!-- Customized jquery file  -->
    <script src="js/main.js"></script>
    <script src="js/color-scheme-demo.js"></script>


    <!-- page level custom script -->
    <script src="js/app.js"></script>
    
<script>
var ATTENDANCE_OFFICE_CHECK = <?php echo json_encode($attendanceOfficeGeofence); ?>;

function attendanceHaversineMeters(lat1, lon1, lat2, lon2) {
    var R = 6371000;
    var toRad = function(x) { return x * Math.PI / 180; };
    var dLat = toRad(lat2 - lat1);
    var dLon = toRad(lon2 - lon1);
    var a = Math.sin(dLat / 2) * Math.sin(dLat / 2)
        + Math.cos(toRad(lat1)) * Math.cos(toRad(lat2))
        * Math.sin(dLon / 2) * Math.sin(dLon / 2);
    return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
}

function attendanceGeoErrorToast(msg) {
    toastr.error(msg, 'Location', { timeOut: 6000 });
}

function attendanceShowInlineError(selector, msg) {
    if (!selector) {
        return;
    }
    var $el = $(selector);
    if (!$el.length) {
        return;
    }
    $el.text(msg).css('display', 'block');
}

function attendanceClearInlineError(selector) {
    if (!selector) {
        return;
    }
    var $el = $(selector);
    if (!$el.length) {
        return;
    }
    $el.hide().empty();
}

/** Toast + optional inline message (e.g. below Submit or below Start/End buttons). */
function attendanceLocationError(msg, inlineSelector) {
    attendanceGeoErrorToast(msg);
    attendanceShowInlineError(inlineSelector, msg);
}

/** Office geofence message if not eligible; null if OK or not enforced. */
function attendanceOfficeEligibilityMessage() {
    if (!ATTENDANCE_OFFICE_CHECK.enforce || ATTENDANCE_OFFICE_CHECK.lat == null || ATTENDANCE_OFFICE_CHECK.lng == null) {
        return null;
    }
    var pLat = ATTENDANCE_OFFICE_CHECK.employeeProfileLat;
    var pLng = ATTENDANCE_OFFICE_CHECK.employeeProfileLng;
    if (pLat == null || pLng == null || isNaN(parseFloat(pLat)) || isNaN(parseFloat(pLng))) {
        return 'Your profile has no latitude/longitude. Ask admin to update your employee record.';
    }
    var d = attendanceHaversineMeters(
        parseFloat(pLat),
        parseFloat(pLng),
        parseFloat(ATTENDANCE_OFFICE_CHECK.lat),
        parseFloat(ATTENDANCE_OFFICE_CHECK.lng)
    );
    if (d > ATTENDANCE_OFFICE_CHECK.radiusM) {
        return 'Not eligible: your saved location is more than 100 m from the office. Update your profile coordinates after you are at the office.';
    }
    return null;
}

/** Update “your location” + distance when company coords exist in ATTENDANCE_OFFICE_CHECK */
function attendanceUpdateDistanceUI(empLat, empLng) {
    if (ATTENDANCE_OFFICE_CHECK.lat == null || ATTENDANCE_OFFICE_CHECK.lng == null) {
        return;
    }
    var elat = parseFloat(empLat);
    var elng = parseFloat(empLng);
    if (isNaN(elat) || isNaN(elng)) {
        return;
    }
    var clat = parseFloat(ATTENDANCE_OFFICE_CHECK.lat);
    var clng = parseFloat(ATTENDANCE_OFFICE_CHECK.lng);
    $('#employee_current_lat').text(elat.toFixed(7));
    $('#employee_current_lng').text(elng.toFixed(7));
    var m = attendanceHaversineMeters(elat, elng, clat, clng);
    var km = (m / 1000).toFixed(3);
    $('#distance_company_employee_km').text(km + ' km (' + Math.round(m) + ' m)');
}

/** Fill distance using company vs employee profile coordinates from database (ATTENDANCE_OFFICE_CHECK.employeeProfileLat/Lng). */
function attendanceApplyProfileLocationsFromDb() {
    var elat = ATTENDANCE_OFFICE_CHECK.employeeProfileLat;
    var elng = ATTENDANCE_OFFICE_CHECK.employeeProfileLng;
    if (elat == null || elng == null || isNaN(parseFloat(elat)) || isNaN(parseFloat(elng))) {
        if ($('#distance_company_employee_km').length) {
            $('#distance_company_employee_km').text('Add latitude & longitude on your employee profile to see distance.');
        }
        return;
    }
    if (ATTENDANCE_OFFICE_CHECK.lat == null || ATTENDANCE_OFFICE_CHECK.lng == null) {
        return;
    }
    attendanceUpdateDistanceUI(parseFloat(elat), parseFloat(elng));
}
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyADZAncocVsQMiK8ebIDhli29nk5GWWydk&callback=init&libraries=places&v=weekly&channel=2"></script> 
<script>
 $(document).ready(function() {
     function geoThenSubmitAttendance($form, options) {
         var submitBtn = options.submitBtn;
         var displayLat = options.displayLat;
         var displayLng = options.displayLng;
         var hidLat = options.hidLat;
         var hidLng = options.hidLng;
         var hidAddr = options.hidAddr;
         var inlineError = options.inlineError || '';

         attendanceClearInlineError(inlineError);
         attendanceClearInlineError('#attendance-main-inline-error');

         function submitWithCoords(latStr, lngStr) {
             $(hidLat).val(latStr);
             $(hidLng).val(lngStr);
             if (displayLat) { $(displayLat).val(latStr); }
             if (displayLng) { $(displayLng).val(lngStr); }
             $.ajax({
                 url: 'ajax_files/ajax_attendance.php',
                 method: 'POST',
                 data: new FormData($form[0]),
                 contentType: false,
                 processData: false,
                 beforeSend: function () {},
                 success: function (data) {
                     if (String(data).trim() === '2') {
                         attendanceLocationError('Not eligible: office staff must have profile location within 100 m of the company.', inlineError);
                         $(submitBtn).prop('disabled', false).text(options.submitLabel || 'Submit');
                         return;
                     }
                     toastr.success('Attendance Successfully!', 'Success', { timeOut: 5000 });
                     setTimeout(function () {
                         window.location.href = 'attendance.php';
                     }, 2000);
                     $(submitBtn).prop('disabled', false).text(options.submitLabel || 'Submit');
                 },
                 error: function () {
                     toastr.error('Request failed. Try again.', 'Error');
                     $(submitBtn).prop('disabled', false).text(options.submitLabel || 'Submit');
                 }
             });
         }

         if (ATTENDANCE_OFFICE_CHECK.enforce && ATTENDANCE_OFFICE_CHECK.lat != null && ATTENDANCE_OFFICE_CHECK.lng != null) {
             var pLat = ATTENDANCE_OFFICE_CHECK.employeeProfileLat;
             var pLng = ATTENDANCE_OFFICE_CHECK.employeeProfileLng;
             if (pLat == null || pLng == null || isNaN(parseFloat(pLat)) || isNaN(parseFloat(pLng))) {
                 attendanceLocationError('Your profile has no latitude/longitude. Ask admin to update your employee record.', inlineError);
                 $(submitBtn).prop('disabled', false).text(options.submitLabel || 'Submit');
                 return;
             }
             var d = attendanceHaversineMeters(
                 parseFloat(pLat),
                 parseFloat(pLng),
                 parseFloat(ATTENDANCE_OFFICE_CHECK.lat),
                 parseFloat(ATTENDANCE_OFFICE_CHECK.lng)
             );
             if (d > ATTENDANCE_OFFICE_CHECK.radiusM) {
                 attendanceLocationError('Not eligible: your saved location is more than 100 m from the office. Update your profile coordinates after you are at the office.', inlineError);
                 $(submitBtn).prop('disabled', false).text(options.submitLabel || 'Submit');
                 return;
             }
             submitWithCoords(parseFloat(pLat).toFixed(7), parseFloat(pLng).toFixed(7));
             return;
         }

         if (!navigator.geolocation) {
             attendanceLocationError('Location is required. Enable GPS in your browser.', inlineError);
             $(submitBtn).prop('disabled', false).text(options.submitLabel || 'Submit');
             return;
         }
         navigator.geolocation.getCurrentPosition(
             function (pos) {
                 var lat = pos.coords.latitude.toFixed(7);
                 var lng = pos.coords.longitude.toFixed(7);
                 submitWithCoords(lat, lng);
             },
             function () {
                 attendanceLocationError('Unable to read your location. Enable GPS and allow access.', inlineError);
                 $(submitBtn).prop('disabled', false).text(options.submitLabel || 'Submit');
             },
             { enableHighAccuracy: true, timeout: 20000, maximumAge: 0 }
         );
     }

     $('#validation-form').on('submit', function (e) {
         e.preventDefault();
         $('#submit').attr('disabled', 'disabled').text('Please Wait...');
         geoThenSubmitAttendance($(this), {
             submitBtn: '#submit',
             submitLabel: 'Submit',
             displayLat: '#display_start_lat',
             displayLng: '#display_start_lng',
             hidLat: '#start_SourceLat',
             hidLng: '#start_SourceLong',
             hidAddr: '#start_SourceAddress',
             inlineError: '#start-attendance-inline-error'
         });
     });

     $('#validation-form2').on('submit', function (e) {
         e.preventDefault();
         $('#submit2').attr('disabled', 'disabled').text('Please Wait...');
         geoThenSubmitAttendance($(this), {
             submitBtn: '#submit2',
             submitLabel: 'Submit',
             displayLat: '#display_end_lat',
             displayLng: '#display_end_lng',
             hidLat: '#end_SourceLat',
             hidLng: '#end_SourceLong',
             hidAddr: '#end_SourceAddress',
             inlineError: '#end-attendance-inline-error'
         });
     });

     $('#myModal').on('show.bs.modal', function (e) {
         attendanceClearInlineError('#start-attendance-inline-error');
         attendanceClearInlineError('#attendance-main-inline-error');
         var msg = attendanceOfficeEligibilityMessage();
         if (msg) {
             e.preventDefault();
             attendanceLocationError(msg, '#attendance-main-inline-error');
         }
     });

     $('#myModal2').on('show.bs.modal', function (e) {
         attendanceClearInlineError('#end-attendance-inline-error');
         attendanceClearInlineError('#attendance-main-inline-error');
         var msg = attendanceOfficeEligibilityMessage();
         if (msg) {
             e.preventDefault();
             attendanceLocationError(msg, '#attendance-main-inline-error');
         }
     });

     attendanceApplyProfileLocationsFromDb();
 });
function checkToday(){
    var CurrDate = $('#CurrDate').val();
    var srno = $('#srno').val();
    var userid = $('#userid').val();
    var action = "checkToday";
            $.ajax({
                url: "ajax_files/ajax_attendance.php",
                method: "POST",
                data: {
                    action: action,
                    date: CurrDate,
                    userid:userid
                },
                success: function(data) {
                    //alert(data);
                    if(data == 1){
                    $('#Attendance'+srno).prop("checked",true);
                    $('#Attendance'+srno).attr("disabled",true);
                    }
                    else{
                     $('#Attendance'+srno).prop("checked",false);   
                     $('#Attendance'+srno).attr("disabled",false);
                    }
                    
                }
            });
}
    function takeAttendance(date,userid,id){
         if($('#Attendance'+id).prop('checked') == true) {
            $('#Attendance'+id).val(1);
        }
        else{
           $('#Attendance'+id).val(0);
            }
            var status = $('#Attendance'+id).val();
            var SourceLat = $('#SourceLat').val();
            var SourceLong = $('#SourceLong').val();
            var SourceAddress = $('#SourceAddress').val();
        var action = "takeAttendance";
            $.ajax({
                url: "ajax_files/ajax_attendance.php",
                method: "POST",
                data: {
                    action: action,
                    date: date,
                    userid:userid,
                    status:status,
                    SourceLat:SourceLat,
                    SourceLong:SourceLong,
                    SourceAddress:SourceAddress
                },
                success: function(data) {
                    //console.log(data);exit();
                    if(status == 1){
                    $('#Attendance'+id).prop("checked",true);
                    $('#Attendance'+id).attr("disabled",true);
                    }
                    else{
                     $('#Attendance'+id).prop("checked",false);   
                     $('#Attendance'+id).attr("disabled",false);
                    }
                    
                }
            });

       
    }
    
    
    
    var map;
var directionsDisplay;
var geocoder = new google.maps.Geocoder();
var infowindow = new google.maps.InfoWindow();
var marker;
var marker2;
function initMap() {
currentLocation();
}
    
    function init() {
initMap();
}
    
    function currentLocation(){
     var SourceLat = $('#SourceLat').val();
    var SourceLong = $('#SourceLong').val();

    var latlng = new google.maps.LatLng(SourceLat, SourceLong);
    // This is making the Geocode request
    var geocoder = new google.maps.Geocoder();
    geocoder.geocode({ 'latLng': latlng },  (results, status) =>{
        if (status !== google.maps.GeocoderStatus.OK) {
           // alert(status);
        }
        // This is checking to see if the Geoeode Status is OK before proceeding
        if (status == google.maps.GeocoderStatus.OK) {
            console.log(results);
            var address = (results[0].formatted_address);
            $('#SourceAddress').val(address);
        }
    });

     if (marker)
        marker.setMap(null);
var myLatlng = new google.maps.LatLng(SourceLat,SourceLong);
var mapOptions = {
zoom: 18,
center: myLatlng,
mapTypeId: google.maps.MapTypeId.ROADMAP,
disableDefaultUI: true,
};

 map = new google.maps.Map(document.getElementById("map"), mapOptions);

var iconBase = 'icons/Webp.net-gifmaker(14).gif';
marker = new google.maps.Marker({
map: map,
 icon: {
   url: iconBase,
   size: new google.maps.Size(20, 80),
   scaledSize: new google.maps.Size(20, 80),
   anchor: new google.maps.Point(0, 50)
  },
position: myLatlng,
animation: google.maps.Animation.DROP,
draggable: true 
}); 


google.maps.event.addListener(marker, 'dragend', function (event) {
    var lat = this.getPosition().lat();
     var lang = this.getPosition().lng();

     var latlng = new google.maps.LatLng(lat, lang);
    // This is making the Geocode request
    var geocoder = new google.maps.Geocoder();
    geocoder.geocode({ 'latLng': latlng },  (results, status) =>{
        if (status !== google.maps.GeocoderStatus.OK) {
            //alert(status);
        }
        // This is checking to see if the Geoeode Status is OK before proceeding
        if (status == google.maps.GeocoderStatus.OK) {
            //console.log(results);
            var address = (results[0].formatted_address);
             //alert(address);
             $('#origin-input2').val(address);
        }
    });
    
     $('#SourceLat').val(parseFloat(lat).toFixed(7));
        $('#SourceLong').val(parseFloat(lang).toFixed(7));
});
 marker.addListener("click", toggleBounce);
}
</script>

<script>
    // load this code when the document has loaded

    document.addEventListener('DOMContentLoaded', function() {

        var button = document.querySelector('#remove-button');
        if (!button) {
            return;
        }

        button.addEventListener('click', function() {

            // get the element with id 'my-cropper'

            var element = document.querySelector('#my-cropper');

            // find the cropper attached to the element

            var cropper = Slim.find(element);

            // call the remove method on the cropper

            cropper.remove();

        });

    });

    </script>

  <script>

    function handleImageRemoval(data) {

        // can't continue without server file name

        if (!data.server) { return; }

        // setup request and send

        var name = data.server.file;

        var url = 'example/async-remove.php';

        var xhr = new XMLHttpRequest();

        xhr.open('GET', url + (url.indexOf('?')===-1?'?':':') + 'name=' + name, true);

        xhr.send();

    }

    </script>
<script src="example/js/slim.kickstart.min.js"></script>
</body>

</html>
