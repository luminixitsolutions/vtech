<?php
include_once 'config.php';

$prodId = $_GET['prodId'] ?? 0;

if (!$prodId) {
    echo "<span class='text-danger'>Invalid Product ID</span>";
    exit;
}

$sql = "SELECT id, SerialNo, ProductName 
        FROM tbl_distibute_item_details2 
        WHERE ProductId='$prodId' 
          AND ProdType='1' 
          AND SellStatus=0 
          AND SerialNo!=''
        ORDER BY id DESC";

$rows = getList($sql);

if (!$rows) {
    echo "<span class='text-danger small'>No serial numbers found.</span>";
    exit;
}
?>

<style>
.serials-table input[type=checkbox] {
  appearance: none !important;
  width: 18px;
  height: 18px;
  border: 2px solid #999;
  border-radius: 3px;
  cursor: pointer;
  position: relative;
}
.serials-table input[type=checkbox]:checked::before {
  content: "✔";
  position: absolute;
  top: -2px;
  left: 2px;
  font-size: 14px;
  color: #0dc30d;
  font-weight: bold;
}
.search-box {
  margin-bottom: 8px;
}
</style>

<div class="search-box" style="width: 35%;">
  <input type="text"
         id="searchSerial_<?php echo $prodId; ?>"
         class="form-control form-control-sm"
         placeholder="Search Serial No...">
</div>

<table class="table table-bordered table-sm mb-0 serials-table">
  <thead class="table-light">
    <tr>
      <th style="width: 50px; text-align: center;"></th>
      <th>Serial No</th>
    </tr>
  </thead>
  <tbody id="serialTableBody_<?php echo $prodId; ?>">
    <?php foreach ($rows as $r): ?>
      <?php $serialNo = htmlspecialchars($r['SerialNo']); ?>
      <tr>
        <td style="text-align: center;">
          <input type="checkbox"
                 class="serialCheckbox serial_<?php echo $prodId; ?>"
                 name="StructureSerialCheckId[<?php echo $prodId; ?>][]"
                 value="<?php echo $serialNo; ?>">
        </td>
        <td><?php echo $serialNo; ?></td>

        <input type="hidden" name="StructureSerialProductId[<?php echo $prodId; ?>][]" value="<?php echo $prodId; ?>">
        <input type="hidden" name="StructureSerialProductName[<?php echo $prodId; ?>][]" value="<?php echo htmlspecialchars($r['ProductName'] ?? ''); ?>">
        <input type="hidden" name="StructureSerialNo[<?php echo $prodId; ?>][]" value="<?php echo $serialNo; ?>">
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<script>
(function() {
  const prodId = "<?php echo $prodId; ?>";

  // Wait until both elements exist in DOM
  function initSerialSearch() {
    const searchInput = document.getElementById("searchSerial_" + prodId);
    const tableBody = document.getElementById("serialTableBody_" + prodId);

    if (!searchInput || !tableBody) {
      // Try again after short delay (handles AJAX-loaded content)
      setTimeout(initSerialSearch, 200);
      return;
    }

    // Remove any existing listener before adding a new one
    searchInput.oninput = null;

    // 🔍 Attach real-time filtering
    searchInput.addEventListener("input", function() {
      const filter = this.value.trim().toLowerCase();
      const rows = tableBody.querySelectorAll("tr");

      rows.forEach(row => {
        const serialCell = row.querySelector("td:nth-child(2)");
        if (!serialCell) return;
        const text = serialCell.textContent.trim().toLowerCase();

        // Hide or show row based on match
        if (text.includes(filter)) {
          row.style.display = "";
        } else {
          row.style.display = "none";
        }
      });
    });

    console.log("✅ Serial search initialized for product:", prodId);
  }

  // Start once
  initSerialSearch();
})();
</script>

