<?php 
session_start();
include_once 'config.php';
?>
<table border="1">
<thead>
<tr>
              <th>SrNo</th>
               <th>Product Id</th>
              <th>Product</th>
              <th>MODEL NO.</th>
             <th>SERIAL NO.</th> 
              <th>Unit</th>
              <th>Qty</th>
              <th>Company Id</th>
              <th>Product Type</th>
              <th>Post Id</th>
            </tr>
</thead>
<?php 
$filename = $_REQUEST['invno'];
            $i3=1;
            $sql66 = "SELECT tpo.*,tp.ModelNo FROM tbl_purchase_order_products tpo INNER JOIN tbl_products tp ON tpo.ProductId=tp.id WHERE tpo.SellId='".$_REQUEST['id']."' AND tp.Roll=1";
            $row66 = getList($sql66);
            foreach($row66 as $result){
               for($i=1;$i<=$result['Qty'];$i++){

                    $sql = "SELECT SerialNo FROM tbl_stocks WHERE SellId='".$_GET['id']."' AND ProductId='".$result['ProductId']."' AND SrNo='$i'";
                    $row = getRecord($sql);
                    $SerialNo = $row['SerialNo'];
                ?>
            <tr>
                <td><?php echo $i;?></td>
                 <td><?php echo $result['ProductId'];?></td>
                <td><?php echo $result['ProductName'];?></td>
                <td><?php echo $result['ModelNo'];?></td>
                <td><?php echo $SerialNo;?></td>
                <td><?php echo $result['Purity'];?></td>
                <td>1</td>
                <td><?php echo $_REQUEST['compid'];?></td>
                <td>1</td>
                <td><?php echo $result['id'];?></td>
            </tr>
<?php
$i3++; 
// Genrating Execel  filess
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=".$filename."-Products.xls");
header("Pragma: no-cache");
header("Expires: 0");
 }} ?>
</table>