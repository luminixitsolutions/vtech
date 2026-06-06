<?php 
session_start();
include_once '../config.php';
include_once '../auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage="Structure-Product-Specification";
$Page = "Structure-Product-Specification";
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
$structSpecSaved = false;
$structSpecError = '';

if (isset($_POST['submit'])) {
    $AcDc = $conn->real_escape_string(trim((string) ($_POST['AcDc'] ?? '')));
    $Surface = $conn->real_escape_string(trim((string) ($_POST['Surface'] ?? '')));
    $PumpCapacity = $conn->real_escape_string(trim((string) ($_POST['PumpCapacity'] ?? '')));
    $ModuleWatt = $conn->real_escape_string(trim((string) ($_POST['ModuleWatt'] ?? '')));
    $ModuleQty = $conn->real_escape_string(trim((string) ($_POST['ModuleQty'] ?? '')));
    $Structure = $conn->real_escape_string(trim((string) ($_POST['Structure'] ?? '')));
    $ModuleMake = $conn->real_escape_string(trim((string) ($_POST['ModuleMake'] ?? '')));
    $StructureMake = $conn->real_escape_string(trim((string) ($_POST['StructureMake'] ?? '')));
    $AgencyId = $conn->real_escape_string(trim((string) ($_POST['AgencyId'] ?? '')));
    $SchemeId = $conn->real_escape_string(trim((string) ($_POST['SchemeId'] ?? '')));
    $CreatedDate = date('Y-m-d');

    if ($Surface === '' || $PumpCapacity === '' || $ModuleWatt === '' || $ModuleQty === '' || $Structure === '' || $ModuleMake === '' || $StructureMake === '' || $AgencyId === '' || $SchemeId === '') {
        $structSpecError = 'Please fill all required dropdown fields before submit.';
    } elseif (empty($_POST['ProdId']) || !is_array($_POST['ProdId'])) {
        $structSpecError = 'Product list not loaded. Change any filter to reload the table, then submit again.';
    } else {
        $sql = "DELETE FROM tbl_struct_product_specification WHERE Surface='$Surface' AND PumpCapacity='$PumpCapacity' AND ModuleWatt='$ModuleWatt' AND ModuleQty='$ModuleQty' AND Structure='$Structure' AND ModuleMake='$ModuleMake' AND StructureMake='$StructureMake' AND AgencyId='$AgencyId' AND SchemeId='$SchemeId'";
        if ($AcDc !== '') {
            $sql .= " AND AcDc='$AcDc'";
        }
        $conn->query($sql);

        $savedCount = 0;
        $number = count($_POST['ProdId']);
        for ($i = 0; $i < $number; $i++) {
            $prodId = trim((string) ($_POST['ProdId'][$i] ?? ''));
            $qty = trim((string) ($_POST['Qty'][$i] ?? ''));
            if ($prodId === '' || $qty === '' || !is_numeric($qty) || (float) $qty <= 0) {
                continue;
            }
            $ProdName = $conn->real_escape_string((string) ($_POST['ProdName'][$i] ?? ''));
            $Unit = $conn->real_escape_string((string) ($_POST['Unit'][$i] ?? ''));
            $Qty = $conn->real_escape_string($qty);

            $sql = "INSERT INTO tbl_struct_product_specification SET AcDc='$AcDc',Surface='$Surface',PumpCapacity='$PumpCapacity',ModuleWatt='$ModuleWatt',ModuleQty='$ModuleQty',Structure='$Structure',ProdId='$prodId',ProdName='$ProdName',Unit='$Unit',Qty='$Qty',CreatedBy='$user_id',CreatedDate='$CreatedDate',ModuleMake='$ModuleMake',StructureMake='$StructureMake',AgencyId='$AgencyId',SchemeId='$SchemeId'";
            if ($conn->query($sql)) {
                $savedCount++;
            } elseif ($structSpecError === '') {
                $structSpecError = 'Database error: ' . $conn->error;
            }
        }
        if ($savedCount > 0) {
            $structSpecSaved = true;
            $structSpecError = '';
        } elseif ($structSpecError === '') {
            $structSpecError = 'Enter quantity (Qty) for at least one product row, then submit.';
        }
    }
}

if (!isset($row7) || !is_array($row7)) {
    $row7 = [];
}
$structSel = function ($field) use ($row7) {
    return (string) ($_POST[$field] ?? ($row7[$field] ?? ''));
};
?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Structure Product Specification
</h4><br>

<form id="validation-form" method="post" enctype="multipart/form-data">
<div class="card mb-4">
<div class="card-body">
<div id="alert_message"></div>
<?php if ($structSpecSaved) { ?>
<div class="alert alert-success">Structure product specification saved successfully.</div>
<?php } elseif ($structSpecError !== '') { ?>
<div class="alert alert-danger"><?php echo htmlspecialchars($structSpecError, ENT_QUOTES, 'UTF-8'); ?></div>
<?php } ?>

<div class="form-row">


<div class="form-group col-md-4">
      <label class="form-label">Gov Agency <span class="text-danger">*</span></label>
<select class="form-control" id="AgencyId" name="AgencyId" onchange="getProdList(document.getElementById('Surface').value,document.getElementById('PumpCapacity').value,document.getElementById('ModuleWatt').value,document.getElementById('ModuleQty').value,document.getElementById('Structure').value,document.getElementById('ModuleMake').value,document.getElementById('StructureMake').value,document.getElementById('AgencyId').value,document.getElementById('SchemeId').value)" required>
<option selected="" disabled="">Select Agency</option>
  <?php 
 $StateId = $row7['StateId'];
        $q = "select Fname,id from tbl_users WHERE Roll=11 ORDER BY Fname ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($structSel('AgencyId')==$rw['id']){ ?> selected <?php } ?> 
                value="<?php echo $rw['id']; ?>"><?php echo $rw['Fname']; ?></option>
              <?php } ?>
</select>
  </div>
                      
                       
    
  <div class="form-group col-md-4">
      <label class="form-label">Yojana  <span class="text-danger">*</span></label>
<select class="form-control" id="SchemeId" name="SchemeId" required onchange="getProdList(document.getElementById('Surface').value,document.getElementById('PumpCapacity').value,document.getElementById('ModuleWatt').value,document.getElementById('ModuleQty').value,document.getElementById('Structure').value,document.getElementById('ModuleMake').value,document.getElementById('StructureMake').value,document.getElementById('AgencyId').value,document.getElementById('SchemeId').value)">
<option selected="" disabled="">Select Yojana</option>
  <?php 
 $StateId = $row7['StateId'];
        $q = "select * from tbl_scheme WHERE Status='1' ORDER BY Name ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($structSel('SchemeId')==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>
  </div>


                                        <div class="form-group col-md-4 Pump">
                                            <label class="form-label">Type Of Pump <span class="text-danger">*</span></label>

                                            <select class="form-control" id="Surface" name="Surface" onchange="getProdList(document.getElementById('Surface').value,document.getElementById('PumpCapacity').value,document.getElementById('ModuleWatt').value,document.getElementById('ModuleQty').value,document.getElementById('Structure').value,document.getElementById('ModuleMake').value,document.getElementById('StructureMake').value,document.getElementById('AgencyId').value,document.getElementById('SchemeId').value)" required>
<option selected="" disabled="" value="">Select Type Of Pump</option>
  <?php 
        $q = "select * from tbl_common_master WHERE Status='1' AND Roll=4 ORDER BY Name ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($structSel('Surface')==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>

                                            
                                            <div class="clearfix"></div>
                                        </div>
                                       
                                        <div class="form-group col-md-3 Pump">
                                            <label class="form-label">Capacity <span class="text-danger">*</span></label>

                                            <select class="form-control" id="PumpCapacity" name="PumpCapacity" onchange="getProdList(document.getElementById('Surface').value,document.getElementById('PumpCapacity').value,document.getElementById('ModuleWatt').value,document.getElementById('ModuleQty').value,document.getElementById('Structure').value,document.getElementById('ModuleMake').value,document.getElementById('StructureMake').value,document.getElementById('AgencyId').value,document.getElementById('SchemeId').value)" required>
<option selected="" disabled="" value="">Select Pump Capacity</option>
  <?php 
        $q = "select * from tbl_common_master WHERE Status='1' AND Roll=2 ORDER BY Name ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($structSel('PumpCapacity')==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>

                                            
                                            <div class="clearfix"></div>
                                        </div>


                                        <div class="form-group col-md-3 Pump">
                                            <label class="form-label">Module Watt <span class="text-danger">*</span></label>
                                            <select class="form-control" id="ModuleWatt" name="ModuleWatt" onchange="getProdList(document.getElementById('Surface').value,document.getElementById('PumpCapacity').value,document.getElementById('ModuleWatt').value,document.getElementById('ModuleQty').value,document.getElementById('Structure').value,document.getElementById('ModuleMake').value,document.getElementById('StructureMake').value,document.getElementById('AgencyId').value,document.getElementById('SchemeId').value)" required>
<option selected="" disabled="" value="">Select Module Watt</option>
  <?php 
        $q = "select * from tbl_common_master WHERE Status='1' AND Roll=15 ORDER BY Name ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($structSel('ModuleWatt')==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>
                                           
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3 Pump">
                                            <label class="form-label">Module Qty <span class="text-danger">*</span></label>
                                            <select class="form-control" id="ModuleQty" name="ModuleQty" onchange="getProdList(document.getElementById('Surface').value,document.getElementById('PumpCapacity').value,document.getElementById('ModuleWatt').value,document.getElementById('ModuleQty').value,document.getElementById('Structure').value,document.getElementById('ModuleMake').value,document.getElementById('StructureMake').value,document.getElementById('AgencyId').value,document.getElementById('SchemeId').value)" required>
<option selected="" disabled="" value="">Select Module Qty</option>
  <?php 
        $q = "select * from tbl_common_master WHERE Status='1' AND Roll=16 ORDER BY id ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($structSel('ModuleQty')==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>
                                           
                                            <div class="clearfix"></div>
                                        </div>


                                        <div class="form-group col-md-3 Pump">
                                            <label class="form-label">Structure <span class="text-danger">*</span></label>
                                            <select class="form-control" id="Structure" name="Structure" onchange="getProdList(document.getElementById('Surface').value,document.getElementById('PumpCapacity').value,document.getElementById('ModuleWatt').value,document.getElementById('ModuleQty').value,document.getElementById('Structure').value,document.getElementById('ModuleMake').value,document.getElementById('StructureMake').value,document.getElementById('AgencyId').value,document.getElementById('SchemeId').value)" required>
<option selected="" disabled="" value="">Select Structure</option>
  <?php 
        $q = "select * from tbl_common_master WHERE Status='1' AND Roll=17 ORDER BY Name ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($structSel('Structure')==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>
                                           
                                            <div class="clearfix"></div>
                                        </div>

                                         <div class="form-group col-md-3 Pump">
                                            <label class="form-label">Module Make <span class="text-danger">*</span></label>
                                            <select class="form-control" id="ModuleMake" name="ModuleMake" onchange="getProdList(document.getElementById('Surface').value,document.getElementById('PumpCapacity').value,document.getElementById('ModuleWatt').value,document.getElementById('ModuleQty').value,document.getElementById('Structure').value,document.getElementById('ModuleMake').value,document.getElementById('StructureMake').value,document.getElementById('AgencyId').value,document.getElementById('SchemeId').value)" required>
<option selected="" disabled="" value="">Select Module Make</option>
  <?php 
        $q = "select * from tbl_common_master WHERE Status='1' AND Roll=22 ORDER BY Name ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($structSel('ModuleMake')==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>
                                           
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3 Pump">
                                            <label class="form-label">Structure Make <span class="text-danger">*</span></label>
                                            <select class="form-control" id="StructureMake" name="StructureMake" onchange="getProdList(document.getElementById('Surface').value,document.getElementById('PumpCapacity').value,document.getElementById('ModuleWatt').value,document.getElementById('ModuleQty').value,document.getElementById('Structure').value,document.getElementById('ModuleMake').value,document.getElementById('StructureMake').value,document.getElementById('AgencyId').value,document.getElementById('SchemeId').value)" required>
<option selected="" disabled="" value="">Select Structure Make</option>
  <?php 
        $q = "select * from tbl_common_master WHERE Status='1' AND Roll=23 ORDER BY Name ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($structSel('StructureMake')==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>
                                           
                                            <div class="clearfix"></div>
                                        </div>


</div>

</div>
</div>

<div class="card" style="padding: 10px;">
<div class="mb-2">
    <input type="text" id="structProdSearch" class="form-control" placeholder="Search product name..." style="max-width:320px;">
</div>
<div class="card-datatable table-responsive" id="custresult"></div>
<div class="mt-3">
    <button type="submit" name="submit" value="1" id="structSpecSubmit" class="btn btn-primary btn-finish" style="min-width: 120px;">Submit</button>
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
    function getProdList(Surface, PumpCapacity, ModuleWatt, ModuleQty, Structure, ModuleMake, StructureMake, AgencyId, SchemeId) {
        $.ajax({
            type: 'POST',
            url: '../ajax_files/ajax_struct_product_specification.php',
            data: {
                action: 'view',
                Surface: Surface || '',
                PumpCapacity: PumpCapacity || '',
                ModuleWatt: ModuleWatt || '',
                ModuleQty: ModuleQty || '',
                Structure: Structure || '',
                ModuleMake: ModuleMake || '',
                StructureMake: StructureMake || '',
                AgencyId: AgencyId || '',
                SchemeId: SchemeId || ''
            },
            success: function (data) {
                $('#custresult').html(data);
            }
        });
    }

    function structSpecFilterValues() {
        return {
            Surface: document.getElementById('Surface').value,
            PumpCapacity: document.getElementById('PumpCapacity').value,
            ModuleWatt: document.getElementById('ModuleWatt').value,
            ModuleQty: document.getElementById('ModuleQty').value,
            Structure: document.getElementById('Structure').value,
            ModuleMake: document.getElementById('ModuleMake').value,
            StructureMake: document.getElementById('StructureMake').value,
            AgencyId: document.getElementById('AgencyId').value,
            SchemeId: document.getElementById('SchemeId').value
        };
    }

    function reloadStructSpecList() {
        var f = structSpecFilterValues();
        if (!f.AgencyId || !f.SchemeId || !f.Surface || !f.PumpCapacity || !f.ModuleWatt || !f.ModuleQty || !f.Structure || !f.ModuleMake || !f.StructureMake) {
            return;
        }
        getProdList(f.Surface, f.PumpCapacity, f.ModuleWatt, f.ModuleQty, f.Structure, f.ModuleMake, f.StructureMake, f.AgencyId, f.SchemeId);
    }

    $(document).on('change', '#AgencyId,#SchemeId,#Surface,#PumpCapacity,#ModuleWatt,#ModuleQty,#Structure,#ModuleMake,#StructureMake', reloadStructSpecList);

    $(document).on('keyup', '#structProdSearch', function () {
        var q = $(this).val().toLowerCase();
        $('#custresult tbody tr').each(function () {
            var name = $(this).find('td').eq(1).text().toLowerCase();
            $(this).toggle(name.indexOf(q) !== -1);
        });
    });

    $('#validation-form').on('submit', function () {
        if ($.fn.DataTable && $.fn.DataTable.isDataTable('#example')) {
            $('#example').DataTable().destroy();
        }
        $('#structSpecDynamicFields').remove();
        var $holder = $('<div id="structSpecDynamicFields" style="display:none"></div>').appendTo(this);
        $('#custresult tbody tr').each(function () {
            var $tr = $(this);
            var prodId = $tr.find('input[name="ProdId[]"]').val();
            if (!prodId) {
                return;
            }
            $holder.append($('<input type="hidden" name="ProdId[]">').val(prodId));
            $holder.append($('<input type="hidden" name="ProdName[]">').val($tr.find('input[name="ProdName[]"]').val() || ''));
            $holder.append($('<input type="hidden" name="Unit[]">').val($tr.find('input[name="Unit[]"]').val() || ''));
            $holder.append($('<input type="hidden" name="Qty[]">').val($tr.find('input[name="Qty[]"]').val() || ''));
        });
        $('#custresult tbody input').prop('disabled', true);
    });

    $(function () {
        <?php if ($structSpecSaved || $structSpecError !== '' || !empty($_POST['submit'])) { ?>
        reloadStructSpecList();
        <?php } ?>
    });
</script>

</body>
</html>
