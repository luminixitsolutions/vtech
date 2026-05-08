<?php
session_start();
include_once 'config.php';
include_once 'auth.php';

$user_id = $_SESSION['Admin']['id'];
$MainPage = 'File-Submission-Reminder';
$Page = 'File-Submission-Reminder';

$sql = "
SELECT
    ti.FileInHandDate,
    tu.Fname,
    tu.Lname,
    tu.Phone,
    tu.BeneficiaryId,
    tu.Address,
    tu.District,
    tu.Village,
    tu.Taluka,
    tu.ProjectId,
    tu.ProjectSubHeadId,
    st.Name AS StateName,
    proj.Name AS ProjectName,
    sub.Name AS SubHeadName,
    pcm.Name AS PumpCapacityName
FROM tbl_installations ti
INNER JOIN tbl_users tu ON tu.id = ti.CustId
LEFT JOIN tbl_state st ON st.id = tu.StateId
LEFT JOIN tbl_common_master proj ON proj.id = tu.ProjectId
LEFT JOIN tbl_common_master sub ON sub.id = tu.ProjectSubHeadId
LEFT JOIN tbl_common_master pcm ON pcm.id = tu.PumpCapacity
WHERE ti.Type = 2
  AND ti.FileInHand = 'Yes'
  AND (ti.CircleOfficeStatus IS NULL OR ti.CircleOfficeStatus <> 'Yes')
ORDER BY ti.FileInHandDate DESC, ti.id DESC
";

$res = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
    <title><?php echo $Proj_Title; ?> | File submission reminder</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <?php include_once 'header_script.php'; ?>
</head>
<body>
<div class="layout-wrapper layout-2">
<div class="layout-inner">

<?php include_once 'header.php'; ?>

<div class="layout-container">
<?php include_once 'top_header.php'; ?>

<div class="layout-content">
<div class="container-fluid flex-grow-1 container-p-y">

<h4 class="font-weight-bold py-3 mb-0">File submission reminder</h4>


<div class="card" style="padding: 10px;">
    <div class="card-datatable table-responsive">
        <table id="example" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Beneficiary ID</th>
                    <th>Customer name</th>
                    <th>Contact</th>
                    <th>Project</th>
                    <th>Sub head</th>
                    <th>Pump capacity</th>
                    <th>State</th>
                    <th>District</th>
                    <th>Village</th>
                    <th>File in hand date</th>
                </tr>
            </thead>
            <tbody>
<?php
$i = 1;
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $fih = $row['FileInHandDate'];
        if (!empty($fih) && $fih !== '0000-00-00') {
            $fihDisp = date('d/m/Y', strtotime($fih));
            $fihOrder = $fih;
        } else {
            $fihDisp = '—';
            $fihOrder = '';
        }
        $custName = trim($row['Fname'] . ' ' . $row['Lname']);
?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td><?php echo htmlspecialchars((string) $row['BeneficiaryId'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($custName, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars((string) $row['Phone'], ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars((string) ($row['ProjectName'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars((string) ($row['SubHeadName'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars((string) ($row['PumpCapacityName'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars((string) ($row['StateName'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars((string) ($row['District'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars((string) ($row['Village'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                    <td data-order="<?php echo htmlspecialchars($fihOrder, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($fihDisp, ENT_QUOTES, 'UTF-8'); ?></td>
                </tr>
<?php
        $i++;
    }
}
?>
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
<script>
$(document).ready(function () {
    $('#example').DataTable({
        scrollX: true,
        order: [[10, 'desc']]
    });
});
</script>
</body>
</html>
