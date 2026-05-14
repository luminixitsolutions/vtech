<?php
session_start();
include_once __DIR__ . '/config.php';
include_once __DIR__ . '/auth.php';
include_once __DIR__ . '/../exeapp/leave_request_helpers.php';

$user_id = (int) $_SESSION['Admin']['id'];
$MainPage = 'Leave-Requests';
$Page = 'Leave-Requests-List';

$filterStatus = isset($_GET['status']) ? $conn->real_escape_string($_GET['status']) : 'Pending';
if (!in_array($filterStatus, array('All', 'Pending', 'Approved', 'Rejected'), true)) {
    $filterStatus = 'Pending';
}
$year = isset($_GET['y']) ? (int) $_GET['y'] : (int) date('Y');
$month = isset($_GET['m']) ? (int) $_GET['m'] : (int) date('n');
if ($month < 1 || $month > 12) {
    $month = (int) date('n');
}
if ($year < 2000 || $year > 2100) {
    $year = (int) date('Y');
}
$m2 = str_pad((string) $month, 2, '0', STR_PAD_LEFT);

$approveCommentRequired = isset($_GET['approve_err']) && $_GET['approve_err'] === '1';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['leave_action']) && !empty($_POST['req_id'])) {
    $reqId = (int) $_POST['req_id'];
    $at = $conn->real_escape_string(date('Y-m-d H:i:s'));
    $ry = isset($_POST['ret_y']) ? (int) $_POST['ret_y'] : $year;
    $rm = isset($_POST['ret_m']) ? (int) $_POST['ret_m'] : $month;
    if ($rm < 1 || $rm > 12) {
        $rm = (int) date('n');
    }
    if ($ry < 2000 || $ry > 2100) {
        $ry = (int) date('Y');
    }
    $rs = isset($_POST['ret_status']) ? trim((string) $_POST['ret_status']) : $filterStatus;
    if (!in_array($rs, array('All', 'Pending', 'Approved', 'Rejected'), true)) {
        $rs = 'Pending';
    }
    $qsBase = array('y' => $ry, 'm' => $rm, 'status' => $rs);

    if ($reqId < 1) {
        header('Location: leave-requests.php?' . http_build_query($qsBase));
        exit;
    }
    $r = $conn->query("SELECT id,Status FROM tbl_leave_request WHERE id='$reqId' LIMIT 1");
    if ($r && $row0 = $r->fetch_assoc()) {
        if ($row0['Status'] === 'Pending' && $_POST['leave_action'] === 'approve') {
            $comment = trim((string) ($_POST['approve_comment'] ?? ''));
            if ($comment === '') {
                $qsBase['approve_err'] = '1';
                header('Location: leave-requests.php?' . http_build_query($qsBase));
                exit;
            }
            $c = $conn->real_escape_string(substr($comment, 0, 1000));
            $conn->query("UPDATE tbl_leave_request SET Status='Approved', ApprovedBy=$user_id, ApprovedAt='$at', ApprovedComment='$c', RejectedBy=NULL, RejectedAt=NULL WHERE id=$reqId AND Status='Pending' LIMIT 1");
        } elseif ($row0['Status'] === 'Pending' && $_POST['leave_action'] === 'reject') {
            $conn->query("UPDATE tbl_leave_request SET Status='Rejected', RejectedBy=$user_id, RejectedAt='$at', ApprovedBy=NULL, ApprovedAt=NULL, ApprovedComment=NULL WHERE id=$reqId AND Status='Pending' LIMIT 1");
        }
    }
    header('Location: leave-requests.php?' . http_build_query($qsBase));
    exit;
}

$where = '1=1';
if ($filterStatus !== 'All') {
    $st = $conn->real_escape_string($filterStatus);
    $where .= " AND lr.Status = '$st'";
}
$listQ = "SELECT lr.*, u.Fname, u.Lname, u.Phone, u.Phone2
          FROM tbl_leave_request lr
          LEFT JOIN tbl_users u ON lr.UserId = u.id
          WHERE $where
          ORDER BY (lr.Status='Pending') DESC, lr.CreatedAt DESC";
$listRes = $conn->query($listQ);
$rows = array();
if ($listRes) {
    while ($row = $listRes->fetch_assoc()) {
        $rows[] = $row;
    }
}

$sumApprovedInMonth = 0;
$sumQ = "SELECT * FROM tbl_leave_request WHERE Status='Approved'";
$sumR = $conn->query($sumQ);
if ($sumR) {
    while ($lr = $sumR->fetch_assoc()) {
        if (!empty($lr['FromDate']) && !empty($lr['ToDate'])) {
            $sumApprovedInMonth += leave_request_days_in_month($lr['FromDate'], $lr['ToDate'], $year, $month);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
    <title><?php echo $Proj_Title; ?> | Leave requests</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <?php include_once __DIR__ . '/header_script.php'; ?>
    <style>
        .status-p { color: #b8860b; font-weight: 600; }
        .status-a { color: #198754; font-weight: 600; }
        .status-r { color: #b02a37; font-weight: 600; }
        div.dataTables_wrapper div.dataTables_filter { margin-bottom: 0.5rem; }
    </style>
</head>
<body>
<div class="layout-wrapper layout-2">
<div class="layout-inner">

<?php include_once __DIR__ . '/header.php'; ?>

<div class="layout-container">
<?php include_once __DIR__ . '/top_header.php'; ?>

<div class="layout-content">
<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Employee leave requests</h4>
<p class="text-muted">Approve or reject leave applications. <strong>Approved</strong> leave counts are summed by the calendar month you select (each day in range that falls in that month).</p>

<?php if ($approveCommentRequired) { ?>
<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    Please enter an approval comment before confirming.
</div>
<?php } ?>

<div class="row mb-3">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body py-2">
                <p class="mb-1">Total <strong>approved</strong> leave days in month <strong><?php echo (int) $year; ?>-<?php echo $m2; ?></strong> (all staff):</p>
                <p class="h4 mb-0" style="color: #c9302c;"><?php echo (int) $sumApprovedInMonth; ?> day(s)</p>
            </div>
        </div>
    </div>
</div>

<div class="card" style="padding:10px;">
    <form class="form-inline flex-wrap" method="get" action="" style="gap:8px;">
        <label>Status</label>
        <select name="status" class="form-control form-control-sm" onchange="this.form.submit()">
            <option value="All" <?php echo $filterStatus === 'All' ? 'selected' : ''; ?>>All</option>
            <option value="Pending" <?php echo $filterStatus === 'Pending' ? 'selected' : ''; ?>>Pending</option>
            <option value="Approved" <?php echo $filterStatus === 'Approved' ? 'selected' : ''; ?>>Approved</option>
            <option value="Rejected" <?php echo $filterStatus === 'Rejected' ? 'selected' : ''; ?>>Rejected</option>
        </select>
        <label>Month</label>
        <select name="m" class="form-control form-control-sm">
            <?php for ($i = 1; $i <= 12; $i++) { ?>
            <option value="<?php echo $i; ?>" <?php echo $month === $i ? 'selected' : ''; ?>><?php echo date('F', mktime(0, 0, 0, $i, 1)); ?></option>
            <?php } ?>
        </select>
        <label>Year</label>
        <input type="number" class="form-control form-control-sm" name="y" value="<?php echo (int) $year; ?>" min="2000" max="2100" style="width:90px;" />
        <button type="submit" class="btn btn-sm btn-primary">Filter</button>
    </form>

    <div class="table-responsive mt-3">
        <table id="tblLeaveRequests" class="table table-striped table-bordered display" style="width:100%;">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Employee</th>
                    <th>Phone</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Days (range)</th>
                    <th>Session</th>
                    <th>Reason</th>
                    <th>Attachment</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Approval comment</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
            foreach ($rows as $r) {
                $reasonPlain = trim((string) ($r['Reason'] ?? ''));
                $reasonCell = $reasonPlain !== '' ? nl2br(htmlspecialchars($reasonPlain, ENT_QUOTES, 'UTF-8')) : '—';
                ?>
                <tr>
                    <td><?php echo (int) $r['id']; ?></td>
                    <td><?php echo htmlspecialchars(($r['Fname'] ?? '') . ' ' . ($r['Lname'] ?? '')); ?></td>
                    <td><?php echo htmlspecialchars($r['Phone'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($r['FromDate'] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($r['ToDate'] ?? ''); ?></td>
                    <td><?php
                        $ld = isset($r['LeaveDays']) ? (float) $r['LeaveDays'] : 0;
                        echo htmlspecialchars(rtrim(rtrim(sprintf('%.2f', $ld), '0'), '.'));
                    ?></td>
                    <td><?php
                        $hs = trim((string) ($r['HalfSession'] ?? ''));
                        echo $hs !== '' ? htmlspecialchars($hs, ENT_QUOTES, 'UTF-8') : '—';
                    ?></td>
                    <td><?php echo $reasonCell; ?></td>
                    <td><?php
                        $at = trim((string) ($r['Attachment'] ?? ''));
                        if ($at !== '') {
                            $u = '../uploads/' . rawurlencode($at);
                            echo '<a href="' . htmlspecialchars($u, ENT_QUOTES, 'UTF-8') . '" target="_blank" rel="noopener">View</a>';
                        } else {
                            echo '—';
                        }
                    ?></td>
                    <td>
                        <span class="<?php
                        if ($r['Status'] === 'Approved') {
                            echo 'status-a';
                        } elseif ($r['Status'] === 'Rejected') {
                            echo 'status-r';
                        } else {
                            echo 'status-p';
                        }
                        ?>"><?php echo htmlspecialchars($r['Status'] ?? ''); ?></span>
                    </td>
                    <td><?php echo htmlspecialchars($r['CreatedAt'] ?? ''); ?></td>
                    <td><?php
                    $ac = trim((string) ($r['ApprovedComment'] ?? ''));
                    echo $ac !== '' ? nl2br(htmlspecialchars($ac, ENT_QUOTES, 'UTF-8')) : '—';
                    ?></td>
                    <td>
                        <?php if (($r['Status'] ?? '') === 'Pending') { ?>
                        <button type="button" class="btn btn-sm btn-success js-open-approve-leave" data-req-id="<?php echo (int) $r['id']; ?>">Approve</button>
                        <form method="post" class="d-inline" onsubmit="return confirm('Reject this request?');" style="display:inline;">
                            <input type="hidden" name="req_id" value="<?php echo (int) $r['id']; ?>" />
                            <input type="hidden" name="leave_action" value="reject" />
                            <input type="hidden" name="ret_y" value="<?php echo (int) $year; ?>" />
                            <input type="hidden" name="ret_m" value="<?php echo (int) $month; ?>" />
                            <input type="hidden" name="ret_status" value="<?php echo htmlspecialchars($filterStatus, ENT_QUOTES, 'UTF-8'); ?>" />
                            <button type="submit" class="btn btn-sm btn-outline-danger">Reject</button>
                        </form>
                        <?php } else { ?>
                        —
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>
</div>
</div>

<div class="modal fade" id="modalApproveLeave" tabindex="-1" role="dialog" aria-labelledby="modalApproveLeaveTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="post" action="" id="frmApproveLeave">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalApproveLeaveTitle">Approve leave request</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small mb-2">Add a short comment for the record (required).</p>
                    <label for="approve_comment">Comment</label>
                    <textarea class="form-control" name="approve_comment" id="approve_comment" rows="4" maxlength="1000" required placeholder="e.g. Approved as per team coverage"></textarea>
                    <input type="hidden" name="leave_action" value="approve" />
                    <input type="hidden" name="req_id" value="" />
                    <input type="hidden" name="ret_y" value="<?php echo (int) $year; ?>" />
                    <input type="hidden" name="ret_m" value="<?php echo (int) $month; ?>" />
                    <input type="hidden" name="ret_status" value="<?php echo htmlspecialchars($filterStatus, ENT_QUOTES, 'UTF-8'); ?>" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Approve request</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php include_once __DIR__ . '/footer.php'; ?>
</div>
</div>
<div class="layout-overlay layout-sidenav-toggle"></div>
</div>
<?php include_once __DIR__ . '/footer_script.php'; ?>
<script type="text/javascript">
jQuery(function ($) {
    $(document).on('click', '.js-open-approve-leave', function () {
        var id = $(this).data('req-id');
        $('#frmApproveLeave input[name="req_id"]').val(id);
        $('#frmApproveLeave textarea[name="approve_comment"]').val('');
        $('#modalApproveLeave').modal('show');
    });

    if (!$('#tblLeaveRequests').length) { return; }
    $('#tblLeaveRequests').DataTable({
        scrollX: true,
        pageLength: 25,
        order: [[0, 'desc']],
        columnDefs: [
            { orderable: false, targets: [10] }
        ],
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                text: 'Export Excel',
                title: 'Leave_requests_<?php echo (int) $year; ?>_<?php echo htmlspecialchars($m2, ENT_QUOTES, 'UTF-8'); ?>',
                className: 'btn btn-sm btn-primary mb-2',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
                    format: {
                        body: function (data, row, column, node) {
                            if (data == null) { return ''; }
                            if (column === 6 || column === 9) {
                                return String(data).replace(/<br\s*\/?\s*>/gi, ' ').replace(/&nbsp;/g, ' ').replace(/<[^>]+>/g, '');
                            }
                            return $('<div>').html(data).text();
                        }
                    }
                }
            }
        ]
    });
});
</script>
</body>
</html>
