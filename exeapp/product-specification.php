<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage="Product-Specification";
$Page = "Product-Specification";

?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> - Product Specification</title>
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
if(isset($_POST['submit'])){
    $AcDc = $_POST['AcDc']; 
    $Surface = $_POST['Surface'];
    $PumpCapacity = $_POST['PumpCapacity'];
    $WaterSource = $_POST['WaterSource'];
    $BoreDia = $_POST['BoreDia'];
    $PumpHead = $_POST['PumpHead'];
   $CreatedDate = date('Y-m-d');

$sql = "DELETE FROM tbl_product_specification WHERE AcDc='$AcDc' AND Surface='$Surface' AND PumpCapacity='$PumpCapacity' AND WaterSource='$WaterSource' AND BoreDia='$BoreDia' AND PumpHead='$PumpHead'";
$conn->query($sql);
    $number = count($_POST["ProdId"]);
if($number > 0)  
      {  
        for($i=0; $i<$number; $i++)  
          {  
            if(trim($_POST["ProdId"][$i] != ''))  
              {
                 $ProdId = $_POST['ProdId'][$i];
                $ProdName = mysqli_real_escape_string($conn, $_POST['ProdName'][$i]);
                $Unit = $_POST['Unit'][$i];
                $Qty = $_POST['Qty'][$i];
              
                $sql = "INSERT INTO tbl_product_specification SET AcDc='$AcDc',Surface='$Surface',PumpCapacity='$PumpCapacity',WaterSource='$WaterSource',BoreDia='$BoreDia',PumpHead='$PumpHead',ProdId='$ProdId',ProdName='$ProdName',Unit='$Unit',Qty='$Qty',CreatedBy='$user_id',CreatedDate='$CreatedDate'";
                $conn->query($sql);
            }
        }
    }
}

?>
<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Product Specification</h4>

<form id="validation-form" method="post" enctype="multipart/form-data">
<div class="card mb-4">
<div class="card-body">
<div id="alert_message"></div>

<div class="form-row">

 <div class="form-group col-md-4">
                                            <label class="form-label">AC/DC </label>
                                            <select class="form-control" id="AcDc" name="AcDc" onchange="getProdList(document.getElementById('AcDc').value,document.getElementById('Surface').value,document.getElementById('PumpCapacity').value,document.getElementById('WaterSource').value,document.getElementById('BoreDia').value,document.getElementById('PumpHead').value)">
                                               
                                                <option value="AC" <?php if($row7["AcDc"]=='AC') {?> selected
                                                    <?php } ?>>AC</option>
                                                <option value="DC" <?php if($row7["AcDc"]=='DC') {?> selected
                                                    <?php } ?>>DC</option>
                                            </select>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-4 Pump">
                                            <label class="form-label">Type Of Pump </label>

                                            <select class="form-control" id="Surface" name="Surface" onchange="getProdList(document.getElementById('AcDc').value,document.getElementById('Surface').value,document.getElementById('PumpCapacity').value,document.getElementById('WaterSource').value,document.getElementById('BoreDia').value,document.getElementById('PumpHead').value)">
<option selected="" disabled="" value="">Select Type Of Pump</option>
  <?php 
        $q = "select * from tbl_common_master WHERE Status='1' AND Roll=4 ORDER BY Name ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($row7['Surface']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>

                                            
                                            <div class="clearfix"></div>
                                        </div>
                                       
                                        <div class="form-group col-md-4 Pump">
                                            <label class="form-label">Capacity </label>

                                            <select class="form-control" id="PumpCapacity" name="PumpCapacity" onchange="getProdList(document.getElementById('AcDc').value,document.getElementById('Surface').value,document.getElementById('PumpCapacity').value,document.getElementById('WaterSource').value,document.getElementById('BoreDia').value,document.getElementById('PumpHead').value)">
<option selected="" disabled="" value="">Select Pump Capacity</option>
  <?php 
        $q = "select * from tbl_common_master WHERE Status='1' AND Roll=2 ORDER BY Name ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($row7['PumpCapacity']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>

                                            
                                            <div class="clearfix"></div>
                                        </div>


                                        <div class="form-group col-md-4 Pump">
                                            <label class="form-label">Type Of Source </label>
                                            <select class="form-control" id="WaterSource" name="WaterSource" onchange="getProdList(document.getElementById('AcDc').value,document.getElementById('Surface').value,document.getElementById('PumpCapacity').value,document.getElementById('WaterSource').value,document.getElementById('BoreDia').value,document.getElementById('PumpHead').value)">
<option selected="" disabled="" value="">Select Type Of Source</option>
  <?php 
        $q = "select * from tbl_common_master WHERE Status='1' AND Roll=3 ORDER BY Name ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($row7['WaterSource']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>
                                           
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="form-group col-md-4 Pump">
                                            <label class="form-label">Bore Diameter </label>
                                            <select class="form-control" id="BoreDia" name="BoreDia" onchange="getProdList(document.getElementById('AcDc').value,document.getElementById('Surface').value,document.getElementById('PumpCapacity').value,document.getElementById('WaterSource').value,document.getElementById('BoreDia').value,document.getElementById('PumpHead').value)">
<option selected="" disabled="" value="">Select Bore Diameter</option>
  <?php 
        $q = "select * from tbl_common_master WHERE Status='1' AND Roll=7 ORDER BY id ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($row7['BoreDia']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>
                                           
                                            <div class="clearfix"></div>
                                        </div>


                                        <div class="form-group col-md-4 Pump">
                                            <label class="form-label">Pump Head </label>
                                            <select class="form-control" id="PumpHead" name="PumpHead" onchange="getProdList(document.getElementById('AcDc').value,document.getElementById('Surface').value,document.getElementById('PumpCapacity').value,document.getElementById('WaterSource').value,document.getElementById('BoreDia').value,document.getElementById('PumpHead').value)">
<option selected="" disabled="" value="">Select Pump Head</option>
  <?php 
        $q = "select * from tbl_common_master WHERE Status='1' AND Roll=1 ORDER BY Name ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($row7['PumpHead']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>
                                           
                                            <div class="clearfix"></div>
                                        </div>

</div>


</div>
</div>

<div class="card">
<div class="card-datatable table-responsive" id="custresult">

</div>

</div>

</form>
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
    function getProdList(acdc,Surface,PumpCapacity,WaterSource,BoreDia,PumpHead){
  var action = 'view';
      $.ajax({
  type: "POST",
  url: "ajax_files/ajax_product_specification.php",
   data:{action:action,acdc:acdc,Surface:Surface,PumpCapacity:PumpCapacity,WaterSource:WaterSource,BoreDia:BoreDia,PumpHead:PumpHead},  
  success: function(data){
      $('#custresult').html(data);
  }
  });
    }
</script>
</body>
</html>