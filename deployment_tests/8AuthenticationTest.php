<?php declare(strict_types=1);

use Authentication\User_Session;
use DatabaseLink\SQLQueryError;

class AuthenticationTest extends \PHPUnit\Framework\TestCase
{
    private \DatabaseLink\Table $table_dblink;
    private \config\ConfigurationFile $cConfigs;

	public function setUp() :void
	{
        global $cConfigs;
        $this->cConfigs = $cConfigs;
        global $dblink;
        $this->table_dblink = new \DatabaseLink\Table('Users',$dblink);
    }
    
    public function tearDown() :void
    {

    }

    function test_Create_Temp_User_With_Basic_Password()
    {
        $new_user = new \Authentication\User('temp_user','basic_password',$_SESSION['company_id'],true);
        unset($new_user);
        try
        {
            $new_user = new \Authentication\User('temp_user','basic_password',$_SESSION['company_id'],false);
        } catch (\Authentication\User_Does_Not_Exist $e)
        {
            $this->fail();
        }
        $this->addToAssertionCount(1);
    }

    function test_Authenticate_Temp_User()
    {
        $new_user = new \Authentication\User('temp_user','basic_password',$_SESSION['company_id'],false);
        $this->addToAssertionCount(1);
    }
    function test_Start_A_User_Session()
    {
        $new_user = new \Authentication\User('temp_user','basic_password',$_SESSION['company_id'],false);
        $new_user_session = new \Authentication\User_Session($new_user);
        $current_session = new \Authentication\Current_User_Session;
        $this->addToAssertionCount(1);
    }
    function test_Log_Out_A_User()
    {
        $current_session = new \Authentication\Current_User_Session;
        $current_session->session->LogOut();
        unset($current_session);
        $this->expectException(\Authentication\User_Not_Logged_In::class);
        $current_session = new \Authentication\Current_User_Session;
    }
    function test_Delete_Temp_User()
    {
        try
        {
            $new_user = new \Authentication\User('temp_user','basic_password',$_SESSION['company_id'],false);
        } catch (\Authentication\User_Does_Not_Exist $e)
        {
            $this->fail();
        }
        $new_user->Delete_Object('destroy');
        $this->addToAssertionCount(1);
    }

}

?>