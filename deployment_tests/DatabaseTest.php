<?php

class DatabaseTest extends \PHPUnit\Framework\TestCase
{
	private $dblink;

	public function setUp() :void
	{
        try
        {
            $cConfigs = new \config\ConfigurationFile();
            $this->assertTrue($cConfigs->Get_Value_If_Enabled(dirname(__FILE__)."_username"));
            $this->DBLink = new DatabaseLink\MySQLLink($cConfigs->Get_Name_Of_Project_Database($cConfigs->Get_Name_Of_Project_Database()));
        }catch(\DatabaseLink\SQLConnectionError $e)
        {
            $con = mysqli_connect($cConfigs->Get_Value_If_Enabled('root_hostname'),$cConfigs->Get_Value_If_Enabled('root_username'),$cConfigs->Get_Value_If_Enabled('root_password'));
            mysqli_query($con,"CREATE DATABASE ".$cConfigs->Get_Name_Of_Project_Database());
        }
	}

	function test_Execute_SQL_Query()
	{    
        $this->assertTrue(true);
    }
    

}

?>