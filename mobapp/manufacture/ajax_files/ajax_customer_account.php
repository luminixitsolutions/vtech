<?php
session_start();
$sessionid = session_id();
include_once '../../config.php';
$user_id = $_SESSION['User']['id'];
if($_POST['action'] == 'Save'){
$id = $_POST['id'];
$ColgId = addslashes(trim($_POST["ColgId"]));
$CourseId = addslashes(trim($_POST["CourseId"]));
$Fname = addslashes(trim($_POST['Fname']));
$Mname = addslashes(trim($_POST['Mname']));
$Lname = addslashes(trim($_POST['Lname']));
$Phone = $_POST['Phone'];
$EmailId = $_POST['EmailId'];
$Phone2 = $_POST['Phone2'];
$Password = addslashes(trim($_POST['Password']));
$CountryId = addslashes($_POST['CountryId']);
$StateId = addslashes($_POST['StateId']);
$CityId = addslashes($_POST['CityId']);
$Address = addslashes(trim($_POST['Address']));
$LoanCategory = addslashes(trim($_POST['LoanCategory']));
$SubCategory = addslashes(trim($_POST['SubCategory']));
$Campaign = addslashes(trim($_POST['Campaign']));
$Source = addslashes(trim($_POST['Source']));
$CallDate = addslashes(trim($_POST['CallDate']));
$AgentName = addslashes(trim($_POST['AgentName']));
$AgentComments = addslashes(trim($_POST['AgentComments']));
$PartId = addslashes(trim($_POST['PartId']));
$BranchId = addslashes(trim($_POST['BranchId']));
$Pincode = trim($_POST['Pincode']);
$LeadId = trim($_POST['LeadId']);
$Status = $_POST['Status'];
$UserType = $_POST['UserType'];
$Roll = $_POST['Roll'];

$Address2 = addslashes(trim($_POST['Address2']));
$WorkingDetails = addslashes(trim($_POST['WorkingDetails']));
$WorkingAddress = addslashes(trim($_POST['WorkingAddress']));
$Gname = addslashes(trim($_POST['Gname']));
$Gphone = addslashes(trim($_POST['Gphone']));
$Gname2 = addslashes(trim($_POST['Gname2']));
$Gphone2 = addslashes(trim($_POST['Gphone2']));
$Dob = addslashes(trim($_POST['Dob']));
$Area = addslashes(trim($_POST['Area']));
$UnderUser = addslashes(trim($_POST['UnderUser']));

$ProjectType = addslashes(trim($_POST['ProjectType']));
$BeneficiaryId = addslashes(trim($_POST['BeneficiaryId']));
$Taluka = addslashes(trim($_POST['Taluka']));
$Village = addslashes(trim($_POST['Village']));
$District = addslashes(trim($_POST['District']));
$PumpCapacity = addslashes(trim($_POST['PumpCapacity']));
$RooftopPlantCapacity = addslashes(trim($_POST['RooftopPlantCapacity']));

$Lattitude = addslashes(trim($_POST['Lattitude']));
$Longitude = addslashes(trim($_POST['Longitude']));
$OffOnGrid = addslashes(trim($_POST['OffOnGrid']));
$SanctionLoad = addslashes(trim($_POST['SanctionLoad']));
$LoadExtension = addslashes(trim($_POST['LoadExtension']));
$WaterSource = addslashes(trim($_POST['WaterSource']));
$SummerDepth = addslashes(trim($_POST['SummerDepth']));

$WinterDepth = addslashes(trim($_POST['WinterDepth']));
$PumpHead = addslashes(trim($_POST['PumpHead']));
$BgNumber = addslashes(trim($_POST['BgNumber']));
$BgValidity = addslashes(trim($_POST['BgValidity']));
$BgClaimPeriod = addslashes(trim($_POST['BgClaimPeriod']));
$InsuranceNumber = addslashes(trim($_POST['InsuranceNumber']));
$InsuranceAgency = addslashes(trim($_POST['InsuranceAgency']));
$InsuranceValidity = addslashes(trim($_POST['InsuranceValidity']));
$InstallationVendor = addslashes(trim($_POST['InstallationVendor']));
$PumpHeadSelect = addslashes(trim($_POST['PumpHeadSelect']));

$SchemeId = addslashes(trim($_POST['SchemeId']));
$AcDc = addslashes(trim($_POST['AcDc']));
$Surface = addslashes(trim($_POST['Surface']));
$AadharNo = addslashes(trim($_POST['AadharNo']));
$PanNo = addslashes(trim($_POST['PanNo']));

$AccountName = addslashes(trim($_POST['AccountName']));
$BankName = addslashes(trim($_POST['BankName']));
$AccountNo = addslashes(trim($_POST['AccountNo']));
$IfscCode = addslashes(trim($_POST['IfscCode']));
$Branch = addslashes(trim($_POST['Branch']));
$UpiNo = addslashes(trim($_POST['UpiNo']));
$GstNo = addslashes(trim($_POST['GstNo']));

$GumastaNo = addslashes(trim($_POST['GumastaNo']));
$MsmeNo = addslashes(trim($_POST['MsmeNo']));
$InspectionDate = addslashes(trim($_POST['InspectionDate']));
$CommissioningDate = addslashes(trim($_POST['CommissioningDate']));
$CustType = addslashes(trim($_POST['CustType']));
$BoreDia = addslashes(trim($_POST['BoreDia']));

$CompName = addslashes(trim($_POST['CompName']));
$CompAddress = addslashes(trim($_POST['CompAddress']));
$CompPhone = addslashes(trim($_POST['CompPhone']));
$AuthorName = addslashes(trim($_POST['AuthorName']));
  
$CreatedDate = date('Y-m-d');

$randno = rand(1,100);
$src = $_FILES['Photo']['tmp_name'];
$fnm = substr($_FILES["Photo"]["name"], 0,strrpos($_FILES["Photo"]["name"],'.')); 
$fnm = str_replace(" ","_",$fnm);
$ext = substr($_FILES["Photo"]["name"],strpos($_FILES["Photo"]["name"],"."));
$dest = '../../../uploads/'. $randno . "_".$fnm . $ext;
$imagepath =  $randno . "_".$fnm . $ext;
if(move_uploaded_file($src, $dest))
{
$Photo = $imagepath ;
} 
else{
	$Photo = $_POST['OldPhoto'];
}


$randno3 = rand(1,100);
$src3 = $_FILES['AadharCard']['tmp_name'];
$fnm3 = substr($_FILES["AadharCard"]["name"], 0,strrpos($_FILES["AadharCard"]["name"],'.')); 
$fnm3 = str_replace(" ","_",$fnm3);
$ext3 = substr($_FILES["AadharCard"]["name"],strpos($_FILES["AadharCard"]["name"],"."));
$dest3 = '../../../uploads/'. $randno3 . "_".$fnm3 . $ext3;
$imagepath3 =  $randno3 . "_".$fnm3 . $ext3;
if(move_uploaded_file($src3, $dest3))
{
$AadharCard = $imagepath3 ;
} 
else{
$AadharCard = $_POST['AadharCardOld'];
}

$randno4 = rand(1,100);
$src4 = $_FILES['AadharCard2']['tmp_name'];
$fnm4 = substr($_FILES["AadharCard2"]["name"], 0,strrpos($_FILES["AadharCard2"]["name"],'.')); 
$fnm4 = str_replace(" ","_",$fnm4);
$ext4 = substr($_FILES["AadharCard2"]["name"],strpos($_FILES["AadharCard2"]["name"],"."));
$dest4 = '../../../uploads/'. $randno4 . "_".$fnm4 . $ext4;
$imagepath4 =  $randno4 . "_".$fnm4 . $ext4;
if(move_uploaded_file($src4, $dest4))
{
$AadharCard2 = $imagepath4 ;
} 
else{
$AadharCard2 = $_POST['AadharCardOld2'];
}

$randno6 = rand(1,100);
$src6 = $_FILES['PanCard']['tmp_name'];
$fnm6 = substr($_FILES["PanCard"]["name"], 0,strrpos($_FILES["PanCard"]["name"],'.')); 
$fnm6 = str_replace(" ","_",$fnm6);
$ext6 = substr($_FILES["PanCard"]["name"],strpos($_FILES["PanCard"]["name"],"."));
$dest6 = '../../../uploads/'. $randno6 . "_".$fnm6 . $ext6;
$imagepath6 =  $randno6 . "_".$fnm6 . $ext6;
if(move_uploaded_file($src6, $dest6))
{
$PanCard = $imagepath6 ;
} 
else{
$PanCard = $_POST['PanCardOld'];
}

$randno7 = rand(1,100);
$src7 = $_FILES['PanCard2']['tmp_name'];
$fnm7 = substr($_FILES["PanCard2"]["name"], 0,strrpos($_FILES["PanCard2"]["name"],'.')); 
$fnm7 = str_replace(" ","_",$fnm7);
$ext7 = substr($_FILES["PanCard2"]["name"],strpos($_FILES["PanCard2"]["name"],"."));
$dest7 = '../../../uploads/'. $randno7 . "_".$fnm7 . $ext7;
$imagepath7 =  $randno7 . "_".$fnm7 . $ext7;
if(move_uploaded_file($src7, $dest7))
{
$PanCard2 = $imagepath7 ;
} 
else{
$PanCard2 = $_POST['PanCardOld2'];
}

$randno8 = rand(1,100);
$src8 = $_FILES['GstCertificate']['tmp_name'];
$fnm8 = substr($_FILES["GstCertificate"]["name"], 0,strrpos($_FILES["GstCertificate"]["name"],'.')); 
$fnm8 = str_replace(" ","_",$fnm8);
$ext8 = substr($_FILES["GstCertificate"]["name"],strpos($_FILES["GstCertificate"]["name"],"."));
$dest8 = '../../../uploads/'. $randno8 . "_".$fnm8 . $ext8;
$imagepath8 =  $randno8 . "_".$fnm8 . $ext8;
if(move_uploaded_file($src8, $dest8))
{
$GstCertificate = $imagepath8 ;
} 
else{
$GstCertificate = $_POST['OldGstCertificate'];
}



if($id == ''){
 $sql2 = "SELECT * FROM tbl_users WHERE Roll=5 AND Phone='$Phone'";
 $rncnt2 = getRow($sql2);
 if($rncnt2 > 0){
    echo 0;
 }  
 else{
 $sql = "INSERT INTO tbl_users SET SchemeId='$SchemeId',ColgId='$ColgId',Fname='$Fname',Mname='$Mname',Lname='$Lname',Phone='$Phone',EmailId='$EmailId',Phone2='$Phone2',Password='$Password',CountryId='$CountryId',StateId='$StateId',CityId='$CityId',Address='$Address',Pincode='$Pincode',Status='$Status',BranchId='$BranchId',CreatedDate='$CreatedDate',CreatedBy='$user_id',Dob='$Dob',Area='$Area',UserType='$UserType',UnderUser='$UnderUser',ProjectType='$ProjectType',BeneficiaryId='$BeneficiaryId',Taluka='$Taluka',Village='$Village',District='$District',PumpCapacity='$PumpCapacity',RooftopPlantCapacity='$RooftopPlantCapacity',Lattitude='$Lattitude',Longitude='$Longitude',OffOnGrid='$OffOnGrid',SanctionLoad='$SanctionLoad',LoadExtension='$LoadExtension',WaterSource='$WaterSource',SummerDepth='$SummerDepth',WinterDepth='$WinterDepth',PumpHead='$PumpHead',BgNumber='$BgNumber',BgValidity='$BgValidity',BgClaimPeriod='$BgClaimPeriod',InsuranceNumber='$InsuranceNumber',InsuranceAgency='$InsuranceAgency',InsuranceValidity='$InsuranceValidity',InstallationVendor='$InstallationVendor',PumpHeadSelect='$PumpHeadSelect',AcDc='$AcDc',Surface='$Surface',AadharCard='$AadharCard',AadharCard2='$AadharCard2',PanCard='$PanCard',PanCard2='$PanCard2',AadharNo='$AadharNo',PanNo='$PanNo',GstCertificate='$GstCertificate',GstNo='$GstNo',AccountName='$AccountName',BankName='$BankName',AccountNo='$AccountNo',IfscCode='$IfscCode',Branch='$Branch',UpiNo='$UpiNo',GumastaNo='$GumastaNo',Gumasta='$Gumasta',MsmeNo='$MsmeNo',Msme='$Msme',InspectionDate='$InspectionDate',CommissioningDate='$CommissioningDate',CustType='$CustType',BoreDia='$BoreDia',CompName='$CompName',CompAddress='$CompAddress',CompPhone='$CompPhone',AuthorName='$AuthorName',Roll=5";
$conn->query($sql);
$EmpId = mysqli_insert_id($conn);
$CustomerId = "S".$EmpId;
$sql3 = "UPDATE tbl_users SET CustomerId='$CustomerId' WHERE id='$EmpId'";
     $conn->query($sql3);

if($_POST["ProdId"]!=''){
       $number = count($_POST["ProdId"]);
if($number > 0)  
      {  
        for($i=0; $i<$number; $i++)  
          {  
            if(trim($_POST["ProdId"][$i] != ''))  
              {
                 $ProdId = $_POST['ProdId'][$i];
                $ProdName = addslashes(trim($_POST['ProdName'][$i]));
                $Unit = $_POST['Unit'][$i];
                $Qty = $_POST['Qty'][$i];
              
                $sql = "INSERT INTO tbl_cust_product_specification SET CustId='$EmpId',AcDc='$AcDc',Surface='$Surface',PumpCapacity='$PumpCapacity',WaterSource='$WaterSource',BoreDia='$BoreDia',PumpHead='$PumpHead',ProdId='$ProdId',ProdName='$ProdName',Unit='$Unit',Qty='$Qty',CreatedBy='$user_id',CreatedDate='$CreatedDate'";
                $conn->query($sql);
            }
        }
    }
}
echo 1;
}
}
else{
     $sql2 = "SELECT * FROM tbl_users WHERE Roll=5 AND Phone='$Phone' AND id!='$id'";
 $rncnt2 = getRow($sql2);
 if($rncnt2 > 0){
    echo 0;
 }  
 else{
 $sql = "UPDATE tbl_users SET Roll=5,SchemeId='$SchemeId',ColgId='$ColgId',Fname='$Fname',Mname='$Mname',Lname='$Lname',Phone='$Phone',EmailId='$EmailId',Phone2='$Phone2',Password='$Password',CountryId='$CountryId',StateId='$StateId',CityId='$CityId',Address='$Address',Pincode='$Pincode',Status='$Status',BranchId='$BranchId',ModifiedDate='$CreatedDate',ModifiedBy='$user_id',Dob='$Dob',Area='$Area',UserType='$UserType',UnderUser='$UnderUser',ProjectType='$ProjectType',BeneficiaryId='$BeneficiaryId',Taluka='$Taluka',Village='$Village',District='$District',PumpCapacity='$PumpCapacity',RooftopPlantCapacity='$RooftopPlantCapacity',Lattitude='$Lattitude',Longitude='$Longitude',OffOnGrid='$OffOnGrid',SanctionLoad='$SanctionLoad',LoadExtension='$LoadExtension',WaterSource='$WaterSource',SummerDepth='$SummerDepth',WinterDepth='$WinterDepth',PumpHead='$PumpHead',BgNumber='$BgNumber',BgValidity='$BgValidity',BgClaimPeriod='$BgClaimPeriod',InsuranceNumber='$InsuranceNumber',InsuranceAgency='$InsuranceAgency',InsuranceValidity='$InsuranceValidity',InstallationVendor='$InstallationVendor',PumpHeadSelect='$PumpHeadSelect',AcDc='$AcDc',Surface='$Surface',AadharCard='$AadharCard',AadharCard2='$AadharCard2',PanCard='$PanCard',PanCard2='$PanCard2',AadharNo='$AadharNo',PanNo='$PanNo',GstCertificate='$GstCertificate',GstNo='$GstNo',AccountName='$AccountName',BankName='$BankName',AccountNo='$AccountNo',IfscCode='$IfscCode',Branch='$Branch',UpiNo='$UpiNo',GumastaNo='$GumastaNo',Gumasta='$Gumasta',MsmeNo='$MsmeNo',Msme='$Msme',InspectionDate='$InspectionDate',CommissioningDate='$CommissioningDate',CustType='$CustType',BoreDia='$BoreDia',CompName='$CompName',CompAddress='$CompAddress',CompPhone='$CompPhone',AuthorName='$AuthorName' WHERE id='$id'";
$conn->query($sql);

if($_POST["ProdId"]!=''){
$sql = "DELETE FROM tbl_cust_product_specification WHERE CustId='$id'";
$conn->query($sql);

$number = count($_POST["ProdId"]);
if($number > 0)  
      {  
        for($i=0; $i<$number; $i++)  
          {  
            if(trim($_POST["ProdId"][$i] != ''))  
              {
                 $ProdId = $_POST['ProdId'][$i];
               $ProdName = addslashes(trim($_POST['ProdName'][$i]));
                $Unit = $_POST['Unit'][$i];
                $Qty = $_POST['Qty'][$i];
              
                $sql = "INSERT INTO tbl_cust_product_specification SET CustId='$id',AcDc='$AcDc',Surface='$Surface',PumpCapacity='$PumpCapacity',WaterSource='$WaterSource',BoreDia='$BoreDia',PumpHead='$PumpHead',ProdId='$ProdId',ProdName='$ProdName',Unit='$Unit',Qty='$Qty',CreatedBy='$user_id',CreatedDate='$CreatedDate'";
                $conn->query($sql);
            }
        }
    }
}
echo 1;
}
}
}

if($_POST['action'] == 'deletePhoto'){
   	$id = $_POST['id'];
    $Photo = $_POST['Photo'];
    $q = "UPDATE tbl_users SET Photo='' WHERE id=$id";
    $conn->query($q);
    echo "File Deleted Successfully";
}

if($_POST['action'] == 'getCustDetails'){
    $id = $_POST['id'];
    $sql = "SELECT * FROM tbl_users WHERE id=$id";
    $row = getRecord($sql);
    echo json_encode($row);
}

if($_POST['action'] == 'chageSurveyDetails'){
    $id = $_POST['id'];
    $val = $_POST['val'];
    $sql = "UPDATE tbl_users SET SurveyDetails='$val' WHERE id=$id";
    $conn->query($sql);
    echo 1;
}
