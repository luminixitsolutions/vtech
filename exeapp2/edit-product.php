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
<title><?php echo $Proj_Title; ?> | Edit Products</title>
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
<h4 class="font-weight-bold py-3 mb-0">Edit product</h4>
<div class="text-muted small mt-0 mb-4 d-block breadcrumb">
<ol class="breadcrumb">
<li class="breadcrumb-item"><a href="#"><i class="feather icon-home"></i></a></li>
<li class="breadcrumb-item">Products</li>
<li class="breadcrumb-item active">Edit product</li>
</ol>
</div>
<?php 
$id = $_GET['id'];
$sql7 = "SELECT * FROM products WHERE id='$id'";
$res7 = $conn->query($sql7);
$row7 = $res7->fetch_assoc();
$row7['Color'] = explode(",", $row7['Color']);
$row7['DdsOffers'] = explode(",",$row7['DdsOffers']);

if($_REQUEST["action"]=="color_photo_delete")
{
  $id = $_REQUEST["id"];
  $prdid = $_REQUEST["prdid"];
   $query2 = "SELECT Photo FROM temp_color WHERE id = '$id'";
    $row2 = getRecord($query2);
    $Photo2 = $row2['Photo'];
    $src2 = "../uploads/$Photo2";
        unlink($src2);
     $sql11 = "UPDATE temp_color SET Photo='' WHERE id = '$id'";
    $conn->query($sql11);?>
    <script type="text/javascript">
      alert("Photo Deleted Successfully!");
      window.location.href="edit-product.php?id=<?php echo $prdid;?>";
    </script>
<?php    

}  
?>
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
     <input type="hidden" name="id" id="id" value="<?php echo $_GET['id']; ?>"/> 
    <input type="hidden" name="action" value="Edit">
<div class="form-group">
<label class="form-label">Product Name<span class="text-danger">*</span></label>
<input type="text" class="form-control" name="ProductName" value="<?php echo $row7["ProductName"]; ?>" required="">
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
                <option <?php if($row7['CatId']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?></select>
<div class="clearfix"></div>
</div>
<div class="form-group col-lg-6">
<label class="form-label">Sub Category</label>
  <select class="form-control" id="SubCatId" name="SubCatId">
<option selected="" value="0">Select Sub Category</option>
<?php 
    $CatId = $row7['CatId'];
        $q = "select * from sub_category WHERE CatId ='$CatId' AND Status='1'";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($row7['SubCatId']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
<?php } ?>
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
        $CatId = $row7['CatId'];
        $q = "select * from brands WHERE Status='1'";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($row7['BrandId']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?></select>
</div>

<div class="form-group col-lg-6">
<label class="form-label">Batch Code Of Product</label>
 <input type="text" class="form-control" value="<?php echo $row7["BatchCode"]; ?>" name="BatchCode">
<div class="clearfix"></div>
</div>
</div>

<div class="form-row" id="attributes">
  <?php $subid = $row7['CatId']; ?>
  <?php 
    $q4 = "select * from attribute_value WHERE AttrNameId='1' AND CatId='$subid' AND Status='1'";
    $r4 = $conn->query($q4);
    $rncnt7 = mysqli_num_rows($r4);
  if($rncnt7 > 0){  
   ?>
<div class="form-group col-md-12">
    <input type="hidden" name="NameSize" id="NameSize" value="1">
<label class="form-label">Size </label>
        <select class="form-control" name="Size" id="Size" >
<option selected="" disabled="">Select Size</option>
    <?php 
        while($rw = $r4->fetch_assoc())
                                    {
                                ?>

                                                <option <?php if($row7['Size']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>

                                              <?php } ?>
</select>
</div>
<?php } ?>

</div>


 <div class="form-row">
  <div class="form-group col-lg-6">
<label class="form-label">Market Price<span class="text-danger">*</span></label>
<div class="input-group">
<div class="input-group-prepend">
<div class="input-group-text">&#8377;</div>
</div>
<input type="text" id="MaxPrice" name="MaxPrice" class="form-control" value="<?php echo $row7["MaxPrice"]; ?>" required="" onKeyPress="return isNumberKey(event)">
<div class="clearfix"></div>
</div>
</div>
<div class="form-group col-lg-6">
<label class="form-label">Our Price<span class="text-danger">*</span></label>
<div class="input-group">
<div class="input-group-prepend">
<div class="input-group-text">&#8377;</div>
</div>
<input type="text" id="MinPrice" name="MinPrice" class="form-control" value="<?php echo $row7["MinPrice"]; ?>" required="" onKeyPress="return isNumberKey(event)">
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
<input type="text" id="OfferPrice" name="OfferPrice" class="form-control" value="<?php echo $row7["OfferPrice"]; ?>" readonly="">
<div class="clearfix"></div>
</div>
</div>
<div class="form-group col-lg-6">
<label class="form-label">Offer Percentage<span class="text-danger">*</span></label>
<div class="input-group">
<input type="text" id="OfferPer" name="OfferPer" class="form-control" value="<?php echo $row7["OfferPer"]; ?>" readonly="">
<div class="clearfix"></div>
<div class="input-group-prepend">
<div class="input-group-text">%</div>
</div>
</div>
</div>

<!--<div class="form-group col-lg-4">
<label class="form-label">Discount<span class="text-danger">*</span></label>
<div class="input-group">
<input type="text" id="Discount" name="Discount" class="form-control" value="<?php echo $row7["Discount"]; ?>" required>
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
<input type="checkbox" name="Featured" value="1" <?php if($row7["Featured"]=='1') {?> checked <?php } ?> class="custom-control-input">
<span class="custom-control-label">Featured</span>
</label>
</div>
<div class="form-group col-lg-3">
<label class="custom-control custom-checkbox m-0">
<input type="checkbox" name="FreeShipping" value="1" <?php if($row7["FreeShipping"]=='1') {?> checked <?php } ?> class="custom-control-input">
<span class="custom-control-label">Free Shipping</span>
</label>
</div>
<div class="form-group col-lg-3">
<label class="custom-control custom-checkbox m-0">
<input type="checkbox" name="Bestseller" value="1" <?php if($row7["Bestseller"]=='1') {?> checked <?php } ?> class="custom-control-input">
<span class="custom-control-label">Bestseller</span>
</label>
</div>
<div class="form-group col-lg-3">
<label class="custom-control custom-checkbox m-0">
<input type="checkbox" name="Trending" value="1" <?php if($row7["Trending"]=='1') {?> checked <?php } ?> class="custom-control-input">
<span class="custom-control-label">Show In Home Page</span>
</label>
</div>

</div>





<div class="form-row">

<!--<div class="form-group col-lg-6">
<label class="form-label">Cashback</label>
<div class="input-group">
<input type="text" class="form-control" name="Cashback" value="<?php echo $row7["Cashback"]; ?>" required="" onKeyPress="return isNumberKey(event)">
<div class="input-group-append">
<div class="input-group-text">%</div>
</div>
</div>
</div>-->
<!-- <div class="form-group col-lg-12">
<label class="form-label">Minimum Purchase Qty<span class="text-danger">*</span></label>
<input type="number" class="form-control" name="MinQty" value="<?php echo $row7["MinQty"]; ?>" required="" min=1>
<div class="clearfix"></div>
</div> -->

<div class="form-group col-lg-6">
<label class="form-label">Items in stock<span class="text-danger">*</span></label>
 <input type="number" min='0' name="ItemStock" class="form-control" value="<?php echo $row7["ItemStock"]; ?>" required="">
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-6">
<label class="form-label">Product Stock<span class="text-danger">*</span></label>
<select class="form-control" name="Stock" required="">
<option value="1" <?php if($row7["Stock"]=='1') {?> selected <?php } ?>>Instock</option>
<option value="0" <?php if($row7["Stock"]=='0') {?> selected <?php } ?>>Out of stock</option>
</select>
</div>

<div class="form-group col-lg-12">
<label class="form-label">Status<span class="text-danger">*</span></label>
<select class="form-control" name="Status" required="">
<option value="1" <?php if($row7["Status"]=='1') {?> selected <?php } ?>>Publish</option>
<option value="0" <?php if($row7["Status"]=='0') {?> selected <?php } ?>>Not Publish</option>
</select>
</div>
</div>

 



<div class="form-row">

</div>
<span class="text-danger">*</span> Mandatory
</div>
</div>
<div id="smartwizard-6-step-2" class="card animated fadeIn">
 
<div class="card-body">
  
 <div class="form-row">
<div class="form-group col-md-12">
  <label class="form-label">Product Details <span class="text-danger">*</span></label>
<textarea class="form-control" rows="10" name="Details" autocomplete="off" required="required" id="editor1"><?php echo $row7["Details"]; ?></textarea>
</div>

<span class="text-danger">*</span> Mandatory
</div>


</div>
</div>

<div id="smartwizard-6-step-3" class="card animated fadeIn">
<div class="card-body">
 <div class="form-row">
<div class="form-group col">
  <label class="form-label">Product Image <span class="text-danger">*</span></label>
<label class="custom-file">
<input type="file" class="custom-file-input" id="Photo" name="Photo" style="opacity: 1;">
<input type="hidden" name="OldPhoto" id="OldPhoto" value="<?php echo $row7["Photo"]; ?>">
<span class="custom-file-label"></span>
</label>
<?php if($row7['Photo']=='') {} else{?>
  <span id="show_photo">
<div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a href="javascript:void(0)" class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white" id="delete_photo"></a><img src="../uploads/<?php echo $row7['Photo'];?>" alt="" class="img-fluid ticket-file-img" style="width: 64px;height: 64px;"></div>
</span>
<?php } ?>
</div>
</div>

 <div class="form-row">
<div class="form-group col">
  <label class="form-label">Product Image (Multiple) <span class="text-danger">(File size must be less than 2 MB)</span></label>
<label class="custom-file">
<input type="file" class="custom-file-input" id="Photo2" name="Files[]" style="opacity: 1;" multiple="">
<span class="custom-file-label"></span>
</label>
 <span id="show_photo2">
<?php 
  $id = $_GET['id'];
  $sql2 = "SELECT * FROM product_images WHERE ProductId='$id'";
  $res2 = $conn->query($sql2);
  $rncnt = mysqli_num_rows($res2);
  if($rncnt > 0){
    while($row2 = $res2->fetch_assoc()){?>
    <input type="hidden" name="OldMulImage" id="OldMulImage<?php echo $row2["id"]; ?>" value="<?php echo $row2["Files"]; ?>">
<div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a href="javascript:void(0)" class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white" onclick="delete_photo2(<?php echo $row2["id"]; ?>,<?php echo $_GET["id"]; ?>)"></a><img src="../uploads/<?php echo $row2['Files'];?>" alt="" class="img-fluid ticket-file-img" style="width: 64px;height: 64px;"></div>
<?php }} ?>
</span>
</div>
</div>
<span class="text-danger">*</span> Mandatory
</div>
</div>

<div id="smartwizard-6-step-4" class="card animated fadeIn">
  <div class="card-body">
      <input type="hidden" id="ProdId" value="<?php echo $_GET['id']; ?>">
      <?php 
      $ProdId = $_GET['id'];
         $sql3 = "SELECT * FROM related_products WHERE ProdId='$ProdId'";
        $res3 = $conn->query($sql3);
        $rncnt3 = mysqli_num_rows($res3);
       ?>
        <input type="hidden" id="rn_cnt" value="<?php echo $rncnt3; ?>">
    <div id="show_attr">
    

    
     </div>
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

function showProdImages(pid){
  var action = "showProdImages";
    $.ajax({
    url:"ajax_files/ajax_shop_products.php",
    method:"POST",
    data : {action:action,id:pid},
    success:function(data)
    {
      $('#show_photo2').html(data);
    }
    });
}

function delete_photo2(id,pid){
  if(confirm("Are you sure you want to delete Product Photo?"))  
           {  
             var action = "deletePhoto2";
            
             var Photo = $('#OldMulImage'+id).val();
             $.ajax({
    url:"ajax_files/ajax_shop_products.php",
    method:"POST",
    data : {action:action,id:id,pid:pid,Photo:Photo},
    success:function(data)
    {
      
      $('#OldMulImage'+id).val('');
      showProdImages(pid);
      var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
   $.growl.success({
      title:    'Success',
      message:  data,
      location: isRtl ? 'tl' : 'tr'
    });

    }
    });
           }
}

function deleteAttr(id,pid){
  if(confirm("Are you sure you want to delete?"))  
           {  
    var CatId = $('#CatId').val();
        var action = 'delete_attr';
      $.ajax({
  type: "POST",
  url: "ajax_files/ajax_shop_products.php",
   data:{action:action,id:id,pid:pid},  
  success: function(data){
      //$('#show_attr').html(data);
       showAttributes(pid,CatId);
      
  }
  });
    }
    else{}
}

function showAttributes(pid,CatId){
        var action = 'save_view_attr';
      $.ajax({
  type: "POST",
  url: "ajax_files/ajax_shop_products.php",
   data:{action:action,id:pid,CatId:CatId},  
  success: function(data){
      $('#show_attr').html(data);
      
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

     var i=1;  
      $('#addMore').click(function(){  
        
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

    var pid = $('#ProdId').val();
    var val = $('#CatId').val();
    var rncnt = $('#rn_cnt').val();
    showAttributes(pid,val);
    if(rncnt > 0){
       var action = 'edit_view_attr';
      $.ajax({
  type: "POST",
  url: "ajax_files/ajax_shop_products.php",
   data:{action:action,id:val,rncnt:rncnt},  
  success: function(data){
      $('#show').html(data);
      
  }
  });
    }
    else{
         var action = 'view_attr';
      $.ajax({
  type: "POST",
  url: "ajax_files/ajax_shop_products.php",
   data:{action:action,id:val},  
  success: function(data){
      $('#show').html(data);
      
  }
  });
    }
    
       
    

 $(document).on("input", "#MinPrice", function(event){
   calculate();

  });

 $(document).on("input", "#MaxPrice", function(event){
   calculate();

  });

/*$("#Photo2").on("change", function() {
    if ($("#Photo2")[0].files.length > 4) {
        alert("You can select only 5 images");
    } else {
        
    }
});*/
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

     var action = 'view_attr';
      $.ajax({
  type: "POST",
  url: "ajax_files/ajax_shop_products.php",
   data:{action:action,id:val},  
  success: function(data){
      $('#show').html(data);
      
  }
  });
       var action = 'getAttributes';
      $.ajax({
  type: "POST",
  url: "ajax_files/ajax_shop_products.php",
   data:{action:action,id:val},  
  success: function(data){
      $('#attributes').html(data);
      
  }
  });
    //getBrands(val);

 });

     /* $(document).on("change", "#SubCatId", function(event){

         var val = this.value;
          var CatId = $('#CatId').val();
       
     });
*/
      $(document).on("click", "#delete_photo", function(event){
event.preventDefault();  
if(confirm("Are you sure you want to delete Product Photo?"))  
           {  
             var action = "deletePhoto";
             var id = $('#id').val();
             var Photo = $('#OldPhoto').val();
             $.ajax({
    url:"ajax_files/ajax_shop_products.php",
    method:"POST",
    data : {action:action,id:id,Photo:Photo},
    success:function(data)
    {
      $('#show_photo').hide();
      $('#OldPhoto').val('');
      var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
   $.growl.success({
      title:    'Success',
      message:  data,
      location: isRtl ? 'tl' : 'tr'
    });

    }
    });
           }

   });




  
   });
</script>
</body>
</html>
