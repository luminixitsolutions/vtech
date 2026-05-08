<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Leads";
$Page = "Bank-Leads";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | Education Leads List</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta name="description" content="" />
<meta name="keywords" content="">
<meta name="author" content="" />
<link rel="stylesheet" href="pipe/css/style.css">

<?php include_once 'header_script.php'; ?>

<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
</head>
<body>
<style>
/*  .breadcrumbs {*/
/*  border: 1px solid #cbd2d9;*/
/*  border-radius: 0.3rem;*/
/*  display: inline-flex;*/
/*  overflow: hidden;*/
/*}*/

/*.breadcrumbs__item {*/
/*  background: #fff;*/
/*  color: #333;*/
/*  outline: none;*/
/*  padding: 0.75em 0.75em 0.75em 1.25em;*/
/*  position: relative;*/
/*  text-decoration: none;*/
/*  transition: background 0.2s linear;*/
/*}*/

/*.breadcrumbs__item:hover:after,*/
/*.breadcrumbs__item:hover {*/
/*  background: #62d493;*/
/*}*/

/*.breadcrumbs__item:focus:after,*/
/*.breadcrumbs__item:focus,*/
/*.breadcrumbs__item.is-active:focus {*/
/*  background: #323f4a;*/
/*  color: #fff;*/
/*}*/

/*.breadcrumbs__item:after,*/
/*.breadcrumbs__item:before {*/
/*  background: white;*/
/*  bottom: 2px;*/
/*  -webkit-clip-path: polygon(50% 50%, -50% -50%, 0 100%);*/
/*          clip-path: polygon(50% 50%, -50% -50%, 0 100%);*/
/*  content: "";*/
/*  left: 100%;*/
/*  position: absolute;*/
/*  top: 0;*/
/*  transition: background 0.2s linear;*/
/*  width: 1em;*/
/*  z-index: 1;*/
/*}*/

/*.breadcrumbs__item:before {*/
/*  background: #cbd2d9;*/
/*  margin-left: 1px;*/
/*}*/

/*.breadcrumbs__item:last-child {*/
/*  border-right: none;*/
/*}*/

/*.breadcrumbs__item.is-active {*/
/*  background: #62d493;*/
/*}*/


/* Some styles to make the page look a little nicer */

</style>
<body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <!-- Fixed navbar -->
        <?php include_once 'back-header.php'; ?> 
        

        <div class="main-container">

<?php //include_once 'sidebar.php'; ?>




<?php //include_once 'top_header.php'; ?>

<?php 
$id = $_GET['id'];
$sql7 = "SELECT * FROM tbl_users WHERE id='$id'";
$row7 = getRecord($sql7);


  if(isset($_POST['submit'])){
    $CustId = addslashes(trim($_POST["CustId"]));
    $CustName = addslashes(trim($_POST["CustName"]));
$Status = $_POST["Status"];
$Phone = addslashes(trim($_POST["Phone"]));
$Address = addslashes(trim($_POST['Address']));
$EmailId = addslashes(trim($_POST["EmailId"]));
$CallAfter = addslashes(trim($_POST["CallAfter"]));
$NextDate = addslashes(trim($_POST["NextDate"]));
$NextTime = addslashes(trim($_POST["NextTime"]));
$Details = addslashes(trim($_POST["Details"]));
$Diaposition = addslashes(trim($_POST["Diaposition"]));
$CreatedDate = date('Y-m-d');
$CreatedTime = date('h:i a');



      $qx = "INSERT INTO tbl_purchase_feedback SET SellId='0',CustId='$CustId',CustName='$CustName',Phone = '$Phone',Status='1',Address='$Address',CreatedBy='$user_id',NextDate='$NextDate',NextTime='$NextTime',Details='$Details',CreatedDate='$CreatedDate',CreatedTime='$CreatedTime',Diaposition='$Diaposition'";
  $conn->query($qx);
  
echo "<script>alert('Feedback Added Successfully!');window.close();window.opener.location.reload(true);</script>";

  }
 ?>

<!-- [ content ] Start -->
                    <div class="container-fluid flex-grow-1 container-p-y">

<style type="text/css">
    {
    --breadcrumb-theme-11: red;
}
</style>

                         <!-- Header -->
                        <div class="card">
                            <div class="">
                                <div class="row">
        
                                    <div class="col-lg-7">
                                        
                                        
                                        <h4 class="font-weight-bold mb-1"><?php echo $row7['Fname'];?> </h4>
                                       
                                       
                                        

                                     
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Header -->

                        <div class="row">
                            <div class="col">

                                <!-- Info -->
                                <div class="card mb-2">
                                    <div class="card-body">
                                      
                                       <div class="row mb-2">
                                            <div class="col-md-3 text-muted">Phone No.:</div>
                                            <div class="col-md-9">
                                               +91 <?php echo $row7['Phone'];?>
                                            </div>
                                        </div>

                                        
                                        <div class="row mb-2">
                                            <div class="col-md-3 text-muted">Address:</div>
                                            <div class="col-md-9">
                                                <a href="javascript:void(0)" class="text-dark"><?php echo $row7['Address'];?></a>
                                            </div>
                                        </div>

                                       

     <a href="#Conversation" class="btn btn-primary btn-round">+&nbsp; Conversation</a>
    
                                    </div>
                                    
                                </div>
                                
                                     
                                        
                                <!-- / Info -->

                                <!-- Posts -->

 <div class="card mb-4">
                            <h6 class="card-header">Notes</h6>
                            <div class="card-body">
                                <form id="validation-form" method="post" enctype="multipart/form-data">
                                     <input type="hidden" name="CustId" id="CustId" value="<?php echo $_GET['id'];?>">
                                    <input type="hidden" name="CustName" id="CustName" value="<?php echo $row7["Fname"];?>">
                                    <input type="hidden" name="Phone" id="Phone" value="<?php echo $row7["Phone"];?>">
                                 
                                    <input type="hidden" name="Address" id="Address" value="<?php echo $row7["Address"];?>">
<div class="form-row">
                               



<div class="form-group col-lg-12">
<label class="form-label">Conversation <span class="text-danger">*</span></label>
<textarea name="Details" class="form-control" placeholder="Details" required></textarea>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-6 col-lg-6 col-xl-6">
<label class="form-label">Call After Date</label>
<input type="date" name="NextDate" class="form-control" id="NextDate" placeholder="" value="">
<div class="clearfix"></div>
</div>

<div class="form-group col-md-6 col-lg-6 col-xl-6">
<label class="form-label">Time</label>
<input type="text" name="NextTime" class="form-control" id="NextTime" placeholder="" value="">
<div class="clearfix"></div>
</div>

 <div class="form-group col-md-12">
                                            <label class="form-label"> Status </label>
                                            <select class="form-control" name="Diaposition" id="Diaposition">
                                                <option selected="" disabled="">Select  Status</option>
                                                <?php 
                                        $q = "select * from tbl_diapostion WHERE Status=1 ORDER BY SrNo ASC";
                                        $r = $conn->query($q);
                                        while($rw = $r->fetch_assoc())
                                    {
                                ?>
                                                <option <?php if($row7['Diaposition']==$rw['id']){ ?> selected <?php } ?>
                                                    value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>

</div>

                                <button class="btn btn-primary" type="submit" name="submit"
                               >Save</button>
                                </form>
                            </div>
                        </div>


 
                                <div class="card mb-4" id="Conversation">
                                    <div class="card-body">
                                        <div class="row help-desk">
                            <div class="col-xl-12 col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <nav class="navbar justify-content-between p-0 align-items-center shadow-none">
                                            <h5 class="my-2">Conversation</h5>
                                            
                                        </nav>
                                    </div>
                                </div>
                                

     <div class="container-fluid flex-grow-1 container-p-y">
                      
                        
                        <div class="row help-desk">
                            <div class="col-xl-12 col-lg-12">
                                
                            

                          
                                
                                
                                <?php 
$i=2;
$id = $_GET['id'];
  $sql2 = "SELECT tp.*,tu.Fname,tu.Lname FROM tbl_purchase_feedback tp 
          LEFT JOIN tbl_users tu ON tp.CreatedBy=tu.id WHERE tp.CustId='$id'
          ORDER BY tp.id DESC"; 

    $res2 = $conn->query($sql2);
    while($row = $res2->fetch_assoc()){ ?>

                                <div class="ticket-block">
                                    <div class="row">
                                     
                                        <div class="col">
                                            <div class="card example-popover" data-toggle="modal" data-target="#modals-slide" data-toggle="popover" data-placement="right" data-html="true"
                                                title="<img src='assets/img/user/avatar-1.jpg' class='wid-20 rounded mr-1 img-fluid'><p class='d-inline-block mb-0 ml-2'>You replied</p>" data-content="hello Yogen dra,you need to create "
                                                toolbar-options="div only once in a page in your code, this div fill found every 'td' ...">
                                                <div class="row no-gutters row-bordered row-border-light h-100">
                                                    <div class="d-flex col">
                                                        <div class="card-body">
                                                            <h5 class="mb-0"><?php echo $row['Fname']." ".$row['Lname'];?></h5>
                                                            <p class="my-1 text-muted"><i class="feather icon-lock mr-1 f-14"></i>Telecaller</p>
                                                            <ul class="list-inline mt-2 mb-0 hid-sm">
                                                                
                                                                <li class="list-inline-item my-1"><i class="feather icon-calendar mr-1 f-14"></i>Conversation at <?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['CreatedDate']))); ?> <?php echo $row['CreatedTime']; ?></li>
                                                                
                                                            </ul>
                                                            <div class="card bg-light my-3 p-3 hid-md">
                                                                <h6><img src="assets/img/user/avatar-5.jpg" alt="" class="wid-20 avatar mr-2 rounded">Last comment from <a href="#"><?php echo $row['CustName'];?>:</a></h6>
                                                                <p class="mb-0"><?php echo $row['Details']; ?></p>
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                 <?php $i++;} ?>
                                
                                
                            </div>


                            
                            
                        </div>
                    </div>
                    
                                
                                
                                </div>
                                        </div>
                                    </div>
                                    
                                </div>

                                

                            </div>
                            <div class="col-xl-5">

                               

                                

                            </div>
                        </div>

                    </div>
                    <!-- [ content ] End -->

                    <!-- [ Layout footer ] Start -->
                  
                    <!-- [ Layout footer ] End -->

                </div>
                <!-- [ Layout content ] Start -->
                
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

    <!-- Libs -->
    <script src="assets/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <!-- Demo -->
    <script src="assets/js/demo.js"></script><script src="assets/js/analytics.js"></script>


<script type="text/javascript">
 
	$(document).ready(function() {
    $('#example').DataTable({
        "scrollX": true
    });
});
</script>
<script>
       function featured(){
        if($('#VeryInt').prop('checked') == true) {
            $('#VeryInt').val(1);
            $('.ptnlist').show();
        }
        else{
           $('#VeryInt').val(0);
            $('.ptnlist').hide();
            }
        }
$(document).ready(function() {
           $(document).on("change", "#Services", function(event) {
            var val = this.value;
            var action = "getServicesDetails";
            $.ajax({
                url: "ajax_files/ajax_dropdown.php",
                method: "POST",
                data: {
                    action: action,
                    id: val
                },
                success: function(data) {
                    $('#servicedetails').html(data);
                  
                }
            });

        });

           $(document).on("change", "#PartId", function(event) {
            var val = this.value;
            var action = "getBranch";
            $.ajax({
                url: "ajax_files/ajax_dropdown.php",
                method: "POST",
                data: {
                    action: action,
                    id: val
                },
                success: function(data) {
                    $('#BranchId').html(data);
                  
                }
            });

        });

            
            });
            
            function upload_doc(id){
     setTimeout(function() {
        window.open(
            'upload-doc.php?id=' + id, 'stickerPrint2',
            'toolbar=1, scrollbars=1, location=1,statusbar=0, menubar=1, resizable=1, width=800, height=800,left=250,top=50,right=50'
        );
    }, 1);
 
 }
  function editProfile(id){
    setTimeout(function() {
        window.open(
            'edit-bank-profile.php?id=' + id, 'stickerPrint3',
            'toolbar=1, scrollbars=1, location=1,statusbar=0, menubar=1, resizable=1, width=800, height=800,left=250,top=50,right=50'
        );
    }, 1);
 }
</script>
</body>
</html>
