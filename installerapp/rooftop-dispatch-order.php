<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Customers";
$Page = "View-Customers";
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
$id = $_GET['id'];
$sql = "SELECT * FROM tbl_rooftop_sell WHERE id='$id'";
$row7 = getRecord($sql);
$CellNo = $row7['CellNo'];
if(isset($_POST['submit'])){
    $CustId = $_POST['CustId'];
    $CreatedDate = date('Y-m-d');
$CreatedTime = date('h:i a');
$InvoiceDate = addslashes(trim($_POST['InvoiceDate']));

$randno = rand(1,100);
$src = $_FILES['Photo']['tmp_name'];
$fnm = substr($_FILES["Photo"]["name"], 0,strrpos($_FILES["Photo"]["name"],'.')); 
$fnm = str_replace(" ","_",$fnm);
$ext = substr($_FILES["Photo"]["name"],strpos($_FILES["Photo"]["name"],"."));
$dest = '../uploads/'. $randno . "_".$fnm . $ext;
$imagepath =  $randno . "_".$fnm . $ext;
if(move_uploaded_file($src, $dest))
{
$Photo = $imagepath ;
} 
else{
    $Photo = $_POST['OldPhoto'];
}


$sql = "UPDATE tbl_rooftop_sell SET Photo='$Photo',Inst_Dispatcher_By='$user_id',Inst_Dispatcher_Date='$CreatedDate' WHERE id='$id'";
$conn->query($sql);

$rncnt = $_POST['Rncnt'];
if($rncnt > 0){
      $number = count($_POST['CheckId']);
    if($number > 0)  
      {  
        for($i=0; $i<$number; $i++)  
          {  
            if(trim($_POST["CheckId"][$i] != ''))  
              {
                $CheckId = addslashes(trim($_POST['CheckId'][$i]));
               
                $SellPrdId = addslashes(trim($_POST['SellPrdId'][$i]));
                
                 if($CheckId == 1){
                $sql22 = "UPDATE tbl_rooftop_sell_products SET Dispatch='1',DispatchBy='$user_id',DispatchDate='$CreatedDate' WHERE id='$SellPrdId'";
                $conn->query($sql22);

                }
              }
            }
        }
}
$otp = rand(1000,9999);
$_SESSION['otp'] = $otp;
echo "<script>window.location.href='rooftop-dispatch-otp-verify.php?id=$id&phone=$CellNo';</script>";
}
?>


        <div class="main-container">

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Dispatch Order

</h4>

<div class="card">

<div class="card-body">
<div id="alert_message"></div>
<form id="validation-form" method="post" enctype="multipart/form-data">
<div class="form-row">

   

        
        <div class="form-group col-md-12">
                                            <label class="form-label">Photo <span
                                                    class="text-danger">*</span></label>
                                            <label class="custom-file">
                                                <input type="file" class="custom-file-input" name="Photo"
                                                    style="opacity: 1;">
                                                <input type="hidden" name="OldPhoto"
                                                    value="<?php echo $row7['Photo'];?>" id="OldPhoto">
                                                <span class="custom-file-label"></span>
                                            </label>
                                            <?php if($row7['Photo']=='') {} else{?>
                                            <span id="show_photo">
                                                <div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a
                                                        href="javascript:void(0)"
                                                        class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white"
                                                        id="delete_photo"></a><img
                                                        src="../uploads/<?php echo $row7['Photo'];?>" alt=""
                                                        class="img-fluid ticket-file-img"
                                                        style="width: 64px;height: 64px;"></div>
                                            </span>
                                            <?php } ?>
                                        </div>
                                        
                                         <div class="form-group col-md-4">
                                            <label class="form-label">Barcode No </label>
                                            <div class="input-group">
                                            <input type="text" name="BarcodeNo" id="BarcodeNo" class="form-control"
                                                placeholder="" value=""
                                                autocomplete="off" oninput="checkBarcodeNo()">
                                                <div class="input-group-append">
                                  <button class="btn btn-primary" type="button" onclick="scanQrCode(1)"><i class="fas fa-barcode"></i></button>
                                             </div>
                                        </div>
                                            <div class="clearfix"></div>
                                        </div>
                                        
<input type="hidden" value="<?php echo $_GET['id']; ?>" name="SellId" id="SellId">
</div>

<div class="form-row">
  <label class="form-label" style="font-size: 18px;color: #0dc30d;"> Dispatch Products</label><br>
<table id="example" class="table table-striped table-bordered" width="100%">
     <thead>
    <tr>
        <th>#</th>
        <th width="30%">Product</th>
      
        <th>Qty </th>
       
    </tr>
     </thead>
     <tbody id="dynamic_field" >
   <?php 
$i=1;
$id = $_GET['id'];
  $sql12 = "SELECT * FROM tbl_rooftop_sell_products WHERE SellId='$id' AND Qty>0";
  $rncnt2 = getRow($sql12);
  $row12 = getList($sql12);
  foreach($row12 as $result12){
    if($result12['SerialNo'] == 'N/A'){
        $SerialNo = "";
    }
    else{
        $SerialNo = " (".$result12['SerialNo'].")";
    }

    if($result12['Dispatch'] == 1){
                     $bgcolor = "background-color: #b9efb9;";
                }
                else{
                    $bgcolor = "";
                }
     ?>
     <tr style="<?php echo $bgcolor;?>">
        <td><?php if($result12['Dispatch'] == 1){} else{?>
            <label class="switcher switcher-info">
                                        <input type="checkbox" class="switcher-input" id="Check_Id<?php echo $result12['id']; ?>" value="0"  onclick="featured(<?php echo $result12['id']; ?>)"  >
                                        <span class="switcher-indicator">
                                            <span class="switcher-yes">
                                                <span class="ion ion-md-checkmark"></span>
                                            </span>
                                            <span class="switcher-no">
                                                <span class="ion ion-md-close"></span>
                                            </span>
                                        </span>
                                        <span class="switcher-label">&nbsp;</span>
                                    </label><?php } ?></td>
  <input type="hidden" value="1" name="CheckId[]" id="CheckId<?php echo $result12['id']; ?>">
   <input type="hidden" name="SellPrdId[]" id="SellPrdId" value="<?php echo $result12['id'];?>">
        <td><?php echo $result12['ProductName']."".$SerialNo;?></td>
        <input type="hidden" name="ProductId[]" id="ProductId" value="<?php echo $result12['ProductId'];?>">
<input type="hidden" name="Rncnt" id="Rncnt" value="<?php echo $rncnt2;?>">

 <input type="hidden" name="ProdType[]" id="ProdType1" value='0'>
       <input type="hidden" name="ProductName[]" id="ProductName1" value='<?php echo $result12['ProductName'];?>'>
  <input type="hidden" name="SerialNo[]" id="SerialNo<?php echo $result12['id']; ?>" value='<?php echo $SerialNo;?>'>
 <input type="hidden" name="ModelNo[]" id="ModelNo1" value="<?php echo $result12['Model_No'];?>">

<td><input type="number" name="Qty[]" id="Qty1" class="form-control" placeholder="e.g.,1" value="<?php echo $result12['Qty'];?>" autocomplete="off" min="0" style="width: 100px;" readonly></td>
     


     </tr>
           <?php $i++;} ?>
     </tbody>

    
    </table>
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
function scanQrCode(id){
    Android.scanQrCode(id);
}
          
function getBarcodeValue(value,id){
        $('#BarcodeNo').val(value);
             checkBarcodeNo();
          }
    function featured(id){
        if($('#Check_Id'+id).prop('checked') == true) {
            $('#CheckId'+id).val(1);
        }
        else{
           $('#CheckId'+id).val(0);
            }
        }


    function getItemLists(id){
        window.location.href="dispatch-order.php?CustId="+id;
    }
    
     function checkBarcodeNo(){
        var BarcodeNo = $('#BarcodeNo').val();
        var SellId = $('#SellId').val();
        var action = "checkBarcodeNo";
            $.ajax({
                url: "ajax_files/ajax_dropdown.php",
                method: "POST",
                data: {
                    action: action,
                    BarcodeNo: BarcodeNo,
                    SellId:SellId
                },
                success: function(data) {
                   console.log(data);
                   var res = JSON.parse(data);
                   var Status = res.Status;
                   var id = res.id;
                   if(Status == 1){
                      $('#Check_Id'+id).prop("checked", true);
                      $('#CheckId'+id).val(1);
                      $('#BarcodeNo').val('');
                   }
                   else{

                   }
                }
            });
    }

    $(document).ready(function() {
    $('#example').DataTable({
       "scrollX": true,
         paging: false,
    ordering: false,
    info: false,
    searching: false,
    });
});
</script>
</body>
</html>
