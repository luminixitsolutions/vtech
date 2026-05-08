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
</head>

<body>
    <style type="text/css">
    .mr_5 {
        margin-right: 3rem !important;
    }
    </style>
   <div class="layout-wrapper layout-2">
        <div class="layout-inner">

            <?php include_once 'installation-sidebar.php'; ?>


            <div class="layout-container">

              <?php include_once 'top_header.php'; ?>


                <div class="layout-content">
                    <div class="container-fluid flex-grow-1 container-p-y">
                        <div class="text-muted small mt-0 mb-4 d-block breadcrumb">
                        </div>

                        <div class="row">

                            <div class="col-xl-12 col-md-12">
                                <div class="card ui-task mb-4">
                                    <h5 class="card-header" style="text-align:center;"><?php echo strtoupper($_GET['name']);?> PROJECT SUB HEAD</h5>
                                    <div class="card-body">
                                      

                            <div class="row">
                            <?php 
                                $sql = "SELECT * FROM tbl_rooftop_project_sub_head WHERE UnderBy='".$_GET['id']."'";
                                $row = getList($sql);
                                foreach($row as $result){

                            ?>
                          <div class="col-sm-6 col-xl-2">
                                 <a href="installation-project-dashboard-2.php?prjid=<?php echo $_GET['id'];?>&id=<?php echo $result['id'];?>&name=<?php echo $result['Name'];?>">
                               <div class="card mb-4 bg-pattern-3-dark">
                                    <div class="card-body" style="padding-top: 15px; padding-bottom: 15px; padding-right: 5px; padding-left: 10px;">
                                        <div class="d-flex align-items-center">
                                          
                                            <div class="ml-3">
                                                <h6 class="mb-0" style="color: black;"><?php echo $result['Name'];?></h6>
                                        <div class="text-large"><?php  
                                                            $sql4 = "SELECT * FROM tbl_users WHERE ProjectSubHeadId='".$result['id']."' AND ProjectType=2";
                                                            echo $rncnt4 = getRow($sql4);

                                                        ?></div>
                                        
                                     </div>
                                        </div>
                                     </div>
                                </div></a>
                            </div>
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