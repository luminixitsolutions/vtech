<?php

/**
 * Sync master product / structure specifications to customer specifications.
 * Customers with an active delivery challan (tbl_sell.SellType = 'Challan') are skipped.
 */

function custSpecEsc($conn, $value)
{
    return $conn->real_escape_string(trim((string) $value));
}

function custSpecCustomerHasDeliveryChallan($conn, $custId)
{
    $custId = (int) $custId;
    if ($custId <= 0) {
        return true;
    }

    return getRow(
        "SELECT id FROM tbl_sell WHERE CustId='" . $custId . "' AND SellType='Challan' AND Status=1 LIMIT 1"
    ) > 0;
}

/**
 * @param array<string, string> $filters column => value; empty value = do not filter
 */
function custSpecBuildUserWhere($filters)
{
    $parts = ["Roll='5'", "ProjectType='1'"];
    foreach ($filters as $column => $value) {
        $value = trim((string) $value);
        if ($value !== '') {
            $parts[] = $column . "='" . $value . "'";
        }
    }

    return implode(' AND ', $parts);
}

/**
 * @return list<array<string, mixed>>
 */
function custSpecFindPumpCustomersWithoutChallan($conn, array $filters, $structureId = '')
{
    $where = custSpecBuildUserWhere($filters);
    if ($structureId !== '') {
        $structureId = custSpecEsc($conn, $structureId);
        $where .= " AND (Structure1='" . $structureId . "' OR Structure2='" . $structureId . "' OR Structure3='" . $structureId . "')";
    }

    $rows = getList(
        "SELECT id, AcDc, Surface, PumpCapacity, WaterSource, BoreDia, PumpHead, PumpOutletSize,
                ModuleWatt, ModuleQty, Structure1, Structure2, Structure3, AgencyId, SchemeId,
                ModuleMake, StructureMake
         FROM tbl_users
         WHERE " . $where . "
         ORDER BY id ASC"
    );
    if (!is_array($rows)) {
        return [];
    }

    $eligible = [];
    foreach ($rows as $row) {
        if (!custSpecCustomerHasDeliveryChallan($conn, (int) ($row['id'] ?? 0))) {
            $eligible[] = $row;
        }
    }

    return $eligible;
}

/**
 * @param array<string, string> $filters
 * @return list<array<string, mixed>>
 */
function custSpecFetchMasterBosLines($conn, array $filters)
{
    $sql = "SELECT tp.id AS ProdId, tp.ProductName AS ProdName, tp.Unit, tps.Qty
            FROM tbl_product_specification tps
            INNER JOIN tbl_products tp ON tps.ProdId = tp.id
            WHERE tp.Roll != 1 AND tps.Qty > 0 AND tp.ProdSpec = 1";

    foreach ($filters as $column => $value) {
        $value = trim((string) $value);
        if ($value !== '') {
            $sql .= " AND tps." . $column . "='" . custSpecEsc($conn, $value) . "'";
        }
    }

    $sql .= " GROUP BY tps.ProdId ORDER BY tp.ProductName ASC";
    $rows = getList($sql);

    return is_array($rows) ? $rows : [];
}

/**
 * @param array<string, mixed> $cust
 * @return list<array<string, mixed>>
 */
function custSpecFetchMasterStructLinesForCustomer($conn, array $cust)
{
    $esc = function ($value) use ($conn) {
        return custSpecEsc($conn, $value);
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
            $structureIds[$sid] = $esc($sid);
        }
    }
    if (count($structureIds) === 0) {
        return [];
    }

    $lines = [];
    foreach ($structureIds as $structureId) {
        $sql = "SELECT tp.id AS ProdId, tp.ProductName AS ProdName, tp.Unit,
                       tps.Qty, tps.Structure
                FROM tbl_struct_product_specification tps
                INNER JOIN tbl_products tp ON tps.ProdId = tp.id
                WHERE tps.Qty > 0 AND tp.ProdSpec = 2 AND tps.Structure = '" . $structureId . "'";
        foreach ($filters as $column => $value) {
            if ($value !== '') {
                $sql .= " AND tps." . $column . " = '" . $value . "'";
            }
        }
        $sql .= " GROUP BY tp.id, tps.Structure ORDER BY tp.ProductName ASC";

        $rows = getList($sql);
        if (!is_array($rows)) {
            continue;
        }
        foreach ($rows as $row) {
            $lines[] = $row;
        }
    }

    return $lines;
}

/**
 * @param array<string, string> $filters
 * @return array{updated:int, skipped:int, error:string}
 */
function syncCustomerBosSpecifications($conn, array $filters, $userId, $createdDate)
{
    $result = ['updated' => 0, 'skipped' => 0, 'error' => ''];
    $userId = (int) $userId;
    $createdDate = custSpecEsc($conn, $createdDate);

    $masterLines = custSpecFetchMasterBosLines($conn, $filters);
    $customers = custSpecFindPumpCustomersWithoutChallan($conn, [
        'AcDc' => $filters['AcDc'] ?? '',
        'Surface' => $filters['Surface'] ?? '',
        'PumpCapacity' => $filters['PumpCapacity'] ?? '',
        'WaterSource' => $filters['WaterSource'] ?? '',
        'BoreDia' => $filters['BoreDia'] ?? '',
        'PumpHead' => $filters['PumpHead'] ?? '',
        'AgencyId' => $filters['AgencyId'] ?? '',
        'PumpOutletSize' => $filters['PumpOutletSize'] ?? '',
    ]);

    foreach ($customers as $cust) {
        $custId = (int) ($cust['id'] ?? 0);
        if ($custId <= 0) {
            continue;
        }

        try {
            if (!$conn->query("DELETE FROM tbl_cust_product_specification WHERE CustId='" . $custId . "' AND SpecType='0'")) {
                throw new Exception($conn->error);
            }

            foreach ($masterLines as $line) {
                $prodId = (int) ($line['ProdId'] ?? 0);
                if ($prodId <= 0) {
                    continue;
                }
                $prodName = custSpecEsc($conn, $line['ProdName'] ?? '');
                $unit = custSpecEsc($conn, $line['Unit'] ?? '');
                $qty = custSpecEsc($conn, $line['Qty'] ?? '0');

                $sql = "INSERT INTO tbl_cust_product_specification SET
                    CustId='" . $custId . "',
                    AcDc='" . custSpecEsc($conn, $cust['AcDc'] ?? '') . "',
                    Surface='" . custSpecEsc($conn, $cust['Surface'] ?? '') . "',
                    PumpCapacity='" . custSpecEsc($conn, $cust['PumpCapacity'] ?? '') . "',
                    WaterSource='" . custSpecEsc($conn, $cust['WaterSource'] ?? '') . "',
                    BoreDia='" . custSpecEsc($conn, $cust['BoreDia'] ?? '') . "',
                    PumpHead='" . custSpecEsc($conn, $cust['PumpHead'] ?? '') . "',
                    ProdId='" . $prodId . "',
                    ProdName='" . $prodName . "',
                    Unit='" . $unit . "',
                    Qty='" . $qty . "',
                    CreatedBy='" . $userId . "',
                    CreatedDate='" . $createdDate . "',
                    SpecType='0',
                    ModuleWatt='" . custSpecEsc($conn, $cust['ModuleWatt'] ?? '') . "',
                    ModuleQty='" . custSpecEsc($conn, $cust['ModuleQty'] ?? '') . "',
                    Structure='0',
                    AgencyId='" . custSpecEsc($conn, $cust['AgencyId'] ?? '') . "',
                    PumpOutletSize='" . custSpecEsc($conn, $cust['PumpOutletSize'] ?? '') . "'";
                if (!$conn->query($sql)) {
                    throw new Exception($conn->error);
                }
            }

            $result['updated']++;
        } catch (Exception $e) {
            $result['error'] = $e->getMessage();
            return $result;
        }
    }

    $allMatched = getList(
        "SELECT id FROM tbl_users WHERE " . custSpecBuildUserWhere([
            'AcDc' => $filters['AcDc'] ?? '',
            'Surface' => $filters['Surface'] ?? '',
            'PumpCapacity' => $filters['PumpCapacity'] ?? '',
            'WaterSource' => $filters['WaterSource'] ?? '',
            'BoreDia' => $filters['BoreDia'] ?? '',
            'PumpHead' => $filters['PumpHead'] ?? '',
            'AgencyId' => $filters['AgencyId'] ?? '',
            'PumpOutletSize' => $filters['PumpOutletSize'] ?? '',
        ])
    );
    if (is_array($allMatched)) {
        foreach ($allMatched as $row) {
            if (custSpecCustomerHasDeliveryChallan($conn, (int) ($row['id'] ?? 0))) {
                $result['skipped']++;
            }
        }
    }

    return $result;
}

/**
 * @param array<string, string> $filters
 * @return array{updated:int, skipped:int, error:string}
 */
function syncCustomerStructSpecifications($conn, array $filters, $userId, $createdDate)
{
    $result = ['updated' => 0, 'skipped' => 0, 'error' => ''];
    $userId = (int) $userId;
    $createdDate = custSpecEsc($conn, $createdDate);
    $structureId = trim((string) ($filters['Structure'] ?? ''));

    $customers = custSpecFindPumpCustomersWithoutChallan($conn, [
        'Surface' => $filters['Surface'] ?? '',
        'PumpCapacity' => $filters['PumpCapacity'] ?? '',
        'ModuleWatt' => $filters['ModuleWatt'] ?? '',
        'ModuleQty' => $filters['ModuleQty'] ?? '',
        'ModuleMake' => $filters['ModuleMake'] ?? '',
        'StructureMake' => $filters['StructureMake'] ?? '',
        'AgencyId' => $filters['AgencyId'] ?? '',
        'SchemeId' => $filters['SchemeId'] ?? '',
    ], $structureId);

    foreach ($customers as $cust) {
        $custId = (int) ($cust['id'] ?? 0);
        if ($custId <= 0) {
            continue;
        }

        $structLines = custSpecFetchMasterStructLinesForCustomer($conn, $cust);

        try {
            if (!$conn->query("DELETE FROM tbl_cust_product_specification WHERE CustId='" . $custId . "' AND SpecType='1'")) {
                throw new Exception($conn->error);
            }

            foreach ($structLines as $line) {
                $prodId = (int) ($line['ProdId'] ?? 0);
                if ($prodId <= 0) {
                    continue;
                }
                $prodName = custSpecEsc($conn, $line['ProdName'] ?? '');
                $unit = custSpecEsc($conn, $line['Unit'] ?? '');
                $qty = custSpecEsc($conn, $line['Qty'] ?? '0');
                $structure = custSpecEsc($conn, $line['Structure'] ?? '0');

                $sql = "INSERT INTO tbl_cust_product_specification SET
                    CustId='" . $custId . "',
                    AcDc='" . custSpecEsc($conn, $cust['AcDc'] ?? '') . "',
                    Surface='" . custSpecEsc($conn, $cust['Surface'] ?? '') . "',
                    PumpCapacity='" . custSpecEsc($conn, $cust['PumpCapacity'] ?? '') . "',
                    WaterSource='" . custSpecEsc($conn, $cust['WaterSource'] ?? '') . "',
                    BoreDia='" . custSpecEsc($conn, $cust['BoreDia'] ?? '') . "',
                    PumpHead='" . custSpecEsc($conn, $cust['PumpHead'] ?? '') . "',
                    ProdId='" . $prodId . "',
                    ProdName='" . $prodName . "',
                    Unit='" . $unit . "',
                    Qty='" . $qty . "',
                    CreatedBy='" . $userId . "',
                    CreatedDate='" . $createdDate . "',
                    SpecType='1',
                    ModuleWatt='" . custSpecEsc($conn, $cust['ModuleWatt'] ?? '') . "',
                    ModuleQty='" . custSpecEsc($conn, $cust['ModuleQty'] ?? '') . "',
                    Structure='" . $structure . "',
                    AgencyId='" . custSpecEsc($conn, $cust['AgencyId'] ?? '') . "',
                    PumpOutletSize='" . custSpecEsc($conn, $cust['PumpOutletSize'] ?? '') . "'";
                if (!$conn->query($sql)) {
                    throw new Exception($conn->error);
                }
            }

            $result['updated']++;
        } catch (Exception $e) {
            $result['error'] = $e->getMessage();
            return $result;
        }
    }

    $allMatched = getList(
        "SELECT id FROM tbl_users WHERE " . custSpecBuildUserWhere([
            'Surface' => $filters['Surface'] ?? '',
            'PumpCapacity' => $filters['PumpCapacity'] ?? '',
            'ModuleWatt' => $filters['ModuleWatt'] ?? '',
            'ModuleQty' => $filters['ModuleQty'] ?? '',
            'ModuleMake' => $filters['ModuleMake'] ?? '',
            'StructureMake' => $filters['StructureMake'] ?? '',
            'AgencyId' => $filters['AgencyId'] ?? '',
            'SchemeId' => $filters['SchemeId'] ?? '',
        ]) . ($structureId !== '' ? " AND (Structure1='" . custSpecEsc($conn, $structureId) . "' OR Structure2='" . custSpecEsc($conn, $structureId) . "' OR Structure3='" . custSpecEsc($conn, $structureId) . "')" : '')
    );
    if (is_array($allMatched)) {
        foreach ($allMatched as $row) {
            if (custSpecCustomerHasDeliveryChallan($conn, (int) ($row['id'] ?? 0))) {
                $result['skipped']++;
            }
        }
    }

    return $result;
}
