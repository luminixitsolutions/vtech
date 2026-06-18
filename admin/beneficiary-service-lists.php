<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Service";
$Page = "Service-Beneficiary-List";
$filterFromDate = $_POST['FromDate'] ?? $_GET['FromDate'] ?? '';
$filterToDate = $_POST['ToDate'] ?? $_GET['ToDate'] ?? '';
$filterStateId = $_REQUEST['StateId'] ?? 'all';
$filterDistrict = $_REQUEST['District'] ?? 'all';
$filterProjectId = $_REQUEST['ProjectId'] ?? 'all';
$filterProjectSubHeadId = $_REQUEST['ProjectSubHeadId'] ?? 'all';
$filterSearchActive = isset($_POST['Search']) || isset($_GET['Search']);
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?></title>
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
if($_REQUEST["action"]=="delete")
{
  $id = $_REQUEST["id"];
  $sql11 = "DELETE FROM tbl_service_complaint WHERE id = '$id'";
  $conn->query($sql11);
  ?>
    <script type="text/javascript">
      alert("Deleted Successfully!");
      window.location.href="beneficiary-service-lists.php";
    </script>
<?php } ?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">View Beneficiary Service List
  
</h4>

<div class="card" style="padding: 10px;">
    <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
        <div class="" style="padding:5px;">
            <form id="validation-form" method="post" enctype="multipart/form-data" action="">
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label class="form-label">Project</label>
                        <select class="form-control" id="ProjectId" name="ProjectId" onchange="getSubHead(this.value)">
                            <option value="all"<?php if ($filterProjectId === 'all' || $filterProjectId === '') { ?> selected<?php } ?>>All Project</option>
                            <?php
                            $projectRes = $conn->query("SELECT id, Name FROM tbl_common_master WHERE Status='1' AND Roll=24 ORDER BY Name ASC");
                            while ($projectRow = $projectRes->fetch_assoc()) {
                                $sel = ((string) $filterProjectId === (string) $projectRow['id']) ? ' selected' : '';
                                echo '<option value="' . (int) $projectRow['id'] . '"' . $sel . '>' . htmlspecialchars($projectRow['Name']) . '</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group col-md-3">
                        <label class="form-label">Project Sub Head</label>
                        <select class="form-control" id="ProjectSubHeadId" name="ProjectSubHeadId">
                            <option value="all"<?php if ($filterProjectSubHeadId === 'all' || $filterProjectSubHeadId === '') { ?> selected<?php } ?>>All Sub Head</option>
                            <?php
                            if ($filterProjectId !== '' && $filterProjectId !== 'all') {
                                $projectIdInt = (int) $filterProjectId;
                                $subRes = $conn->query("SELECT id, Name FROM tbl_project_sub_head WHERE Status='1' AND UnderBy='$projectIdInt' ORDER BY Name ASC");
                                while ($subRow = $subRes->fetch_assoc()) {
                                    $sel = ((string) $filterProjectSubHeadId === (string) $subRow['id']) ? ' selected' : '';
                                    echo '<option value="' . (int) $subRow['id'] . '"' . $sel . '>' . htmlspecialchars($subRow['Name']) . '</option>';
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group col-md-3">
                        <label class="form-label">State</label>
                        <select class="select2-demo form-control" name="StateId" id="StateId">
                            <option value="all"<?php if ($filterStateId === 'all' || $filterStateId === '') { ?> selected<?php } ?>>All State</option>
                            <?php
                            $stateRes = $conn->query("SELECT id, Name FROM tbl_state WHERE CountryId='1' ORDER BY Name ASC");
                            while ($stateRow = $stateRes->fetch_assoc()) {
                                $sel = ((string) $filterStateId === (string) $stateRow['id']) ? ' selected' : '';
                                echo '<option value="' . (int) $stateRow['id'] . '"' . $sel . '>' . htmlspecialchars($stateRow['Name']) . '</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group col-md-3">
                        <label class="form-label">District</label>
                        <select class="select2-demo form-control" name="District" id="District">
                            <option value="all"<?php if ($filterDistrict === 'all' || $filterDistrict === '') { ?> selected<?php } ?>>All District</option>
                            <?php
                            if ($filterStateId !== '' && $filterStateId !== 'all') {
                                $stateIdInt = (int) $filterStateId;
                                $districtSql = "SELECT DISTINCT(District) AS District FROM tbl_users WHERE District!='' AND StateId='$stateIdInt' ORDER BY District ASC";
                            } else {
                                $districtSql = "SELECT DISTINCT(District) AS District FROM tbl_users WHERE District!='' ORDER BY District ASC";
                            }
                            $districtRes = $conn->query($districtSql);
                            while ($districtRow = $districtRes->fetch_assoc()) {
                                $districtName = $districtRow['District'];
                                $sel = ((string) $filterDistrict === (string) $districtName) ? ' selected' : '';
                                echo '<option value="' . htmlspecialchars($districtName, ENT_QUOTES, 'UTF-8') . '"' . $sel . '>' . htmlspecialchars($districtName) . '</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group col-md-3">
                        <label class="form-label">From Date (Installation)</label>
                        <input type="date" name="FromDate" class="form-control" value="<?php echo htmlspecialchars($filterFromDate, ENT_QUOTES, 'UTF-8'); ?>" autocomplete="off">
                    </div>
                    <div class="form-group col-md-3">
                        <label class="form-label">To Date (Installation)</label>
                        <input type="date" name="ToDate" class="form-control" value="<?php echo htmlspecialchars($filterToDate, ENT_QUOTES, 'UTF-8'); ?>" autocomplete="off">
                    </div>
                    <input type="hidden" name="Search" value="Search">
                    <div class="form-group col-md-1" style="padding-top:20px;">
                        <button type="submit" name="submit" class="btn btn-primary btn-finish">Search</button>
                    </div>
                    <?php if ($filterSearchActive) { ?>
                    <div class="form-group col-md-1">
                        <label class="form-label d-none d-md-block">&nbsp;</label>
                        <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-info btn-block" data-toggle="tooltip" data-placement="top" data-original-title="Clear Filter">X</a>
                    </div>
                    <?php } ?>
                </div>
            </form>
        </div>
    </div>

<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
               <th>Sr No</th>
               
              <th>Project Head</th> 
              <th>Project Sub Head</th> 
                <th>Customer Name</th> 
                <th>Contact No</th>
               
                <th>Address</th>
                <th>Installation Date</th>
                <th>Warranty Date</th>
                <th>View Complaints</th>
                <th>Raise Complaints</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=1;
            $sql = "SELECT ti.*,tcm.Name AS ProjectHead,tps.Name AS SubProjectHead FROM tbl_installations ti 
                    INNER JOIN tbl_users tu ON ti.CustId=tu.id 
                    LEFT JOIN tbl_common_master tcm ON tcm.id=tu.ProjectId 
                    LEFT JOIN tbl_project_sub_head tps ON tps.id=tu.ProjectSubHeadId 
                    WHERE ti.WarrantyReg='Yes' AND tu.ProjectType=1";

            if ($filterSearchActive) {
                if ($filterProjectId !== '' && $filterProjectId !== 'all') {
                    $sql .= " AND tu.ProjectId='" . (int) $filterProjectId . "'";
                }
                if ($filterProjectSubHeadId !== '' && $filterProjectSubHeadId !== 'all') {
                    $sql .= " AND tu.ProjectSubHeadId='" . (int) $filterProjectSubHeadId . "'";
                }
                if ($filterStateId !== '' && $filterStateId !== 'all') {
                    $sql .= " AND tu.StateId='" . (int) $filterStateId . "'";
                }
                if ($filterDistrict !== '' && $filterDistrict !== 'all') {
                    $sql .= " AND tu.District='" . $conn->real_escape_string($filterDistrict) . "'";
                }
                if ($filterFromDate !== '') {
                    $sql .= " AND ti.InstallationDate>='" . $conn->real_escape_string($filterFromDate) . "'";
                }
                if ($filterToDate !== '') {
                    $sql .= " AND ti.InstallationDate<='" . $conn->real_escape_string($filterToDate) . "'";
                }
            }

            $sql .= " ORDER BY ti.CustName ASC";
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
               
             ?>
            <tr>
               <td><?php echo $i; ?> </td>
               <td><?php echo $row['ProjectHead']; ?></td> 
               <td><?php echo $row['SubProjectHead']; ?></td> 
              
               <td><?php echo $row['CustName']; ?></td> 
              
                <td><?php echo $row['CellNo']; ?></td>
                 <td><?php echo $row['Address']; ?></td>
               
           
               
            <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['InstallationDate']))); ?></td>
            <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['WarrantyRegDate']))); ?></td>
          
            <td><a href="view-customer-complaints.php?custid=<?php echo $row['CustId']; ?>">View</a></td>
             <td><a href="choose-service-type.php?CustId=<?php echo $row['CustId']; ?>">Raise New Complaint</a></td>
              
            </tr>
           <?php $i++;} ?>
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
    function getSubHead(id) {
        if (!id || id === 'all') {
            $('#ProjectSubHeadId').html('<option value="all">All Sub Head</option>');
            return;
        }
        $.ajax({
            type: 'POST',
            url: 'ajax_files/ajax_dropdown.php',
            data: { action: 'getSubHead', id: id },
            success: function(data) {
                $('#ProjectSubHeadId').html('<option value="all">All Sub Head</option>' + data);
                $('#ProjectSubHeadId option[value=""]').remove();
            }
        });
    }

    function getDistrictByState(stateId) {
        if (!stateId || stateId === 'all') {
            $('#District').html('<option value="all">All District</option>');
            return;
        }
        $.ajax({
            type: 'POST',
            url: 'ajax_files/ajax_dropdown.php',
            data: { action: 'getDistrict', id: stateId },
            success: function(data) {
                $('#District').html(data);
            }
        });
    }

    $(document).ready(function() {
        $('#StateId').on('change', function() {
            getDistrictByState(this.value);
        });

        $('#example').DataTable({
            "scrollX": true,
            dom: 'Bfrtip',
            buttons: ['excelHtml5']
        });
    });
</script>
</body>
</html>
