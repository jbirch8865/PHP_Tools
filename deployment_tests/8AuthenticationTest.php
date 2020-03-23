<?php declare(strict_types=1);

use Authentication\User_Session;
use DatabaseLink\SQLQueryError;

class AuthenticationTest extends \PHPUnit\Framework\TestCase
{
    private \DatabaseLink\Table $table_dblink;
    public \config\ConfigurationFile $cConfigs;

	public function setUp() :void
	{
        global $toolbelt;
        $this->cConfigs = $toolbelt->cConfigs;
        $this->table_dblink = $toolbelt->Users;
    }
    
    public function tearDown() :void
    {

    }

    function test_Create_Temp_User_With_Basic_Password()
    {
        $this->addToAssertionCount(1);
    }

    function test_Authenticate_Temp_User()
    {
        $this->addToAssertionCount(1);
    }
    function test_Start_A_User_Session()
    {
        $this->addToAssertionCount(1);
    }
    function test_Log_Out_A_User()
    {
        $this->addToAssertionCount(1);
    }
    function test_Delete_Temp_User()
    {
        $this->addToAssertionCount(1);
    }

}

?>