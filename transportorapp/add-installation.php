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
function getImage($roll,$UserId){
    global $conn;
     $sql11 = "SELECT * FROM tbl_crop_image WHERE UserId='$UserId' AND SrNo='$roll'";
    $res11 = $conn->query($sql11);
       $row11 = $res11->fetch_assoc();
       $Photo = $row11['Image'];
    //   $OrgImage = $row11['OrgImage'];
    //   return json_encode(array('Photo'=>$Photo,'OrgImage'=>$OrgImage));
      return $Photo;
}
$id = $_GET['id'];
$sql = "SELECT * FROM tbl_users WHERE id='$id'";
$row7 = getRecord($sql); 
$CellNo = $row7['Phone'];
$CustName = $row7['Fname'];
$Address = $row7['Address'];
$ProjectType = $row7['ProjectType'];
if($ProjectType == 1){
    $Type=2;
}
else{
   $Type=1; 
}
if(isset($_POST['submit'])){
    $CustId = $_POST['CustId'];
    $CreatedDate = date('Y-m-d');
$CreatedTime = date('h:i a');
$InstallationDate = addslashes(trim($_POST['InstallationDate']));
$Lattitude = addslashes(trim($_POST['Lattitude']));
$Longitude = addslashes(trim($_POST['Longitude']));
$Money = addslashes(trim($_POST['Money']));
$Specify = addslashes(trim($_POST['Specify']));

/*$randno = rand(1,100);
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

$randno2 = rand(1,100);
$src2 = $_FILES['Photo2']['tmp_name'];
$fnm2 = substr($_FILES["Photo2"]["name"], 0,strrpos($_FILES["Photo2"]["name"],'.')); 
$fnm2 = str_replace(" ","_",$fnm2);
$ext2 = substr($_FILES["Photo2"]["name"],strpos($_FILES["Photo2"]["name"],"."));
$dest2 = '../uploads/'. $randno2 . "_".$fnm2 . $ext2;
$imagepath2 =  $randno2 . "_".$fnm2 . $ext2;
if(move_uploaded_file($src2, $dest2))
{
$Photo2 = $imagepath2 ;
} 
else{
    $Photo2 = $_POST['OldPhoto2'];
}*/

$Photo = getImage(1,$user_id);
$Photo2 = getImage(2,$user_id);

$sql = "UPDATE tbl_users SET InstPhoto1='$Photo',InstPhoto2='$Photo2',InstallationDate='$InstallationDate',InstMoney='$Money',InstSpecify='$Specify',InstalledBy='$user_id',InstLattitude='$Lattitude',InstLongitude='$Longitude',InstOtpVerify=0 WHERE id='$id'";
$conn->query($sql);

 $sql = "INSERT INTO tbl_installations SET CustId='$id',CellNo='$CellNo',CustName='$CustName',Address='$Address',Lattitude='$Lattitude',Longitude='$Longitude',InstStatus='Installation',Status=1,CreatedBy='$user_id',CreatedDate='$CreatedDate',Type='$Type'";
    $conn->query($sql);
    
  $sql = "DELETE FROM tbl_crop_image WHERE UserId='$user_id'";
       $conn->query($sql);
$otp = rand(1000,9999);
$_SESSION['otp'] = $otp;
echo "<script>window.location.href='installation-otp-verify.php?id=$id&phone=$CellNo';</script>";
}
?>


        <div class="main-container">

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">Installation

</h4>

<div class="card">

<div class="card-body">
<div id="alert_message"></div>
<form id="validation-form" method="post" enctype="multipart/form-data">
<div class="form-row">

   

 <div class="form-group col-md-3 Pump">
                                            <label class="form-label">Customer </label>
                                            <select class="form-control" id="CustId" name="CustId">
<!-- <option selected="" disabled="" value="">Select Customer</option> -->
  <?php 
        $q = "SELECT tu.* FROM tbl_users tu WHERE tu.id='$id' ORDER BY tu.Fname";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option  selected value="<?php echo $rw['id']; ?>"><?php echo $rw['Fname']; ?></option>
              <?php } ?>
</select>
                                           
                                            <div class="clearfix"></div>
                                        </div>   

       
<div class="form-group col-md-4">
                                            <label class="form-label">Installation Date <span
                                                    class="text-danger">*</span></label>
                                            <input type="date" name="InstallationDate" id="InstallationDate" class="form-control"
                                                placeholder="" value="<?php echo $row7['InstallationDate']; ?>"
                                                autocomplete="off" required>
                                            <div class="clearfix"></div>
                                        </div> 
        
        <div class="form-group col-md-12">
                                            <label class="form-label">System Installed Photo <span
                                                    class="text-danger">*</span>  <a href="javascript:void(0)" data-toggle="modal" data-target="#myModal">click here for process upload image</a></label>
                                                    <main>
    <div class="slim" data-service="example/async.php?Roll=1" data-did-remove="handleImageRemoval">
        
        <input type="file" name="slim[]" id="Photo" name="car3_logo" class="input_css"/>
      
    </div>
</main>
                                            
                                        </div>
                                        
                                        <div class="modal" id="myModal">
  <div class="modal-dialog">
    <div class="modal-content">

    
      <!-- Modal body -->
      <div class="modal-body">
       <img src="image_instruction.jpg">
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>

                                         <div class="form-group col-md-12">
                                            <label class="form-label">Water Output Photo <span
                                                    class="text-danger">*</span> <a href="javascript:void(0)" data-toggle="modal" data-target="#myModal">click here for process upload image</a></label>
                                                    <main>
    <div class="slim" data-service="example/async.php?Roll=2" data-did-remove="handleImageRemoval">
        
        <input type="file" name="slim[]" id="Photo" name="car3_logo" class="input_css"/>
      
    </div>
</main>
                                            
                                        </div>


 <div class="form-group col-md-3 Pump">
                                            <label class="form-label">Any Money taken From Customer </label>
                                            <select class="form-control" id="Money" name="Money">
<option <?php if($row7['InstMoney']=='No'){ ?> selected <?php } ?> value="No">No</option>
 <option <?php if($row7['InstMoney']=='Yes'){ ?> selected <?php } ?> value="Yes">Yes</option>
            
</select>
                                           
                                            <div class="clearfix"></div>
                                        </div>  

                                        <div class="form-group col-md-4">
                                            <label class="form-label">If Yes, Specify </label>
                                            <textarea name="Specify" id="Specify" class="form-control"
                                                placeholder=""
                                                autocomplete="off"><?php echo $row7['InstSpecify']; ?></textarea>
                                            <div class="clearfix"></div>
                                        </div> 

                                        <div class="form-group col-md-3">
<label class="form-label">Lattitude </label>
<input type="text" name="Lattitude" id="Lattitude" class="form-control" placeholder="" value="<?php echo $Latitude; ?>" readonly>
 <div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Longitude </label>
<input type="text" name="Longitude" id="Longitude" class="form-control" placeholder="" value="<?php echo $Longitude; ?>" readonly>
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

<script>
    // load this code when the document has loaded

    document.addEventListener('DOMContentLoaded', function() {

        // get a reference to the remove button

        var button = document.querySelector('#remove-button');

        // listen to clicks on the remove button

        button.addEventListener('click', function() {

            // get the element with id 'my-cropper'

            var element = document.querySelector('#my-cropper');

            // find the cropper attached to the element

            var cropper = Slim.find(element);

            // call the remove method on the cropper

            cropper.remove();

        });

    });

    </script>

  <script>

    function handleImageRemoval(data) {

        // can't continue without server file name

        if (!data.server) { return; }

        // setup request and send

        var name = data.server.file;

        var url = 'example/async-remove.php';

        var xhr = new XMLHttpRequest();

        xhr.open('GET', url + (url.indexOf('?')===-1?'?':':') + 'name=' + name, true);

        xhr.send();

    }

    </script>
<script src="example/js/slim.kickstart.min.js"></script>
</body>
</html>
