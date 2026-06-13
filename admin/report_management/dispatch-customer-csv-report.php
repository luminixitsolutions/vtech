<?php 
session_start();
include_once '../config.php';
include_once '../auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Report";
$Page = "Dispatch-Customer-CSV-Report";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | Dispatch Customer CSV Report</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="" />
<meta name="keywords" content="">
<meta name="author" content="" />
<?php include_once '../header_script.php'; ?>
</head>
<body>

<div class="layout-wrapper layout-2">
<div class="layout-inner">

<?php include_once 'report-sidebar.php'; ?>


<div class="layout-container">

<?php include_once '../top_header.php'; ?>

<?php
if($_REQUEST["action"]=="delete")
{
  $id = $_REQUEST["id"];
  $sql11 = "DELETE FROM tbl_users WHERE id = '$id'";
  $conn->query($sql11);
  ?>
    <script type="text/javascript">
      alert("Deleted Successfully!");
      window.location.href="view-customers.php";
    </script>
<?php } ?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Dispatch Customer CSV Report</h4>

<div class="card" style="padding: 10px;">

       <div id="accordion2">
<div class="card mb-2">
                                        
                                        <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                            <div class="" style="padding:5px;">
                                                <form id="validation-form" method="post" enctype="multipart/form-data" action="">
<div class="form-row">

<div class="form-group col-md-3">
<label class="form-label">Project</label>
<select class="form-control" id="ProjectId" name="ProjectId" onchange="getSubHead(this.value)">
<option selected="" value="all">All Project</option>
<?php
$q = "SELECT * FROM tbl_common_master WHERE Status='1' AND Roll=24 ORDER BY Name ASC";
$r = $conn->query($q);
while ($rw = $r->fetch_assoc()) {
?>
<option <?php if (isset($_POST['ProjectId']) && $_POST['ProjectId'] == $rw['id']) { ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
<?php } ?>
</select>
</div>

<div class="form-group col-md-3">
<label class="form-label">Sub Project</label>
<select class="form-control" id="ProjectSubHeadId" name="ProjectSubHeadId">
<option selected="" value="all">All Sub Project</option>
<?php
if (!empty($_POST['ProjectId']) && $_POST['ProjectId'] != 'all') {
    $projectId = (int) $_POST['ProjectId'];
    $q = "SELECT * FROM tbl_project_sub_head WHERE Status='1' AND UnderBy='$projectId' ORDER BY Name ASC";
    $r = $conn->query($q);
    while ($rw = $r->fetch_assoc()) {
?>
<option <?php if (isset($_POST['ProjectSubHeadId']) && $_POST['ProjectSubHeadId'] == $rw['id']) { ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
<?php
    }
}
?>
</select>
</div>

<div class="form-group col-md-3">
<label class="form-label">District</label>
<select class="select2-demo form-control" id="District" name="District">
<option selected="" value="all">All District</option>
<?php
$q = "SELECT DISTINCT(District) AS District FROM tbl_users WHERE District!='' ORDER BY District ASC";
$r = $conn->query($q);
while ($rw = $r->fetch_assoc()) {
?>
<option <?php if (isset($_POST['District']) && $_POST['District'] == $rw['District']) { ?> selected <?php } ?> value="<?php echo htmlspecialchars($rw['District']); ?>"><?php echo htmlspecialchars($rw['District']); ?></option>
<?php } ?>
</select>
</div>

<div class="form-group col-md-3">
<label class="form-label">From Date </label>
<input type="date" name="FromDate" id="FromDate" class="form-control" value="<?php echo isset($_POST['FromDate']) ? $_POST['FromDate'] : ''; ?>" autocomplete="off">
</div>
<div class="form-group col-md-3">
<label class="form-label">To Date</label>
<input type="date" name="ToDate" id="ToDate" class="form-control" value="<?php echo isset($_POST['ToDate']) ? $_POST['ToDate'] : ''; ?>" autocomplete="off">
</div>
<input type="hidden" name="Search" value="Search">
<div class="form-group col-md-1" style="padding-top:20px;">
<button type="submit" name="submit" class="btn btn-primary btn-finish">Search</button>
</div>
<?php if(isset($_POST['Search'])) {?>
<div class="form-group col-md-1">
<label class="form-label">&nbsp;</label>
<a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-info btn-block" data-toggle="tooltip" data-placement="top" data-original-title="Clear Filter">X</a>
</div>
<?php } ?>
</div>

</form>
                                            </div>
                                        </div>
                                    </div>
   </div>

<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
    <thead>
        <tr>
            <th>Id</th>
            <th>Beneficiary ID</th>
            <th>Beneficiary Name</th>
            <th>Application No</th>
            <th>Mobile</th>
            <th>Water Source</th>
            <th>District</th>
            <th>Taluka</th>
            <th>Village</th>
            <th>Pump Capacity</th>
            <th>Final Head</th>
            <th>MATERIAL DISPATCH</th>
            <th>Pump No</th>
            <th>Controller No</th>
            <th>Imei No</th>
            <?php for ($i = 1; $i <= 15; $i++) { ?>
                <th>Panel No <?php echo $i; ?></th>
            <?php } ?>
        </tr>
    </thead>
    <tbody>
        <?php 
        $sql = "SELECT tu.id, tu.BeneficiaryId, tu.Fname, tu.Phone, tu.District, tu.Taluka, tu.Village,
                       tcm.Name AS Pump_Capacity, tcm2.Name AS Water_Source
                FROM tbl_sell ts 
                INNER JOIN tbl_users tu ON ts.CustId = tu.id 
                LEFT JOIN tbl_common_master tcm ON tcm.id = tu.PumpCapacity 
                LEFT JOIN tbl_common_master tcm2 ON tcm2.id = tu.WaterSource 
                WHERE 1";

        if (!empty($_POST['FromDate'])) {
            $FromDate = mysqli_real_escape_string($conn, $_POST['FromDate']);
            $sql .= " AND ts.InvoiceDate >= '$FromDate'";
        }

        if (!empty($_POST['ToDate'])) {
            $ToDate = mysqli_real_escape_string($conn, $_POST['ToDate']);
            $sql .= " AND ts.InvoiceDate <= '$ToDate'";
        }

        if (!empty($_POST['District']) && $_POST['District'] != 'all') {
            $District = mysqli_real_escape_string($conn, $_POST['District']);
            $sql .= " AND tu.District='$District'";
        }

        if (!empty($_POST['ProjectId']) && $_POST['ProjectId'] != 'all') {
            $ProjectId = (int) $_POST['ProjectId'];
            $sql .= " AND tu.ProjectId='$ProjectId'";
        }

        if (!empty($_POST['ProjectSubHeadId']) && $_POST['ProjectSubHeadId'] != 'all') {
            $ProjectSubHeadId = (int) $_POST['ProjectSubHeadId'];
            $sql .= " AND tu.ProjectSubHeadId='$ProjectSubHeadId'";
        }

        $sql .= " GROUP BY ts.CustId ORDER BY ts.id DESC";
        $res = $conn->query($sql);

        while ($row = $res->fetch_assoc()) {
        ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['BeneficiaryId']; ?></td>
                <td><?php echo $row['Fname']; ?></td>
                <td></td>
                <td><?php echo $row['Phone']; ?></td>
                <td><?php echo $row['Water_Source']; ?></td>
                <td><?php echo $row['District']; ?></td>
                <td><?php echo $row['Taluka']; ?></td>
                <td><?php echo $row['Village']; ?></td>
                <td><?php echo $row['Pump_Capacity']; ?></td>
                <td></td>
                <td>1</td>

                <!-- ✅ Pump No -->
                <?php
                $pumpSql = "SELECT SerialNo FROM tbl_sell_products 
                            WHERE SerialNo != 'N/A' 
                            AND ProductId != 0 
                            AND UserId = '{$row['id']}' 
                            AND ProductName LIKE '%PUMPSET%' LIMIT 1";
                $pumpRows = getList($pumpSql);
                if (!empty($pumpRows)) {
                    echo "<td>{$pumpRows[0]['SerialNo']}</td>";
                } else {
                    echo "<td>-</td>";
                }
                ?>

                <!-- ✅ Controller No -->
                <?php
                $controllerSql = "SELECT SerialNo FROM tbl_sell_products 
                                  WHERE SerialNo != 'N/A' 
                                  AND ProductId != 0 
                                  AND UserId = '{$row['id']}' 
                                  AND ProductName LIKE '%CONTROLLER%' LIMIT 1";
                $controllerRows = getList($controllerSql);
                if (!empty($controllerRows)) {
                    echo "<td>{$controllerRows[0]['SerialNo']}</td>";
                } else {
                    echo "<td>-</td>";
                }
                ?>

                <!-- ✅ Imei No (if you have a separate logic, you can replace this) -->
                <td>0</td>

                <!-- ✅ Panel Nos (show up to 15, or all '-' if none found) -->
                <?php
                $panelSql = "SELECT SerialNo FROM tbl_sell_products 
                             WHERE SerialNo != 'N/A' 
                             AND ProductId != 0 
                             AND UserId = '{$row['id']}' 
                             AND ProductName LIKE '%PV MODULE%'";
                $panelRows = getList($panelSql);
                $panelCount = is_array($panelRows) ? count($panelRows) : 0;

                if ($panelCount > 0) {
                    // Show available panel numbers
                    for ($i = 0; $i < $panelCount; $i++) {
                        echo "<td>{$panelRows[$i]['SerialNo']}</td>";
                    }
                    // Fill remaining columns with '-'
                    for ($i = $panelCount; $i < 15; $i++) {
                        echo "<td>-</td>";
                    }
                } else {
                    // No panels found → all 15 show '-'
                    for ($i = 0; $i < 15; $i++) {
                        echo "<td>-</td>";
                    }
                }
                ?>
            </tr>
        <?php } ?>
    </tbody>
</table>



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
function getSubHead(id) {
    if (!id || id === 'all') {
        $('#ProjectSubHeadId').html('<option selected="" value="all">All Sub Project</option>');
        return;
    }
    $.ajax({
        type: "POST",
        url: "../ajax_files/ajax_dropdown.php",
        data: { action: 'getSubHead', id: id },
        success: function(data) {
            $('#ProjectSubHeadId').html('<option value="all">All Sub Project</option>' + data);
        }
    });
}

$(document).ready(function() {
    $('#example').DataTable({
        "scrollX": true,
        "pageLength": 10,
        dom: 'Bfrtip',
        buttons: [
            'excelHtml5'
        ]
    });
});
</script>

</body>
</html>
