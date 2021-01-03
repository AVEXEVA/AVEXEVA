<?php
if(session_id() == '' || !isset($_SESSION)) {session_start();}

$errorlevel=error_reporting();
error_reporting($errorlevel & ~E_NOTICE);

ini_set('display_errors', 'On');

define('PROJECT_ROOT',__DIR__ . '/..');

setlocale(LC_MONETARY, 'en_US');

require(PROJECT_ROOT.'/PHP/Class/index.php');
require(PROJECT_ROOT.'/PHP/Function/index.php');
require(PROJECT_ROOT.'/PHP/Config/index.php');

$Icons = Icons::getInstance();
?>
