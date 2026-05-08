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
            <a href="<?php echo $SiteUrl;?>/dashboard.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>Home</div>
                
            </a>
        </li>
        <li class="sidenav-item">
            <a href="product-managment-dashboard.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-activity"></i>
                <div>Product Dashboard</div>
                
            </a>
        </li> 
        
      <?php if(in_array("14", $Options)) {?>
        <li class="sidenav-item">
            <a href="add-product.php" class="sidenav-link">
                 <i class="sidenav-icon feather icon-activity"></i>
                <div>Add Product</div>
                
            </a>
        </li>
        <?php } ?>
        <li class="sidenav-item">
            <a href="view-products.php" class="sidenav-link">
                 <i class="sidenav-icon feather icon-activity"></i>
                <div>View Product</div>
                
            </a>
        </li>
        <?php if(in_array("17", $Options)) {?>
        <li class="sidenav-item">
            <a href="brand.php" class="sidenav-link">
                 <i class="sidenav-icon feather icon-activity"></i>
                <div>Brand</div>
                <?php if($Page=='Brand') {?>
                <div class="pl-1 ml-auto">
                    <span class="badge badge-dot badge-primary"></span>
                </div>
                <?php } ?>
            </a>
        </li> 
        <?php } if(in_array("17", $Options)) {?>
        <li class="sidenav-item">
            <a href="category.php" class="sidenav-link">
                 <i class="sidenav-icon feather icon-activity"></i>
                <div>Category</div>
                <?php if($Page=='Category') {?>
                <div class="pl-1 ml-auto">
                    <span class="badge badge-dot badge-primary"></span>
                </div>
                <?php } ?>
            </a>
        </li> 
        <?php } if(in_array("17", $Options)) {?>
        <li class="sidenav-item">
            <a href="view-qtn-calculation.php" class="sidenav-link">
                 <i class="sidenav-icon feather icon-activity"></i>
                <div>Calculation For Quotation</div>
                <?php if($Page=='Category') {?>
                <div class="pl-1 ml-auto">
                    <span class="badge badge-dot badge-primary"></span>
                </div>
                <?php } ?>
            </a>
        </li> 
        <?php } if(in_array("17", $Options)) {?>
    <!-- <li class="sidenav-item">
            <a href="product-specification.php" class="sidenav-link">
                 <i class="sidenav-icon feather icon-activity"></i>
                <div>BOS Product Specification</div>
                <?php if($Page=='Product-Specification') {?>
                <div class="pl-1 ml-auto">
                    <span class="badge badge-dot badge-primary"></span>
                </div>
                <?php } ?>
            </a>
        </li> -->
<?php } if(in_array("78", $Options)) {?>
    <!-- <li class="sidenav-item">
            <a href="structure-product-specification.php" class="sidenav-link">
                 <i class="sidenav-icon feather icon-activity"></i>
                <div>Structure Product Specification</div>
                <?php if($Page=='Structure-Product-Specification') {?>
                <div class="pl-1 ml-auto">
                    <span class="badge badge-dot badge-primary"></span>
                </div>
                <?php } ?>
            </a>
        </li> -->
<?php } ?>
        
        
    </ul>
</div>