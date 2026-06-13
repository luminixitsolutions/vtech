<?php
session_start();
$allowRemove = isset($_GET['edit']) && (int) $_GET['edit'] === 1;
if (!empty($_SESSION["cart_item"])) {
    ?>

    <!-- Search box -->
    <div class="mb-3">
        <input type="text" id="cartSearch" class="form-control" placeholder="Search by Product or Serial No..." onkeyup="filterCartTable()">
    </div>

    <!-- Table -->
    <div style="max-height:60vh; overflow-y:auto; overflow-x:auto;">
        <table class="table table-bordered table-striped" id="cartTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th>Serial No</th>
                    <?php if ($allowRemove) { ?><th>Action</th><?php } ?>
                </tr>
            </thead>
            <tbody>
            <?php
            $i = 1;
            foreach ($_SESSION["cart_item"] as $item) {
                $itemId = (int) ($item['id'] ?? 0);
                echo "<tr>
                        <td>{$i}</td>
                        <td>" . htmlspecialchars($item['ProductName'] ?? '') . "</td>
                        <td>" . htmlspecialchars($item['SerialNo'] ?? '') . "</td>";
                if ($allowRemove && $itemId > 0) {
                    echo "<td><button type=\"button\" class=\"btn btn-sm btn-danger\" onclick=\"removeCartItem({$itemId})\">Remove</button></td>";
                } elseif ($allowRemove) {
                    echo "<td>-</td>";
                }
                echo "</tr>";
                $i++;
            }
            ?>
            </tbody>
        </table>
    </div>

    <!-- JS for search filter -->
    <script>
    function filterCartTable() {
        var input = document.getElementById("cartSearch");
        var filter = input.value.toLowerCase();
        var rows = document.querySelectorAll("#cartTable tbody tr");

        rows.forEach(function(row) {
            var product = row.cells[1].textContent.toLowerCase();
            var serial = row.cells[2].textContent.toLowerCase();
            if (product.indexOf(filter) > -1 || serial.indexOf(filter) > -1) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    }

    <?php if ($allowRemove) { ?>
    function removeCartItem(id) {
        if (typeof delete_prod === 'function') {
            delete_prod(id);
        }
        if ($('#Check_Id' + id).length) {
            $('#Check_Id' + id).prop('checked', false);
            $('#CheckId' + id).val(0);
        }
        $.get('view_cart.php?edit=1', function(response) {
            $('#cartContent').html(response);
        });
        if ($.fn.DataTable && $('#empTable').length && $.fn.DataTable.isDataTable('#empTable')) {
            $('#empTable').DataTable().ajax.reload(null, false);
        }
    }
    <?php } ?>
    </script>

    <?php
} else {
    echo '<p class="text-center text-muted">No items added yet.</p>';
}
?>
