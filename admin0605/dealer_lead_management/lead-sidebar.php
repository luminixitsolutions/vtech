<?php 
$user_id = $_SESSION['Admin']['id'];
$sql77 = "SELECT * FROM tbl_users WHERE id='$user_id'";
$row77 = getRecord($sql77);
$Roll = $row77['Roll'];
$UserCat = $row77['CatId'];
$Options = explode(',',$row77['Options']);
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
            <a href="../dashboard.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>Home</div>
                
            </a>
        </li>
        <li class="sidenav-item">
            <a href="lead-management-dashboard.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-activity"></i>
                <div>Lead Dashboard</div>
                
            </a>
        </li>
        <?php if(in_array("64", $Options)) {?>
    <li class="sidenav-item">
            <a href="upload-excel.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-activity"></i>
                <div>Upload Excel</div>
               
            </a>
        </li>
        <?php } if(in_array("154", $Options)) {?>
        <li class="sidenav-item">
            <a href="add-lead.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-activity"></i>
                <div>Lead Creation</div>
                
            </a>
        </li>
        <?php } if(in_array("155", $Options)) {?>
        <li class="sidenav-item">
            <a href="view-leads.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-activity"></i>
                <div>View Leads</div>
                
            </a>
        </li>
        <?php } if(in_array("156", $Options)) {?>
        <li class="sidenav-item">
            <a href="assign-leads.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-activity"></i>
                <div>Lead Assign</div>
                
            </a>
        </li>
        <?php } if(in_array("157", $Options)) {?>
         <li class="sidenav-item">
            <a href="view-leads-calling.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-activity"></i>
                <div>To do Activity</div>
               
            </a>
        </li>

        <?php } if(in_array("158", $Options)) {?>
       
        <li class="sidenav-item">
            <a href="lead-completed-customers.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-activity"></i>
                <div>Prospects Dealers</div>
               
            </a>
        </li>

<?php } if(in_array("159", $Options)) {?>
        <li class="sidenav-item">
            <a href="opportunity-convert-to-order.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-activity"></i>
                <div>Convert to Dealer</div>
               
            </a>
        </li>

     <?php } ?>
       

    </ul>
</div>