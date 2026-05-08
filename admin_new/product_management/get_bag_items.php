<?php
include_once '../config.php';

$bag_id = $_GET['bag_id'];
$sql = "SELECT bi.*
        FROM tbl_bag_items bi
        
        WHERE bi.BagId = '$bag_id'";
$res = $conn->query($sql);

if($res->num_rows > 0){
    $i=1;
    while($row = $res->fetch_assoc()){
        echo "<tr>
                <td>".$i."</td>
                <td>".$row['ProductName']."</td>
                <td>".$row['Qty']."</td>
              </tr>";
        $i++;
    }
} else {
    echo "<tr><td colspan='3' class='text-center text-muted'>No items found in this bag.</td></tr>";
}
?>
