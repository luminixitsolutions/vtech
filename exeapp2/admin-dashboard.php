<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$MainPage="Dashboard";
$Page = "Dashboard";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">

<head>
    <title><?php echo $Proj_Title; ?> - Dashboard</title>
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
    .mr_5 {
        margin-right: 3rem !important;
    }
    </style>
    <body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">

            


            

                


                <div class="layout-content">
                    <div class="container-fluid flex-grow-1 container-p-y">
                        <div class="text-muted small mt-0 mb-4 d-block breadcrumb">
                        </div>


                        <div class="row">
                             
                             <div class="col-xl-3 col-md-6">
                                <a href="view-all-leads.php" style="color:#000;" href="dashboard.php"><div class="card mb-4">
                                    <div class="progress bg-white">
                                        <div class="progress-bar" style="width:100%;background-color:#00c600;;"></div>
                                    </div>
                                    <div class="card-body card text-center order-visitor-card bg-pattern-2-dark mb-4" align="center">
                                        <h5>Total Leads</h5>
                                        <div class="text-center">
                                            <span class="d-block display-3" style="color:#00c600;;"><?php   
                                            
                                                $sql = "SELECT * FROM tbl_leads WHERE CatId='$UserCat'";
                                            
                                                            echo $rncnt = getRow($sql);

                                                        ?></span>
                                            <p class="mb-0">Total</p>
                                        </div>
                                    </div>
                                    <!-- <div class="card-footer bg-success bg-pattern-2">
                                        <h6 class="text-white mb-0">Used: 14</h6>
                                    </div> -->
                                </div></a>
                            </div>
                           <?php 
                                
                                
                                     $sql = "SELECT * FROM tbl_diapostion WHERE CatId='$UserCat' ORDER BY SrNo";
                                
                               
                                $row = getList($sql);
                                foreach($row as $result){
                                    
                                      $sql2 = "SELECT * FROM tbl_leads WHERE CatId='$UserCat' AND Diaposition='".$result['Name']."'";   
                                    
                                    $rncnt2 = getRow($sql2);
                           ?>
                           <div class="col-xl-3 col-md-6">
                                <a href="view-all-leads.php?Diaposition=<?php echo $result['Name'];?>" style="color:#000;" href="dashboard.php"><div class="card mb-4">
                                    <div class="progress bg-white">
                                        <div class="progress-bar" style="width:100%;background-color:<?php echo $result['Color'];?>;"></div>
                                    </div>
                                    <div class="card-body" align="center">
                                        <h5><?php echo $result['Name'];?></h5>
                                        <div class="text-center">
                                            <span class="d-block display-3" style="color:<?php echo $result['Color'];?>;"><?php echo $rncnt2;?></span>
                                            <p class="mb-0">Total</p>
                                        </div>
                                    </div>
                                    <!-- <div class="card-footer bg-success bg-pattern-2">
                                        <h6 class="text-white mb-0">Used: 14</h6>
                                    </div> -->
                                </div></a>
                            </div>
                            <?php }  ?>
                            <div class="col-xl-3 col-md-6"></div>
                          <!-- Staustic card 2 Start -->
                        
                            
                            <!-- Staustic card 2 Start -->
                            <div class="col-sm-12 col-xl-12">
                                <div class="card mb-4">
                                    <div class="card-header with-elements">
                                        <h6 class="card-header-title mb-0">Leads</h6>
                                          <div class="card-header-elements ml-auto">
                                            <a href="add-bank-leads.php" class="btn btn-default btn-xs md-btn-flat">Add New Leads</a>
                                        </div>
                                    </div>
                                    
                                  
                                   <div class="card">
                                      <div id="accordion2">
<div class="card mb-2">
                                        
                                        <div id="accordion2-2" class="collapse show" data-parent="#accordion2">
                                            <div class="" style="padding:5px;">
                                                <form id="validation-form" method="post" enctype="multipart/form-data" action="">
<div class="form-row">

       

  <div class="form-group col-md-3">
<label class="form-label">Services</label>
 <select class="form-control" name="Services" id="Services">
<option selected="" value="all">All</option>
 <?php 
  $sql12 = "SELECT * FROM tbl_courses WHERE CatId = '$UserCat' AND Status='1'";
  $row12 = getList($sql12);
  foreach($row12 as $result){
     ?>
  <option <?php if($_REQUEST["Services"] == $result['Name']) {?> selected <?php } ?> value="<?php echo $result['Name'];?>">
    <?php echo $result['Name']; ?></option>
<?php } ?>
</select>
<div class="clearfix"></div>
</div>

  <div class="form-group col-md-3">
                                            <label class="form-label">Diapostion </label>
                                            <select class="form-control" name="Diaposition" id="Diaposition">
                                                <option selected="" value="all">All</option>
                                                <?php 
                                        $q = "select * from tbl_diapostion WHERE Status=1 AND CatId='$UserCat'";
                                        $r = $conn->query($q);
                                        while($rw = $r->fetch_assoc())
                                    {
                                ?>
                                                <option <?php if($_REQUEST['Diaposition']==$rw['Name']){ ?> selected <?php } ?>
                                                    value="<?php echo $rw['Name']; ?>"><?php echo $rw['Name']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>


<div class="form-group col-md-2">
<label class="form-label">From Date </label>
<input type="date" name="FromDate" id="FromDate" class="form-control" value="<?php echo $_POST['FromDate'] ?>" autocomplete="off">
</div>
<div class="form-group col-md-2">
<label class="form-label">To Date</label>
<input type="date" name="ToDate" id="ToDate" class="form-control" value="<?php echo $_POST['ToDate'] ?>" autocomplete="off">
</div>
<input type="hidden" name="Search" value="Search">
<div class="form-group col-md-1" style="padding-top:20px;">
<button type="submit" name="submit" class="btn btn-primary btn-finish">Search</button>
</div>
<?php if(isset($_POST['Search'])) {?>
<div class="col-md-1">
<label class="form-label d-none d-md-block">&nbsp;</label>
<a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-info btn-block" data-toggle="tooltip" data-placement="top" data-original-title="Clear Filter">X</a>
</div>
<?php } ?>
</div>

</form>
                                            </div>
                                        </div>
                                    </div>
   </div>
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered" style="width:100%">
       <thead>
           <tr>
              <th>#</th>
              <th>Service</th>
              <th>Status</th>
              <th>Customer Name</th>
              <th>Phone No</th>
              <th>Email Id</th>
              <!--<th>Diapostion</th>
              <th>Sub</th>-->
              <th>Telecaller Name</th>
              <th>Calling Time</th>
              <th>Conversation</th>
             <!-- <th>Calling After</th>
              <th>Next Date</th>
              <th>Time</th>
              <th>Calling Date</th>
              <th>Calling Time</th>-->
              <th>Next Action</th>
              <?php if(in_array("10", $Options) || in_array("11", $Options)) {?>
               <th>Action</th>
               <?php } ?>
            </tr>
        </thead>
<tbody>
<?php 
$i=1;

    $sql2 = "SELECT tp.* FROM tbl_leads tp WHERE tp.CatId='$UserCat'
          "; 


if($_REQUEST['CatId']){
                $CatId = $_REQUEST['CatId'];
                if($CatId == 'all'){
                    $sql2.= " ";
                }
                else{
                $sql2.= " AND tp.CatId='$CatId'";
                }
            }
            if($_REQUEST['Services']){
                $Services = $_REQUEST['Services'];
                if($Services == 'all'){
                    $sql2.= " ";
                }
                else{
                $sql2.= " AND tp.Services='$Services'";
                }
            }
            if($_REQUEST['Diaposition']){
                $Diaposition = $_REQUEST['Diaposition'];
                if($Diaposition == 'all'){
                    $sql2.= " ";
                }
                else{
                $sql2.= " AND tp.Diaposition='$Diaposition'";
                }
            }
            if($_REQUEST['FromDate']){
                $FromDate = $_REQUEST['FromDate'];
                $sql2.= " AND tp.CreatedDate>='$FromDate'";
            }
            if($_REQUEST['ToDate']){
                $ToDate = $_REQUEST['ToDate'];
                $sql2.= " AND tp.CreatedDate<='$ToDate'";
            }
            $sql2.=" ORDER BY tp.id DESC"; 
            //echo $sql2;
    $res2 = $conn->query($sql2);
    $row_cnt = mysqli_num_rows($res2);
    if($row_cnt > 0){
    while($row = $res2->fetch_assoc()){
        if($row['CatId'] == 'Bank'){
          $Indus = "Bank";
          $acturl = "bank-next-action.php";
          $editurl = "add-bank-leads.php";
        }
        else if($row['CatId'] == 'Health'){
          $Indus = "Health";
          $acturl = "health-next-action.php";
          $editurl = "add-health-leads.php";
        }
        else if($row['CatId'] == 'Education'){
          $Indus = "Education";
          $acturl = "education-next-action.php";
          $editurl = "add-education-leads.php";
        }
$sql33 = "SELECT Color FROM tbl_diapostion WHERE Name='".$row['Diaposition']."' AND CatId='$Indus'";
      $row33 = getRecord($sql33);
     ?>
<tr style="cursor: pointer;" onclick="take_action(<?php echo $row['id']; ?>,'<?php echo $acturl;?>')">
             <td><?php echo $i; ?></td>
            
          
             <td><?php echo $row['Services']; ?></td>
             <td><a href="javascript:void(0)" class="badge badge-pill badge-success" style="background-color: <?php echo $row33['Color']; ?>;"><?php echo $row['Diaposition']; ?></a></td>
             <td><?php echo $row['CustName']; ?></td>
             <td><?php echo $row['Phone']; ?></td>
             <td><?php echo $row['EmailId']; ?></td>
             <td><?php echo $row['TelecallerName']; ?></td>
           <!--  <td><?php echo $row['Sub']; ?></td>
             <td><?php echo $row['Details']; ?></td>
             <td><?php echo $row['CallAfter']; ?></td>
              <td><?php if($row['NextDate']!='0000-00-00'){echo date("d/m/Y", strtotime(str_replace('-', '/',$row['NextDate']))); }?></td>
          
             <td><?php echo $row['NextTime']; ?></td>
             <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['CreatedDate']))); ?></td>-->
             <td><?php echo $row['CreatedTime']; ?></td>
             <td><?php echo $row['Details']; ?></td>
            <td><a href="<?php echo $acturl;?>?id=<?php echo $row['id']; ?>" target="_new" class="badge badge-pill badge-primary">Take Action</a>

              <a href="view-lead-actions.php?id=<?php echo $row['id']; ?>" target="_new" class="badge badge-pill badge-secondary">View Action</a>
            </td>
           <?php if(in_array("10", $Options) || in_array("11", $Options)) {?>
<td>
  <?php if(in_array("10", $Options)){?>
 <a href="<?php echo $editurl;?>?id=<?php echo $row['id']; ?>"><i class="lnr lnr-pencil mr-2"></i></a>
   <?php } if(in_array("11", $Options)){?>
 &nbsp;&nbsp;<a onClick="return confirm('Are you sure you want delete this leads?');" href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $row['id']; ?>&action=delete"><i class="lnr lnr-trash text-danger"></i></a> <?php } ?>
</td> <?php } ?>
</tr>
<?php $i++;}} ?>

</tbody>
    </table>
</div>
</div>
                            </div>
                            

                        </div>


                        


                    </div>








                </div>



                <?php include_once 'footer.php'; ?>

            </div>

        </div>

    </div>

    <div class="layout-overlay layout-sidenav-toggle"></div>
    </div>


    <?php include_once 'footer_script.php'; ?>
    
<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>
    

  
    
    <script type="text/javascript">
 function take_action(id,url){
     //alert(url);
     setTimeout(function() {
        window.open(
            url+'?id=' + id, 'stickerPrint',
            'toolbar=1, scrollbars=1, location=1,statusbar=0, menubar=1, resizable=1, width=800, height=800,left=250,top=50,right=50'
        );
    }, 1);
 
 }
	$(document).ready(function() {
    $('#example').DataTable({
        "scrollX": true
    });
});
</script>
</body>

</html>