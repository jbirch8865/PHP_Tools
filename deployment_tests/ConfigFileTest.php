<?php

class ConfigFileTest extends \PHPUnit\Framework\TestCase
{
	private string $filename;

	public function setUp() :void
	{
		$filename = explode('/',dirname(__FILE__));
		$filename = $filename[count($filename) - 4];
	}
	function test_Project_Name()
	{
		$cConfigs = new \config\ConfigurationFile();
		$this->assertFalse($cConfigs->Is_Feature_Enabled('project_name'));
		$cConfigs->Add_Or_Update_Config('project_name',dirname(__FILE__));
		$this->assertTrue($cConfigs->Is_Feature_Enabled('project_name'));
		$cConfigs->Set_Name_Of_Project_Database($cConfigs->Get_Value_If_Enabled('project_name'));
	}
	function test_Instantiate_Class()
	{    
		$cConfigs = new \config\ConfigurationFile();
		$this->assertTrue(true);
    }
	function test_Bogus_Config()
	{
		$cConfigs = new \config\ConfigurationFile();
		$cConfigs->Add_Or_Update_Config('bogus_config','true');
		$this->assertEquals("true",$cConfigs->Get_Value_If_Enabled('bogus_config'));
		$cConfigs->Delete_Config_If_Exists('bogus_config');
		$this->assertFalse($cConfigs->Is_Feature_Enabled('bogus_config'));
	}
	function test_Setting_Prod()
	{
		$cConfigs = new \config\ConfigurationFile();
		$cConfigs->Save_Environment();
		$cConfigs->Set_Prod_Environment();
		$this->assertTrue($cConfigs->Is_Prod());
		$cConfigs->Reset_Environment();
	}
	function test_Setting_Dev()
	{
		$cConfigs = new \config\ConfigurationFile();
		$cConfigs->Save_Environment();
		$cConfigs->Set_Dev_Environment();
		$this->assertTrue($cConfigs->Is_Dev());
		$cConfigs->Reset_Environment();
	}
	function test_Setting_End_User_Date_Format()
	{
		$cConfigs = new \config\ConfigurationFile();
		$date_format = $cConfigs->Get_End_User_Date_Format();
		$cConfigs->Set_End_User_Date_Format('Y-m-d');
		$this->assertEquals('Y-m-d',$cConfigs->Get_End_User_Date_Format());
		if($date_format)
		{
			$cConfigs->Set_End_User_Date_Format($date_format);
		}
	}
	function test_Setting_Database_Values()
	{
		$cConfigs = new \config\ConfigurationFile();
		$this->assertTrue($cConfigs->Is_Feature_Enabled('root_username'));
		$this->assertTrue($cConfigs->Is_Feature_Enabled('root_password'));
		$this->assertTrue($cConfigs->Is_Feature_Enabled('root_hostname'));
		$this->assertTrue($cConfigs->Is_Feature_Enabled('root_listeningport'));
		$cConfigs->Set_Database_Connection_Preferences('new_host','new_username','some_new_password','not_project_database','3307');
		$this->assertEquals('new_host',$cConfigs->Get_Connection_Hostname('not_project_database'));
		$this->assertEquals('new_username',$cConfigs->Get_Connection_Username('not_project_database'));
		$this->assertEquals('some_new_password',$cConfigs->Get_Connection_Password('not_project_database'));
		$this->assertEquals('not_project_database',$cConfigs->Get_Name_Of_Project_Database('not_project_database'));
		$this->assertEquals('3307',$cConfigs->Get_Connection_Listeningport('not_project_database'));
		$cConfigs->Delete_Config_If_Exists('not_project_database_username');
		$cConfigs->Delete_Config_If_Exists('not_project_database_password');
		$cConfigs->Delete_Config_If_Exists('not_project_database_hostname');
		$cConfigs->Delete_Config_If_Exists('not_project_database_listeningport');
		$cConfigs->Delete_Config_If_Exists('not_project_database_project_database_name');
		$this->assertFalse($cConfigs->Get_Connection_Hostname('not_project_database'));
		$this->assertFalse($cConfigs->Get_Connection_Username('not_project_database'));
		$this->assertFalse($cConfigs->Get_Connection_Password('not_project_database'));
		$this->assertFalse($cConfigs->Get_Name_Of_Project_Database('not_project_database'));
		$this->assertFalse($cConfigs->Get_Connection_Listeningport('not_project_database'));
	}
	function test_Base_URL()
	{
		$cConfigs = new \config\ConfigurationFile();
		$base_url = $cConfigs->Get_Base_URL();
		$cConfigs->Set_Base_URL('some_url');
		$this->assertEquals('some_url',$cConfigs->Get_Base_URL());
		if($base_url)
		{
			$cConfigs->Set_Base_URL($base_url);
		}else
		{
			$cConfigs->Set_Base_URL('');
		}
	}
	function test_Vendor_URL()
	{
		$cConfigs = new \config\ConfigurationFile();
		$vendor_url = $cConfigs->Get_Vendor_URL();
		$cConfigs->Set_Vendor_URL('vendor_url');
		$this->assertEquals('vendor_url',$cConfigs->Get_Vendor_URL());
		if($vendor_url)
		{
			$cConfigs->Set_Vendor_URL($vendor_url);
		}else
		{
			$cConfigs->Set_Vendor_URL('');
		}
	}
	function test_Fail_On_No_File()
	{
		$this->expectException(\config\config_file_missing::class);
		$cConfigs = new \config\ConfigurationFile('not_a_file');
	}
}

?>