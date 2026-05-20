<?php
session_start();
include_once 'config.php';
include_once 'auth.php';

$user_id = $_SESSION['Admin']['id'];
$MainPage = 'File-Submission-Reminder';
$Page = 'File-Submission-Reminder';

$projectId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$projectName = isset($_GET['name']) ? trim((string) $_GET['name']) : '';
$subHeadId = isset($_GET['subheadid']) ? (int) $_GET['subheadid'] : 0;
$subHeadName = isset($_GET['subname']) ? trim((string) $_GET['subname']) : '';

$filterCustId = isset($_GET['CustId']) ? (int) $_GET['CustId'] : 0;
$filterFromDate = isset($_GET['FromDate']) ? trim((string) $_GET['FromDate']) : '';
$filterToDate = isset($_GET['ToDate']) ? trim((string) $_GET['ToDate']) : '';
if ($filterFromDate !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $filterFromDate)) {
    $filterFromDate = '';
}
if ($filterToDate !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $filterToDate)) {
    $filterToDate = '';
}

$pendingWhere = fileSubmissionPendingSql();
$showList = ($projectId > 0 && $subHeadId > 0);
$showSubHeads = ($projectId > 0 && !$showList);

if ($projectId > 0) {
    $projRow = getRecord("SELECT Name FROM tbl_common_master WHERE id='$projectId' AND Roll=24 LIMIT 1");
    if (!empty($projRow['Name'])) {
        $projectName = (string) $projRow['Name'];
    }
}

if ($showList && $subHeadId > 0) {
    $subRow = getRecord("SELECT Name FROM tbl_project_sub_head WHERE id='$subHeadId' AND UnderBy='$projectId' LIMIT 1");
    if (!empty($subRow['Name'])) {
        $subHeadName = (string) $subRow['Name'];
    }
}

$listSql = '';
$res = null;
$filterCustomers = array();
$filterProjects = array();
$filterSubHeads = array();

if ($showList) {
    $listSql = "
SELECT
    ti.FileInHandDate,
    tu.Fname,
    tu.Lname,
    tu.Phone,
    tu.BeneficiaryId,
    tu.District,
    tu.Village,
    st.Name AS StateName,
    proj.Name AS ProjectName,
    sub.Name AS SubHeadName,
    pcm.Name AS PumpCapacityName
FROM tbl_installations ti
INNER JOIN tbl_users tu ON tu.id = ti.CustId
LEFT JOIN tbl_state st ON st.id = tu.StateId
LEFT JOIN tbl_common_master proj ON proj.id = tu.ProjectId
LEFT JOIN tbl_project_sub_head sub ON sub.id = tu.ProjectSubHeadId
LEFT JOIN tbl_common_master pcm ON pcm.id = tu.PumpCapacity
WHERE $pendingWhere
  AND tu.ProjectId = '$projectId'
  AND tu.ProjectSubHeadId = '$subHeadId'";

    if ($filterCustId > 0) {
        $listSql .= " AND tu.id = '$filterCustId'";
    }
    if ($filterFromDate !== '') {
        $escFrom = $conn->real_escape_string($filterFromDate);
        $listSql .= " AND ti.FileInHandDate >= '$escFrom'";
    }
    if ($filterToDate !== '') {
        $escTo = $conn->real_escape_string($filterToDate);
        $listSql .= " AND ti.FileInHandDate <= '$escTo'";
    }

    $listSql .= " ORDER BY ti.FileInHandDate DESC, ti.id DESC";
    $res = $conn->query($listSql);

    $filterProjects = getList("SELECT id, Name FROM tbl_common_master WHERE Status=1 AND Roll=24 ORDER BY Name");
    $filterSubHeads = getList("SELECT id, Name FROM tbl_project_sub_head WHERE Status=1 AND UnderBy='$projectId' ORDER BY Name");
    $filterCustomers = getList("
        SELECT DISTINCT tu.id, tu.Fname, tu.Lname, tu.BeneficiaryId, tu.Phone
        FROM tbl_installations ti
        INNER JOIN tbl_users tu ON tu.id = ti.CustId
        WHERE $pendingWhere
          AND tu.ProjectId = '$projectId'
          AND tu.ProjectSubHeadId = '$subHeadId'
        ORDER BY tu.Fname, tu.Lname
    ");
}
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
    <title><?php echo $Proj_Title; ?> | File submission reminder</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <?php include_once 'header_script.php'; ?>
    <style>
    .fsr-page { max-width: 1200px; margin: 0 auto; }
    .fsr-hero {
        background: linear-gradient(135deg, #1e3a5f 0%, #2d5a87 100%);
        border-radius: 8px;
        color: #fff;
        padding: 0.85rem 1.15rem;
        margin-bottom: 1rem;
        box-shadow: 0 4px 14px rgba(30, 58, 95, 0.2);
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
    }
    .fsr-hero-text { flex: 1; min-width: 200px; }
    .fsr-hero h1 { font-size: 1.1rem; font-weight: 700; margin: 0 0 0.15rem; }
    .fsr-hero p { margin: 0; opacity: 0.88; font-size: 0.8rem; }
    .fsr-hero-stat {
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 8px;
        padding: 0.4rem 0.85rem;
        font-size: 0.75rem;
        white-space: nowrap;
    }
    .fsr-hero-stat strong { font-size: 1.25rem; font-weight: 700; margin-right: 0.35rem; }
    .fsr-back {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        color: #2d5a87;
        font-weight: 600;
        font-size: 0.8rem;
        margin-bottom: 0.75rem;
        text-decoration: none !important;
    }
    .fsr-back:hover { color: #1e3a5f; }
    .fsr-panel {
        background: #fff;
        border-radius: 8px;
        border: 1px solid #e8ecf1;
        box-shadow: 0 1px 6px rgba(0, 0, 0, 0.04);
        padding: 0.85rem 0.75rem 0.25rem;
        margin-bottom: 1rem;
    }
    .fsr-panel-title {
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: #6c757d;
        margin: 0 0 0.65rem;
        padding-left: 0.25rem;
    }
    .fsr-tiles-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(125px, 125px));
        gap: 12px;
        justify-content: start;
    }
    .fsr-tiles-grid--sub {
        grid-template-columns: repeat(auto-fill, minmax(138px, 138px));
    }
    .fsr-tile-link {
        display: block;
        width: 100%;
        aspect-ratio: 1;
        text-decoration: none !important;
    }
    .fsr-tile {
        width: 100%;
        height: 100%;
        border-radius: 6px;
        padding: 0.35rem 0.3rem 0.3rem;
        position: relative;
        overflow: hidden;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        gap: 0.15rem;
    }
    .fsr-tile-link:hover .fsr-tile {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
    }
    .fsr-tile--project {
        background: linear-gradient(135deg, #f59e0b 0%, #ea580c 100%);
        color: #fff;
        box-shadow: 0 2px 8px rgba(234, 88, 12, 0.25);
    }
    .fsr-tile--project.fsr-tile--zero {
        background: #f1f5f9;
        color: #64748b;
        box-shadow: none;
        border: 1px solid #e2e8f0;
    }
    .fsr-tile--sub {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-top: 3px solid #3d7ab5;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        color: #1e293b;
    }
    .fsr-tile--sub.fsr-tile--zero { border-top-color: #cbd5e1; }
    .fsr-tile--sub.fsr-tile--active { border-top-color: #ea580c; }
    .fsr-tile-icon {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
        margin-bottom: 0.1rem;
    }
    .fsr-tile--project .fsr-tile-icon { background: rgba(255, 255, 255, 0.2); }
    .fsr-tile--project.fsr-tile--zero .fsr-tile-icon { background: #e2e8f0; color: #94a3b8; }
    .fsr-tile--sub .fsr-tile-icon { background: #eff6ff; color: #2563eb; font-size: 0.75rem; }
    .fsr-tile--sub.fsr-tile--active .fsr-tile-icon { background: #fff7ed; color: #ea580c; }
    .fsr-tile-name {
        font-size: 0.88rem;
        font-weight: 700;
        line-height: 1.25;
        margin: 0;
        width: 100%;
        padding: 0 0.12rem;
        word-break: break-word;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .fsr-tiles-grid--sub .fsr-tile-name {
        font-size: 0.8rem;
        line-height: 1.2;
        -webkit-line-clamp: 3;
    }
    .fsr-tile-meta {
        line-height: 1.15;
        margin-top: 0.12rem;
    }
    .fsr-tile-count {
        font-size: 1.5rem;
        font-weight: 800;
        letter-spacing: -0.02em;
        display: block;
    }
    .fsr-tile-label {
        font-size: 0.68rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        opacity: 0.85;
        display: block;
        margin-top: 0.1rem;
    }
    .fsr-breadcrumb {
        background: #f8fafc;
        border-radius: 8px;
        padding: 0.6rem 1rem;
        font-size: 0.875rem;
        margin-bottom: 1rem;
    }
    .fsr-breadcrumb a { color: #2563eb; font-weight: 500; }
    </style>
</head>
<body>
<div class="layout-wrapper layout-2">
<div class="layout-inner">

<?php include_once 'header.php'; ?>

<div class="layout-container">
<?php include_once 'top_header.php'; ?>

<div class="layout-content">
<div class="container-fluid flex-grow-1 container-p-y">

<?php if ($showList) { ?>
    <h4 class="font-weight-bold py-3 mb-0">File submission reminder</h4>
    <nav class="fsr-breadcrumb" aria-label="breadcrumb">
        <a href="file-submission-reminder.php">Project heads</a>
        &nbsp;&rsaquo;&nbsp;
        <a href="file-submission-reminder.php?id=<?php echo $projectId; ?>&amp;name=<?php echo urlencode($projectName); ?>"><?php echo htmlspecialchars($projectName, ENT_QUOTES, 'UTF-8'); ?></a>
        &nbsp;&rsaquo;&nbsp;
        <span class="text-dark font-weight-bold"><?php echo htmlspecialchars($subHeadName, ENT_QUOTES, 'UTF-8'); ?></span>
    </nav>

    <div class="card mb-3" style="padding: 10px;">
        <form method="get" action="file-submission-reminder.php" id="fsrFilterForm" class="form-row align-items-end">
            <div class="form-group col-md-2 mb-2 mb-md-0">
                <label class="form-label">Project head</label>
                <select name="id" id="filterProjectId" class="form-control" required>
                    <option value="">Select project</option>
                    <?php foreach ($filterProjects as $fp) {
                        $fpId = (int) $fp['id'];
                        $sel = ($projectId === $fpId) ? ' selected' : '';
                        echo '<option value="' . $fpId . '"' . $sel . '>' . htmlspecialchars((string) $fp['Name'], ENT_QUOTES, 'UTF-8') . '</option>';
                    } ?>
                </select>
            </div>
            <div class="form-group col-md-3 mb-2 mb-md-0">
                <label class="form-label">Sub head (project wise)</label>
                <select name="subheadid" id="filterSubHeadId" class="form-control" required>
                    <option value="">Select sub head</option>
                    <?php foreach ($filterSubHeads as $fsh) {
                        $fshId = (int) $fsh['id'];
                        $sel = ($subHeadId === $fshId) ? ' selected' : '';
                        echo '<option value="' . $fshId . '"' . $sel . '>' . htmlspecialchars((string) $fsh['Name'], ENT_QUOTES, 'UTF-8') . '</option>';
                    } ?>
                </select>
            </div>
            <div class="form-group col-md-3 mb-2 mb-md-0">
                <label class="form-label">Customer</label>
                <select name="CustId" id="filterCustId" class="form-control select2-demo">
                    <option value="0">All customers</option>
                    <?php foreach ($filterCustomers as $fc) {
                        $fcId = (int) $fc['id'];
                        $sel = ($filterCustId === $fcId) ? ' selected' : '';
                        $label = trim($fc['Fname'] . ' ' . $fc['Lname']);
                        if (!empty($fc['BeneficiaryId'])) {
                            $label .= ' (' . $fc['BeneficiaryId'] . ')';
                        }
                        if (!empty($fc['Phone'])) {
                            $label .= ' — ' . $fc['Phone'];
                        }
                        echo '<option value="' . $fcId . '"' . $sel . '>' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '</option>';
                    } ?>
                </select>
            </div>
            <div class="form-group col-md-2 mb-2 mb-md-0">
                <label class="form-label">File in hand from</label>
                <input type="date" name="FromDate" class="form-control" value="<?php echo htmlspecialchars($filterFromDate, ENT_QUOTES, 'UTF-8'); ?>">
            </div>
            <div class="form-group col-md-2 mb-2 mb-md-0">
                <label class="form-label">File in hand to</label>
                <input type="date" name="ToDate" class="form-control" value="<?php echo htmlspecialchars($filterToDate, ENT_QUOTES, 'UTF-8'); ?>">
            </div>
            <div class="form-group col-md-12 mb-0">
                <input type="hidden" name="name" id="filterProjectName" value="<?php echo htmlspecialchars($projectName, ENT_QUOTES, 'UTF-8'); ?>">
                <input type="hidden" name="subname" id="filterSubHeadName" value="<?php echo htmlspecialchars($subHeadName, ENT_QUOTES, 'UTF-8'); ?>">
                <button type="submit" class="btn btn-primary">Search</button>
                <a href="file-submission-reminder.php?id=<?php echo $projectId; ?>&amp;name=<?php echo urlencode($projectName); ?>&amp;subheadid=<?php echo $subHeadId; ?>&amp;subname=<?php echo urlencode($subHeadName); ?>" class="btn btn-info">Clear filters</a>
            </div>
        </form>
    </div>

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

<?php } elseif ($showSubHeads) {
    $projectTotalPending = getFileSubmissionReminderCount($projectId);
    $subHeadTiles = array();
    $sql = "SELECT * FROM tbl_project_sub_head WHERE Status=1 AND UnderBy='$projectId' ORDER BY Name";
    foreach (getList($sql) as $subHead) {
        $shId = (int) $subHead['id'];
        $subHeadTiles[] = array(
            'id' => $shId,
            'name' => (string) $subHead['Name'],
            'count' => getFileSubmissionReminderCount($projectId, $shId),
            'url' => 'file-submission-reminder.php?id=' . $projectId
                . '&name=' . urlencode($projectName)
                . '&subheadid=' . $shId
                . '&subname=' . urlencode((string) $subHead['Name']),
        );
    }
    usort($subHeadTiles, function ($a, $b) {
        return $b['count'] - $a['count'];
    });
?>
<div class="fsr-page">
    <a href="file-submission-reminder.php" class="fsr-back"><i class="feather icon-arrow-left"></i> Back to project heads</a>
    <div class="fsr-hero">
        <div class="fsr-hero-text">
            <h1><?php echo htmlspecialchars($projectName, ENT_QUOTES, 'UTF-8'); ?></h1>
            <p>Select sub project head</p>
        </div>
        <div class="fsr-hero-stat">
            <strong><?php echo (int) $projectTotalPending; ?></strong> pending
        </div>
    </div>
    <div class="fsr-panel">
        <h2 class="fsr-panel-title">Sub project heads</h2>
        <div class="row">
<?php foreach ($subHeadTiles as $tile) {
    $hasPending = $tile['count'] > 0;
    $tileClass = 'fsr-tile fsr-tile--sub' . ($hasPending ? ' fsr-tile--active' : ' fsr-tile--zero');
?>
            <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                <a href="<?php echo htmlspecialchars($tile['url'], ENT_QUOTES, 'UTF-8'); ?>" class="fsr-tile-link">
                    <div class="<?php echo $tileClass; ?>">
                        <div class="fsr-tile-icon"><i class="feather icon-folder"></i></div>
                        <p class="fsr-tile-name"><?php echo htmlspecialchars($tile['name'], ENT_QUOTES, 'UTF-8'); ?></p>
                        <div class="fsr-tile-meta">
                            <span class="fsr-tile-count"><?php echo (int) $tile['count']; ?></span>
                            <span class="fsr-tile-label">pending</span>
                        </div>
                    </div>
                </a>
            </div>
<?php } ?>
        </div>
    </div>
</div>

<?php } else {
    $totalPending = getFileSubmissionReminderCount();
    $projectTiles = array();
    $sql = "SELECT * FROM tbl_common_master WHERE Status=1 AND Roll=24 ORDER BY Name";
    foreach (getList($sql) as $project) {
        $pId = (int) $project['id'];
        $pName = (string) $project['Name'];
        $projectTiles[] = array(
            'id' => $pId,
            'name' => $pName,
            'count' => getFileSubmissionReminderCount($pId),
            'url' => 'file-submission-reminder.php?id=' . $pId . '&name=' . urlencode($pName),
        );
    }
    usort($projectTiles, function ($a, $b) {
        return $b['count'] - $a['count'];
    });
?>
<div class="fsr-page">
    <div class="fsr-hero">
        <div class="fsr-hero-text">
            <h1>File submission reminder</h1>
            <p>Select project head</p>
        </div>
        <div class="fsr-hero-stat">
            <strong><?php echo (int) $totalPending; ?></strong> total pending
        </div>
    </div>
    <div class="fsr-panel">
        <h2 class="fsr-panel-title">Project heads</h2>
        <div class="fsr-tiles-grid">
<?php foreach ($projectTiles as $tile) {
    $hasPending = $tile['count'] > 0;
    $tileClass = 'fsr-tile fsr-tile--project' . ($hasPending ? '' : ' fsr-tile--zero');
?>
            <a href="<?php echo htmlspecialchars($tile['url'], ENT_QUOTES, 'UTF-8'); ?>" class="fsr-tile-link">
                <div class="<?php echo $tileClass; ?>">
                    <div class="fsr-tile-icon"><i class="feather icon-layers"></i></div>
                    <p class="fsr-tile-name"><?php echo htmlspecialchars($tile['name'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <div class="fsr-tile-meta">
                        <span class="fsr-tile-count"><?php echo (int) $tile['count']; ?></span>
                        <span class="fsr-tile-label">pending</span>
                    </div>
                </div>
            </a>
<?php } ?>
        </div>
    </div>
</div>
<?php } ?>

</div>
<?php include_once 'footer.php'; ?>
</div>
</div>
</div>
<div class="layout-overlay layout-sidenav-toggle"></div>
</div>

<?php include_once 'footer_script.php'; ?>
<?php if ($showList) { ?>
<script>
function fsrSyncHiddenNames() {
    var $proj = $('#filterProjectId option:selected');
    var $sub = $('#filterSubHeadId option:selected');
    $('#filterProjectName').val($proj.val() ? $proj.text() : '');
    $('#filterSubHeadName').val($sub.val() ? $sub.text() : '');
}

function fsrLoadSubHeads(projectId, selectedId) {
    if (!projectId) {
        $('#filterSubHeadId').html('<option value="">Select sub head</option>');
        return;
    }
    $.post('ajax_files/ajax_dropdown.php', { action: 'getSubHead', id: projectId }, function (html) {
        $('#filterSubHeadId').html(html);
        if (selectedId) {
            $('#filterSubHeadId').val(String(selectedId));
        }
        fsrSyncHiddenNames();
    });
}

$(document).ready(function () {
    $('.select2-demo').select2({ width: '100%' });

    $('#filterProjectId').on('change', function () {
        fsrLoadSubHeads($(this).val(), '');
        fsrSyncHiddenNames();
    });

    $('#filterSubHeadId').on('change', fsrSyncHiddenNames);
    $('#fsrFilterForm').on('submit', fsrSyncHiddenNames);

    $('#example').DataTable({
        scrollX: true,
        order: [[10, 'desc']]
    });
});
</script>
<?php } ?>
</body>
</html>
