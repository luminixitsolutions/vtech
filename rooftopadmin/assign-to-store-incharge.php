<?php
session_start();
include_once 'config.php';
require_once "exe-database.php";
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "Assign-Store-Incharge";
$Page = "Assign-Store-Incharge";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">

<head>
    <title><?php echo $Proj_Title; ?> | View Sell List</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="" />
    <meta name="keywords" content="">
    <meta name="author" content="" />
    <?php include_once 'header_script.php'; ?>
</head>

<body>

    <div class="layout-wrapper layout-2">
        <div class="layout-inner">

            <?php include_once 'sidebar.php'; ?>


            <div class="layout-container">

                <?php include_once 'top_header.php'; ?>

                <?php

                if (isset($_POST['submit'])) {

  $rncnt = $_POST['rncnt'];
     $rncnt2 = $_POST['rncnt2'];
//     if($rncnt > 0){
//                     $number = count($_POST['CheckId']);

//                     $StoreInchId = $_POST['StoreInchId'];
//                     $CreatedDate = date('Y-m-d H:i:s');
//                     if ($number > 0) {
//                         for ($i = 0; $i < $number; $i++) {
//                             if (trim($_POST["CheckId"][$i] != '')) {
//                                 $CheckId = addslashes(trim($_POST['CheckId'][$i]));
//                                 if ($CheckId == 1) {
//                                     $QtnId = addslashes(trim($_POST['QtnId'][$i]));
//                                      $sql = "UPDATE tbl_rooftop_quotation SET StoreInchStatus='1',StoreInchId='$StoreInchId',StoreInchDate='$CreatedDate' WHERE id='$QtnId'";
//                                     $conn->query($sql);
//                                 }
//                             }
//                         }
//                     }

// }

if($rncnt2 > 0){
                     $number2 = count($_POST['CheckId2']);

                    $StoreInchId = $_POST['StoreInchId'];
                    $CreatedDate = date('Y-m-d H:i:s');
                    if ($number2 > 0) {
                        for ($i2 = 0; $i2 < $number2; $i2++) {
                            if (trim($_POST["CheckId2"][$i2] != '')) {
                                $CheckId2 = addslashes(trim($_POST['CheckId2'][$i2]));
                                if ($CheckId2 == 1) {
                                    $QtnId = addslashes(trim($_POST['QtnId2'][$i2]));
                                     $sql = "UPDATE tbl_users SET StoreInchStatus='1',StoreInchId='$StoreInchId',StoreInchDate='$CreatedDate' WHERE id='$QtnId'";
                                    $conn->query($sql);
                                }
                            }
                        }
                    }
}

                    
                    echo "<script>alert('Assign To Store Incharge');window.location.href='assign-to-store-incharge.php?StoreInchStatus=0';</script>";
                }
                ?>

                <div class="layout-content">

                    <div class="container-fluid flex-grow-1 container-p-y">
                        <h4 class="font-weight-bold py-3 mb-0">Assign Beneficiary To Store Incharge
                            <!-- <span style="float: right;">
<a href="add-sell.php" class="btn btn-secondary btn-round"><i class="ion ion-md-add mr-2"></i> Add New Sell</a></span> -->
                        </h4>

                        <div class="card" style="padding: 10px;">
                            
                             <form id="validation-form" method="post" enctype="multipart/form-data" action="">
                                                <div class="form-row">


                                                  

                                                     <div class="form-group col-lg-4">
                                                        <label class="form-label"> Store<span class="text-danger">*</span></label>
                                                        <select class="select2-demo form-control" name="StoreInchId2" id="StoreInchId2" required>
                                                            <option selected="" value="">Select</option>
                                                            <?php
                                                            $sql12 = "SELECT * FROM tbl_rooftop_branch WHERE Status='1'";
                                                            $row12 = getList($sql12);
                                                            foreach ($row12 as $result) {
                                                            ?>
                                                                <option value="<?php echo $result['id']; ?>" <?php if($_REQUEST['StoreInchId2']==$result['id']){ ?> selected <?php } ?>>
                                                                    <?php echo $result['Name']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <div class="clearfix"></div>
                                                    </div>
                                                    
                                                    
                                                    
                                                    <div class="form-group col-md-2">
		<label class="form-label">State <span class="text-danger">*</span></label>
<select class="select2-demo form-control" id="StateId" name="StateId">
<option selected="" value="all">All State</option>
 <?php 
        $CountryId = $row7['CountryId'];
        $q = "select * from tbl_state WHERE CountryId='1' ORDER BY Name ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($_REQUEST['StateId']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>
	</div>

<!--<div class="form-group col-md-2">
		<label class="form-label">Village <span class="text-danger">*</span></label>
<select class="form-control" id="Village" name="Village">
<option selected="" value="all">All Village</option>
 <?php 
        $q = "select DISTINCT(Village) AS Village from tbl_users WHERE Village!='' AND ProjectType=2";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($_REQUEST['Village']==$rw['Village']){ ?> selected <?php } ?> value="<?php echo $rw['Village']; ?>"><?php echo $rw['Village']; ?></option>
              <?php } ?>
</select>
	</div>-->
	
	<div class="form-group col-md-2">
		<label class="form-label">District <span class="text-danger">*</span></label>
<select class="select2-demo form-control" id="District" name="District">
<option selected="" value="all">All District</option>
 <?php 
        $q = "select DISTINCT(District) AS District from tbl_users WHERE District!='' AND ProjectType=2";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($_REQUEST['District']==$rw['District']){ ?> selected <?php } ?> value="<?php echo $rw['District']; ?>"><?php echo $rw['District']; ?></option>
              <?php } ?>
</select>
	</div>
	
	<div class="form-group col-md-2">
		<label class="form-label">Taluka <span class="text-danger">*</span></label>
<select class="select2-demo form-control" id="Taluka" name="Taluka">
<option selected="" value="all">All Taluka</option>
 <?php 
        $q = "select DISTINCT(Taluka) AS Taluka from tbl_users WHERE Taluka!='' AND ProjectType=2";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option <?php if($_REQUEST['Taluka']==$rw['Taluka']){ ?> selected <?php } ?> value="<?php echo $rw['Taluka']; ?>"><?php echo $rw['Taluka']; ?></option>
              <?php } ?>
</select>
	</div>

    <div class="form-group col-md-2">
                                                        <label class="form-label">Assign Status </label>
                                                        <select class="select2-demo form-control" id="StoreInchStatus" name="StoreInchStatus">
                                                        <option selected="" value="all">All</option>
                                    <option value="1" <?php if($_REQUEST['StoreInchStatus']==1){ ?> selected <?php } ?> >Assign</option>
                                    <option value="0"  <?php if($_REQUEST['StoreInchStatus']==0){ ?> selected <?php } ?> >Not Assign</option>
                                                          
                                                        </select>
                                                    </div>
	
	
	
                                                    <input type="hidden" id="Search" value="Search">
                                                    <div class="form-group col-md-1" style="padding-top:20px;">
                                                        <button type="button" onclick="search()" class="btn btn-primary btn-finish">Search</button>
                                                    </div>
                                                    <?php if (isset($_REQUEST['Search'])) { ?>
                                                        <div class="form-group col-md-1">
                                                            <label class="form-label">&nbsp;</label>
                                                            <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-info btn-block" data-toggle="tooltip" data-placement="top" data-original-title="Clear Filter">X</a>
                                                        </div>
                                                    <?php } ?>
                                                </div>

                                            </form>
                                            
                            <form id="validation-form" method="post" enctype="multipart/form-data" action="">
                                
                                 <?php
                                            $i = 1;
                                            $sql = "SELECT tu.*,tu2.Fname As InchargeName FROM tbl_users tu 
                                                    LEFT JOIN tbl_users tu2 ON tu2.id=tu.StoreInchId 
                                                    WHERE tu.ProjectType=2 ORDER BY tu.CreatedDate DESC";
                                                    $rncnt2 = getRow($sql);?>
        <input type="hidden" name="rncnt2" value="<?php echo $rncnt2;?>">
       
        <?php
                                            $i = 1;
                                            $sql = "SELECT tp.*,tu.Fname As InchargeName FROM tbl_rooftop_quotation tp 
                    LEFT JOIN tbl_users tu ON tu.id=tp.StoreInchId 
                    
            
            ORDER BY tp.CreatedDate DESC";
            $rncnt = getRow($sql);?>
        <input type="hidden" name="rncnt" value="<?php echo $rncnt;?>">
        
        
                              
                                <div class="card-datatable table-responsive">
                                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Assign To Store</th>
                                                 <th>Consumer No</th>
                                                <th>Customer Name</th>
                                                <th>Contact No</th>
                                                <th>Rooftop Plant Capacity</th>
                                                <th>Address</th>
                                                 <th>State</th>
                                                <th>Village</th>
                                                    <th>District</th>
                                                <!--<th>QTN NO</th>
                                                <th>QTN Date</th>
                                                <th>Paid Status</th>
                                                <th>Total Amount</th>
                                                <th>Paid Amount</th>
                                                <th>Bal Amount</th>
                                                <th>Paid Date</th>
                                                <th>Paid By</th>-->



                                            </tr>
                                        </thead>
                                        <tbody>
                                           


                                            <?php
                                            $i = 1;
                                            $sql = "SELECT tu.*,tu2.Name As InchargeName,ts2.Name As StateName,tcm.Name As Pump_Capacity FROM tbl_users tu 
                                                    LEFT JOIN tbl_branch tu2 ON tu2.id=tu.StoreInchId 
                                                    LEFT JOIN tbl_state ts2 ON ts2.id=tu.StateId 
                                                    LEFT JOIN tbl_rooftop_common_master tcm ON tcm.id=tu.RooftopPlantCapacity 
                                                    WHERE tu.ProjectType=2 AND tu.UnderProdStatus=1 AND tu.SurveyDetails=1 AND tu.FieldSurveyDetails=1";
                                       if($_REQUEST['StoreInchId2']!=''){
                if($_REQUEST['StoreInchId2'] == 'all'){
                    $sql.=" ";
                }
                else{
                $sql.=" AND tu.StoreInchId='".$_REQUEST['StoreInchId2']."'";
                }
            }
            
            
            if($_REQUEST['StateId']!=''){
                if($_REQUEST['StateId'] == 'all'){
                    $sql.=" ";
                }
                else{
                $sql.=" AND tu.StateId='".$_REQUEST['StateId']."'";
                }
            }
            /*if($_REQUEST['Village']!=''){
                if($_REQUEST['Village'] == 'all'){
                    $sql.=" ";
                }
                else{
                $sql.=" AND tu.Village='".$_REQUEST['Village']."'";
                }
            }*/
            if($_REQUEST['Taluka']!=''){
                if($_REQUEST['Taluka'] == 'all'){
                    $sql.=" ";
                }
                else{
                $sql.=" AND tu.Taluka='".$_REQUEST['Taluka']."'";
                }
            }
            
            if($_REQUEST['District']!=''){
                if($_REQUEST['District'] == 'all'){
                    $sql.=" ";
                }
                else{
                $sql.=" AND tu.District='".$_REQUEST['District']."'";
                }
            } 

            if($_REQUEST['StoreInchStatus']!=''){
                if($_REQUEST['StoreInchStatus'] == 'all'){
                    $sql.=" ";
                }
                else{
                $sql.=" AND tu.StoreInchStatus='".$_REQUEST['StoreInchStatus']."'";
                }
            }
            
                                        $sql.= " ORDER BY tu.CreatedDate DESC";
                                               //echo  $sql;   
                                            $res = $conn->query($sql);
                                            while ($row = $res->fetch_assoc()) {

                                                $sql22 = "SELECT * FROM tbl_users WHERE StoreInchStatus=1 AND id='" . $row['id'] . "'";
                                                $rncnt22 = getRow($sql22);
                                                if ($rncnt22 > 0) {
                                                    $bcolor = "background-color: #b9efb9;";
                                                } else {
                                                    $bcolor = "";
                                                }

                                            ?>
                                                <tr style="<?php echo $bcolor; ?>">
                                                    <td><?php if ($rncnt22 > 0) {
                                                        } else { ?>
                                                            <label class="custom-control custom-checkbox">
                                                                <input type="checkbox" id="Check_Id2<?php echo $row['id']; ?>" value="0" class="custom-control-input is-valid" onclick="featured2(<?php echo $row['id']; ?>)">
                                                                <span class="custom-control-label">&nbsp;</span>
                                                            </label><?php } ?>
                                                    </td>
                                                    <input type="hidden" value="0" name="CheckId2[]" id="CheckId2<?php echo $row['id']; ?>">
                                                    <input type="hidden" value="<?php echo $row['id']; ?>" name="QtnId2[]">
                                                    <input type="hidden" value="<?php echo $row['Fname']; ?>" name="CustName[]">
                                                    <input type="hidden" value="<?php echo $row['Phone']; ?>" name="CellNo[]">
                                                    <input type="hidden" value="<?php echo $row['Address']; ?>" name="Address[]">

                                                    <td><?php echo $row['InchargeName']; ?></td>
                                                    <td><?php echo $row['BeneficiaryId']; ?></td>
                                                    <td><?php echo $row['Fname']; ?></td>

                                                    <td><?php echo $row['Phone']; ?></td>
                                                    <td><?php echo $row['Pump_Capacity']; ?></td>
                                                    <td><?php echo $row['Address']; ?></td>
                                                     <td><?php echo $row['StateName']; ?></td>
            <td><?php echo $row['Village']; ?></td>
            <td><?php echo $row['District']; ?></td>
                                                    <!--<td><?php echo $row['InvoiceNo']; ?></td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>-->


                                                </tr>
                                            <?php $i++;
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>

 <div class="form-row">
                                                    <div class="form-group col-lg-4">
                                                        <label class="form-label"> Store<span class="text-danger">*</span></label>
                                                        <select class="select2-demo form-control" name="StoreInchId" id="StoreInchId" required>
                                                            <option selected="" value="">Select</option>
                                                            <?php
                                                            $sql12 = "SELECT * FROM tbl_rooftop_branch WHERE Status='1'";
                                                            $row12 = getList($sql12);
                                                            foreach ($row12 as $result) {
                                                            ?>
                                                                <option value="<?php echo $result['id']; ?>">
                                                                    <?php echo $result['Name']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <div class="clearfix"></div>
                                                    </div>



                                               


                                <div class="form-group col-md-1" style="padding-top:20px;">
                                    <button type="submit" name="submit" class="btn btn-primary btn-finish">Assign</button>
                                </div>
                                 </div>
                            </form>
                        </div>
                    </div>


                    <?php include_once 'footer.php'; ?>

                </div>

            </div>

        </div>

        <div class="layout-overlay layout-sidenav-toggle"></div>
    </div>


    <?php include_once 'footer_script.php'; ?>

    <script type="text/javascript">
   function search(){
        var PumpCapacity = $('#PumpCapacity').val();
    var StoreInchId2 = $('#StoreInchId2').val();
    var StateId = $('#StateId').val();
    var District = $('#District').val();
    //var Village = $('#Village').val();
    var Search = $('#Search').val();
    var Taluka = $('#Taluka').val();
    var StoreInchStatus = $('#StoreInchStatus').val();
    window.location.href="assign-to-store-incharge.php?StoreInchId2="+StoreInchId2+"&StateId="+StateId+"&District="+District+"&Search="+Search+"&PumpCapacity="+PumpCapacity+"&Taluka="+Taluka+"&StoreInchStatus="+StoreInchStatus;
}
        function featured(id) {
            if ($('#Check_Id' + id).prop('checked') == true) {
                $('#CheckId' + id).val(1);
            } else {
                $('#CheckId' + id).val(0);
            }
        }

        function featured2(id) {
            if ($('#Check_Id2' + id).prop('checked') == true) {
                $('#CheckId2' + id).val(1);
            } else {
                $('#CheckId2' + id).val(0);
            }
        }

        $(document).ready(function() {
            $('#example').DataTable({
                "scrollX": true
            });
        });
    </script>
</body>

</html>