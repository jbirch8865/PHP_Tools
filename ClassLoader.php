<?php
@session_start();
if(empty($_SESSION['company_id'])){$_SESSION['company_id'] = '';}
date_default_timezone_set('America/Los_Angeles');
require dirname(__FILE__) . DIRECTORY_SEPARATOR . 'vendor/autoload.php';
//require dirname(__FILE__) . DIRECTORY_SEPARATOR . "SendSMS.php"; //Rename to SMSClass eventually
require dirname(__FILE__) . DIRECTORY_SEPARATOR . "StartupVariables.php";
require dirname(__FILE__) . DIRECTORY_SEPARATOR . 'ExceptionClass.php';
require dirname(__FILE__) . DIRECTORY_SEPARATOR . "Startup_Functions.php";
require dirname(__FILE__) . DIRECTORY_SEPARATOR . "InterfaceClass.php";
require dirname(__FILE__) . DIRECTORY_SEPARATOR . "ConfigFileClass.php";

global $cConfigs;
$cConfigs = new \config\ConfigurationFile();
require dirname(__FILE__) . DIRECTORY_SEPARATOR . '../../adodb/adodb-php/adodb-active-record.inc.php';
//require dirname(__FILE__) . DIRECTORY_SEPARATOR . '../../adodb/adodb-php/adodb-errorhandler.inc.php';
$db = NewADOConnection('mysqli://'.$cConfigs->Get_Value_If_Enabled($cConfigs->Get_Name_Of_Project().'_username').':'.$cConfigs->Get_Value_If_Enabled($cConfigs->Get_Name_Of_Project().'_password').'@'.$cConfigs->Get_Value_If_Enabled($cConfigs->Get_Name_Of_Project().'_hostname').'/'.$cConfigs->Get_Name_Of_Project_Database($cConfigs->Get_Name_Of_Project()));
ADOdb_Active_Record::SetDatabaseAdapter($db);

require dirname(__FILE__) . DIRECTORY_SEPARATOR . "Database/ClassLoader.php";
global $dblink;
global $read_only_dblink;
global $root_dblink;
$root_dblink = new \DatabaseLink\MySQLLink("",2);
$dblink = new \DatabaseLink\Database($cConfigs->Get_Name_Of_Project(),true);
$read_only_dblink = new \DatabaseLink\Database($cConfigs->Get_Name_Of_Project(),false);
require dirname(__FILE__) . DIRECTORY_SEPARATOR . "Authentication/UserClass.php";
require dirname(__FILE__) . DIRECTORY_SEPARATOR . "Company/CompanyClass.php";
require dirname(__FILE__) . DIRECTORY_SEPARATOR . "Company/CompanyConfigClass.php";
ADODB_Active_Record::TableHasMany('Companies','Company_Configs','company_id','\Company\Company_Config');

/*
require dirname(__FILE__) . DIRECTORY_SEPARATOR . "TestClass.php";
require dirname(__FILE__) . DIRECTORY_SEPARATOR . "BootstrapHTMLClass.php";
require dirname(__FILE__) . DIRECTORY_SEPARATOR . "BootstrapJSClass.php";
require dirname(__FILE__) . DIRECTORY_SEPARATOR . "LoggingClass.php";
require dirname(__FILE__) . DIRECTORY_SEPARATOR . "SystemClass.php";
*/
?>