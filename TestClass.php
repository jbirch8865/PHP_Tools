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
    public \DatabaseLink\Table $Peoples;
    public \DatabaseLink\Table $People_Belong_To_Company;
    public \DatabaseLink\Table $Customers;
    public \DatabaseLink\Table $Credit_Statuses;
    public \DatabaseLink\Table $Equipments;
    public \DatabaseLink\Table $Addresses;
    public \DatabaseLink\Table $Customer_Has_Addresses;
    public \DatabaseLink\Table $Phone_Numbers;
    public \DatabaseLink\Table $Customer_Has_Phone_Numbers;
    public \DatabaseLink\Table $Tags;
    public ?\app\Helpers\Program_Session $documentation_session = null;
    public ?\app\Helpers\Company $Company = null;
    public ?\app\Helpers\Program $Program = null;
    public ?\app\Helpers\Program_Session $Program_Session = null;
    public ?\app\Helpers\Route $Route = null;
    public ?\app\Helpers\User $User = null;
    public ?\app\Helpers\Employee $Employee = null;
    public ?\app\Helpers\People $People = null;
    public ?\app\Helpers\Customer $Customer = null;
    public ?\app\Helpers\Credit_Status $Credit_Status = null;
    public ?\app\Helpers\Equipment $Equipment = null;
    public ?\app\Helpers\Address $Address = null;
    public ?\app\Helpers\Customer_Address $Customer_Address = null;
    public ?\app\Helpers\Customer_Phone_Number $Customer_Phone_Number = null;
    public ?\app\Helpers\Phone_Number $Phone_Number = null;

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

    public function Get_Program(bool $send_response = true) : \app\Helpers\Program
    {
        if(is_null($this->Program))
        {
            $this->Program = $this->Get_Bind_Object('Program',2,$send_response);
        }
        return $this->Program;
    }
    private function Null_Program() : void
    {
        $this->Program = null;
    }

    public function Get_Company(bool $access_token = true,bool $send_response = true) : \app\Helpers\Company
    {
        if(is_null($this->Company))
        {
            if($access_token)
            {
                $this->Company = $this->Get_Bind_Object('Company_Access_Token',null,$send_response,'Company');
            }else
            {
                $this->Company = $this->Get_Bind_Object('Company',null,$send_response,'Company');
            }
        }
        return $this->Company;
    }
    private function Null_Company() : void
    {
        $this->Company = null;
    }

    public function Get_Program_Session(bool $username = false,bool $send_response) : \app\Helpers\Program_Session
    {
        if(is_null($this->Program_Session))
        {
            if($username)
            {
                $this->Program_Session = $this->Get_Bind_Object('Program_Session_Username',null,$send_response,'Program_Session');
            }else
            {
                $this->Program_Session = $this->Get_Bind_Object('Program_Session_Access_Token',null,$send_response,'Program_Session');
            }
        }
        return $this->Program_Session;
    }
    private function Null_Program_Session() : void
    {
        $this->Program_Session = null;
    }

    public function Get_Route(bool $send_response = true) : \app\Helpers\Route
    {
        if(is_null($this->Route))
        {
            $this->Route = $this->Get_Bind_Object('Route',null,$send_response,'Route');
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
    public function Get_User(int $user_object_type, bool $send_response = true) : \app\Helpers\User
    {
        if(is_null($this->User))
        {
            if($user_object_type == 0)
            {
                $this->User = $this->Get_Bind_Object('Get_Active_User',null,$send_response,'User');
            }elseif($user_object_type == 1)
            {
                $this->User = $this->Get_Bind_Object('Get_Any_User',null,$send_response,'User');
            }elseif($user_object_type == 2)
            {
                $this->User = $this->Get_Bind_Object('Create_User',null,$send_response,'User');
            }elseif($user_object_type == 3)
            {
                $this->User = $this->Get_Bind_Object('Update_User',null,$send_response,'User');
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
     * @param string $object_type the name of the bind declaration
     * @param ?string $name the name of the active record class if bind declaration is odd
     * @param int $object_version 1 = Delete_Employee, 2 = Create_Employee, 3 = Update_Employee
     * @throws \Exception if object_type does not exist
     * @throws \Exception if $object_version is not 1,2 or 3
     */
    private function Get_Bind_Object(string $object_type,?int $object_version,bool $send_response,?string $name = null) : ?\Active_Record\Active_Record
    {
        try
        {
            if(!is_null($name))
            {
                if(is_null($this->$name))
                {
                    $this->$name = app()->make($object_type);
                }
                return $this->$name;
            }
            if(is_null($this->$object_type))
            {
                if($object_version == 1)
                {
                    $this->$object_type = app()->make('Delete_'.$object_type);
                }elseif($object_version == 2)
                {
                    $this->$object_type = app()->make('Create_'.$object_type);
                }elseif($object_version == 3)
                {
                    $this->$object_type = app()->make('Update_'.$object_type);
                }else
                {
                    throw new \Exception('sorry '.$object_version.' is not a valid 1,2, or 3');
                }
            }
        } catch (\Active_Record\Object_Is_Currently_Inactive $e)
        {
            if($send_response)
            {
                Response_422(['message' => 'The '.$object_type.' is currently innactive'],app()->request)->send();
                exit();
            }
        }
        return $this->$object_type;
    }
    /**
     * Only for create employee
     */
    public function Get_Employee(bool $send_response = true) : \app\Helpers\Employee
    {
        return $this->Get_Bind_Object('Employee',2,$send_response);
    }
    private function Null_Employee() : void
    {
        $this->Employee = null;
    }
    /**
     * @param int $object_type 1 = Delete_Employee , 3 = Update_Employee
     */
    public function Get_People(int $object_type,bool $send_response = true) : \app\Helpers\People
    {
        if($object_type == 2)
        {
            throw new \Exception('Cannot create a person, must use the Get_Link_Person method');
        }
        return $this->Get_Bind_Object('People',$object_type,$send_response);
    }
    private function Null_People() : void
    {
        $this->People = null;
    }
    /**
     * @param int $user_object_type 1 = Delete_Customer, 2 = Create_Customer, 3 = Update_Customer
     */
    public function Get_Customer(int $object_type,bool $send_response = true) : \app\Helpers\Customer
    {
        return $this->Get_Bind_Object('Customer',$object_type,$send_response);
    }
    private function Null_Customer() : void
    {
        $this->Customer = null;
    }
    /**
     * @param int $user_object_type 1 = Delete_Customer, 2 = Create_Customer, 3 = Update_Customer
     */
    public function Get_Credit_Status(int $object_type,bool $send_response = true) : \app\Helpers\Credit_Status
    {
        return $this->Get_Bind_Object('Credit_Status',$object_type,$send_response);
    }
    private function Null_Credit_Status() : void
    {
        $this->Credit_Status = null;
    }
    /**
     * @param int $user_object_type 1 = Delete_Equipment, 2 = Create_Equipment, 3 = Update_Equipment
     */
    public function Get_Equipment(int $object_type,bool $send_response = true) : \app\Helpers\Equipment
    {
        return $this->Get_Bind_Object('Equipment',$object_type,$send_response);
    }
    private function Null_Equipment() : void
    {
        $this->Equipment = null;
    }
    /**
     * @param int $user_object_type 1 = Delete_Address, 3 = Update_Address
     */
    public function Get_Address(int $object_type,bool $send_response = true) : \app\Helpers\Address
    {
        if($object_type == 2)
        {
            throw new \Exception('Cannot create an address, must use the Get_Link_Address method');
        }
        return $this->Get_Bind_Object('Address',$object_type,$send_response);
    }
    private function Null_Address() : void
    {
        $this->Address = null;
    }
    public function Get_Customer_Address(bool $send_response = true) : \app\Helpers\Customer_Address
    {
        return $this->Get_Bind_Object('Customer_Address',2,$send_response);
    }
    private function Null_Customer_Address() : void
    {
        $this->Customer_Address = null;
    }
    /**
     * @param int $user_object_type 1 = Delete_Phone_Number, 3 = Update_Phone_Number
     */
    public function Get_Phone_Number(int $object_type,bool $send_response = true) : \app\Helpers\Phone_Number
    {
        if($object_type == 2)
        {
            throw new \Exception('Cannot create a Phone Number, must use the Get_Link_Phone_Number method');
        }
        return $this->Get_Bind_Object('Phone_Number',$object_type,$send_response);
    }
    private function Null_Phone_Number() : void
    {
        $this->Phone_Number = null;
    }
    public function Get_Customer_Phone_Number(bool $send_response = true) : \app\Helpers\Customer_Phone_Number
    {
        return $this->Get_Bind_Object('Customer_Phone_Number',2,$send_response);
    }
    private function Null_Customer_Phone_Number() : void
    {
        $this->Customer_Phone_Number = null;
    }
    public function Null_All() : void
    {
        $this->Null_Company();
        $this->Null_Program();
        $this->Null_Program_Session();
        $this->Null_Employee();
        $this->Null_People();
        $this->Null_Route();
        $this->Null_User();
        $this->Null_Customer();
        $this->Null_Credit_Status();
        $this->Null_Equipment();
        $this->Null_Customer_Address();
        $this->Null_Address();
        $this->Null_Phone_Number();
        $this->Null_Customer_Phone_Number();
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
    public \DatabaseLink\Table $Peoples;
    public \DatabaseLink\Table $People_Belong_To_Company;
    public \DatabaseLink\Table $Credit_Statuses;
    public \DatabaseLink\Table $Customers;
    public \DatabaseLink\Table $Equipments;
    public \DatabaseLink\Table $Addresses;
    public \DatabaseLink\Table $Customer_Has_Addresses;
    public \DatabaseLink\Table $Phone_Numbers;
    public \DatabaseLink\Table $Customer_Has_Phone_Numbers;
    public \DatabaseLink\Table $Tags;
    public \DatabaseLink\Table $Object_Has_Tags;


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
        $this->Peoples = $toolbelt_base->Peoples;
        $this->People_Belong_To_Company = $toolbelt_base->People_Belong_To_Company;
        $this->Credit_Statuses = $toolbelt_base->Credit_Statuses;
        $this->Customers = $toolbelt_base->Customers;
        $this->Equipments = $toolbelt_base->Equipments;
        $this->Addresses = $toolbelt_base->Addresses;
        $this->Customer_Has_Addresses = $toolbelt_base->Customer_Has_Addresses;
        $this->Phone_Numbers = $toolbelt_base->Phone_Numbers;
        $this->Customer_Has_Phone_Numbers = $toolbelt_base->Customer_Has_Phone_Numbers;
        $this->Tags = $toolbelt_base->Tags;
        $this->Object_Has_Tags = $toolbelt_base->Object_Has_Tags;
    }

    public function Create_Sessions_Token_For_Documentation() : \app\Helpers\Program_Session
    {
        global $toolbelt_base;
        return $toolbelt_base->Create_Sessions_Token_For_Documentation();
    }

    public function Get_Program(bool $send_response = true) : \app\Helpers\Program
    {
        global $toolbelt_base;
        return $toolbelt_base->Get_Program($send_response);
    }

    public function Get_Company(bool $access_token = true,bool $send_response = true) : \app\Helpers\Company
    {
        global $toolbelt_base;
        return $toolbelt_base->Get_Company($access_token,$send_response);
    }

    public function Get_Program_Session(bool $username = false,bool $send_response = true) : \app\Helpers\Program_Session
    {
        global $toolbelt_base;
        return $toolbelt_base->Get_Program_Session($username,$send_response);
    }

    public function Get_Route(bool $send_response = true) : \app\Helpers\Route
    {
        global $toolbelt_base;
        return $toolbelt_base->Get_Route($send_response);
    }

    /**
     * @param int $user_object_type 0 = Get_Active_User, 1 = Get_Any_User, 2 = Create_User, 3 = Update_User
     */
    public function Get_User(int $object_type,bool $send_response = true) : \app\Helpers\User
    {
        global $toolbelt_base;
        return $toolbelt_base->Get_User($object_type,$send_response);
    }
    /**
     * @param int $user_object_type 1 = Delete_Employee, 3 = Update_Employee
     */
    public function Get_People(int $object_type,bool $send_response = true) : \app\Helpers\People
    {
        global $toolbelt_base;
        return $toolbelt_base->Get_People($object_type,$send_response);
    }
    /**
     * Create Employee only
     */
    public function Get_Employee(bool $send_response = true) : \app\Helpers\Employee
    {
        global $toolbelt_base;
        return $toolbelt_base->Get_Employee($send_response);
    }
    /**
     * @param int $object_type 1 = Delete_Customer, 2 = Create_Customer, 3 = Update_Customer
     */
    public function Get_Customer(int $object_type,bool $send_response = true) : \app\Helpers\Customer
    {
        global $toolbelt_base;
        return $toolbelt_base->Get_Customer($object_type,$send_response);
    }
    /**
     * @param int $object_type 1 = Delete_Credit_status, 2 = Create_Credit_Status, 3 = Update_Credit_Status
     */
    public function Get_Credit_Status(int $object_type,bool $send_response = true) : \app\Helpers\Credit_Status
    {
        global $toolbelt_base;
        return $toolbelt_base->Get_Credit_Status($object_type,$send_response);
    }
    /**
     * @param int $object_type 1 = Delete_Equipment, 2 = Create_Equipment, 3 = Update_Equipment
     */
    public function Get_Equipment(int $object_type,bool $send_response = true) : \app\Helpers\Equipment
    {
        global $toolbelt_base;
        return $toolbelt_base->Get_Equipment($object_type,$send_response);
    }
    /**
     * @param int $object_type 1 = Delete_Equipment, 3 = Update_Equipment
     */
    public function Get_Address(int $object_type,bool $send_response = true) : \app\Helpers\Address
    {
        global $toolbelt_base;
        return $toolbelt_base->Get_Address($object_type,$send_response);
    }
    /**
     * @param int Create Customer Address only
     */
    public function Get_Customer_Address(bool $send_response = true) : \app\Helpers\Customer_Address
    {
        global $toolbelt_base;
        return $toolbelt_base->Get_Customer_Address($send_response);
    }
    /**
     * @param int $object_type 1 = Delete_Phone_Number, 3 = Update_Phone_Number
     */
    public function Get_Phone_Number(int $object_type,bool $send_response = true) : \app\Helpers\Phone_Number
    {
        global $toolbelt_base;
        return $toolbelt_base->Get_Phone_Number($object_type,$send_response);
    }
    /**
     * @param int Create Customer Phone Number only
     */
    public function Get_Customer_Phone_Number(bool $send_response = true) : \app\Helpers\Customer_Phone_Number
    {
        global $toolbelt_base;
        return $toolbelt_base->Get_Customer_Phone_Number($send_response);
    }


    public function Get_Companies() : \DatabaseLink\Table{return $this->Companies;}
    public function Get_Programs() : \DatabaseLink\Table{return $this->Programs;}
    public function Get_Configs() : \DatabaseLink\Table{return $this->Configs;}
    public function Get_Rights() : \DatabaseLink\Table{return $this->Rights;}
    public function Get_Routes() : \DatabaseLink\Table{return $this->Routes;}
    public function Get_Programs_Have_Sessions() : \DatabaseLink\Table{return $this->Programs_Have_Sessions;}
    public function Get_Company_Configs() : \DatabaseLink\Table
    {
        if(!$this->Company_Configs->Validate_Where_Logic_Started())
        {
            $this->Company_Configs->LimitBy($this->Company_Configs->Get_Column('company_id')->
            Equals($this->Get_Company()->Get_Verified_ID()),true);
        }
        return $this->Company_Configs;
    }
    public function Get_Users(bool $access_token = true) : \DatabaseLink\Table
    {
        if(!$this->Users->Validate_Where_Logic_Started())
        {
            $this->Users->LimitBy($this->Users->Get_Column('company_id')->
            Equals($this->Get_Company($access_token)->Get_Verified_ID()),true);
        }
        return $this->Users;
    }
    public function Get_Users_Have_Roles(bool $access_token = true) : \DatabaseLink\Table
    {
        if(!$this->Users_Have_Roles->Validate_Where_Logic_Started())
        {
            $this->Users_Have_Roles->LimitBy($this->Users->Get_Column('company_id')->
            Equals($this->Get_Company($access_token)->Get_Verified_ID()),true);
        }
        return $this->Users;
    }
    public function Get_Company_Roles() : \DatabaseLink\Table
    {
        if(!$this->Company_Roles->Validate_Where_Logic_Started())
        {
            $this->Company_Roles->LimitBy($this->Company_Roles->Get_Column('company_id')->
            Equals($this->Get_Company()->Get_Verified_ID()),true);
        }
        return $this->Company_Roles;
    }
    public function Get_Routes_Have_Roles(bool $access_token = true) : \DatabaseLink\Table
    {
        if(!$this->Routes_Have_Roles->Validate_Where_Logic_Started())
        {
            $this->Routes_Have_Roles->InnerJoinWith($this->Get_Company_Roles()->
            Get_Column('id'),$this->Routes_Have_Roles->Get_Column('role_id'),true);
            $this->Routes_Have_Roles->LimitBy(
            $this->Get_Company_Roles()->Get_Column('company_id')->
            Equals($this->Get_Company($access_token)->Get_Verified_ID()),true);
        }
        return $this->Routes_Have_Roles;
    }
    public function Get_Peoples() : \DatabaseLink\Table
    {
        if(!$this->Peoples->Validate_Where_Logic_Started())
        {
            $this->Peoples->LimitBy($this->Peoples->Get_Column('company_id')->
            Equals($this->Get_Company()->Get_Verified_ID()),true);
        }
        return $this->Peoples;
    }
    public function Get_People_Belong_To_Company() : \DatabaseLink\Table
    {
        if(!$this->People_Belong_To_Company->Validate_Where_Logic_Started())
        {
            $this->People_Belong_To_Company->InnerJoinWith($this->Get_Peoples()->
            Get_Column('id'),$this->People_Belong_To_Company->Get_Column('people_id'),true);
            $this->People_Belong_To_Company->LimitBy(
            $this->People_Belong_To_Company->Get_Column('company_id')->
            Equals($this->Get_Company()->Get_Verified_ID()),true);
        }
        return $this->People_Belong_To_Company;
    }
    public function Get_Customers() : \DatabaseLink\Table
    {
        if(!$this->Customers->Validate_Where_Logic_Started())
        {
            $this->Customers->LimitBy($this->Customers->Get_Column('company_id')->
            Equals($this->Get_Company()->Get_Verified_ID()),true);
        }
        return $this->Customers;
    }
    public function Get_Credit_Statuses() : \DatabaseLink\Table
    {
        if(!$this->Credit_Statuses->Validate_Where_Logic_Started())
        {
            $this->Credit_Statuses->LimitBy($this->Credit_Statuses->Get_Column('company_id')->
            Equals($this->Get_Company()->Get_Verified_ID()),true);
        }
        return $this->Credit_Statuses;
    }
    public function Get_Equipments() : \DatabaseLink\Table
    {
        if(!$this->Equipments->Validate_Where_Logic_Started())
        {
            $this->Equipments->LimitBy($this->Equipments->Get_Column('company_id')->
            Equals($this->Get_Company()->Get_Verified_ID()),true);
        }
        return $this->Equipments;
    }
    public function Get_Addresses() : \DatabaseLink\Table
    {
        if(!$this->Addresses->Validate_Where_Logic_Started())
        {
            $this->Addresses->LimitBy($this->Addresses->Get_Column('company_id')->
            Equals($this->Get_Company()->Get_Verified_ID()),true);
        }
        return $this->Addresses;
    }
    public function Get_Customer_Has_Addresses() : \DatabaseLink\Table
    {
        if(!$this->Customer_Has_Addresses->Validate_Where_Logic_Started())
        {
            $this->Customer_Has_Addresses->InnerJoinWith($this->Get_Addresses()->
            Get_Column('id'),$this->Customer_Has_Addresses->Get_Column('address_id'),true);
            $this->Customer_Has_Addresses->LimitBy(
            $this->Customer_Has_Addresses->Get_Column('customer_id')->
            Equals($this->Get_Customer(3)->Get_Verified_ID()),true);
        }
        return $this->Customer_Has_Addresses;
    }
    public function Get_Phone_Numbers() : \DatabaseLink\Table
    {
        if(!$this->Phone_Numbers->Validate_Where_Logic_Started())
        {
            $this->Phone_Numbers->LimitBy($this->Phone_Numbers->Get_Column('company_id')->
            Equals($this->Get_Company()->Get_Verified_ID()),true);
        }
        return $this->Phone_Numbers;
    }
    public function Get_Customer_Has_Phone_Numbers() : \DatabaseLink\Table
    {
        if(!$this->Customer_Has_Phone_Numbers->Validate_Where_Logic_Started())
        {
            $this->Customer_Has_Phone_Numbers->InnerJoinWith($this->Get_Phone_Numbers()->
            Get_Column('id'),$this->Customer_Has_Phone_Numbers->Get_Column('phone_number_id'),true);
            $this->Customer_Has_Phone_Numbers->LimitBy(
            $this->Customer_Has_Phone_Numbers->Get_Column('customer_id')->
            Equals($this->Get_Customer(3)->Get_Verified_ID()),true);
        }
        return $this->Customer_Has_Phone_Numbers;
    }
    public function Get_Tags() : \DatabaseLink\Table
    {
        if(!$this->Tags->Validate_Where_Logic_Started())
        {
            $this->Tags->LimitBy(
            $this->Tags->Get_Column('customer_id')->
            Equals($this->Get_Customer(3)->Get_Verified_ID()),true);
        }
        return $this->Tags;
    }
    public function Get_Objects_Have_Tags() : \DatabaseLink\Table
    {
        if(!$this->Object_Has_Tags->Validate_Where_Logic_Started())
        {
            $this->Object_Has_Tags->InnerJoinWith($this->Get_Tags()->
            Get_Column('id'),$this->Customer_Has_Addresses->Get_Column('tag_id'),true);
            $this->Object_Has_Tags->LimitBy(
            $this->Object_Has_Tags->Get_Column('company_id')->
            Equals($this->Get_Company()->Get_Verified_ID()),true);
        }
        return $this->Object_Has_Tags;
    }
}

?>
