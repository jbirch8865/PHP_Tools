<?php

class ConfigIntegrationTest extends \PHPUnit\Framework\TestCase
{
    private $configs;
    function test_Return_MySQL_Configs()
	{

		$this->configs = new \config\ConfigurationFile();
        $this->assertArrayHasKey('hostname',$this->configs->Configurations());
        $this->assertArrayHasKey('username',$this->configs->Configurations());
        $this->assertArrayHasKey('password',$this->configs->Configurations());
        $this->assertArrayHasKey('listeningport',$this->configs->Configurations());
        
	}

}

?>