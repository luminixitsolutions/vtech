<?php
session_start();
include_once 'config.php';
require_once "exe-database.php";
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Installation";
$Page = "Installation";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">

<head>
    <title><?php echo $Proj_Title; ?> | View Sell List</title>
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

          



                <div class="layout-content">

                    <div class="container-fluid flex-grow-1 container-p-y">
                        <h4 class="font-weight-bold py-3 mb-0">Pending File Submission Sites 
                           
                        </h4>

                        <div class="card" style="padding: 10px;">
                            
                             <form id="validation-form" method="post" enctype="multipart/form-data" action="">
                                                <div class="form-row">


                                                  

                                                     <div class="form-group col-lg-4">
                                                        <label class="form-label"> Store<span class="text-danger">*</span></label>
                                                        <select class="select2-demo form-control" name="StoreInchId2" id="StoreInchId2" required>
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
        $CountryId = $row7['CountryId'];
        $q = "select * from tbl_state WHERE CountryId='1' ORDER BY Name ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($_REQUEST['StateId']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>
	</div>


	
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
                                            
                           
                                
                                 <?php
                                            $i = 1;
                                            $sql = "SELECT tu.*,tu2.Fname As InchargeName FROM tbl_users tu 
                                                    LEFT JOIN tbl_users tu2 ON tu2.id=tu.StoreInchId 
                                                    WHERE tu.ProjectType=1 ORDER BY tu.CreatedDate DESC";
                                                    $rncnt2 = getRow($sql);?>
        <input type="hidden" name="rncnt2" value="<?php echo $rncnt2;?>">
       
        <?php
                                            $i = 1;
                                            $sql = "SELECT tp.*,tu.Fname As InchargeName FROM tbl_quotation tp 
                    LEFT JOIN tbl_users tu ON tu.id=tp.StoreInchId 
                    WHERE tp.PaidStatus=1
            
            ORDER BY tp.CreatedDate DESC";
            $rncnt = getRow($sql);?>
        <input type="hidden" name="rncnt" value="<?php echo $rncnt;?>">
        
        
                              <input type="hidden" name="selected_ids_combined" id="selected_ids_combined" />
                                <div class="card-datatable table-responsive">
                                    
                                    <?php
$i = 1;

$sql = "
SELECT 
    ts.*,
    ti.InstallStatus,
    ti.Type,ti.id As InstId,
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
    cm.Name AS Pump_Capacity
FROM tbl_sell ts
INNER JOIN tbl_installations ti 
    ON ti.CustId = ts.CustId 
    AND ti.Type = 2 
    AND ti.PoInspection = 'Yes' AND ti.FileInHand='No'
LEFT JOIN tbl_users u 
    ON u.id = ts.CustId
LEFT JOIN tbl_branch tb 
    ON tb.id = u.StoreInchId
LEFT JOIN tbl_state st 
    ON st.id = u.StateId
LEFT JOIN tbl_common_master cm 
    ON cm.id = u.PumpCapacity
WHERE ts.ContractorAssignStatus = 1
AND ts.ChallanType = 1 AND ti.AssignPendingFileSubmissionTo='$user_id'
GROUP BY ts.CustId
";

/* ===== Filters (unchanged logic) ===== */
if ($_REQUEST['StoreInchId2'] != '' && $_REQUEST['StoreInchId2'] != 'all') {
    $sql .= " AND u.StoreInchId='" . $_REQUEST['StoreInchId2'] . "'";
}
if ($_REQUEST['PumpCapacity'] != '' && $_REQUEST['PumpCapacity'] != 'all') {
    $sql .= " AND u.PumpCapacity='" . $_REQUEST['PumpCapacity'] . "'";
}
if ($_REQUEST['StateId'] != '' && $_REQUEST['StateId'] != 'all') {
    $sql .= " AND u.StateId='" . $_REQUEST['StateId'] . "'";
}
if ($_REQUEST['Taluka'] != '' && $_REQUEST['Taluka'] != 'all') {
    $sql .= " AND u.Taluka='" . $_REQUEST['Taluka'] . "'";
}
if ($_REQUEST['District'] != '' && $_REQUEST['District'] != 'all') {
    $sql .= " AND u.District='" . $_REQUEST['District'] . "'";
}

$sql .= " ORDER BY ts.CreatedDate DESC";

$res = $conn->query($sql);
?>


                                   <table id="example" class="table table-striped table-bordered" style="width:100%">
    <thead>
        <tr>
            <th>#</th>
         
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
while ($row = $res->fetch_assoc()) {

   
?>
<tr style="<?php echo $bcolor; ?>">

 
    <td><?php echo $i; ?></td>
    <td><?php echo $row['BeneficiaryId']; ?></td>
    <td><?php echo $row['Fname']; ?></td>
    <td><?php echo $row['Phone']; ?></td>
    <td><?php echo $row['Pump_Capacity']; ?></td>
    <td><?php echo $row['Address']; ?></td>
    <td><?php echo $row['StateName']; ?></td>
    <td><?php echo $row['Village']; ?></td>
    <td><?php echo $row['District']; ?></td>

</tr>
<?php $i++; } ?>

    </tbody>
</table>

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
        var table = $('#example').DataTable();

        // On checkbox click
        $(document).on('change', '.rowCheckbox', function () {
            toggleCheckbox(this);
        });

        // On redraw (pagination/search)
        table.on('draw', function () {
            $('.rowCheckbox').each(function () {
                const id = this.getAttribute("data-id");
                this.checked = !!selectedIds[id];
            });
            updateHiddenField();
        });
    });
    
    function search(){
        var PumpCapacity = $('#PumpCapacity').val();
    var StoreInchId2 = $('#StoreInchId2').val();
    var StateId = $('#StateId').val();
    var District = $('#District').val();
    var Village = $('#Village').val();
    var Search = $('#Search').val();
    var Taluka = $('#Taluka').val();
    var StoreInchStatus = $('#StoreInchStatus').val();
    window.location.href="assign-to-store-incharge.php?StoreInchId2="+StoreInchId2+"&StateId="+StateId+"&District="+District+"&Village="+Village+"&Search="+Search+"&PumpCapacity="+PumpCapacity+"&Taluka="+Taluka+"&StoreInchStatus="+StoreInchStatus;
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