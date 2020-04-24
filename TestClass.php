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
    public ?\app\Helpers\Company $Company = null;
    public ?\API\Program $Program = null;
    public ?\API\Program_Session $Program_Session = null;
    public ?\app\Helpers\Route $Route = null;
    public ?\Authentication\User $User = null;

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

    }


    public function Get_Program() : \API\Program
    {
        if(is_null($this->Program) || $this->cConfigs->Is_Dev())
        {
            $this->Program = app()->make('Program');
        }
        return $this->Program;
    }

    public function Get_Company() : \app\Helpers\Company
    {
        if(is_null($this->Company))
        {
            $this->Company = app()->make('Company');
        }
        return $this->Company;
    }

    public function Get_Program_Session($username = false) : \API\Program_Session
    {
        if(is_null($this->Program_Session) || $this->cConfigs->Is_Dev())
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

    public function Get_Route() : \app\Helpers\Route
    {
        if(is_null($this->Route) || $this->cConfigs->Is_Dev())
        {
            $this->Route = app()->make('Route');
        }
        return $this->Route;
    }

    /**
     * @param int $user_object_type 0 = Get_Active_User, 1 = Get_Any_User, 2 = Create_User, 3 = Update_User
     */
    public function Get_User(int $user_object_type) : \Authentication\User
    {
        if(is_null($this->User) || $this->cConfigs->Is_Dev())
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
}

?>
