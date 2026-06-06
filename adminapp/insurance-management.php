<?php
session_start();
require_once 'config.php';
require_once 'auth.php';
require_once 'inc-mobile-mgmt.php';

$PageName = 'Insurance Management';

$selectedProjectId = isset($_GET['ProjectId']) ? (int) $_GET['ProjectId'] : 0;
$selectedSubHeadId = isset($_GET['ProjectSubHeadId']) ? (int) $_GET['ProjectSubHeadId'] : 0;

$projects = mobileMgmtGetProjects();
$subHeads = $selectedProjectId > 0 ? mobileMgmtGetSubHeadsForProject($selectedProjectId) : array();
$subHeadsByProject = mobileMgmtGetSubHeadsByProjectMap($projects);

$filters = array();
if ($selectedProjectId > 0) {
    $filters['project_id'] = $selectedProjectId;
}
if ($selectedSubHeadId > 0) {
    $filters['sub_head_id'] = $selectedSubHeadId;
}

$insuranceSummary = getInsuranceMgmtProjectSubHeadSummary($filters);
$insuranceRows = $insuranceSummary['rows'];
$insuranceTotals = $insuranceSummary['totals'];
?>
<!doctype html>
<html lang="en" class="h-100">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $Proj_Title; ?> | Insurance Management</title>
<link rel="manifest" href="manifest.json">
<link rel="apple-touch-icon" href="img/favicon180.png">
<link rel="icon" href="img/favicon32.png">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
<link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<link href="css/mobile-mgmt.css" rel="stylesheet">
</head>
<body class="body-scroll menu-overlay mob-mgmt-page">

<?php include_once 'sidebar.php'; ?>

<main class="main has-footer">
<?php include_once 'top_header.php'; ?>

<div class="mob-mgmt-topbar">
    <a href="home.php"><span class="material-icons">arrow_back</span></a>
    <h1>Insurance Management</h1>
</div>

<div class="mob-mgmt-heading mob-mgmt-heading-insurance">Insurance Site Abstract</div>

<div class="mob-mgmt-list-wrap" style="padding-top: 8px;">
    <div class="mob-mgmt-card mob-mgmt-filter-card">
        <form method="get" id="insuranceFilterForm">
            <div class="mb-3">
                <label class="form-label small fw-semibold mb-1">Project Head</label>
                <select name="ProjectId" id="ProjectId" class="form-control form-control-sm">
                    <option value="">All Project Head</option>
                    <?php foreach ($projects as $project) { ?>
                    <option value="<?php echo (int) $project['id']; ?>" <?php echo ($selectedProjectId === (int) $project['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($project['Name']); ?>
                    </option>
                    <?php } ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label small fw-semibold mb-1">Sub Head</label>
                <select name="ProjectSubHeadId" id="ProjectSubHeadId" class="form-control form-control-sm">
                    <option value="">All Sub Head</option>
                    <?php foreach ($subHeads as $subHead) { ?>
                    <option value="<?php echo (int) $subHead['id']; ?>" <?php echo ($selectedSubHeadId === (int) $subHead['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($subHead['Name']); ?>
                    </option>
                    <?php } ?>
                </select>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-grow-1">Search</button>
                <a href="insurance-management.php" class="btn btn-outline-secondary btn-sm">Clear</a>
            </div>
        </form>
    </div>

    <div class="mob-mgmt-abstract-meta text-center mb-3">
        <div class="fw-bold">VTECH SUNSYSTEMS PVT LTD</div>
        <div class="fw-semibold small mt-1">Insurance Site Abstract</div>
        <div class="text-muted small">Update as on <?php echo date('d.m.Y'); ?></div>
    </div>

    <div class="mob-mgmt-table-wrap mob-mgmt-table-scroll">
        <table class="table table-striped table-bordered mob-mgmt-table mob-mgmt-table-insurance mb-0">
            <thead>
                <tr>
                    <th>Project Head</th>
                    <th>Sub Head</th>
                    <th>Total Insurance</th>
                    <th>Pending Insurance</th>
                    <th>Completed</th>
                    <th>Upcoming Renewal</th>
                    <th>Expired</th>
                    <th>Renewed Insurance</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($insuranceRows)) { ?>
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">No records found.</td>
                </tr>
                <?php } else {
                    foreach ($insuranceRows as $row) {
                        $projectId = (int) ($row['project_id'] ?? 0);
                        $subHeadId = (int) ($row['sub_head_id'] ?? 0);
                        ?>
                <tr>
                    <td class="mob-mgmt-table-label"><?php echo htmlspecialchars((string) ($row['project_name'] ?? '')); ?></td>
                    <td><?php echo htmlspecialchars((string) ($row['sub_head_name'] ?? '')); ?></td>
                    <td><a href="<?php echo htmlspecialchars(mobileMgmtInsuranceListUrl('dispatched', $projectId, $subHeadId), ENT_QUOTES, 'UTF-8'); ?>"><?php echo number_format((int) $row['total_insurance']); ?></a></td>
                    <td><a href="<?php echo htmlspecialchars(mobileMgmtInsuranceListUrl('pending', $projectId, $subHeadId), ENT_QUOTES, 'UTF-8'); ?>"><?php echo number_format((int) $row['pending']); ?></a></td>
                    <td><a href="<?php echo htmlspecialchars(mobileMgmtInsuranceListUrl('active', $projectId, $subHeadId), ENT_QUOTES, 'UTF-8'); ?>"><?php echo number_format((int) $row['completed']); ?></a></td>
                    <td><a href="<?php echo htmlspecialchars(mobileMgmtInsuranceListUrl('renewal', $projectId, $subHeadId), ENT_QUOTES, 'UTF-8'); ?>"><?php echo number_format((int) $row['renewal']); ?></a></td>
                    <td><a href="<?php echo htmlspecialchars(mobileMgmtInsuranceListUrl('expired', $projectId, $subHeadId), ENT_QUOTES, 'UTF-8'); ?>"><?php echo number_format((int) $row['expired']); ?></a></td>
                    <td><a href="<?php echo htmlspecialchars(mobileMgmtInsuranceListUrl('renewed', $projectId, $subHeadId), ENT_QUOTES, 'UTF-8'); ?>"><?php echo number_format((int) $row['renewed']); ?></a></td>
                </tr>
                    <?php }
                    if (count($insuranceRows) > 1) { ?>
                <tr class="mob-mgmt-table-total">
                    <td class="mob-mgmt-table-label"><?php echo htmlspecialchars($insuranceTotals['project_name']); ?></td>
                    <td><?php echo htmlspecialchars($insuranceTotals['sub_head_name']); ?></td>
                    <td><a href="<?php echo htmlspecialchars(mobileMgmtInsuranceListUrl('dispatched', $selectedProjectId, $selectedSubHeadId), ENT_QUOTES, 'UTF-8'); ?>"><?php echo number_format((int) $insuranceTotals['total_insurance']); ?></a></td>
                    <td><a href="<?php echo htmlspecialchars(mobileMgmtInsuranceListUrl('pending', $selectedProjectId, $selectedSubHeadId), ENT_QUOTES, 'UTF-8'); ?>"><?php echo number_format((int) $insuranceTotals['pending']); ?></a></td>
                    <td><a href="<?php echo htmlspecialchars(mobileMgmtInsuranceListUrl('active', $selectedProjectId, $selectedSubHeadId), ENT_QUOTES, 'UTF-8'); ?>"><?php echo number_format((int) $insuranceTotals['completed']); ?></a></td>
                    <td><a href="<?php echo htmlspecialchars(mobileMgmtInsuranceListUrl('renewal', $selectedProjectId, $selectedSubHeadId), ENT_QUOTES, 'UTF-8'); ?>"><?php echo number_format((int) $insuranceTotals['renewal']); ?></a></td>
                    <td><a href="<?php echo htmlspecialchars(mobileMgmtInsuranceListUrl('expired', $selectedProjectId, $selectedSubHeadId), ENT_QUOTES, 'UTF-8'); ?>"><?php echo number_format((int) $insuranceTotals['expired']); ?></a></td>
                    <td><a href="<?php echo htmlspecialchars(mobileMgmtInsuranceListUrl('renewed', $selectedProjectId, $selectedSubHeadId), ENT_QUOTES, 'UTF-8'); ?>"><?php echo number_format((int) $insuranceTotals['renewed']); ?></a></td>
                </tr>
                    <?php }
                } ?>
            </tbody>
        </table>
    </div>

    <p class="text-muted small px-1 mb-0">Tap a count to view insurance records for that project and status.</p>
</div>

</main>

<?php include_once 'footer.php'; ?>
<script>
(function () {
    var subHeadsByProject = <?php echo json_encode($subHeadsByProject, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE); ?>;
    var selectedSubHeadId = <?php echo (int) $selectedSubHeadId; ?>;

    function renderSubHeads(projectId) {
        var $sub = $('#ProjectSubHeadId');
        projectId = parseInt(projectId, 10) || 0;
        $sub.html('<option value="">All Sub Head</option>');

        if (projectId < 1) {
            return;
        }

        var list = subHeadsByProject[projectId] || [];
        list.forEach(function (item) {
            var selected = selectedSubHeadId === item.id ? ' selected' : '';
            $sub.append(
                '<option value="' + item.id + '"' + selected + '>' +
                $('<div>').text(item.name).html() +
                '</option>'
            );
        });
    }

    $('#ProjectId').on('change', function () {
        selectedSubHeadId = 0;
        renderSubHeads(this.value);
    });

    renderSubHeads($('#ProjectId').val());
})();
</script>
</body>
</html>
