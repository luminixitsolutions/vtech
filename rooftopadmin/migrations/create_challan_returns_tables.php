<?php
include dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/inc-challan-return.php';

$conn->query("SET SESSION sql_mode = ''");
challanReturnEnsureTables($conn);
echo "Rooftop challan return tables are ready.\n";
