<?php 
session_start();
include_once '../config.php';
$user_id = $_SESSION['Admin']['id'];
if($_POST['action'] == 'getState'){?>
    <option value="" selected="selected" disabled="">Select State</option>
<?php 
    $CountryId = $_POST['id'];
        $q = "select * from tbl_state WHERE CountryId = '$CountryId' AND Status='1'";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
<?php } } 

if($_POST['action'] == 'getCity'){?>
    <option value="" selected="selected" disabled="">Select City</option>
<?php 
    $StateId = $_POST['id'];
        $q = "select * from tbl_city WHERE StateId = '$StateId' AND Status='1'";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
<?php } } 

if($_POST['action'] == 'getArea'){?>
    <option value="" selected="selected" disabled="">Select Area</option>
<?php 
    $CityId = $_POST['id'];
        $q = "select * from tbl_area WHERE CityId = '$CityId' AND Status='1'";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
<?php } } 

if($_POST['action'] == 'getCourse'){?>
    <option value="" selected="selected" disabled="">Select Course</option>
<?php 
    $CityId = $_POST['id'];
        $q = "select * from tbl_courses WHERE ColgId = '$CityId' AND Status='1'";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
<?php } } 

if($_POST['action'] == 'getCourse'){?>
    <option value="" selected="selected" disabled="">Select Course</option>
<?php 
    $DeptId = $_POST['id'];
        $q = "select * from tbl_courses WHERE DeptId = '$DeptId' AND Status='1'";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
    <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
<?php } }




if($_POST['action'] == 'getPartners'){?>
    <option value="" selected="selected" disabled="">Select Partner</option>
<?php 
    $CityId = $_POST['id'];
        $q = "select * from tbl_users WHERE CatId = '$CityId' AND Status='1' AND Roll=3";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option value="<?php echo $rw['Fname']; ?>"><?php echo $rw['Fname']; ?></option>
<?php } } 


if($_POST['action'] == 'getDistrict'){?>
    <option value="all" selected="selected" >All District</option>
<?php 
    $StateId = $_POST['id'];
        $q = "select DISTINCT(District) As District from tbl_users WHERE District!='' AND StateId='$StateId' ORDER BY District ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option value="<?php echo $rw['District']; ?>"><?php echo $rw['District']; ?></option>
<?php } } 

if($_POST['action'] == 'getServicesDetails'){
    $id = $_POST['id'];
    $sql = "SELECT * FROM tbl_courses WHERE id='$id'";
    $row = getRecord($sql);
    echo $row['Details'];


 }

 if($_POST['action'] == 'getServices'){?>
    <option value="all" selected="selected">All</option>
<?php 
    $CityId = $_POST['id'];
        $q = "select * from tbl_courses WHERE CatId = '$CityId' AND Status='1'";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
<?php } } 

 if($_POST['action'] == 'getServices2'){?>
    <option value="" selected="selected" disabled>Select Service</option>
<?php 
    $CityId = $_POST['id'];
        $q = "select * from tbl_courses WHERE CatId = '$CityId' AND Status='1'";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option value="<?php echo $rw['Name']; ?>"><?php echo $rw['Name']; ?></option>
<?php } } 


if($_POST['action'] == 'getBranch'){?>
    <option value="" selected="selected" disabled="">Select Branch</option>
<?php 
    $CityId = $_POST['id'];
        $q = "select * from tbl_users WHERE PartnerName = '$CityId' AND Status='1' AND Roll=6";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option value="<?php echo $rw['Fname']; ?>"><?php echo $rw['Fname']; ?></option>
<?php } } 

if($_POST['action'] == 'getDiapostion'){?>
    <option value="all" selected="selected" >All</option>
<?php 
    $DeptId = $_POST['id'];
        $q = "select * from tbl_diapostion WHERE CatId = '$DeptId' AND Status='1'";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
    <option value="<?php echo $rw['Name']; ?>"><?php echo $rw['Name']; ?></option>
<?php } }



if($_POST['action'] == 'getProd'){?>
    <option value="" selected="selected" disabled>Select Product</option>
<?php 
    $DeptId = $_POST['id'];
        $q = "select * from tbl_products WHERE BrandId = '$DeptId' AND Status='1'";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
    <option value="<?php echo $rw['id']; ?>"><?php echo $rw['ProductName']." (".$rw['PrdNo'].")"; ?></option>
<?php } }


if($_POST['action'] == 'getModelNo'){?>
    <option value="all" selected="selected" >All</option>
<?php 
    $DeptId = $_POST['id'];
        $q = "select * from tbl_stocks WHERE ModelNo = '$DeptId' AND BuyStatus='1'";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
    <option value="<?php echo $rw['ProductNo']; ?>"><?php echo $rw['ProductNo']; ?></option>
<?php } } 

//shop 
if($_POST['action'] == 'getSubCat'){?>
  <option value="" selected="selected" disabled="">Select Sub Category</option>
<?php 
    $CatId = $_POST['id'];
        $q = "select * from sub_category WHERE CatId ='$CatId' AND Status='1'";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
<?php } }

if($_POST['action'] == 'getAttrValue'){?>
  <option value="" selected="selected" disabled="">--Select--</option>
<?php 
    $id = $_POST['id'];
        $q = "select * from attribute_value WHERE AttrNameId ='$id' AND Status='1'";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
<?php } }

if($_POST['action'] == 'getAttrName'){?>
  <option value="" selected="selected" disabled="">Select Attribute Name</option>
<?php 
    $id = $_POST['id'];
        $q = "select * from attribute_name WHERE CatId ='$id' AND Status='1'";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
<?php } }

if($_POST['action'] == 'getBrands'){?>
  <option value="" selected="selected" disabled="">Select Brand</option>
<?php 
    $CatId = $_POST['id'];
        $q = "select * from brands WHERE CatId ='$CatId' AND Status='1'";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
<?php } }

if($_POST['action'] == 'getMoreAttributes'){
    $i = $_POST['id'];?>
<tr id="row<?php echo $i;?>">
<td>
<select class="form-control" name="AttrName" id="AttrName<?php echo $i;?>" onchange="getAttrValue(<?php echo $i;?>,this.value);">
<option selected="" disabled="" value="">--Select--</option>
<?php 
        $q = "select * from attribute_name WHERE Status='1'";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
              <?php } ?>
</select>
</td>
<td>
<select class="form-control" name="AttrValue" id="AttrValue<?php echo $i;?>">
<option selected="" disabled="" value="">--Select--</option>
</select>
</td>
<td>
<input type="text" class="form-control" value="" onKeyPress="return isNumberKey(event)">
</td>
<td>
<input type="file" class="form-control">
</td>
<td>
<label class="switcher switcher-success">
<input type="checkbox" class="switcher-input" checked="">
<span class="switcher-indicator">
<span class="switcher-yes">
<span class="ion ion-md-checkmark"></span>
</span>
<span class="switcher-no">
<span class="ion ion-md-close"></span>
</span>
</span>
</label>
</td>
<td>
    <td><a href="javascript:void(0)" id="<?php echo $i;?>" class="btn btn-default md-btn-flat icon-btn btn-sm btn_remove"><i class="ion ion-md-close"></i></a></td>
</td>
</tr>
<?php } 
if($_POST['action'] == 'deletePhoto'){
    $id = $_POST['id'];
    $Photo = $_POST['Photo'];
        $q = "UPDATE tbl_users SET Photo='' WHERE id=$id";
        $conn->query($q);
        $src = "../../uploads/$Photo";
        unlink($src);

    echo "Profile Photo Delete Successfully";
}

if($_POST['action'] == 'getMembers'){
    $Roll = $_POST['id'];?>
  <!--   <option selected="" disabled="">Select Account</option> -->

 <?php 
     $sql4 = "SELECT * FROM tbl_users WHERE Status=1 AND Roll!=1 AND Tokens!=''";
     if($Roll == 'all'){
        $sql4.="";
     }
     else{
        $sql4.=" AND Roll='$Roll'";
     }
     $row4 = getList($sql4);
     $rncnt = getRow($sql4);
     if($rncnt > 0){?>
     <option value="all" selected="">All</option>
     <?php 
     if($Roll != 'all'){
     foreach($row4 as $result)
      {
      ?>
    <option value="<?php echo $result['id']; ?>"><?php echo $result['Fname']." ".$result['Lname']." (".$result['AccName'].")"; ?></option>
<?php } } } }   

if($_POST['action'] == 'getCommissionList'){
    $Oid = $_POST['Oid'];
    $UserId = $_POST['UserId'];
    $TotAmt = $_POST['Amount'];
  
$sql11 = "SELECT UnderBy FROM tbl_users WHERE id='$UserId'";
        $row11 = getRecord($sql11);
        $rncnt11 = getRow($sql11);
        $UnderUserId1 = $row11['UnderBy'];
        if($rncnt11 > 0){
            $sql_11 = "SELECT * FROM tbl_set_percentage WHERE Roll=2";
            $row_11 = getRecord($sql_11);
            //$Per = $row_11['Percentage'];
            $Per = 20;
            $Commision = $TotAmt*($Per/100);
            $sql11_2 = "SELECT Fname,Lname,Phone FROM tbl_users WHERE id='$UnderUserId1'";
            $row11_2 = getRecord($sql11_2);
            $rncnt11_2 = getRow($sql11_2);
            if($rncnt11_2 > 0){
?>
<input type="hidden" id="UnderUserId1" name="UnderUserId1" value="<?php echo $UnderUserId1;?>">
<div class="form-group col-md-4">
<label class="form-label">Customer Name <span class="text-danger">*</span></label>
<input type="text" name="CustName" class="form-control" placeholder="" autocomplete="off" required value="<?php echo $row11_2["Fname"]." ".$row11_2["Lname"]; ?>" readonly>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Conact No <span class="text-danger">*</span></label>
<input type="text" name="OrderNo" class="form-control" placeholder="" autocomplete="off" required value="<?php echo $row11_2["Phone"]; ?>" readonly>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-2">
<label class="form-label">Percentage <span class="text-danger">*</span></label>
<input type="text" name="Per1" class="form-control" placeholder="" autocomplete="off" required value="<?php echo $Per; ?>" readonly>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Amount <span class="text-danger">*</span></label>
<input type="text" name="Amount1" class="form-control" placeholder="" autocomplete="off" required value="<?php echo $Commision; ?>" readonly>
<div class="clearfix"></div>
</div>
<?php } } ?>

<?php
$sql22 = "SELECT UnderBy FROM tbl_users WHERE id='$UnderUserId1'";
        $row22 = getRecord($sql22);
        $UnderUserId2 = $row22['UnderBy'];
        if($UnderUserId2 != 0){
             $Per2 = 5;
            $Commision2 = $TotAmt*($Per2/100);
            $sql22_2 = "SELECT Fname,Lname,Phone FROM tbl_users WHERE id='$UnderUserId2'";
            $row22_2 = getRecord($sql22_2);
            $rncnt22_2 = getRow($sql22_2);
            if($rncnt22_2 > 0){
?>
<input type="hidden" id="UnderUserId2" name="UnderUserId2" value="<?php echo $UnderUserId2;?>">
<div class="form-group col-md-4">
<label class="form-label">Customer Name <span class="text-danger">*</span></label>
<input type="text" name="CustName" class="form-control" placeholder="" autocomplete="off" required value="<?php echo $row22_2["Fname"]." ".$row11_2["Lname"]; ?>" readonly>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Conact No <span class="text-danger">*</span></label>
<input type="text" name="OrderNo" class="form-control" placeholder="" autocomplete="off" required value="<?php echo $row22_2["Phone"]; ?>" readonly>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-2">
<label class="form-label">Percentage <span class="text-danger">*</span></label>
<input type="text" name="Per2" class="form-control" placeholder="" autocomplete="off" required value="<?php echo $Per2; ?>" readonly>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Amount <span class="text-danger">*</span></label>
<input type="text" name="Amount2" class="form-control" placeholder="" autocomplete="off" required value="<?php echo $Commision2; ?>" readonly>
<div class="clearfix"></div>
</div>
<?php } } ?>

<?php
 $sql33 = "SELECT UnderBy FROM tbl_users WHERE id='$UnderUserId2'";
        $row33 = getRecord($sql33);
        $UnderUserId3 = $row33['UnderBy'];
        if($UnderUserId3 != 0){
            $Per3 = 3;
            $Commision3 = $TotAmt*($Per3/100);
            $sql33_2 = "SELECT Fname,Lname,Phone FROM tbl_users WHERE id='$UnderUserId2'";
            $row33_2 = getRecord($sql33_2);
            $rncnt33_2 = getRow($sql33_2);
            if($rncnt33_2 > 0){
?>
<input type="hidden" id="UnderUserId3" name="UnderUserId3" value="<?php echo $UnderUserId3;?>">
<div class="form-group col-md-4">
<label class="form-label">Customer Name <span class="text-danger">*</span></label>
<input type="text" name="CustName" class="form-control" placeholder="" autocomplete="off" required value="<?php echo $row33_2["Fname"]." ".$row11_2["Lname"]; ?>" readonly>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Conact No <span class="text-danger">*</span></label>
<input type="text" name="OrderNo" class="form-control" placeholder="" autocomplete="off" required value="<?php echo $row33_2["Phone"]; ?>" readonly>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-2">
<label class="form-label">Percentage <span class="text-danger">*</span></label>
<input type="text" name="Per3" class="form-control" placeholder="" autocomplete="off" required value="<?php echo $Per3; ?>" readonly>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Amount <span class="text-danger">*</span></label>
<input type="text" name="Amount3" class="form-control" placeholder="" autocomplete="off" required value="<?php echo $Commision2; ?>" readonly>
<div class="clearfix"></div>
</div>
<?php } } ?>

<?php
 $sql44 = "SELECT UnderBy FROM tbl_users WHERE id='$UnderUserId3'";
        $row44 = getRecord($sql44);
        $UnderUserId4 = $row44['UnderBy'];
        if($UnderUserId4 != 0){
            $Per4 = 2;
            $Commision4 = $TotAmt*($Per4/100);
            $sql44_2 = "SELECT Fname,Lname,Phone FROM tbl_users WHERE id='$UnderUserId2'";
            $row44_2 = getRecord($sql44_2);
            $rncnt44_2 = getRow($sql44_2);
            if($rncnt44_2 > 0){
?>
<input type="hidden" id="UnderUserId4" name="UnderUserId4" value="<?php echo $UnderUserId4;?>">
<div class="form-group col-md-4">
<label class="form-label">Customer Name <span class="text-danger">*</span></label>
<input type="text" name="CustName" class="form-control" placeholder="" autocomplete="off" required value="<?php echo $row44_2["Fname"]." ".$row11_2["Lname"]; ?>" readonly>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Conact No <span class="text-danger">*</span></label>
<input type="text" name="OrderNo" class="form-control" placeholder="" autocomplete="off" required value="<?php echo $row44_2["Phone"]; ?>" readonly>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-2">
<label class="form-label">Percentage <span class="text-danger">*</span></label>
<input type="text" name="Per4" class="form-control" placeholder="" autocomplete="off" required value="<?php echo $Per4; ?>" readonly>
<div class="clearfix"></div>
</div>

<div class="form-group col-md-3">
<label class="form-label">Amount <span class="text-danger">*</span></label>
<input type="text" name="Amount4" class="form-control" placeholder="" autocomplete="off" required value="<?php echo $Commision2; ?>" readonly>
<div class="clearfix"></div>
</div>
<?php } } 
}

if($_POST['action'] == 'changeStatus'){
    $id = $_POST['id'];
    $Status = $_POST['Status'];
    $UserId = $_POST['UserId'];
    $Price = $_POST['Price'];
    $CreatedDate = date('Y-m-d');
    $CreatedTime = date('h:i a');
    $sql = "UPDATE tbl_donate_cow SET Status='$Status',ApprovePrice='$Price' WHERE id='$id'";
    $conn->query($sql);
    if($Status == 1){
        $sql2 = "INSERT INTO wallet SET UserId='$UserId',Amount='$Price',Narration='Amount Added via Donate Cow',Status='Cr',CreatedDate='$CreatedDate',CreatedTime='$CreatedTime'";
        $conn->query($sql2);
        echo "Approved Successfully!";
    }
    else{
        $sql2 = "INSERT INTO wallet SET UserId='$UserId',Amount='$Price',Narration='Amount Deducted via Donate Cow Request Cancel',Status='Dr',CreatedDate='$CreatedDate',CreatedTime='$CreatedTime'";
        $conn->query($sql2);
         echo "Request Rejected/Pending!";
    }
    
    
}

if($_POST['action'] == 'get_Areas'){?>
     <div class="form-group col-md-12">
      <label class="form-label">Area <span class="text-danger">*</span></label>
      </div>
       <?php 
       $sql = "SELECT GROUP_CONCAT(AreaId) As AreaId FROM `tbl_allocate_areas`";
       $row = getRecord($sql);
       $AreaId = $row['AreaId'];
 $CityId = $_POST['id'];
        $q = "select * from area WHERE CityId='$CityId' AND id NOT IN($AreaId) ORDER BY Name ASC";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
      <div class="form-group col-lg-3">
<label class="custom-control custom-checkbox m-0">
<input type="checkbox" name="Areas[]" value="<?php echo $rw['id'];?>" class="custom-control-input">
<span class="custom-control-label"><?php echo $rw['Name'];?></span>
</label>
</div>
     <?php } } 
     
     if($_POST['action'] == 'getStoreIncharge'){?>
        <option value="" selected="selected" disabled="">Select Store Incharge</option>
    <?php 
        $BranchId = $_POST['id'];
            $q = "SELECT * FROM tbl_users WHERE Status='1' AND Roll=27 AND BranchId='$BranchId'";
            $r = $conn->query($q);
            while($rw = $r->fetch_assoc())
        {
    ?>
                    <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Fname']; ?></option>
    <?php } }    
    
     if($_POST['action'] == 'getVehicalNos'){?>
        <option value="" selected="selected" disabled="">Select Vehical No</option>
    <?php
    $VehicalDate = $_POST['vehdate'];
        $sql12 = "SELECT DISTINCT(VehicalNo) AS VehicalNo FROM tbl_stocks WHERE VehicalNo!='' AND CrDr='cr' AND VehicalDate='$VehicalDate'";
        $row12 = getList($sql12);
        foreach ($row12 as $result) {
    ?>
        <option <?php if($row7["VehicalNo"] == $result['VehicalNo']) {?> selected <?php } ?> value="<?php echo $result['VehicalNo']; ?>">
        <?php echo $result['VehicalNo']; ?></option>
        <?php } } 
        
        if($_POST['action'] == 'getStoreExecutive'){?>
        <option value="" selected="selected" disabled="">Select Dispatch Officier</option>
    <?php 
        $BranchId = $_POST['id'];
             $q = "SELECT * FROM tbl_users WHERE Status='1' AND Roll=26 AND UnderUser='$BranchId'";
            $r = $conn->query($q);
            while($rw = $r->fetch_assoc())
        {
    ?>
                    <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Fname']; ?></option>
    <?php } } 
    
    if($_POST['action'] == 'approveAttendance'){
    $id = $_POST['id'];
    $status = $_POST['status'];
    $ApproveDate = date('Y-m-d');
    $ApproveTime = date('h:i a');
    if($status == 1){
        $line = "Approve By";
    }
    else{
        $line = "Pending By";
    }
    $sql = "UPDATE tbl_attendance SET ApproveStatus='$status',ApproveBy='$user_id',ApproveDate	='$ApproveDate',ApproveTime='$ApproveTime',ApproveLine='$line' WHERE id='$id'";
    $conn->query($sql);
    
    $sql2 = "SELECT UserId,CreatedDate FROM tbl_attendance WHERE id='$id'";
    $row2 = getRecord($sql2);
    $UserId = $row2['UserId'];
    $CreatedDate = $row2['CreatedDate'];
    $sql3 = "UPDATE tbl_attendance SET ApproveStatus='$status',ApproveBy='$user_id',ApproveDate	='$ApproveDate',ApproveTime='$ApproveTime',ApproveLine='$line' WHERE UserId='$UserId' AND CreatedDate='$CreatedDate' AND Type=2";
    $conn->query($sql3);
    echo $status;
    
    } 
    

    if($_POST['action'] == 'getSubHead'){?>
  <option value="" selected="selected" disabled="">Select Sub Head</option>
<?php 
    $CatId = $_POST['id'];
        $q = "select * from tbl_project_sub_head WHERE UnderBy ='$CatId' AND Status='1'";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
                <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
<?php } } 
        ?>


