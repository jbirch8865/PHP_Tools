<?php
require dirname(__FILE__) . DIRECTORY_SEPARATOR . 'vendor/autoload.php';
require dirname(__FILE__) . DIRECTORY_SEPARATOR . "StartupVariables.php";
require dirname(__FILE__) . DIRECTORY_SEPARATOR . 'ExceptionClass.php';
require dirname(__FILE__) . DIRECTORY_SEPARATOR . "Startup_Functions.php";
require dirname(__FILE__) . DIRECTORY_SEPARATOR . "ConfigFileClass.php";
require 'TestClass.php';
global $toolbelt_base;
$toolbelt_base = new \Test_Tools\toolbelt_base();
$cConfigs = new \config\ConfigurationFile();
$toolbelt_base->cConfigs = $cConfigs;
require dirname(__FILE__) . DIRECTORY_SEPARATOR . '../../adodb/adodb-php/adodb-active-record.inc.php';
require dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Active_Record/ActiveRecordClass.php';
require dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Active_Record/ActiveRecordRelationManagementClass.php';
require dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Active_Record/ActiveRecordInterface.php';
global $active_record_relationship_manager;
$toolbelt_base->active_record_relationship_manager = new \Active_Record\RelationshipManager;
require dirname(__FILE__) . DIRECTORY_SEPARATOR . "Database/ClassLoader.php";
$root_dblink = new \DatabaseLink\MySQLLink("",2);
$toolbelt_base->root_dblink = $root_dblink;
$dblink = new \DatabaseLink\Database($cConfigs->Get_Name_Of_Project(),true);
$toolbelt_base->dblink = $dblink;
$read_only_dblink = new \DatabaseLink\Database($cConfigs->Get_Name_Of_Project(),false);
$toolbelt_base->read_only_dblink = $read_only_dblink;
unset($cConfigs);
unset($root_dblink);
unset($dblink);
unset($read_only_dblink);
require dirname(__FILE__) . DIRECTORY_SEPARATOR . "Authentication/User_Interface.php";
require dirname(__FILE__) . DIRECTORY_SEPARATOR . "Authentication/UserClass.php";
require dirname(__FILE__) . DIRECTORY_SEPARATOR . "Company/Loader.php";
require dirname(__FILE__) . DIRECTORY_SEPARATOR . "API/Loader.php";
require dirname(__FILE__) . DIRECTORY_SEPARATOR . "Authentication/UserRoleClass.php";
$db = NewADOConnection('mysqli://'.$toolbelt_base->cConfigs->Get_Connection_Username().':'.$toolbelt_base->cConfigs->Get_Connection_Password().'@'.$toolbelt_base->cConfigs->Get_Connection_Hostname().'/'.$toolbelt_base->cConfigs->Get_Name_Of_Project_Database());
ADOdb_Active_Record::SetDatabaseAdapter($db);

?>