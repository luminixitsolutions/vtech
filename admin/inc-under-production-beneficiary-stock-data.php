<?php

/**
 * Merge required stock lines by product id (saved customer spec wins over master template).
 *
 * @param list<array{ProductId:int,ProductName:string,ReqQty:float}> $base
 * @param list<array{ProductId:int,ProductName:string,ReqQty:float}> $extra
 * @return list<array{ProductId:int,ProductName:string,ReqQty:float}>
 */
function upb_merge_required_lines_by_product(array $base, array $extra)
{
    $byProduct = [];
    foreach ($base as $ln) {
        $pid = (int) ($ln['ProductId'] ?? 0);
        $qty = (float) ($ln['ReqQty'] ?? 0);
        if ($pid <= 0 || $qty <= 0) {
            continue;
        }
        $byProduct[$pid] = [
            'ProductId' => $pid,
            'ProductName' => (string) ($ln['ProductName'] ?? ''),
            'ReqQty' => $qty,
        ];
    }
    foreach ($extra as $ln) {
        $pid = (int) ($ln['ProductId'] ?? 0);
        $qty = (float) ($ln['ReqQty'] ?? 0);
        if ($pid <= 0 || $qty <= 0 || isset($byProduct[$pid])) {
            continue;
        }
        $byProduct[$pid] = [
            'ProductId' => $pid,
            'ProductName' => (string) ($ln['ProductName'] ?? ''),
            'ReqQty' => $qty,
        ];
    }

    $out = array_values($byProduct);
    usort($out, function ($a, $b) {
        return strcasecmp((string) $a['ProductName'], (string) $b['ProductName']);
    });

    return $out;
}

/**
 * Structure BOM from master spec (Add Pump Customer → Structure Product Specification / view2).
 *
 * @return list<array{ProductId:int,ProductName:string,ReqQty:float}>
 */
function upb_fetch_structure_master_lines_for_customer($conn, $custId)
{
    $custId = (int) $custId;
    if ($custId <= 0) {
        return [];
    }

    $cust = getRecord(
        "SELECT Surface, PumpCapacity, ModuleWatt, ModuleQty, Structure1, Structure2, Structure3,
                ModuleMake, StructureMake, AgencyId, SchemeId
         FROM tbl_users
         WHERE id='" . $custId . "' LIMIT 1"
    );
    if (!$cust || !is_array($cust)) {
        return [];
    }

    $esc = function ($value) use ($conn) {
        return $conn->real_escape_string(trim((string) $value));
    };

    $filters = [
        'Surface' => $esc($cust['Surface'] ?? ''),
        'PumpCapacity' => $esc($cust['PumpCapacity'] ?? ''),
        'ModuleWatt' => $esc($cust['ModuleWatt'] ?? ''),
        'ModuleQty' => $esc($cust['ModuleQty'] ?? ''),
        'ModuleMake' => $esc($cust['ModuleMake'] ?? ''),
        'StructureMake' => $esc($cust['StructureMake'] ?? ''),
        'AgencyId' => $esc($cust['AgencyId'] ?? ''),
        'SchemeId' => $esc($cust['SchemeId'] ?? ''),
    ];

    $structureIds = [];
    foreach (['Structure1', 'Structure2', 'Structure3'] as $field) {
        $sid = trim((string) ($cust[$field] ?? ''));
        if ($sid !== '') {
            $structureIds[] = $esc($sid);
        }
    }
    if (count($structureIds) === 0) {
        return [];
    }

    $byProduct = [];
    foreach ($structureIds as $structureId) {
        $sql = "SELECT tp.id AS ProductId,
                       tp.ProductName,
                       SUM(COALESCE(tps.Qty, 0)) AS ReqQty
                FROM tbl_struct_product_specification tps
                INNER JOIN tbl_products tp ON tps.ProdId = tp.id
                WHERE tps.Qty > 0 AND tp.ProdSpec = 2 AND tps.Structure = '" . $structureId . "'";
        foreach ($filters as $column => $value) {
            if ($value !== '') {
                $sql .= " AND tps." . $column . " = '" . $value . "'";
            }
        }
        $sql .= " GROUP BY tp.id, tp.ProductName ORDER BY tp.ProductName ASC";

        $rows = getList($sql);
        if (!is_array($rows)) {
            continue;
        }
        foreach ($rows as $row) {
            $pid = (int) ($row['ProductId'] ?? 0);
            $qty = (float) ($row['ReqQty'] ?? 0);
            if ($pid <= 0 || $qty <= 0) {
                continue;
            }
            if (!isset($byProduct[$pid])) {
                $byProduct[$pid] = [
                    'ProductId' => $pid,
                    'ProductName' => (string) ($row['ProductName'] ?? ''),
                    'ReqQty' => 0.0,
                ];
            }
            $byProduct[$pid]['ReqQty'] += $qty;
            if ($byProduct[$pid]['ProductName'] === '' && !empty($row['ProductName'])) {
                $byProduct[$pid]['ProductName'] = (string) $row['ProductName'];
            }
        }
    }

    $out = array_values($byProduct);
    usort($out, function ($a, $b) {
        return strcasecmp((string) $a['ProductName'], (string) $b['ProductName']);
    });

    return $out;
}

/**
 * Required stock lines for one done beneficiary (BOM / quotation), same rules as required-stock page.
 *
 * @return list<array{ProductId:int,ProductName:string,ReqQty:float}>
 */
function upb_fetch_required_lines_for_customer($conn, $custId)
{
    $custId = (int) $custId;
    if ($custId <= 0) {
        return [];
    }

    $lines = getList(
        "SELECT cps.ProdId AS ProductId,
                MAX(COALESCE(
                    NULLIF(TRIM(cps.ProdName), ''),
                    NULLIF(TRIM(tp.ProductName), ''),
                    CONCAT('Product #', cps.ProdId)
                )) AS ProductName,
                SUM(COALESCE(CAST(NULLIF(TRIM(cps.Qty), '') AS DECIMAL(12,2)), 0)) AS ReqQty
         FROM tbl_cust_product_specification cps
         LEFT JOIN tbl_products tp ON tp.id = cps.ProdId
         WHERE cps.CustId = '" . $custId . "'
         GROUP BY cps.ProdId
         HAVING SUM(COALESCE(CAST(NULLIF(TRIM(cps.Qty), '') AS DECIMAL(12,2)), 0)) > 0
         ORDER BY MAX(COALESCE(
                    NULLIF(TRIM(cps.ProdName), ''),
                    NULLIF(TRIM(tp.ProductName), ''),
                    CONCAT('Product #', cps.ProdId)
                )) ASC"
    );
    if (!is_array($lines)) {
        $lines = [];
    }
    if (count($lines) === 0) {
        $lines = getList(
            "SELECT qop.ProductId, qop.ProductName, SUM(COALESCE(qop.Qty,0)) AS ReqQty
             FROM tbl_quotation_order_products qop
             INNER JOIN tbl_quotation q ON q.id = qop.SellId AND q.CustId = '" . $custId . "'
             GROUP BY qop.ProductId, qop.ProductName
             ORDER BY qop.ProductName ASC"
        );
        if (!is_array($lines)) {
            $lines = [];
        }
    }

    $structureLines = upb_fetch_structure_master_lines_for_customer($conn, $custId);
    if (count($structureLines) > 0) {
        $lines = upb_merge_required_lines_by_product($lines, $structureLines);
    }

    return $lines;
}

/**
 * Aggregate required stock across multiple customers (sum qty per product).
 *
 * @param int[] $custIds
 * @return list<array{ProductId:int,ProductName:string,ReqQty:float}>
 */
function upb_fetch_combined_required_lines($conn, array $custIds)
{
    $byProduct = [];
    foreach ($custIds as $custId) {
        $custId = (int) $custId;
        if ($custId <= 0) {
            continue;
        }
        foreach (upb_fetch_required_lines_for_customer($conn, $custId) as $ln) {
            $pid = (int) ($ln['ProductId'] ?? 0);
            $name = (string) ($ln['ProductName'] ?? '');
            $qty = (float) ($ln['ReqQty'] ?? 0);
            if ($qty <= 0) {
                continue;
            }
            if (!isset($byProduct[$pid])) {
                $byProduct[$pid] = [
                    'ProductId' => $pid,
                    'ProductName' => $name,
                    'ReqQty' => 0.0,
                ];
            }
            $byProduct[$pid]['ReqQty'] += $qty;
            if ($name !== '' && ($byProduct[$pid]['ProductName'] === '' || strpos($byProduct[$pid]['ProductName'], 'Product #') === 0)) {
                $byProduct[$pid]['ProductName'] = $name;
            }
        }
    }

    $out = array_values($byProduct);
    usort($out, function ($a, $b) {
        return strcasecmp((string) $a['ProductName'], (string) $b['ProductName']);
    });

    return $out;
}

/**
 * Numeric qty from tbl_common_master.Name (e.g. "67" modules).
 */
function upb_common_master_numeric_qty($conn, $masterId)
{
    $masterId = (int) $masterId;
    if ($masterId <= 0) {
        return 0;
    }
    $row = getRecord("SELECT Name FROM tbl_common_master WHERE id='" . $masterId . "' LIMIT 1");
    $name = trim((string) ($row['Name'] ?? ''));
    if ($name === '') {
        return 0;
    }
    if (preg_match('/(\d+)/', $name, $m)) {
        return (int) $m[1];
    }

    return 0;
}

/**
 * Whether a product has (or had) serial-number stock rows in distribute tables.
 */
function upb_product_has_serial_inventory($conn, $productId)
{
    $productId = (int) $productId;
    if ($productId <= 0) {
        return false;
    }
    $n = getRow(
        "SELECT d.id FROM tbl_distibute_item_details d
         WHERE d.ProductId='" . $productId . "'
           AND d.ProdType IN (1, 2)
           AND TRIM(IFNULL(d.SerialNo, '')) <> ''
           AND UPPER(TRIM(d.SerialNo)) <> 'N/A'
         LIMIT 1"
    );
    if ($n > 0) {
        return true;
    }

    return getRow(
        "SELECT d2.id FROM tbl_distibute_item_details2 d2
         WHERE d2.ProductId='" . $productId . "'
           AND d2.ProdType IN (1, 2)
           AND TRIM(IFNULL(d2.SerialNo, '')) <> ''
           AND UPPER(TRIM(d2.SerialNo)) <> 'N/A'
         LIMIT 1"
    ) > 0;
}

/**
 * Serial-no product = Product Type &quot;Serial No Product&quot; on Add Product (tbl_products.Roll = 1).
 */
function upb_product_is_serial_for_stock($conn, $productId)
{
    return upb_product_is_serial($conn, $productId);
}

/**
 * Roll=1 BOS master lines for a customer profile (pump / module / controller).
 *
 * @return list<array{ProductId:int,ProductName:string,ReqQty:float}>
 */
function upb_fetch_bos_serial_master_lines_for_customer($conn, $custId)
{
    $custId = (int) $custId;
    if ($custId <= 0) {
        return [];
    }
    $cust = getRecord(
        "SELECT AcDc, Surface, PumpCapacity, WaterSource, BoreDia, PumpHead, AgencyId, PumpOutletSize, ModuleQty
         FROM tbl_users WHERE id='" . $custId . "' LIMIT 1"
    );
    if (!$cust || !is_array($cust)) {
        return [];
    }
    $esc = function ($value) use ($conn) {
        return $conn->real_escape_string(trim((string) $value));
    };
    $filters = [
        'AcDc' => $esc($cust['AcDc'] ?? ''),
        'Surface' => $esc($cust['Surface'] ?? ''),
        'PumpCapacity' => $esc($cust['PumpCapacity'] ?? ''),
        'WaterSource' => $esc($cust['WaterSource'] ?? ''),
        'BoreDia' => $esc($cust['BoreDia'] ?? ''),
        'PumpHead' => $esc($cust['PumpHead'] ?? ''),
        'AgencyId' => $esc($cust['AgencyId'] ?? ''),
        'PumpOutletSize' => $esc($cust['PumpOutletSize'] ?? ''),
    ];
    $moduleQty = upb_common_master_numeric_qty($conn, $cust['ModuleQty'] ?? 0);
    if ($moduleQty <= 0) {
        $moduleQty = 1;
    }

    $sql = "SELECT tp.id AS ProductId, tp.ProductName,
                   COALESCE(MAX(CAST(NULLIF(TRIM(tps.Qty), '') AS DECIMAL(12,2))), 0) AS ReqQty
            FROM tbl_product_specification tps
            INNER JOIN tbl_products tp ON tps.ProdId = tp.id
            WHERE tps.Qty > 0 AND tp.Roll = 1 AND tp.Status = 1";
    foreach ($filters as $column => $value) {
        if ($value !== '') {
            $sql .= " AND tps." . $column . " = '" . $value . "'";
        }
    }
    $sql .= " GROUP BY tp.id, tp.ProductName ORDER BY tp.ProductName ASC";

    $out = [];
    $rows = getList($sql);
    if (!is_array($rows)) {
        return [];
    }
    foreach ($rows as $row) {
        $pid = (int) ($row['ProductId'] ?? 0);
        $qty = (float) ($row['ReqQty'] ?? 0);
        if ($pid <= 0) {
            continue;
        }
        if ($qty <= 0) {
            $name = strtoupper((string) ($row['ProductName'] ?? ''));
            if (preg_match('/MODULE|PV MODULE/i', $name)) {
                $qty = (float) $moduleQty;
            } else {
                $qty = 1.0;
            }
        }
        $out[] = [
            'ProductId' => $pid,
            'ProductName' => (string) ($row['ProductName'] ?? ''),
            'ReqQty' => $qty,
        ];
    }

    return $out;
}

/**
 * Common Roll=1 lines saved for other customers under the same agency (template).
 *
 * @return list<array{ProductId:int,ProductName:string,ReqQty:float}>
 */
function upb_fetch_agency_serial_template_lines($conn, $custId)
{
    $custId = (int) $custId;
    if ($custId <= 0) {
        return [];
    }
    $cust = getRecord("SELECT AgencyId, ModuleQty FROM tbl_users WHERE id='" . $custId . "' LIMIT 1");
    if (!$cust || !is_array($cust)) {
        return [];
    }
    $agencyId = (int) ($cust['AgencyId'] ?? 0);
    if ($agencyId <= 0) {
        return [];
    }
    $moduleQty = upb_common_master_numeric_qty($conn, $cust['ModuleQty'] ?? 0);
    if ($moduleQty <= 0) {
        $moduleQty = 1;
    }

    $sql = "SELECT cps.ProdId AS ProductId,
                   MAX(COALESCE(NULLIF(TRIM(tp.ProductName), ''), NULLIF(TRIM(cps.ProdName), ''), CONCAT('Product #', cps.ProdId))) AS ProductName,
                   MAX(CAST(NULLIF(TRIM(cps.Qty), '') AS DECIMAL(12,2))) AS ReqQty,
                   COUNT(DISTINCT cps.CustId) AS peer_count
            FROM tbl_cust_product_specification cps
            INNER JOIN tbl_products tp ON tp.id = cps.ProdId AND tp.Roll = 1
            INNER JOIN tbl_users u ON u.id = cps.CustId AND u.AgencyId = '" . $agencyId . "'
            WHERE cps.CustId != '" . $custId . "'
            GROUP BY cps.ProdId
            HAVING MAX(CAST(NULLIF(TRIM(cps.Qty), '') AS DECIMAL(12,2))) > 0
            ORDER BY peer_count DESC, ProductName ASC";
    $rows = getList($sql);
    if (!is_array($rows)) {
        return [];
    }
    $out = [];
    foreach ($rows as $row) {
        $pid = (int) ($row['ProductId'] ?? 0);
        $qty = (float) ($row['ReqQty'] ?? 0);
        $name = (string) ($row['ProductName'] ?? '');
        if ($pid <= 0) {
            continue;
        }
        if (preg_match('/MODULE|PV MODULE/i', strtoupper($name)) && $moduleQty > 0) {
            $qty = (float) $moduleQty;
        }
        if ($qty <= 0) {
            continue;
        }
        $out[] = [
            'ProductId' => $pid,
            'ProductName' => $name,
            'ReqQty' => $qty,
        ];
    }

    return $out;
}

/**
 * Every active Serial No Product in product master (Add Product → Roll = 1).
 *
 * @return list<array{ProductId:int,ProductName:string,ReqQty:float}>
 */
function upb_fetch_all_serial_product_catalog($conn)
{
    $rows = getList(
        "SELECT id AS ProductId, ProductName
         FROM tbl_products
         WHERE Roll = 1 AND Status = 1
         ORDER BY ProductName ASC"
    );
    if (!is_array($rows)) {
        return [];
    }
    $out = [];
    foreach ($rows as $row) {
        $pid = (int) ($row['ProductId'] ?? 0);
        if ($pid <= 0) {
            continue;
        }
        $out[] = [
            'ProductId' => $pid,
            'ProductName' => (string) ($row['ProductName'] ?? ('Product #' . $pid)),
            'ReqQty' => 0.0,
        ];
    }

    return $out;
}

/**
 * Count active Serial No Product rows in product master.
 */
function upb_count_serial_product_catalog($conn)
{
    return (int) getRow("SELECT COUNT(*) AS c FROM tbl_products WHERE Roll = 1 AND Status = 1");
}

/**
 * Paginated Serial No Product catalog (product master only).
 *
 * @return list<array{ProductId:int,ProductName:string,ReqQty:float}>
 */
function upb_fetch_serial_product_catalog_page($conn, $offset, $limit, $search = '')
{
    $offset = max(0, (int) $offset);
    $limit = max(1, min(50, (int) $limit));
    $search = trim((string) $search);
    $where = 'Roll = 1 AND Status = 1';
    if ($search !== '') {
        $where .= " AND ProductName LIKE '%" . $conn->real_escape_string($search) . "%'";
    }
    $rows = getList(
        "SELECT id AS ProductId, ProductName
         FROM tbl_products
         WHERE " . $where . "
         ORDER BY ProductName ASC
         LIMIT " . $offset . ", " . $limit
    );
    if (!is_array($rows)) {
        return [];
    }
    $out = [];
    foreach ($rows as $row) {
        $pid = (int) ($row['ProductId'] ?? 0);
        if ($pid <= 0) {
            continue;
        }
        $out[] = [
            'ProductId' => $pid,
            'ProductName' => (string) ($row['ProductName'] ?? ('Product #' . $pid)),
            'ReqQty' => 0.0,
        ];
    }

    return $out;
}

/**
 * Required qty map for one or more customers (combined sums duplicate product ids).
 *
 * @param int[] $custIds
 * @return array<int, float>
 */
function upb_serial_required_qty_map_for_customer_ids($conn, array $custIds)
{
    $reqMap = [];
    foreach ($custIds as $custId) {
        $custId = (int) $custId;
        if ($custId <= 0) {
            continue;
        }
        $lines = upb_fetch_required_lines_for_customer($conn, $custId);
        foreach (upb_serial_required_qty_map_for_customer($conn, $custId, $lines) as $pid => $qty) {
            $pid = (int) $pid;
            $qty = (float) $qty;
            if ($pid <= 0 || $qty <= 0) {
                continue;
            }
            if (!isset($reqMap[$pid])) {
                $reqMap[$pid] = 0.0;
            }
            $reqMap[$pid] += $qty;
        }
    }

    return $reqMap;
}

/**
 * Build serial-stock grid rows (avail counts) for AJAX / partial render.
 *
 * @param list<array{ProductId:int,ProductName:string,ReqQty:float}> $serialLines
 * @param list<array{branch_id:int,store_name:string}> $storeColumns
 * @return list<array<string,mixed>>
 */
function upb_build_serial_stock_row_payload($conn, array $serialLines, array $storeColumns, $startRowNum = 1)
{
    $rows = [];
    $n = max(1, (int) $startRowNum);
    foreach ($serialLines as $ln) {
        $pid = (int) ($ln['ProductId'] ?? 0);
        $req = (int) round((float) ($ln['ReqQty'] ?? 0));
        $name = (string) ($ln['ProductName'] ?? '');
        $totalSerials = ($pid > 0) ? upb_serial_available_count($conn, $pid, null) : 0;
        $short = ($pid > 0 && $req > 0 && $req > $totalSerials);
        $storeSerials = [];
        $locations = [];
        foreach ($storeColumns as $storeCol) {
            $bid = (int) ($storeCol['branch_id'] ?? 0);
            $cnt = ($pid > 0 && $bid > 0) ? upb_serial_available_count($conn, $pid, $bid) : 0;
            $storeSerials[] = [
                'branch_id' => $bid,
                'count' => (int) $cnt,
                'short' => ($pid > 0 && $req > 0 && $cnt < $req),
            ];
            if ($pid > 0 && $cnt > 0) {
                $locations[] = [
                    'StoreName' => 'Store (serial): ' . (string) ($storeCol['store_name'] ?? ''),
                    'AvailQty' => (float) $cnt,
                    'BranchId' => $bid,
                    'row_kind' => 'store_serial',
                    'branch_id' => $bid,
                    'store_exe_id' => 0,
                ];
            }
        }
        $rows[] = [
            'row_num' => $n++,
            'product_id' => $pid,
            'product_name' => $name,
            'req_qty' => $req,
            'store_serials' => $storeSerials,
            'total_serials' => (int) $totalSerials,
            'short' => $short,
            'locations' => $locations,
        ];
    }

    return $rows;
}

/**
 * Overlay required qty from map onto catalog lines.
 *
 * @param list<array{ProductId:int,ProductName:string,ReqQty:float}> $lines
 * @param array<int, float> $reqMap
 * @return list<array{ProductId:int,ProductName:string,ReqQty:float}>
 */
function upb_overlay_serial_req_map(array $lines, array $reqMap)
{
    $out = [];
    foreach ($lines as $ln) {
        $pid = (int) ($ln['ProductId'] ?? 0);
        if ($pid <= 0) {
            continue;
        }
        $out[] = [
            'ProductId' => $pid,
            'ProductName' => (string) ($ln['ProductName'] ?? ''),
            'ReqQty' => isset($reqMap[$pid]) ? (float) $reqMap[$pid] : 0.0,
        ];
    }

    return $out;
}

/**
 * Required qty per serial product id from customer BOM / BOS serial master.
 *
 * @param list<array{ProductId:int,ProductName:string,ReqQty:float}> $requiredLines
 * @return array<int, float>
 */
function upb_serial_required_qty_map_for_customer($conn, $custId, array $requiredLines)
{
    $map = [];
    foreach ($requiredLines as $ln) {
        $pid = (int) ($ln['ProductId'] ?? 0);
        if ($pid <= 0 || !upb_product_is_serial($conn, $pid)) {
            continue;
        }
        $qty = (float) ($ln['ReqQty'] ?? 0);
        if ($qty <= 0) {
            continue;
        }
        if (!isset($map[$pid])) {
            $map[$pid] = 0.0;
        }
        $map[$pid] += $qty;
    }
    foreach (upb_fetch_bos_serial_master_lines_for_customer($conn, $custId) as $ln) {
        $pid = (int) ($ln['ProductId'] ?? 0);
        $qty = (float) ($ln['ReqQty'] ?? 0);
        if ($pid <= 0 || $qty <= 0 || isset($map[$pid])) {
            continue;
        }
        $map[$pid] = $qty;
    }

    return $map;
}

/**
 * All Serial No Products for required-stock page, with customer required qty when applicable.
 *
 * @param list<array{ProductId:int,ProductName:string,ReqQty:float}>|null $requiredLines
 * @return list<array{ProductId:int,ProductName:string,ReqQty:float}>
 */
function upb_fetch_serial_required_lines_for_customer($conn, $custId, array $requiredLines = null)
{
    $custId = (int) $custId;
    if ($custId <= 0) {
        return [];
    }
    if ($requiredLines === null) {
        $requiredLines = upb_fetch_required_lines_for_customer($conn, $custId);
    }

    $reqMap = upb_serial_required_qty_map_for_customer($conn, $custId, $requiredLines);

    return upb_overlay_serial_req_map(upb_fetch_all_serial_product_catalog($conn), $reqMap);
}

/**
 * Split required lines into bulk vs serial-no sections.
 *
 * @param list<array{ProductId:int,ProductName:string,ReqQty:float}> $lines
 * @return array{bulk:list<array{ProductId:int,ProductName:string,ReqQty:float}>,serial:list<array{ProductId:int,ProductName:string,ReqQty:float}>}
 */
function upb_partition_required_lines($conn, array $lines, array $serialLines)
{
    $serialIds = [];
    foreach ($serialLines as $ln) {
        $pid = (int) ($ln['ProductId'] ?? 0);
        if ($pid > 0) {
            $serialIds[$pid] = true;
        }
    }
    $bulk = [];
    foreach ($lines as $ln) {
        $pid = (int) ($ln['ProductId'] ?? 0);
        if ($pid > 0 && (isset($serialIds[$pid]) || upb_product_is_serial($conn, $pid))) {
            continue;
        }
        $bulk[] = $ln;
    }

    return ['bulk' => $bulk, 'serial' => $serialLines];
}

/**
 * All Serial No Products for combined required-stock view.
 *
 * @param int[] $custIds
 * @return list<array{ProductId:int,ProductName:string,ReqQty:float}>
 */
function upb_fetch_combined_serial_required_lines($conn, array $custIds)
{
    $reqMap = upb_serial_required_qty_map_for_customer_ids($conn, $custIds);

    return upb_overlay_serial_req_map(upb_fetch_all_serial_product_catalog($conn), $reqMap);
}

/**
 * Unique store columns from customer account BranchId (Add Pump Customer → Store).
 *
 * @param int[] $custIds
 * @return list<array{branch_id:int,store_name:string}>
 */
function upb_fetch_customer_store_columns($conn, array $custIds)
{
    $custIds = array_values(array_filter(array_map('intval', $custIds)));
    if (count($custIds) === 0) {
        return [];
    }
    $in = implode(',', $custIds);
    $list = getList(
        "SELECT DISTINCT u.BranchId AS branch_id,
                COALESCE(NULLIF(TRIM(b.Name), ''), CONCAT('Store #', u.BranchId)) AS store_name
         FROM tbl_users u
         LEFT JOIN tbl_branch b ON b.id = u.BranchId
         WHERE u.id IN (" . $in . ") AND u.BranchId > 0
         ORDER BY store_name ASC, u.BranchId ASC"
    );
    if (!is_array($list)) {
        return [];
    }
    $out = [];
    $seen = [];
    foreach ($list as $row) {
        $bid = (int) ($row['branch_id'] ?? 0);
        if ($bid <= 0 || isset($seen[$bid])) {
            continue;
        }
        $seen[$bid] = true;
        $out[] = [
            'branch_id' => $bid,
            'store_name' => (string) ($row['store_name'] ?? ('Store #' . $bid)),
        ];
    }

    return $out;
}

/**
 * Item balance qty at a single store (distribute balance, then stock ledger fallback).
 */
function upb_store_balance_qty($conn, $productId, $branchId)
{
    $productId = (int) $productId;
    $branchId = (int) $branchId;
    if ($productId <= 0 || $branchId <= 0) {
        return 0;
    }

    $sqlStore = "SELECT
        (COALESCE(SUM(d.Qty), 0) - COALESCE((
            SELECT SUM(x.Qty) FROM tbl_distibute_item_details2 x
            WHERE x.BranchId = d.BranchId AND x.ProductId = d.ProductId AND x.ProdType = 0
        ), 0)) AS AvailQty
        FROM tbl_distibute_item_details d
        INNER JOIN tbl_distibute_items h ON h.id = d.DistId AND h.Status = 1
        WHERE d.ProdType = 0 AND d.ProductId='" . $productId . "' AND d.BranchId='" . $branchId . "'";
    $row = getRecord($sqlStore);
    $avail = isset($row['AvailQty']) ? (float) $row['AvailQty'] : 0;
    if ($avail > 0.0001) {
        return (int) max(0, round($avail));
    }

    return upb_stock_net($conn, $productId, $branchId);
}

/**
 * Net qty from stock ledger for bulk items (ProdType 0) at optional branch.
 */
function upb_stock_net($conn, $productId, $branchId = null)
{
    $productId = (int) $productId;
    if ($productId <= 0) {
        return 0;
    }
    $where = "Status=1 AND ProductId='" . $productId . "' AND ProdType=0";
    if ($branchId !== null && $branchId !== '' && $branchId !== 'all') {
        $bid = (int) $branchId;
        $where .= " AND BranchId='" . $bid . "'";
    }
    $row = getRecord(
        "SELECT SUM(CASE WHEN CrDr='cr' THEN Qty ELSE 0 END) AS crq,
                SUM(CASE WHEN CrDr='dr' THEN Qty ELSE 0 END) AS drq
         FROM tbl_stocks WHERE " . $where
    );
    $cr = isset($row['crq']) ? (float) $row['crq'] : 0;
    $dr = isset($row['drq']) ? (float) $row['drq'] : 0;
    return (int) max(0, round($cr - $dr));
}

/**
 * Validate customer ids belong to done-beneficiary stock report list.
 *
 * @param int[] $custIds
 * @return int[]
 */
function upb_validate_stock_report_customer_ids($conn, array $custIds)
{
    $ids = [];
    foreach ($custIds as $id) {
        $id = (int) $id;
        if ($id > 0) {
            $ids[$id] = $id;
        }
    }
    if (count($ids) === 0) {
        return [];
    }
    $in = implode(',', array_map('intval', array_values($ids)));
    $sql = "SELECT tp.id
            FROM tbl_users tp
            WHERE tp.id IN (" . $in . ")
            AND tp.SurveyMatch = 1 AND tp.ProjectType = 1 AND tp.UnderProdStatus = '1'
            AND NOT EXISTS (
                SELECT 1 FROM tbl_sell ts
                WHERE ts.CustId = tp.id AND ts.SellType = 'Challan' AND ts.Status = 1
            )";
    $valid = [];
    $res = $conn->query($sql);
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $valid[] = (int) $row['id'];
        }
    }
    return $valid;
}

/**
 * Per-store net qty for a product (bulk / ProdType 0) from stock ledger only.
 */
function upb_stock_by_branch($conn, $productId)
{
    $productId = (int) $productId;
    if ($productId <= 0) {
        return [];
    }
    $sql = "SELECT ts.BranchId,
                   COALESCE(
                       NULLIF(TRIM(MAX(tb.Name)), ''),
                       IF(ts.BranchId = 0, 'Main / central stock (ledger, not assigned to a store)',
                          CONCAT('Branch #', ts.BranchId, ' (no name in master)'))
                   ) AS StoreName,
                   SUM(CASE WHEN ts.CrDr='cr' THEN ts.Qty ELSE 0 END) -
                   SUM(CASE WHEN ts.CrDr='dr' THEN ts.Qty ELSE 0 END) AS AvailQty
            FROM tbl_stocks ts
            LEFT JOIN tbl_branch tb ON tb.id = ts.BranchId
            WHERE ts.Status=1 AND ts.ProductId='" . $productId . "' AND ts.ProdType=0
            GROUP BY ts.BranchId
            HAVING AvailQty > 0
            ORDER BY AvailQty DESC";
    $list = getList($sql);
    return is_array($list) ? $list : [];
}

/**
 * Where bulk qty sits: store net, dispatch officer lines, then ledger fallback.
 *
 * @return list<array{StoreName:string,AvailQty:float|int,row_kind:string,branch_id:int,store_exe_id:int}>
 */
function upb_available_locations($conn, $productId)
{
    $productId = (int) $productId;
    if ($productId <= 0) {
        return [];
    }

    $hasDispatchTransferTbl = false;
    $hasDetail2IdCol = false;
    $t1 = $conn->query("SHOW TABLES LIKE 'tbl_dispatch_to_store_transfer_details'");
    if ($t1 && $t1->num_rows > 0) {
        $hasDispatchTransferTbl = true;
        $c = $conn->query("SHOW COLUMNS FROM tbl_dispatch_to_store_transfer_details LIKE 'Detail2Id'");
        if ($c && $c->num_rows > 0) {
            $hasDetail2IdCol = true;
        }
    }
    $d2JoinOpen = ($hasDispatchTransferTbl && $hasDetail2IdCol)
        ? "LEFT JOIN (SELECT DISTINCT Detail2Id FROM tbl_dispatch_to_store_transfer_details WHERE Detail2Id IS NOT NULL) td_open ON td_open.Detail2Id = d2.id"
        : '';
    $d2WhereOpen = ($hasDispatchTransferTbl && $hasDetail2IdCol) ? 'AND td_open.Detail2Id IS NULL' : '';

    $out = [];

    $sqlStore = "SELECT d.BranchId, MAX(b.Name) AS BranchName,
        (COALESCE(SUM(d.Qty),0) - COALESCE((SELECT SUM(x.Qty) FROM tbl_distibute_item_details2 x
            WHERE x.BranchId = d.BranchId AND x.ProductId = d.ProductId AND x.ProdType = 0), 0)) AS AvailQty
        FROM tbl_distibute_item_details d
        INNER JOIN tbl_distibute_items h ON h.id = d.DistId AND h.Status = 1
        INNER JOIN tbl_branch b ON b.id = d.BranchId
        WHERE d.ProdType = 0 AND d.ProductId='" . $productId . "'
        GROUP BY d.BranchId
        HAVING AvailQty > 0.0001
        ORDER BY MAX(b.Name)";
    foreach (getList($sqlStore) as $r) {
        $bn = isset($r['BranchName']) ? trim((string) $r['BranchName']) : '';
        if ($bn === '') {
            continue;
        }
        $out[] = [
            'StoreName' => 'Store (balance): ' . $bn,
            'AvailQty' => $r['AvailQty'],
            'row_kind' => 'store',
            'branch_id' => (int) ($r['BranchId'] ?? 0),
            'store_exe_id' => 0,
        ];
    }

    $sqlDisp = "SELECT d2.StoreExeId, d2.BranchId,
        COALESCE(u.Fname, CONCAT('User #', d2.StoreExeId)) AS officer_name,
        COALESCE(NULLIF(TRIM(b.Name), ''), 'branch not set') AS assign_branch_name,
        SUM(d2.Qty) AS AvailQty
        FROM tbl_distibute_item_details2 d2
        INNER JOIN tbl_distibute_items2 h ON h.id = d2.DistId AND h.Status = 1
        LEFT JOIN tbl_users u ON u.id = d2.StoreExeId
        LEFT JOIN tbl_branch b ON b.id = d2.BranchId
        " . $d2JoinOpen . "
        WHERE d2.ProdType = 0 AND d2.StoreExeId > 0 AND d2.ProductId='" . $productId . "' " . $d2WhereOpen . "
        GROUP BY d2.StoreExeId, d2.BranchId, u.Fname, b.Name
        HAVING SUM(d2.Qty) > 0.0001
        ORDER BY officer_name, assign_branch_name";
    foreach (getList($sqlDisp) as $r) {
        $on = isset($r['officer_name']) ? trim((string) $r['officer_name']) : '';
        $br = isset($r['assign_branch_name']) ? trim((string) $r['assign_branch_name']) : '';
        if ($br === '') {
            $br = 'branch not set';
        }
        $out[] = [
            'StoreName' => 'Dispatch officer: ' . $on . ' (store: ' . $br . ')',
            'AvailQty' => $r['AvailQty'],
            'row_kind' => 'dispatch',
            'branch_id' => (int) ($r['BranchId'] ?? 0),
            'store_exe_id' => (int) ($r['StoreExeId'] ?? 0),
        ];
    }

    if (count($out) > 0) {
        return $out;
    }

    $ledger = upb_stock_by_branch($conn, $productId);
    if (!is_array($ledger)) {
        return [];
    }
    $wrapped = [];
    foreach ($ledger as $row) {
        $wrapped[] = array_merge($row, [
            'row_kind' => 'ledger',
            'branch_id' => isset($row['BranchId']) ? (int) $row['BranchId'] : 0,
            'store_exe_id' => 0,
        ]);
    }
    return $wrapped;
}

/**
 * Open dispatch-to-store transfer metadata (shared by bulk + serial location queries).
 *
 * @return array{has_transfer_tbl:bool,has_detail2_col:bool,d2_join_open:string,d2_where_open:string}
 */
function upb_dispatch_transfer_meta($conn)
{
    $hasTransferTbl = false;
    $hasDetail2IdCol = false;
    $t1 = $conn->query("SHOW TABLES LIKE 'tbl_dispatch_to_store_transfer_details'");
    if ($t1 && $t1->num_rows > 0) {
        $hasTransferTbl = true;
        $c = $conn->query("SHOW COLUMNS FROM tbl_dispatch_to_store_transfer_details LIKE 'Detail2Id'");
        if ($c && $c->num_rows > 0) {
            $hasDetail2IdCol = true;
        }
    }
    $d2JoinOpen = ($hasTransferTbl && $hasDetail2IdCol)
        ? "LEFT JOIN (SELECT DISTINCT Detail2Id FROM tbl_dispatch_to_store_transfer_details WHERE Detail2Id IS NOT NULL) td_open ON td_open.Detail2Id = d2.id"
        : '';
    $d2WhereOpen = ($hasTransferTbl && $hasDetail2IdCol) ? 'AND td_open.Detail2Id IS NULL' : '';

    return [
        'has_transfer_tbl' => $hasTransferTbl,
        'has_detail2_col' => $hasDetail2IdCol,
        'd2_join_open' => $d2JoinOpen,
        'd2_where_open' => $d2WhereOpen,
    ];
}

/**
 * ProdType values used in stock tables for a product Roll (1 = serial, 2 = bag serial).
 *
 * @return int[]
 */
function upb_serial_prod_types_for_roll($roll)
{
    $roll = (int) $roll;
    if ($roll === 2) {
        return [2];
    }
    if ($roll === 1) {
        return [1];
    }

    return [];
}

/**
 * Whether product is Serial No Product (Add Product → Product Type, tbl_products.Roll = 1).
 */
function upb_product_is_serial($conn, $productId)
{
    $productId = (int) $productId;
    if ($productId <= 0) {
        return false;
    }
    $row = getRecord("SELECT Roll FROM tbl_products WHERE id='" . $productId . "' LIMIT 1");
    $roll = (int) ($row['Roll'] ?? 0);

    return $roll === 1;
}

/**
 * Roll value for a product (cached per request in $cache).
 */
function upb_product_roll($conn, $productId, array &$cache = [])
{
    $productId = (int) $productId;
    if ($productId <= 0) {
        return 0;
    }
    if (isset($cache[$productId])) {
        return (int) $cache[$productId];
    }
    $row = getRecord("SELECT Roll FROM tbl_products WHERE id='" . $productId . "' LIMIT 1");
    $cache[$productId] = (int) ($row['Roll'] ?? 0);

    return (int) $cache[$productId];
}

/**
 * Count distinct available serial numbers for a product (optionally at one store).
 */
function upb_serial_available_count($conn, $productId, $branchId = null)
{
    static $memo = [];
    $productId = (int) $productId;
    if ($productId <= 0) {
        return 0;
    }
    $branchKey = $branchId === null ? -1 : (int) $branchId;
    $cacheKey = $productId . ':' . $branchKey;
    if (isset($memo[$cacheKey])) {
        return $memo[$cacheKey];
    }
    $rollCache = [];
    $roll = upb_product_roll($conn, $productId, $rollCache);
    $prodTypes = upb_serial_prod_types_for_roll($roll);
    if (count($prodTypes) === 0) {
        $memo[$cacheKey] = 0;
        return 0;
    }
    $prodTypeIn = implode(',', array_map('intval', $prodTypes));
    $meta = upb_dispatch_transfer_meta($conn);
    $branchId = $branchId !== null ? (int) $branchId : 0;
    $branchFilterStore = $branchId > 0 ? " AND d.BranchId='" . $branchId . "'" : '';
    $branchFilterDisp = $branchId > 0 ? " AND d2.BranchId='" . $branchId . "'" : '';

    $sql = "SELECT COUNT(DISTINCT loc.serial_no) AS cnt
            FROM (
                SELECT TRIM(d.SerialNo) AS serial_no
                FROM tbl_distibute_item_details d
                WHERE d.ProductId='" . $productId . "'
                  AND d.ProdType IN (" . $prodTypeIn . ")
                  AND TRIM(IFNULL(d.SerialNo, '')) <> ''
                  AND UPPER(TRIM(d.SerialNo)) <> 'N/A'
                  " . $branchFilterStore . "
                  AND NOT EXISTS (
                      SELECT 1 FROM tbl_distibute_item_details2 d2x
                      WHERE d2x.ProductId = d.ProductId
                        AND d2x.SerialNo = d.SerialNo
                        AND d2x.ProdType = d.ProdType
                        AND d2x.BranchId = d.BranchId
                  )
                UNION
                SELECT TRIM(d2.SerialNo) AS serial_no
                FROM tbl_distibute_item_details2 d2
                INNER JOIN tbl_distibute_items2 h ON h.id = d2.DistId AND h.Status = 1
                " . $meta['d2_join_open'] . "
                WHERE d2.ProductId='" . $productId . "'
                  AND d2.ProdType IN (" . $prodTypeIn . ")
                  AND TRIM(IFNULL(d2.SerialNo, '')) <> ''
                  AND UPPER(TRIM(d2.SerialNo)) <> 'N/A'
                  " . $meta['d2_where_open'] . "
                  " . $branchFilterDisp . "
            ) loc
            WHERE NOT EXISTS (
                SELECT 1 FROM tbl_stocks sx
                WHERE sx.ProdType IN (" . $prodTypeIn . ")
                  AND sx.CrDr = 'dr'
                  AND sx.SerialNo = loc.serial_no
            )";
    $row = getRecord($sql);

    $memo[$cacheKey] = (int) ($row['cnt'] ?? 0);

    return $memo[$cacheKey];
}

/**
 * Available serial numbers grouped by store / dispatch location (for modal).
 *
 * @return list<array{StoreName:string,AvailQty:int,row_kind:string,branch_id:int,store_exe_id:int}>
 */
function upb_serial_available_locations($conn, $productId)
{
    $productId = (int) $productId;
    if ($productId <= 0) {
        return [];
    }
    $rollCache = [];
    $roll = upb_product_roll($conn, $productId, $rollCache);
    $prodTypes = upb_serial_prod_types_for_roll($roll);
    if (count($prodTypes) === 0) {
        return [];
    }
    $prodTypeIn = implode(',', array_map('intval', $prodTypes));
    $meta = upb_dispatch_transfer_meta($conn);
    $out = [];

    $sqlStore = "SELECT d.BranchId, MAX(b.Name) AS BranchName, COUNT(DISTINCT TRIM(d.SerialNo)) AS AvailQty
        FROM tbl_distibute_item_details d
        INNER JOIN tbl_branch b ON b.id = d.BranchId
        WHERE d.ProductId='" . $productId . "'
          AND d.ProdType IN (" . $prodTypeIn . ")
          AND TRIM(IFNULL(d.SerialNo, '')) <> ''
          AND UPPER(TRIM(d.SerialNo)) <> 'N/A'
          AND NOT EXISTS (
              SELECT 1 FROM tbl_distibute_item_details2 d2x
              WHERE d2x.ProductId = d.ProductId
                AND d2x.SerialNo = d.SerialNo
                AND d2x.ProdType = d.ProdType
                AND d2x.BranchId = d.BranchId
          )
          AND NOT EXISTS (
              SELECT 1 FROM tbl_stocks sx
              WHERE sx.ProdType IN (" . $prodTypeIn . ")
                AND sx.CrDr = 'dr'
                AND sx.SerialNo = d.SerialNo
          )
        GROUP BY d.BranchId
        HAVING AvailQty > 0
        ORDER BY MAX(b.Name)";
    foreach (getList($sqlStore) as $r) {
        $bn = isset($r['BranchName']) ? trim((string) $r['BranchName']) : '';
        if ($bn === '') {
            continue;
        }
        $out[] = [
            'StoreName' => 'Store (serial): ' . $bn,
            'AvailQty' => (int) ($r['AvailQty'] ?? 0),
            'row_kind' => 'store_serial',
            'branch_id' => (int) ($r['BranchId'] ?? 0),
            'store_exe_id' => 0,
        ];
    }

    $sqlDisp = "SELECT d2.StoreExeId, d2.BranchId,
        COALESCE(u.Fname, CONCAT('User #', d2.StoreExeId)) AS officer_name,
        COALESCE(NULLIF(TRIM(b.Name), ''), 'branch not set') AS assign_branch_name,
        COUNT(DISTINCT TRIM(d2.SerialNo)) AS AvailQty
        FROM tbl_distibute_item_details2 d2
        INNER JOIN tbl_distibute_items2 h ON h.id = d2.DistId AND h.Status = 1
        LEFT JOIN tbl_users u ON u.id = d2.StoreExeId
        LEFT JOIN tbl_branch b ON b.id = d2.BranchId
        " . $meta['d2_join_open'] . "
        WHERE d2.ProductId='" . $productId . "'
          AND d2.ProdType IN (" . $prodTypeIn . ")
          AND d2.StoreExeId > 0
          AND TRIM(IFNULL(d2.SerialNo, '')) <> ''
          AND UPPER(TRIM(d2.SerialNo)) <> 'N/A'
          " . $meta['d2_where_open'] . "
          AND NOT EXISTS (
              SELECT 1 FROM tbl_stocks sx
              WHERE sx.ProdType IN (" . $prodTypeIn . ")
                AND sx.CrDr = 'dr'
                AND sx.SerialNo = d2.SerialNo
          )
        GROUP BY d2.StoreExeId, d2.BranchId, u.Fname, b.Name
        HAVING AvailQty > 0
        ORDER BY officer_name, assign_branch_name";
    foreach (getList($sqlDisp) as $r) {
        $on = isset($r['officer_name']) ? trim((string) $r['officer_name']) : '';
        $br = isset($r['assign_branch_name']) ? trim((string) $r['assign_branch_name']) : '';
        if ($br === '') {
            $br = 'branch not set';
        }
        $out[] = [
            'StoreName' => 'Dispatch officer (serial): ' . $on . ' (store: ' . $br . ')',
            'AvailQty' => (int) ($r['AvailQty'] ?? 0),
            'row_kind' => 'dispatch_serial',
            'branch_id' => (int) ($r['BranchId'] ?? 0),
            'store_exe_id' => (int) ($r['StoreExeId'] ?? 0),
        ];
    }

    return $out;
}

/**
 * Fetch customer rows for stock report / combined view.
 *
 * @param int[] $custIds
 * @return list<array>
 */
function upb_fetch_stock_report_customers($conn, array $custIds)
{
    $custIds = upb_validate_stock_report_customer_ids($conn, $custIds);
    if (count($custIds) === 0) {
        return [];
    }
    $in = implode(',', array_map('intval', $custIds));
    $list = getList(
        "SELECT id, BeneficiaryId, Fname, Phone, Address, BranchId
         FROM tbl_users
         WHERE id IN (" . $in . ")
         ORDER BY Fname ASC"
    );
    return is_array($list) ? $list : [];
}
