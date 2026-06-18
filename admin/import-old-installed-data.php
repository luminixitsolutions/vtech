<?php
session_start();
include_once 'config.php';
include_once 'auth.php';
require_once __DIR__ . '/inc-employee-project-access.php';
require_once __DIR__ . '/inc-pump-installation-import.php';

$MainPage = 'Installation';
$Page = 'Installation';
$user_id = (int) $_SESSION['Admin']['id'];
$sql77 = getRecord("SELECT * FROM tbl_users WHERE id='$user_id'");
$projectId = isset($_GET['prjid']) ? (int) $_GET['prjid'] : 0;
$subHeadId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$projectName = isset($_GET['name']) ? (string) $_GET['name'] : '';

employeeProjectAccessEnforceProject($sql77, $projectId, 'installation-project-dashboard.php');
employeeProjectAccessEnforceSubHead($sql77, $subHeadId, 'installation-project-dashboard.php');

$importSummary = null;
$importError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['import_submit'])) {
    if (empty($_FILES['import_file']['tmp_name']) || !is_uploaded_file($_FILES['import_file']['tmp_name'])) {
        $importError = 'Please select an Excel or CSV file to import.';
    } elseif ((int) ($_FILES['import_file']['size'] ?? 0) > pumpInstallImportMaxFileBytes()) {
        $importError = 'File is too large. Maximum allowed size is 5 MB.';
    } else {
        $originalName = isset($_FILES['import_file']['name']) ? (string) $_FILES['import_file']['name'] : 'import.xlsx';
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        if (!in_array($ext, array('xls', 'xlsx', 'csv'), true)) {
            $importError = 'Invalid file type. Upload .xlsx, .xls, or .csv only.';
        } else {
            $fileType = isset($_FILES['import_file']['type']) ? (string) $_FILES['import_file']['type'] : '';
            if ($fileType === '' || $fileType === 'application/octet-stream') {
                if ($ext === 'xlsx') {
                    $fileType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
                } elseif ($ext === 'xls') {
                    $fileType = 'application/vnd.ms-excel';
                } else {
                    $fileType = 'text/csv';
                }
            }

            $uploadDir = __DIR__ . '/uploads';
            if (!is_dir($uploadDir)) {
                @mkdir($uploadDir, 0755, true);
            }

            $safeName = 'old_installed_import_' . time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalName);
            $targetPath = $uploadDir . '/' . $safeName;

            if (!move_uploaded_file($_FILES['import_file']['tmp_name'], $targetPath)) {
                $importError = 'Could not save uploaded file.';
            } else {
                $importSummary = pumpInstallImportProcessSpreadsheet(
                    $targetPath,
                    $originalName,
                    $fileType,
                    $projectId,
                    $subHeadId,
                    $user_id
                );
                if (empty($importSummary['success'])) {
                    $importError = (string) ($importSummary['message'] ?? 'Import failed.');
                }
            }
        }
    }
}

$backUrl = 'installation-project-dashboard-2.php?prjid=' . $projectId . '&id=' . $subHeadId . '&name=' . rawurlencode($projectName);
$sampleUrl = 'download-pump-installation-import-sample.php?prjid=' . $projectId . '&id=' . $subHeadId;
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
    <title><?php echo $Proj_Title; ?> - Import Old Installed Data</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <?php include_once 'header_script.php'; ?>
</head>
<body>
<div class="layout-wrapper layout-2">
    <div class="layout-inner">
        <?php include_once 'installation-sidebar.php'; ?>
        <div class="layout-container">
            <?php include_once 'top_header.php'; ?>
            <div class="layout-content">
                <div class="container-fluid flex-grow-1 container-p-y">
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                        <h4 class="font-weight-bold mb-2">Import Old Installed Data</h4>
                        <a href="<?php echo htmlspecialchars($backUrl, ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-outline-secondary btn-sm">Back to Dashboard</a>
                    </div>

                    <div class="card mb-4">
                        <div class="card-body">
                            <p class="text-muted mb-3">
                                Project: <strong><?php echo htmlspecialchars($projectName, ENT_QUOTES, 'UTF-8'); ?></strong>
                                &nbsp;|&nbsp; Project ID: <strong><?php echo (int) $projectId; ?></strong>
                                &nbsp;|&nbsp; Sub Head ID: <strong><?php echo (int) $subHeadId; ?></strong>
                            </p>

                            <?php if ($importError !== '') { ?>
                                <div class="alert alert-danger"><?php echo htmlspecialchars($importError, ENT_QUOTES, 'UTF-8'); ?></div>
                            <?php } ?>

                            <?php if (is_array($importSummary) && !empty($importSummary['success'])) { ?>
                                <div class="alert alert-success"><?php echo htmlspecialchars($importSummary['message'], ENT_QUOTES, 'UTF-8'); ?></div>
                                <div class="table-responsive mb-4">
                                    <table class="table table-bordered table-sm">
                                        <tbody>
                                            <tr><th style="width:260px;">Total rows processed</th><td><?php echo (int) $importSummary['total_rows']; ?></td></tr>
                                            <tr><th>Customers created</th><td><?php echo (int) $importSummary['customers_created']; ?></td></tr>
                                            <tr><th>Existing customers matched</th><td><?php echo (int) $importSummary['customers_matched']; ?></td></tr>
                                            <tr><th>Installations inserted</th><td><?php echo (int) $importSummary['installations_inserted']; ?></td></tr>
                                            <tr><th>Skipped rows</th><td><?php echo (int) $importSummary['skipped']; ?></td></tr>
                                        </tbody>
                                    </table>
                                </div>

                                <?php if (!empty($importSummary['errors'])) { ?>
                                    <h6 class="font-weight-bold">Row-wise errors</h6>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Row #</th>
                                                    <th>Reason</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($importSummary['errors'] as $err) { ?>
                                                    <tr>
                                                        <td><?php echo (int) ($err['row'] ?? 0); ?></td>
                                                        <td><?php echo htmlspecialchars((string) ($err['reason'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php } ?>
                            <?php } ?>

                            <form method="post" enctype="multipart/form-data" autocomplete="off">
                                <input type="hidden" name="import_submit" value="1">
                                <div class="form-group">
                                    <label class="form-label">Excel / CSV file <span class="text-danger">*</span></label>
                                    <input type="file" name="import_file" class="form-control" accept=".xlsx,.xls,.csv" required>
                                    <small class="text-muted">Allowed: .xlsx, .xls, .csv (max 5 MB)</small>
                                </div>
                                <button type="submit" class="btn btn-primary">Import Data</button>
                                <a href="<?php echo htmlspecialchars($sampleUrl, ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-outline-success ml-2">Download Sample Excel</a>
                            </form>
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
</body>
</html>
