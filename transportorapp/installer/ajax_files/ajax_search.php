<?php
session_start();
include_once '../config.php';
require_once("../dbcontroller.php");
$UserId = $_SESSION['User']['id'];
if(isset($_POST["query"]))
{
 $search = mysqli_real_escape_string($conn, $_POST["query"]);
  $output = "";
/*$query = "SELECT p.*,c.Name As CatName,sc.Name As SubCatName FROM products p 
          LEFT JOIN category c ON c.id=p.CatId 
          LEFT JOIN sub_category sc ON sc.id=p.SubCatId 
          WHERE p.Status=1 AND 
		  (p.ProductName LIKE '%".$search."%' OR 
		   p.Details LIKE '%".$search."%' OR 
		   c.Name LIKE '%".$search."%' OR 
		   sc.Name LIKE '%".$search."%')"; */
$query = "SELECT p.* FROM products p
          WHERE p.Status=1 AND 
		  (p.ProductName LIKE '%".$search."%')";		   
}
else{
$query = "SELECT * FROM products WHERE Status=1"; 	
}
$query.=" ORDER BY id ASC";
//echo $query;
$rncnt = getRow($query);
if($rncnt > 0){
    $row2 = getList($query);
    foreach($row2 as $result){
    $Prod_id = $result["id"];
                        $cat_id = $result['CatId'];
                        $SizeId = $result['Size'];
                        $ItemStock = $result['Stock'];
                        $sql5 = "SELECT * FROM attribute_value WHERE id='$SizeId'";
                        $res5 = $conn->query($sql5);
                        $row5 = $res5->fetch_assoc();
    
 ?>
 
    <input type="hidden" id="user_id" value="<?php echo $user_id; ?>">
  <input type="hidden" value="<?php echo $value;?>" id="wishid<?php echo $result["id"];?>">
   <input type="hidden" id="pid<?php echo $result["id"];?>" value="<?php echo $result["id"];?>">
    <input type="hidden" id="sizeid<?php echo $result["id"];?>" value="<?php echo $result['Size'];?>">
   <input type="hidden" id="ramid<?php echo $result["id"];?>" value="<?php echo $result['Ram'];?>">
    <input type="hidden" id="storageid<?php echo $result["id"];?>" value="<?php echo $result['Storage'];?>">
    <input type="hidden" id="code<?php echo $result["id"];?>" value="<?php echo $result['code'];?>">
     <input type="hidden" id="prd_price<?php echo $result["id"];?>" value="<?php echo $result['MinPrice'];?>"> 
      <input type="hidden" id="qntno<?php echo $result["id"];?>" value="1">     
        
        <div class="media mb-2 w-100 " data-aos="zoom-in" style="box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.05);
-webkit-box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.05); -moz-box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.05);
-ms-box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.05); border-radius: 15px; background: #fff;">

                    <div class="avatar avatar-150 mr-2 has-background rounded">
                        <a href="product-details.php?id=<?php echo $result["id"];?>"><figure class="">
                           <?php if($result["Photo"] == '') {?>
                  <img src="no_image.jpg" style="width: 150px;height: 150px;"> 
                 <?php } else if(file_exists('../../uploads/'.$result["Photo"])){?>
                 <img src="../uploads/<?php echo $result["Photo"];?>" alt="" style="width: 150px;height: 150px;">
                  <?php }  else{?>
                 <img src="no_image.jpg" style="width: 150px;height: 150px;"> 
             <?php } ?>
                        </figure></a>
                          <?php if($result['OfferPer'] != 0){?>
                        <div class="top-right m-2">
                                    <button class="btn btn-sm btn-light btn-rounded btn-30 rounded-circle" style="border-radius: 20px !important;height: 40px;width: 60px;"><?php echo $result['OfferPer'];?>% Off</button>
                                </div>
                                <?php } ?>
                    </div>
                    <div class="media-body " style="padding-top: 15px; padding-right:3px;">
                         <a href="product-details.php?id=<?php echo $result["id"];?>"><span style="font-weight: 600; font-size: 15px;"><?php echo $result['ProductName']; ?></span></a><br>
                        <?php if($result['MaxPrice'] != $result['MinPrice']){?>
                        <span id="MaxPrice3<?php echo $result["id"];?>"><del>&#8377;<?php echo number_format($result["MaxPrice"],2);?> </del></span>
                        <?php } ?>
                        <span style="font-weight: 500;" id="MinPrice3<?php echo $result["id"];?>">&#8377; <?php echo number_format($result["MinPrice"],2); ?> </span>
                        
                        <br>
                        <?php if($result['Size']=='0'){}else if($result['CatId'] != 0){?>
                        <select class="" style="display: block;

padding: 0.375rem 0.75rem;
font-size: 12px;
font-weight: 400;
line-height: 1.5;
color: #495057;
background-color: #fff;
background-clip: padding-box;
border: 1px solid #ced4da;
border-radius: 0.25rem;
transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;" onchange="getDiffSize(this.value,<?php echo $result["id"];?>)">
 <?php if($result['Size']=='0') {} else if($result['CatId'] != 0){?>
                                  <option value="<?php echo $result['Size'];?>" selected><?php echo $row5['Name'];?></option>
                                    <?php }
                                    $sql41 = "SELECT DISTINCT(AttrValueSize) as sizeid FROM `related_products`  WHERE ProdId = '$Prod_id' AND AttrValueSize != '0'";
                                    $res41 = $conn->query($sql41);
                                    $rncnt41 = mysqli_num_rows($res41);
                                    if($rncnt41 > 0){ 
                                    $i= 2;
                                    while($row41 = $res41->fetch_assoc()){
                                    $Size_Id = $row41['sizeid'];
                                    $sql32 = "SELECT * FROM attribute_value WHERE id='$Size_Id'";
                                    $res32 = $conn->query($sql32);
                                    $row32 = $res32->fetch_assoc();
                                    if($row2['Size'] == $Size_Id){} else{?>
                                    <option value="<?php echo $row32['id'];?>"><?php echo $row32['Name'];?></option>
                                   <?php } $i++;} } ?>
                                  </select>
                                   <?php } ?>
                                <div style="padding-top:5px;">
<button class="btn btn-sm btn-default rounded" style="font-size: 12px;" id="add-cart<?php echo $result["id"];?>" onclick="addCart(<?php echo $result["id"];?>);"><i style="font-size:14px;" class="material-icons">local_mall</i> Add</button>

<?php if($result["Subscription"] == 1) {?>
<a href="subscribe-product.php?id=<?php echo $result["id"];?>" class="btn btn-sm btn-default rounded" style="font-size: 12px;"> subscribe</a>
 <?php } ?>
                                </div>
                               
                      <!--   <small class="text-secondary">11-1-2020 | 24:00 am</small> -->
                        
                    </div>
           
                </div>  
                            
                        <?php
}
}  
?>