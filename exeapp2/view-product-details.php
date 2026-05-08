<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Products";
$Page = "View-Product";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> |  Product Details</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="Empire is one of the unique admin template built on top of Bootstrap 4 framework. It is easy to customize, flexible code styles, well tested, modern & responsive are the topmost key factors of Empire Dashboard Template" />
<meta name="keywords" content="bootstrap admin template, dashboard template, backend panel, bootstrap 4, backend template, dashboard template, saas admin, CRM dashboard, eCommerce dashboard">
<meta name="author" content="" />
<?php include_once 'header_script.php'; ?>
</head>
<body>

<body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <!-- Fixed navbar -->
        <?php include_once 'back-header.php'; ?> 
        

        <div class="main-container">








<?php 
$id = $_GET['id'];
$sql7 = "SELECT p.*,c.Name As Category,sb.Name As SubCategory,b.Name As Brand 
                    FROM products p 
                    LEFT JOIN category c ON c.id=p.CatId
                    LEFT JOIN sub_category sb ON sb.id=p.SubCatId
                    LEFT JOIN brands b ON b.id=p.BrandId WHERE p.id='$id'";
$res7 = $conn->query($sql7);
$row7 = $res7->fetch_assoc();
$SubCatId = $row7['SubCatId'];

function getPrdCnt($table,$val,$pid){
  global $conn;
    $sql = "SELECT count(od.id) as count FROM $table od 
              LEFT JOIN orders o ON o.id=od.OrderId WHERE o.OrderProcess='1' AND od.ProductId='$pid'";
    $res = $conn->query($sql);
    $row = $res->fetch_assoc();
    $count = $row['count'];
    return $count;
}
?>
<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Product Details</h4>
<div class="text-muted small mt-0 mb-4 d-block breadcrumb">
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="#"><i class="feather icon-home"></i></a></li>
<li class="breadcrumb-item">E-commerce</li>
<li class="breadcrumb-item active">Product Details</li>
</ol>
</div>
<div class="media align-items-center py-3 mb-4">
<?php if($row7["Photo"] == '') {?>
                  <img src="../no_image.jpg" class="d-block ui-w-80 ui-bordered"  style="width: 80px;height: 80px;"> 
                 <?php } else if(file_exists('../uploads/'.$row7["Photo"])){?>
                 <img src="../uploads/<?php echo $row7["Photo"];?>" class="d-block ui-w-80 ui-bordered" alt="" style="width: 80px;height: 80px;">
                  <?php }  else{?>
                 <img src="no_image.jpg" class="d-block ui-w-80 ui-bordered" style="width: 80px;height: 80px;"> 
             <?php } ?>	

<div class="media-body ml-4">
<h4 class="font-weight-bold mb-2"><?php echo $row7["ProductName"]; ?></h4>
<a href="edit-product.php?id=<?php echo $row7['id']; ?>" class="btn btn-primary btn-sm">Edit</a>&nbsp;
<a href="../product-details.php?id=<?php echo $row7['id']; ?>&cat_id=<?php echo $row7['CatId']; ?>" target="_blank" class="btn btn-info btn-sm">Show item page</a>
</div>
</div>
<div class="nav-tabs-top nav-responsive-sm">
<ul class="nav nav-tabs">
<li class="nav-item">
<a class="nav-link active" data-toggle="tab" href="#item-overview">Overview</a>
</li>
<li class="nav-item">
<a class="nav-link" data-toggle="tab" href="#item-description">Description</a>
</li>
<li class="nav-item">
<a class="nav-link" data-toggle="tab" href="#item-discounts">Other Details</a>
</li>
<li class="nav-item">
<a class="nav-link" data-toggle="tab" href="#item-images">Images</a>
</li>
<li class="nav-item">
<a class="nav-link" data-toggle="tab" href="#item-size">Different Size Products</a>
</li>
</ul>
<div class="tab-content">

<div class="tab-pane fade show active" id="item-overview">
<div class="row no-gutters row-bordered">
<div class="d-flex col-md-6 col-lg align-items-center">
<div class="card-body d-flex align-items-center">
<div>
<div class="ui-stars text-large text-nowrap">
<div class="ui-star filled">
<i class="ion ion-md-star"></i>
<i class="ion ion-md-star"></i>
</div>
<div class="ui-star filled">
<i class="ion ion-md-star"></i>
 <i class="ion ion-md-star"></i>
</div>
<div class="ui-star filled">
<i class="ion ion-md-star"></i>
<i class="ion ion-md-star"></i>
</div>
<div class="ui-star filled">
<i class="ion ion-md-star"></i>
<i class="ion ion-md-star"></i>
</div>
<div class="ui-star half-filled">
<i class="ion ion-md-star"></i>
<i class="ion ion-md-star"></i>
</div>
</div>
<a href="javascript:void(0)" class="d-block text-muted small">123 reviews</a>
</div>
</div>
</div>
<div class="d-flex col-md-6 col-lg align-items-center">
<div class="card-body d-flex align-items-center">
<div class="lnr lnr-cart display-4 text-secondary"></div>
<div class="ml-3">
<div class="text-muted small line-height-1">Sales</div>
<div class="text-xlarge"> <?php $table = 'order_details';
        $val = 'sales'; 
        echo getPrdCnt($table,$val,$id);?></div>
</div>
</div>
</div>
<div class="d-flex col-md-6 col-lg align-items-center">
<div class="card-body d-flex align-items-center">
<div class="feather icon-box display-4 text-secondary"></div>
<div class="ml-3">
<div class="text-muted small line-height-1">Items In Stock <span class="badge badge-success">Instock</span></div>
<div class="text-xlarge"><?php echo number_format($row7['ItemStock']); ?></div>
</div>
</div>
</div>
<div class="d-flex col-md-6 col-lg align-items-center">
<div class="card-body d-flex align-items-center">
<div class="lnr lnr-earth display-4 text-secondary"></div>
<div class="ml-3">
<div class="text-muted small line-height-1">View</div>
<div class="text-xlarge">3,671</div>
</div>
</div>
</div>
</div>
<hr class="m-0">
<div class="card-body">
<h6 class="small font-weight-semibold mb-4">Basic info</h6>
<div class="table-responsive">
<table class="table product-item-table">
<tbody>
 <tr>
<td>Price:</td>
<td>
<strong>&#8377;<?php echo number_format($row7["MinPrice"],2); ?> &nbsp;&nbsp;<del>&#8377;<?php echo number_format($row7["MaxPrice"],2); ?></del></strong>
</td>
</tr>
<tr>
<td>Batch Code Of Product:</td>
<td><?php echo $row7['BatchCode']; ?></td>
</tr>
<tr>
<td>Category:</td>
<td><?php echo $row7['Category']; ?></td>
</tr>
<tr>
<td>Sub Category:</td>
<td><?php echo $row7['SubCategory']; ?></td>
</tr>
<tr>
<td>Brand:</td>
<td><?php echo $row7['Brand']; ?></td>
</tr>
<tr>
<td>Items in stock:</td>
<td><?php echo number_format($row7['ItemStock']); ?></td>
</tr>
<tr>
<td>Tax:</td>
<td><?php echo $row7['Tax']; ?>%</td>
</tr>
<tr>
<td>Size:</td>
<td><?php echo $row7['Size']; ?></td>
</tr>
<tr>
<td>Storages:</td>
<td><?php echo $row7['Storage']; ?></td>
</tr>
<tr>
<td>Colors:</td>
<td><?php echo $row7['Color']; ?></td>
</tr>
<tr>
<td>Offer Price:</td>
<td><?php echo number_format($row7['OfferPrice'],2); ?></td>
</tr>
<tr>
<td>Offer Percent:</td>
<td><?php echo $row7['OfferPer']; ?>%</td>
</tr>
<tr>
<td>Cashback:</td>
<td><?php echo $row7['Cashback']; ?>%</td>
</tr>
<tr>
<td>Status:</td>
<td>
	<?php if($row7['Status']=='1') {?>
<span class="badge badge-success">Published</span>
<?php } else{?>
	<span class="badge badge-danger">Not Published</span>
	<?php } ?>
</td>
</tr>
</tbody>
</table>
</div>
</div>
<hr class="m-0">

<div class="card-body product-item-table">
<h6 class="small font-weight-semibold mb-4">Meta</h6>
<div class="table-responsive">
<table class="table">
<tbody>
<tr>
<td>Title:</td>
<td><?php echo $row7['MetaTag']; ?></td>
</tr>
<tr>
<td>Description:</td>
<td><?php echo $row7['MetaDesc']; ?></td>
</tr>
<tr>
<td>Keywords:</td>
<td><?php echo $row7['Keywords']; ?></td>
</tr>
</tbody>
</table>
</div>
</div>
</div>


<div class="tab-pane fade" id="item-description">
<div class="card-body">
<div class="row">
<div class="col-md">
	<p style="text-align: justify;"><?php echo $row7["Details"]; ?></p>
</div>
</div>
</div>
</div>


<div class="tab-pane fade" id="item-discounts">
<div class="card-body">
<h6 class="small font-weight-semibold mb-4">Delivery Info</h6>
<hr>
<p style="text-align: justify;"><?php echo $row7["DeliveryInfo"]; ?></p>

<h6 class="small font-weight-semibold mb-4">Offers</h6>
<hr>
<p style="text-align: justify;"><?php echo $row7["Offers"]; ?></p>
 </div>
</div>


<div class="tab-pane fade" id="item-images">
<div class="card-body">
<div class="mb-4">
<span class="badge badge-dot badge-primary"></span> Primary image
</div>

<div id="product-item-lightbox" class="blueimp-gallery blueimp-gallery-controls">
<div class="slides"></div>
<h3 class="title"></h3>
<a class="prev">‹</a>
<a class="next">›</a>
<a class="close">×</a>
<ol class="indicator"></ol>
</div>
<div id="product-item-images" class="row">
	<?php 
  $id = $_GET['id'];
  $sql2 = "SELECT * FROM product_images WHERE ProductId='$id'";
  $res2 = $conn->query($sql2);
  $rncnt = mysqli_num_rows($res2);
  if($rncnt > 0){
    while($row2 = $res2->fetch_assoc()){?>
	<div class="col-sm-6 col-md-6 col-xl-3 mb-4">
<a href="../uploads/<?php echo $row2['Files'];?>" class="img-thumbnail img-thumbnail-shadow">
<span class="img-thumbnail-overlay bg-white opacity-25"></span>
<img src="../uploads/<?php echo $row2['Files'];?>" alt class="img-fluid" style="width: 230px;height: 160px;">
</a>
</div>
<?php }} ?>
</div>
</div>
</div>

<div class="tab-pane fade" id="item-size">
<div class="card-body">
  <?php 
  $ProdId = $_GET['id'];
         $sql3 = "SELECT * FROM related_products WHERE ProdId='$ProdId'";
        $res3 = $conn->query($sql3);
        $rncnt3 = mysqli_num_rows($res3);
        if($rncnt3 > 0){
        while($row3 = $res3->fetch_assoc()){
?>
<div class="form-row" style="padding-top: 10px;">
  <?php 
   $AttrValueSize = $row3['AttrValueSize'];
  $q4 = "select * from attribute_value WHERE AttrNameId='1' AND id='$AttrValueSize' AND Status='1'";
  $r4 = $conn->query($q4); 
  $rncnt7 = mysqli_num_rows($r4);
  if($rncnt7 > 0){         
  $rw = $r4->fetch_assoc()  
    ?>   
<div class="form-group col-md-2">
  <label class="form-label">Size </label>
    <input type="text" class="form-control" value="<?php echo $rw['Name']; ?>" disabled>

</div>
<?php } 
$AttrValueRam = $row3['AttrValueRam'];
  $q3 = "select * from attribute_value WHERE AttrNameId='4' AND id='$AttrValueRam' AND Status='1'";
  $r3 = $conn->query($q3);
   $rncnt6 = mysqli_num_rows($r3);
  if($rncnt6 > 0){
    $rw2 = $r3->fetch_assoc()?>
  <div class="form-group col-md-2">
  <label class="form-label">Ram </label>
    <input type="text" class="form-control" value="<?php echo $rw2['Name']; ?>" disabled>
</div>  
<?php }  
$AttrValueStorage = $row3['AttrValueStorage'];
$q2 = "select * from attribute_value WHERE AttrNameId='2' AND SubCatId='$SubCatId' AND Status='1'";
$r2 = $conn->query($q2);
$rncnt5 = mysqli_num_rows($r2);
  if($rncnt5 > 0){
$rw3 = $r2->fetch_assoc()    
?>
<div class="form-group col-md-2">
  <label class="form-label">Storage </label>
    <input type="text" class="form-control" value="<?php echo $rw3['Name']; ?>" disabled>
</div> 
<?php } ?>
<div class="form-group col-lg-2">
<label class="form-label">Market Price</label>
<div class="input-group">
<div class="input-group-prepend">
<div class="input-group-text">&#8377;</div>
</div>
<input type="text" value="<?php echo $row3["MaxPrice"]; ?>" class="form-control" disabled>
<div class="clearfix"></div>
</div>
</div>    
<div class="form-group col-lg-2">
<label class="form-label">Our Price</label>
<div class="input-group">
<div class="input-group-prepend">
<div class="input-group-text">&#8377;</div>
</div>
<input type="text" value="<?php echo $row3["MinPrice"]; ?>" class="form-control" disabled>
<div class="clearfix"></div>
</div>
</div>
<div class="form-group col-lg-2">
<label class="form-label">Offer Price</label>
<div class="input-group">
<div class="input-group-prepend">
<div class="input-group-text">&#8377;</div>
</div>
<input type="text" value="<?php echo $row3["OfferPrice"]; ?>" class="form-control" disabled>
<div class="clearfix"></div>
</div>
</div>
<div class="form-group col-lg-2">
<label class="form-label">Offer Percent</label>
<div class="input-group">
<div class="input-group-prepend">
<div class="input-group-text">&#8377;</div>
</div>
<input type="text" value="<?php echo $row3["OfferPer"]; ?>%" class="form-control" disabled>
<div class="clearfix"></div>
</div>
</div>

<!-- <div class="form-group col-lg-2">
<label class="form-label">Items in stock</label>
 <input type="number" value="<?php echo $row3["ItemStock"]; ?>" class="form-control" disabled>
<div class="clearfix"></div>
</div> -->

<div class="form-group col-lg-2">
<label class="form-label">Product Stock</label><br>
<?php if($row3["Stock"]=='1') {?>
  <span class="form-control" style="color: green;">Instock</span>
<?php } if($row3["Stock"]=='0') {?>
  <span class="form-control" style="color: red;">Out Of Instock</span>
<?php } ?>  
</div>
</div>
<?php }}?>
</div>

</div>
</div>
</div>


<?php include_once 'footer.php'; ?>

</div>

</main>

    <!-- footer-->
    


    <!-- Required jquery and libraries -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- cookie js -->
    <script src="js/jquery.cookie.js"></script>

    <!-- Swiper slider  js-->
    <script src="vendor/swiper/js/swiper.min.js"></script>

    <!-- Customized jquery file  -->
    <script src="js/main.js"></script>
    <script src="js/color-scheme-demo.js"></script>


    <!-- page level custom script -->
    <script src="js/app.js"></script>
       <?php include_once 'footer_script.php'; ?></body>
</html>
