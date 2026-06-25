<?php 
session_start();
include_once '../config.php';
include_once '../auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage="Product-Specification";
$Page = "Product-Specification";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | State</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="" />
<meta name="keywords" content="">
<meta name="author" content="" />
<link rel="icon" type="image/x-icon" href="<?php echo $SiteUrl;?>/assets/img/favicon.ico">
    <!-- Google fonts -->
<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
    <!-- Icon fonts -->
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/fontawesome.css">
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/ionicons.css">
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/linearicons.css">
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/feather.css">
    <!-- Core stylesheets -->
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/css/bootstrap-material.css">
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/css/shreerang-material.css">
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/css/uikit.css">
<!-- Libs -->
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/libs/perfect-scrollbar/perfect-scrollbar.css">
<link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/libs/datatables/datatables.css">
</head>
<body>

<div class="layout-wrapper layout-2">
<div class="layout-inner">

<?php include_once 'product-sidebar.php'; ?>


<div class="layout-container">

<?php include_once '../top_header.php'; ?>

<?php
$productSpecSaved = false;
$productSpecError = '';
$productSpecSyncMessage = '';

if (!isset($row7) || !is_array($row7)) {
    $row7 = [];
}
$specSel = function ($field) use ($row7) {
    return (string) ($_GET[$field] ?? $_POST[$field] ?? ($row7[$field] ?? ''));
};
if (isset($_GET['saved']) && (string) $_GET['saved'] === '1') {
    $productSpecSaved = true;
}
?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Product Specification
</h4><br>

<form id="validation-form" method="post" enctype="multipart/form-data">
<div class="card mb-4">
<div class="card-body">
<div id="alert_message"></div>
<?php if ($productSpecSaved) { ?>
<div class="alert alert-success">Product specification saved successfully.<?php if ($productSpecSyncMessage !== '') { ?> <?php echo htmlspecialchars($productSpecSyncMessage, ENT_QUOTES, 'UTF-8'); ?><?php } ?></div>
<?php } elseif ($productSpecError !== '') { ?>
<div class="alert alert-danger"><?php echo htmlspecialchars($productSpecError, ENT_QUOTES, 'UTF-8'); ?></div>
<?php } ?>

<div class="form-row">

<div class="form-group col-md-4">
      <label class="form-label">Gov Agency <span
                                                    class="text-danger">*</span></label>
<select class="form-control" id="AgencyId" name="AgencyId" required>
<option value="" selected disabled>Select Agency</option>
  <?php 
        $q = "select Fname,id from tbl_users WHERE Roll=11 ORDER BY Fname ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if((string)$specSel('AgencyId')===(string)$rw['id']){ ?> selected <?php } ?> 
                value="<?php echo $rw['id']; ?>"><?php echo $rw['Fname']; ?></option>
              <?php } ?>
</select>
  </div>

 <div class="form-group col-md-2">
                                            <label class="form-label">AC/DC <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-control" id="AcDc" name="AcDc" required>
                                               
                                                <option value="AC" <?php if($specSel('AcDc')=='AC') {?> selected
                                                    <?php } ?>>AC</option>
                                                <option value="DC" <?php if($specSel('AcDc')=='DC') {?> selected
                                                    <?php } ?>>DC</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3 Pump">
                                            <label class="form-label">Type Of Pump <span
                                                    class="text-danger">*</span></label>

                                            <select class="form-control" id="Surface" name="Surface" required>
<option selected="" value="">Select Type Of Pump</option>
  <?php 
        $q = "select * from tbl_common_master WHERE Status='1' AND Roll=4 ORDER BY Name ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($specSel('Surface')==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>

                                            
                                            <div class="clearfix"></div>
                                        </div>

                                         <div class="form-group col-md-3 Pump">
                                            <label class="form-label">Pump Outlet Size <span
                                                    class="text-danger">*</span></label>

                                            <select class="form-control" id="PumpOutletSize" name="PumpOutletSize" required>
<option selected="" value="">Select Pump Outlet Size</option>
  <?php 
        $q = "select * from tbl_common_master WHERE Status='1' AND Roll=12 ORDER BY Name ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($specSel('PumpOutletSize')==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>

                                            
                                            <div class="clearfix"></div>
                                        </div>
                                       
                                        <div class="form-group col-md-3 Pump">
                                            <label class="form-label">Capacity <span
                                                    class="text-danger">*</span></label>

                                            <select class="form-control" id="PumpCapacity" name="PumpCapacity" required>
<option selected="" value="">Select Pump Capacity</option>
  <?php 
        $q = "select * from tbl_common_master WHERE Status='1' AND Roll=2 ORDER BY Name ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($specSel('PumpCapacity')==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>

                                            
                                            <div class="clearfix"></div>
                                        </div>


                                        <div class="form-group col-md-3 Pump">
                                            <label class="form-label">Type Of Source <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-control" id="WaterSource" name="WaterSource" required>
<option selected="" value="">Select Type Of Source</option>
  <?php 
        $q = "select * from tbl_common_master WHERE Status='1' AND Roll=3 ORDER BY Name ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($specSel('WaterSource')==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>
                                           
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3 Pump">
                                            <label class="form-label">Bore Diameter </label>
                                            <select class="form-control" id="BoreDia" name="BoreDia">
<option selected="" value="">Select Bore Diameter</option>
  <?php 
        $q = "select * from tbl_common_master WHERE Status='1' AND Roll=7 ORDER BY id ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($specSel('BoreDia')==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>
                                           
                                            <div class="clearfix"></div>
                                        </div>


                                        <div class="form-group col-md-3 Pump">
                                            <label class="form-label">Pump Head <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-control" id="PumpHead" name="PumpHead" required>
<option selected="" value="">Select Pump Head</option>
  <?php 
        $q = "select * from tbl_common_master WHERE Status='1' AND Roll=1 ORDER BY Name ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($specSel('PumpHead')==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>
                                           
                                            <div class="clearfix"></div>
                                        </div>

</div>


</div>
</div>

<div class="card" style="padding: 10px;">
<div class="card-datatable table-responsive" id="custresult"></div>
<div class="mt-3">
    <button type="submit" name="submit" value="1" id="productSpecSubmit" class="btn btn-primary btn-finish" style="min-width: 120px;">Submit</button>
</div>
</div>

</form>
</div>

</div>
<?php include_once '../footer.php'; ?>
</div>
</div>
</div>
<div class="layout-overlay layout-sidenav-toggle"></div>
</div>


    <script src="<?php echo $SiteUrl;?>/assets/js/jquery.min.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/datatables.min.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/pace.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/sidenav.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/layout-helpers.js"></script>
    <!-- Libs -->
    <script src="<?php echo $SiteUrl;?>/assets/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <!-- Demo -->
    <script src="<?php echo $SiteUrl;?>/assets/js/demo.js"></script>
    <script>
    function getProdList(AcDc, Surface, PumpCapacity, WaterSource, BoreDia, PumpHead, AgencyId, PumpOutletSize) {
        $.ajax({
            type: 'POST',
            url: '../ajax_files/ajax_product_specification.php',
            data: {
                action: 'view',
                AcDc: AcDc || '',
                Surface: Surface || '',
                PumpCapacity: PumpCapacity || '',
                WaterSource: WaterSource || '',
                BoreDia: BoreDia || '',
                PumpHead: PumpHead || '',
                AgencyId: AgencyId || '',
                PumpOutletSize: PumpOutletSize || ''
            },
            success: function (data) {
                $('#custresult').html(data);
            }
        });
    }

    function productSpecFilterValues() {
        return {
            AcDc: document.getElementById('AcDc').value,
            Surface: document.getElementById('Surface').value,
            PumpCapacity: document.getElementById('PumpCapacity').value,
            WaterSource: document.getElementById('WaterSource').value,
            BoreDia: document.getElementById('BoreDia').value,
            PumpHead: document.getElementById('PumpHead').value,
            AgencyId: document.getElementById('AgencyId').value,
            PumpOutletSize: document.getElementById('PumpOutletSize').value
        };
    }

    function reloadProductSpecList() {
        var f = productSpecFilterValues();
        if (!f.AgencyId || !f.AcDc || !f.Surface || !f.PumpCapacity || !f.WaterSource || !f.PumpHead || !f.PumpOutletSize) {
            return;
        }
        getProdList(f.AcDc, f.Surface, f.PumpCapacity, f.WaterSource, f.BoreDia, f.PumpHead, f.AgencyId, f.PumpOutletSize);
    }

    $(document).on('change', '#AgencyId,#AcDc,#Surface,#PumpOutletSize,#PumpCapacity,#WaterSource,#BoreDia,#PumpHead', reloadProductSpecList);

    function showProductSpecAlert(type, message) {
        var cls = type === 'success' ? 'alert-success' : 'alert-danger';
        $('#alert_message').html('<div class="alert ' + cls + '">' + $('<div>').text(message).html() + '</div>');
        $('html, body').animate({ scrollTop: $('#alert_message').offset().top - 80 }, 200);
    }

    function collectProductSpecLines() {
        var lines = { ProdId: [], ProdName: [], Unit: [], Qty: [] };
        $('#custresult tbody tr').each(function () {
            var $tr = $(this);
            var prodId = ($tr.find('input[name="ProdId[]"]').val() || '').trim();
            if (!prodId) {
                return;
            }
            lines.ProdId.push(prodId);
            lines.ProdName.push($tr.find('input[name="ProdName[]"]').val() || '');
            lines.Unit.push($tr.find('input[name="Unit[]"]').val() || '');
            lines.Qty.push($tr.find('input[name="Qty[]"]').val() || '');
        });
        return lines;
    }

    function hasProductSpecQty(lines) {
        for (var i = 0; i < lines.Qty.length; i++) {
            var qty = (lines.Qty[i] || '').trim();
            if (qty !== '' && !isNaN(qty) && parseFloat(qty) > 0) {
                return true;
            }
        }
        return false;
    }

    $('#validation-form').on('submit', function (e) {
        e.preventDefault();

        var f = productSpecFilterValues();
        if (!f.AgencyId || !f.AcDc || !f.Surface || !f.PumpCapacity || !f.WaterSource || !f.PumpHead || !f.PumpOutletSize) {
            showProductSpecAlert('danger', 'Please fill all required dropdown fields before submit.');
            return;
        }

        var lines = collectProductSpecLines();
        if (lines.ProdId.length === 0) {
            showProductSpecAlert('danger', 'Product list not loaded. Wait for the table to load, then submit again.');
            return;
        }
        if (!hasProductSpecQty(lines)) {
            showProductSpecAlert('danger', 'Enter quantity (Qty) for at least one product row, then submit.');
            return;
        }

        var $btn = $('#productSpecSubmit');
        $btn.prop('disabled', true).text('Please wait...');

        $.ajax({
            type: 'POST',
            url: '../ajax_files/ajax_product_specification.php',
            dataType: 'json',
            data: {
                action: 'save',
                AcDc: f.AcDc,
                Surface: f.Surface,
                PumpCapacity: f.PumpCapacity,
                WaterSource: f.WaterSource,
                BoreDia: f.BoreDia,
                PumpHead: f.PumpHead,
                AgencyId: f.AgencyId,
                PumpOutletSize: f.PumpOutletSize,
                'ProdId[]': lines.ProdId,
                'ProdName[]': lines.ProdName,
                'Unit[]': lines.Unit,
                'Qty[]': lines.Qty
            },
            success: function (res) {
                if (res && res.ok) {
                    var msg = res.message || 'Product specification saved successfully.';
                    if (res.sync) {
                        msg += ' ' + res.sync;
                    }
                    showProductSpecAlert('success', msg);
                    reloadProductSpecList();
                } else {
                    showProductSpecAlert('danger', (res && res.error) ? res.error : 'Save failed. Please try again.');
                }
            },
            error: function (xhr) {
                var msg = 'Save failed. Please try again.';
                if (xhr.responseText) {
                    try {
                        var res = JSON.parse(xhr.responseText);
                        if (res.error) {
                            msg = res.error;
                        }
                    } catch (ignore) {}
                }
                showProductSpecAlert('danger', msg);
            },
            complete: function () {
                $btn.prop('disabled', false).text('Submit');
            }
        });
    });

    $(function () {
        reloadProductSpecList();
    });
    </script>

</body>
</html>
