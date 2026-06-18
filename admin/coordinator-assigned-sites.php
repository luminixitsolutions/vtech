<?php

session_start();

include_once 'config.php';

include_once 'auth.php';



$user_id = $_SESSION['Admin']['id'];

$MainPage = "Installation";

$Page = "Coordinator-Sites";



installationWorkflowBootstrap();



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {



    $action = $_POST['action'];

    $flowId = intval($_POST['flowId'] ?? 0);



    if ($flowId <= 0) {

        exit;

    }



    if ($action === 'load_history') {

        if (!installationWorkflowCanAccessFlow($flowId, $user_id, 'coordinator')) {

            exit;

        }

        echo installationWorkflowRenderHistory($flowId);

        exit;

    }



    if (!installationWorkflowCanAccessFlow($flowId, $user_id, 'coordinator')) {

        exit;

    }

    $flow = installationWorkflowGetFlow($flowId);

    if ($action === 'followup') {

        if (!$flow || installationWorkflowCoordinatorIsReadOnly($flow)) {

            exit;

        }

        $remark = trim($_POST['remark'] ?? '');

        if ($remark === '') {

            exit;

        }

        installationWorkflowLogAction($flowId, $user_id, 'FOLLOW_UP', $remark);

        exit;

    }



    if ($action === 'installed') {

        if (!$flow || installationWorkflowCoordinatorIsReadOnly($flow)) {

            echo 'ERR';

            exit;

        }

        $result = installationWorkflowMarkInstalled($flowId, $user_id);

        echo !empty($result['ok']) ? 'OK' : 'ERR';

        exit;

    }

}



?>

<!DOCTYPE html>

<html lang="en" class="default-style layout-fixed layout-navbar-fixed">

<head>

<title><?php echo $Proj_Title; ?> | Assigned Installation Sites</title>

<meta charset="utf-8">

<meta http-equiv="X-UA-Compatible" content="IE=edge" />

<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">

<?php include_once 'header_script.php'; ?>

</head>



<style><?php echo installationWorkflowTimelineCss(); ?></style>



<body>



<div class="layout-wrapper layout-2">

<div class="layout-inner">



<?php include_once 'sidebar.php'; ?>



<div class="layout-container">

<?php include_once 'top_header.php'; ?>



<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">



<h4 class="font-weight-bold py-3 mb-0">Assigned Installation Sites</h4>



<div class="card" style="padding: 10px;">

<div class="card-datatable table-responsive">



<table id="example" class="table table-striped table-bordered" style="width:100%">

<thead>

<tr>

    <th>Sr No</th>

    <th>Beneficiary ID</th>

    <th>Customer Name</th>

    <th>Contact No</th>

    <th>Address</th>

    <th>Pump Capacity</th>

    <th>Assigned Date</th>

    <th>Due Date</th>

    <th>Overdue Days</th>

    <th>Status</th>

    <th>Action</th>

</tr>

</thead>



<tbody>

<?php

$i = 1;

$listSql = installationWorkflowCoordinatorListSql($user_id);



$sql = "

SELECT 

    f.id AS FlowId,

    f.assigned_date,

    f.coordinator_due_date,

    f.stage_start_date,

    u.Fname,

    u.Phone,

    u.Address,

    u.BeneficiaryId,

    cm.Name AS PumpCapacity,

    f.*

FROM tbl_installation_flow f

JOIN tbl_users u ON u.id = f.CustId

LEFT JOIN tbl_common_master cm ON cm.id = u.PumpCapacity

WHERE $listSql

ORDER BY f.coordinator_due_date ASC, f.assigned_date DESC

";



$res = $conn->query($sql);

while ($row = $res->fetch_assoc()) {



    $overdue = installationWorkflowOverdueDays($row);

    $dueDate = installationWorkflowActiveDueDate($row);

    $readOnly = installationWorkflowCoordinatorIsReadOnly($row);

    $status = $readOnly

        ? "<span class='badge badge-danger'>Escalation Due</span>"

        : installationWorkflowStatusBadge($row);

    $rowStyle = $readOnly ? "style='background:#ffe6e6'" : "";

?>

<tr <?php echo $rowStyle; ?>>

    <td><?php echo $i++; ?></td>

    <td><?php echo $row['BeneficiaryId']; ?></td>

    <td><?php echo $row['Fname']; ?></td>

    <td><?php echo $row['Phone']; ?></td>

    <td><?php echo $row['Address']; ?></td>

    <td><?php echo $row['PumpCapacity']; ?></td>

    <td><?php echo date('d/m/Y', strtotime($row['assigned_date'])); ?></td>

    <td><?php echo $dueDate ? date('d/m/Y', strtotime($dueDate)) : '-'; ?></td>

    <td><?php echo $overdue > 0 ? $overdue : 0; ?></td>

    <td><?php echo $status; ?></td>

    <td>

        <button class="btn btn-sm btn-secondary" onclick="openHistoryModal('<?php echo $row['FlowId']; ?>')">History</button>

        <?php if ($readOnly) { ?>

            <span class="badge badge-secondary ml-1">View history only</span>

        <?php } else { ?>

        <button class="btn btn-sm btn-info" onclick="openFollowUp('<?php echo $row['FlowId']; ?>')">Follow-up</button>

        <button class="btn btn-sm btn-success" onclick="markInstalled('<?php echo $row['FlowId']; ?>')">Mark Installed</button>

        <?php } ?>

    </td>

</tr>

<?php } ?>

</tbody>

</table>



</div>

</div>

</div>



<div class="modal fade" id="historyModal" tabindex="-1">

  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">

    <div class="modal-content">

      <div class="modal-header bg-dark text-white">

        <h5 class="modal-title">Installation Follow-up History</h5>

        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>

      </div>

      <div class="modal-body">

        <div id="historyContent" class="timeline">

          <div class="text-center text-muted">Loading history...</div>

        </div>

      </div>

      <div class="modal-footer">

        <button class="btn btn-secondary" data-dismiss="modal">Close</button>

      </div>

    </div>

  </div>

</div>



<div class="modal fade" id="followUpModal" tabindex="-1">

  <div class="modal-dialog">

    <div class="modal-content">

      <div class="modal-header">

        <h5 class="modal-title">Coordinator Follow-up</h5>

        <button type="button" class="close" data-dismiss="modal">&times;</button>

      </div>

      <div class="modal-body">

        <input type="hidden" id="flowId">

        <textarea id="followupRemark" class="form-control" placeholder="Enter follow-up remark"></textarea>

      </div>

      <div class="modal-footer">

        <button class="btn btn-primary" onclick="saveFollowUp()">Save</button>

      </div>

    </div>

  </div>

</div>



<?php include_once 'footer.php'; ?>

</div>

</div>

</div>



<div class="layout-overlay layout-sidenav-toggle"></div>



<?php include_once 'footer_script.php'; ?>



<script>

$(document).ready(function() {

    $('#example').DataTable({ scrollX: true });

});



function openFollowUp(flowId){

    $('#flowId').val(flowId);

    $('#followupRemark').val('');

    $('#followUpModal').modal('show');

}



function saveFollowUp(){

    let remark = $('#followupRemark').val();

    if(remark.trim() === ''){

        alert('Please enter remark');

        return;

    }

    $.post('', { action: 'followup', flowId: $('#flowId').val(), remark: remark }, function(){

        alert('Follow-up saved');

        location.reload();

    });

}



function markInstalled(flowId){

    if(!confirm('Are you sure installation is completed?')) return;

    $.post('', { action: 'installed', flowId: flowId }, function(res){

        if(res === 'OK'){

            alert('Installation marked completed');

            location.reload();

        } else {

            alert('Unable to mark installed');

        }

    });

}



function openHistoryModal(flowId){

    $('#historyModal').modal('show');

    $('#historyContent').html('<div class="text-center text-muted">Loading history...</div>');

    $.post('', { action: 'load_history', flowId: flowId }, function(res){

        $('#historyContent').html(res);

    });

}

</script>



</body>

</html>


