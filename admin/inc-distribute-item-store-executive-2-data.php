<?php
/**
 * Data queries for distribute-item-store-executive-2.
 * Sets: $productRows, $rncnt2, $bagRows, $rncnt223, $serialRows, $rncnt22
 */
$BranchId = (int) $BranchId;

$productSql = "SELECT tp.id AS ProductId, tp.ProductName AS Product_Name, tp.ModelNo AS Model_No, tp.Unit,
    (COALESCE(cr.CrQty, 0) - COALESCE(dr.DrQty, 0)) AS BalQty
    FROM (
        SELECT ProductId, SUM(Qty) AS CrQty
        FROM tbl_distibute_item_details
        WHERE ProdType = 0 AND BranchId = '$BranchId'
        GROUP BY ProductId
    ) cr
    LEFT JOIN (
        SELECT ProductId, SUM(Qty) AS DrQty
        FROM tbl_distibute_item_details2
        WHERE ProdType = 0 AND BranchId = '$BranchId'
        GROUP BY ProductId
    ) dr ON dr.ProductId = cr.ProductId
    INNER JOIN tbl_products tp ON tp.id = cr.ProductId
    HAVING BalQty > 0
    ORDER BY tp.id";
$productRows = getList($productSql);
$rncnt2 = count($productRows);

$bagSql = "SELECT d.id, d.ProductName, d.SerialNo
    FROM tbl_distibute_item_details d
    WHERE d.ProdType = 2 AND d.SerialNo != '' AND d.BranchId = '$BranchId'
    AND NOT EXISTS (
        SELECT 1 FROM tbl_distibute_item_details2 d2
        WHERE d2.ProdType = 2 AND d2.SerialNo = d.SerialNo AND d2.BranchId = '$BranchId'
        LIMIT 1
    )
    ORDER BY d.SerialNo";
$bagRows = getList($bagSql);
$rncnt223 = count($bagRows);

$serialSql = "SELECT d.id, d.ProductName, d.SerialNo
    FROM tbl_distibute_item_details d
    WHERE d.ProdType = 1 AND d.SerialNo != '' AND d.BranchId = '$BranchId'
    AND NOT EXISTS (
        SELECT 1 FROM tbl_distibute_item_details2 d2
        WHERE d2.ProdType = 1 AND d2.SerialNo = d.SerialNo AND d2.BranchId = '$BranchId'
        LIMIT 1
    )
    ORDER BY d.SerialNo";
$serialRows = getList($serialSql);
$rncnt22 = count($serialRows);
