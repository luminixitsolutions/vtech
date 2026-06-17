<?php 
$user_id = $_SESSION['Admin']['id'];
$sql77 = "SELECT * FROM tbl_users WHERE id='$user_id'";
$row77 = getRecord($sql77);
$Roll = $row77['Roll'];
$UserCat = $row77['CatId'];
require_once __DIR__ . '/../inc-menu-option-groups.php';
$Options = adminResolveMenuOptionsFromUserRow($row77);
require_once __DIR__ . '/../inc-menu-access-granular-options.php';
$MenuOptions = adminUserRawMenuOptions($row77);
?>

            <!-- [ Layout navbar ( Header ) ] End -->
            <!-- [ Layout sidenav ] Start -->
            <div id="layout-sidenav" class="layout-sidenav sidenav sidenav-vertical bg-white logo-dark">
     <div class="app-brand demo">
                    <span class="app-brand-logo demo">
                        <a href="../dashboard.php"><img src="../logo.jpg" alt="Brand Logo" class="img-fluid" style="width: 185px;"></a>
                    </span>
                   <!-- <a href="dashboard.php" class="app-brand-text demo sidenav-text font-weight-normal ml-2"><?php echo $Proj_Title; ?></a>-->
                    <a href="javascript:" class="layout-sidenav-toggle sidenav-link text-large ml-auto">
                        <i class="ion ion-md-menu align-middle"></i>
                    </a>
                </div>
                <div class="sidenav-divider mt-0"></div>
    <ul class="sidenav-inner">
        <li class="sidenav-item">
            <a href="../dashboard.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-user"></i>
                <div><?php echo $row77['Fname']." ".$row77['Lname']; ?></div>
                
            </a>
        </li> 
        <li class="sidenav-item">
            <a href="<?php echo $SiteUrl;?>/dashboard.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>Home</div>
                
            </a>
        </li>
        <?php if (userHasAnyMenuOption($Options, menuAccessTaskOptionIds())) { ?>
        <li class="sidenav-item">
            <a href="task-dashboard.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-activity"></i>
                <div>Task Dashboard</div>
                
            </a>
        </li> 
         <?php if(menuAccessShowGranularLink($MenuOptions, 252)) { ?>
         <li class="sidenav-item">
            <a href="department.php" class="sidenav-link">
                 <i class="sidenav-icon feather icon-activity"></i>
                <div>Project Head / Department</div>
                
            </a>
        </li>
      <?php if(menuAccessShowGranularLink($MenuOptions, 249)) {?>
        <li class="sidenav-item">
            <a href="create-task.php" class="sidenav-link">
                 <i class="sidenav-icon feather icon-activity"></i>
                <div>Create Task</div>
                
            </a>
        </li>
        <?php } ?>
       
        <?php if(menuAccessShowGranularLink($MenuOptions, 250)) { ?>
        <li class="sidenav-item">
            <a href="view-tasks.php" class="sidenav-link">
                 <i class="sidenav-icon feather icon-activity"></i>
                <div>View Tasks</div>
                
            </a>
        </li>
        <?php } ?>
         <?php if(menuAccessShowGranularLink($MenuOptions, 251)) { ?>
         <li class="sidenav-item">
            <a href="to-do-tasks.php" class="sidenav-link">
                 <i class="sidenav-icon feather icon-activity"></i>
                <div>To Do Tasks</div>
                
            </a>
        </li>
        <?php } ?>
        <?php } ?>

        
    </ul>
</div>
