<?php
date_default_timezone_set('America/Los_Angeles');
require dirname(__FILE__) . DIRECTORY_SEPARATOR . 'vendor/autoload.php';
require dirname(__FILE__) . DIRECTORY_SEPARATOR . 'ExceptionClass.php';
require dirname(__FILE__) . DIRECTORY_SEPARATOR . "SendSMS.php"; //Rename to SMSClass eventually
require dirname(__FILE__) . DIRECTORY_SEPARATOR . "StartupVariables.php";
require dirname(__FILE__) . DIRECTORY_SEPARATOR . "Startup_Functions.php";
require dirname(__FILE__) . DIRECTORY_SEPARATOR . "ConfigFileClass.php";
global $cConfigs;
$cConfigs = new \config\ConfigurationFile();
require dirname(__FILE__) . DIRECTORY_SEPARATOR . "Database/ClassLoader.php";
global $dblink;
global $read_only_dblink;
global $root_dblink;
$root_dblink = new \DatabaseLink\MySQLLink("",2);
$dblink = new \DatabaseLink\Database($cConfigs->Get_Name_Of_Project(),true);
$read_only_dblink = new \DatabaseLink\Database($cConfigs->Get_Name_Of_Project(),false);
require dirname(__FILE__) . DIRECTORY_SEPARATOR . "UserClass.php";
require dirname(__FILE__) . DIRECTORY_SEPARATOR . "TestClass.php";
require dirname(__FILE__) . DIRECTORY_SEPARATOR . "BootstrapHTMLClass.php";
require dirname(__FILE__) . DIRECTORY_SEPARATOR . "BootstrapJSClass.php";
require dirname(__FILE__) . DIRECTORY_SEPARATOR . "LoggingClass.php";
require dirname(__FILE__) . DIRECTORY_SEPARATOR . "SystemClass.php";
?>