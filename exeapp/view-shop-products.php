<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
$user_id = $_SESSION['Admin']['id'];
$MainPage = "E-Commerce";
$Page = "View-Product";
?>
<!DOCTYPE html>
<html lang="en" class="default-style layout-fixed layout-navbar-fixed">
<head>
<title><?php echo $Proj_Title; ?> | View Product List</title>
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

<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
</head>
<body>

<body class="body-scroll d-flex flex-column h-100 menu-overlay">
   


    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <!-- Fixed navbar -->
        <?php include_once 'back-header.php'; ?> 
        

        <div class="main-container">

<?php include_once 'ecommerce-sidebar.php'; ?>






<?php
if($_REQUEST["action"]=="delete")
{
  $id = $_REQUEST["id"];
  
    $query2 = "SELECT Photo FROM products WHERE id = '$id'";
    $result2 = $conn->query($query2);
    $row2 = $result2->fetch_assoc();
    $Photo2 = $row2['Photo'];
    $src2 = "../uploads/$Photo2";
        unlink($src2);
  $sql2 = "SELECT * FROM product_images WHERE ProductId='$id'";
  $res2 = $conn->query($sql2);
    while($row2 = $res2->fetch_assoc()){
       $Photo = $row2['Files'];
       $imgid = $row2['id'];
         $src = "../uploads/$Photo";
        unlink($src);
        $sql12 = "DELETE FROM product_images WHERE id = '$imgid'";
       $conn->query($sql12);
    }
    $sql11 = "DELETE FROM products WHERE id = '$id'";
  $conn->query($sql11);
   $sql3 = "DELETE FROM related_products WHERE ProdId='$id'";
  $conn->query($sql3);
   $sql4 = "DELETE FROM temp_color WHERE ProdId='$id'";
  $conn->query($sql4);
  
  ?>
    <script type="text/javascript">
      alert("Deleted Successfully!");
      window.location.href="view-shop-products.php";
    </script>
<?php } 

if($_REQUEST["action"]=="copy")
{
  $id = $_REQUEST["id"];
    $query2 = "SELECT * FROM products WHERE id = '$id'";
    $result2 = $conn->query($query2);
    $row2 = $result2->fetch_assoc();

    $ProductName = addslashes(trim($row2['ProductName']));
$Details = addslashes(trim($row2['Details']));
$SubCatId = $row2['SubCatId'];
$BrandId = $row2['BrandId'];
$CatId = $row2['CatId'];
$BatchCode = addslashes(trim($row2['BatchCode']));
$NameSize = $row2['NameSize'];
$Size = $row2['Size'];
$NameColor = $row2['NameColor'];
$Color = implode(",",$row2['Color']);
$NameStorage = $row2['NameStorage'];
$Storage = $row2['Storage'];
$NameRam = $row2['NameRam'];
$Ram = $row2['Ram'];
$MinPrice = $row2['MinPrice'];
$MaxPrice = $row2['MaxPrice'];
$OfferPrice = $row2['OfferPrice'];
$OfferPer = $row2['OfferPer'];
$Cashback = $row2['Cashback'];
$Featured = $row2['Featured'];
$FreeShipping = $row2['FreeShipping'];
$Bestseller = $row2['Bestseller'];
$ItemStock = $row2['ItemStock'];
$Stock = $row2['Stock'];
$Tax = $row2['Tax'];
$Highlight1 = addslashes(trim($row2['Highlight1']));
$Highlight2 = addslashes(trim($row2['Highlight2']));
$Highlight3 = addslashes(trim($row2['Highlight3']));
$Highlight4 = addslashes(trim($row2['Highlight4']));
$Highlight5 = addslashes(trim($row2['Highlight5']));

$MetaTag = addslashes(trim($row2['MetaTag']));
$MetaDesc = addslashes(trim($row2['MetaDesc']));
$Keywords = addslashes(trim($row2['Keywords']));
$DeliveryInfo = addslashes(trim($row2['DeliveryInfo']));
$Offers = addslashes(trim($row2['Offers']));
$Status = $row2['Status'];
$CreatedDate = date('Y-m-d');

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


$sql = "INSERT INTO products SET ProductName='$ProductName',Details='$Details',CatId='$CatId',SubCatId='$SubCatId',BrandId='$BrandId',BatchCode='$BatchCode',code='$Code',NameSize='$NameSize',Size='$Size',NameColor='$NameColor',Color='$Color',NameStorage='$NameStorage',Storage='$Storage',NameRam='$NameRam',Ram='$Ram',MinPrice='$MinPrice',MaxPrice='$MaxPrice',OfferPrice='$OfferPrice',OfferPer='$OfferPer',Tax='$Tax',Cashback='$Cashback',Featured='$Featured',FreeShipping='$FreeShipping',Bestseller='$Bestseller',Photo='$Photo',ItemStock='$ItemStock',Stock='$Stock',Status='$Status',DeliveryInfo='$DeliveryInfo',Offers='$Offers',MetaTag='$MetaTag',MetaDesc='$MetaDesc',Keywords='$Keywords',CreatedDate='$CreatedDate',Highlight1='$Highlight1',Highlight2='$Highlight2',Highlight3='$Highlight3',Highlight4='$Highlight4',Highlight5='$Highlight5'";
$conn->query($sql);
$ProdId = mysqli_insert_id($conn);

 $query21 = "SELECT * FROM related_products WHERE ProdId = '$id'";
    $result21 = $conn->query($query21);
    while($row21 = $result21->fetch_assoc()){
      $srno = $row21['srno'];
                       $AttrNameSize = $row21['AttrNameSize'];
                       $AttrValueSize = $row21['AttrValueSize'];
                       $AttrNameRam = $row21['AttrNameRam'];
                       $AttrValueRam = $row21['AttrValueRam'];
                       $AttrNameStorage = $row21['AttrNameStorage'];
                       $AttrValueStorage = $row21['AttrValueStorage'];
                       $Min_Price = addslashes($row21['MinPrice']);
                        $Max_Price = addslashes($row21['MaxPrice']);
                        $Offer_Price = addslashes($row21['OfferPrice']);
                        $Offer_Per = addslashes($row21['OfferPer']);
                        $Item_Stock = addslashes($row21['ItemStock']);
                        $PrStock = addslashes($row21['Stock']);
                         $Status = $row21['Status'];
      $sql = "INSERT INTO related_products SET srno='$srno',ProdId='$ProdId',AttrNameSize='$AttrNameSize',AttrValueSize='$AttrValueSize',AttrNameRam='$AttrNameRam',AttrValueRam='$AttrValueRam',AttrNameStorage='$AttrNameStorage',AttrValueStorage='$AttrValueStorage',MinPrice='$Min_Price',MaxPrice='$Max_Price',OfferPrice='$Offer_Price',OfferPer='$Offer_Per',ItemStock='$Item_Stock',Stock='$PrStock',Status='$Status'";
 $conn->query($sql);
    }
    ?>
    <script type="text/javascript">
      alert("Product Copied Successfully!");
      window.location.href="edit-product.php?id=<?php echo $ProdId;?>";
    </script>
<?php } ?>

<div class="layout-content">

<div class="container-fluid flex-grow-1 container-p-y">
<h4 class="font-weight-bold py-3 mb-0">View Product List</h4>

<div class="card">
<div class="card-datatable table-responsive">
<table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
            <tr>
               <th>Photo</th>
                <th>Product name</th>
               
                <th>Category</th>
                <!-- <th>Sub Category</th> -->
                <th>Action</th>
                <!--  <th>Brand</th>
                  <th>Short Details</th> -->
                   <th>Price</th>
                 <th>Item In Stock</th>
                <th>Product Stock</th>
                <th>Status</th>
                <th>Register Date</th>
                
            </tr>
        </thead>
        <tbody>
            <?php 
            $sql = "SELECT p.*,c.Name As Category,sb.Name As SubCategory,b.Name As Brand 
                    FROM products p 
                    LEFT JOIN category c ON c.id=p.CatId
                    LEFT JOIN sub_category sb ON sb.id=p.SubCatId
                    LEFT JOIN brands b ON b.id=p.BrandId";
     
  $sql.= " ORDER BY p.id DESC";
            $res = $conn->query($sql);
            while($row = $res->fetch_assoc())
            {
             
             ?>
            <tr>
               <td> <?php if($row["Photo"] == '') {?>
                  <img src="../no_image.jpg" class="img-fluid ui-w-40"  style="width: 40px;height: 40px;"> 
                 <?php } else if(file_exists('../uploads/'.$row["Photo"])){?>
                 <img src="../uploads/<?php echo $row["Photo"];?>" class="img-fluid ui-w-40" alt="" style="width: 40px;height: 40px;">
                  <?php }  else{?>
                 <img src="no_image.jpg" class="img-fluid ui-w-40" style="width: 40px;height: 40px;"> 
             <?php } ?></td>

                <td><?php echo $row['ProductName']; ?></td>
              
               
                <td><?php echo $row['Category']; ?></td>
                <!--   <td><?php echo $row['SubCategory']; ?></td> -->
                     <td>
                      <?php if($_SESSION['Roll']==1) {?>
              <a href="edit-product.php?id=<?php echo $row['id']; ?>" data-toggle="tooltip" data-placement="top" title="Edit" data-original-title="Edit"><i class="lnr lnr-pencil mr-2"></i></a>&nbsp;&nbsp;<a onClick="return confirm('Are you sure you want delete this Product?\nNote : Delete all orders related this Product (Y/N)');" href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $row['id']; ?>&action=delete" ><i class="lnr lnr-trash text-danger"></i></a>&nbsp;&nbsp;<?php } ?>
              <a href="view-product-details.php?id=<?php echo $row['id']; ?>" data-toggle="tooltip" data-placement="top" title="" data-original-title="View"><i class="lnr lnr-eye mr-2"></i></a>
              <a href="<?php echo $_SERVER['PHP_SELF']; ?>?id=<?php echo $row['id']; ?>&action=copy" onClick="return confirm('Are you sure you want copy of this Product?');" data-toggle="tooltip" data-placement="top" title="" data-original-title="Copy"><i class="pe-7s-copy-file mr-2"></i></a>
            </td>
         
             <!--   <td><?php echo $row['Brand']; ?></td>
                <td><?php echo substr($row['Details'],0,100); ?>...</td> -->
                <td>&#8377;<?php echo number_format($row["MinPrice"],2); ?>&nbsp;&nbsp;<del>&#8377;<?php echo number_format($row["MaxPrice"],2); ?></del></td>
               <td><?php echo $row['ItemStock']; ?></td>
                <td><?php if($row['Stock']=='1'){echo "<span style='color:green;'>In Stock</span>";} else { echo "<span style='color:red;'>Out Of Stock</span>";} ?></td>
                    
                 <td><?php if($row['Status']=='1'){ echo "<span style='color:green;'>Publish</span>";} else { echo "<span style='color:red;'>Not Publish</span>";} ?></td>
            <td><?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row['CreatedDate']))); ?></td>
         
              
            </tr>
           <?php } ?>
        </tbody>
    </table>
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

<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>


<script type="text/javascript">
 
	$(document).ready(function() {
    $('#example').DataTable({
      
    });
});
</script>
</body>
</html>
