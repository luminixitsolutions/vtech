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

$sql = "SELECT * FROM tbl_installations WHERE CustName=''";
$row = getList($sql);
foreach($row as $result){
    $sql1 = "SELECT Fname,Phone,Address FROM tbl_users WHERE id='".$result['CustId']."'";
    $row1 = getRecord($sql1);
    $sql2 = "UPDATE tbl_installations SET CustName='".$row1['Fname']."',CellNo='".$row1['Phone']."',Address='".$row1['Address']."' WHERE CustId='".$result['CustId']."'";
    $conn->query($sql2);
}

$sql = "SELECT CustId FROM tbl_installations WHERE Type=0";
$row7 = getList($sql); 
foreach($row7 as $result){
    $id = $result['CustId'];
    $sql78 = "SELECT ProjectType FROM tbl_users WHERE id='$id'";
    $row78 = getRecord($sql78); 
    $ProjectType = $row78['ProjectType'];
    if($ProjectType == 1){
        $Type=2;
    }
    else{
       $Type=1; 
    }
    
    $sql = "UPDATE tbl_installations SET Type='$Type' WHERE CustId='$id'";
    $conn->query($sql);
    
}

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
                                    <h5 class="card-header ipd-header">Project Dashboard</h5>
                                    <div class="card-body ipd-body">
                                        <div class="ipd-project-grid">
                            <?php 
                                $sql = "SELECT * FROM tbl_common_master WHERE Status=1 AND Roll=24";
                                $row = getList($sql);
                                $tone = 0;
                                foreach($row as $result){
                                    $projId = (int)$result['id'];
                                    $projName = htmlspecialchars($result['Name'], ENT_QUOTES, 'UTF-8');
                                    $toneClass = 'ipd-tone-' . ($tone % 7);
                                    $tone++;
                            ?>
                                            <a href="installation-project-sub-head-dashboard.php?id=<?php echo $projId; ?>&name=<?php echo urlencode($result['Name']); ?>" class="ipd-project-link">
                                                <div class="ipd-project-card <?php echo $toneClass; ?>">
                                                    <i class="feather icon-folder ipd-project-icon" aria-hidden="true"></i>
                                                    <h6 class="ipd-project-name"><?php echo $projName; ?></h6>
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