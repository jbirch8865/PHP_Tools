<?php

class ConfigFileTest extends \PHPUnit\Framework\TestCase
{
	private string $filename;
    private \config\ConfigurationFile $cConfigs;

	public function setUp() :void
	{
		global $root_folder;
		global $cConfigs;
		$this->cConfigs = $cConfigs;
		$this->filename = $root_folder;
	}
	function test_Project_Name()
	{
		$this->cConfigs->Add_Or_Update_Config('project_name',$this->filename);
		$this->assertTrue($this->cConfigs->Is_Config_Set('project_name'));
		$this->cConfigs->Set_Name_Of_Project_Database($this->cConfigs->Get_Value_If_Enabled('project_name'));
		$this->assertEquals($this->cConfigs->Get_Value_If_Enabled('project_name'),$this->cConfigs->Get_Name_Of_Project_Database($this->cConfigs->Get_Value_If_Enabled('project_name')));
	}
	function test_Bogus_Config()
	{
		$this->cConfigs->Add_Or_Update_Config('bogus_config','true');
		$this->assertEquals("true",$this->cConfigs->Get_Value_If_Enabled('bogus_config'));
		$this->cConfigs->Delete_Config_If_Exists('bogus_config');
		$this->assertFalse($this->cConfigs->Is_Config_Set('bogus_config'));
	}
	function test_Setting_Prod()
	{
		$this->cConfigs->Save_Environment();
		$this->cConfigs->Set_Prod_Environment();
		$this->assertTrue($this->cConfigs->Is_Prod());
		$this->cConfigs->Reset_Environment();
	}
	function test_Setting_Dev()
	{
		$this->cConfigs->Save_Environment();
		$this->cConfigs->Set_Dev_Environment();
		$this->assertTrue($this->cConfigs->Is_Dev());
		$this->cConfigs->Reset_Environment();
	}
	function test_Setting_End_User_Date_Format()
	{
		$date_format = $this->cConfigs->Get_End_User_Date_Format();
		$this->cConfigs->Set_End_User_Date_Format('some-new-format');
		$this->assertEquals('some-new-format',$this->cConfigs->Get_End_User_Date_Format());
		$this->cConfigs->Set_End_User_Date_Format($date_format);
	}
	function test_Setting_Database_Values()
	{
		$this->assertTrue($this->cConfigs->Is_Config_Set('root_username'));
		$this->assertTrue($this->cConfigs->Is_Config_Set('root_password'));
		$this->assertTrue($this->cConfigs->Is_Config_Set('root_hostname'));
		$this->assertTrue($this->cConfigs->Is_Config_Set('root_listeningport'));
		$this->cConfigs->Set_Database_Connection_Preferences('new_host','new_username','some_new_password','not_project_database','3307');
		$this->assertEquals('new_host',$this->cConfigs->Get_Connection_Hostname('not_project_database'));
		$this->assertEquals('new_username',$this->cConfigs->Get_Connection_Username('not_project_database'));
		$this->assertEquals('some_new_password',$this->cConfigs->Get_Connection_Password('not_project_database'));
		$this->assertEquals('not_project_database',$this->cConfigs->Get_Name_Of_Project_Database('not_project_database'));
		$this->assertEquals('3307',$this->cConfigs->Get_Connection_Listeningport('not_project_database'));
		$this->cConfigs->Delete_Config_If_Exists('not_project_database_username');
		$this->cConfigs->Delete_Config_If_Exists('not_project_database_password');
		$this->cConfigs->Delete_Config_If_Exists('not_project_database_hostname');
		$this->cConfigs->Delete_Config_If_Exists('not_project_database_listeningport');
		$this->cConfigs->Delete_Config_If_Exists('not_project_database_project_database_name');
		$this->assertFalse($this->cConfigs->Get_Connection_Hostname('not_project_database'));
		$this->assertFalse($this->cConfigs->Get_Connection_Username('not_project_database'));
		$this->assertFalse($this->cConfigs->Get_Connection_Password('not_project_database'));
		$this->assertFalse($this->cConfigs->Get_Name_Of_Project_Database('not_project_database'));
		$this->assertFalse($this->cConfigs->Get_Connection_Listeningport('not_project_database'));
	}
	function test_Base_URL()
	{
		$base_url = $this->cConfigs->Get_Base_URL();
		$this->cConfigs->Set_Base_URL('some_url');
		$this->assertEquals('some_url',$this->cConfigs->Get_Base_URL());
		if($base_url)
		{
			$this->cConfigs->Set_Base_URL($base_url);
		}else
		{
			$this->cConfigs->Set_Base_URL('');
		}
	}
	function test_Vendor_URL()
	{
		$vendor_url = $this->cConfigs->Get_Vendor_URL();
		$this->cConfigs->Set_Vendor_URL('vendor_url');
		$this->assertEquals('vendor_url',$this->cConfigs->Get_Vendor_URL());
		$this->assertEquals('vendor_url/images',$this->get_Images_URL());
		if($vendor_url)
		{
			$this->cConfigs->Set_Vendor_URL($vendor_url);
		}else
		{
			$this->cConfigs->Set_Vendor_URL('');
		}
	}
	function get_Images_URL()
	{
		return $this->cConfigs->Get_Images_URL();
	}
	function test_Fail_On_No_File()
	{
		$this->expectException(\config\config_file_missing::class);
		$this->cConfigs = new \config\ConfigurationFile('not_a_file');
	}
	
	function test_Creating_A_Public_Directory()
	{
		if(file_exists(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'test_folder'))
		{
			$new_directory = new \config\Public_Folder('test_folder',true);
			$this->assertTrue($new_directory->Delete_Public_Directory(true));
		}
		$new_directory = new \config\Public_Folder('test_folder',true);
		$this->assertTrue(file_exists(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'test_folder'));
	}
	function Create_A_File()
	{
		$new_file = new \config\Public_File('test_file.txt','test_folder');
		if(file_exists(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'test_folder'. DIRECTORY_SEPARATOR . 'test_file.txt'))
		{
			$this->assertTrue($new_file->Does_The_File_Exists());
			$new_file->Delete_File();
			$this->assertFalse(file_exists(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'test_folder'. DIRECTORY_SEPARATOR . 'test_file.txt'));
		}
		$new_file->Create_Or_Overwrite_File('this is some test data');
		$this->assertTrue($new_file->Does_The_File_Exists());
		$this->assertEquals('this is some test data',$new_file->Get_File_Contents());
	}
	function test_Expect_Error_If_Folder_Is_Non_Existant()
	{
		$this->expectException(\config\file_or_folder_does_not_exist::class);
		$new_file = new \config\Public_File('test_file.txt','test_folder_non_existant');
	}	
	function test_Creating_And_Delete_A_File()
	{
		$this->Create_A_File();
		$new_file = new \config\Public_File('test_file.txt','test_folder');
		$new_file->Delete_File();
		$this->assertFalse(file_exists(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'test_folder' . DIRECTORY_SEPARATOR . 'test_file.txt'));
	}
	function test_Creating_A_File_And_Deleting_The_Parent_Folder()
	{
		$this->Create_A_File();
		$new_folder = new \config\Public_Folder('test_folder');
		$new_folder->Delete_Public_Directory(true);
		$this->assertFalse(file_exists(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'test_folder'));
	}
}

?>