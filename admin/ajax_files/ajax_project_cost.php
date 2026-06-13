<?php
session_start();
include_once '../config.php';
include_once 'incuserdetails.php';

$user_id = $_SESSION['Admin']['id'];
$row77 = getRecord("SELECT * FROM tbl_users WHERE id='$user_id'");
$Options = adminResolveMenuOptionsFromUserRow($row77);

@$conn->query("CREATE TABLE IF NOT EXISTS tbl_project_cost (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  ProjectId INT NOT NULL DEFAULT 0,
  ProjectSubHeadId INT NOT NULL DEFAULT 0,
  CapacityId INT NOT NULL DEFAULT 0,
  Amount DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  Status TINYINT NOT NULL DEFAULT 1,
  CreatedDate DATE NULL DEFAULT NULL,
  ModifiedDate DATE NULL DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_project_cost (ProjectId, ProjectSubHeadId, CapacityId)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

function projectCostDuplicateSql($conn, $projectId, $subHeadId, $capacityId, $excludeId = 0)
{
    $projectId = (int) $projectId;
    $subHeadId = (int) $subHeadId;
    $capacityId = (int) $capacityId;
    $excludeId = (int) $excludeId;
    $sql = "SELECT id FROM tbl_project_cost
        WHERE ProjectId='$projectId'
          AND ProjectSubHeadId='$subHeadId'
          AND CapacityId='$capacityId'";
    if ($excludeId > 0) {
        $sql .= " AND id!='$excludeId'";
    }

    return $sql;
}

if ($_POST['action'] === 'Add') {
    $ProjectId = (int) ($_POST['ProjectId'] ?? 0);
    $ProjectSubHeadId = (int) ($_POST['ProjectSubHeadId'] ?? 0);
    $CapacityId = (int) ($_POST['CapacityId'] ?? 0);
    $Amount = addslashes(trim($_POST['Amount'] ?? '0'));
    $Status = (int) ($_POST['Status'] ?? 1);
    $CreatedDate = date('Y-m-d');

    if ($ProjectId <= 0 || $ProjectSubHeadId <= 0 || $CapacityId <= 0) {
        echo 0;
        exit;
    }

    if (getRow(projectCostDuplicateSql($conn, $ProjectId, $ProjectSubHeadId, $CapacityId)) > 0) {
        echo 0;
        exit;
    }

    $sql = "INSERT INTO tbl_project_cost SET
        ProjectId='$ProjectId',
        ProjectSubHeadId='$ProjectSubHeadId',
        CapacityId='$CapacityId',
        Amount='$Amount',
        Status='$Status',
        CreatedDate='$CreatedDate'";
    $conn->query($sql);
    echo 1;
    exit;
}

if ($_POST['action'] === 'Edit') {
    $id = (int) ($_POST['id'] ?? 0);
    $ProjectId = (int) ($_POST['ProjectId'] ?? 0);
    $ProjectSubHeadId = (int) ($_POST['ProjectSubHeadId'] ?? 0);
    $CapacityId = (int) ($_POST['CapacityId'] ?? 0);
    $Amount = addslashes(trim($_POST['Amount'] ?? '0'));
    $Status = (int) ($_POST['Status'] ?? 1);
    $ModifiedDate = date('Y-m-d');

    if ($id <= 0 || $ProjectId <= 0 || $ProjectSubHeadId <= 0 || $CapacityId <= 0) {
        echo 0;
        exit;
    }

    if (getRow(projectCostDuplicateSql($conn, $ProjectId, $ProjectSubHeadId, $CapacityId, $id)) > 0) {
        echo 0;
        exit;
    }

    $sql = "UPDATE tbl_project_cost SET
        ProjectId='$ProjectId',
        ProjectSubHeadId='$ProjectSubHeadId',
        CapacityId='$CapacityId',
        Amount='$Amount',
        Status='$Status',
        ModifiedDate='$ModifiedDate'
        WHERE id='$id'";
    $conn->query($sql);
    echo 1;
    exit;
}

if ($_POST['action'] === 'fetch_record') {
    $id = (int) ($_POST['id'] ?? 0);
    $row = getRecord("SELECT * FROM tbl_project_cost WHERE id='$id'");
    echo json_encode(is_array($row) ? $row : array());
    exit;
}

if ($_POST['action'] === 'delete') {
    $id = (int) ($_POST['id'] ?? 0);
    if ($id > 0) {
        $conn->query("DELETE FROM tbl_project_cost WHERE id='$id'");
    }
    echo 'Delete Successfully';
    exit;
}

if ($_POST['action'] === 'view') {
    ?>
<table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Project</th>
            <th>Sub Head</th>
            <th>Capacity</th>
            <th>Amount</th>
            <th>Status</th>
            <?php if (in_array('10', $Options) || in_array('11', $Options)) { ?>
            <th>Action</th>
            <?php } ?>
        </tr>
    </thead>
    <tbody>
    <?php
    $srno = 1;
    $sql = "SELECT pc.*,
            p.Name AS ProjectName,
            sh.Name AS SubHeadName,
            c.Name AS CapacityName
        FROM tbl_project_cost pc
        LEFT JOIN tbl_common_master p ON p.id = pc.ProjectId
        LEFT JOIN tbl_project_sub_head sh ON sh.id = pc.ProjectSubHeadId
        LEFT JOIN tbl_common_master c ON c.id = pc.CapacityId
        ORDER BY pc.id DESC";
    $rx = $conn->query($sql);
    if ($rx) {
        while ($nx = $rx->fetch_assoc()) {
            ?>
        <tr>
            <td><?php echo $srno; ?></td>
            <td><?php echo htmlspecialchars($nx['ProjectName'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($nx['SubHeadName'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($nx['CapacityName'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo number_format((float) ($nx['Amount'] ?? 0), 2); ?></td>
            <td><?php echo ((string) ($nx['Status'] ?? '') === '1') ? "<span style='color:green;'>Active</span>" : "<span style='color:red;'>Inactive</span>"; ?></td>
            <?php if (in_array('10', $Options) || in_array('11', $Options)) { ?>
            <td>
                <?php if (in_array('10', $Options)) { ?>
                <a data-id="<?php echo (int) $nx['id']; ?>" href="javascript:void(0);" class="update" title="Edit"><i class="lnr lnr-pencil mr-2"></i></a>&nbsp;&nbsp;
                <?php } if (in_array('11', $Options)) { ?>
                <a data-id="<?php echo (int) $nx['id']; ?>" href="javascript:void(0);" class="delete" title="Delete"><i class="lnr lnr-trash text-danger"></i></a>
                <?php } ?>
            </td>
            <?php } ?>
        </tr>
            <?php
            $srno++;
        }
    }
    ?>
    </tbody>
</table>
<script type="text/javascript">
$(document).ready(function() {
    $('#example').DataTable({ responsive: true });
});
</script>
    <?php
    exit;
}

echo 0;
