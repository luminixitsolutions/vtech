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

if($_POST['action'] == 'getBrands'){?>
    <option value="" selected="selected" disabled>Select Brand</option>
<?php 
    $DeptId = $_POST['id'];
        $q = "select * from tbl_sub_category WHERE CatId = '$DeptId' AND Status='1'";
        $r = $conn->query($q);
        while($rw = $r->fetch_assoc())
    {
?>
    <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
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
<?php } } ?>


