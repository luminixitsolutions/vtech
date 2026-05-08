<?php
session_start();
include_once '../config.php';
include_once '../auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Products";
$Page = "Add-Products";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">

<head>
  <title><?php echo $Proj_Title; ?></title>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
  <meta name="description" content="" />
  <meta name="keywords" content="">
  <meta name="author" content="Codedthemes" />
  <link rel="icon" type="image/x-icon" href="<?php echo $SiteUrl; ?>/assets/img/favicon.ico">

  <!-- Google fonts -->
  <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
  <!-- Icon fonts -->
  <link rel="stylesheet" href="<?php echo $SiteUrl; ?>/assets/fonts/fontawesome.css">
  <link rel="stylesheet" href="<?php echo $SiteUrl; ?>/assets/fonts/ionicons.css">
  <link rel="stylesheet" href="<?php echo $SiteUrl; ?>/assets/fonts/linearicons.css">
  <link rel="stylesheet" href="<?php echo $SiteUrl; ?>/assets/fonts/open-iconic.css">
  <link rel="stylesheet" href="<?php echo $SiteUrl; ?>/assets/fonts/pe-icon-7-stroke.css">
  <link rel="stylesheet" href="<?php echo $SiteUrl; ?>/assets/fonts/feather.css">

  <!-- Core stylesheets -->
  <link rel="stylesheet" href="<?php echo $SiteUrl; ?>/assets/css/bootstrap-material.css">
  <link rel="stylesheet" href="<?php echo $SiteUrl; ?>/assets/css/shreerang-material.css">
  <link rel="stylesheet" href="<?php echo $SiteUrl; ?>/assets/css/uikit.css">

  <!-- Libs -->
  <link rel="stylesheet" href="<?php echo $SiteUrl; ?>/assets/libs/perfect-scrollbar/perfect-scrollbar.css">
  <link rel="stylesheet" href="<?php echo $SiteUrl; ?>/assets/libs/flot/flot.css">

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
            <h5 class="font-weight-bold py-3 mb-0">Add Rate Calculation For Quotation</h5>

            <?php
            $id = $_GET['id'];
            $sql7 = "SELECT * FROM tbl_rooftop_rate_calculation WHERE id='$id'";
            $row7 = getRecord($sql7);

            if (isset($_POST['submit'])) {
              $BrandId = addslashes(trim($_POST["BrandId"]));
              $CatId = addslashes(trim($_POST["CatId"]));
              $ProductName = addslashes(trim($_POST["ProductName"]));
              $Status = $_POST["Status"];
              $Capacity = addslashes(trim($_POST["Capacity"]));
              $MakeModule = addslashes(trim($_POST['MakeModule']));
              $Qty = addslashes(trim($_POST["Qty"]));
              $Rate = addslashes(trim($_POST["Rate"]));
              $CostOfItem = addslashes(trim($_POST["CostOfItem"]));

              function RandomStringGenerator($n)
              {
                $generated_string = "";
                $domain = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
                $len = strlen($domain);
                for ($i = 0; $i < $n; $i++) {
                  $index = rand(0, $len - 1);
                  $generated_string = $generated_string . $domain[$index];
                }
                return $generated_string;
              }
              $n = 10;
              $Code = RandomStringGenerator($n);

              if ($_GET['id'] == '') {
                $qx = "INSERT INTO tbl_rooftop_rate_calculation SET BrandId='$BrandId',CatId='$CatId',ProductName='$ProductName',Capacity='$Capacity',MakeModule='$MakeModule',Qty='$Qty',Rate='$Rate',CostOfItem = '$CostOfItem',Status='1',CreatedDate='$CreatedDate',CreatedBy='$user_id',Code='$Code'";
                $conn->query($qx);
                echo "<script>alert('Rate Calculation Added Successfully!');window.location.href='view-qtn-calculation.php';</script>";
              } else {

                $query2 = "UPDATE tbl_rooftop_rate_calculation SET BrandId='$BrandId',CatId='$CatId',ProductName='$ProductName',Capacity='$Capacity',MakeModule='$MakeModule',Qty='$Qty',Rate='$Rate',CostOfItem = '$CostOfItem',Status='1',ModifiedDate='$ModifiedDate',ModifiedBy='$user_id',Code='$Code' WHERE id = '$id'";
                $conn->query($query2);
                echo "<script>alert('Rate Calculation Updated Successfully!');window.location.href='view-qtn-calculation.php';</script>";
              }
              //header('Location:courses.php'); 

            }
            ?>

            <div class="card mb-4">
              <div class="card-body">
                <form id="validation-form" method="post" enctype="multipart/form-data">
                  <div class="form-row">

                    <div class="form-group col-md-6">
                      <label class="form-label"> Brand<span class="text-danger">*</span></label>
                      <select class="select2-demo form-control" name="BrandId" id="BrandId" required>
                        <option selected="" value="">Select Brand</option>
                        <?php
                        $sql12 = "SELECT * FROM tbl_brand WHERE Status='1'";
                        $row12 = getList($sql12);
                        foreach ($row12 as $result) {
                        ?>
                          <option <?php if ($row7["BrandId"] == $result['id']) { ?> selected <?php } ?> value="<?php echo $result['id']; ?>">
                            <?php echo $result['Name']; ?></option>
                        <?php } ?>
                      </select>
                      <div class="clearfix"></div>
                    </div>

                    <div class="form-group col-md-6">
                      <label class="form-label"> Category<span class="text-danger">*</span></label>
                      <select class="select2-demo form-control" name="CatId" id="CatId" required>
                        <option selected="" value="">Select Category</option>
                        <?php
                        $sql12 = "SELECT * FROM tbl_qtn_category WHERE Status='1'";
                        $row12 = getList($sql12);
                        foreach ($row12 as $result) {
                        ?>
                          <option <?php if ($row7["CatId"] == $result['id']) { ?> selected <?php } ?> value="<?php echo $result['id']; ?>">
                            <?php echo $result['Name']; ?></option>
                        <?php } ?>
                      </select>
                      <div class="clearfix"></div>
                    </div>

                    <div class="form-group col-lg-12">
                      <label class="form-label">Product Name <span class="text-danger">*</span></label>
                      <input type="text" name="ProductName" class="form-control" id="ProductName" placeholder="Product Name" value='<?php echo $row7["ProductName"]; ?>' required>
                      <div class="clearfix"></div>
                    </div>


                    <div class="form-group col-lg-2">
                      <label class="form-label">Capacity <span class="text-danger">*</span></label>
                      <input type="text" name="Capacity" class="form-control" id="Capacity" placeholder="" value="<?php echo $row7["Capacity"]; ?>" required>
                      <div class="clearfix"></div>
                    </div>

                    <div class="form-group col-lg-3">
                      <label class="form-label">Make Of Module </label>
                      <input type="text" name="MakeModule" class="form-control" id="MakeModule" placeholder="" value="<?php echo $row7["MakeModule"]; ?>">
                      <div class="clearfix"></div>
                    </div>

                    <div class="form-group col-lg-2">
                      <label class="form-label">Qty </label>
                      <input type="text" name="Qty" class="form-control" id="Qty" placeholder="" value="1" readonly>
                      <div class="clearfix"></div>
                    </div>

                    <div class="form-group col-lg-2">
                      <label class="form-label">Rate <span class="text-danger">*</span></label>
                      <input type="text" name="Rate" class="form-control" id="Rate" placeholder="" value="<?php echo $row7["Rate"]; ?>" required oninput="calTotCost()">
                      <div class="clearfix"></div>
                    </div>


                    <div class="form-group col-lg-3">
                      <label class="form-label">Cost Of Item <span class="text-danger">*</span></label>
                      <input type="text" name="CostOfItem" class="form-control" id="CostOfItem" placeholder="" value="<?php echo $row7["CostOfItem"]; ?>" readonly>
                      <div class="clearfix"></div>
                    </div>

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
  <script src="<?php echo $SiteUrl; ?>/assets/js/pace.js"></script>
  <script src="<?php echo $SiteUrl; ?>/assets/js/jquery-3.3.1.min.js"></script>
  <script src="<?php echo $SiteUrl; ?>/assets/libs/popper/popper.js"></script>
  <script src="<?php echo $SiteUrl; ?>/assets/js/bootstrap.js"></script>
  <script src="<?php echo $SiteUrl; ?>/assets/js/sidenav.js"></script>
  <script src="<?php echo $SiteUrl; ?>/assets/js/layout-helpers.js"></script>
  <script src="<?php echo $SiteUrl; ?>/assets/js/material-ripple.js"></script>

  <!-- Libs -->
  <script src="<?php echo $SiteUrl; ?>/assets/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

  <!-- Demo -->
  <script src="<?php echo $SiteUrl; ?>/assets/js/demo.js"></script>
  <script src="<?php echo $SiteUrl; ?>/assets/js/analytics.js"></script>
  <script src="<?php echo $SiteUrl; ?>/assets/js/pages/forms_selects.js"></script>

  <script>
    function calTotCost() {
      var Rate = $('#Rate').val();
      var CostOfItem = Number(Rate) * 1;
      $('#CostOfItem').val(CostOfItem);
    }
  </script>
</body>

</html>