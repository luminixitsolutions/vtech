<?php
session_start();
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
                </tr>
            </thead>
            <tbody>
            <?php
            $i = 1;
            foreach ($_SESSION["cart_item"] as $item) {
                echo "<tr>
                        <td>{$i}</td>
                        <td>{$item['ProductName']}</td>
                        <td>{$item['SerialNo']}</td>
                      </tr>";
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
    </script>

    <?php
} else {
    echo '<p class="text-center text-muted">No items added yet.</p>';
}
?>
