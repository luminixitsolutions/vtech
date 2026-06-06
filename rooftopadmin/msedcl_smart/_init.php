<?php
session_start();
include_once __DIR__ . '/../config.php';
include_once __DIR__ . '/../auth.php';
require_once __DIR__ . '/../inc-msedcl-smart-site.php';

msedclSmartInitUserAccess();
msedclSmartRequireAnyAccess();

$MainPage = 'MSEDCL-Smart';
