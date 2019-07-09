<?php
require '../../ClassLoader.php';
class DockerTest extends \PHPUnit\Framework\TestCase
{
	private $docker;
	function test_this_is_true()
	{
		$this->docker = new docker\Docker;
		$this->assertInstanceOf('docker\Docker',$this->docker);
	}
}

?>
