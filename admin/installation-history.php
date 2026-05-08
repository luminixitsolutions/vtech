<?php
include_once 'config.php';
$flowId = intval($_GET['flowId']);

$sql = "
SELECT a.*, u.Fname
FROM tbl_installation_actions a
LEFT JOIN tbl_users u ON u.id=a.action_by
WHERE a.flow_id='$flowId'
ORDER BY a.action_date DESC
";

$res = $conn->query($sql);
?>

<h4>Follow-up History</h4>
<table class="table table-bordered">
<tr>
    <th>Date</th>
    <th>Action</th>
    <th>Remark</th>
    <th>By</th>
</tr>
<?php while($r=$res->fetch_assoc()){ ?>
<tr>
    <td><?php echo date('d/m/Y H:i',strtotime($r['action_date'])); ?></td>
    <td><?php echo $r['action_type']; ?></td>
    <td><?php echo $r['remarks']; ?></td>
    <td><?php echo $r['Fname']; ?></td>
</tr>
<?php } ?>
</table>
