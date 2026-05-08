<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Sell";
$Page = "View-Sell";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">

<head>
    <title><?php echo $Proj_Title; ?> - <?php if($_GET['id']) {?>Edit <?php } else{?> Add <?php } ?> Vendor Payment
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

            


            

                

                <?php 
$id = $_GET['id'];
$sql7 = "SELECT * FROM tbl_sell WHERE id='$id'";
$row7 = getRecord($sql7);
?>

                <div class="layout-content">

                    <div class="container-fluid flex-grow-1 container-p-y">
                        <h4 class="font-weight-bold py-3 mb-0"><?php if($_GET['id']) {?>Edit <?php } else{?> Add
                            <?php } ?> Customer EMI Payment</h4>

                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                <div id="alert_message"></div>
                                <form id="validation-form" method="post" autocomplete="off">
                                    <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" id="userid">
                                    <input type="hidden" name="action" value="SaveVed" id="action">
                                    <div class="form-row">
                                     
<div class="form-group col-md-4">
                                            <label class="form-label">Cell No </label>
                                            <input type="text" name="CellNo" id="CellNo" class="form-control"
                                                placeholder="" value="<?php echo $row7["CellNo"]; ?>"
                                                autocomplete="off" oninput="getUserDetails()" disabled>
                                            <div class="clearfix"></div>
                                        </div>
  <div class="form-group col-md-8">
   <label class="form-label">Customer Name </label>
     <input type="text" name="CustName" id="CustName" class="form-control"
                                                placeholder="" value="<?php echo $row7["CustName"]; ?>"
                                                autocomplete="off" disabled>
    <div class="clearfix"></div>
 </div> 


 <div class="form-group col-md-4">
                                            <label class="form-label">Ref No </label>
                                            <input type="text" name="Gphone" id="Gphone" class="form-control"
                                                placeholder="" value="<?php echo $row7["Gphone"]; ?>"
                                                autocomplete="off" disabled>
                                            <div class="clearfix"></div>
                                        </div>
  <div class="form-group col-md-8">
   <label class="form-label">Ref Name </label>
     <input type="text" name="Gname" id="Gname" class="form-control"
                                                placeholder="" value="<?php echo $row7["Gname"]; ?>"
                                                autocomplete="off" disabled>
    <div class="clearfix"></div>
 </div> 

  <div class="form-group col-md-4">
                                            <label class="form-label">Ref No 2</label>
                                            <input type="text" name="Gphone2" id="Gphone2" class="form-control"
                                                placeholder="" value="<?php echo $row7["Gphone2"]; ?>"
                                                autocomplete="off" disabled>
                                            <div class="clearfix"></div>
                                        </div>
  <div class="form-group col-md-8">
   <label class="form-label">Ref Name 2</label>
     <input type="text" name="Gname2" id="Gname2" class="form-control"
                                                placeholder="" value="<?php echo $row7["Gname2"]; ?>"
                                                autocomplete="off" disabled>
    <div class="clearfix"></div>
 </div>

 <div class="form-group col-md-12">
   <label class="form-label">Invoice No</label>
     <input type="text" name="InvoiceNo" id="InvoiceNo" class="form-control"
                                                placeholder="" value="<?php echo $row7["InvoiceNo"]; ?>"
                                                autocomplete="off" disabled>
    <div class="clearfix"></div>
 </div>


                                     
                                     <div class="form-group col-md-6">
<label class="form-label"> Payment Type<span class="text-danger">*</span></label>
 <select class="form-control" name="PayType" id="PayType" required onchange="checkPayType(this.value)" disabled>
<option selected="" value="" disabled>Select Payment Type</option>
  <option value="Cash" <?php if($row7["PayType"] == 'Cash') {?> selected <?php } ?>>
    By Cash</option>
<option value="EMI" <?php if($row7["PayType"] == 'EMI') {?> selected <?php } ?>>
    By EMI</option>

</select>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-6">
<label class="form-label"> Product Type<span class="text-danger">*</span></label>
 <select class="form-control" name="ProdType" id="ProdType" required onchange="checkProdType(this.value)" disabled>
  <option value="1" <?php if($row7["ProdType"] == 1) {?> selected <?php } ?>>
    Saved Product</option>
<option value="2" <?php if($row7["ProdType"] == 2) {?> selected <?php } ?>>
    Not Saved Product</option>

</select>
<div class="clearfix"></div>
</div>

 <input type="hidden" id="ProductId" name="ProductId" value="<?php echo $row7['ProductId']; ?>">    
<input type="hidden" id="CatId" name="CatId" value="<?php echo $row7['CatId']; ?>">
<input type="hidden" id="BrandId" name="BrandId" value="<?php echo $row7['BrandId']; ?>">   

<div class="form-group col-md-3 svprd" <?php if($row7["ProdType"] == 1) {?> style="display: block;" <?php } else{?> style="display: none;" <?php } ?>>
<label class="form-label">Product No</label>
 <select class="select2-demo form-control" name="ProductNo" id="ProductNo"  onchange="getProdDetails(this.value)" disabled>
<option selected="" value="">Select Vendor</option>
<?php 
  $sql12 = "SELECT * FROM tbl_stocks WHERE ProductNo='".$row7["ProductNo"]."'";
  $row12 = getRecord($sql12);
  ?>
  <option <?php if($row7["ProductNo"] == $row12['ProductNo']) {?> selected <?php } ?> value="<?php echo $row12['ProductNo'];?>">
    <?php echo $row12['ProductNo']; ?></option>
 <?php 
  $sql12 = "SELECT * FROM tbl_stocks WHERE BuyStatus='0'";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($row7["ProductNo"] == $result['ProductNo']) {?> selected <?php } ?> value="<?php echo $result['ProductNo'];?>">
    <?php echo $result['ProductNo']; ?></option>
<?php } ?>
</select>
</div>



 <div class="form-group col-md-3 svprd" <?php if($row7["ProdType"] == 1) {?> style="display: block;" <?php } else{?> style="display: none;" <?php } ?>>
<label class="form-label">Model No</label>
<input type="text" id="ModelNo" name="ModelNo" class="form-control" placeholder="" value="<?php echo $row7['ModelNo']; ?>" autocomplete="off" readonly>
</div>

 <div class="form-group col-md-3 svprd" <?php if($row7["ProdType"] == 1) {?> style="display: block;" <?php } else{?> style="display: none;" <?php } ?>>
<label class="form-label">Category </label>
<input type="text" name="CatName" id="CatName" class="form-control" placeholder="" value="<?php echo $row7['CatName']; ?>" autocomplete="off" readonly>
</div>

 <div class="form-group col-md-3 svprd" <?php if($row7["ProdType"] == 1) {?> style="display: block;" <?php } else{?> style="display: none;" <?php } ?>>
<label class="form-label">Brand </label>
<input type="text" name="BrandName" id="BrandName" class="form-control" placeholder="" value="<?php echo $row7['BrandName']; ?>" autocomplete="off" readonly>
</div>

<div class="form-group col-md-12 nsvprd" <?php if($row7["ProdType"] == 2) {?> style="display: block;" <?php } else{?> style="display: none;" <?php } ?>>
<label class="form-label">Product Name</label>
<input type="text" id="ProductName" name="ProductName" class="form-control" placeholder="" value="<?php echo $row7['ProductName']; ?>" autocomplete="off" disabled>
</div>


   <div class="form-group col-md-12">
   <label class="form-label">Product Details</label>
     <textarea name="ProdDetails" id="ProdDetails" class="form-control" disabled 
                                                ><?php echo $row7['ProdDetails']; ?></textarea>
    <div class="clearfix"></div>
 </div>                                     

                                        <div class="form-group col-md-3">
<label class="form-label">Qty </label>
<input type="number" name="Qty" id="Qty" class="form-control" placeholder="e.g.,1" autocomplete="off" min="1" oninput="getTotal(document.getElementById('Qty').value,document.getElementById('Price').value,document.getElementById('DownPayment').value,document.getElementById('Total').value,document.getElementById('ProcFees').value)" value="<?php echo $row7['Qty']; ?>" disabled>
</div>



<div class="form-group col-md-3">
<label class="form-label">Price </label>
<input type="text" name="Price" id="Price" class="form-control" placeholder="e.g.,150" value="<?php echo $row7['Price']; ?>" autocomplete="off" oninput="getTotal(document.getElementById('Qty').value,document.getElementById('Price').value,document.getElementById('DownPayment').value,document.getElementById('Total').value,document.getElementById('ProcFees').value)" disabled>
</div>

<div class="form-group col-md-3">
<label class="form-label">Processing Fees </label>
<input type="text" name="ProcFees" id="ProcFees" class="form-control" placeholder="e.g.,150" value="<?php echo $row7['ProcFees']; ?>" autocomplete="off" oninput="getTotal(document.getElementById('Qty').value,document.getElementById('Price').value,document.getElementById('DownPayment').value,document.getElementById('Total').value,document.getElementById('ProcFees').value)" disabled>
</div>

<div class="form-group col-md-3">
<label class="form-label">Total </label>
<input type="text" name="Total" id="Total" class="form-control" placeholder="e.g.,150" value="<?php echo $row7['Total']; ?>" autocomplete="off" disabled>
</div>

<div class="form-group col-md-4">
<label class="form-label">Down Payment </label>
<input type="text" name="DownPayment" id="DownPayment" class="form-control" placeholder="e.g.,150" value="<?php echo $row7['DownPayment']; ?>" autocomplete="off" oninput="getTotal(document.getElementById('Qty').value,document.getElementById('Price').value,document.getElementById('DownPayment').value,document.getElementById('Total').value,document.getElementById('ProcFees').value)" disabled>
</div>

<div class="form-group col-md-4">
<label class="form-label">Balance </label>
<input type="text" name="Balance" id="Balance" class="form-control" placeholder="e.g.,150" value="<?php echo $row7['Balance']; ?>" autocomplete="off" disabled>
</div>

<div class="form-group col-md-4">
<label class="form-label">EMI Month </label>
<input type="number" name="EmiMonth" id="EmiMonth" class="form-control" placeholder="e.g.,150" value="<?php echo $row7['EmiMonth']; ?>" autocomplete="off" min="1" oninput="calEmi()" disabled>
</div>
                                        
                                        
                                       
                                       


                                       

                                    </div>
                                    <!-- <button id="growl-default" class="btn btn-default">Default</button> -->
                                  <!--   <button type="submit" class="btn btn-primary btn-finish" id="submit">Save</button> -->
                                </form>
                            </div>

<div class="col-lg-6">
    <button type="button" class="btn btn-primary" class="btn btn-primary btn-finish" data-toggle="modal" data-target="#modals-slide" >Add Receipt</button>
    <h4 style="text-align: center;">EMI Details</h4>
    <table class="table table-striped table-bordered">
         <thead>
            <tr>
              <th>Date</th>
              <th>EMI</th>
              <th>Cheque No</th>
              <!-- <th>Pay</th> -->
            </tr>
        </thead>
        <tbody>
            <?php $sql = "SELECT * FROM tbl_emi WHERE SellId='$id' ORDER BY EmiDate ASC";
            $row = getList($sql);
            foreach($row as $result){
                $TotalEmi+=$result['EmiAmt'];
                $date = date('Y-m-d', strtotime('+'.$i.' month', strtotime($InvoiceDate)));
                if($result['PayStatus'] == 1){
                    $Bgcolor = "#adffad";
                }
                else if($result['PayStatus'] == 2){
                    $Bgcolor = "#abaaaa";
                }
                else{
                    $Bgcolor = "";
                }
                ?>
            <tr style="background-color:<?php echo $Bgcolor;?>">
                <td><input type="date" name="EmiDate[]" id="EmiDate<?php echo $i;?>" class="form-control" value="<?php echo $result['EmiDate'];?>" readonly></td>
                <td><input type="text" name="EmiAmt[]" id="EmiAmt<?php echo $i;?>" class="form-control txt" value="<?php echo $result['EmiAmt'];?>" oninput="checkEmiAmt(<?php echo $i;?>)" readonly></td>
                <td><input type="text" name="EmiChequeNo[]" id="EmiChequeNo<?php echo $i;?>" class="form-control" value="<?php echo $result['EmiChequeNo'];?>" readonly></td>
                <!-- <td>
                    <?php  if($result['PayStatus'] == 1){?>
                        <button type="button" class="btn btn-success" onclick="payAmt(<?php echo $result['id'];?>)" class="btn btn-success btn-finish" style="padding: 0.5px 1rem">Paid</button>

                    <?php } else{?>
                 <button type="button" class="btn btn-primary" onclick="payAmt(<?php echo $result['id'];?>)" class="btn btn-primary btn-finish" style="padding: 0.5px 1rem">Pay</button>
             <?php } ?>
             </td> -->
            </tr>
        <?php } ?>
        
        <tr>
                <td>Total</td>
                <td class="TotalEmiAmt"><?php echo round($TotalEmi);?></td>
                <td></td>
            </tr>
          
        </tbody>
    </table>


    <h4 style="text-align: center;">EMI Paid</h4>
    <table class="table table-striped table-bordered">
         <thead>
            <tr>
                <th>Receipt No</th>
              <th>Date</th>
              <th>Amount</th>
          
            </tr>
        </thead>
        <tbody>
            <?php $sql = "SELECT * FROM tbl_paid_emi WHERE InvId='$id' ORDER BY ReceiptDate ASC";
            $row = getList($sql);
            foreach($row as $result){
                
                ?>
            <tr>
                <td><?php echo $result['ReceiptNo']; ?></td>
                <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$result['ReceiptDate']))); ?></td>
                <td><?php echo $result['ReceiptAmt']; ?></td>
               
            </tr>
        <?php } ?>
        
      
        </tbody>
    </table>



                                <!-- Modal template -->
                                <div class="modal modal-slide fade" id="modals-slide">
                                    <div class="modal-dialog">
                                        <form class="modal-content">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">×</button>
                                            <div class="modal-body">
                                                <div class="form-row">
                                                    <input type="hidden" name="InvId" value="<?php echo $_GET['id']; ?>" id="InvId">
                                                    <input type="hidden" name="EmiId" value="" id="EmiId">
                                                <div class="form-group col-md-12">
   <label class="form-label">Receipt No</label>
     <input type="text" name="ReceiptNo" id="ReceiptNo" class="form-control"
                                                placeholder="" value=""
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div>

 <div class="form-group col-md-12">
   <label class="form-label">Date</label>
     <input type="date" name="ReceiptDate" id="ReceiptDate" class="form-control"
                                                placeholder="" value=""
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div>

  <div class="form-group col-md-12">
   <label class="form-label">Amount</label>
     <input type="text" name="ReceiptAmt" id="ReceiptAmt" class="form-control"
                                                placeholder="" value=""
                                                autocomplete="off">
    <div class="clearfix"></div>
 </div>

  <div class="form-group col-md-12">
   <label class="form-label">Payment Mode</label>
    <select name="PayMode" id="PayMode" class="form-control">
            <option value="Cash">Cash</option>
            <option value="Card Payment">Card Payment</option>
            <option value="Mobile Payment">Mobile Payment</option>
    </select>
    <div class="clearfix"></div>
 </div>

 <div class="form-group col-md-12">
   <label class="form-label">Narration</label>
     <textarea name="Narration" id="Narration" class="form-control"
                                                ></textarea>
    <div class="clearfix"></div>
 </div>


  </div>
                                                <button type="button" class="btn btn-primary btn-block" onclick="saveReceipt()">Save</button>
                                                <button type="button" class="btn btn-default btn-block" data-dismiss="modal">Cancel</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>

</div>


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
       <?php include_once 'footer_script.php'; ?>

    <script type="text/javascript">
        function saveReceipt(){
            var InvId = $('#InvId').val();
            var ReceiptNo = $('#ReceiptNo').val();
            var ReceiptDate = $('#ReceiptDate').val();
            var ReceiptAmt = $('#ReceiptAmt').val();
            var PayMode = $('#PayMode').val();
            var Narration = $('#Narration').val();
            var EmiId = $('#EmiId').val();
            var action = "saveReceipt";
            $.ajax({
                url: "ajax_files/ajax_sell.php",
                method: "POST",
                data: {
                    action: action,
                    ReceiptNo: ReceiptNo,
                    ReceiptDate:ReceiptDate,
                    ReceiptAmt:ReceiptAmt,
                    PayMode:PayMode,
                    Narration:Narration,
                    InvId:InvId,
                    EmiId:EmiId
                },
                success: function(data) {
                   alert("Payment Successfully");
                   window.location.href='pay-emi.php?id='+InvId;
                }
            });
        }
        function payAmt(id){
            var action = "getEmiPayDetails";
            $.ajax({
                url: "ajax_files/ajax_sell.php",
                method: "POST",
                data: {
                    action: action,
                    id: id
                },
                success: function(data) {
                    var res = JSON.parse(data);
                   $('#ReceiptAmt').val(res.EmiAmt);
                   $('#ReceiptDate').val(res.EmiDate);
                   $('#ReceiptNo').val(res.ReceiptNo);
                   $('#PayMode').val(res.PayMode);
                   $('#Narration').val(res.Narration);
                   $('#EmiId').val(id);
                    $('#modals-slide').modal('show'); 
                }
            });
            
        }
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
                                window.location.href = 'view-vendor-payments.php';
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