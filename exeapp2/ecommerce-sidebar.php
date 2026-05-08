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
            <a href="ecommerce-dashboard.php" class="sidenav-link">
                <i class="sidenav-icon feather icon-home"></i>
                <div>E-Commece Dashboard</div>
                
            </a>
        </li>
        <li class="sidenav-item">
<a href="payment-method.php" class="sidenav-link">
    <i class="sidenav-icon feather icon-home"></i>
<div>Payment Method</div>
<?php if($Page=='PaymentMethod') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

<li class="sidenav-item">
<a href="cancel-reason.php" class="sidenav-link">
    <i class="sidenav-icon feather icon-home"></i>
<div>Cancel Reason</div>
<?php if($Page=='Cancel-Reason') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

<li class="sidenav-item">
<a href="coupon-code.php" class="sidenav-link">
    <i class="sidenav-icon feather icon-home"></i>
<div>Referral/Coupon/Offer Code</div>
<?php if($Page=='Coupon-Code') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

<li class="sidenav-item">
<a href="today-orders.php" class="sidenav-link">
    <i class="sidenav-icon feather icon-home"></i>
<div> Today's Orders</div>
<?php if($Page=='Today-Orders') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<li class="sidenav-item">
<a href="view-orders.php" class="sidenav-link">
    <i class="sidenav-icon feather icon-home"></i>
<div> View Orders</div>
<?php if($Page=='View-Orders') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

<li class="sidenav-item">
<a href="add-shop-product.php" class="sidenav-link">
    <i class="sidenav-icon feather icon-home"></i>
<div> Add Product</div>
<?php if($Page=='Add-Product') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
<li class="sidenav-item">
<a href="view-shop-products.php" class="sidenav-link">
    <i class="sidenav-icon feather icon-home"></i>
<div> View Products</div>
<?php if($Page=='View-Product') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

<li class="sidenav-item">
<a href="shop-category.php" class="sidenav-link">
    <i class="sidenav-icon feather icon-home"></i>
<div>Category </div> 
<?php if($Page=='Category') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
 <li class="sidenav-item">
<a href="shop-sub-category.php" class="sidenav-link">
    <i class="sidenav-icon feather icon-home"></i>
<div>Sub Category</div>
<?php if($Page=='Sub-Category') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>  
</a>
</li> 

<li class="sidenav-item">
<a href="brands.php" class="sidenav-link">
    <i class="sidenav-icon feather icon-home"></i>
<div>Brands</div>
<?php if($Page=='Brands') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

<li class="sidenav-item">
<a href="attribute-value.php" class="sidenav-link">
    <i class="sidenav-icon feather icon-home"></i>
<div>Product Attributes</div>
<?php if($Page=='Attributes') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li> 

<li class="sidenav-item">
<a href="shipping-price.php" class="sidenav-link">
    <i class="sidenav-icon feather icon-home"></i>
<div>Shipping Price</div>
<?php if($Page=='Shipping-Price') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>


<li class="sidenav-item">
<a href="home-sliders.php" class="sidenav-link">
    <i class="sidenav-icon feather icon-home"></i>
<div>Home Sliders</div>
<?php if($Page=='Home Slider') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

<li class="sidenav-item">
<a href="home-banners.php" class="sidenav-link">
    <i class="sidenav-icon feather icon-home"></i>
<div>Home Banners</div>
<?php if($Page=='Home Banner') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>

<li class="sidenav-item">
<a href="faqs.php" class="sidenav-link">
    <i class="sidenav-icon feather icon-home"></i>
<div>Faq's</div>
<?php if($Page=='Faq') {?>
<div class="pl-1 ml-auto">
<span class="badge badge-dot badge-primary"></span>
</div>
<?php } ?>
</a>
</li>
        
        
    </ul>
</div>