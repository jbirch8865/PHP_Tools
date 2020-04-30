<?php
namespace Test_Tools;

class toolbelt_base
{
    public \config\ConfigurationFile $cConfigs;
    public \DatabaseLink\MySQLLink $root_dblink;
    public \DatabaseLink\Database $dblink;
    public \DatabaseLink\Database $read_only_dblink;
    public \DatabaseLink\Table $Companies;
    public \DatabaseLink\Table $Programs;
    public \DatabaseLink\Table $Configs;
    public \DatabaseLink\Table $Company_Configs;
    public \DatabaseLink\Table $Users;
    public \DatabaseLink\Table $Programs_Have_Sessions;
    public \DatabaseLink\Table $Users_Have_Roles;
    public \DatabaseLink\Table $Company_Roles;
    public \Active_Record\RelationshipManager $active_record_relationship_manager;
    public \DatabaseLink\Table $Rights;
    public \DatabaseLink\Table $Routes;
    public \DatabaseLink\Table $Routes_Have_Roles;
    public \DatabaseLink\Table $People;
    public \DatabaseLink\Table $People_Belong_To_Company;
    public ?\app\Helpers\Program_Session $documentation_session = null;
    public ?\app\Helpers\Company $Company = null;
    public ?\app\Helpers\Program $Program = null;
    public ?\app\Helpers\Program_Session $Program_Session = null;
    public ?\app\Helpers\Route $Route = null;
    public ?\app\Helpers\User $User = null;
    public ?\app\Helpers\Employee $Employee = null;

    public function Create_Sessions_Token_For_Documentation()
    {
        if(is_null($this->documentation_session))
        {
            $session = new \app\Helpers\Program_Session;
            $company = new \app\Helpers\Company;
            $company->Load_Object_By_ID(1);
            $session->Create_New_Session($session->cConfigs->Get_Client_ID(),$company,'default',$session->cConfigs->Get_Client_ID());
            $this->documentation_session = $session;
        }
        return $this->documentation_session;
    }

    /**
     * Call protected/private method of a class.
     *
     * @param object &$object    Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    public function Get_Program() : \app\Helpers\Program
    {
        if(is_null($this->Program))
        {
            $this->Program = app()->make('Program');
        }
        return $this->Program;
    }
    private function Null_Program() : void
    {
        $this->Program = null;
    }

    public function Get_Company(bool $access_token = true) : \app\Helpers\Company
    {
        if(is_null($this->Company))
        {
            if($access_token)
            {
                $this->Company = app()->make('Company_Access_Token');
            }else
            {
                $this->Company = app()->make('Company');
            }
        }
        return $this->Company;
    }
    private function Null_Company() : void
    {
        $this->Company = null;
    }

    public function Get_Program_Session(bool $username = false) : \app\Helpers\Program_Session
    {
        if(is_null($this->Program_Session))
        {
            if($username)
            {
                $this->Program_Session = app()->make('Program_Session_Username');
            }else
            {
                $this->Program_Session = app()->make('Program_Session_Access_Token');
            }
        }
        return $this->Program_Session;
    }
    private function Null_Program_Session() : void
    {
        $this->Program_Session = null;
    }

    public function Get_Route() : \app\Helpers\Route
    {
        if(is_null($this->Route))
        {
            $this->Route = app()->make('Route');
        }
        return $this->Route;
    }
    private function Null_Route() : void
    {
        $this->Route = null;
    }

    /**
     * @param int $user_object_type 0 = Get_Active_User, 1 = Get_Any_User, 2 = Create_User, 3 = Update_User
     */
    public function Get_User(int $user_object_type) : ?\app\Helpers\User
    {
        if(is_null($this->User))
        {
            if($user_object_type == 0)
            {
                $this->User = app()->make('Get_Active_User');
            }elseif($user_object_type == 1)
            {
                $this->User = app()->make('Get_Any_User');
            }elseif($user_object_type == 2)
            {
                $this->User = app()->make('Create_User');
            }elseif($user_object_type == 3)
            {
                $this->User = app()->make('Update_User');
            }else
            {
                throw new \Exception('sorry '.$user_object_type.' is not a valid user_object_type for $toolbelt->Get_User');
            }
        }
        return $this->User;
    }
    private function Null_User() : void
    {
        $this->User = null;
    }

    /**
     * @param int $user_object_type 1 = Get_Any_Employee, 2 = Create_Employee, 3 = Update_Employee
     */
    public function Get_Employee(int $user_object_type) : ?\app\Helpers\Employee
    {
        if(is_null($this->Employee))
        {
            if($user_object_type == 1)
            {
                $this->Employee = app()->make('Get_Any_Employee');
            }elseif($user_object_type == 2)
            {
                $this->Employee = app()->make('Create_Employee');
            }elseif($user_object_type == 3)
            {
                $this->Employee = app()->make('Update_Employee');
            }else
            {
                throw new \Exception('sorry '.$user_object_type.' is not a valid employee_object_type for $toolbelt->Get_Employee');
            }
        }
        return $this->Employee;
    }
    private function Null_Employee() : void
    {
        $this->Employee = null;
    }


    public function Null_All() : void
    {
        $this->Null_Company();
        $this->Null_Program();
        $this->Null_Program_Session();
        $this->Null_Employee();
        $this->Null_Route();
        $this->Null_User();
    }

}

class toolbelt extends toolbelt_base
{
    public \config\ConfigurationFile $cConfigs;
    public \DatabaseLink\MySQLLink $root_dblink;
    public \DatabaseLink\Database $dblink;
    public \DatabaseLink\Database $read_only_dblink;
    public \DatabaseLink\Table $Companies;
    public \DatabaseLink\Table $Programs;
    public \DatabaseLink\Table $Configs;
    public \DatabaseLink\Table $Company_Configs;
    public \DatabaseLink\Table $Users;
    public \DatabaseLink\Table $Programs_Have_Sessions;
    public \DatabaseLink\Table $Users_Have_Roles;
    public \DatabaseLink\Table $Company_Roles;
    public \Active_Record\RelationshipManager $active_record_relationship_manager;
    public \DatabaseLink\Table $Rights;
    public \DatabaseLink\Table $Routes;
    public \DatabaseLink\Table $Routes_Have_Roles;
    public \DatabaseLink\Table $People;
    public \DatabaseLink\Table $People_Belong_To_Company;


    function __construct()
    {
        global $toolbelt_base;
        $this->cConfigs = $toolbelt_base->cConfigs;
        $this->root_dblink = $toolbelt_base->root_dblink;
        $this->dblink = $toolbelt_base->dblink;
        $this->read_only_dblink = $toolbelt_base->read_only_dblink;
        $this->Companies = $toolbelt_base->Companies;
        $this->Programs = $toolbelt_base->Programs;
        $this->Configs = $toolbelt_base->Configs;
        $this->Company_Configs = $toolbelt_base->Company_Configs;
        $this->Users = $toolbelt_base->Users;
        $this->Programs_Have_Sessions = $toolbelt_base->Programs_Have_Sessions;
        $this->Users_Have_Roles = $toolbelt_base->Users_Have_Roles;
        $this->Company_Roles = $toolbelt_base->Company_Roles;
        $this->active_record_relationship_manager = $toolbelt_base->active_record_relationship_manager;
        $this->Rights = $toolbelt_base->Rights;
        $this->Routes = $toolbelt_base->Routes;
        $this->Routes_Have_Roles = $toolbelt_base->Routes_Have_Roles;
        $this->People = $toolbelt_base->People;
        $this->People_Belong_To_Company = $toolbelt_base->People_Belong_To_Company;

    }

    public function Create_Sessions_Token_For_Documentation() : \app\Helpers\Program_Session
    {
        global $toolbelt_base;
        return $toolbelt_base->Create_Sessions_Token_For_Documentation();
    }

    public function Get_Program() : \app\Helpers\Program
    {
        global $toolbelt_base;
        return $toolbelt_base->Get_Program();
    }

    public function Get_Company(bool $access_token = true) : \app\Helpers\Company
    {
        global $toolbelt_base;
        return $toolbelt_base->Get_Company($access_token);
    }

    public function Get_Program_Session(bool $username = false) : \app\Helpers\Program_Session
    {
        global $toolbelt_base;
        return $toolbelt_base->Get_Program_Session($username);
    }

    public function Get_Route() : \app\Helpers\Route
    {
        global $toolbelt_base;
        return $toolbelt_base->Get_Route();
    }

    /**
     * @param int $user_object_type 0 = Get_Active_User, 1 = Get_Any_User, 2 = Create_User, 3 = Update_User
     */
    public function Get_User(int $user_object_type) : ?\app\Helpers\User
    {
        global $toolbelt_base;
        return $toolbelt_base->Get_User($user_object_type);
    }
    /**
     * @param int $user_object_type 1 = Get_Any_User, 2 = Create_User, 3 = Update_User
     */
    public function Get_Employee(int $user_object_type) : ?\app\Helpers\Employee
    {
        global $toolbelt_base;
        return $toolbelt_base->Get_Employee($user_object_type);
    }
}

?>
