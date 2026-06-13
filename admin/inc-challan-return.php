<?php
/**
 * Challan return / edit helpers — tables, stock reversal, and activity logs.
 */

function challanReturnEnsureTables($conn)
{
    static $checked = false;
    if ($checked) {
        return;
    }
    $checked = true;

    $conn->query("CREATE TABLE IF NOT EXISTS challan_returns (
        id INT(11) NOT NULL AUTO_INCREMENT,
        sell_id INT(11) NOT NULL DEFAULT 0,
        return_date DATE NOT NULL,
        remarks TEXT,
        created_by INT(11) NOT NULL DEFAULT 0,
        created_at DATETIME NOT NULL,
        updated_at DATETIME DEFAULT NULL,
        PRIMARY KEY (id),
        UNIQUE KEY uk_sell_id (sell_id),
        KEY return_date (return_date)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $conn->query("CREATE TABLE IF NOT EXISTS challan_return_items (
        id INT(11) NOT NULL AUTO_INCREMENT,
        return_id INT(11) NOT NULL DEFAULT 0,
        sell_id INT(11) NOT NULL DEFAULT 0,
        product_id INT(11) NOT NULL DEFAULT 0,
        item_id INT(11) NOT NULL DEFAULT 0,
        serial_no VARCHAR(255) DEFAULT NULL,
        qty DECIMAL(12,2) NOT NULL DEFAULT 0,
        prod_type TINYINT(1) NOT NULL DEFAULT 0,
        product_name VARCHAR(255) DEFAULT NULL,
        model_no VARCHAR(255) DEFAULT NULL,
        unit VARCHAR(64) DEFAULT NULL,
        created_at DATETIME NOT NULL,
        PRIMARY KEY (id),
        KEY return_id (return_id),
        KEY sell_id (sell_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $conn->query("CREATE TABLE IF NOT EXISTS challan_edit_log (
        id INT(11) NOT NULL AUTO_INCREMENT,
        sell_id INT(11) NOT NULL DEFAULT 0,
        return_id INT(11) NOT NULL DEFAULT 0,
        action_type VARCHAR(32) NOT NULL DEFAULT 'edit',
        old_items_json TEXT,
        new_items_json TEXT,
        remarks TEXT,
        performed_by INT(11) NOT NULL DEFAULT 0,
        created_at DATETIME NOT NULL,
        PRIMARY KEY (id),
        KEY sell_id (sell_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    $col = $conn->query("SHOW COLUMNS FROM tbl_sell LIKE 'ReturnStatus'");
    if ($col && $col->num_rows === 0) {
        $conn->query("ALTER TABLE tbl_sell ADD COLUMN ReturnStatus TINYINT(1) NOT NULL DEFAULT 0 AFTER Status");
    }
}

function challanReturnIsSerialItem(array $item)
{
    $serial = trim((string) ($item['SerialNo'] ?? ''));
    return $serial !== '' && strcasecmp($serial, 'N/A') !== 0;
}

function challanReturnGetSellItems($conn, $sellId)
{
    $sellId = (int) $sellId;
    if ($sellId < 1) {
        return [];
    }
    $rows = getList("SELECT * FROM tbl_sell_products WHERE SellId='$sellId' AND ProductName!='' ORDER BY id ASC");
    return is_array($rows) ? $rows : [];
}

function challanReturnAlreadyReturned($conn, $sellId)
{
    challanReturnEnsureTables($conn);
    $sellId = (int) $sellId;
    return getRow("SELECT id FROM challan_returns WHERE sell_id='$sellId' LIMIT 1") > 0;
}

function challanReturnGetRecord($conn, $sellId)
{
    challanReturnEnsureTables($conn);
    $sellId = (int) $sellId;
    return getRecord("SELECT cr.*, ts.InvoiceNo, ts.CustName, ts.BranchId, tu.Fname AS ReturnedByName
        FROM challan_returns cr
        LEFT JOIN tbl_sell ts ON ts.id = cr.sell_id
        LEFT JOIN tbl_users tu ON tu.id = cr.created_by
        WHERE cr.sell_id='$sellId' LIMIT 1");
}

function challanReturnLogActivity($conn, $sellId, $returnId, $actionType, $oldItems, $newItems, $remarks, $performedBy)
{
    challanReturnEnsureTables($conn);
    $sellId = (int) $sellId;
    $returnId = (int) $returnId;
    $performedBy = (int) $performedBy;
    $actionType = mysqli_real_escape_string($conn, (string) $actionType);
    $remarksEsc = mysqli_real_escape_string($conn, (string) $remarks);
    $oldJson = mysqli_real_escape_string($conn, json_encode($oldItems));
    $newJson = mysqli_real_escape_string($conn, json_encode($newItems));
    $now = date('Y-m-d H:i:s');
    $sql = "INSERT INTO challan_edit_log SET
        sell_id='$sellId',
        return_id='$returnId',
        action_type='$actionType',
        old_items_json='$oldJson',
        new_items_json='$newJson',
        remarks='$remarksEsc',
        performed_by='$performedBy',
        created_at='$now'";
    return (bool) $conn->query($sql);
}

/**
 * Restore stock for challan items (reverse dispatch deductions).
 */
function challanReturnRestoreStock($conn, $sellId, array $items)
{
    $sellId = (int) $sellId;
    foreach ($items as $item) {
        if (challanReturnIsSerialItem($item)) {
            $distId = (int) ($item['ProductId'] ?? 0);
            if ($distId > 0) {
                $conn->query("UPDATE tbl_distibute_item_details2 SET SellStatus=0, SellId=0 WHERE id='$distId'");
            }
        }
    }
    $conn->query("DELETE FROM tbl_stocks WHERE SellId='$sellId' AND SellType='Challan'");
}

/**
 * Deduct stock when challan is edited / re-dispatched.
 */
function challanReturnDeductStock($conn, $sellId, $branchId, $invoiceDate, $narration, $userId, array $bulkItems, array $serialItems)
{
    $sellId = (int) $sellId;
    $branchId = (int) $branchId;
    $userId = (int) $userId;
    $invoiceDate = mysqli_real_escape_string($conn, (string) $invoiceDate);
    $narration = mysqli_real_escape_string($conn, (string) $narration);

    foreach ($bulkItems as $item) {
        $productId = (int) ($item['ProductId'] ?? 0);
        $productName = mysqli_real_escape_string($conn, (string) ($item['ProductName'] ?? ''));
        $qty = (float) ($item['Qty'] ?? 0);
        $modelNo = mysqli_real_escape_string($conn, (string) ($item['ModelNo'] ?? ''));
        $serialNo = mysqli_real_escape_string($conn, (string) ($item['SerialNo'] ?? 'N/A'));
        $purity = mysqli_real_escape_string($conn, (string) ($item['Purity'] ?? ''));
        $custId = (int) ($item['UserId'] ?? 0);

        if ($productId < 1 || $qty <= 0) {
            continue;
        }

        $sql22 = "INSERT INTO tbl_sell_products SET UserId='$custId',SellId='$sellId',ProductName='$productName',Purity='$purity',Qty='$qty',ProductId='$productId',ModelNo='$modelNo',SellDate='$invoiceDate',SerialNo='$serialNo',BranchId='$branchId'";
        if (!$conn->query($sql22)) {
            return ['success' => false, 'message' => 'Failed to save challan product.'];
        }
        $postId = (int) mysqli_insert_id($conn);

        $sqlStock = "INSERT INTO tbl_stocks SET SellId='$sellId',ProductId='$productId',ProductName='$productName',Qty='$qty',Status='1',CrDr='dr',CreatedBy='$userId',CreatedDate='$invoiceDate',Narration='$narration',PostId='$postId',BranchId='$branchId',SellType='Challan',SerialNo='$serialNo',ModelNo='$modelNo',ProdType='0'";
        if (!$conn->query($sqlStock)) {
            return ['success' => false, 'message' => 'Failed to update stock for bulk item.'];
        }
    }

    foreach ($serialItems as $item) {
        $distId = (int) ($item['id'] ?? $item['ProductId'] ?? 0);
        if ($distId < 1) {
            continue;
        }

        $dist = getRecord("SELECT * FROM tbl_distibute_item_details2 WHERE id='$distId' LIMIT 1");
        if (!$dist) {
            return ['success' => false, 'message' => 'Serial item not found.'];
        }
        if ((int) ($dist['SellStatus'] ?? 0) === 1 && (int) ($dist['SellId'] ?? 0) !== $sellId) {
            return ['success' => false, 'message' => 'Serial no ' . ($dist['SerialNo'] ?? '') . ' is already assigned to another challan.'];
        }

        $productName = mysqli_real_escape_string($conn, (string) ($dist['ProductName'] ?? ''));
        $serialNo = mysqli_real_escape_string($conn, (string) ($dist['SerialNo'] ?? ''));
        $modelNo = mysqli_real_escape_string($conn, (string) ($dist['ModelNo'] ?? ''));
        $purity = mysqli_real_escape_string($conn, (string) ($dist['Unit'] ?? ''));
        $custId = (int) ($item['UserId'] ?? 0);

        $sql22 = "INSERT INTO tbl_sell_products SET UserId='$custId',SellId='$sellId',ProductName='$productName',Purity='$purity',Qty='1',ProductId='$distId',ModelNo='$modelNo',SellDate='$invoiceDate',SerialNo='$serialNo',BranchId='$branchId'";
        if (!$conn->query($sql22)) {
            return ['success' => false, 'message' => 'Failed to save serial challan product.'];
        }
        $postId = (int) mysqli_insert_id($conn);

        $sqlStock = "INSERT INTO tbl_stocks SET SellId='$sellId',ProductId='$distId',ProductName='$productName',Qty='1',Status='1',CrDr='dr',CreatedBy='$userId',CreatedDate='$invoiceDate',Narration='$narration',PostId='$postId',BranchId='$branchId',SellType='Challan',SerialNo='$serialNo',ModelNo='$modelNo',ProdType='1'";
        if (!$conn->query($sqlStock)) {
            return ['success' => false, 'message' => 'Failed to update stock for serial item.'];
        }

        if (!$conn->query("UPDATE tbl_distibute_item_details2 SET SellId='$sellId',SellStatus=1 WHERE id='$distId'")) {
            return ['success' => false, 'message' => 'Failed to assign serial number to challan.'];
        }
    }

    return ['success' => true, 'message' => 'Stock updated successfully.'];
}

/**
 * Process full challan return with transaction.
 */
function challanReturnProcess($conn, $sellId, $returnDate, $remarks, $userId)
{
    challanReturnEnsureTables($conn);
    $sellId = (int) $sellId;
    $userId = (int) $userId;
    $returnDate = trim((string) $returnDate);
    $remarks = trim((string) $remarks);

    if ($sellId < 1) {
        return ['success' => false, 'message' => 'Invalid challan.'];
    }
    if ($returnDate === '' || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $returnDate)) {
        return ['success' => false, 'message' => 'Please enter a valid return date.'];
    }
    if (challanReturnAlreadyReturned($conn, $sellId)) {
        return ['success' => false, 'message' => 'This challan has already been returned.'];
    }

    $sell = getRecord("SELECT * FROM tbl_sell WHERE id='$sellId' AND Status=1 LIMIT 1");
    if (!$sell) {
        return ['success' => false, 'message' => 'Challan not found or inactive.'];
    }

    $items = challanReturnGetSellItems($conn, $sellId);
    if (empty($items)) {
        return ['success' => false, 'message' => 'No items found on this challan.'];
    }

    $conn->begin_transaction();
    try {
        $remarksEsc = mysqli_real_escape_string($conn, $remarks);
        $now = date('Y-m-d H:i:s');
        $sqlReturn = "INSERT INTO challan_returns SET sell_id='$sellId', return_date='$returnDate', remarks='$remarksEsc', created_by='$userId', created_at='$now', updated_at='$now'";
        if (!$conn->query($sqlReturn)) {
            throw new Exception('Failed to save challan return record.');
        }
        $returnId = (int) mysqli_insert_id($conn);

        foreach ($items as $item) {
            $productId = (int) ($item['ProductId'] ?? 0);
            $itemId = (int) ($item['id'] ?? 0);
            $qty = (float) ($item['Qty'] ?? 0);
            $serialNo = mysqli_real_escape_string($conn, (string) ($item['SerialNo'] ?? ''));
            $productName = mysqli_real_escape_string($conn, (string) ($item['ProductName'] ?? ''));
            $modelNo = mysqli_real_escape_string($conn, (string) ($item['ModelNo'] ?? ''));
            $unit = mysqli_real_escape_string($conn, (string) ($item['Purity'] ?? ''));
            $prodType = challanReturnIsSerialItem($item) ? 1 : 0;

            $sqlItem = "INSERT INTO challan_return_items SET return_id='$returnId', sell_id='$sellId', product_id='$productId', item_id='$itemId', serial_no='$serialNo', qty='$qty', prod_type='$prodType', product_name='$productName', model_no='$modelNo', unit='$unit', created_at='$now'";
            if (!$conn->query($sqlItem)) {
                throw new Exception('Failed to save returned item details.');
            }
        }

        challanReturnRestoreStock($conn, $sellId, $items);

        if (!$conn->query("UPDATE tbl_sell SET ReturnStatus=1, ContractorAssignStatus=0, ContractorAssignId=0 WHERE id='$sellId'")) {
            throw new Exception('Failed to update challan return status.');
        }

        challanReturnLogActivity($conn, $sellId, $returnId, 'return', $items, [], $remarks, $userId);

        $conn->commit();
        return ['success' => true, 'message' => 'Challan returned successfully. Stock has been restored.', 'return_id' => $returnId];
    } catch (Exception $e) {
        $conn->rollback();
        return ['success' => false, 'message' => $e->getMessage()];
    }
}

/**
 * Edit returned challan — replace items, deduct stock, ready for dispatch officer assignment.
 */
function challanReturnEditProcess($conn, $sellId, $remarks, $userId, array $bulkItems, array $serialDistIds, $custId, $branchId, $invoiceDate, $narration)
{
    challanReturnEnsureTables($conn);
    $sellId = (int) $sellId;
    $userId = (int) $userId;
    $custId = (int) $custId;
    $branchId = (int) $branchId;

    if ($sellId < 1) {
        return ['success' => false, 'message' => 'Invalid challan.'];
    }

    $sell = getRecord("SELECT * FROM tbl_sell WHERE id='$sellId' AND Status=1 LIMIT 1");
    if (!$sell) {
        return ['success' => false, 'message' => 'Challan not found.'];
    }

    $returnRec = getRecord("SELECT * FROM challan_returns WHERE sell_id='$sellId' LIMIT 1");
    if (!$returnRec) {
        return ['success' => false, 'message' => 'Return record not found for this challan.'];
    }

    $oldItems = challanReturnGetSellItems($conn, $sellId);

    $preparedBulk = [];
    foreach ($bulkItems as $item) {
        if ((int) ($item['CheckId'] ?? 0) !== 1) {
            continue;
        }
        $qty = (float) ($item['Qty'] ?? 0);
        if ($qty <= 0) {
            continue;
        }
        $item['UserId'] = $custId;
        $preparedBulk[] = $item;
    }

    $preparedSerial = [];
    foreach ($serialDistIds as $distId) {
        $distId = (int) $distId;
        if ($distId > 0) {
            $preparedSerial[] = ['id' => $distId, 'UserId' => $custId];
        }
    }

    if (empty($preparedBulk) && empty($preparedSerial)) {
        return ['success' => false, 'message' => 'Please select at least one item for the challan.'];
    }

    foreach ($preparedBulk as $item) {
        $productId = (int) ($item['ProductId'] ?? 0);
        $qty = (float) ($item['Qty'] ?? 0);
        $sql11 = "SELECT SUM(Qty) AS CrQty FROM tbl_distibute_item_details2 WHERE ProductId='$productId' AND BranchId='$branchId'";
        $row11 = getRecord($sql11);
        $crQty = (float) ($row11['CrQty'] ?? 0);
        $sqlDr = "SELECT SUM(Qty) AS DrQty FROM tbl_stocks WHERE CrDr='dr' AND ProductId='$productId' AND BranchId='$branchId'";
        $rowDr = getRecord($sqlDr);
        $drQty = (float) ($rowDr['DrQty'] ?? 0);
        $balQty = $crQty - $drQty;
        if ($qty > $balQty) {
            $pname = (string) ($item['ProductName'] ?? 'Product');
            return ['success' => false, 'message' => 'Insufficient stock for ' . $pname . '. Available: ' . $balQty];
        }
    }

    $conn->begin_transaction();
    try {
        // Re-edit: reverse stock from current challan lines before replacing items.
        if (!empty($oldItems) && (int) ($sell['ReturnStatus'] ?? 0) === 0) {
            challanReturnRestoreStock($conn, $sellId, $oldItems);
        }

        if (!$conn->query("DELETE FROM tbl_sell_products WHERE SellId='$sellId'")) {
            throw new Exception('Failed to clear old challan items.');
        }

        $stockResult = challanReturnDeductStock($conn, $sellId, $branchId, $invoiceDate, $narration, $userId, $preparedBulk, $preparedSerial);
        if (!$stockResult['success']) {
            throw new Exception($stockResult['message']);
        }

        $newItems = challanReturnGetSellItems($conn, $sellId);

        if (!$conn->query("UPDATE tbl_sell SET ReturnStatus=0, ContractorAssignStatus=0, ContractorAssignId=0, ContractorAssignDate=NULL WHERE id='$sellId'")) {
            throw new Exception('Failed to update challan status for re-dispatch.');
        }

        challanReturnLogActivity($conn, $sellId, (int) $returnRec['id'], 'edit', $oldItems, $newItems, $remarks, $userId);

        $conn->commit();
        return ['success' => true, 'message' => 'Challan updated successfully. It is ready to assign to dispatch officer again.'];
    } catch (Exception $e) {
        $conn->rollback();
        return ['success' => false, 'message' => $e->getMessage()];
    }
}
