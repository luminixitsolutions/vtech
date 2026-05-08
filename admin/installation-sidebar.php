<?php 
	$user_id = $_SESSION['Admin']['id'];
	 $sql77 = "SELECT * FROM tbl_users WHERE id='$user_id'";
	$row77 = getRecord($sql77);
	$Roll = $row77['Roll'];
	$UserCat = $row77['CatId'];
	$Options = explode(',',$row77['Options']);
	$BranchId = $row77['BranchId'];
 ?>
<div class="page-loader">
    <div class="bg-primary"></div>
</div>

 <div id="layout-sidenav" class="layout-sidenav sidenav sidenav-vertical bg-white logo-dark">
     <div class="app-brand demo">
                    <span class="app-brand-logo demo">
                        <a href="dashboard.php"><img src="logo.jpg" alt="Brand Logo" class="img-fluid" style="width: 185px;"></a>
                    </span>
                   <!-- <a href="dashboard.php" class="app-brand-text demo sidenav-text font-weight-normal ml-2"><?php echo $Proj_Title; ?></a>-->
                    <a href="javascript:" class="layout-sidenav-toggle sidenav-link text-large ml-auto">
                        <i class="ion ion-md-menu align-middle"></i>
                    </a>
                </div>
                <div class="sidenav-divider mt-0"></div>
    <ul class="sidenav-inner">
        <li class="sidenav-item">
            <a href="dashboard.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>Home</div>
                
            </a>
        </li>
        <li class="sidenav-item">
            <a href="installation-project-dashboard.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>Project Dashboard</div>
                
            </a>

        </li>
        <?php 
                                $sql = "SELECT * FROM tbl_common_master WHERE Status=1 AND Roll=24";
                                $row = getList($sql);
                                foreach($row as $result){

                            ?>
        <li class="sidenav-item">
            <a href="installation-project-sub-head-dashboard.php?id=<?php echo $result['id'];?>&name=<?php echo $result['Name'];?>" class="sidenav-link sidenav-toggle">
                <i class="sidenav-icon feather icon-activity"></i>
                <div><?php echo $result['Name'];?> Project</div>
                
            </a>
            <ul class="sidenav-menu">
                <?php 
                                $sql2 = "SELECT * FROM tbl_project_sub_head WHERE UnderBy='".$result['id']."'";
                                $row2 = getList($sql2);
                                foreach($row2 as $result2){

                            ?>
                <li class="sidenav-item">
<a href="installation-project-dashboard-2.php?prjid=<?php echo $result['id'];?>&id=<?php echo $result2['id'];?>&name=<?php echo $result2['Name'];?>" class="sidenav-link">
<div> <?php echo $result2['Name'];?></div>
</a>
</li>
<?php } ?>
</ul>
        </li>
        <?php } ?>

      
        
    </ul>
</div>