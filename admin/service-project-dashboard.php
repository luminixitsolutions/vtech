<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
require_once __DIR__ . '/inc-employee-project-access.php';
$MainPage = 'Service';
$Page = 'Service-Dashboard';
$user_id = $_SESSION['Admin']['id'];
$sql77 = getRecord("SELECT * FROM tbl_users WHERE id='$user_id'");
$projectId = isset($_GET['prjid']) ? (int) $_GET['prjid'] : 0;
$subHeadId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
employeeProjectAccessEnforceProject($sql77, $projectId, 'service-dashboard.php');
employeeProjectAccessEnforceSubHead($sql77, $subHeadId, 'service-dashboard.php');
$subHeadName = isset($_GET['name']) ? htmlspecialchars($_GET['name'], ENT_QUOTES, 'UTF-8') : '';
$projectName = '';
if ($projectId > 0) {
    $projectRow = getRecord("SELECT Name FROM tbl_common_master WHERE id='$projectId'");
    $projectName = !empty($projectRow['Name']) ? htmlspecialchars($projectRow['Name'], ENT_QUOTES, 'UTF-8') : '';
}
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">

<head>
    <title><?php echo $Proj_Title; ?> - Service Dashboard</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="" />
    <?php include_once 'header_script.php'; ?>
    <link rel="stylesheet" href="css/installation-project-dashboard.css">
</head>

<body>
   <div class="layout-wrapper layout-2">
        <div class="layout-inner">

            <?php include_once 'service-sidebar.php'; ?>

            <div class="layout-container">

              <?php include_once 'top_header.php'; ?>

                <div class="layout-content">
                    <div class="container-fluid flex-grow-1 container-p-y ipd-page">

                        <div class="row">
                            <div class="col-12">
                                <div class="card ipd-shell mb-4">
                                    <h5 class="card-header ipd-header"><?php echo $subHeadName; ?> — Service Dashboard</h5>
                                    <div class="card-body ipd-body">
                                        <nav class="ipd-breadcrumb" aria-label="Breadcrumb">
                                            <a href="service-dashboard.php">Service Dashboard</a>
                                            <span aria-hidden="true">/</span>
                                            <?php if ($projectName !== '') { ?>
                                            <a href="service-project-sub-head-dashboard.php?id=<?php echo $projectId; ?>&name=<?php echo urlencode($projectName); ?>"><?php echo $projectName; ?></a>
                                            <span aria-hidden="true">/</span>
                                            <?php } ?>
                                            <span><?php echo $subHeadName; ?></span>
                                        </nav>
<?php include_once 'inc-service-project-dashboard.php'; ?>
                                    </div>
                                </div>
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
