<?php
$user_id = $_SESSION['Admin']['id'];
$sql77 = "SELECT * FROM tbl_users WHERE id='$user_id'";
$row77 = getRecord($sql77);
$Roll = $row77['Roll'];
$UserCat = $row77['CatId'];
$Options = explode(',', $row77['Options']);
$BranchId = $row77['BranchId'];
$ImmediateBoss = $row77['ImmediateBoss'];
$MulBranchId = $row77['MulBranchId'];
?>
<div id="layout-sidenav" class="layout-sidenav sidenav sidenav-vertical bg-white logo-dark">
    <div class="app-brand demo">
        <span class="app-brand-logo demo">
            <a href="dashboard.php"><img src="logo.jpg" alt="Brand Logo" class="img-fluid" style="width: 150px;"></a>
        </span>

        <!--<a href="dashboard.php" class="app-brand-text demo sidenav-text font-weight-normal ml-2"><?php echo $row77['Fname'] . " " . $row77['Lname']; ?></a>-->
        <a href="javascript:" class="layout-sidenav-toggle sidenav-link text-large ml-auto">
            <i class="ion ion-md-menu align-middle"></i>
        </a>
    </div>
    <div class="sidenav-divider mt-0"></div>
    <ul class="sidenav-inner">
        <li class="sidenav-item">
            <a href="dispatch-dashboard.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>Home</div>

            </a>
        </li>
        <?php 
                                    $sql = "SELECT * FROM tbl_branch WHERE id IN($MulBranchId)";
                                    $row = getList($sql);
                                    foreach($row as $result){
                                ?>
        <li class="sidenav-item">
            <a href="dispatch-dashboard2.php?storeid=<?php echo $result['id'];?>" class="sidenav-link">
            <i class="sidenav-icon feather icon-activity"></i>
                <div><?php echo $result['Name'];?></div>

            </a>
        </li>
        <?php } ?>       


        <li class="sidenav-item">
            <a href="logout.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-activity"></i>
                <div>Logout</div>

            </a>
        </li>

        <li class="sidenav-item">
            <a href="backup.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-activity"></i>
                <div>DB Backup</div>

            </a>
        </li>


    </ul>
</div>