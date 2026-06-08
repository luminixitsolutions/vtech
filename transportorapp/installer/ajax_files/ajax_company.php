<?php
include_once '../config.php';
$id = $_POST['id'];
$Oname = addslashes(trim($_POST['Oname']));
$Sname = addslashes(trim($_POST['Sname']));
$Address = addslashes(trim(nl2br($_POST['Address'])));
$Address2 = addslashes(trim(nl2br($_POST['Address2'])));
$GoogleMap = addslashes(trim($_POST['GoogleMap']));
$Phone1 = $_POST['Phone1'];
$EmailId = $_POST['EmailId'];
$Phone2 = $_POST['Phone2'];
$EmailId2 = $_POST['EmailId2'];
$Facebook = addslashes($_POST['Facebook']);
$Twitter = addslashes($_POST['Twitter']);
$Linkedin = addslashes($_POST['Linkedin']);
$Google = addslashes($_POST['Google']);
$Instagram = addslashes($_POST['Instagram']);
$Pinterest = addslashes($_POST['Pinterest']);
$Qualification = addslashes(trim($_POST['Qualification']));
$CpcNo = addslashes(trim($_POST['CpcNo']));

$BankName = addslashes(trim($_POST['BankName']));
$BranchName = addslashes(trim($_POST['BranchName']));
$AccName = addslashes(trim($_POST['AccName']));
$AccNo = addslashes(trim($_POST['AccNo']));
$Ifsc = addslashes(trim($_POST['Ifsc']));
$PanNo = addslashes(trim($_POST['PanNo']));
$GstNo = addslashes(trim($_POST['GstNo']));

$randno = rand(1,100);
$src = $_FILES['Photo']['tmp_name'];
$fnm = substr($_FILES["Photo"]["name"], 0,strrpos($_FILES["Photo"]["name"],'.')); 
$fnm = str_replace(" ","_",$fnm);
$ext = substr($_FILES["Photo"]["name"],strpos($_FILES["Photo"]["name"],"."));
$dest = '../../uploads/'. $randno . "_".$fnm . $ext;
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
$dest2 = '../../uploads/'. $randno2 . "_".$fnm2 . $ext2;
$imagepath2 =  $randno2 . "_".$fnm2 . $ext2;
if(move_uploaded_file($src2, $dest2))
{
$Photo2 = $imagepath2 ;
} 
else{
	$Photo2 = $_POST['OldPhoto2'];
}
$sql = "UPDATE tbl_company_profile SET Sname='$Sname',Oname='$Oname',Address='$Address',Address2='$Address2',GoogleMap='$GoogleMap',Phone1='$Phone1',EmailId='$EmailId',EmailId2='$EmailId2',Phone2='$Phone2',BankName='$BankName',PanNo='$PanNo',GstNo='$GstNo',BranchName='$BranchName',AccName='$AccName',AccNo='$AccNo',Ifsc='$Ifsc' WHERE id='$id'";
$conn->query($sql);
echo 1;
?>