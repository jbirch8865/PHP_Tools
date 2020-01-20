<?php

class DatabaseTest extends \PHPUnit\Framework\TestCase
{
	private $dblink;

	public function setUp() :void
	{
        try
        {
            $this->DBLink = new DatabaseLink\MySQLLink('syslog');   
        }catch(\DatabaseLink\SQLConnectionError $e)
        {
            throw new \Exception($e->getMessage());
        }
	}

	function test_Execute_SQL_Query()
	{    
        $this->assertTrue(true);
    }
    

}

?>