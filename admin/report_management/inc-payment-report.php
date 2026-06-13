<?php



function paymentReportFormatDate($date)

{

    if ($date === null || $date === '' || $date === '0000-00-00') {

        return '';

    }

    $ts = strtotime(str_replace('-', '/', (string) $date));



    return $ts ? date('d/m/Y', $ts) : '';

}



function paymentReportFormatMoney($value)

{

    if ($value === null || $value === '') {

        return '';

    }



    return number_format((float) $value, 2, '.', '');

}



function paymentReportProjectCostTableExists()

{

    static $exists = null;

    if ($exists !== null) {

        return $exists;

    }



    global $conn;

    $check = $conn->query("SHOW TABLES LIKE 'tbl_project_cost'");

    $exists = $check && $check->num_rows > 0;



    return $exists;

}



function paymentReportProjectCostJoinSql()

{

    if (!paymentReportProjectCostTableExists()) {

        return '';

    }



    return "LEFT JOIN tbl_project_cost pc ON pc.ProjectId = tu.ProjectId

        AND pc.ProjectSubHeadId = tu.ProjectSubHeadId

        AND pc.CapacityId = tu.PumpCapacity

        AND pc.Status = '1'";

}



function paymentReportProjectCostSelectSql()

{

    return paymentReportProjectCostTableExists()

        ? 'pc.Amount AS TotalCost'

        : 'NULL AS TotalCost';

}



function paymentReportLatestInstallJoinSql()

{

    return "LEFT JOIN (

        SELECT ti1.CustId, ti1.Payment90, ti1.Payment90Amt, ti1.Payment10, ti1.Payment10Amt, ti1.PaymentDate

        FROM tbl_installations ti1

        INNER JOIN (

            SELECT CustId, MAX(id) AS max_id

            FROM tbl_installations

            WHERE Type = 2

            GROUP BY CustId

        ) ti_max ON ti_max.max_id = ti1.id

    ) ti ON ti.CustId = tu.id";

}



function paymentReportLookupJoinsSql()

{

    return "LEFT JOIN tbl_common_master cap ON cap.id = tu.PumpCapacity

        LEFT JOIN tbl_common_master ph ON ph.id = tu.ProjectId

        LEFT JOIN tbl_project_sub_head sh ON sh.id = tu.ProjectSubHeadId

        " . paymentReportProjectCostJoinSql();

}



function paymentReportWhereSql($conn, array $filters)

{

    $sql = '';



    if (!empty($filters['ProjectId']) && $filters['ProjectId'] !== 'all') {

        $projectId = (int) $filters['ProjectId'];

        $sql .= " AND tu.ProjectId = '$projectId'";

    }



    if (!empty($filters['ProjectSubHeadId']) && $filters['ProjectSubHeadId'] !== 'all') {

        $subHeadId = (int) $filters['ProjectSubHeadId'];

        $sql .= " AND tu.ProjectSubHeadId = '$subHeadId'";

    }



    if (!empty($filters['District']) && $filters['District'] !== 'all') {

        $district = mysqli_real_escape_string($conn, $filters['District']);

        $sql .= " AND tu.District = '$district'";

    }



    if (!empty($filters['Taluka']) && $filters['Taluka'] !== 'all') {

        $taluka = mysqli_real_escape_string($conn, $filters['Taluka']);

        $sql .= " AND tu.Taluka = '$taluka'";

    }



    if (!empty($filters['Village']) && $filters['Village'] !== 'all') {

        $village = mysqli_real_escape_string($conn, $filters['Village']);

        $sql .= " AND tu.Village = '$village'";

    }



    return $sql;

}



function paymentReportSearchSql($conn, $searchValue)

{

    if ($searchValue === '') {

        return '';

    }



    $search = mysqli_real_escape_string($conn, $searchValue);



    return " AND (

        tu.BeneficiaryId LIKE '%$search%' OR

        tu.Fname LIKE '%$search%' OR

        tu.Lname LIKE '%$search%' OR

        tu.District LIKE '%$search%' OR

        tu.Village LIKE '%$search%' OR

        tu.Taluka LIKE '%$search%' OR

        cap.Name LIKE '%$search%' OR

        ph.Name LIKE '%$search%' OR

        sh.Name LIKE '%$search%'

    )";

}



function paymentReportOrderColumn($columnName)

{

    $map = array(

        'sr' => 'tu.BeneficiaryId',

        'BeneficiaryId' => 'tu.BeneficiaryId',

        'CustomerName' => 'tu.Fname',

        'District' => 'tu.District',

        'Village' => 'tu.Village',

        'Taluka' => 'tu.Taluka',

        'CapacityName' => 'cap.Name',

        'ProjectHeadName' => 'ph.Name',

        'SubHeadName' => 'sh.Name',

        'TotalCost' => 'pc.Amount',

        'Payment90' => 'ti.Payment90',

        'Payment90Amt' => 'ti.Payment90Amt',

        'Payment90Date' => 'ti.PaymentDate',

        'Payment10' => 'ti.Payment10',

        'Payment10Amt' => 'ti.Payment10Amt',

        'Payment10Date' => 'ti.PaymentDate',

        'BalanceAmount' => '(COALESCE(pc.Amount,0) - COALESCE(ti.Payment90Amt,0) - COALESCE(ti.Payment10Amt,0))',

    );



    return isset($map[$columnName]) ? $map[$columnName] : 'tu.BeneficiaryId';

}



function paymentReportSelectSql()

{

    $costSelect = paymentReportProjectCostSelectSql();



    return "SELECT tu.id, tu.BeneficiaryId, tu.Fname, tu.Lname, tu.District, tu.Village, tu.Taluka,

            cap.Name AS CapacityName,

            ph.Name AS ProjectHeadName,

            sh.Name AS SubHeadName,

            $costSelect,

            ti.Payment90, ti.Payment90Amt, ti.Payment10, ti.Payment10Amt, ti.PaymentDate";

}



function paymentReportBaseSql($conn, array $filters, $searchValue = '')

{

    $where = paymentReportWhereSql($conn, $filters);

    $search = paymentReportSearchSql($conn, $searchValue);



    return paymentReportSelectSql() . "

        FROM tbl_users tu

        " . paymentReportLatestInstallJoinSql() . "

        " . paymentReportLookupJoinsSql() . "

        WHERE tu.Roll = '5' AND tu.ProjectType = '1'

        $where

        $search";

}



function paymentReportBuildSql($conn, array $filters, $orderBy = 'tu.BeneficiaryId', $orderDir = 'ASC', $limit = null, $offset = null)

{

    $sql = paymentReportBaseSql($conn, $filters);

    $sql .= ' ORDER BY ' . $orderBy . ' ' . $orderDir . ', tu.id ASC';



    if ($limit !== null) {

        $limit = (int) $limit;

        $offset = (int) $offset;

        $sql .= " LIMIT $offset, $limit";

    }



    return $sql;

}



function paymentReportBalanceAmount($totalCost, $payment90Amt, $payment10Amt)

{

    if ($totalCost === null || $totalCost === '') {

        return '';

    }



    $total = (float) $totalCost;

    $paid90 = (float) $payment90Amt;

    $paid10 = (float) $payment10Amt;



    return paymentReportFormatMoney($total - $paid90 - $paid10);

}



function paymentReportFormatRow(array $row, $serial = '')

{

    $customerName = trim(($row['Fname'] ?? '') . ' ' . ($row['Lname'] ?? ''));

    $totalCost = $row['TotalCost'] ?? '';

    $payment90Amt = $row['Payment90Amt'] ?? '';

    $payment10Amt = $row['Payment10Amt'] ?? '';

    $paymentDate = paymentReportFormatDate($row['PaymentDate'] ?? '');

    $payment90 = (string) ($row['Payment90'] ?? 'No');

    $payment10 = (string) ($row['Payment10'] ?? 'No');



    return array(

        'sr' => $serial,

        'BeneficiaryId' => $row['BeneficiaryId'] ?? '',

        'CustomerName' => $customerName,

        'District' => $row['District'] ?? '',

        'Village' => $row['Village'] ?? '',

        'Taluka' => $row['Taluka'] ?? '',

        'CapacityName' => $row['CapacityName'] ?? '',

        'ProjectHeadName' => $row['ProjectHeadName'] ?? '',

        'SubHeadName' => $row['SubHeadName'] ?? '',

        'TotalCost' => paymentReportFormatMoney($totalCost),

        'Payment90' => $payment90,

        'Payment90Amt' => paymentReportFormatMoney($payment90Amt),

        'Payment90Date' => ($payment90 === 'Yes') ? $paymentDate : '',

        'Payment10' => $payment10,

        'Payment10Amt' => paymentReportFormatMoney($payment10Amt),

        'Payment10Date' => ($payment10 === 'Yes') ? $paymentDate : '',

        'BalanceAmount' => paymentReportBalanceAmount($totalCost, $payment90Amt, $payment10Amt),

    );

}



function paymentReportFilterOptionsSql($field)

{

    $allowed = array('District', 'Taluka', 'Village');

    if (!in_array($field, $allowed, true)) {

        return '';

    }



    return "SELECT DISTINCT($field) AS val FROM tbl_users

        WHERE Roll='5' AND ProjectType='1' AND $field!=''

        ORDER BY $field ASC";

}


