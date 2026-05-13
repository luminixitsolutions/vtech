<?php
session_start();
include_once '../config.php';
include_once '../auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Item-Transfer-Workflow";
$Page = "View-Store-To-Store-Transfers";
$row77 = getRecord("SELECT Roll, BranchId, Options FROM tbl_users WHERE id='$user_id'");
$Roll = $row77['Roll'] ?? 0;
$BranchId = $row77['BranchId'] ?? 0;
$Options = isset($row77['Options']) ? explode(',', $row77['Options']) : array();
$is_allowed = ($Roll == 27 || $Roll == 1 || $Roll == 7 || in_array('72', $Options));
if (!$is_allowed) {
    echo "<script>alert('Access denied.'); window.location.href='../dashboard.php';</script>";
    exit;
}
$where = "1=1";
if ($Roll == 27) $where = "(t.FromBranchId='$BranchId' OR t.ToBranchId='$BranchId')";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
    <title><?php echo $Proj_Title; ?> - View Store to Store Transfers</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include_once '../header_script.php'; ?>
</head>
<body>
<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <?php include_once '../sidebar.php'; ?>
        <div class="layout-container">
            <?php include_once '../top_header.php'; ?>
            <div class="layout-content">
                <div class="container-fluid flex-grow-1 container-p-y">
                    <h4 class="font-weight-bold py-3 mb-0">Store to Store – Transfer History</h4>
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="example">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Transfer Date</th>
                                        <th>From Store</th>
                                        <th>To Store</th>
                                        <th>Items / Qty</th>
                                        <th>Narration</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $sql = "SELECT t.*, tb1.Name AS FromStoreName, tb2.Name AS ToStoreName FROM tbl_store_to_store_transfer t LEFT JOIN tbl_branch tb1 ON t.FromBranchId=tb1.id LEFT JOIN tbl_branch tb2 ON t.ToBranchId=tb2.id WHERE $where ORDER BY t.id DESC";
                                    $res = $conn->query($sql);
                                    $i = 1;
                                    while ($row = $res->fetch_assoc()) {
                                        $tid = $row['id'];
                                        $cnt = getRecord("SELECT COUNT(*) AS c FROM tbl_store_to_store_transfer_details WHERE TransferId='$tid'");
                                        $tot = getRecord("SELECT COALESCE(SUM(Qty),0) AS t FROM tbl_store_to_store_transfer_details WHERE TransferId='$tid'");
                                        ?>
                                        <tr>
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo $row['TransferDate']; ?></td>
                                            <td><?php echo htmlspecialchars($row['FromStoreName']); ?></td>
                                            <td><?php echo htmlspecialchars($row['ToStoreName']); ?></td>
                                            <td><?php echo $cnt['c']; ?> line(s), <?php echo $tot['t']; ?> unit(s)</td>
                                            <td><?php echo htmlspecialchars($row['Narration']); ?></td>
                                        </tr>
                                        <?php $i++; }
                                    if ($i == 1) echo '<tr><td colspan="6" class="text-muted">No transfers found.</td></tr>';
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                <a href="store-to-store-transfer.php" class="btn btn-primary">New Store to Store Transfer</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php include_once '../footer.php'; ?>
            </div>
        </div>
    </div>
</div>
<?php include_once '../footer_script.php'; ?>
<script>
$(document).ready(function() { $('#example').DataTable({ pageLength: 25, order: [[1, 'desc']] }); });
</script>
</body>
</html>
