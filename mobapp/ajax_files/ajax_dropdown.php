<?php 
session_start();
include_once '../config.php';
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

if($_POST['action'] == 'saveLatlng'){
    $lat = $_POST['lat'];
    $lng = $_POST['lng'];
    $_SESSION['lat'] = $lat;
    $_SESSION['lng'] = $lng;
    echo "saved";
}   

if($_POST['action'] == 'checkSponserId'){
    $SponserId = $_POST['SponserId'];
    $sql = "SELECT * FROM customers WHERE (CustomerId='$SponserId' OR Phone='$SponserId') AND Roll=7 AND Status=1";
    $rncnt = getRow($sql);
    if($rncnt > 0){
    $row = getRecord($sql);
    $id = $row['id'];
    $MemberName = $row['Fname']." ".$row['Lname'];
    echo json_encode(array('status'=>1,'name'=>$MemberName,'id'=>$id));  
        //echo 1;//Member id exist
    }
    else{
         echo json_encode(array('status'=>0,'msg'=>'Sponsor id does not exist')); 
        //echo 0;//Member id not exist
    }
}   
?>