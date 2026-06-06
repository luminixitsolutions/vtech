<?php
session_start();
require_once 'config.php';
require_once 'auth.php';
require_once 'inc-mobile-mgmt.php';

$searched = isset($_POST['Search']) && $_POST['Search'] === 'Search';
$selectedProjectId = isset($_POST['ProjectId']) ? (int) $_POST['ProjectId'] : 0;
$selectedSubHeadIds = array();
if (isset($_POST['SubHeadIds']) && is_array($_POST['SubHeadIds'])) {
    $selectedSubHeadIds = array_values(array_unique(array_filter(array_map('intval', $_POST['SubHeadIds']), function ($id) {
        return $id > 0;
    })));
}

$projects = mobileMgmtGetProjects();
$subHeadsByProject = mobileMgmtGetSubHeadsByProjectMap($projects);

$filters = array();
$reportError = '';
$summary = null;
$rows = array();

if ($searched) {
    if ($selectedProjectId < 1) {
        $reportError = 'Please select a project.';
    } elseif (empty($selectedSubHeadIds)) {
        $reportError = 'Please select at least one sub head.';
    } else {
        $filters['project_id'] = $selectedProjectId;
        $filters['sub_head_ids'] = $selectedSubHeadIds;
        set_time_limit(120);
        $summary = mobileMgmtGetDeliveryChallanSummary($filters);
        $rows = $summary['rows'];
    }
}
?>
<!doctype html>
<html lang="en" class="h-100">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $Proj_Title; ?> | Delivery Challan</title>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<link href="css/mobile-mgmt.css" rel="stylesheet">
</head>
<body class="body-scroll menu-overlay mob-mgmt-page">

<?php include_once 'sidebar.php'; ?>

<main class="main has-footer">
<?php include_once 'top_header.php'; ?>

<div class="mob-mgmt-topbar">
    <a href="stock-management.php"><span class="material-icons">arrow_back</span></a>
    <h1>Delivery Challan</h1>
    <?php if ($summary !== null) { ?>
    <span class="badge bg-primary"><?php echo count($rows); ?></span>
    <?php } ?>
</div>

<div class="mob-mgmt-list-wrap" style="padding-top: 8px;">
    <div class="mob-mgmt-card mob-mgmt-filter-card">
        <form method="post" action="" id="dcFilterForm">
            <div class="mb-3">
                <label class="form-label small fw-semibold mb-1">Project <span class="text-danger">*</span></label>
                <select class="form-control form-control-sm" name="ProjectId" id="ProjectId" required>
                    <option value="" disabled <?php echo $selectedProjectId < 1 ? 'selected' : ''; ?>>Select Project</option>
                    <?php foreach ($projects as $project) { ?>
                    <option value="<?php echo (int) $project['id']; ?>" <?php echo ($selectedProjectId === (int) $project['id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($project['Name']); ?>
                    </option>
                    <?php } ?>
                </select>
            </div>

            <div class="mb-3 mob-mgmt-subhead-section" id="SubHeadSection" style="<?php echo $selectedProjectId > 0 ? '' : 'display:none;'; ?>">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <label class="form-label small fw-semibold mb-0">Sub Head <span class="text-danger">*</span></label>
                    <div class="mob-mgmt-subhead-actions">
                        <button type="button" class="btn btn-link btn-sm p-0 me-2" id="SubHeadSelectAll">All</button>
                        <button type="button" class="btn btn-link btn-sm p-0" id="SubHeadClearAll">Clear</button>
                    </div>
                </div>
                <div class="mob-mgmt-checkbox-list" id="SubHeadCheckboxes"></div>
                <div class="form-text" id="SubHeadHint">Select one or more sub heads, then tap Search.</div>
            </div>

            <input type="hidden" name="Search" value="Search">
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-grow-1">Search</button>
                <?php if ($searched) { ?>
                <a href="mobile-delivery-challan-list.php" class="btn btn-outline-secondary btn-sm">Clear</a>
                <?php } ?>
            </div>
        </form>
    </div>

    <?php if ($reportError !== '') { ?>
    <div class="mob-mgmt-empty"><?php echo htmlspecialchars($reportError); ?></div>
    <?php } elseif ($summary !== null) { ?>
    <div class="mob-mgmt-section-title">Delivery Challan Summary</div>
    <div class="mob-mgmt-table-wrap mob-mgmt-table-scroll">
        <table class="table table-striped table-bordered mob-mgmt-table mob-mgmt-table-challan mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Project</th>
                    <th>Sub Head</th>
                    <th>Total Delivery Challan</th>
                    <th>Total Material Dispatch</th>
                    <th>Balance Challan</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $i = 1;
                foreach ($rows as $row) {
                    ?>
                <tr>
                    <td><?php echo $i++; ?></td>
                    <td><?php echo htmlspecialchars((string) ($row['project_name'] ?? '')); ?></td>
                    <td><?php echo htmlspecialchars((string) ($row['sub_head_name'] ?? '')); ?></td>
                    <td><?php echo (int) $row['total_delivery_challan']; ?></td>
                    <td><?php echo (int) $row['total_material_dispatch']; ?></td>
                    <td><?php echo (int) $row['balance_challan']; ?></td>
                </tr>
                    <?php
                }
                if (empty($rows)) {
                    ?>
                <tr>
                    <td colspan="6" class="text-center text-muted">No delivery challan records found for selected sub heads.</td>
                </tr>
                    <?php
                }
                ?>
                <tr class="mob-mgmt-table-total">
                    <td><?php echo $i; ?></td>
                    <th colspan="2">Total</th>
                    <th><?php echo (int) $summary['tot_delivery_challan']; ?></th>
                    <th><?php echo (int) $summary['tot_material_dispatch']; ?></th>
                    <th><?php echo (int) $summary['tot_balance_challan']; ?></th>
                </tr>
            </tbody>
        </table>
    </div>
    <?php } ?>
</div>

</main>

<?php include_once 'footer.php'; ?>
<script>
(function () {
    var subHeadsByProject = <?php echo json_encode($subHeadsByProject, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE); ?>;
    var selectedSubHeadIds = <?php echo json_encode($selectedSubHeadIds, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP); ?>;

    function renderSubHeads(projectId) {
        var $section = $('#SubHeadSection');
        var $box = $('#SubHeadCheckboxes');
        var $hint = $('#SubHeadHint');
        projectId = parseInt(projectId, 10) || 0;
        $box.empty();

        if (projectId < 1) {
            $section.hide();
            return;
        }

        var list = subHeadsByProject[projectId] || [];
        $section.show();

        if (!list.length) {
            $hint.text('No sub heads found for this project.');
            return;
        }

        $hint.text('Select one or more sub heads, then tap Search.');
        list.forEach(function (item) {
            var checked = selectedSubHeadIds.indexOf(item.id) !== -1 ? ' checked' : '';
            var id = 'subhead_' + item.id;
            $box.append(
                '<label class="mob-mgmt-checkbox-item" for="' + id + '">' +
                '<input type="checkbox" name="SubHeadIds[]" id="' + id + '" value="' + item.id + '"' + checked + '>' +
                '<span>' + $('<div>').text(item.name).html() + '</span>' +
                '</label>'
            );
        });
    }

    $('#ProjectId').on('change', function () {
        selectedSubHeadIds = [];
        renderSubHeads(this.value);
    });

    $('#SubHeadSelectAll').on('click', function () {
        $('#SubHeadCheckboxes input[type="checkbox"]').prop('checked', true);
    });

    $('#SubHeadClearAll').on('click', function () {
        $('#SubHeadCheckboxes input[type="checkbox"]').prop('checked', false);
    });

    renderSubHeads($('#ProjectId').val());
})();
</script>
</body>
</html>
