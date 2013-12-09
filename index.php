<?php
session_start();
$path = $_SERVER['DOCUMENT_ROOT'];
$modulesPath = __DIR__ . "/modules/";

require_once($modulesPath . "/core/theme-manager/head.php");
require_once($modulesPath . "/core/mysql.php");
include_once($modulesPath . "/core/ui/panel.php");
include_once($modulesPath . "/core/login/login.php");

Login::buildLoginForm("$modulesPath/core");
?>
