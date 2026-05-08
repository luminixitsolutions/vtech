<?php 
session_start();
include_once '../config.php';
include_once '../auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage="Company";
$Page = "Add-Company";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">

<head>
    <title><?php echo $Proj_Title; ?> - <?php if($_GET['id']) {?>Edit <?php } else{?> Add <?php } ?> Company Account
    </title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="" />

    <?php include_once '../header_script.php'; ?>
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
    #companyMap {
        width: 100%;
        height: 320px;
        border-radius: 4px;
        border: 1px solid rgba(24, 28, 33, 0.12);
    }
    .address-field-wrap .address-input-shell {
        position: relative;
    }
    #addressPredictions {
        display: none;
        position: absolute;
        left: 0;
        right: 0;
        top: 100%;
        z-index: 1050;
        max-height: 260px;
        overflow-y: auto;
        margin-top: 2px;
        border: 1px solid rgba(24, 28, 33, 0.12);
        border-radius: 4px;
        background: #fff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
    }
    #addressPredictions .address-prediction {
        display: block;
        width: 100%;
        padding: 0.5rem 0.75rem;
        margin: 0;
        border: 0;
        border-bottom: 1px solid #eee;
        background: #fff;
        text-align: left;
        font-size: 0.9rem;
        cursor: pointer;
    }
    #addressPredictions .address-prediction:last-child {
        border-bottom: 0;
    }
    #addressPredictions .address-prediction:hover {
        background: #f5f5f9;
    }
    </style>
    <div class="layout-wrapper layout-2">
        <div class="layout-inner">

            <?php include_once 'account-sidebar.php'; ?>


            <div class="layout-container">

                <?php include_once '../top_header.php'; ?>

                <?php 
$id = $_GET['id'];
$sql7 = "SELECT * FROM tbl_users WHERE id='$id'";
$row7 = getRecord($sql7);

?>

                <div class="layout-content">

                    <div class="container-fluid flex-grow-1 container-p-y">
                        <h4 class="font-weight-bold py-3 mb-0"><?php if($_GET['id']) {?>Edit <?php } else{?> Add
                            <?php } ?> Company Account</h4>

                        <div class="card mb-4">
                            <div class="card-body">
                                <div id="alert_message"></div>
                                <form id="validation-form" method="post" autocomplete="off" action="../ajax_files/ajax_compnay.php" enctype="multipart/form-data">
                                    <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" id="userid">
                                    <input type="hidden" name="action" value="Save" id="action">
                                    <input type="hidden" name="Mname" value="<?php echo htmlspecialchars(isset($row7['Mname']) ? $row7['Mname'] : ''); ?>">
                                    <input type="hidden" name="Lname" value="<?php echo htmlspecialchars(isset($row7['Lname']) ? $row7['Lname'] : ''); ?>">
                                    <input type="hidden" name="CountryId" value="<?php echo htmlspecialchars(isset($row7['CountryId']) ? $row7['CountryId'] : '0'); ?>">
                                    <input type="hidden" name="StateId" value="<?php echo htmlspecialchars(isset($row7['StateId']) ? $row7['StateId'] : '0'); ?>">
                                    <input type="hidden" name="CityId" value="<?php echo htmlspecialchars(isset($row7['CityId']) ? $row7['CityId'] : '0'); ?>">
                                    <input type="hidden" name="Pincode" value="<?php echo htmlspecialchars(isset($row7['Pincode']) ? $row7['Pincode'] : ''); ?>">
                                    <input type="hidden" name="CatId" value="<?php echo htmlspecialchars(isset($row7['CatId']) ? $row7['CatId'] : '0'); ?>">
                                    <input type="hidden" name="Roll" value="10">
                                    <input type="hidden" name="OldPhoto2" value="<?php echo htmlspecialchars(isset($row7['Photo2']) ? $row7['Photo2'] : ''); ?>">
                                    <input type="hidden" name="OldPhoto3" value="<?php echo htmlspecialchars(isset($row7['Photo3']) ? $row7['Photo3'] : ''); ?>">
                                    <div class="form-row">
                                       
                                       <div class="form-group col-md-12">
                                            <label class="form-label">Company Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="Fname" id="Fname" class="form-control"
                                                placeholder="" value="<?php echo $row7["Fname"]; ?>"
                                                autocomplete="off">
                                        </div>

                                        <!-- <div class="form-group col-md-4">
                                            <label class="form-label">Middle Name</label>
                                            <input type="text" name="Mname" id="Mname" class="form-control"
                                                placeholder="" value="<?php echo $row7["Mname"]; ?>"
                                                autocomplete="off">
                                        </div>

                                         <div class="form-group col-md-4">
                                            <label class="form-label">Last Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="Lname" id="Lname" class="form-control"
                                                placeholder="" value="<?php echo $row7["Lname"]; ?>"
                                                autocomplete="off">
                                        </div>-->

                                        <div class="form-group col-md-4">
                                            <label class="form-label">Email Id </label>
                                            <input type="email" name="EmailId" id="EmailId" class="form-control"
                                                placeholder="Email Id" value="<?php echo $row7["EmailId"]; ?>"
                                                autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>
                                        <!--<div class="form-group col-md-6">
                                            <label class="form-label">Password <span
                                                    class="text-danger">*</span></label>
                                            <input type="password" name="Password" id="Password" class="form-control"
                                                placeholder="Password" value="<?php echo $row7["Password"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>-->
                                        <input type="hidden" name="Password" id="Password" class="form-control"
                                                placeholder="Password" value="12345">
                                        <div class="form-group col-md-4">
                                            <label class="form-label">Mobile No <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="Phone" id="Phone" class="form-control"
                                                placeholder="Mobile No" value="<?php echo $row7["Phone"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label class="form-label">Another Mobile No</label>
                                            <input type="text" name="Phone2" class="form-control"
                                                placeholder="Another Mobile No" value="<?php echo $row7["Phone2"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>


                                        <div class="form-group col-lg-12">
<label class="form-label">Details <span class="text-danger">*</span></label>
<textarea name="Details" class="form-control" id="editor1" placeholder="Details" required><?php echo $row7["Details"]; ?></textarea>
<div class="clearfix"></div>
</div>


                                        <div class="form-group col-md-12">
                                            <label class="form-label">Photo <span
                                                    class="text-danger">*</span></label>
                                            <label class="custom-file">
                                                <input type="file" class="custom-file-input" name="Photo"
                                                    style="opacity: 1;">
                                                <input type="hidden" name="OldPhoto"
                                                    value="<?php echo $row7['Photo'];?>" id="OldPhoto">
                                                <span class="custom-file-label"></span>
                                            </label>
                                            <?php if($row7['Photo']=='') {} else{?>
                                            <span id="show_photo">
                                                <div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a
                                                        href="javascript:void(0)"
                                                        class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white"
                                                        id="delete_photo"></a><img
                                                        src="../uploads/<?php echo $row7['Photo'];?>" alt=""
                                                        class="img-fluid ticket-file-img"
                                                        style="width: 64px;height: 64px;"></div>
                                            </span>
                                            <?php } ?>
                                        </div>

 <div class="form-group col-md-6">
                                            <label class="form-label">GST No</label>
                                            <input type="text" name="GstNo" class="form-control"
                                                placeholder="" value="<?php echo $row7["GstNo"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>

                                         <div class="form-group col-md-6">
                                            <label class="form-label">PAN No</label>
                                            <input type="text" name="PanNo" class="form-control"
                                                placeholder="" value="<?php echo $row7["PanNo"]; ?>">
                                            <div class="clearfix"></div>
                                        </div>
                                     
                                        <div class="form-group col-md-12 address-field-wrap">
                                            <label class="form-label">Address <span class="text-danger">*</span></label>
                                            <div class="address-input-shell">
                                                <textarea name="Address" id="Address" class="form-control" placeholder="Start typing for address suggestions…"
                                                autocomplete="off" required><?php echo $row7["Address"]; ?></textarea>
                                                <div id="addressPredictions" role="listbox" aria-label="Address suggestions"></div>
                                            </div>
                                            <small class="text-muted">Suggestions appear as you type; choose one to set the map pin and coordinates.</small>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label class="form-label">Latitude</label>
                                            <input type="text" name="Lattitude" id="Lattitude" class="form-control"
                                                placeholder="e.g. 19.0760"
                                                value="<?php echo $row7["Lattitude"]; ?>" autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label class="form-label">Longitude</label>
                                            <input type="text" name="Longitude" id="Longitude" class="form-control"
                                                placeholder="e.g. 72.8777"
                                                value="<?php echo $row7["Longitude"]; ?>" autocomplete="off">
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="form-group col-md-4 d-flex align-items-end">
                                            <button type="button" class="btn btn-secondary btn-block"
                                                id="btnGeocodeAddress">Fetch from address</button>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label class="form-label">Location on map</label>
                                            <div id="companyMap"></div>
                                            <small class="text-muted d-block mt-1">Drag the marker or click the map to set
                                                coordinates. The address field is filled from Google when you finish
                                                dragging.</small>
                                        </div>

                                       
                                       <div class="form-group col-md-6">
<label class="form-label">Bank Holder Name </label>
<input type="text" name="AccountName" id="AccountName" class="form-control" placeholder="" value="<?php echo $row7["AccountName"]; ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-6">
<label class="form-label">Bank Name </label>
<input type="text" name="BankName" id="BankName" class="form-control" placeholder="" value="<?php echo $row7["BankName"]; ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
<label class="form-label">Account No </label>
<input type="text" name="AccountNo" id="AccountNo" class="form-control" placeholder="" value="<?php echo $row7["AccountNo"]; ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
<label class="form-label">Branch </label>
<input type="text" name="Branch" id="Branch" class="form-control" placeholder="" value="<?php echo $row7["Branch"]; ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-4">
<label class="form-label">IFSC Code </label>
<input type="text" name="IfscCode" id="IfscCode" class="form-control" placeholder="" value="<?php echo $row7["IfscCode"]; ?>">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-6">
<label class="form-label">UPI ID </label>
<input type="text" name="UpiNo" id="UpiNo" class="form-control" placeholder="" value="<?php echo $row7["UpiNo"]; ?>">
<div class="clearfix"></div>
</div>


                                        <div class="form-group col-md-6">
                                            <label class="form-label">Status <span class="text-danger">*</span></label>
                                            <select class="form-control" id="Status" name="Status" required="">
                                                <option selected="" disabled="" value="">Select Status</option>
                                                <option value="1" <?php if($row7["Status"]=='1') {?> selected
                                                    <?php } ?>>Active</option>
                                                <option value="0" <?php if($row7["Status"]=='0') {?> selected
                                                    <?php } ?>>Inctive</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>


                                      

                                    </div>
                                    <!-- <button id="growl-default" class="btn btn-default">Default</button> -->
                                    <button type="submit" class="btn btn-primary btn-finish" id="submit">Save</button>
                                </form>
                            </div>
                        </div>






                    </div>


                    <?php include_once '../footer.php'; ?>
                </div>

            </div>

        </div>

        <div class="layout-overlay layout-sidenav-toggle"></div>
    </div>


    <?php include_once '../footer_script.php'; ?>

    <script type="text/javascript">
    function myFunction2() {

        var x = document.getElementById("Password");
        if (x.type === "password") {
            x.type = "text";
            $('.show2').html('<i class="fa fa-eye-slash" aria-hidden="true"></i>');
        } else {
            x.type = "password";
            $('.show2').html('<i class="fa fa-eye" aria-hidden="true"></i>');
        }
    }

    function error_toast() {
        var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
        $.growl.error({
            title: 'Error',
            message: 'Email Id / Phone No Already Exists',
            location: isRtl ? 'tl' : 'tr'
        });
    }

    function success_toast() {
        var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
        $.growl.success({
            title: 'Success',
            message: 'Saved Successfully...',
            location: isRtl ? 'tl' : 'tr'
        });
    }
    $(document).ready(function() {
        //$(document).on("click", ".btn-finish", function(event){
        $('#validation-form').on('submit', function(e) {
            e.preventDefault();
            if (typeof CKEDITOR !== 'undefined' && CKEDITOR.instances.editor1) {
                CKEDITOR.instances.editor1.updateElement();
            }
            if ($('#validation-form').valid()) {

                $.ajax({
                    url: "../ajax_files/ajax_compnay.php",
                    method: "POST",
                    data: new FormData(this),
                    dataType: 'text',
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('#submit').attr('disabled', 'disabled');
                        $('#submit').text('Please Wait...');
                    },
                    success: function(data) {
                        var r = $.trim(String(data).replace(/^\uFEFF/, ''));
                        if (r === '0') {
                            error_toast();
                        } else if (r === '1') {
                            success_toast();
                            setTimeout(function() {
                                window.location.href = 'view-company.php';
                            }, 2000);
                        } else {
                            var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
                            $.growl.error({
                                title: 'Error',
                                message: r === '-1' ? 'Could not save. Check required fields and try again.' : 'Unexpected response from server.',
                                location: isRtl ? 'tl' : 'tr'
                            });
                        }
                        $('#submit').attr('disabled', false);
                        $('#submit').text('Save');
                    },
                    error: function() {
                        var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
                        $.growl.error({
                            title: 'Error',
                            message: 'Request failed. Check your connection or try again.',
                            location: isRtl ? 'tl' : 'tr'
                        });
                        $('#submit').attr('disabled', false);
                        $('#submit').text('Save');
                    }
                })



            } else {
                //$('#Fname').focus();
                return false;
            }
        });

        $(document).on("click", "#delete_photo", function(event) {
            event.preventDefault();
            if (confirm("Are you sure you want to delete Profile Photo?")) {
                var action = "deletePhoto";
                var id = $('#userid').val();
                var Photo = $('#OldPhoto').val();
                $.ajax({
                    url: "../ajax_files/ajax_compnay.php",
                    method: "POST",
                    data: {
                        action: action,
                        id: id,
                        Photo: Photo
                    },
                    success: function(data) {

                        $('#show_photo').hide();
                        var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr(
                            'dir') === 'rtl';
                        $.growl.success({
                            title: 'Success',
                            message: data,
                            location: isRtl ? 'tl' : 'tr'
                        });

                    }
                });
            }

        });
        $(document).on("change", "#CountryId", function(event) {
            var val = this.value;
            var action = "getState";
            $.ajax({
                url: "../ajax_files/ajax_dropdown.php",
                method: "POST",
                data: {
                    action: action,
                    id: val
                },
                success: function(data) {
                    $('#StateId').html(data);
                }
            });

        });

        $(document).on("change", "#StateId", function(event) {
            var val = this.value;
            var action = "getCity";
            $.ajax({
                url: "../ajax_files/ajax_dropdown.php",
                method: "POST",
                data: {
                    action: action,
                    id: val
                },
                success: function(data) {
                    $('#CityId').html(data);
                }
            });

        });
    });
    </script>
    <script type="text/javascript">
        var companyMapObj, companyMarkerObj, companyGeocoderObj;

        function initCompanyMap() {
            companyGeocoderObj = new google.maps.Geocoder();
            var latEl = document.getElementById('Lattitude');
            var lngEl = document.getElementById('Longitude');
            var latVal = parseFloat(latEl.value);
            var lngVal = parseFloat(lngEl.value);
            var hasCoords = latEl.value !== '' && lngEl.value !== '' && !isNaN(latVal) && !isNaN(lngVal);
            var defaultCenter = {
                lat: hasCoords ? latVal : 20.5937,
                lng: hasCoords ? lngVal : 78.9629
            };

            companyMapObj = new google.maps.Map(document.getElementById('companyMap'), {
                center: defaultCenter,
                zoom: hasCoords ? 16 : 5,
                mapTypeControl: true
            });

            var autocompleteService = new google.maps.places.AutocompleteService();
            var placesService = new google.maps.places.PlacesService(companyMapObj);
            var addressSessionToken = new google.maps.places.AutocompleteSessionToken();
            var addressPredictTimer;

            function newAddressSession() {
                addressSessionToken = new google.maps.places.AutocompleteSessionToken();
            }

            function hideAddressPredictions() {
                var box = document.getElementById('addressPredictions');
                box.innerHTML = '';
                box.style.display = 'none';
            }

            function applyLatLng(latLng, skipReverseGeocode) {
                latEl.value = latLng.lat().toFixed(7);
                lngEl.value = latLng.lng().toFixed(7);
                if (skipReverseGeocode) {
                    return;
                }
                companyGeocoderObj.geocode({
                    location: latLng
                }, function(results, status) {
                    if (status === 'OK' && results[0]) {
                        document.getElementById('Address').value = results[0].formatted_address;
                    }
                });
            }

            function ensureMarkerAt(latLng, skipReverseGeocode) {
                if (!companyMarkerObj) {
                    companyMarkerObj = new google.maps.Marker({
                        map: companyMapObj,
                        position: latLng,
                        draggable: true
                    });
                    companyMarkerObj.addListener('dragend', function(e) {
                        applyLatLng(e.latLng, false);
                    });
                } else {
                    companyMarkerObj.setPosition(latLng);
                }
                companyMapObj.panTo(latLng);
                applyLatLng(latLng, !!skipReverseGeocode);
            }

            if (hasCoords) {
                companyMarkerObj = new google.maps.Marker({
                    map: companyMapObj,
                    position: defaultCenter,
                    draggable: true
                });
                companyMarkerObj.addListener('dragend', function(e) {
                    applyLatLng(e.latLng, false);
                });
            }

            companyMapObj.addListener('click', function(e) {
                ensureMarkerAt(e.latLng, false);
            });

            $('#Address').on('blur', function() {
                setTimeout(hideAddressPredictions, 220);
            });

            $('#Address').on('input', function() {
                clearTimeout(addressPredictTimer);
                var q = $.trim($(this).val());
                if (q.length < 3) {
                    hideAddressPredictions();
                    return;
                }
                addressPredictTimer = setTimeout(function() {
                    autocompleteService.getPlacePredictions({
                        input: q,
                        sessionToken: addressSessionToken
                    }, function(predictions, status) {
                        var box = document.getElementById('addressPredictions');
                        if (status !== google.maps.places.PlacesServiceStatus.OK || !predictions ||
                            !predictions.length) {
                            box.innerHTML = '';
                            box.style.display = 'none';
                            return;
                        }
                        box.innerHTML = '';
                        predictions.slice(0, 8).forEach(function(p) {
                            var btn = document.createElement('button');
                            btn.type = 'button';
                            btn.className = 'address-prediction';
                            btn.setAttribute('data-place-id', p.place_id);
                            btn.textContent = p.description;
                            box.appendChild(btn);
                        });
                        box.style.display = 'block';
                    });
                }, 320);
            });

            $('#addressPredictions').on('mousedown', '.address-prediction', function(e) {
                e.preventDefault();
                var placeId = this.getAttribute('data-place-id');
                if (!placeId) {
                    return;
                }
                placesService.getDetails({
                    placeId: placeId,
                    fields: ['geometry', 'formatted_address'],
                    sessionToken: addressSessionToken
                }, function(place, status) {
                    newAddressSession();
                    hideAddressPredictions();
                    if (status === google.maps.places.PlacesServiceStatus.OK && place &&
                        place.geometry && place.geometry.location) {
                        if (place.formatted_address) {
                            document.getElementById('Address').value = place.formatted_address;
                        }
                        ensureMarkerAt(place.geometry.location, true);
                        companyMapObj.setZoom(17);
                    } else {
                        var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
                        $.growl.error({
                            title: 'Places',
                            message: 'Could not load details for that place.',
                            location: isRtl ? 'tl' : 'tr'
                        });
                    }
                });
            });

            $('#btnGeocodeAddress').on('click', function() {
                var addr = $.trim($('#Address').val());
                if (!addr) {
                    var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
                    $.growl.warning({
                        title: 'Address',
                        message: 'Enter an address first, then click Fetch from address.',
                        location: isRtl ? 'tl' : 'tr'
                    });
                    return;
                }
                newAddressSession();
                companyGeocoderObj.geocode({
                    address: addr
                }, function(results, status) {
                    if (status === 'OK' && results[0]) {
                        var loc = results[0].geometry.location;
                        ensureMarkerAt(loc, false);
                        if (results[0].formatted_address) {
                            document.getElementById('Address').value = results[0].formatted_address;
                        }
                        companyMapObj.setZoom(16);
                    } else {
                        var isRtl2 = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
                        $.growl.error({
                            title: 'Geocode',
                            message: 'Could not find that address on the map.',
                            location: isRtl2 ? 'tl' : 'tr'
                        });
                    }
                });
            });
        }
    </script>
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?libraries=places&amp;key=AIzaSyADZAncocVsQMiK8ebIDhli29nk5GWWydk&amp;callback=initCompanyMap"></script>
     <script>
        CKEDITOR.replace( 'editor1');
</script>
</body>

</html>