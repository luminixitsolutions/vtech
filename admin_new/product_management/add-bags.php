<?php 
session_start();
include_once '../config.php';
include_once '../auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage="Products";
$Page = "Add-Products";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
    <title><?php echo $Proj_Title;?></title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="Codedthemes" />
    <link rel="icon" type="image/x-icon" href="<?php echo $SiteUrl;?>/assets/img/favicon.ico">

    <!-- Google fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
    <!-- Icon fonts -->
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/fontawesome.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/ionicons.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/linearicons.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/open-iconic.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/pe-icon-7-stroke.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/fonts/feather.css">

    <!-- Core stylesheets -->
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/css/bootstrap-material.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/css/shreerang-material.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/css/uikit.css">

    <!-- Libs -->
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/libs/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="<?php echo $SiteUrl;?>/assets/libs/flot/flot.css">

</head>

<body>
    <!-- [ Preloader ] Start -->
    <div class="page-loader">
        <div class="bg-primary"></div>
    </div>
    <!-- [ Preloader ] Ebd -->
    <!-- [ Layout wrapper ] Start -->
    <div class="layout-wrapper layout-2">
        <div class="layout-inner">

            <?php include_once 'product-sidebar.php'; ?>

                <div class="layout-container">

                <?php include_once '../top_header.php'; ?>
                <!-- [ Layout content ] Start -->
                <div class="layout-content">
                    <!-- [ content ] Start -->
                    <div class="container flex-grow-1 container-p-y">
                        <h5 class="font-weight-bold py-3 mb-0">Create Bags</h5>
                        
 <?php 
$id = $_GET['id'];
$sql7 = "SELECT * FROM tbl_products WHERE id='$id'";
$row7 = getRecord($sql7);

 $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (isset($_POST['submit'])) {
    $BagTitle = $conn->real_escape_string(trim($_POST["Title"]));
    $BagDetails = addslashes(trim($_POST["Details"]));
    $Price = $_POST['Price'];
    $user_id    = 1; // replace with $_SESSION['User']['id']
    $now        = date("Y-m-d H:i:s");
    $CreatedDate = $_POST['CreatedDate'];
    
    function RandomStringGenerator($n)
{
    $generated_string = "";   
    $domain = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
    $len = strlen($domain);
    for ($i = 0; $i < $n; $i++)
    {
        $index = rand(0, $len - 1);
        $generated_string = $generated_string . $domain[$index];
    }
    return $generated_string;
} 
$n = 10;
$Code = RandomStringGenerator($n);

    // ✅ If no id → Insert Bag
    if ($id == 0) {
        $sqlBag = "INSERT INTO tbl_products 
            SET ProductName='$BagTitle',
                Details='$BagDetails',
                Status=1,Roll=2,Code='$Code',
                CreatedBy='$user_id',Price='$Price',
                CreatedDate='$CreatedDate'";
        $conn->query($sqlBag);
        $bagId = mysqli_insert_id($conn);
    } else {
        // ✅ Update Bag
        $sqlBag = "UPDATE tbl_products 
            SET ProductName='$BagTitle',
                Details='$BagDetails',
                Status=1,Price='$Price',
                ModifiedBy='$user_id',
                ModifiedDate='$CreatedDate'
            WHERE id='$id'";
        $conn->query($sqlBag);
        $bagId = $id;

        // Delete old items before reinserting
        $conn->query("DELETE FROM tbl_bag_items WHERE BagId='$bagId'");
    }

    // ✅ Insert Bag Items (only checked rows)
   if (!empty($_POST['ProductId'])) {
    foreach ($_POST['ProductId'] as $k => $prodId) {
        if (isset($_POST['checked'][$k])) { // checked index matches
            $prodName = $conn->real_escape_string($_POST['ProductName'][$k]);
            $qty      = (int) $_POST['Qty'][$k];

            $sqlItem = "INSERT INTO tbl_bag_items 
                        SET BagId='$bagId',
                            ProdId='$prodId',
                            ProductName='$prodName',
                            Qty='$qty'";
            $conn->query($sqlItem);
        }
    }
}

 

    if ($id == 0) {
        echo "<script>alert('Bag Added Successfully!');window.location.href='view-bags.php';</script>";
    } else {
        echo "<script>alert('Bag Updated Successfully!');window.location.href='view-bags.php';</script>";
    }
}
 ?>

                <div class="card mb-4">
                    <div class="card-body">
                          <form id="validation-form" method="post" enctype="multipart/form-data">
<div class="form-row">

 
 <div class="form-group col-lg-12">
<label class="form-label">Title <span class="text-danger">*</span></label>
<input type="text" name="Title" class="form-control" id="Title" placeholder="" value='<?php echo $row7["ProductName"]; ?>' required>
<div class="clearfix"></div>
</div>



<div class="form-group col-lg-4">
<label class="form-label">Date <span class="text-danger">*</span></label>
<input type="date" name="CreatedDate" class="form-control" id="CreatedDate" placeholder="" value="<?php echo $row7["CreatedDate"]; ?>" required>
<div class="clearfix"></div>
</div>

 <div class="form-group col-lg-4">
<label class="form-label">Product Price </label>
<input type="text" name="Price" class="form-control" id="Price" placeholder="Product Price" value="<?php echo $row7["Price"]; ?>">
<div class="clearfix"></div>
</div>

 <div class="form-group col-md-4">
<label class="form-label">Status <span class="text-danger">*</span></label>
  <select class="form-control" id="Status" name="Status" required="">

<option value="1" <?php if($row7["Status"]=='1') {?> selected <?php } ?>>Active</option>
<option value="0" <?php if($row7["Status"]=='0') {?> selected <?php } ?>>Inctive</option>
</select>
<div class="clearfix"></div>
</div> 
 
 <div class="form-group col-lg-12">
<label class="form-label">Details </label>
<textarea name="Details" class="form-control" id="editor1" placeholder="Details"><?php echo $row7["Details"]; ?></textarea>
<div class="clearfix"></div>
</div>

</div>

<div class="form-row">
  <label class="form-label" style="font-size: 18px;color: #0dc30d;"> Product Details</label>
<table id="example" class="table table-striped table-bordered" width="100%">
     <thead>
    <tr>
        <th><input type="checkbox" id="checkAll"></th>
        <th width="30%">Product</th>
      
        <th>Qty </th>
      
    </tr>
     </thead>
        <tbody id="dynamic_field">
      <?php 
        $i=1;
        $sql12 = "SELECT * FROM tbl_products WHERE Status=1 AND Roll=0";
        $rncnt2 = getRow($sql12);
        $row12 = getList($sql12);
        foreach($row12 as $result){ 
            $sql2 = "SELECT * FROM tbl_bag_items WHERE ProdId='".$result['id']."' AND BagId='$id'";
            $rncnt22 = getRow($sql2);
            $row22 = getRecord($sql2);
            if($rncnt22>0){
                $Qty = $row22['Qty'];
            }
            else{
                $Qty = 0;
            }
        ?>
          <tr>
  <td>
    <input type="checkbox" name="checked[<?php echo $i; ?>]" class="prod-check" <?php if($rncnt22>0){?> checked <?php } ?>>
  </td>
  <td><?php echo $result['ProductName']; ?>
    <input type="hidden" name="ProductId[<?php echo $i; ?>]" value="<?php echo $result['id']; ?>">
    <input type="hidden" name="ProductName[<?php echo $i; ?>]" value='<?php echo $result['ProductName']; ?>'>
  </td>
  <td>
    <input type="number" name="Qty[<?php echo $i; ?>]" class="form-control" value="<?php echo $Qty;?>" min="0">
  </td>
</tr>

      <?php $i++; } ?>
    </tbody>

    
    </table>
</div>
<button type="submit" name="submit" class="btn btn-primary btn-finish">Submit</button>
</form>   

                    </div>
                </div>
                        



					</div>
                    <!-- [ content ] End -->
                    <!-- [ Layout footer ] Start -->
                    
                    <!-- [ Layout footer ] End -->
                </div>
                <!-- [ Layout content ] Start -->
            </div>
            <!-- [ Layout container ] End -->
        </div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core scripts -->
    <script src="<?php echo $SiteUrl;?>/assets/js/pace.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/jquery-3.3.1.min.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/libs/popper/popper.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/bootstrap.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/sidenav.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/layout-helpers.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/material-ripple.js"></script>

    <!-- Libs -->
    <script src="<?php echo $SiteUrl;?>/assets/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    
    <!-- Demo -->
    <script src="<?php echo $SiteUrl;?>/assets/js/demo.js"></script>
    <script src="<?php echo $SiteUrl;?>/assets/js/analytics.js"></script>
     <script src="<?php echo $SiteUrl;?>/assets/js/pages/forms_selects.js"></script>
     
     <script>
      // Toggle all checkboxes
  $("#checkAll").on("change", function() {
    $(".prod-check").prop("checked", $(this).prop("checked"));
  });

  // On form submit: disable unchecked rows’ inputs
  $("form").on("submit", function() {
    $("#example tbody tr").each(function() {
      if (!$(this).find(".prod-check").is(":checked")) {
        $(this).find("input").prop("disabled", true); 
      }
    });
  });
         $(document).ready(function() {
$('#example').DataTable({
    "pageLength":1000,
        "scrollX": true,
        "scrollY": "500px"
    });
         });
     </script>
</body>

</html>
