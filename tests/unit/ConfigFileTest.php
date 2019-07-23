<?php

class ConfigFileTest extends \PHPUnit\Framework\TestCase
{
	private $configs;
	private $file_system;

	public function setUp() :void
	{
//		org\bovigo\vfs\vfsStream
	  // define my virtual file system
	  $directory = [
		'config.local.ini' => "Token=ThisIsAToken"
	  ];
	  // setup and cache the virtual file system
	  $this->file_system = org\bovigo\vfs\vfsStream::setup('root', 444, $directory);
	}

	function test_Errors_On_Non_String()
	{
		$this->expectException(Exception::class);
     
		$this->configs = new config\ConfigurationFile(array("This should fail"));
	}
	
	function test_Return_Empty_Array_On_Non_Existent_File()
	{
		$this->configs = new config\ConfigurationFile("NotAValidFile.ini.test");
		$this->assertIsArray($this->configs->Configurations());
	}

	function test_Return_Empty_Array_On_Default_File()
	{
		$this->configs = new config\ConfigurationFile();
		$this->assertIsArray($this->configs->Configurations());		
	}

	function test_Return_Known_Configs_From_File()
	{

		$this->configs = new \config\ConfigurationFile(org\bovigo\vfs\vfsStream::url('root/config.local.ini'));
		$this->assertArrayHasKey('Token',$this->configs->Configurations());
		$this->assertEquals('ThisIsAToken',$this->configs->Configurations()['Token']);
	}

	function test_Expect_Exception_On_Non_Ini_File()
	{
		$this->expectException(Exception::class);
		$this->configs = new \config\ConfigurationFile('DatabaseClass.php');
	}
}

?>