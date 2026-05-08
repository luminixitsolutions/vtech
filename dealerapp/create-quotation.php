<?php
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['User']['id'];
$MainPage = "Customers";
$Page = "View-Customers";
//print_r($_SESSION["cart_item"]);echo "<pre>";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">

<head>
    <title><?php echo $Proj_Title; ?> | View Customer Account List</title>
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
</head>

<body>

    <body class="body-scroll d-flex flex-column h-100 menu-overlay">



        <!-- Begin page content -->
        <main class="flex-shrink-0 main">
            <!-- Fixed navbar -->
            <?php include_once 'back-header.php'; ?>


            <?php
            if (isset($_POST['submit'])) {
                $CustId = $_POST['CustId'];
                $CreatedDate = date('Y-m-d H:i:s');
                $CustName = addslashes(trim($_POST['CustName']));
                $PhoneNo = addslashes(trim($_POST['PhoneNo']));
                $Address = addslashes(trim($_POST['Address']));
                $QtnDate = addslashes(trim($_POST['QtnDate']));
                $Remark = addslashes(trim($_POST['Remark']));
                $sql = "INSERT INTO tbl_dealer_rooftop_quotations SET DealerId='$user_id',CustName='$CustName',PhoneNo='$PhoneNo',Address='$Address',
                QtnDate='$QtnDate',Remark='$Remark',Status='1',CreatedBy='$user_id',CreatedDate='$CreatedDate'";
                $conn->query($sql);
                $QtnId = mysqli_insert_id($conn);
                foreach ($_SESSION["cart_item"] as $product) {
                    $ProdId = $product['id'];
                    $Qty = addslashes(trim($product['Qty']));
                    $ProductName = addslashes(trim($product['ProductName']));
                    $Capacity = addslashes(trim($product['Capacity']));
                    $MakeModule = addslashes(trim($product['MakeModule']));
                    $Rate = addslashes(trim($product['Rate']));
                    $CostOfItem = addslashes(trim($product['CostOfItem']));
                    $sql = "INSERT INTO tbl_dealer_rooftop_quotations_items SET DealerId='$user_id',QtnId='$QtnId',
                    ProdId='$ProdId',Qty='$Qty',ProductName='$ProductName',Capacity='$Capacity',
                    MakeModule='$MakeModule',Rate='$Rate',CostOfItem='$CostOfItem',CreatedDate='$CreatedDate'";
                    $conn->query($sql);
                }
                unset($_SESSION["cart_item"]);
                echo "<script>window.location.href='view-quotations.php';</script>";
            }
            ?>


            <div class="main-container">

                <div class="layout-content">

                    <div class="container-fluid flex-grow-1 container-p-y">
                        <h4 class="font-weight-bold py-3 mb-0">Create Rooftop Quotation

                        </h4>

                        <div class="card">

                            <div class="card-body">
                                <div id="alert_message"></div>
                                <form id="validation-form" method="post" enctype="multipart/form-data">
                                    <div class="form-row">


                                        <div class="form-group col-lg-12">
                                            <label class="form-label">Customer Name <span class="text-danger">*</span></label>
                                            <input type="text" name="CustName" class="form-control" id="CustName" placeholder="" value='' required>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-lg-12">
                                            <label class="form-label">Phone No <span class="text-danger">*</span></label>
                                            <input type="text" name="PhoneNo" class="form-control" id="PhoneNo" placeholder="" value='' required>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-lg-12">
                                            <label class="form-label">Address <span class="text-danger">*</span></label>
                                            <input type="text" name="Address" class="form-control" id="Address" placeholder="" value='' required>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label class="form-label"> Product<span class="text-danger">*</span></label>
                                            <select class="select2-demo form-control" name="ProdId" id="ProdId" onchange="getProdDetails(this.value)">
                                                <option selected="" value="">Select Product</option>
                                                <?php
                                                $sql12 = "SELECT * FROM tbl_rooftop_rate_calculation WHERE Status='1'";
                                                $row12 = getList($sql12);
                                                foreach ($row12 as $result) {
                                                ?>
                                                    <option <?php if ($row7["ProdId"] == $result['id']) { ?> selected <?php } ?> value="<?php echo $result['id']; ?>">
                                                        <?php echo $result['ProductName']; ?></option>
                                                <?php } ?>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>



                                        <div class="form-group col-lg-2 col-6">
                                            <label class="form-label">Capacity <span class="text-danger">*</span></label>
                                            <input type="text" name="Capacity" class="form-control" id="Capacity" placeholder="" value="" readonly>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-lg-3 col-6">
                                            <label class="form-label">Make Of Module </label>
                                            <input type="text" name="MakeModule" class="form-control" id="MakeModule" placeholder="" value="" readonly>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-lg-2 col-6">
                                            <label class="form-label">Qty </label>
                                            <input type="text" name="Qty" class="form-control" id="Qty" placeholder="" value="" oninput="calTotCost()">
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-lg-2 col-6">
                                            <label class="form-label">Rate <span class="text-danger">*</span></label>
                                            <input type="text" name="Rate" class="form-control" id="Rate" placeholder="" value="" readonly>
                                            <div class="clearfix"></div>
                                        </div>


                                        <div class="form-group col-lg-3 col-6">
                                            <label class="form-label">Cost Of Item <span class="text-danger">*</span></label>
                                            <input type="text" name="CostOfItem" class="form-control" id="CostOfItem" placeholder="" value="" readonly>
                                            <div class="clearfix"></div>
                                        </div>

                                        <input type="hidden" name="code" id="code" class="form-control" placeholder="" value="" autocomplete="off" readonly>
                                        <div class="form-group col-md-2 col-6" style="padding-top: 33px;">
                                            <button type="button" id="add" class="btn btn-success btn-finish" onclick="addToCart()">Add</button>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-lg-12" id="showcart">

                                        </div>
                                    </div>



                                    <div class="form-row">
                                        <div class="form-group col-md-3 col-6">
                                            <label class="form-label">Total Qty </label>
                                            <input type="text" name="TotQty" id="TotQty" class="form-control" placeholder="" value="" readonly>
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                        <div class="form-group col-md-3 col-6">
                                            <label class="form-label">Total Amount </label>
                                            <input type="text" name="TotAmt" id="TotAmt" class="form-control" placeholder="" value="" readonly>
                                            <div class="clearfix"></div>
                                        </div>
                                        
                                        <div class="form-group col-md-3">
                                            <label class="form-label">Quotation Date </label>
                                            <input type="date" name="QtnDate" id="QtnDate" class="form-control" placeholder="" value="" required>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Remark </label>
                                            <textarea name="Remark" id="Remark" class="form-control" placeholder=""></textarea>
                                            <div class="clearfix"></div>
                                        </div>

                                    </div>


                                    <br>
                                    <button type="submit" name="submit" class="btn btn-primary btn-finish">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <br><br>

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
        <?php include_once 'footer_script.php'; ?>
        <script>
            function calTotCost() {
                var Qty = $('#Qty').val();
                var Rate = $('#Rate').val();
                var CostOfItem = Number(Rate) * Number(Qty);
                $('#CostOfItem').val(CostOfItem);
            }

            function getProdDetails(id) {
                var action = "getProdDetails";
                $.ajax({
                    url: "ajax_files/ajax_product.php",
                    method: "POST",
                    data: {
                        action: action,
                        id: id
                    },
                    dataType: "json",
                    success: function(data) {
                        $('#Capacity').val(data.Capacity);
                        $('#MakeModule').val(data.MakeModule);
                        $('#Rate').val(data.Rate);
                        $('#code').val(data.Code);
                    }
                });
            }

            function addToCart() {
                var action = "addToCart";
                var code = $('#code').val();
                var ProdId = $('#ProdId').val();
                var Capacity = $('#Capacity').val();
                var MakeModule = $('#MakeModule').val();
                var quantity = $('#Qty').val();
                var Rate = $('#Rate').val();
                var CostOfItem = $('#CostOfItem').val();

                if (ProdId == '') {
                    alert("Please Select Product");
                } else if (quantity == '') {
                    alert("Please Enter Qty");
                } else {
                    $.ajax({
                        url: "ajax_files/ajax_product.php",
                        method: "POST",
                        data: {
                            action: action,
                            code: code,
                            quantity: quantity, // Ensure consistency with PHP
                            id: ProdId,
                            Capacity: Capacity,
                            MakeModule: MakeModule,
                            Rate: Rate,
                            CostOfItem: CostOfItem
                        },
                        beforeSend: function() {
                            $('#add').text('Please Wait...').prop('disabled', true);
                        },
                        success: function(data) {
                            console.log(data);
                            displayCart();
                            $('#add').prop('disabled', false).text('Add');

                            // Reset form fields
                            $('#code, #Capacity, #MakeModule, #Qty, #Rate, #CostOfItem').val('');
                            $('#ProdId').val(0).trigger("change"); // Properly reset dropdown
                        }
                    });
                }
            }

            function displayCart() {
                var action = "displayCart";
                $.ajax({
                    url: "ajax_files/ajax_product.php",
                    type: "POST",
                    data: {
                        action: action
                    },
                    success: function(data) {
                        console.log(data);
                        $('#showcart').html(data);
                        calTotalQty();
                    },

                });
            }


 function calTotalQty(){
      var action = "calTotalQty";
            $.ajax({
                url: "ajax_files/ajax_product.php",
                type: "POST",
                data: {
                    action: action
                },
                success: function(data) {
                    console.log(data);
                    var res = JSON.parse(data);
                    $('#TotQty').val(res.TotQty);
                    $('#TotAmt').val(res.TotAmt)
                },

            });
 }
 
            function delete_prod(code) {
                if (confirm("Are you sure you want to delete Record?")) {
                    var action = "delete_cart_prod";
                    $.ajax({
                        url: "ajax_files/ajax_product.php",
                        type: "POST",
                        data: {
                            action: action,
                            code: code
                        },
                        success: function(data) {
                            console.log(data);
                            displayCart();

                        },

                    });
                }
            }
        </script>

    </body>

</html>