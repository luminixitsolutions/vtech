<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Sell";
$Page = "View-Customer-Payment";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">

<head>
    <title><?php echo $Proj_Title; ?> - <?php if($_GET['id']) {?>Edit <?php } else{?> Add <?php } ?> Customer Payment
    </title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="" />

    <?php include_once 'header_script.php'; ?>
</head>

<body>
    <style type="text/css">
    .password-tog-info {
        display: inline-block;
        cursor: pointer;
        font-size: 12px;
        font-weight: 600;
        position: absolute;
        right: 50px;
        top: 30px;
        text-transform: uppercase;
        z-index: 2;
    }
    </style>
<body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <!-- Fixed navbar -->
        <?php include_once 'back-header.php'; ?> 
        

        <div class="main-container">








                <?php 
$id = $_GET['id'];
$sql7 = "SELECT * FROM tbl_general_ledger WHERE id='$id'";
$row7 = getRecord($sql7);
?>

                <div class="layout-content">

                    <div class="container-fluid flex-grow-1 container-p-y">
                        <h4 class="font-weight-bold py-3 mb-0"><?php if($_GET['id']) {?>Edit <?php } else{?> Add
                            <?php } ?> Customer Payment</h4>

                        <div class="card mb-4">
                            <div class="card-body">
                                <div id="alert_message"></div>
                                <form id="validation-form" method="post" autocomplete="off">
                                    <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" id="userid">
                                    <input type="hidden" name="action" value="SaveVed" id="action">
                                    <div class="form-row">
                                      <div class="form-group col-md-12">
<label class="form-label">Customer <span class="text-danger">*</span></label>
<select class="select2-demo form-control" id="UserId" name="UserId" <?php if($_GET['id'] != ''){?> disabled <?php } ?> required>
   <option value="" selected>...</option>
    <?php 
     $sql1 = "SELECT * FROM tbl_users WHERE Status=1 AND Roll=5";
     $row1 = getList($sql1);
     foreach($row1 as $result)
      {
      ?>
    <option <?php if($result['id'] == $row7['UserId']) {?> selected <?php } ?>  value="<?php echo $result['id']; ?>"><?php echo $result['Fname']." ".$result['Lname']." (".$result['Phone'].")"; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-12">
<label class="form-label">Invoices <span class="text-danger">*</span></label>
<select class="select2-demo form-control" id="InvoiceNo" name="InvoiceNo" required <?php if($_GET['id'] != ''){?> disabled <?php } ?>>
   <option value="" selected>...</option>
    <?php 
     $sql1 = "SELECT * FROM tbl_sell WHERE CustId='".$row7['UserId']."'";
     $row1 = getList($sql1);
     foreach($row1 as $result)
      {
      ?>
    <option <?php if($result['InvoiceNo'] == $row7['InvoiceNo']) {?> selected <?php } ?>  value="<?php echo $result['InvoiceNo']; ?>"><?php echo $result['InvoiceNo']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>
                                       <?php if($_GET['id'] == ''){?>

                                         <div class="form-group col-md-2">
                                            <label class="form-label">Total Amount <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="TotAmt" id="TotAmt" class="form-control"
                                                placeholder="" value="<?php echo $row7["TotAmt"]; ?>"
                                                autocomplete="off" required readonly>
                                        </div>

                                         <div class="form-group col-md-3">
                                            <label class="form-label">Total Paid Amount <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="TotPaidAmt" id="TotPaidAmt" class="form-control"
                                                placeholder="" value="<?php echo $row7["TotPaidAmt"]; ?>"
                                                autocomplete="off" min="1" readonly >
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label class="form-label">Balance Amount <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="TotBalAmt" id="TotBalAmt" class="form-control"
                                                placeholder="" value="<?php echo $row7["TotBalAmt"]; ?>"
                                                autocomplete="off" min="1" readonly>
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label class="form-label">Paid Amount <span
                                                    class="text-danger">*</span></label>
                                            <input type="number" name="PaidAmt" id="PaidAmt" class="form-control"
                                                placeholder="" value="<?php echo $row7["PaidAmt"]; ?>"
                                                autocomplete="off" min="1" required oninput="getTotAmt(document.getElementById('TotBalAmt').value,document.getElementById('PaidAmt').value)">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label class="form-label">Balance Amount <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" name="BalAmt" id="BalAmt" class="form-control"
                                                placeholder="" value="<?php echo $row7["BalAmt"]; ?>"
                                                autocomplete="off" min="1" readonly>
                                        </div>

                                    <?php } else{?>
 <div class="form-group col-md-12">
                                            <label class="form-label">Paid Amount <span
                                                    class="text-danger">*</span></label>
                                            <input type="number" name="PaidAmt" id="PaidAmt" class="form-control"
                                                placeholder="" value="<?php echo $row7["PaidAmt"]; ?>"
                                                autocomplete="off" min="1" required oninput="getTotAmt(document.getElementById('TotBalAmt').value,document.getElementById('PaidAmt').value)">
                                        </div>

                                    <?php } ?>
                                        
                                        
                                       
                                       


                                       

                                    </div>
                                    <!-- <button id="growl-default" class="btn btn-default">Default</button> -->
                                    <button type="submit" class="btn btn-primary btn-finish" id="submit">Save</button>
                                </form>
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
       <?php include_once 'footer_script.php'; ?>

    <script type="text/javascript">
    function myFunction2() {

        var x = document.getElementById("Password");
        if (x.type === "password") {
            x.type = "text";
            $('.show2').html('<i class="fa fa-eye-slash" aria-hidden="true"></i>');
        } else {
            x.type = "password";
            $('.show2').html('<i class="fa fa-eye" aria-hidden="true"></i>');
        }
    }

    function error_toast() {
        var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
        $.growl.error({
            title: 'Error',
            message: 'Email Id / Phone No Already Exists',
            location: isRtl ? 'tl' : 'tr'
        });
    }

    function success_toast() {
        var isRtl = $('body').attr('dir') === 'rtl' || $('html').attr('dir') === 'rtl';
        $.growl.success({
            title: 'Success',
            message: 'Saved Successfully...',
            location: isRtl ? 'tl' : 'tr'
        });
    }

    function getTotAmt(TotBalAmt,PaidAmt){
        var Amount = Number(TotBalAmt)  - Number(PaidAmt);
        $('#BalAmt').val(parseFloat(Amount).toFixed(2));
    }
    $(document).ready(function() {
        //$(document).on("click", ".btn-finish", function(event){
        $('#validation-form').on('submit', function(e) {
            e.preventDefault();
            if ($('#validation-form').valid()) {

                $.ajax({
                    url: "ajax_files/ajax_general_ledger.php",
                    method: "POST",
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('#submit').attr('disabled', 'disabled');
                        $('#submit').text('Please Wait...');
                    },
                    success: function(data) {

                        if (data == 0) {
                            error_toast();

                        } else {
                            success_toast();
                            setTimeout(function() {
                                window.location.href = 'view-customer-payments.php';
                            }, 2000);
                        }
                        $('#submit').attr('disabled', false);
                        $('#submit').text('Save');
                    }
                })



            } else {
                //$('#Fname').focus();
                return false;
            }
        });

        
       
       $(document).on("change", "#UserId", function(event) {
            var val = this.value;
            var action = "getInvoice";
            $.ajax({
                url: "ajax_files/ajax_general_ledger.php",
                method: "POST",
                data: {
                    action: action,
                    id: val
                },
                success: function(data) {
                    $('#InvoiceNo').html(data);
                  
                }
            });

        });

       $(document).on("change", "#InvoiceNo", function(event) {
            var val = this.value;
            var action = "getInvoiceDetails";
            $.ajax({
                url: "ajax_files/ajax_general_ledger.php",
                method: "POST",
                data: {
                    action: action,
                    id: val
                },
                success: function(data) {
                    console.log(data);
                   var res = JSON.parse(data);
                   var TotAmt = res.TotAmt;
                   var TotPaidAmt = res.TotPaidAmt;
                   var TotBalAmt = res.TotBalAmt;
                   $('#TotAmt').val(TotAmt);
                   $('#TotPaidAmt').val(TotPaidAmt);
                   $('#TotBalAmt').val(TotBalAmt);
                   $('#PaidAmt').focus();
                  
                }
            });

        });

       
       
    });
    </script>
</body>

</html>