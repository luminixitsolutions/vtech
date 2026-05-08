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
        

        <div class="main-container" style="background-color: #f1f1f1;">



<?php 
if(isset($_POST['submit'])){
    
$StoreInchId = $_POST['StoreInchId'];
$CreatedDate = $_POST['CreatedDate'];
$BranchId = $_POST['BranchId'];
$StoreExeId = $_POST['DispatchOfficerId'];
$Narration = addslashes(trim($_POST['Narration']));
$Rncnt = $_POST['Rncnt'];
$Rncnt2 = $_POST['Rncnt2'];


$sql = "INSERT INTO tbl_rooftop_distibute_items2 SET StoreExeId='$StoreExeId',BranchId='$BranchId',StoreInchId='$StoreInchId',CreatedDate='$CreatedDate',Narration='$Narration'";
$conn->query($sql);
$SellId = mysqli_insert_id($conn);

if($Rncnt > 0){
$number = count($_POST["ProductId"]);
if($number > 0)  
      {  
        for($i=0; $i<$number; $i++)  
          {  
            if(trim($_POST["ProductId"][$i] != ''))  
              {
                $ProductName = addslashes(trim($_POST['ProductName'][$i]));
                $Purity = addslashes(trim($_POST['Purity'][$i]));
                $Weight = addslashes(trim($_POST['Weight'][$i]));
                $Price = addslashes(trim($_POST['Price'][$i]));
                $Making = addslashes(trim($_POST['Making'][$i]));
                $HmCharge = addslashes(trim($_POST['HmCharge'][$i]));
                $Qty = addslashes(trim($_POST['Qty'][$i]));
                $TotalRate = addslashes(trim($_POST['TotalRate'][$i]));
                $ProductId = addslashes(trim($_POST['ProductId'][$i]));
                $ModelNo = addslashes(trim($_POST['ModelNo'][$i]));
                $SerialNo = addslashes(trim($_POST['SerialNo'][$i]));
                $ProdType = addslashes(trim($_POST['ProdType'][$i]));
                if($Qty > 0){
                $sql22 = "INSERT INTO tbl_rooftop_distibute_item_details2 SET BranchId='$BranchId',StoreExeId='$StoreExeId',DistId='$SellId',StoreInchId='$StoreInchId',
                ProductName='$ProductName',Purity='$Purity',Qty='$Qty',ProductId='$ProductId',ModelNo='$ModelNo',CreatedDate='$CreatedDate',
                SerialNo='$SerialNo'";
                $conn->query($sql22);

            }
              }  

          }
      }
    }

$CustId = implode(",",$_POST['ProcedureId']);
   $array =  explode(",", $CustId);
   foreach ($array as $item) {
    $StockId = $item;
    $sql = "SELECT * FROM tbl_rooftop_distibute_item_details WHERE id='$StockId'";
                $row = getRecord($sql);
                $ProductId = $row['ProductId'];
                $ProductName = $row['ProductName'];
                $Purity = $row['Unit'];
                $SerialNo = $row['SerialNo'];
                $ModelNo = $row['ModelNo'];
                
                $sql22 = "INSERT INTO tbl_rooftop_distibute_item_details2 SET BranchId='$BranchId',StoreExeId='$StoreExeId',DistId='$SellId',StoreInchId='$StoreInchId',
                ProductName='$ProductName',Purity='$Purity',Qty='1',ProductId='$ProductId',ModelNo='$ModelNo',CreatedDate='$CreatedDate',
                SerialNo='$SerialNo',ProdType=1";
                $conn->query($sql22);
   }
    

echo "<script>window.location.href='view-distribute-rooftop-item-store-executive.php';</script>";
}
?>


<div class="container">
  <br>
   <h4>Distribute Item To Dispatch Officier</h4>
  <form id="validation-form" method="post" autocomplete="off">
                            <div class="card-body" style="padding: 0px;">
                                <div class="tab-content">
                                    <div class="tab-pane fade active show" id="tabhome125" role="tabpanel" aria-labelledby="tabhome125-tab">
                                        <h5 style="font-size: 18px;color: #0dc30d;"><strong>Product Details</strong></h5>
                                         <?php 
                $i=1;
             $sql12 = "SELECT ts.*,tp.ProductName AS Product_Name,tp.ModelNo AS Model_No,tp.Unit FROM tbl_rooftop_distibute_item_details ts INNER JOIN tbl_rooftop_products tp ON ts.ProductId=tp.id WHERE ts.ProdType=0 AND ts.BranchId='$RoofBranchId' GROUP BY ts.ProductId ORDER BY ts.ProductId";
             $rncnt2 = getRow($sql12);
             $row12 = getList($sql12);
  foreach($row12 as $result){
    $sql11 = "SELECT SUM(Qty) AS CrQty FROM tbl_rooftop_distibute_item_details WHERE ProductId='".$result['ProductId']."' AND ProdType=0";
    $row11 = getRecord($sql11);
    $CrQty = $row11['CrQty'];

    $sql12 = "SELECT SUM(Qty) AS DrQty FROM tbl_rooftop_distibute_item_details2 WHERE ProductId='".$result['ProductId']."' AND ProdType=0";
    $row12 = getRecord($sql12);
    $DrQty = $row12['DrQty'];

    $BalQty = $CrQty - $DrQty;
    if($BalQty > 0){
            ?>
<div class="card mb-4" id="bgcolor<?php echo $row['id'];?>">

                    <div class="card-body">
                         <div class="row">
                                    <div class="col">
                    <input type="hidden" name="Rncnt" id="Rncnt" value="<?php echo $rncnt2;?>">
                    <input type="hidden" name="ProductId[]" id="ProductId" value="<?php echo $result['ProductId'];?>">
                    <input type="hidden" name="ProdType[]" id="ProdType1" value='0'>
                    <input type="hidden" name="ProductName[]" id="ProductName1" value='<?php echo $result['Product_Name'];?>'>
                    <input type="hidden" name="SerialNo[]" id="SerialNo1" value='<?php echo $result['SerialNo'];?>'>
                    <input type="hidden" name="ModelNo[]" id="ModelNo1" value="<?php echo $result['Model_No'];?>">                 
                        <h6 style="margin-bottom: 1px;font-size: 15px;"><?php echo $result['Product_Name']; ?></h6>
                        <p style="margin-bottom: 1px;"><strong>Stock Qty :</strong> <?php echo $BalQty;?> </p>  
                        <p style="margin-bottom: 1px;"><strong>Qty : </strong> <input type="number" name="Qty[]" id="Qty1"  placeholder="e.g.,1" value="0" autocomplete="off" min="0" style="width: 80px;"> </p>      
                        <p style="margin-bottom: 1px;"><strong>Unit :</strong> <?php echo $result['Unit'];?> </p>
                       
                     </div>
                                    
                                </div>
                    </div>
                </div>
                <?php } $i++;} ?>


                 <h5 style="font-size: 18px;color: #0dc30d;"><strong>Serial No Products</strong></h5>

    <?php 
        $sql22 = "SELECT * FROM tbl_rooftop_distibute_item_details WHERE ProdType='1' AND SerialNo!='' AND BranchId='$RoofBranchId'";
        $rncnt22 = getRow($sql22);
    ?>
<input type="hidden" name="Rncnt2" id="Rncnt2" value="<?php echo $rncnt22;?>">

<?php $row22 = getList($sql22);
    foreach($row22 as $result){
    $sql33 = "SELECT * FROM tbl_rooftop_distibute_item_details2 WHERE ProdType=1 AND SerialNo='".$result['SerialNo']."'";
    $rncnt33 = getRow($sql33);
    if($rncnt33 > 0){}
    else{?>
<div class="card mb-4" id="bgcolor<?php echo $row['id'];?>">

                    <div class="card-body">
                        <h6 style="margin-bottom: 1px;font-size: 15px;"><input type="checkbox" id="Check_Id<?php echo $result['id']; ?>" value="<?php echo $result['id']; ?>" onclick="featured(<?php echo $result['id']; ?>)" class="common_selector Proccedures">&nbsp;&nbsp;&nbsp;<?php echo $result['ProductName']; ?></h6>
                        <p style="margin-bottom: 1px;"><strong>Serial No :</strong> <?php echo $result['SerialNo'];?> </p>  
                        <input type="hidden" value="0" name="CheckId[]" id="CheckId<?php echo $result['id']; ?>">
                        <input type="hidden" value="<?php echo $result['id']; ?>" name="CustId[]">

                        <input type="hidden" name="SerialProd[]" value="<?php echo $result['id'];?>">     
                        <input type="hidden" value="0" name="CheckId[]" id="CheckId<?php echo $result['id']; ?>">   
                        
                        
                        
                       
                   
                     
                   
                                                       
                    </div>
                </div>
           <?php $i++;} } ?>

           <input type="hidden" id="ProcId" name="ProcedureId[]" value="">
        <input type="hidden" name="BranchId" id="BranchId" value="<?php echo $RoofBranchId;?>">
        <input type="hidden" name="StoreInchId" id="StoreInchId" value="<?php echo $user_id;?>">
                 <div class="form-group col-lg-4">
<label class="form-label"> Dispatch Officer<span class="text-danger">*</span></label>
 <select class="select2-demo form-control" name="DispatchOfficerId" id="DispatchOfficerId" required>
<option selected="" value="">Select</option>
<?php 
  $sql12 = "SELECT * FROM tbl_users WHERE Status='1' AND Roll=26";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option value="<?php echo $result['id'];?>">
    <?php echo $result['Fname']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

<div class="form-group col-lg-2">
<label class="form-label">Date <span class="text-danger">*</span></label>
<input type="date" name="CreatedDate" id="CreatedDate" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
<div class="clearfix"></div>
</div>
<br>
 <div class="form-group col-lg-4">
<button type="submit" name="submit" class="btn btn-primary btn-finish">Submit</button>
</div>


                                    </div>
                                
                                   
                                </div>
                            </div>
</form>
   
               
                </div>

 
<br><br>




<?php include_once 'footer.php'; ?>

</div>

</main>
<br><br>
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
function featured(id){
        if($('#Check_Id'+id).prop('checked') == true) {
            $('#CheckId'+id).val(1);
            $('#bgcolor'+id).css('background-color','turquoise');
            clickChk();
        }
        else{
           $('#CheckId'+id).val(0);
           $('#bgcolor'+id).css('background-color','');
           clickChk();
            }
        }


function get_filter(class_name)
    {
        var filter = [];
        $('.'+class_name+':checked').each(function(){
            filter.push($(this).val());
        });
        return filter;
    }
function clickChk(){

        var ProccedureId = get_filter('Proccedures');
         if(ProccedureId == ''){
              $('#ProcId').val(0);
         }
         else{
        $('#ProcId').val(ProccedureId);
         }
    }

    $(document).ready(function() {
    $('#example').DataTable({
       "scrollX": true,
         paging: false,
    ordering: false,
    info: false,
    });
});
</script>
</body>
</html>
