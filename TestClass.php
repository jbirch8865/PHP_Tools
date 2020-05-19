<?php
namespace Test_Tools;

use Active_Record\Email_Address_Not_Valid;
use App\Rules\Does_This_Exist_In_Context;
use Illuminate\Support\Str;
use Active_Record\Active_Record;
use app\Helpers\Address;
use app\Helpers\Company;
use app\Helpers\Company_Config;
use app\Helpers\Company_Role;
use app\Helpers\Config;
use app\Helpers\Credit_Status;
use app\Helpers\Customer;
use app\Helpers\Customer_Address;
use app\Helpers\Customer_Has_Address;
use app\Helpers\Customer_Phone_Number;
use app\Helpers\Employee;
use app\Helpers\Employee_Company;
use app\Helpers\Equipment;
use app\Helpers\Object_Has_Tag;
use app\Helpers\People;
use app\Helpers\Phone_Number;
use app\Helpers\Program;
use app\Helpers\Program_Session;
use app\Helpers\Right;
use app\Helpers\Route;
use app\Helpers\Route_Role;
use app\Helpers\Tag;
use app\Helpers\Tags_Have_Role;
use app\Helpers\User;
use app\Helpers\User_Role;
use DatabaseLink\Safe_Strings;


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
    public \DatabaseLink\Table $Object_Has_Tags;
    public \DatabaseLink\Table $Tags_Have_Roles;
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
    public ?\app\Helpers\Tag $Tag = null;
    public ?\app\Helpers\Tag $Add_Tag = null;
    public ?\app\Helpers\Tags_Have_Role $Tags_Have_Role = null;

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
    private function Get_Bind_Object(string $object_type,?int $object_version,bool $send_response,?string $name = null,?Active_Record $active_record_to_add_tag_to = null) : ?\Active_Record\Active_Record
    {
        try
        {
            if(!is_null($name))
            {
                if(is_null($this->$name))
                {
                    $this->$name = app()->make($object_type,['object' => $active_record_to_add_tag_to]);
                }
                return $this->$name;
            }
            if(is_null($this->$object_type))
            {
                if($object_version == 1)
                {
                    $this->$object_type = app()->make('Delete_'.$object_type,['object' => $active_record_to_add_tag_to]);
                }elseif($object_version == 2)
                {
                    $this->$object_type = app()->make('Create_'.$object_type,['object' => $active_record_to_add_tag_to]);
                }elseif($object_version == 3)
                {
                    $this->$object_type = app()->make('Update_'.$object_type,['object' => $active_record_to_add_tag_to]);
                }else
                {
                    throw new \Exception('sorry '.$object_version.' is not a valid 1,2, or 3');
                }
            }
        } catch (\Active_Record\Object_Is_Currently_Inactive $e)
        {
            if($send_response)
            {
                $toolbelt = new Toolbelt;
                $toolbelt->functions->Response_422(['message' => 'The '.$object_type.' is currently innactive'],app()->request)->send();
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
    /**
     * @param int $object_type 1 = Delete_Tag, 2 = Create_Tag 3 = Update_Tag
     */
    public function Get_Tag(int $object_type,bool $send_response = true) : \app\Helpers\Tag
    {
        return $this->Get_Bind_Object('Tag',$object_type,$send_response);
    }
    private function Null_Tag() : void
    {
        $this->Tag = null;
    }
    /**
     * @param int $object_type 1 = Delete_Role_From_Tag, 2 = Add_Role_To_Tag
     */
    public function Get_Tags_Have_Role(int $object_type,bool $send_response = true) : \app\Helpers\Tags_Have_Role
    {
        if($object_type == 3)
        {
            throw new \Exception('cannot update Tags_Have_Role, must either create, which will override the current role setting or delete tags_have_role and then recreate.');
        }
        return $this->Get_Bind_Object('Tags_Have_Role',$object_type,$send_response);
    }
    private function Null_Tags_Have_Role() : void
    {
        $this->Tags_Have_Role = null;
    }
    /**
     * @param int $object_type 1 = Remove_Tag_From_Object, 2 = Add_Tag_To_Object
     */
    public function Get_Add_Tag(int $object_type,bool $send_response = true,?Active_Record $active_record_to_add_tag_to) : \app\Helpers\Tag
    {
        if($object_type == 3)
        {
            throw new \Exception('Can only Add_tag_to_Object or Remove_Tag_From_Object');
        }
        return $this->Get_Bind_Object('Add_Tag',$object_type,$send_response,null,$active_record_to_add_tag_to);
    }
    private function Null_Add_Tag() : void
    {
        $this->Add_Tag = null;
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
        $this->Null_Tag();
        $this->Null_Tags_Have_Role();
        $this->Null_Add_Tag();
    }

}
class Toolbelt extends toolbelt_base
{
    public \config\ConfigurationFile $cConfigs;
    public Test_Cases $test_cases;
    public Tables $tables;
    public Objects $objects;
    public Functions $functions;
    function __construct()
    {
        $this->test_cases = new Test_Cases;
        $this->tables = new Tables;
        $this->objects = new Objects;
        $this->functions = new Functions;
        global $toolbelt_base;
        $this->cConfigs = $toolbelt_base->cConfigs;
    }
    public function Create_Sessions_Token_For_Documentation() : \app\Helpers\Program_Session
    {
        global $toolbelt_base;
        return $toolbelt_base->Create_Sessions_Token_For_Documentation();
    }
}
class Tables
{
    public Toolbelt $toolbelt;
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
    public \DatabaseLink\Table $Tags_Have_Roles;

    function __construct()
    {
        $this->toolbelt = new Toolbelt;
        global $toolbelt_base;
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
        $this->Tags_Have_Roles = $toolbelt_base->Tags_Have_Roles;
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
            Equals($this->toolbelt->objects->Get_Company()->Get_Verified_ID()),true);
        }
        return $this->Company_Configs;
    }
    public function Get_Users(bool $access_token = true) : \DatabaseLink\Table
    {
        if(!$this->Users->Validate_Where_Logic_Started())
        {
            $this->Users->LimitBy($this->Users->Get_Column('company_id')->
            Equals($this->toolbelt->objects->Get_Company($access_token)->Get_Verified_ID()),true);
        }
        return $this->Users;
    }
    public function Get_Users_Have_Roles(bool $access_token = true) : \DatabaseLink\Table
    {
        if(!$this->Users_Have_Roles->Validate_Where_Logic_Started())
        {
            $this->Users_Have_Roles->LimitBy($this->Users->Get_Column('company_id')->
            Equals($this->toolbelt->objects->Get_Company($access_token)->Get_Verified_ID()),true);
        }
        return $this->Users;
    }
    public function Get_Company_Roles() : \DatabaseLink\Table
    {
        if(!$this->Company_Roles->Validate_Where_Logic_Started())
        {
            $this->Company_Roles->LimitBy($this->Company_Roles->Get_Column('company_id')->
            Equals($this->toolbelt->objects->Get_Company()->Get_Verified_ID()),true);
        }
        return $this->Company_Roles;
    }
    public function Get_Routes_Have_Roles(bool $access_token = true) : \DatabaseLink\Table
    {
        if(!$this->Routes_Have_Roles->Validate_Where_Logic_Started())
        {
            $this->Routes_Have_Roles->InnerJoinWith($this->Company_Roles->
            Get_Column('id'),$this->Routes_Have_Roles->Get_Column('role_id'),true);
            $this->Routes_Have_Roles->LimitBy(
            $this->Company_Roles->Get_Column('company_id')->
            Equals($this->toolbelt->objects->Get_Company($access_token)->Get_Verified_ID()),true);
        }
        return $this->Routes_Have_Roles;
    }
    public function Get_Peoples() : \DatabaseLink\Table
    {
        if(!$this->Peoples->Validate_Where_Logic_Started())
        {
            $this->Peoples->LimitBy($this->Peoples->Get_Column('company_id')->
            Equals($this->toolbelt->objects->Get_Company()->Get_Verified_ID()),true);
        }
        return $this->Peoples;
    }
    public function Get_People_Belong_To_Company() : \DatabaseLink\Table
    {
        if(!$this->People_Belong_To_Company->Validate_Where_Logic_Started())
        {
            $this->People_Belong_To_Company->InnerJoinWith($this->Peoples->
            Get_Column('id'),$this->People_Belong_To_Company->Get_Column('people_id'),true);
            $this->People_Belong_To_Company->LimitBy(
            $this->People_Belong_To_Company->Get_Column('company_id')->
            Equals($this->toolbelt->objects->Get_Company()->Get_Verified_ID()),true);
        }
        return $this->People_Belong_To_Company;
    }
    public function Get_Customers() : \DatabaseLink\Table
    {
        if(!$this->Customers->Validate_Where_Logic_Started())
        {
            $this->Customers->LimitBy($this->Customers->Get_Column('company_id')->
            Equals($this->toolbelt->objects->Get_Company()->Get_Verified_ID()),true);
        }
        return $this->Customers;
    }
    public function Get_Credit_Statuses() : \DatabaseLink\Table
    {
        if(!$this->Credit_Statuses->Validate_Where_Logic_Started())
        {
            $this->Credit_Statuses->LimitBy($this->Credit_Statuses->Get_Column('company_id')->
            Equals($this->toolbelt->objects->Get_Company()->Get_Verified_ID()),true);
        }
        return $this->Credit_Statuses;
    }
    public function Get_Equipments() : \DatabaseLink\Table
    {
        if(!$this->Equipments->Validate_Where_Logic_Started())
        {
            $this->Equipments->LimitBy($this->Equipments->Get_Column('company_id')->
            Equals($this->toolbelt->objects->Get_Company()->Get_Verified_ID()),true);
        }
        return $this->Equipments;
    }
    public function Get_Addresses() : \DatabaseLink\Table
    {
        if(!$this->Addresses->Validate_Where_Logic_Started())
        {
            $this->Addresses->LimitBy($this->Addresses->Get_Column('company_id')->
            Equals($this->toolbelt->objects->Get_Company()->Get_Verified_ID()),true);
        }
        return $this->Addresses;
    }
    public function Get_Customer_Has_Addresses() : \DatabaseLink\Table
    {
        if(!$this->Customer_Has_Addresses->Validate_Where_Logic_Started())
        {
            $this->Customer_Has_Addresses->InnerJoinWith($this->Addresses->
            Get_Column('id'),$this->Customer_Has_Addresses->Get_Column('address_id'),true);
            $this->Customer_Has_Addresses->LimitBy(
            $this->Customer_Has_Addresses->Get_Column('customer_id')->
            Equals($this->toolbelt->objects->Get_Customer(3)->Get_Verified_ID()),true);
        }
        return $this->Customer_Has_Addresses;
    }
    public function Get_Phone_Numbers() : \DatabaseLink\Table
    {
        if(!$this->Phone_Numbers->Validate_Where_Logic_Started())
        {
            $this->Phone_Numbers->LimitBy($this->Phone_Numbers->Get_Column('company_id')->
            Equals($this->toolbelt->objects->Get_Company()->Get_Verified_ID()),true);
        }
        return $this->Phone_Numbers;
    }
    public function Get_Customer_Has_Phone_Numbers() : \DatabaseLink\Table
    {
        if(!$this->Customer_Has_Phone_Numbers->Validate_Where_Logic_Started())
        {
            $this->Customer_Has_Phone_Numbers->InnerJoinWith($this->Phone_Numbers->
            Get_Column('id'),$this->Customer_Has_Phone_Numbers->Get_Column('phone_number_id'),true);
            $this->Customer_Has_Phone_Numbers->LimitBy(
            $this->Customer_Has_Phone_Numbers->Get_Column('customer_id')->
            Equals($this->toolbelt->objects->Get_Customer(3)->Get_Verified_ID()),true);
        }
        return $this->Customer_Has_Phone_Numbers;
    }
    public function Get_Tags(bool $include_global_tags_in_context = true): \DatabaseLink\Table
    {
        if(!$this->Tags->Validate_Where_Logic_Started())
        {
            if($include_global_tags_in_context)
            {
                $this->Tags->LimitByGroup
                (
                    $this->Tags->Get_Column('company_id')->
                    Equals($this->toolbelt->objects->Get_Company()->Get_Verified_ID()),true
                );
                $this->Tags->OrLimitByEndGroup($this->Tags->Get_Column('company_id')->Equals(null),true);
            }else
            {
                $this->Tags->LimitBy($this->Tags->Get_Column('company_id')->Equals($this->toolbelt->objects->Get_Company()->Get_Verified_ID()),true);
            }
        }
        return $this->Tags;
    }
    public function Get_Object_Tags(\Active_Record\Active_Record $object_table,bool $include_global_tags_in_context = true) : \DatabaseLink\Table
    {
        $unique_table_name = 'tags'.$object_table->Get_Table_Name();
        if(!property_exists($this,$unique_table_name))
        {
            $this->$unique_table_name = new \DatabaseLink\Table('Tags',$this->dblink);
        }
        if(!$this->$unique_table_name->Validate_Where_Logic_Started())
        {
            if($include_global_tags_in_context)
            {
                $this->$unique_table_name->LimitByGroup
                (
                    $this->$unique_table_name->Get_Column('company_id')->
                    Equals($this->toolbelt->objects->Get_Company()->Get_Verified_ID()),true
                );
                $this->$unique_table_name->OrLimitByEndGroup($this->$unique_table_name->Get_Column('company_id')->Equals(null),true);
                $this->$unique_table_name->AndLimitBy
                (
                    $this->$unique_table_name->Get_Column('object_table_name')->
                Equals($object_table->Get_Table_Name()),true
                );
            }else
            {
                $this->$unique_table_name->LimitBy($this->$unique_table_name->Get_Column('company_id')->Equals($this->toolbelt->objects->Get_Company()->Get_Verified_ID()),true);
                $this->$unique_table_name->AndLimitBy
                (
                    $this->$unique_table_name->Get_Column('object_table_name')->
                Equals($object_table->Get_Table_Name()),true
                );
            }
        }
        return $this->$unique_table_name;
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     * @param Active_Record $active_record_to_limit_by only required on the first call to the function
     */
    public function Get_Object_Has_Tags(?Active_Record $active_record_to_limit_by = null) : \DatabaseLink\Table
    {
        $unique_table_name = $active_record_to_limit_by->Get_Verified_ID().$active_record_to_limit_by->Get_Table_Name();
        if(empty($this->$unique_table_name)){$this->$unique_table_name = $this->Object_Has_Tags;}
        if(!$this->$unique_table_name->Validate_Where_Logic_Started())
        {
            $this->$unique_table_name->InnerJoinWith
            (
                $this->Tags->Get_Column('id'),$this->$unique_table_name->Get_Column('tag_id'),true
            );
            $this->$unique_table_name->LimitByGroup
            (
                $this->Tags->Get_Column('company_id')->
                Equals($this->toolbelt->objects->Get_Company()->Get_Verified_ID()),true
            );
            $this->$unique_table_name->OrLimitByEndGroup($this->Tags->Get_Column('company_id')->Equals(null),true);
            $this->$unique_table_name->AndLimitBy
            (
                $this->$unique_table_name->Get_Column('object_id')->Equals($active_record_to_limit_by->Get_Verified_ID()),true
            );
        }
        return $this->$unique_table_name;
    }
    public function Get_Tags_Have_Roles(Tag $tag_to_check) : \DatabaseLink\Table
    {
        if(!$this->Tags_Have_Roles->Validate_Where_Logic_Started())
        {
            $this->Tags_Have_Roles->InnerJoinWith($this->Tags->
                Get_Column('id'),$this->Tags_Have_Roles->Get_Column('tag_id'),true
            );
            $this->Tags_Have_Roles->InnerJoinWith($this->Company_Roles->
                Get_Column('id'),$this->Tags_Have_Roles->Get_Column('role_id'),true
            );
            $this->Tags_Have_Roles->LimitBy(
                $this->Tags->Get_Column('company_id')->
                Equals($this->toolbelt->objects->Get_Company()->Get_Verified_ID()),true
            );
            $this->Tags_Have_Roles->AndLimitBy(
                $this->Company_Roles->Get_Column('company_id')->
                Equals($this->toolbelt->objects->Get_Company()->Get_Verified_ID()),true
            );
            $this->Tags_Have_Roles->AndLimitBy(
                $this->Tags_Have_Roles->Get_Column('tag_id')->
                Equals($tag_to_check->Get_Verified_ID()),true
            );
        }
        return $this->Tags_Have_Roles;
    }

}
class Objects
{
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
    /**
     * @param int $object_type 1 = Delete_Tag,2 = Create_Tag, 3 = Update_Tag
     */
    public function Get_Tag(int $object_type,bool $send_response = true) : \app\Helpers\Tag
    {
        global $toolbelt_base;
        return $toolbelt_base->Get_Tag($object_type,$send_response);
    }
    /**
     * @param int $object_type 1 = Delete_Role_From_Tag, 2 = Add_Role_To_Tag
     */
    public function Get_Tags_Have_Role(int $object_type,bool $send_response = true) : \app\Helpers\Tags_Have_Role
    {
        global $toolbelt_base;
        return $toolbelt_base->Get_Tags_Have_Role($object_type,$send_response);
    }
    /**
     * @param int $object_type 1 = Remove_Tag_From_Object, 2 = Add_Tag_To_Object
     */
    public function Get_Add_Tag(int $object_type,bool $send_response = true,?Active_Record $active_record_to_add_tag_to = null) : \app\Helpers\Tag
    {
        global $toolbelt_base;
        return $toolbelt_base->Get_Add_Tag($object_type,$send_response,$active_record_to_add_tag_to);
    }

}
class Test_Cases
{
    private Program $program;
    private Company $company;
    private Company_Role $company_role;
    private User $user;
    private string $user_password;
    private Program_Session $program_session;
    private Company_Config $company_config;
    private Config $config;
    private Right $right;
    private Route $route;
    private Route_Role $route_role;
    private User_Role $user_role;
    //private People $people;
    private Employee $employee;
    //private Employee_Company $employee_company;
    private Customer $customer;
    //private Address $address;
    //private Customer_Address $customer_address; need to ensure all functions are accessible through customer class
    //private Phone_Number $phone_number;
    //private Customer_Phone_Number $customer_phone_number; need to ensure all functions are accessible through customer class
    private Credit_Status $credit_status;
    private Equipment $equipment;
    private Object_Has_Tag $object_has_tag;
    private Tag $tag;
    //private Tags_Have_Role $tags_have_roles;
    private Toolbelt $toolbelt;

    function __construct()
    {
        $this->toolbelt = new Toolbelt;
        $this->user_password = $this->toolbelt->functions->Generate_CSPRNG(14);
    }

    function Get_A_Object(Active_Record $object,string $function_name) : Active_Record
    {
        $object_string = get_class($object);
        if(empty($this->$object_string))
        {
            $this->$object_string = call_user_func([$this,$function_name]);
        }
        return $this->$object_string;
    }
    function Create_New_Program() : Program
    {
        $program = new Program;
        $program->Set_Program_Name(Str::ucfirst($this->toolbelt->functions->Readable_Random_String(12)));
        return $program;
    }
    function Store_Created_Program(Program $program) : void
    {
        $program->Get_Verified_ID();
        $this->program = $program;
    }
    function Get_A_Program() : Program
    {
        return $this->Get_A_Object(new Program,'Create_New_Program');
    }
    function Create_New_Company() : Company
    {
        $company = new Company;
        $company->Set_Company_Name(Str::ucfirst($this->toolbelt->functions->Readable_Random_String()).' '.Str::ucfirst($this->toolbelt->functions->Readable_Random_String()));
        return $company;
    }
    function Store_Created_Company(Company $company) : void
    {
        $company->Get_Verified_ID();
        $this->company = $company;
    }
    function Get_A_Company() : Company
    {
        return $this->Get_A_Object(new Company,'Create_New_Company');
    }
    function Create_New_Company_Role() : Company_Role
    {
        $company = $this->Get_A_Company();
        $company_role = new Company_Role;
        $company_role->Set_Company_ID($company->Get_Verified_ID(),false);
        $company_role->Set_Role_Name(Str::ucfirst($this->toolbelt->functions->Readable_Random_String(6)));
        return $company_role;
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    function Store_Created_Company_Role(Company_Role $company_role) : void
    {
        $company_role->Get_Verified_ID();
        $this->company_role = $company_role;
    }
    function Get_A_Company_Role() : Company_Role
    {
        return $this->Get_A_Object(new Company_Role,'Create_New_Company_Role');
    }
    function Create_New_User() : User
    {
        return new User(Str::ucfirst($this->toolbelt->functions->Readable_Random_String(8)),$this->user_password,$this->Get_A_Company(),true);
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    function Store_Created_User(User $user) : void
    {
        $user->Get_Verified_ID();
        $this->user = $user;
    }
    function Get_A_User() : User
    {
        if(empty($this->user))
        {
            $this->user = $this->Create_New_User();
        }
        return $this->user;
    }
    function Create_New_Program_Session(bool $only_if_user_is_active) : Program_Session
    {
        $program_session = new Program_Session;
        $program_session->Create_New_Session($this->Get_A_Program()->Get_Client_ID(),$this->Get_A_Company(),$this->Get_A_User()->Get_Username(),$this->user_password,$only_if_user_is_active);
        return $program_session;
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    function Store_Created_Program_Session(Program_Session $program_session) : void
    {
        $program_session->Get_Verified_ID();
        $this->program_session = $program_session;
    }
    function Get_A_Program_Session(bool $only_if_user_is_active) : Program_Session
    {
        if(empty($this->program_session))
        {
            $this->program_session = $this->Create_New_Program_Session($only_if_user_is_active);
        }
        return $this->program_session;
    }
    function Create_New_Config() : Config
    {
        $config = new Config;
        $config->Create_Or_Update_Config(Str::ucfirst($this->toolbelt->functions->Readable_Random_String(7)),rand(0,1));
        return $config;
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    function Store_Created_Config(Config $config) : void
    {
        $config->Get_Verified_ID();
        $this->config = $config;
    }
    function Get_A_Config() : Config
    {
        return $this->Get_A_Object(new Config,'Create_New_Config');
    }
    function Create_New_Company_Config() : Company_Config
    {
        $config = $this->Get_A_Config();
        $company = $this->Get_A_Company();
        $company_config = new Company_Config;
        $company_config->Create_Or_Update_Config($config,$company,Str::ucfirst($this->toolbelt->functions->Readable_Random_String(4)));
        return $company_config;
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    function Store_Company_Config(Config $config) : void
    {
        $config->Get_Verified_ID();
        $this->config = $config;
    }
    function Get_A_Company_Config() : Company_Config
    {
        return $this->Get_A_Object(new Company_Config,'Create_New_Company_Config');
    }
    function Build_Full_Scenario() : Void
    {
        $this->Build_Basic_Scenario();
    }
    function Build_Basic_Scenario() : Void
    {
        $this->Get_A_Company();
        $this->Get_A_Company_Role();
        $this->Get_A_Company_Config();
    }
}
class Functions
{
    /**
     * @throws Exception if you use a different keyspace it has to be more than two characters long
     */
    function Generate_CSPRNG(int $length,string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ+=.?$') : string
    {
        $str = '';
        $max = mb_strlen($keyspace, '8bit') - 1;
        if ($max < 1) {
            throw new \Exception('$keyspace must be at least two characters long');
        }
        for ($i = 0; $i < $length; ++$i) {
            $str .= $keyspace[random_int(0, $max)];
        }
        return $str;
    }
    /**
     * @param string $string_to_add example "<-"
     * @param array $array_to_modify array('socks','shoes','pants')
     * @return array array('<-socks<-','<-shoes<-','<-pants<-')
     */
    function Wrap_Array_Values_With_String(string $string_to_add,array $array_to_modify) : array
    {
        $array = $this->Append_To_Array_Values_With_String($string_to_add,$array_to_modify);
        $array = $this->Prepend_To_Array_Values_With_String($string_to_add,$array);
        return $array;
    }
    /**
     * @param string $string_to_add example "<-"
     * @param array $array_to_modify array('socks','shoes','pants')
     * @return array array('socks<-','shoes<-','pants<-')
     */
    function Append_To_Array_Values_With_String(string $string_to_add,array $array_to_modify) : array
    {
        ForEach($array_to_modify as $key => $value)
        {
            $array_to_modify[$key] = $value.$string_to_add;
        }
        return $array_to_modify;
    }
    /**
     * @param string $string_to_add example "<-"
     * @param array $array_to_modify array('socks','shoes','pants')
     * @return array array('<-socks','<-shoes','<-pants')
     */
    function Prepend_To_Array_Values_With_String(string $string_to_add,array $array_to_modify) : array
    {
        ForEach($array_to_modify as $key => $value)
        {
            $array_to_modify[$key] = $string_to_add.$value;
        }
        return $array_to_modify;
    }
    /**
     * @param string $string_to_add example "<-"
     * @param array $array_to_modify array('socks','shoes','pants')
     * @return array array('socks<-','shoes<-','pants')
     */
    function Prepend_To_Array_Except_Last_Element_With_String(string $string_to_add,array $array_to_modify) : array
    {
        $i = 1;
        ForEach($array_to_modify as $key => $value)
        {
            if($i < count($array_to_modify))
            {
                $array_to_modify[$key] = $string_to_add.$value;
            }
            $i = $i + 1;
        }
        return $array_to_modify;
    }
    /**
     * @param string $url - fsockopen($url,80)
     */
    function is_connected($url = "www.google.com") : bool
    {
        $connected = @fsockopen($url, 80);
                                            //website, port  (try 80 or 443)
        if ($connected){
            $is_conn = true; //action when connected
            fclose($connected);
        }else{
            $is_conn = false; //action in connection failure
        }
        return $is_conn;
    }
    function Array_To_Ini(array $array) : string
    {
        $res = array();
        foreach($array as $key => $val)
        {
            if(is_array($val))
            {
                $res[] = "[$key]";
                foreach($val as $skey => $sval) $res[] = "$skey = ".(is_numeric($sval) ? $sval : '"'.$sval.'"');
            }
            else $res[] = "$key = ".(is_numeric($val) ? $val : '"'.$val.'"');
        }
        return implode("\r\n", $res);
    }
    function Send_Message_To_Stdin_Get_Response(string $message) :string
    {
        echo $message;
        $handle = fopen("php://stdin","r"); // read from STDIN
        $line = trim(fgets($handle));
        return $line;
    }
    function Ask_User_For_Credentials() : void
    {
        $root_username = $this->Send_Message_To_Stdin_Get_Response("Database root username?");
        $root_password = $this->Send_Message_To_Stdin_Get_Response("Database root password?");
        $root_hostname = $this->Send_Message_To_Stdin_Get_Response("Database hostname, leave blank for localhost?");
        if ($root_hostname == "")
        {
            $root_hostname = 'localhost';
        }
        $root_listeningport = $this->Send_Message_To_Stdin_Get_Response("Database listeningport, leave blank for 3306?");
        if($root_listeningport == "")
        {
            $root_listeningport = '3306';
        }
        if(mysqli_connect($root_hostname,$root_username,$root_password,'',$root_listeningport))
        {
            $this->Create_Config_File($root_username,$root_password,$root_hostname,$root_listeningport);
        }else
        {
            $connection_failed_try_again = $this->Send_Message_To_Stdin_Get_Response("I tried connecting to the database but failed, would you like to try again?");
            if(strtoupper($connection_failed_try_again) == 'Y' || strtoupper($connection_failed_try_again) == 'Y')
            {
                $this->Ask_User_For_Credentials();
            }else
            {
                return;
            }
        }
    }
    function Create_Config_File(string $root_username,string $root_password,string $root_hostname,string $root_listeningport) : void
    {
        echo 'creating config file and terminating execution';
        $array = array('root_username' => $root_username,'root_password' => $root_password,'root_hostname' => $root_hostname,'root_listeningport' => $root_listeningport);
        $ini_string = $this->Array_To_Ini($array);
        $file_handle = fopen(dirname(__FILE__).DIRECTORY_SEPARATOR.'config.local.ini','w');
        fwrite($file_handle,$ini_string);
        fclose($file_handle);
    }
    function Validate_Array_Types(array $array,string $objecttype) :void
    {
        ForEach($array as $object)
        {
            try
            {
                if(get_class($object) == $objecttype)
                {
                    throw new \Exception(get_class($object).' is not a valid '.$objecttype);
                }
            } catch (\Exception $e)
            {
                throw new \Exception(get_class($object).' is not a valid '.$objecttype);
            }
        }
    }

    function stringEndsWith($haystack,$needle) {
        $expectedPosition = strlen($haystack) - strlen($needle);
        return strrpos($haystack, $needle, 0) === $expectedPosition;
    }

    function Enable_Disabled_Object(\DatabaseLink\Column $column,\Active_Record\Active_Record $object) : void
    {
        $toolbelt = new \Test_Tools\toolbelt;
        if($toolbelt->objects->Get_Route()->Get_Current_Route_Method() == "patch")
        {
            app()->request->validate(['id' => new Does_This_Exist_In_Context($column,true)]);
            $object->Load_Object_By_ID($column->Get_Field_Value(),true);
            $object->Set_Object_Active(true);
        }
    }

    function Validate_Email(string $email,bool $send_response = true) : void
    {
        $pattern = '/^(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){255,})(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){65,}@)(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22))(?:\\.(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-+[a-z0-9]+)*\\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-+[a-z0-9]+)*)|(?:\\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\\]))$/iD';
        if (preg_match($pattern, $email) === 0) {
            if($send_response)
            {
                $this->Response_422(['message' => 'Sorry '.$email.' is not a valid email address'],app()->request)->send();
                exit();
            }else
            {
                throw new Email_Address_Not_Valid('Sorry '.$email.' is not a valid email address');
            }
        }

    }

    function Readable_Random_String($length = 6)
    {
        $string     = '';
        $vowels     = array("a","e","i","o","u");
        $consonants = array(
            'b', 'c', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'm',
            'n', 'p', 'r', 's', 't', 'v', 'w', 'x', 'y', 'z'
        );

        // Seed it
        srand((double) microtime() * 1000000);

        $max = $length/2;
        for ($i = 1; $i <= $max; $i++)
        {
            $string .= $consonants[rand(0,19)];
            $string .= $vowels[rand(0,4)];
        }

        return $string;
    }


    /**
     * Get Success
     */
    function Response_200(array $payload)
    {
        global $toolbelt_base;
        $toolbelt_base->Null_All();
        return response()->json($payload);
    }
    /**
     * Post/Patch/Put Success
    */
    function Response_201(array $payload)
    {
        global $toolbelt_base;
        $toolbelt_base->Null_All();
        return response()->json($payload,201);
    }
    /**
     * What you are asking for I just can't do for you
    */
    function Response_422(array $payload)
    {
        global $toolbelt_base;
        $toolbelt_base->Null_All();
        return response()->json($payload,422);
    }
    /**
     * I can't understand your request
    */
    function Response_400(array $payload)
    {
        global $toolbelt_base;
        $toolbelt_base->Null_All();
        return response()->json($payload,400);
    }
    /**
     * You are not allowed to do this either for authentication or authorization issues
    */
    function Response_401(array $payload)
    {
        global $toolbelt_base;
        $toolbelt_base->Null_All();
        return response()->json($payload,401);
    }
    /**
     * My Bad Sorry I need to fix this
    */
    function Response_500(array $payload)
    {
        global $toolbelt_base;
        $toolbelt_base->Null_All();
        return response()->json($payload,500);
    }

    function Get_Project_Folder_Name() : string
    {
        $tmp_folder = '';
        $tmp_folder = explode('/',dirname(__FILE__));
        /**
         * @var string $probject_folder_name name of the parent folder the project is installed in
         * The folder structure would be project_folder/vendor/jbirch8865/php_tools/StartupVariables.php
         */
        return $tmp_folder[count($tmp_folder) - 4];
    }
}
?>
