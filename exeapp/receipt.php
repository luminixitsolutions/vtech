<?php 
session_start();
include_once 'config.php';
include_once 'auth.php';
?>
<style>
table, td, th {  
  border: 1px solid #000;
  text-align: left;
}

table {
  border-collapse: collapse;
  width: 100%;
}

th, td {
  padding: 5px;
  font-size: 12px;
}

 fieldset legend {
        background: inherit;
        font-family: "Lato", sans-serif;
        color: #650812;
        font-size: 15px;
        left: 10px;
        padding: 0 10px;
        position: absolute;
        top: -12px;
        font-weight: 400;
        width: auto !important;
        border: none !important;
    }

    fieldset {
        background: #ffffff;
        border: 1px solid #C43853;
        border-radius: 5px;
        margin: 2px 0 1px 0;
        padding: 13px;
        position: relative;
    }
</style>
<script type="text/javascript">
window.print();
window.onafterprint = window.close; 
</script>
<?php  
$id = $_GET['id'];
if($_GET['roll'] == 'vendor'){
$sql7 = "SELECT tv.*,tu.GstNo FROM tbl_vendor_orders tv LEFT JOIN tbl_users tu ON tu.id=tv.VedId WHERE tv.id='$id'";
$row7 = getRecord($sql7);
}
else{
 $sql7 = "SELECT * FROM tbl_customer_orders WHERE id='$id'";
$row7 = getRecord($sql7); 
}
 $number = $row7['Amount'];
include_once 'convert_currancy.php';
?>
<table>
    
  <tr>
       <th style="text-align: center;"  rowspan="2"><img src="logo.png" width="100px"></th>
    <th style="text-align: center;background-color: lightgrey;" colspan="6">|| OM SAI RAM ||<br><strong style="font-size:20px;">SAI SOYA FOOD PRODUCTS</strong>
    </th>
  </tr>
<tr>
     
    <th style="text-align: center;" colspan="6">Shanti Nilayam, Plot No. 14, Bhaskar Borkute Layout, Narendra Nagar, Nagpur-15<br>
    Mo.: 8446051991, 7580080044</th>
  </tr>
  <tr>
    <th   colspan="1">Tax Invoice</th>
    <th style="text-align: center;" colspan="5">FSSAI No. : 11516055000661</th>
  </tr>
  <tr>
    <th colspan="4">M/s : <?php echo $row7['VedName'] ?><br>
    Phone No. : <?php echo $row7['CellNo'] ?><br>
      Place Of Supply : <?php echo $row7['Address'] ?>
    </th>
    <th colspan="2">

      Invoice No. &nbsp;&nbsp;&nbsp;&nbsp;: <?php echo $row7['InvoiceNo'] ?><br>
      Date &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <?php echo date("d/m/Y", strtotime(str_replace('-', '/',$row7['InvoiceDate']))); ?> </th>
    
  </tr>
  <tr style="background-color: lightgrey;">
    <th>Sr.</th>
    <th>Product Name</th>
    <th>HSN/SAC Code</th>
    <th>Qty</th>
    <th>Rate</th>

    <th>Net Amount</th>
  </tr>
  <?php 
  $i=1;
  if($_GET['roll'] == 'vendor'){
    $sql2 = "SELECT tv.*,tp.Name As ProdName FROM tbl_vendor_order_items tv INNER JOIN tbl_product tp ON tv.ProdId=tp.id WHERE tv.OrderId='$id'";
}
else{
  $sql2 = "SELECT tv.*,tp.Name As ProdName FROM tbl_customer_order_items tv INNER JOIN tbl_product tp ON tv.ProdId=tp.id WHERE tv.OrderId='$id'";
}
$row2 = getList($sql2);
foreach($row2 as $result){
  if($_GET['roll'] == 'vendor'){
    $Price = $result['VedPrice'];
  }
  else{
$Price = $result['Price'];
  }
   ?>
  <tr>
    <th><?php echo $i; ?>.</th>
    <th><?php echo $result['ProdName'];?></th>
    <th></th>
    <th><?php echo $result['Qty'];?></th>
    <th><?php echo $Price; ?></th>

    <th><?php echo $result['Total'];?></th>

  </tr>
    <?php $i++;} ?>
  <tr>
    <th colspan="2">GSTIN No.:</th>
    <th colspan="3">Total</th>


    <th><?php echo $row7['Amount']; ?></th>
  </tr>
  <tr>
    <th colspan="4">Total GST : <br><br><br>
    Bill Amount : <?php echo  $result22 . "Rupees"; ?></th>
    <th colspan="1">Grand Total : </th>
    <th><?php echo $row7['Amount']; ?></th>
  </tr>
  <tr>
    <th colspan="2">
     <span style="font-size:10px;">NOTE :- <br>
    Subject to Nagpur jurisdiction.<span></th>
    
    <th colspan="3">Account Details : <br>
     <span style="font-size:10px;">Bank Details : ICICI Bank (NEFT)<br>
A/c No. : 178705500711<br>
IFSC Code : ICIC0001787<br>
Branch : Chatrapati Square, Nagpur.<span></th>
    
  <th colspan="2">For Sai Soya Food Products<br><br><br><br>(Authorised Signator)</th>
  </tr>
</table>