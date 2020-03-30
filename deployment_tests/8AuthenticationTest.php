<?php declare(strict_types=1);

use Authentication\User_Session;
use DatabaseLink\SQLQueryError;

class AuthenticationTest extends \PHPUnit\Framework\TestCase
{
    private \DatabaseLink\Table $table_dblink;
    public \config\ConfigurationFile $cConfigs;

	public function setUp() :void
	{
        $toolbelt = new \Test_Tools\toolbelt();;
        $this->cConfigs = $toolbelt->cConfigs;
        $this->table_dblink = $toolbelt->Users;
    }
    
    public function tearDown() :void
    {
    }

    function test_Create_Temp_User_With_Basic_Password()
    {
        $user = new \Authentication\User('temp_user_delete_me','Basic_Password',1,true);
        $this->addToAssertionCount(1);
    }

    function test_Authenticate_Temp_User()
    {
        $user = new \Authentication\User('temp_user_delete_me','Basic_Password',1,false);
        $this->addToAssertionCount(1);
    }
    function test_Create_A_New_User_Session()
    {
        $toolbelt = new \Test_Tools\toolbelt;
        $session = new \API\Program_Session();
        $session->Create_New_Session($toolbelt->cConfigs->Get_Client_ID(),$toolbelt->cConfigs->Get_Secret_ID(),1,'temp_user_delete_me','Basic_Password');
        $this->addToAssertionCount(1);
    }
    function test_Log_Out_A_User()
    {
        $toolbelt = new \Test_Tools\toolbelt;
        $session = new \API\Program_Session();
        $session->Create_New_Session($toolbelt->cConfigs->Get_Client_ID(),$toolbelt->cConfigs->Get_Secret_ID(),1,'temp_user_delete_me','Basic_Password');
        $this->assertFalse($session->Is_Expired());
        $session->Revoke_Session();
        $this->assertTrue($session->Is_Expired());
    }
    function test_Session_Timeout()
    {

        $toolbelt = new \Test_Tools\toolbelt;
        $session = new \API\Program_Session();
        $company = new \Company\Company;
        $company->Load_Company_By_ID(1);
        $session_time_limit = $company->Get_Session_Time_Limit();
        $company->Set_Session_Time_Limit(2);
        $session->Create_New_Session($toolbelt->cConfigs->Get_Client_ID(),$toolbelt->cConfigs->Get_Secret_ID(),1,'temp_user_delete_me','Basic_Password');
        $this->assertFalse($session->Is_Expired());
        sleep(3);
        $this->assertTrue($session->Is_Expired());
        $company->Set_Session_Time_Limit($session_time_limit);
    }
    function test_Delete_Temp_User()
    {
        $user = new \Authentication\User('temp_user_delete_me','Basic_Password',1,false);
        $user->Delete_User();
        $this->expectException(\Authentication\User_Does_Not_Exist::class);
        $user = new \Authentication\User('temp_user','Basic_Password',1,false);

    }

}

?>