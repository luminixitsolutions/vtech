<?php
session_start();
include_once 'config.php';
require_once "exe-database.php";
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Assign-Store-Incharge";
$Page = "Assign-Store-Incharge";
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

               <?php
if (isset($_POST['submit'])) {

    $StoreInchId = addslashes(trim($_POST['StoreInchId']));
    $CreatedDate = date('Y-m-d H:i:s');
    $CreatedTime = date('H:i:s');

    // ✅ Process 1st set (quotation checkboxes)
    if (!empty($_POST['CheckId']) && isset($_POST['QtnId'])) {
        $CheckIds = $_POST['CheckId'];
        $QtnIds = $_POST['QtnId'];

        foreach ($CheckIds as $index => $checkValue) {
            if ($checkValue == 1 && isset($QtnIds[$index])) {
                $QtnId = addslashes(trim($QtnIds[$index]));
                $sql = "UPDATE tbl_quotation SET StoreInchStatus='1', StoreInchId='$StoreInchId', StoreInchDate='$CreatedDate' WHERE id='$QtnId'";
                $conn->query($sql);
            }
        }
    }

    // ✅ Process 2nd set (customer checkboxes)
 if (!empty($_POST['selected_ids_combined'])) {
        $ids = explode(",", $_POST['selected_ids_combined']);
        foreach ($ids as $QtnId2) {
            $QtnId2 = intval($QtnId2);
            $sql = "UPDATE tbl_users SET StoreInchStatus='1', StoreInchId='$StoreInchId', StoreInchDate='$CreatedDate' WHERE id='$QtnId2'";
            $conn->query($sql);
        }

        echo "<script>alert('Assigned successfully'); window.location.href='assign-to-store-incharge.php?StoreInchStatus=0';</script>";
        exit;
    } else {
        echo "<script>alert('No customers selected!'); history.back();</script>";
        exit;
    }
    // ✅ Send Notification
    $Title = "Customer Assign";
    $Message = "Customer Assign To you for Further Process";

    $sql73 = "SELECT Tokens, id FROM tbl_users WHERE Status='1' AND Tokens!='' AND id='$StoreInchId'";
    $data = mysqli_query($conn, $sql73);

    while ($row = mysqli_fetch_array($data)) {
        $ReceiverId = $row['id'];
        $reg_id = $row['Tokens'];

        // Insert notification in DB
        $sql = "INSERT INTO tbl_notifications SET SenderId='$user_id', ReceiverId='$ReceiverId', Title='$Title', Message='$Message', CreatedDate='$CreatedDate', CreatedTime='$CreatedTime'";
        $conn->query($sql);

        // Push Notification
        $title = $Title;
        $body = $Message;
        $registrationIds = array($reg_id);
        include '../incnotification.php';
    }

    echo "<script>alert('Assign To Store Incharge'); window.location.href='assign-to-store-incharge.php?StoreInchStatus=0';</script>";
}
?>


                <div class="layout-content">

                    <div class="container-fluid flex-grow-1 container-p-y">
                        <h4 class="font-weight-bold py-3 mb-0">Assign Beneficiary To Store
                            <!-- <span style="float: right;">
<a href="add-sell.php" class="btn btn-secondary btn-round"><i class="ion ion-md-add mr-2"></i> Add New Sell</a></span> -->
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
                                                        <option value="all" selected>All</option>
                                    <option value="1" <?php if($_REQUEST['StoreInchStatus']==1){ ?> selected <?php } ?> >Assign</option>
                                    <option value="0"  <?php if($_REQUEST['StoreInchStatus']==0){ ?> selected <?php } ?> >Not Assign</option>
                                                          
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
                                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Assign To Store</th>
                                                 <th>Beneficiary ID</th>
                                                <th>Customer Name</th>
                                                <th>Contact No</th>
                                                <th>Pump Capacity</th>
                                                <th>Address</th>
                                                 <th>State</th>
                                                <th>Village</th>
                                                    <th>District</th>
                                                <!--<th>QTN NO</th>
                                                <th>QTN Date</th>
                                                <th>Paid Status</th>
                                                <th>Total Amount</th>
                                                <th>Paid Amount</th>
                                                <th>Bal Amount</th>
                                                <th>Paid Date</th>
                                                <th>Paid By</th>-->



                                            </tr>
                                        </thead>
                                        <tbody>
                                           


                                             <?php
                $i = 1;
                $sql = "SELECT tu.*, tu2.Name As InchargeName, ts2.Name As StateName, tcm.Name As Pump_Capacity 
                        FROM tbl_users tu 
                        LEFT JOIN tbl_branch tu2 ON tu2.id = tu.StoreInchId 
                        LEFT JOIN tbl_state ts2 ON ts2.id = tu.StateId 
                        LEFT JOIN tbl_common_master tcm ON tcm.id = tu.PumpCapacity 
                        WHERE tu.ProjectType = 1 AND tu.SurveyMatch = 1";

                // Filters
                if ($_REQUEST['StoreInchId2'] != '' && $_REQUEST['StoreInchId2'] != 'all') {
                    $sql .= " AND tu.StoreInchId='" . $_REQUEST['StoreInchId2'] . "'";
                }
                if ($_REQUEST['PumpCapacity'] != '' && $_REQUEST['PumpCapacity'] != 'all') {
                    $sql .= " AND tu.PumpCapacity='" . $_REQUEST['PumpCapacity'] . "'";
                }
                if ($_REQUEST['StateId'] != '' && $_REQUEST['StateId'] != 'all') {
                    $sql .= " AND tu.StateId='" . $_REQUEST['StateId'] . "'";
                }
                if ($_REQUEST['Taluka'] != '' && $_REQUEST['Taluka'] != 'all') {
                    $sql .= " AND tu.Taluka='" . $_REQUEST['Taluka'] . "'";
                }
                if ($_REQUEST['District'] != '' && $_REQUEST['District'] != 'all') {
                    $sql .= " AND tu.District='" . $_REQUEST['District'] . "'";
                }
                if ($_REQUEST['StoreInchStatus'] != '' && $_REQUEST['StoreInchStatus'] != 'all') {
                    $sql .= " AND tu.StoreInchStatus='" . $_REQUEST['StoreInchStatus'] . "'";
                }

                $sql .= " ORDER BY tu.CreatedDate DESC";

                $res = $conn->query($sql);
                while ($row = $res->fetch_assoc()) {
                    $sql22 = "SELECT * FROM tbl_users WHERE StoreInchStatus=1 AND id='" . $row['id'] . "'";
                    $rncnt22 = getRow($sql22);
                    $bcolor = ($rncnt22 > 0) ? "background-color: #b9efb9;" : "";
                ?>
                                                <tr style="<?php echo $bcolor; ?>">
                                                   <td>
                            <?php if ($rncnt22 == 0) { ?>
                              <input type="checkbox" class="rowCheckbox" data-id="<?php echo $row['id']; ?>" />
                                
                            <?php } ?>
                          
                        </td>
                                                    
                                                   

                                                    <input type="hidden" value="0" name="CheckId2[]" id="CheckId2<?php echo $row['id']; ?>">
                                                    <input type="hidden" value="<?php echo $row['id']; ?>" name="QtnId2[]">
                                                    <input type="hidden" value="<?php echo $row['Fname']; ?>" name="CustName[]">
                                                    <input type="hidden" value="<?php echo $row['Phone']; ?>" name="CellNo[]">
                                                    <input type="hidden" value="<?php echo $row['Address']; ?>" name="Address[]">

                                                    <td><?php echo $row['InchargeName']; ?></td>
                                                    <td><?php echo $row['BeneficiaryId']; ?></td>
                                                    <td><?php echo $row['Fname']; ?></td>

                                                    <td><?php echo $row['Phone']; ?></td>
                                                    <td><?php echo $row['Pump_Capacity']; ?></td>
                                                    <td><?php echo $row['Address']; ?></td>
                                                     <td><?php echo $row['StateName']; ?></td>
            <td><?php echo $row['Village']; ?></td>
            <td><?php echo $row['District']; ?></td>
                                                    <!--<td><?php echo $row['InvoiceNo']; ?></td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>-->


                                                </tr>
                                            <?php $i++;
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>

 <div class="form-row">
                                                    <div class="form-group col-lg-4">
                                                        <label class="form-label"> Store<span class="text-danger">*</span></label>
                                                        <select class="select2-demo form-control" name="StoreInchId" id="StoreInchId" required>
                                                            <option selected="" value="">Select</option>
                                                            <?php
                                                            $sql12 = "SELECT * FROM tbl_branch WHERE Status='1'";
                                                            $row12 = getList($sql12);
                                                            foreach ($row12 as $result) {
                                                            ?>
                                                                <option value="<?php echo $result['id']; ?>">
                                                                    <?php echo $result['Name']; ?></option>
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