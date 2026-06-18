<?php
session_start();
include_once 'config.php';
require_once "exe-database.php";
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Installation";
$Page = "Assign-Coordinator";

installationWorkflowBootstrap();
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">

<head>
    <title><?php echo $Proj_Title; ?> | Assign Coordinator</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="" />
    <?php include_once 'header_script.php'; ?>
</head>

<body>

    <div class="layout-wrapper layout-2">
        <div class="layout-inner">

            <?php include_once 'sidebar.php'; ?>


            <div class="layout-container">

                <?php include_once 'top_header.php'; ?>

          <?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {

    installationWorkflowBootstrap();

    $CoordinatorId = intval($_POST['CoordinatorId']);
    $adminId = $_SESSION['Admin']['id'];
    $assigned = 0;
    $skipped = 0;
    $errors = [];

    if ($CoordinatorId <= 0) {
        echo "<script>
            alert('Please select Coordinator');
            window.location.href='pending-installations.php';
        </script>";
        exit;
    }

    if (!empty($_POST['selected_sell_ids'])) {

        foreach ($_POST['selected_sell_ids'] as $sellId) {

            $result = installationWorkflowAssignCoordinator((int) $sellId, $CoordinatorId, $adminId);
            if (!empty($result['ok'])) {
                $assigned++;
            } else {
                $skipped++;
                if (!empty($result['message'])) {
                    $errors[] = $result['message'];
                }
            }
        }
    }

    $msg = $assigned . ' site(s) assigned successfully.';
    if ($skipped > 0) {
        $msg .= ' ' . $skipped . ' skipped.';
    }
    $msgEsc = addslashes($msg);

    echo "<script>
        alert('$msgEsc');
        window.location.href='pending-installations.php';
    </script>";
    exit;
}
?>







                <div class="layout-content">

                    <div class="container-fluid flex-grow-1 container-p-y">
                        <h4 class="font-weight-bold py-3 mb-0">Pending Installation Site & Assign Beneficiary To Co-Ordinator
                            <!-- <span style="float: right;">
<a href="add-sell.php" class="btn btn-secondary btn-round"><i class="ion ion-md-add mr-2"></i> Add New Sell</a></span> -->
                        </h4>

                        <div class="card" style="padding: 10px;">
                            
                             <form id="validation-form" method="post" enctype="multipart/form-data" action="">
                                                <div class="form-row">


                                                  

                                                     <div class="form-group col-lg-4">
                                                        <label class="form-label"> Store<span class="text-danger">*</span></label>
                                                        <select class="select2-demo form-control" name="StoreInchId2" id="StoreInchId2">
                                                            <option selected="" value="">Select</option>
                                                            <?php
                                                            $sql12 = "SELECT * FROM tbl_branch WHERE Status='1'";
                                                            $row12 = getList($sql12);
                                                            foreach ($row12 as $result) {
                                                            ?>
                                                                <option value="<?php echo $result['id']; ?>" <?php if($_REQUEST['StoreInchId2']==$result['id']){ ?> selected <?php } ?>>
                                                                    <?php echo $result['Name']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <div class="clearfix"></div>
                                                    </div>
                                                    
                                                    
                                                    <div class="form-group col-md-2">
                                                        <label class="form-label">Pump Capacity </label>
                                                        <select class="form-control" id="PumpCapacity" name="PumpCapacity">
                                                        <option value="all" selected>All</option>
                                                        
  <?php 
        $q = "select * from tbl_common_master WHERE Status='1' AND Roll=2 ORDER BY id ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($_REQUEST['PumpCapacity']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?></select>
                                                    </div>

                                                    <div class="form-group col-md-2">
		<label class="form-label">State <span class="text-danger">*</span></label>
<select class="select2-demo form-control" id="StateId" name="StateId">
<option selected="" value="all">All State</option>
 <?php 
        $q = "select * from tbl_state WHERE CountryId='1' ORDER BY Name ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($_REQUEST['StateId']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>
	</div>

<!--<div class="form-group col-md-2">
		<label class="form-label">Village <span class="text-danger">*</span></label>
<select class="form-control" id="Village" name="Village">
<option selected="" value="all">All Village</option>
 <?php 
        $q = "select DISTINCT(Village) AS Village from tbl_users WHERE Village!='' AND ProjectType=1";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($_REQUEST['Village']==$rw['Village']){ ?> selected <?php } ?> value="<?php echo $rw['Village']; ?>"><?php echo $rw['Village']; ?></option>
              <?php } ?>
</select>
	</div>-->
	
	<div class="form-group col-md-2">
		<label class="form-label">District <span class="text-danger">*</span></label>
<select class="select2-demo form-control" id="District" name="District">
<option selected="" value="all">All District</option>
 <?php 
        $q = "select DISTINCT(District) AS District from tbl_users WHERE District!='' AND ProjectType=1";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($_REQUEST['District']==$rw['District']){ ?> selected <?php } ?> value="<?php echo $rw['District']; ?>"><?php echo $rw['District']; ?></option>
              <?php } ?>
</select>
	</div>
	
	<div class="form-group col-md-2">
		<label class="form-label">Taluka <span class="text-danger">*</span></label>
<select class="select2-demo form-control" id="Taluka" name="Taluka">
<option selected="" value="all">All Taluka</option>
 <?php 
        $q = "select DISTINCT(Taluka) AS Taluka from tbl_users WHERE Taluka!='' AND ProjectType=1";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($_REQUEST['Taluka']==$rw['Taluka']){ ?> selected <?php } ?> value="<?php echo $rw['Taluka']; ?>"><?php echo $rw['Taluka']; ?></option>
              <?php } ?>
</select>
	</div>

    <div class="form-group col-md-2">
                                                        <label class="form-label">Assign Status </label>
                                                        <select class="form-control" id="StoreInchStatus" name="StoreInchStatus">
                                                        <?php $assignStatus = isset($_REQUEST['StoreInchStatus']) ? (string) $_REQUEST['StoreInchStatus'] : 'all'; ?>
                                                        <option value="all" <?php if ($assignStatus === 'all') { ?> selected <?php } ?>>All</option>
                                    <option value="1" <?php if ($assignStatus === '1') { ?> selected <?php } ?>>Assign</option>
                                    <option value="0" <?php if ($assignStatus === '0') { ?> selected <?php } ?>>Not Assign</option>
                                                          
                                                        </select>
                                                    </div>
	
	
	
                                                    <input type="hidden" id="Search" value="Search">
                                                    <div class="form-group col-md-1" style="padding-top:20px;">
                                                        <button type="button" onclick="search()" class="btn btn-primary btn-finish">Search</button>
                                                    </div>
                                                    <?php if (isset($_REQUEST['Search'])) { ?>
                                                        <div class="form-group col-md-1">
                                                            <label class="form-label">&nbsp;</label>
                                                            <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-info btn-block" data-toggle="tooltip" data-placement="top" data-original-title="Clear Filter">X</a>
                                                        </div>
                                                    <?php } ?>
                                                </div>

                                            </form>
                                            
                            <form id="validation-form" method="post" enctype="multipart/form-data" action="">
                              <input type="hidden" name="selected_ids_combined" id="selected_ids_combined" />
                                <div class="card-datatable table-responsive">
                                    
                                    <?php
$i = 1;
$showResults = isset($_REQUEST['Search']);

if ($showResults) {

$sql = "
SELECT 
    ts.id,
    ts.CustId,
    ts.CreatedDate,
    u.id AS UserId,
    u.Fname,
    u.Phone,
    u.Address,
    u.BeneficiaryId,
    u.PumpCapacity,
    u.StateId,
    u.Village,
    u.District,
    tb.Name AS InchargeName,
    st.Name AS StateName,
    cm.Name AS Pump_Capacity,
    f_active.id AS ActiveFlowId
FROM tbl_sell ts
INNER JOIN (
    SELECT CustId, MAX(id) AS latest_sell_id
    FROM tbl_sell
    WHERE ContractorAssignStatus = 1
    AND ChallanType = 1
    GROUP BY CustId
) latest ON latest.latest_sell_id = ts.id
LEFT JOIN tbl_users u 
    ON u.id = ts.CustId
LEFT JOIN tbl_branch tb 
    ON tb.id = u.StoreInchId
LEFT JOIN tbl_state st 
    ON st.id = u.StateId
LEFT JOIN tbl_common_master cm 
    ON cm.id = u.PumpCapacity
LEFT JOIN (
    SELECT CustId, MIN(id) AS id
    FROM tbl_installation_flow
    WHERE is_completed = 0
    GROUP BY CustId
) f_active ON f_active.CustId = ts.CustId
WHERE 1=1
";

if (!empty($_REQUEST['StoreInchId2']) && $_REQUEST['StoreInchId2'] != 'all') {
    $storeId = mysqli_real_escape_string($conn, $_REQUEST['StoreInchId2']);
    $sql .= " AND u.StoreInchId='" . $storeId . "'";
}
if (!empty($_REQUEST['PumpCapacity']) && $_REQUEST['PumpCapacity'] != 'all') {
    $pumpCap = mysqli_real_escape_string($conn, $_REQUEST['PumpCapacity']);
    $sql .= " AND u.PumpCapacity='" . $pumpCap . "'";
}
if (!empty($_REQUEST['StateId']) && $_REQUEST['StateId'] != 'all') {
    $stateId = mysqli_real_escape_string($conn, $_REQUEST['StateId']);
    $sql .= " AND u.StateId='" . $stateId . "'";
}
if (!empty($_REQUEST['Taluka']) && $_REQUEST['Taluka'] != 'all') {
    $taluka = mysqli_real_escape_string($conn, $_REQUEST['Taluka']);
    $sql .= " AND u.Taluka='" . $taluka . "'";
}
if (!empty($_REQUEST['District']) && $_REQUEST['District'] != 'all') {
    $district = mysqli_real_escape_string($conn, $_REQUEST['District']);
    $sql .= " AND u.District='" . $district . "'";
}
if (isset($_REQUEST['StoreInchStatus']) && $_REQUEST['StoreInchStatus'] !== '' && $_REQUEST['StoreInchStatus'] !== 'all') {
    if ((string) $_REQUEST['StoreInchStatus'] === '1') {
        $sql .= " AND f_active.id IS NOT NULL";
    } elseif ((string) $_REQUEST['StoreInchStatus'] === '0') {
        $sql .= " AND f_active.id IS NULL";
    }
}

$sql .= " ORDER BY ts.CreatedDate DESC";

$res = $conn->query($sql);
$queryError = (!$res) ? $conn->error : '';
$rowCount = 0;
if ($res) {
    $rowCount = $res->num_rows;
}
?>

<?php if ($queryError !== '') { ?>
    <div class="alert alert-danger mb-0">Unable to load results: <?php echo htmlspecialchars($queryError, ENT_QUOTES, 'UTF-8'); ?></div>
<?php } else { ?>

                                   <table id="example" class="table table-striped table-bordered" style="width:100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Assign To Co-ordinator</th>
            <th>Beneficiary ID</th>
            <th>Customer Name</th>
            <th>Contact No</th>
            <th>Pump Capacity</th>
            <th>Address</th>
            <th>State</th>
            <th>Village</th>
            <th>District</th>
        </tr>
    </thead>
    <tbody>

<?php
if ($res) {
while ($row = $res->fetch_assoc()) {

    $assigned = !empty($row['ActiveFlowId']);
    $bcolor = $assigned ? "background-color:#b9efb9;" : "";
?>
<tr style="<?php echo $bcolor; ?>">

   <td><?php if(!$assigned){?>
                    <label class="custom-control custom-checkbox">
                   <input type="checkbox"
       name="selected_sell_ids[]"
       value="<?php echo $row['id']; ?>" class="custom-control-input is-valid sell-checkbox">


                    <span class="custom-control-label">&nbsp;</span>
                 </label><?php } ?> </td>

  
    <td><?php echo $row['InchargeName']; ?></td>
    <td><?php echo $row['BeneficiaryId']; ?></td>
    <td><?php echo $row['Fname']; ?></td>
    <td><?php echo $row['Phone']; ?></td>
    <td><?php echo $row['Pump_Capacity']; ?></td>
    <td><?php echo $row['Address']; ?></td>
    <td><?php echo $row['StateName']; ?></td>
    <td><?php echo $row['Village']; ?></td>
    <td><?php echo $row['District']; ?></td>

</tr>
<?php $i++; } }
?>

    </tbody>
</table>

<?php if ($rowCount === 0) { ?>
    <div class="alert alert-warning mt-2 mb-0">No records found for the selected filters.</div>
<?php } ?>

<?php } ?>

<?php } else { ?>
    <div class="alert alert-info mb-0">
        Select filters and click <strong>Search</strong> to load pending installation sites.
    </div>
<?php } ?>

                                </div>

 <div class="form-row">
                                                    <div class="form-group col-lg-4">
                                                        <label class="form-label"> Co-Ordinator<span class="text-danger">*</span></label>
                                                        <select class="select2-demo form-control" name="CoordinatorId" id="CoordinatorId" required>
                                                            <option selected="" value="">Select</option>
                                                            <?php
                                                            $sql12 = "select id,Fname from tbl_users WHERE Status=1 AND Roll=6 ORDER BY Fname";
                                                            $row12 = getList($sql12);
                                                            foreach ($row12 as $result) {
                                                            ?>
                                                                <option value="<?php echo $result['id']; ?>">
                                                                    <?php echo $result['Fname']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <div class="clearfix"></div>
                                                    </div>



                                               


                                <div class="form-group col-md-1" style="padding-top:20px;">
                                    <button type="submit" name="submit" class="btn btn-primary btn-finish">Assign</button>
                                </div>
                                 </div>
                            </form>
                        </div>
                    </div>


                    <?php include_once 'footer.php'; ?>

                </div>

            </div>

        </div>

        <div class="layout-overlay layout-sidenav-toggle"></div>
    </div>


    <?php include_once 'footer_script.php'; ?>

    <script type="text/javascript">
     var selectedIds = {};

    function updateHiddenField() {
        const hiddenInput = document.getElementById("selected_ids_combined");
        hiddenInput.value = Object.keys(selectedIds).join(",");
    }

    function toggleCheckbox(checkbox) {
        const id = checkbox.getAttribute("data-id");
        if (checkbox.checked) {
            selectedIds[id] = true;
        } else {
            delete selectedIds[id];
        }
        updateHiddenField();
    }

    $(document).ready(function () {
        <?php if ($showResults && $queryError === '') { ?>
        var table = $('#example').DataTable({
            scrollX: true,
            pageLength: 25,
            deferRender: true
        });

        // On checkbox click
        $(document).on('change', '.sell-checkbox', function () {
            const id = this.value;
            if (this.checked) {
                selectedIds[id] = true;
            } else {
                delete selectedIds[id];
            }
            updateHiddenField();
        });

        // On redraw (pagination/search)
        table.on('draw', function () {
            $('.sell-checkbox').each(function () {
                const id = this.value;
                this.checked = !!selectedIds[id];
            });
            updateHiddenField();
        });
        <?php } ?>
    });
    
    function search(){
        var PumpCapacity = $('#PumpCapacity').val();
    var StoreInchId2 = $('#StoreInchId2').val();
    var StateId = $('#StateId').val();
    var District = $('#District').val();
    var Village = $('#Village').val() || '';
    var Search = $('#Search').val();
    var Taluka = $('#Taluka').val();
    var StoreInchStatus = $('#StoreInchStatus').val();
    window.location.href="pending-installations.php?StoreInchId2="+encodeURIComponent(StoreInchId2)+"&StateId="+encodeURIComponent(StateId)+"&District="+encodeURIComponent(District)+"&Village="+encodeURIComponent(Village)+"&Search="+encodeURIComponent(Search)+"&PumpCapacity="+encodeURIComponent(PumpCapacity)+"&Taluka="+encodeURIComponent(Taluka)+"&StoreInchStatus="+encodeURIComponent(StoreInchStatus);
}
        function featured(id) {
            if ($('#Check_Id' + id).prop('checked') == true) {
                $('#CheckId' + id).val(1);
            } else {
                $('#CheckId' + id).val(0);
            }
        }

        function featured2(id) {
            if ($('#Check_Id2' + id).prop('checked') == true) {
                $('#CheckId2' + id).val(1);
            } else {
                $('#CheckId2' + id).val(0);
            }
        }

        /*$(document).ready(function() {
            $('#example').DataTable({
                "scrollX": true
            });
        });*/
    </script>
</body>

</html>