<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$MainPage="Installation";
$Page = "Installation";
$user_id = $_SESSION['Admin']['id'];
$sql77 = "SELECT * FROM tbl_users WHERE id='$user_id'";
$row77 = getRecord($sql77);
$Roll = $row77['Roll'];
$UserCat = $row77['CatId'];
$Options = explode(',',$row77['Options']);
$projectId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$projectName = isset($_GET['name']) ? htmlspecialchars($_GET['name'], ENT_QUOTES, 'UTF-8') : '';
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">

<head>
    <title><?php echo $Proj_Title; ?> - Dashboard</title>
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

            <?php include_once 'installation-sidebar.php'; ?>


            <div class="layout-container">

              <?php include_once 'top_header.php'; ?>


                <div class="layout-content">
                    <div class="container-fluid flex-grow-1 container-p-y ipd-page">

                        <div class="row">
                            <div class="col-12">
                                <div class="card ipd-shell mb-4">
                                    <h5 class="card-header ipd-header"><?php echo $projectName; ?> — Project Sub Head</h5>
                                    <div class="card-body ipd-body">
                                        <nav class="ipd-breadcrumb" aria-label="Breadcrumb">
                                            <a href="installation-project-dashboard.php">Project Dashboard</a>
                                            <span aria-hidden="true">/</span>
                                            <span><?php echo $projectName; ?></span>
                                        </nav>
                                        <div class="ipd-stat-grid">
                            <?php 
                                $sql = "SELECT * FROM tbl_project_sub_head WHERE UnderBy='".$projectId."'";
                                $row = getList($sql);
                                if (empty($row)) {
                            ?>
                                            <p class="ipd-empty">No sub heads found for this project.</p>
                            <?php
                                }
                                foreach($row as $result){
                                    $subHeadId = (int)$result['id'];
                                    $subHeadName = htmlspecialchars($result['Name'], ENT_QUOTES, 'UTF-8');
                                    $sql4 = "SELECT * FROM tbl_users WHERE ProjectId='".$projectId."' AND ProjectSubHeadId='".$subHeadId."' AND ProjectType=1";
                                    $rncnt4 = getRow($sql4);
                            ?>
                                            <a href="installation-project-dashboard-2.php?prjid=<?php echo $projectId; ?>&id=<?php echo $subHeadId; ?>&name=<?php echo urlencode($result['Name']); ?>" class="ipd-stat-link">
                                                <div class="ipd-stat-card">
                                                    <h6 class="ipd-stat-label"><?php echo $subHeadName; ?></h6>
                                                    <div class="ipd-stat-meta">
                                                        <span class="ipd-stat-count"><?php echo (int)$rncnt4; ?></span>
                                                        <span class="ipd-stat-badge">
                                                            <i class="feather icon-users" aria-hidden="true"></i>
                                                            Records
                                                        </span>
                                                    </div>
                                                </div>
                                            </a>
                            <?php } ?>
                                        </div>
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