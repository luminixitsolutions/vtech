<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage="E-Commerce";
$Page = "Add-Product";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | Add Products</title>
 <!-- manifest meta -->
    <meta name="apple-mobile-web-app-capable" content="yes">

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="img/favicon180.png" sizes="180x180">
    <link rel="icon" href="img/favicon32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="img/favicon16.png" sizes="16x16" type="image/png">

    <!-- Material icons-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&amp;display=swap" rel="stylesheet">

    <!-- swiper CSS -->
    <link href="vendor/swiper/css/swiper.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet" id="style">
    <link href="css/toastr.min.css" rel="stylesheet">
    <script src="js/jquery.min3.5.1.js" type="text/javascript"></script>
    <script type="text/javascript" src="js/toastr.min.js"></script>
    <link rel="stylesheet" href="example/css/slim.min.css">
    <?php include_once 'header_script.php'; ?>
<script src="ckeditor/ckeditor.js"></script>
</head>
<body>

<body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <!-- Fixed navbar -->
        <?php include_once 'back-header.php'; ?> 
        

        <div class="main-container">

<?php include_once 'ecommerce-sidebar.php'; ?>






<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Add product</h4>
<div class="text-muted small mt-0 mb-4 d-block breadcrumb">
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="#"><i class="feather icon-home"></i></a></li>
<li class="breadcrumb-item">Products</li>
<li class="breadcrumb-item active">Add product</li>
</ol>
</div>

<form id="smartwizard-6" novalidate="novalidate" action="ajax_files/ajax_shop_products.php" method="POST" enctype="multipart/form-data" autocomplete="off">
<ul class="card px-4 pt-3 mb-3">
<li>
<a href="#smartwizard-6-step-1" class="mb-3">
<span class="sw-done-icon ion ion-md-checkmark"></span>
<span class="sw-number">1</span>
<div class="text-muted small">FIRST STEP</div>
Basic Info
</a>
</li>
<li>
<a href="#smartwizard-6-step-2" class="mb-3">
<span class="sw-done-icon ion ion-md-checkmark"></span>
<span class="sw-number">2</span>
<div class="text-muted small">SECOND STEP</div>
Description
</a>
</li>
<li>
<a href="#smartwizard-6-step-3" class="mb-3">
<span class="sw-done-icon ion ion-md-checkmark"></span>
<span class="sw-number">3</span>
<div class="text-muted small">THIRD STEP</div>
Product Images
</a>
</li>
 <li>
<a href="#smartwizard-6-step-4" class="mb-3">
<span class="sw-done-icon ion ion-md-checkmark"></span>
<span class="sw-number">4</span>
<div class="text-muted small">FOURTH STEP</div>
Other Products
</a>
</li>
<!--<li>
<a href="#smartwizard-6-step-5" class="mb-3">
<span class="sw-done-icon ion ion-md-checkmark"></span>
<span class="sw-number">5</span>
<div class="text-muted small">FIFTH STEP</div>
Meta Tags
</a>
</li> -->
</ul>
<div class="mb-3">
<div id="smartwizard-6-step-1" class="card animated fadeIn">
<div class="card-body">
    <input type="hidden" name="action" value="Add">
<div class="form-group">
<label class="form-label">Product Name<span class="text-danger">*</span></label>
<input type="text" class="form-control" name="ProductName" value="" required="">
<div class="clearfix"></div>
</div>
  <div class="form-row">
<div class="form-group col-lg-6">
<label class="form-label">Category <span class="text-danger">*</span></label>
  <select class="form-control" id="CatId" name="CatId" required="">
<option selected="" disabled="" value="">Select Category</option>
<?php 
        $q = "select * from category WHERE Status='1'";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?></select>
<div class="clearfix"></div>
</div>
<div class="form-group col-lg-6">
<label class="form-label">Sub Category</label>
  <select class="form-control" id="SubCatId" name="SubCatId">
<option selected="" value="0">Select Sub Category</option>
</select>
<div class="clearfix"></div>
</div>
</div>
<div class="form-row">
<div class="form-group col-lg-6">
<label class="form-label">Brand</label>
<select class="form-control" id="BrandId" name="BrandId">
<option selected="" value="0">Select Brand</option>
<?php 
        $q = "select * from brands WHERE Status='1'";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?></select>
</div>

<div class="form-group col-lg-6">
<label class="form-label">Batch Code Of Product</label>
 <input type="text" class="form-control" value="" name="BatchCode">
<div class="clearfix"></div>
</div>
</div>

<div class="form-row" id="attributes">

</div>

 <div class="form-row">
  <div class="form-group col-lg-6">
<label class="form-label">Market Price<span class="text-danger">*</span></label>
<div class="input-group">
<div class="input-group-prepend">
<div class="input-group-text">&#8377;</div>
</div>
<input type="text" id="MaxPrice" name="MaxPrice" class="form-control" value="" required="" onKeyPress="return isNumberKey(event)">
<div class="clearfix"></div>
</div>
</div>
<div class="form-group col-lg-6">
<label class="form-label">Our Price<span class="text-danger">*</span></label>
<div class="input-group">
<div class="input-group-prepend">
<div class="input-group-text">&#8377;</div>
</div>
<input type="text" id="MinPrice" name="MinPrice" class="form-control" value="" required="" onKeyPress="return isNumberKey(event)">
<div class="clearfix"></div>
</div>
</div>

</div>

 <div class="form-row">
<div class="form-group col-lg-6">
<label class="form-label">Offer Price<span class="text-danger">*</span></label>
<div class="input-group">
<div class="input-group-prepend">
<div class="input-group-text">&#8377;</div>
</div>
<input type="text" id="OfferPrice" name="OfferPrice" class="form-control" value="" readonly="">
<div class="clearfix"></div>
</div>
</div>
<div class="form-group col-lg-6">
<label class="form-label">Offer Percentage<span class="text-danger">*</span></label>
<div class="input-group">
<input type="text" id="OfferPer" name="OfferPer" class="form-control" value="" readonly="">
<div class="clearfix"></div>
<div class="input-group-prepend">
<div class="input-group-text">%</div>
</div>
</div>
</div>

<!--<div class="form-group col-lg-4">
<label class="form-label">Discount<span class="text-danger">*</span></label>
<div class="input-group">
<input type="text" id="Discount" name="Discount" class="form-control" value="0" required>
<div class="clearfix"></div>
<div class="input-group-prepend">
<div class="input-group-text">%</div>
</div>
</div>
</div>-->
</div>



<div class="form-row">
<div class="form-group col-lg-3">
<label class="custom-control custom-checkbox m-0">
<input type="checkbox" name="Featured" value="1" class="custom-control-input">
<span class="custom-control-label">Featured</span>
</label>
</div>
<div class="form-group col-lg-3">
<label class="custom-control custom-checkbox m-0">
<input type="checkbox" name="FreeShipping" value="1" class="custom-control-input">
<span class="custom-control-label">Free Shipping</span>
</label>
</div>
<div class="form-group col-lg-3">
<label class="custom-control custom-checkbox m-0">
<input type="checkbox" name="Bestseller" value="1" class="custom-control-input">
<span class="custom-control-label">Bestseller</span>
</label>
</div>
<div class="form-group col-lg-3">
<label class="custom-control custom-checkbox m-0">
<input type="checkbox" name="Trending" value="1" class="custom-control-input">
<span class="custom-control-label">Show In Home Page</span>
</label>
</div>


</div>



<div class="form-row">
<!--   <div class="form-group col-lg-6">
<label class="form-label">Tax</label>
<div class="input-group">
<input type="text" class="form-control" name="Tax" value="0" required="" onKeyPress="return isNumberKey(event)">
<div class="input-group-append">
<div class="input-group-text">%</div>
</div>
</div>
</div> -->
<!--<div class="form-group col-lg-6">
<label class="form-label">Cashback</label>
<div class="input-group">
<input type="text" class="form-control" name="Cashback" value="0" required="" onKeyPress="return isNumberKey(event)">
<div class="input-group-append">
<div class="input-group-text">%</div>
</div>
</div>
</div>-->
<!--<div class="form-group col-lg-6">
<label class="form-label">Items in stock<span class="text-danger">*</span></label>
 <input type="number" min='0' name="ItemStock" class="form-control" value="0" required="">
<div class="clearfix"></div>
</div>-->

</div>

 



<div class="form-row">
<!-- <div class="form-group col-lg-12">
<label class="form-label">Minimum Purchase Qty<span class="text-danger">*</span></label>
<input type="number" class="form-control" name="MinQty" value="1" required="" min=1>
<div class="clearfix"></div>
</div> -->
<div class="form-group col-lg-6">
<label class="form-label">Product Stock<span class="text-danger">*</span></label>
<select class="form-control" name="Stock" required="">
<option value="1">Instock</option>
<option value="0">Out of stock</option>
</select>
</div>
<div class="form-group col-lg-6">
<label class="form-label">Status<span class="text-danger">*</span></label>
<select class="form-control" name="Status" required="">
<option value="1">Publish</option>
<option value="0">Not Publish</option>
</select>
</div>
</div>
<span class="text-danger">*</span> Mandatory
</div>
</div>
<div id="smartwizard-6-step-2" class="card animated fadeIn">
<div class="card-body">
 <div class="form-row">
<div class="form-group col-md-12">
  <label class="form-label">Product Details <span class="text-danger">*</span></label>
<textarea class="form-control" rows="10" name="Details" autocomplete="off" required="required" id="editor1"></textarea>
</div>
</div>

<span class="text-danger">*</span> Mandatory
</div>


</div>
<div id="smartwizard-6-step-3" class="card animated fadeIn">
<div class="card-body">
 <div class="form-row">
<div class="form-group col">
  <label class="form-label">Product Image <span class="text-danger">*</span></label>
<label class="custom-file">
<input type="file" class="custom-file-input" id="Photo" name="Photo" style="opacity: 1;" required="">
<input type="hidden" name="OldPhoto" id="OldPhoto">
<span class="custom-file-label"></span>
</label>
</div>
</div>

 <div class="form-row">
<div class="form-group col">
  <label class="form-label">Product Image (Multiple) <span class="text-danger">(File size must be less than 2 MB)</span></label>
<label class="custom-file">
<input type="file" class="custom-file-input" id="Photo2" name="Files[]" style="opacity: 1;" multiple="">
<span class="custom-file-label"></span>
</label>
</div>
</div>
<span class="text-danger">*</span> Mandatory
</div>
</div>

<div id="smartwizard-6-step-4" class="card animated fadeIn">
  <div class="card-body">
<span id="show"></span>
  <div id="dynamic_field"></div>
</div>
</div>

</div>
</form>


</div>

</div>

<?php include_once 'footer.php'; ?>

</div>

</div>

</div>

<div class="layout-overlay layout-sidenav-toggle"></div>
</div>

<?php include_once 'footer_script.php'; ?>

<script type="text/javascript">
CKEDITOR.replace( 'editor1' );
    function isNumberKey(evt){ 
    var charCode = (evt.which) ? evt.which : evt.keyCode
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}
function getAttrValue(id,val){
 var action = "getAttrValue";
    $.ajax({
    url:"ajax_files/ajax_dropdown.php",
    method:"POST",
    data : {action:action,id:val},
    success:function(data)
    {
      $('#AttrValue'+id).html(data);
    }
    });
}

function getBrands(id){
  var action = "getBrands";
    $.ajax({
    url:"ajax_files/ajax_dropdown.php",
    method:"POST",
    data : {action:action,id:id},
    success:function(data)
    {
      $('#BrandId').html(data);
    }
    });
}
function calculate(){
   var MinPrice = $('#MinPrice').val();
    var MaxPrice = $('#MaxPrice').val();
    var OfferPrice = Number(MaxPrice) - Number(MinPrice);
    $('#OfferPrice').val(parseFloat(OfferPrice).toFixed(2));
     var perc="";
            if(isNaN(MinPrice) || isNaN(MaxPrice)){
                perc=" ";
               }else{
               perc = Math.trunc(((MaxPrice-MinPrice)/MaxPrice * 100));
               }
            
            $('#OfferPer').val(perc);
}
   $(document).ready(function() {

 $(document).on("input", "#MinPrice", function(event){
   calculate();

  });

 $(document).on("input", "#MaxPrice", function(event){
   calculate();

  });

     $(document).on("change", "#CatId", function(event){
  var val = this.value;
   var action = "getSubCat";
    $.ajax({
    url:"ajax_files/ajax_dropdown.php",
    method:"POST",
    data : {action:action,id:val},
    success:function(data)
    {
      $('#SubCatId').html(data);
    }
    });

        var action2 = 'view_attr';
      $.ajax({
  type: "POST",
  url: "ajax_files/ajax_shop_products.php",
   data:{action:action2,id:val},  
  success: function(data){
      $('#show').html(data);
      
  }
  });
      var action3 = 'getAttributes';
      $.ajax({
  type: "POST",
  url: "ajax_files/ajax_shop_products.php",
   data:{action:action3,id:val},  
  success: function(data){
      $('#attributes').html(data);
      
  }
  });

    //getBrands(val);

 });

       /* $(document).on("change", "#SubCatId", function(event){
         var val = this.value;
         var CatId = $('#CatId').val();
    
     });*/

   $('.bs-markdown').markdown({
    iconlibrary: 'fa',
    footer: '<div id="md-character-footer"></div><small id="md-character-counter" class="text-muted">350 character left</small>',

    onChange: function(e) {
      var contentLength = e.getContent().length;

      if (contentLength > 350) {
        $('#md-character-counter')
          .removeClass('text-muted')
          .addClass('text-danger')
          .html((contentLength - 350) + ' character surplus.');
      } else {
        $('#md-character-counter')
          .removeClass('text-danger')
          .addClass('text-muted')
          .html((350 - contentLength) + ' character left.');
      }
    },
  });



   var i=1;  
      $('#add').click(function(){  
        alert(data);
           i++;  
             var action = "getMoreAttributes";
    $.ajax({
    url:"ajax_files/ajax_dropdown.php",
    method:"POST",
    data : {action:action,id:i},
    success:function(data)
    {

      
       $('#dynamic_field').append(data);
    }
    });
           
      });  
      $(document).on('click', '.btn_remove', function(){  
           var button_id = $(this).attr("id");   
           $('#row'+button_id+'').remove();
          

      });  
   });
</script>
</body>
</html>
