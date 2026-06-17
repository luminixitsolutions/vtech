<?php
session_start();
include_once 'config.php';
include_once 'auth.php';
require_once __DIR__ . '/inc-view-filtered-challans.php';

$user_id = $_SESSION['Admin']['id'];
$MainPage = 'Sell';
$challanListFilter = 'service';
$cfg = viewFilteredChallanConfig($challanListFilter);
$Page = $cfg['page'];

$filterFromDate = $_POST['FromDate'] ?? $_GET['FromDate'] ?? '';
$filterToDate = $_POST['ToDate'] ?? $_GET['ToDate'] ?? '';
$filterProjectId = $_POST['ProjectId'] ?? $_GET['ProjectId'] ?? 'all';
$filterSubHeadId = $_POST['ProjectSubHeadId'] ?? $_GET['ProjectSubHeadId'] ?? 'all';
$filterBranchId = $_POST['BranchId'] ?? $_GET['BranchId'] ?? '';
$filterCustId = $_POST['CustId'] ?? $_GET['CustId'] ?? 'all';
$filterSearchActive = isset($_POST['Search']) || isset($_GET['Search']);

if ($filterBranchId === '' && ($Roll == 1 || $Roll == 7)) {
    $filterBranchId = 'all';
}

$sql = viewFilteredChallanBaseSql($cfg, (int) $Roll, (int) $user_id, (int) $BranchId);
if ($filterCustId !== '' && $filterCustId !== 'all') {
    $sql .= " AND ts.CustId='" . (int) $filterCustId . "'";
}
if ($filterBranchId !== '' && $filterBranchId !== 'all') {
    $sql .= " AND ts.BranchId='" . (int) $filterBranchId . "'";
}
if ($filterProjectId !== '' && $filterProjectId !== 'all') {
    $sql .= " AND tu.ProjectId='" . (int) $filterProjectId . "'";
}
if ($filterSubHeadId !== '' && $filterSubHeadId !== 'all') {
    $sql .= " AND tu.ProjectSubHeadId='" . (int) $filterSubHeadId . "'";
}
if ($filterFromDate !== '') {
    $sql .= " AND ts.InvoiceDate >= '" . $conn->real_escape_string($filterFromDate) . "'";
}
if ($filterToDate !== '') {
    $sql .= " AND ts.InvoiceDate <= '" . $conn->real_escape_string($filterToDate) . "'";
}
$sql .= ' ORDER BY ts.id DESC';
$custSql = viewFilteredChallanCustomerSql($cfg, (int) $Roll, (int) $user_id);
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
    <title><?php echo $Proj_Title; ?> | <?php echo htmlspecialchars($cfg['title']); ?></title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
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
    <h4 class="font-weight-bold py-3 mb-0"><?php echo htmlspecialchars($cfg['title']); ?></h4>
    <p class="text-muted small mb-3"><?php echo htmlspecialchars($cfg['subtitle']); ?></p>

    <div class="card mb-3" style="padding: 10px;">
        <form method="get">
            <div class="form-row">
                <div class="form-group col-md-3 mb-2">
                    <label class="form-label">Project</label>
                    <select class="form-control" id="ProjectId" name="ProjectId" onchange="getSubHead(this.value)">
                        <option value="all" <?php if ($filterProjectId === 'all') { ?>selected<?php } ?>>All Project</option>
                        <?php foreach (getList("SELECT id, Name FROM tbl_common_master WHERE Status='1' AND Roll=24 ORDER BY Name ASC") as $rw) { ?>
                        <option value="<?php echo (int) $rw['id']; ?>" <?php if ((string) $filterProjectId === (string) $rw['id']) { ?>selected<?php } ?>><?php echo htmlspecialchars($rw['Name']); ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group col-md-3 mb-2">
                    <label class="form-label">Project Sub Head</label>
                    <select class="form-control" id="ProjectSubHeadId" name="ProjectSubHeadId">
                        <option value="all" <?php if ($filterSubHeadId === 'all') { ?>selected<?php } ?>>All Sub Head</option>
                        <?php
                        if ($filterProjectId !== '' && $filterProjectId !== 'all') {
                            foreach (getList("SELECT id, Name FROM tbl_project_sub_head WHERE Status='1' AND UnderBy='" . (int) $filterProjectId . "' ORDER BY Name ASC") as $rw) {
                                ?>
                        <option value="<?php echo (int) $rw['id']; ?>" <?php if ((string) $filterSubHeadId === (string) $rw['id']) { ?>selected<?php } ?>><?php echo htmlspecialchars($rw['Name']); ?></option>
                        <?php
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group col-md-3 mb-2">
                    <label class="form-label">Store</label>
                    <select class="form-control" name="BranchId" id="BranchId">
                        <?php
                        if ($Roll == 1 || $Roll == 7) {
                            echo '<option value="all"' . ($filterBranchId === 'all' ? ' selected' : '') . '>All</option>';
                            $branchSql = "SELECT id, Name FROM tbl_branch WHERE Status='1' ORDER BY Name ASC";
                        } elseif ($Roll == 26) {
                            $branchSql = "SELECT id, Name FROM tbl_branch WHERE Status='1' AND id='" . (int) $_SESSION['storeid'] . "'";
                        } else {
                            $branchSql = "SELECT id, Name FROM tbl_branch WHERE Status='1' AND id='" . (int) $BranchId . "'";
                        }
                        foreach (getList($branchSql) as $result) {
                            $sel = ((string) $filterBranchId === (string) $result['id']) ? ' selected' : '';
                            echo '<option value="' . (int) $result['id'] . '"' . $sel . '>' . htmlspecialchars($result['Name']) . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group col-md-3 mb-2">
                    <label class="form-label">Customer</label>
                    <select class="select2-demo form-control" name="CustId" id="CustId">
                        <option value="all" <?php if ($filterCustId === 'all') { ?>selected<?php } ?>>All</option>
                        <?php foreach (getList($custSql) as $result) { ?>
                        <option value="<?php echo (int) $result['id']; ?>" <?php if ((string) $filterCustId === (string) $result['id']) { ?>selected<?php } ?>><?php echo htmlspecialchars($result['Fname']); ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group col-md-3 mb-2">
                    <label class="form-label">From Date</label>
                    <input type="date" name="FromDate" class="form-control" value="<?php echo htmlspecialchars($filterFromDate, ENT_QUOTES, 'UTF-8'); ?>">
                </div>
                <div class="form-group col-md-3 mb-2">
                    <label class="form-label">To Date</label>
                    <input type="date" name="ToDate" class="form-control" value="<?php echo htmlspecialchars($filterToDate, ENT_QUOTES, 'UTF-8'); ?>">
                </div>
                <div class="form-group col-md-3 mb-2 d-flex align-items-end">
                    <input type="hidden" name="Search" value="1">
                    <button type="submit" class="btn btn-primary mr-2">Search</button>
                    <?php if ($filterSearchActive) { ?>
                    <a href="<?php echo htmlspecialchars($cfg['self_url']); ?>" class="btn btn-secondary">Clear</a>
                    <?php } ?>
                </div>
            </div>
        </form>
    </div>

    <div class="card" style="padding: 10px;">
        <div class="table-responsive">
            <table id="example" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>DM No</th>
                        <th>Store</th>
                        <th>Customer Name</th>
                        <th>Contact No</th>
                        <th>Project</th>
                        <th>Project Sub Head</th>
                        <th><?php echo htmlspecialchars($cfg['type_column']); ?></th>
                        <th>Total Stock Qty</th>
                        <th>Date</th>
                        <th>Print</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $res = $conn->query($sql);
                    if ($res) {
                        while ($row = $res->fetch_assoc()) {
                            $row2 = getRecord("SELECT SUM(Qty) AS TotQty FROM tbl_sell_products WHERE SellId='" . (int) $row['id'] . "' AND ProductName != ''");
                            $totQty = (int) ($row2['TotQty'] ?? 0);
                            $row13 = getRecord("SELECT Name FROM tbl_common_master WHERE id='" . (int) ($row['ProjectId'] ?? 0) . "'");
                            $row131 = getRecord("SELECT Name FROM tbl_project_sub_head WHERE id='" . (int) ($row['ProjectSubHeadId'] ?? 0) . "'");
                            ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo htmlspecialchars((string) $row['InvoiceNo']); ?></td>
                        <td><?php echo htmlspecialchars((string) ($row['Branch'] ?? '')); ?></td>
                        <td><?php echo htmlspecialchars((string) $row['CustName']); ?></td>
                        <td><?php echo htmlspecialchars((string) $row['CellNo']); ?></td>
                        <td><?php echo htmlspecialchars((string) ($row13['Name'] ?? '')); ?></td>
                        <td><?php echo htmlspecialchars((string) ($row131['Name'] ?? '')); ?></td>
                        <td><?php echo htmlspecialchars($cfg['type_label']); ?></td>
                        <td><?php echo $totQty; ?></td>
                        <td><?php echo $row['InvoiceDate'] ? date('d/m/Y', strtotime(str_replace('-', '/', $row['InvoiceDate']))) : ''; ?></td>
                        <td>
                            <a href="print-delivery-challan.php?id=<?php echo (int) $row['id']; ?>" target="_blank" title="Print">
                                <i class="lnr lnr-printer text-danger"></i>
                            </a>
                        </td>
                    </tr>
                            <?php
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
<script type="text/javascript">
function getSubHead(id) {
    $.ajax({
        type: 'POST',
        url: 'ajax_files/ajax_dropdown.php',
        data: { action: 'getSubHead', id: id },
        success: function (html) {
            var opts = '<option value="all">All Sub Head</option>';
            $(html).find('option').each(function () {
                if (!$(this).prop('disabled')) {
                    opts += this.outerHTML;
                }
            });
            $('#ProjectSubHeadId').html(opts);
        }
    });
}
$(function () {
    if ($.fn.DataTable && $('#example').length) {
        $('#example').DataTable({ pageLength: 25, order: [[0, 'asc']] });
    }
});
</script>
</body>
</html>
