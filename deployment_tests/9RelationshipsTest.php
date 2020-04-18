<?php declare(strict_types=1);

use Authentication\User_Session;
use DatabaseLink\SQLQueryError;

class RelationshipsTest extends \PHPUnit\Framework\TestCase
{
    private \Test_Tools\toolbelt $toolbelt;
    private \Company\Company $company;
    private \Authentication\User $user1;
    private \Authentication\User $user2;
    private \Authentication\User $user3;
    private \Authentication\User $user4;
    private \API\Program_Session $session;
    
    public function __construct()
    {
        parent::__construct();
        $toolbelt = new \Test_Tools\toolbelt();
        $this->toolbelt = $toolbelt;        
        $this->company = new \Company\Company;
        try
        {
            $this->company->Set_Company_Name('relationship_test');
            $this->user1 = new \Authentication\User('relationship_user_1','some_password',$this->company,true);
            $this->user2 = new \Authentication\User('relationship_user_2','some_password',$this->company,true);
            $this->user3 = new \Authentication\User('relationship_user_3','some_password',$this->company,true);
            $this->user4 = new \Authentication\User('relationship_user_4','some_password',$this->company,true);
            $this->company->Create_Company_Role('test_role_1');
            $this->company->Create_Company_Role('test_role_2');
            $this->company->Create_Company_Role('test_role_3');
            $this->company->Create_Company_Role('test_role_4');
            $user_role = new \Authentication\User_Role;
            $user_role->Set_Role($this->company->Company_Roles[0],false);
            $user_role->Set_User($this->user1,true);
            $user_role = new \Authentication\User_Role;
            $user_role->Set_Role($this->company->Company_Roles[1],false);
            $user_role->Set_User($this->user1,true);
            $user_role = new \Authentication\User_Role;
            $user_role->Set_Role($this->company->Company_Roles[2],false);
            $user_role->Set_User($this->user2,true);
            $user_role = new \Authentication\User_Role;
            $user_role->Set_Role($this->company->Company_Roles[0],false);
            $user_role->Set_User($this->user3,true);
            $user_role = new \Authentication\User_Role;
            $user_role->Set_Role($this->company->Company_Roles[3],false);
            $user_role->Set_User($this->user4,true);
            $user_role = new \Authentication\User_Role;
            $user_role->Set_Role($this->company->Company_Roles[2],false);
            $user_role->Set_User($this->user4,true);
            $this->session = new \API\Program_Session();
            $this->session->Create_New_Session($this->toolbelt->cConfigs->Get_Client_ID(),$this->company,'relationship_user_1','some_password');

        } catch (\Active_Record\UpdateFailed $e)
        {
            $this->company->Load_Company_By_Name('relationship_test');
            $this->user1 = new \Authentication\User('relationship_user_1','some_password',$this->company);
            $this->user2 = new \Authentication\User('relationship_user_2','some_password',$this->company);
            $this->user3 = new \Authentication\User('relationship_user_3','some_password',$this->company);
            $this->user4 = new \Authentication\User('relationship_user_4','some_password',$this->company);
            $this->session = new \API\Program_Session();
            $this->session->Create_New_Session($this->toolbelt->cConfigs->Get_Client_ID(),$this->company,'relationship_user_1','some_password');
        }
    }
	public function setUp() :void
	{
    }
    
    public function tearDown() :void
    {
    }
    
    function test_Companies_Roles()
    {
        $this->assertIsArray($this->company->Company_Roles);
        $this->assertEquals(4,count($this->company->Company_Roles));
        $role_exists = false;
        ForEach($this->company->Company_Roles as $company_role)
        {
            if($company_role->Get_Friendly_Name() == 'test_role_1')
            {
                $role_exists = true;
                break;
            }
        }
        $this->assertTrue($role_exists);
    }

    function test_Companies_Configs()
    {
        $this->assertIsArray($this->company->Company_Configs);
        $this->assertGreaterThanOrEqual(2,count($this->company->Company_Configs));
        $config_exists = false;
        $config = new \Company\Config;
        $config->Load_Config_By_Name('session_time_limit');
        ForEach($this->company->Company_Configs as $company_config)
        {
            if($company_config->Get_Config_ID() == $config->Get_Verified_ID())
            {
                $config_exists = true;
                break;
            }
        }
        $this->assertTrue($config_exists);
    }

    function test_Comp_Config_Company()
    {
        $this->assertEquals($this->company->Get_Verified_ID(),$this->company->Company_Configs[0]->Companies->Get_Verified_ID());
    }

    function test_Comp_Config_Config()
    {
        $this->assertGreaterThan(0,$this->company->Company_Configs[0]->Configs->Get_Verified_ID());
    }

    function test_Comp_Roles_Company()
    {
        $this->assertEquals($this->company->Company_Roles[0]->Companies->Get_Verified_ID(),$this->company->Get_Verified_ID());
    }
    function test_Comp_Roles_Users_Roles()
    {
        $this->assertIsArray($this->company->Company_Roles[0]->Users_Have_Roles);
        $this->assertEquals(2,count($this->company->Company_Roles[0]->Users_Have_Roles));
        $this->assertGreaterThan(0,$this->company->Company_Roles[0]->Users_Have_Roles[0]->Get_Verified_ID());
    }

    function test_Prog_Sess_Users_Roles()
    {
        $this->assertIsArray($this->session->Users_Have_Roles);
        $this->assertEquals(2,count($this->session->Users_Have_Roles));
        $this->assertGreaterThan(0,$this->session->Users_Have_Roles[0]->Get_Verified_ID());        
    }

    function test_Users_Company()
    {
        $this->assertEquals($this->company->Get_Verified_ID(),$this->user1->Companies->Get_Verified_ID());
    }

    function test_User_Roles_Comp_Role()
    {
        $this->assertEquals('test_role_1',$this->session->Users_Have_Roles[0]->Company_Roles->Get_Friendly_Name());
    }

    function test_Clean_Up()
    {
        $this->company->Delete_Company(false);
        $this->addToAssertionCount(1);
    }

}

?>