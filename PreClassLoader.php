<?php
date_default_timezone_set('America/Los_Angeles');
require dirname(__FILE__) . DIRECTORY_SEPARATOR . 'vendor/autoload.php';
require dirname(__FILE__) . DIRECTORY_SEPARATOR . "StartupVariables.php";
require dirname(__FILE__) . DIRECTORY_SEPARATOR . 'ExceptionClass.php';
require dirname(__FILE__) . DIRECTORY_SEPARATOR . "Startup_Functions.php";
require dirname(__FILE__) . DIRECTORY_SEPARATOR . "ConfigFileClass.php";
require dirname(__FILE__) . DIRECTORY_SEPARATOR . '../../adodb/adodb-php/adodb-active-record.inc.php';
/**
 * @var \config\ConfigurationFile $cConfigs
 */
global $cConfigs;
$cConfigs = new \config\ConfigurationFile();
require dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Active_Record/ActiveRecordClass.php';
require dirname(__FILE__) . DIRECTORY_SEPARATOR . "Database/ClassLoader.php";
global $dblink;
global $read_only_dblink;
global $root_dblink;
$root_dblink = new \DatabaseLink\MySQLLink("",2);
$dblink = new \DatabaseLink\Database($cConfigs->Get_Name_Of_Project(),true);
$read_only_dblink = new \DatabaseLink\Database($cConfigs->Get_Name_Of_Project(),false);
require dirname(__FILE__) . DIRECTORY_SEPARATOR . "Authentication/UserClass.php";
require dirname(__FILE__) . DIRECTORY_SEPARATOR . "Company/CompanyClass.php";
require dirname(__FILE__) . DIRECTORY_SEPARATOR . "Company/ConfigClass.php";
require dirname(__FILE__) . DIRECTORY_SEPARATOR . "Company/CompanyConfigClass.php";
$db = NewADOConnection('mysqli://'.$cConfigs->Get_Connection_Username().':'.$cConfigs->Get_Connection_Password().'@'.$cConfigs->Get_Connection_Hostname().'/'.$cConfigs->Get_Name_Of_Project_Database());
ADOdb_Active_Record::SetDatabaseAdapter($db);

/*
require dirname(__FILE__) . DIRECTORY_SEPARATOR . "TestClass.php";
require dirname(__FILE__) . DIRECTORY_SEPARATOR . "BootstrapHTMLClass.php";
require dirname(__FILE__) . DIRECTORY_SEPARATOR . "BootstrapJSClass.php";
require dirname(__FILE__) . DIRECTORY_SEPARATOR . "LoggingClass.php";
require dirname(__FILE__) . DIRECTORY_SEPARATOR . "SystemClass.php";
*/
?>