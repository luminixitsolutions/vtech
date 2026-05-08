<?php
include_once '../config.php';
if($_POST['action'] == 'Add'){
$ProductName = addslashes(trim($_POST['ProductName']));
$Details = addslashes(trim($_POST['Details']));
$SubCatId = $_POST['SubCatId'];
$BrandId = $_POST['BrandId'];
$CatId = $_POST['CatId'];
$BatchCode = addslashes(trim($_POST['BatchCode']));
$NameSize = $_POST['NameSize'];
$Size = $_POST['Size'];
$NameColor = $_POST['NameColor'];
//$Color2 = implode(",",$_POST['Color']);
$NameStorage = $_POST['NameStorage'];
$Storage = $_POST['Storage'];
$NameRam = $_POST['NameRam'];
$Ram = $_POST['Ram'];
$MinPrice = $_POST['MinPrice'];
$MaxPrice = $_POST['MaxPrice'];
$OfferPrice = $_POST['OfferPrice'];
$OfferPer = $_POST['OfferPer'];
$Cashback = $_POST['Cashback'];
$Featured = $_POST['Featured'];
$FreeShipping = $_POST['FreeShipping'];
$Bestseller = $_POST['Bestseller'];
$ItemStock = $_POST['ItemStock'];
$Subscription = $_POST['Subscription'];
$Stock = $_POST['Stock'];
$Discount = $_POST['Discount'];
$Tax = $_POST['Tax'];
$VedId = $_POST['VedId'];
$Pune = $_POST['Pune'];
$Dhule = $_POST['Dhule'];
$Ahemadnagar = $_POST['Ahemadnagar'];

$Shirpur = $_POST['Shirpur'];
$Mumbai = $_POST['Mumbai'];
$Panvel = $_POST['Panvel'];

$Highlight1 = addslashes(trim($_POST['Highlight1']));
$Highlight2 = addslashes(trim($_POST['Highlight2']));
$Highlight3 = addslashes(trim($_POST['Highlight3']));
$Highlight4 = addslashes(trim($_POST['Highlight4']));
$Highlight5 = addslashes(trim($_POST['Highlight5']));

$MetaTag = addslashes(trim($_POST['MetaTag']));
$MetaDesc = addslashes(trim($_POST['MetaDesc']));
$Keywords = addslashes(trim($_POST['Keywords']));
$DeliveryInfo = addslashes(trim($_POST['DeliveryInfo']));
$Offers = addslashes(trim($_POST['Offers']));
if($_POST['DdsOffers'] == ''){
   $DdsOffers = 0; 
}
else{
$DdsOffers = implode(",",$_POST['DdsOffers']);    
}
$MinQty = addslashes(trim($_POST['MinQty']));
 //$Status = $_POST['Status'];
if($Stock == 1){
    $Status = 1;
}
else{
    $Status = 0;
}
$CreatedDate = date('Y-m-d');

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


function RandomStringGenerator($n)
{
    $generated_string = "";   
    $domain = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
    $len = strlen($domain);
    for ($i = 0; $i < $n; $i++)
    {
        $index = rand(0, $len - 1);
        $generated_string = $generated_string . $domain[$index];
    }
    return $generated_string;
} 
$n = 10;
$Code = RandomStringGenerator($n); 
$sql = "INSERT INTO products SET ProductName='$ProductName',Details='$Details',CatId='$CatId',SubCatId='$SubCatId',BrandId='$BrandId',BatchCode='$BatchCode',code='$Code',NameSize='$NameSize',Size='$Size',NameColor='$NameColor',Color='$Color2',NameStorage='$NameStorage',Storage='$Storage',NameRam='$NameRam',Ram='$Ram',MinPrice='$MinPrice',MaxPrice='$MaxPrice',OfferPrice='$OfferPrice',OfferPer='$OfferPer',Tax='$Tax',Cashback='$Cashback',Featured='$Featured',FreeShipping='$FreeShipping',Bestseller='$Bestseller',Photo='$Photo',ItemStock='$ItemStock',Stock='$Stock',VedId='$VedId',Status='$Status',DeliveryInfo='$DeliveryInfo',Offers='$Offers',MetaTag='$MetaTag',MetaDesc='$MetaDesc',Keywords='$Keywords',CreatedDate='$CreatedDate',Highlight1='$Highlight1',Highlight2='$Highlight2',Highlight3='$Highlight3',Highlight4='$Highlight4',Highlight5='$Highlight5',Discount='$Discount',Subscription='$Subscription',Pune='$Pune',Dhule='$Dhule',Ahemadnagar='$Ahemadnagar',Shirpur='$Shirpur',Mumbai='$Mumbai',Panvel='$Panvel',DdsOffers='$DdsOffers',MinQty='$MinQty'";
$conn->query($sql);
$ProdId = mysqli_insert_id($conn);
if($_POST["DdsOffers"] != ''){
$number44 = count($_POST["DdsOffers"]);
 if($number44 > 0)  
            {  
                for($i=0; $i<$number44; $i++)  
                {  
                     if($_POST["DdsOffers"][$i] != '')  
                     {
                       $DdsOffers = $_POST['DdsOffers'][$i];
                       $sql = "INSERT INTO tbl_dds_offers SET ProdId='$ProdId',DdsOffers='$DdsOffers'";
                       $conn->query($sql);
                     }
                }
            }
}
/*$number22 = count($_POST["Color"]);
 if($number22 > 0)  
            {  
                for($i=0; $i<$number22; $i++)  
                {  
                     if($_POST["Color"][$i] != '' && $_FILES["ColorPhoto"]["name"][$i] != '')  
                     {
                       $Color = $_POST['Color'][$i];
                       $randno = rand(1,100);
                       $src = $_FILES['ColorPhoto']['tmp_name'][$i];
                        $fnm = substr($_FILES["ColorPhoto"]["name"][$i], 0,strrpos($_FILES["ColorPhoto"]["name"][$i],'.')); 
                        $fnm = str_replace(" ","_",$fnm);
                        $ext = substr($_FILES["ColorPhoto"]["name"][$i],strpos($_FILES["ColorPhoto"]["name"][$i],"."));
                        $dest = '../../uploads/'. $randno . "_".$fnm . $ext;
                        $imagepath =  $randno . "_".$fnm . $ext;
                        if(move_uploaded_file($src, $dest))
                        {
                        $ColorPhoto = $imagepath ;
                        } 

                     
                       
                        $sql22 = "INSERT INTO temp_color SET ProdId = '$ProdId',Status='1',Color='$Color',Photo='$ColorPhoto'";
                        $conn->query($sql22);
                     }
                }
            }*/        

$number = count($_POST["Min_Price"]);
  if($number > 0)  
            {  
                for($i=0; $i<$number; $i++)  
                {  
                     if(trim($_POST["Min_Price"][$i] != ''))  
                     {
                      
                       $srno = $i+1;
                       $AttrNameSize = $_POST['AttrNameSize'][$i];
                       $AttrValueSize = $_POST['AttrValueSize'][$i];
                       $AttrNameRam = $_POST['AttrNameRam'][$i];
                       $AttrValueRam = $_POST['AttrValueRam'][$i];
                       $AttrNameStorage = $_POST['AttrNameStorage'][$i];
                       $AttrValueStorage = $_POST['AttrValueStorage'][$i];
                       $Min_Price = addslashes($_POST['Min_Price'][$i]);
                        $Max_Price = addslashes($_POST['Max_Price'][$i]);
                        $Offer_Price = addslashes($_POST['Offer_Price'][$i]);
                        $Offer_Per = addslashes($_POST['Offer_Per'][$i]);
                        $Item_Stock = addslashes($_POST['Item_Stock'][$i]);
                        $PrStock = addslashes($_POST['PrStock'][$i]);
 $sql = "INSERT INTO related_products SET srno='$srno',ProdId='$ProdId',AttrNameSize='$AttrNameSize',AttrValueSize='$AttrValueSize',AttrNameRam='$AttrNameRam',AttrValueRam='$AttrValueRam',AttrNameStorage='$AttrNameStorage',AttrValueStorage='$AttrValueStorage',MinPrice='$Min_Price',MaxPrice='$Max_Price',OfferPrice='$Offer_Price',OfferPer='$Offer_Per',ItemStock='$Item_Stock',Stock='$Stock',Status='$Status'";
 $conn->query($sql);
                     }
                }
             }  
 if (isset($_FILES['Files'])) {
    $errors = array();
    foreach ($_FILES['Files']['tmp_name'] as $key => $tmp_name) {
        $file_name = $key . $_FILES['Files']['name'][$key];
        $file_size = $_FILES['Files']['size'][$key];
        $file_tmp = $_FILES['Files']['tmp_name'][$key];
        $file_type = $_FILES['Files']['type'][$key];
        $FileName = $_FILES['Files']['name'][$key];
        
        if ($file_size > 2097152) {
            $errors[] = 'File size must be less than 2 MB';
        }
        if ($file_name == '0' || $file_size == '0') {} else {
             $query = "INSERT into product_images SET ProductId='$ProdId',Files='$file_name',FileName='$FileName'";
            $desired_dir = "../../uploads/";
            if (empty($errors) == true) {
                if (is_dir($desired_dir) == false) {
                    mkdir("$desired_dir", 0700); // Create directory if it does not exist
                }
                if (is_dir("$desired_dir/" . $file_name) == false) {
                    move_uploaded_file($file_tmp, "../../uploads/" . $file_name);
                } else {
                    // rename the file if another one exist
                    $new_dir = "../../uploads/" . $file_name . time();
                    rename($file_tmp, $new_dir);
                }
                $conn->query($query);
            } else {
                print_r($errors);
            }
        }
        if (empty($error)) {
           
           
        }
    }
}

?>
<script type="text/javascript">
    alert("New Product Added Successfully!");
    window.location.href="../view-shop-products.php";
</script>
<?php
}


if($_POST['action'] == 'Edit'){
    $id = $_POST['id'];
$ProductName = addslashes(trim($_POST['ProductName']));
$Details = addslashes(trim($_POST['Details']));
$SubCatId = $_POST['SubCatId'];
$BrandId = $_POST['BrandId'];
$CatId = $_POST['CatId'];
$BatchCode = addslashes(trim($_POST['BatchCode']));
$NameSize = $_POST['NameSize'];
$Size = $_POST['Size'];
$NameColor = $_POST['NameColor'];
//$Color2 = implode(",",$_POST['Color']);
$NameStorage = $_POST['NameStorage'];
$Storage = $_POST['Storage'];
$NameRam = $_POST['NameRam'];
$Ram = $_POST['Ram'];
$MinPrice = $_POST['MinPrice'];
$MaxPrice = $_POST['MaxPrice'];
$OfferPrice = $_POST['OfferPrice'];
$OfferPer = $_POST['OfferPer'];
$Cashback = $_POST['Cashback'];
$Featured = $_POST['Featured'];
$FreeShipping = $_POST['FreeShipping'];
$Bestseller = $_POST['Bestseller'];
$ItemStock = $_POST['ItemStock'];
$Subscription = $_POST['Subscription'];
$Stock = $_POST['Stock'];
$VedId = $_POST['VedId'];
$Tax = $_POST['Tax'];
$Pune = $_POST['Pune'];
$Dhule = $_POST['Dhule'];
$Ahemadnagar = $_POST['Ahemadnagar'];
$Shirpur = $_POST['Shirpur'];
$Mumbai = $_POST['Mumbai'];
$Panvel = $_POST['Panvel'];
$Highlight1 = addslashes(trim($_POST['Highlight1']));
$Highlight2 = addslashes(trim($_POST['Highlight2']));
$Highlight3 = addslashes(trim($_POST['Highlight3']));
$Highlight4 = addslashes(trim($_POST['Highlight4']));
$Highlight5 = addslashes(trim($_POST['Highlight5']));
$MetaTag = addslashes(trim($_POST['MetaTag']));
$MetaDesc = addslashes(trim($_POST['MetaDesc']));
$Keywords = addslashes(trim($_POST['Keywords']));
$DeliveryInfo = addslashes(trim($_POST['DeliveryInfo']));
$Offers = addslashes(trim($_POST['Offers']));
$MinQty = addslashes(trim($_POST['MinQty']));
//$Status = $_POST['Status'];
if($Stock == 1){
    $Status = 1;
}
else{
    $Status = 0;
}
$Discount = $_POST['Discount'];
$CreatedDate = date('Y-m-d');
if($_POST['DdsOffers'] == ''){
   $DdsOffers = 0; 
}
else{
$DdsOffers = implode(",",$_POST['DdsOffers']);    
}


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


$sql = "UPDATE products SET ProductName='$ProductName',Details='$Details',CatId='$CatId',SubCatId='$SubCatId',BrandId='$BrandId',BatchCode='$BatchCode',NameSize='$NameSize',Size='$Size',NameColor='$NameColor',Color='$Color2',NameStorage='$NameStorage',Storage='$Storage',NameRam='$NameRam',Ram='$Ram',MinPrice='$MinPrice',MaxPrice='$MaxPrice',OfferPrice='$OfferPrice',OfferPer='$OfferPer',Tax='$Tax',Cashback='$Cashback',Featured='$Featured',FreeShipping='$FreeShipping',Bestseller='$Bestseller',Photo='$Photo',ItemStock='$ItemStock',Stock='$Stock',Status='$Status',VedId='$VedId',DeliveryInfo='$DeliveryInfo',Offers='$Offers',MetaTag='$MetaTag',MetaDesc='$MetaDesc',Keywords='$Keywords',ModifiedDate='$CreatedDate',Highlight1='$Highlight1',Highlight2='$Highlight2',Highlight3='$Highlight3',Highlight4='$Highlight4',Highlight5='$Highlight5',Discount='$Discount',Subscription='$Subscription',Pune='$Pune',Dhule='$Dhule',Ahemadnagar='$Ahemadnagar',Shirpur='$Shirpur',Mumbai='$Mumbai',Panvel='$Panvel',DdsOffers='$DdsOffers',MinQty='$MinQty' WHERE id='$id'";
$conn->query($sql);
$ProdId = $_POST['id'];

if($_POST["DdsOffers"] != ''){
$sql31 = "DELETE FROM tbl_dds_offers WHERE ProdId='$ProdId'";
$conn->query($sql31);
$number44 = count($_POST["DdsOffers"]);
 if($number44 > 0)  
            {  
                for($i=0; $i<$number44; $i++)  
                {  
                     if($_POST["DdsOffers"][$i] != '')  
                     {
                       $DdsOffers = $_POST['DdsOffers'][$i];
                       $sql = "INSERT INTO tbl_dds_offers SET ProdId='$ProdId',DdsOffers='$DdsOffers'";
                       $conn->query($sql);
                     }
                }
            }
}
/*$sql31 = "DELETE FROM temp_color WHERE ProdId='$ProdId'";
$conn->query($sql31);

$number22 = count($_POST["Color"]);
 if($number22 > 0)  
            {  
                for($i=0; $i<$number22; $i++)  
                {  
                    if($_POST['ColorPhotoName'][$i] == ''){
                       $GetColorPhoto = $_FILES["ColorPhoto"]["name"][$i];
                    }
                    else{
                        $GetColorPhoto = $_POST['ColorPhotoName'][$i];
                    }
                     if($_POST["Color"][$i] != '' && $GetColorPhoto !='')  
                     {
                       $Color = $_POST['Color'][$i];
                       
                        $randno = rand(1,100);
                       $src = $_FILES['ColorPhoto']['tmp_name'][$i];
                        $fnm = substr($_FILES["ColorPhoto"]["name"][$i], 0,strrpos($_FILES["ColorPhoto"]["name"][$i],'.')); 
                        $fnm = str_replace(" ","_",$fnm);
                        $ext = substr($_FILES["ColorPhoto"]["name"][$i],strpos($_FILES["ColorPhoto"]["name"][$i],"."));
                        $dest = '../../uploads/'. $randno . "_".$fnm . $ext;
                        $imagepath =  $randno . "_".$fnm . $ext;
                        if(move_uploaded_file($src, $dest))
                        {
                        $ColorPhoto = $imagepath ;
                        } 
                        else{
                        $ColorPhoto = $_POST['ColorPhotoName'][$i];
                    }
                       
                       
                         $sql22 = "INSERT INTO temp_color SET ProdId = '$ProdId',Status='1',Color='$Color',Photo='$ColorPhoto'";
                        $conn->query($sql22);
                       
                     }
                }
            } */        


$sql3 = "DELETE FROM related_products WHERE ProdId='$ProdId'";
$conn->query($sql3);
 $number = count($_POST["Min_Price"]);
  if($number > 0)  
            {  
                for($i=0; $i<$number; $i++)  
                {  
                     if(trim($_POST["Min_Price"][$i] != ''))  
                     {
                      
                       $srno = $i+1;
                      $AttrNameSize = $_POST['AttrNameSize'][$i];
                       $AttrValueSize = $_POST['AttrValueSize'][$i];
                       $AttrNameRam = $_POST['AttrNameRam'][$i];
                       $AttrValueRam = $_POST['AttrValueRam'][$i];
                       $AttrNameStorage = $_POST['AttrNameStorage'][$i];
                       $AttrValueStorage = $_POST['AttrValueStorage'][$i];
                       $Min_Price = addslashes($_POST['Min_Price'][$i]);
                        $Max_Price = addslashes($_POST['Max_Price'][$i]);
                        $Offer_Price = addslashes($_POST['Offer_Price'][$i]);
                        $Offer_Per = addslashes($_POST['Offer_Per'][$i]);
                        $Item_Stock = addslashes($_POST['Item_Stock'][$i]);
                        $PrStock = addslashes($_POST['PrStock'][$i]);
 $sql = "INSERT INTO related_products SET srno='$srno',ProdId='$ProdId',AttrNameSize='$AttrNameSize',AttrValueSize='$AttrValueSize',AttrNameRam='$AttrNameRam',AttrValueRam='$AttrValueRam',AttrNameStorage='$AttrNameStorage',AttrValueStorage='$AttrValueStorage',MinPrice='$Min_Price',MaxPrice='$Max_Price',OfferPrice='$Offer_Price',OfferPer='$Offer_Per',ItemStock='$Item_Stock',Stock='$Stock',Status='$Status'";
 $conn->query($sql);
                     }
                }
             }  

 if (isset($_FILES['Files'])) {
    $errors = array();
    foreach ($_FILES['Files']['tmp_name'] as $key => $tmp_name) {
        $file_name = $key . $_FILES['Files']['name'][$key];
        $file_size = $_FILES['Files']['size'][$key];
        $file_tmp = $_FILES['Files']['tmp_name'][$key];
        $file_type = $_FILES['Files']['type'][$key];
        $FileName = $_FILES['Files']['name'][$key];
        
        if ($file_size > 2097152) {
            $errors[] = 'File size must be less than 2 MB';
        }
        if ($file_name == '0' || $file_size == '0') {} else {
             $query = "INSERT into product_images SET ProductId='$ProdId',Files='$file_name',FileName='$FileName'";
            $desired_dir = "../../uploads/";
            if (empty($errors) == true) {
                if (is_dir($desired_dir) == false) {
                    mkdir("$desired_dir", 0700); // Create directory if it does not exist
                }
                if (is_dir("$desired_dir/" . $file_name) == false) {
                    move_uploaded_file($file_tmp, "../../uploads/" . $file_name);
                } else {
                    // rename the file if another one exist
                    $new_dir = "../../uploads/" . $file_name . time();
                    rename($file_tmp, $new_dir);
                }
                $conn->query($query);
            } else {
                print_r($errors);
            }
        }
        if (empty($error)) {
           
           
        }
    }
}
?>
<script type="text/javascript">
    alert("Product Update Successfully!");
    window.location.href="../view-shop-products.php";
</script>
<?php
}

if($_POST['action'] == 'deletePhoto'){
    $id = $_POST['id'];
    $Photo = $_POST['Photo'];
        $q = "UPDATE products SET Photo='' WHERE id=$id";
        $conn->query($q);
        $src = "../../uploads/$Photo";
        unlink($src);

    echo "Product Photo Delete Successfully";
} 
if($_POST['action'] == 'deletePhoto2'){
    $id = $_POST['id'];
    $pid = $_POST['pid'];
    $Photo = $_POST['Photo'];
        $q = "DELETE FROM product_images WHERE id=$id AND ProductId='$pid'";
        $conn->query($q);
        $src = "../../uploads/$Photo";
        unlink($src);

    echo "Product Photo Delete Successfully";
}

if($_POST['action'] == 'showProdImages'){ 
    $id = $_POST['id'];
  $sql2 = "SELECT * FROM product_images WHERE ProductId='$id'";
  $res2 = $conn->query($sql2);
  $rncnt = mysqli_num_rows($res2);
  if($rncnt > 0){
    while($row2 = $res2->fetch_assoc()){?>
    <input type="hidden" name="OldMulImage" id="OldMulImage<?php echo $row2["id"]; ?>" value="<?php echo $row2["Files"]; ?>">
<div class="ui-feed-icon-container float-left pt-2 mr-3 mb-3"><a href="javascript:void(0)" class="ui-icon ui-feed-icon ion ion-md-close bg-secondary text-white" onclick="delete_photo2(<?php echo $row2["id"]; ?>,<?php echo $_POST["id"]; ?>)"></a><img src="../uploads/<?php echo $row2['Files'];?>" alt="" class="img-fluid ticket-file-img" style="width: 64px;height: 64px;"></div>
<?php }} }?>



<?php
if($_POST['action'] == 'view_attr'){
    $id = $_POST['id'];?>
       <div class="form-row" id="row1">
 <?php 
  $aid = $row['id'];
  $q4 = "select * from attribute_value WHERE AttrNameId='1' AND Status='1'";
    $r4 = $conn->query($q4); 
    $rncnt7 = mysqli_num_rows($r4);
  if($rncnt7 > 0){           
    ?>   
<div class="form-group col-md-3">
    <input type="hidden" name="AttrNameSize[]" id="AttrNameSize1" value="1">
<label class="form-label">Size <span class="text-danger">*</span></label>
        <select class="form-control" name="AttrValueSize[]" id="AttrValueSize1" >
<option selected="" >Select Size</option>
    <?php 
        while($rw = $r4->fetch_assoc())
             {
                                ?>

                                                <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>

                                              <?php } ?>
</select>
</div>
<?php } 
$aid = $row['id'];
  $q3 = "select * from attribute_value WHERE AttrNameId='4' AND Status='1'";
  $r3 = $conn->query($q3);
   $rncnt6 = mysqli_num_rows($r3);
  if($rncnt6 > 0){?>
<div class="form-group col-md-3">
    <input type="hidden" name="AttrNameRam[]" id="AttrNameRam1" value="4">
<label class="form-label">Ram <span class="text-danger">*</span></label>
        <select class="form-control" name="AttrValueRam[]" id="AttrValueRam1">
<option selected="" >Select Ram</option>
    <?php 
    
                                        while($rw = $r3->fetch_assoc())
                                    {
                                ?>

                                                <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>

                                              <?php } ?>
</select>
</div>
<?php }  $aid = $row['id'];
$q2 = "select * from attribute_value WHERE AttrNameId='2' AND Status='1'";
$r2 = $conn->query($q2);
$rncnt5 = mysqli_num_rows($r2);
  if($rncnt5 > 0){
?>
<div class="form-group col-md-3">
    <input type="hidden" name="AttrNameStorage[]" id="AttrNameStorage1" value="2">
<label class="form-label">Storage <span class="text-danger">*</span></label>
        <select class="form-control" name="AttrValueStorage[]" id="AttrValueStorage1">
<option selected="" >Select Storage</option>
    <?php 
   
                                        while($rw = $r2->fetch_assoc())
                                    {
                                ?>

                                                <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>

                                              <?php } ?>
</select>
</div>
<?php } ?>   

      <input type="hidden" class="form-control" name="srno[]" id="srno1" value="1">
  <div class="form-row col-md-4">
    <div class="form-group col-lg-6">
<label class="form-label">Market Price<span class="text-danger">*</span></label>
<div class="input-group">
<div class="input-group-prepend">
<div class="input-group-text">&#8377;</div>
</div>
<input type="text" id="MaxPrice1" name="Max_Price[]" class="form-control" value="" onKeyPress="return isNumberKey(event)" oninput="calculate2(document.getElementById('MinPrice1').value,document.getElementById('MaxPrice1').value,document.getElementById('srno1').value)">
<div class="clearfix"></div>
</div>
</div>
<div class="form-group col-lg-6">
<label class="form-label">Our Price<span class="text-danger">*</span></label>
<div class="input-group">
<div class="input-group-prepend">
<div class="input-group-text">&#8377;</div>
</div>
<input type="text" id="MinPrice1" name="Min_Price[]" class="form-control" value="" onKeyPress="return isNumberKey(event)" oninput="calculate2(document.getElementById('MinPrice1').value,document.getElementById('MaxPrice1').value,document.getElementById('srno1').value)">
<div class="clearfix"></div>
</div>
</div>

</div>
 <div class="form-row col-md-3">
<div class="form-group col-lg-6">
<label class="form-label">Offer Price<span class="text-danger">*</span></label>
<div class="input-group">
<div class="input-group-prepend">
<div class="input-group-text">&#8377;</div>
</div>
<input type="text" id="OfferPrice1" name="Offer_Price[]" class="form-control" value="" readonly="">
<div class="clearfix"></div>
</div>
</div>
<div class="form-group col-lg-6">
<label class="form-label">Offer %<span class="text-danger">*</span></label>
<div class="input-group">
<input type="text" id="OfferPer1" name="Offer_Per[]" class="form-control" value="" readonly="">
<div class="clearfix"></div>
<div class="input-group-prepend">
<div class="input-group-text">%</div>
</div>
</div>
</div>
</div>
<div class="form-group col-lg-2">
<label class="form-label">Product Stock<span class="text-danger">*</span></label>
<select class="form-control" name="PrStock[]" >
<option value="1">Instock</option>
<option value="0">Out of stock</option>
</select>
</div>
<!-- <div class="form-row col-md-3">
<div class="form-group col-lg-6">
<label class="form-label">Items in stock<span class="text-danger">*</span></label>
 <input type="number" min='0' name="Item_Stock[]" class="form-control" value="" >
<div class="clearfix"></div>
</div> 


</div>-->
<!--  <div class="form-row">
<div class="form-group col">
  <label class="form-label">Product Image (Multiple) <span class="text-danger">(File size must be less than 2 MB)</span></label>
<label class="custom-file">
<input type="file" class="custom-file-input" id="Photo2" name="Files[]" style="opacity: 1;" multiple="" accept="image/jpg, image/jpeg, image/png">
<span class="custom-file-label"></span>
</label>
</div>
</div> -->
<div class="form-group col-md-3">
    <label class="form-label d-none d-md-block">&nbsp;</label><button type="button" class="btn btn-primary" id="add"><i class="ion ion-md-add"></i>&nbsp; Add More</button></div>


</div>
<script type="text/javascript">
      function isNumberKey(evt){ 
    var charCode = (evt.which) ? evt.which : evt.keyCode
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}
    $(document).ready(function(){
    var i=1;  
      $('#add').click(function(){  
           i++;  
             var action = "getMoreAttributes";
             var CatId = $('#CatId').val();
    $.ajax({
    url:"ajax_files/ajax_shop_products.php",
    method:"POST",
    data : {action:action,id:i,CatId:CatId},
    success:function(data)
    {

       $('#dynamic_field').append(data);
    }
    });
           
      });  
      $(document).on('click', '.btn_remove', function(){  
           var button_id = $(this).attr("id");   
           $('#row'+button_id+'').remove();
           $('#hr'+button_id+'').remove();  

      });
});
      function calculate2(MinPrice,MaxPrice,srno){
    var OfferPrice = Number(MaxPrice) - Number(MinPrice);
    $('#OfferPrice'+srno).val(parseFloat(OfferPrice).toFixed(2));
     var perc="";
            if(isNaN(MinPrice) || isNaN(MaxPrice)){
                perc=" ";
               }else{
               perc = Math.trunc(((MaxPrice-MinPrice)/MaxPrice * 100));
               }
            
            $('#OfferPer'+srno).val(perc);
}
    
</script>
<?php }


if($_POST['action'] == 'getMoreAttributes'){
 $i = $_POST['id'];
 $CatId = $_POST['CatId'];?>
 <div class="progress" id="hr<?php echo $i;?>" style="height: 0.15rem;">
<div class="progress-bar bg-success" style="width: 100%"></div>
</div>
       <div class="form-row" id="row<?php echo $i;?>" style="padding-top: 10px;">
 <?php 
  $aid = $row['id'];
  $q4 = "select * from attribute_value WHERE AttrNameId='1' AND Status='1'";
    $r4 = $conn->query($q4); 
    $rncnt7 = mysqli_num_rows($r4);
  if($rncnt7 > 0){           
    ?>   
<div class="form-group col-md-3">
    <input type="hidden" name="AttrNameSize[]" id="AttrNameSize<?php echo $i;?>" value="1">
<label class="form-label">Size <span class="text-danger">*</span></label>
        <select class="form-control" name="AttrValueSize[]" id="AttrValueSize<?php echo $i;?>" >
<option selected="" >Select Size</option>
    <?php 
        while($rw = $r4->fetch_assoc())
             {
                                ?>

                                                <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>

                                              <?php } ?>
</select>
</div>
<?php } 
$aid = $row['id'];
  $q3 = "select * from attribute_value WHERE AttrNameId='4' AND Status='1'";
  $r3 = $conn->query($q3);
   $rncnt6 = mysqli_num_rows($r3);
  if($rncnt6 > 0){?>
<div class="form-group col-md-3">
    <input type="hidden" name="AttrNameRam[]" id="AttrNameRam<?php echo $i;?>" value="4">
<label class="form-label">Ram <span class="text-danger">*</span></label>
        <select class="form-control" name="AttrValueRam[]" id="AttrValueRam<?php echo $i;?>">
<option selected="" >Select Ram</option>
    <?php 
    
                                        while($rw = $r3->fetch_assoc())
                                    {
                                ?>

                                                <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>

                                              <?php } ?>
</select>
</div>
<?php }  $aid = $row['id'];
$q2 = "select * from attribute_value WHERE AttrNameId='2' AND Status='1'";
$r2 = $conn->query($q2);
$rncnt5 = mysqli_num_rows($r2);
  if($rncnt5 > 0){
?>
<div class="form-group col-md-3">
    <input type="hidden" name="AttrNameStorage[]" id="AttrNameStorage<?php echo $i;?>" value="2">
<label class="form-label">Storage <span class="text-danger">*</span></label>
        <select class="form-control" name="AttrValueStorage[]" id="AttrValueStorage<?php echo $i;?>">
<option selected="">Select Storage</option>
    <?php 
   
                                        while($rw = $r2->fetch_assoc())
                                    {
                                ?>

                                                <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>

                                              <?php } ?>
</select>
</div>
<?php } ?>   
<input type="hidden" class="form-control" name="srno[]" id="srno<?php echo $i;?>" value="<?php echo $i;?>">
  <div class="form-row col-md-4">
    <div class="form-group col-lg-6">
<label class="form-label">Market Price<span class="text-danger">*</span></label>
<div class="input-group">
<div class="input-group-prepend">
<div class="input-group-text">&#8377;</div>
</div>
<input type="text" id="MaxPrice<?php echo $i;?>" name="Max_Price[]" class="form-control" value="" onKeyPress="return isNumberKey(event)" oninput="calculate2(document.getElementById('MinPrice<?php echo $i;?>').value,document.getElementById('MaxPrice<?php echo $i;?>').value,document.getElementById('srno<?php echo $i;?>').value)">
<div class="clearfix"></div>
</div>
</div>
<div class="form-group col-lg-6">
<label class="form-label">Our Price<span class="text-danger">*</span></label>
<div class="input-group">
<div class="input-group-prepend">
<div class="input-group-text">&#8377;</div>
</div>
<input type="text" id="MinPrice<?php echo $i;?>" name="Min_Price[]" class="form-control" value="" onKeyPress="return isNumberKey(event)" oninput="calculate2(document.getElementById('MinPrice<?php echo $i;?>').value,document.getElementById('MaxPrice<?php echo $i;?>').value,document.getElementById('srno<?php echo $i;?>').value)">
<div class="clearfix"></div>
</div>
</div>

</div>
 <div class="form-row col-md-3">
<div class="form-group col-lg-6">
<label class="form-label">Offer Price<span class="text-danger">*</span></label>
<div class="input-group">
<div class="input-group-prepend">
<div class="input-group-text">&#8377;</div>
</div>
<input type="text" id="OfferPrice<?php echo $i;?>" name="Offer_Price[]" class="form-control" value="" readonly="">
<div class="clearfix"></div>
</div>
</div>
<div class="form-group col-lg-6">
<label class="form-label">Offer %<span class="text-danger">*</span></label>
<div class="input-group">
<input type="text" id="OfferPer<?php echo $i;?>" name="Offer_Per[]" class="form-control" value="" readonly="">
<div class="clearfix"></div>
<div class="input-group-prepend">
<div class="input-group-text">%</div>
</div>
</div>
</div>
</div>
<div class="form-group col-lg-2">
<label class="form-label">Product Stock<span class="text-danger">*</span></label>
<select class="form-control" name="PrStock[]" id="Stock<?php echo $i;?>">
<option value="1">Instock</option>
<option value="0">Out of stock</option>
</select>
</div>
<!--<div class="form-row col-md-4">
 <div class="form-group col-lg-6">
<label class="form-label">Items in stock<span class="text-danger">*</span></label>
 <input type="number" min='0' name="Item_Stock[]" class="form-control" value="" >
<div class="clearfix"></div>
</div>
</div>-->
<div class="form-group col-md-3">
<label class="form-label d-none d-md-block">&nbsp;</label>
<button type="button" id="<?php echo $i;?>" class="btn btn-outline-danger btn_remove"><i class="ion ion-md-close"></i>&nbsp;Remove</button></div>
</div>
<?php } 


if($_POST['action'] == 'edit_view_attr'){
    $id = $_POST['id'];
     $rncnt = $_POST['rncnt']+1;?>
      <input type="hidden" name="rncnt" id="rncnt2" value="<?php echo $rncnt; ?>">
       <div class="form-row" id="row<?php echo $rncnt;?>" style="padding-top: 10px;">
  <?php 
  $aid = $row['id'];
  $q4 = "select * from attribute_value WHERE AttrNameId='1' AND Status='1'";
    $r4 = $conn->query($q4); 
    $rncnt7 = mysqli_num_rows($r4);
  if($rncnt7 > 0){           
    ?>   
<div class="form-group col-md-3">
    <input type="hidden" name="AttrNameSize[]" id="AttrNameSize<?php echo $rncnt;?>" value="1">
<label class="form-label">Size <span class="text-danger">*</span></label>
        <select class="form-control" name="AttrValueSize[]" id="AttrValueSize<?php echo $rncnt;?>" >
<option selected="" >Select Size</option>
    <?php 
        while($rw = $r4->fetch_assoc())
             {
                                ?>

                                                <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>

                                              <?php } ?>
</select>
</div>
<?php } 
$aid = $row['id'];
  $q3 = "select * from attribute_value WHERE AttrNameId='4' AND Status='1'";
  $r3 = $conn->query($q3);
   $rncnt6 = mysqli_num_rows($r3);
  if($rncnt6 > 0){?>
<div class="form-group col-md-3">
    <input type="hidden" name="AttrNameRam[]" id="AttrNameRam<?php echo $rncnt;?>" value="4">
<label class="form-label">Ram <span class="text-danger">*</span></label>
        <select class="form-control" name="AttrValueRam[]" id="AttrValueRam<?php echo $rncnt;?>">
<option selected="">Select Ram</option>
    <?php 
    
                                        while($rw = $r3->fetch_assoc())
                                    {
                                ?>

                                                <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>

                                              <?php } ?>
</select>
</div>
<?php }  $aid = $row['id'];
$q2 = "select * from attribute_value WHERE AttrNameId='2' AND Status='1'";
$r2 = $conn->query($q2);
$rncnt5 = mysqli_num_rows($r2);
  if($rncnt5 > 0){
?>
<div class="form-group col-md-3">
    <input type="hidden" name="AttrNameStorage[]" id="AttrNameStorage<?php echo $rncnt;?>" value="2">
<label class="form-label">Storage <span class="text-danger">*</span></label>
        <select class="form-control" name="AttrValueStorage[]" id="AttrValueStorage<?php echo $rncnt;?>">
<option selected="">Select Storage</option>
    <?php 
   
                                        while($rw = $r2->fetch_assoc())
                                    {
                                ?>

                                                <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>

                                              <?php } ?>
</select>
</div>
<?php } ?>       


      <input type="hidden" class="form-control" name="srno[]" id="srno<?php echo $rncnt;?>" value="<?php echo $rncnt;?>">
  <div class="form-row col-md-4">
    <div class="form-group col-lg-6">
<label class="form-label">Market Price<span class="text-danger">*</span></label>
<div class="input-group">
<div class="input-group-prepend">
<div class="input-group-text">&#8377;</div>
</div>
<input type="text" id="MaxPrice<?php echo $rncnt;?>" name="Max_Price[]" class="form-control" value="" onKeyPress="return isNumberKey(event)" oninput="calculate2(document.getElementById('MinPrice<?php echo $rncnt;?>').value,document.getElementById('MaxPrice<?php echo $rncnt;?>').value,document.getElementById('srno<?php echo $rncnt;?>').value)">
<div class="clearfix"></div>
</div>
</div>
<div class="form-group col-lg-6">
<label class="form-label">Our Price<span class="text-danger">*</span></label>
<div class="input-group">
<div class="input-group-prepend">
<div class="input-group-text">&#8377;</div>
</div>
<input type="text" id="MinPrice<?php echo $rncnt;?>" name="Min_Price[]" class="form-control" value="" onKeyPress="return isNumberKey(event)" oninput="calculate2(document.getElementById('MinPrice<?php echo $rncnt;?>').value,document.getElementById('MaxPrice<?php echo $rncnt;?>').value,document.getElementById('srno<?php echo $rncnt;?>').value)">
<div class="clearfix"></div>
</div>
</div>

</div>
 <div class="form-row col-md-3">
<div class="form-group col-lg-6">
<label class="form-label">Offer Price<span class="text-danger">*</span></label>
<div class="input-group">
<div class="input-group-prepend">
<div class="input-group-text">&#8377;</div>
</div>
<input type="text" id="OfferPrice<?php echo $rncnt;?>" name="Offer_Price[]" class="form-control" value="" readonly="">
<div class="clearfix"></div>
</div>
</div>
<div class="form-group col-lg-6">
<label class="form-label">Offer %<span class="text-danger">*</span></label>
<div class="input-group">
<input type="text" id="OfferPer<?php echo $rncnt;?>" name="Offer_Per[]" class="form-control" value="" readonly="">
<div class="clearfix"></div>
<div class="input-group-prepend">
<div class="input-group-text">%</div>
</div>
</div>
</div>
</div>
<div class="form-row col-md-2">
<!-- <div class="form-group col-lg-6">
<label class="form-label">Items in stock<span class="text-danger">*</span></label>
 <input type="number" min='0' name="Item_Stock[]" class="form-control" value="" >
<div class="clearfix"></div>
</div> -->

<div class="form-group col-lg-12">
<label class="form-label">Product Stock<span class="text-danger">*</span></label>
<select class="form-control" name="PrStock[]" >
<option value="1">Instock</option>
<option value="0">Out of stock</option>
</select>
</div>
</div>
<div class="form-group col-md-3">
    <label class="form-label d-none d-md-block">&nbsp;</label><button type="button" class="btn btn-primary" id="add"><i class="ion ion-md-add"></i>&nbsp; Add More</button></div>


</div>
<script type="text/javascript">
      function isNumberKey(evt){ 
    var charCode = (evt.which) ? evt.which : evt.keyCode
        if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}
    $(document).ready(function(){
    var i=$('#rncnt2').val(); 
      $('#add').click(function(){  
           i++;  
             var action = "getMoreAttributes";
             var CatId = $('#CatId').val();
    $.ajax({
    url:"ajax_files/ajax_shop_products.php",
    method:"POST",
    data : {action:action,id:i,CatId:CatId},
    success:function(data)
    {

       $('#dynamic_field').append(data);
    }
    });
           
      });  
      $(document).on('click', '.btn_remove', function(){  
           var button_id = $(this).attr("id");   
           $('#row'+button_id+'').remove();
           $('#hr'+button_id+'').remove();  

      });
});
      function calculate2(MinPrice,MaxPrice,srno){
    var OfferPrice = Number(MaxPrice) - Number(MinPrice);
    $('#OfferPrice'+srno).val(parseFloat(OfferPrice).toFixed(2));
     var perc="";
            if(isNaN(MinPrice) || isNaN(MaxPrice)){
                perc=" ";
               }else{
               perc = Math.trunc(((MaxPrice-MinPrice)/MaxPrice * 100));
               }
            
            $('#OfferPer'+srno).val(perc);
}
    
</script>
<?php } ?>

<?php 
if($_POST['action']=='delete_attr'){
    $ProdId = $_POST['pid'];
     $id = $_POST['id'];
    $SubCatId = $_POST['SubCatId'];
    $sql4 = "DELETE FROM related_products WHERE id='$id'";
    $conn->query($sql4);
} 

if($_POST['action']=='save_view_attr'){
    $ProdId = $_POST['id'];
    $CatId = $_POST['CatId'];
    $i=1;
         $sql3 = "SELECT * FROM related_products WHERE ProdId='$ProdId'";
        $res3 = $conn->query($sql3);
        $rncnt3 = mysqli_num_rows($res3);
        if($rncnt3 > 0){?>
            <input type="hidden" name="rncnt" id="rncnt" value="<?php echo $rncnt3; ?>">
            <?php
        while($row3 = $res3->fetch_assoc()){?>

              <div class="form-row" id="row<?php echo $i;?>"  style="padding-top: 10px;">
  <?php 
  $aid = $row['id'];
  $q4 = "select * from attribute_value WHERE AttrNameId='1' AND Status='1'";
    $r4 = $conn->query($q4); 
    $rncnt7 = mysqli_num_rows($r4);
  if($rncnt7 > 0){           
    ?>   
<div class="form-group col-md-3">
    <input type="hidden" name="AttrNameSize[]" id="AttrNameSize<?php echo $i;?>" value="1">
<label class="form-label">Size <span class="text-danger">*</span></label>
        <select class="form-control" name="AttrValueSize[]" id="AttrValueSize<?php echo $i;?>" >
<option selected="" >Select Size</option>
    <?php 
        while($rw = $r4->fetch_assoc())
             {
                                ?>

                                                <option <?php if($row3['AttrValueSize']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>

                                              <?php } ?>
</select>
</div>
<?php } 
$aid = $row['id'];
  $q3 = "select * from attribute_value WHERE AttrNameId='4' AND Status='1'";
  $r3 = $conn->query($q3);
   $rncnt6 = mysqli_num_rows($r3);
  if($rncnt6 > 0){?>
<div class="form-group col-md-3">
    <input type="hidden" name="AttrNameRam[]" id="AttrNameRam<?php echo $i;?>" value="4">
<label class="form-label">Ram <span class="text-danger">*</span></label>
        <select class="form-control" name="AttrValueRam[]" id="AttrValueRam<?php echo $i;?>">
<option selected="" >Select Ram</option>
    <?php 
    
                                        while($rw = $r3->fetch_assoc())
                                    {
                                ?>

                                                <option <?php if($row3['AttrValueRam']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>

                                              <?php } ?>
</select>
</div>
<?php }  $aid = $row['id'];
$q2 = "select * from attribute_value WHERE AttrNameId='2' AND Status='1'";
$r2 = $conn->query($q2);
$rncnt5 = mysqli_num_rows($r2);
  if($rncnt5 > 0){
?>
<div class="form-group col-md-3">
    <input type="hidden" name="AttrNameStorage[]" id="AttrNameStorage<?php echo $i;?>" value="2">
<label class="form-label">Storage <span class="text-danger">*</span></label>
        <select class="form-control" name="AttrValueStorage[]" id="AttrValueStorage<?php echo $i;?>">
<option selected="" >Select Storage</option>
    <?php 
   
                                        while($rw = $r2->fetch_assoc())
                                    {
                                ?>

                                                <option <?php if($row3['AttrValueStorage']==$rw['id']){ ?> selected <?php } ?> value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>

                                              <?php } ?>
</select>
</div>
<?php } ?>
      <input type="hidden" class="form-control" name="srno[]" id="srno<?php echo $i;?>" value="<?php echo $i;?>">
  <div class="form-row col-md-4">
    <div class="form-group col-lg-6">
<label class="form-label">Market Price<span class="text-danger">*</span></label>
<div class="input-group">
<div class="input-group-prepend">
<div class="input-group-text">&#8377;</div>
</div>
<input type="text" id="MaxPrice<?php echo $i;?>" value="<?php echo $row3["MaxPrice"]; ?>" name="Max_Price[]" class="form-control" value="" onKeyPress="return isNumberKey(event)" oninput="calculate2(document.getElementById('MinPrice<?php echo $i;?>').value,document.getElementById('MaxPrice<?php echo $i;?>').value,document.getElementById('srno<?php echo $i;?>').value)">
<div class="clearfix"></div>
</div>
</div>
<div class="form-group col-lg-6">
<label class="form-label">Our Price<span class="text-danger">*</span></label>
<div class="input-group">
<div class="input-group-prepend">
<div class="input-group-text">&#8377;</div>
</div>
<input type="text" id="MinPrice<?php echo $i;?>" value="<?php echo $row3["MinPrice"]; ?>" name="Min_Price[]" class="form-control" value="" onKeyPress="return isNumberKey(event)" oninput="calculate2(document.getElementById('MinPrice<?php echo $i;?>').value,document.getElementById('MaxPrice<?php echo $i;?>').value,document.getElementById('srno<?php echo $i;?>').value)">
<div class="clearfix"></div>
</div>
</div>

</div>
 <div class="form-row col-md-3">
<div class="form-group col-lg-6">
<label class="form-label">Offer Price<span class="text-danger">*</span></label>
<div class="input-group">
<div class="input-group-prepend">
<div class="input-group-text">&#8377;</div>
</div>
<input type="text" id="OfferPrice<?php echo $i;?>" value="<?php echo $row3["OfferPrice"]; ?>" name="Offer_Price[]" class="form-control" value="" readonly="">
<div class="clearfix"></div>
</div>
</div>
<div class="form-group col-lg-6">
<label class="form-label">Offer %<span class="text-danger">*</span></label>
<div class="input-group">
<input type="text" id="OfferPer<?php echo $i;?>" name="Offer_Per[]" value="<?php echo $row3["OfferPer"]; ?>" class="form-control" value="" readonly="">
<div class="clearfix"></div>
<div class="input-group-prepend">
<div class="input-group-text">%</div>
</div>
</div>
</div>
</div>
<div class="form-row col-md-2">
<!-- <div class="form-group col-lg-6">
<label class="form-label">Items in stock<span class="text-danger">*</span></label>
 <input type="number" min='0' name="Item_Stock[]" value="<?php echo $row3["ItemStock"]; ?>" class="form-control" value="" >
<div class="clearfix"></div>
</div> -->

<div class="form-group col-lg-12">
<label class="form-label">Product Stock<span class="text-danger">*</span></label>
<select class="form-control" name="PrStock[]" >
<option value="1" <?php if($row3["Stock"]=='1') {?> selected <?php } ?>>Instock</option>
<option value="0" <?php if($row3["Stock"]=='0') {?> selected <?php } ?>>Out of stock</option>
</select>
</div>
</div>
<div class="form-group col-md-3">
    <label class="form-label d-none d-md-block">&nbsp;</label><button type="button" onclick="deleteAttr(<?php echo $row3['id']; ?>,<?php echo $ProdId; ?>);" class="btn btn-outline-danger"><i class="ion ion-md-close"></i>&nbsp;Remove</button></div>


</div>
<div class="progress" id="hr<?php echo $i;?>" style="height: 0.15rem;">
<div class="progress-bar bg-success" style="width: 100%"></div>
</div>
<script type="text/javascript">
     $(document).ready(function() {
    var rncnt = $('#rncnt').val();
    $('#rncnt2').val(rncnt);
     });
</script>
       <?php $i++;}} 
}
?>

<?php if($_POST['action']=='getAttributes'){
  $id = $_POST['id'];
  $CatId = $_POST['CatId'];
  $q4 = "select * from attribute_value WHERE AttrNameId='1' AND Status='1'";
  $r4 = $conn->query($q4);
   $rncnt7 = mysqli_num_rows($r4);
  if($rncnt7 > 0){
  ?>
<div class="form-group col-md-3">
    <input type="hidden" name="NameSize" id="NameSize" value="1">
<label class="form-label">Size </label>
        <select class="form-control" name="Size" id="Size" >
<option selected="" >Select Size</option>
    <?php 
                                        while($rw = $r4->fetch_assoc())
                                    {
                                ?>

                                                <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>

 <?php } } ?>
</select>
</div>
<?php 
$q3 = "select * from attribute_value WHERE AttrNameId='4' AND Status='1'";
$r3 = $conn->query($q3);
 $rncnt6 = mysqli_num_rows($r3);
  if($rncnt6 > 0){
 ?>
<div class="form-group col-md-3">
    <input type="hidden" name="NameColor" id="NameRam" value="4">
<label class="form-label">Ram </label>
        <select class="form-control" name="Ram" id="Ram">
<option selected="" >Select Ram</option>
    <?php 
 
                                        
                                        while($rw = $r3->fetch_assoc())
                                    {
                                ?>

                                                <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
  <?php }} ?>
</select>
</div>
<?php
 $q2 = "select * from attribute_value WHERE AttrNameId='2' AND Status='1'";
 $r2 = $conn->query($q2);
 $rncnt5 = mysqli_num_rows($r2);
  if($rncnt5 > 0){
 ?>
<div class="form-group col-md-3">
    <input type="hidden" name="NameStorage" id="NameStorage" value="2">
<label class="form-label">Storage </label>
        <select class="form-control" name="Storage" id="Storage">
<option selected="" >Select Storage</option>
    <?php 
    while($rw = $r2->fetch_assoc())
         { ?>

          <option value="<?php echo $rw['id']; ?>"><?php echo $rw['Name']; ?></option>
    <?php } } ?>
</select>
</div>
<?php 
       $q = "select * from attribute_value WHERE AttrNameId='3' AND Status='1' ORDER BY id ASC";
        $r = $conn->query($q);
        $rncnt4 = mysqli_num_rows($r);
        if($rncnt4 > 0){
?>
 <div class="form-row">
  <label class="form-label" style="padding-left: 5px;">Color<span class="text-danger">*</span></label>
 <div class="form-row col-md-12" style="padding-top: 10px;">
 <?php 
        while($rw = $r->fetch_assoc())
    {
?>
<label class="custom-control custom-checkbox m-0" style="display: none;">
<input type="checkbox" name="Color[]" value="<?php echo $rw['id']; ?>" id="Color" class="custom-control-input" checked>
<span class="custom-control-label"><?php echo $rw['Name']; ?></span>
</label>
<div class="form-group col-lg-4">
<?php echo $rw['Name']; ?>
</div>
<div class="form-group col-lg-8">
   <label class="form-label">Upload Image <span class="text-danger">*</span></label>
<label class="custom-file">
<input type="file" class="custom-file-input" id="ColorPhoto" name="ColorPhoto[]" style="opacity: 1;" accept="image/jpg, image/jpeg, image/png">
<span class="custom-file-label"></span>
</label>
</div>
<?php } } ?>
</div>
</div>
<?php } 

if($_POST['action'] == 'rpedit'){
    $id = $_POST['id'];
    $MaxPrice = $_POST['MaxPrice'];
    $MinPrice = $_POST['MinPrice'];
    $OfferPrice = $_POST['OfferPrice'];
    $OfferPer = $_POST['OfferPer'];
    $Stock = $_POST['Stock'];

    $sql = "UPDATE products SET MaxPrice='$MaxPrice',MinPrice='$MinPrice',OfferPrice='$OfferPrice',OfferPer='$OfferPer',Stock='$Stock' WHERE id='$id'";
    $conn->query($sql);
    echo 1;
}


if($_POST['action'] == 'changeOrderStatus'){
$oid = $_POST['oid'];
$val = $_POST['val'];
$sql = "UPDATE orders SET PaStatus='$val' WHERE id='$oid'";
$conn->query($sql);
echo "Status Updated Successfully";
    }

if($_POST['action'] == 'updateDiscount'){
$oid = $_POST['oid'];
$SubTotal = $_POST['SubTotal'];
$Discount = $_POST['Discount'];
$TotalAmt = $_POST['TotalAmt'];
$sql = "UPDATE orders SET Discount='$Discount' WHERE id='$oid'";
$conn->query($sql);
echo "Record Updated Successfully";
    }
    
?>


