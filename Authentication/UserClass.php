<?php declare(strict_types=1);
namespace Authentication;

use Active_Record_Object;
use ADODB_Active_Record;
class User extends ADODB_Active_Record implements Active_Record_Object
{
    private \config\ConfigurationFile $cConfigs;
    public \DatabaseLink\Table $table_dblink;
    private $table_name = "Users";
    var $_table = "Users";

    function __construct(string $unverified_username,string $unverified_password,int $company_id,bool $create_user = false)
    {
        parent::__construct();
        global $cConfigs;
        $this->cConfigs = $cConfigs;
        global $dblink;
        $this->table_dblink = new \DatabaseLink\Table($this->table_name,$dblink);
        $this->company_id = $company_id;
        $this->username = $unverified_username;
        $this->password = $unverified_password;
        $this->project_name = $this->cConfigs->Get_Name_Of_Project();
        if($this->Load_User_If_Exists())
        {
            $this->Check_Password();
        }else
        {
            if($create_user)
            {
                $this->Create_Object();
            }else
            {
                throw new User_Does_Not_Exist($unverified_username." does not exist");
            }
        }
    }
    private function Load_User_If_Exists() : bool
    {
        return $this->Load('username=? AND company_id=? AND project_name=?',array($this->username,$this->company_id,$this->project_name));
    }
    public function Check_Password() : void
    {
        if(!$this->Is_Password_Correct($this->password))
        {
            throw new Incorrect_Password($this->password." is not correct.");
        }
    }
    private function Is_Password_Correct(string $unverified_password) : bool
    {
        if($this->verified_hashed_password == $this->Hash_Password($unverified_password))
        {
            return true;
        }else
        {
            return false;
        }
    }
    private function Hash_Password(string $password) : string
    {
        $string_to_hash = $password.$this->cspring;
        return hash('sha256',$string_to_hash);
    }
    public function Create_Object() : void
    {
        $this->cspring = Generate_CSPRNG(64);
        $this->verified_hashed_password = $this->Hash_Password($this->password);
        $this->Set_Username($this->username,true,false);
        $this->password = $this->verified_hashed_password;
        $this->cspring = $this->cspring;
        $this->company_id = $this->company_id;
        $this->project_name = $this->cConfigs->Get_Name_Of_Project();
        $this->active_status = 1;
        $this->Update_Object();
    }
    public function Get_Username() : string
    {
        return $this->username;
    }
    public function Set_Username(string $username,bool $trim_if_too_long = true,bool $update_immediately = true) : void
    {
        if(strlen($username) > $this->table_dblink->Get_Column('username')->Get_Data_Length())
        {
            if($trim_if_too_long)
            {
                $username = substr($username,0,$this->table_dblink->Get_Column('username')->Get_Data_Length());
            }else
            {
                throw new \Exception($username." is too long of a username");
            }
        }

        $this->username = $username;
        if($update_immediately)
        {
            $this->Update_Object();
        }
    }
    public function Set_Object_Active() : void
    {
        $this->active_status = 1;
    }
    public function Set_Object_Inactive() : void
    {
        $this->active_status = 0;
    }
    public function Is_Object_Active() : bool
    {  
        return $this->Get_Active_Status();
    }
    private function Get_Active_Status() : bool
    {
        return $this->active_status;
    }

    public function Update_Object() : void
    {
        if(!$this->save())
        {
            throw new \Active_Record\UpdateFailed($this->Get_Username().' failed to create or update with error '.$this->ErrorMsg());
        }
    }

    public function Delete_Object(string $password) : void
    {
        if($password != "destroy")
        {
            throw new \Exception("destroy password not set.");
        }
        $this->Delete();
    }


}

class Current_User_Session
{
    public User_Session $session;

    function __construct()
    {
        if($this->Does_User_Session_Exist())
        {
            $this->session = $_SESSION['User_Session'];
            $this->session->Reestablish_Serialized_Components();
            if(!$this->session->Am_I_Currently_Authenticated(false))
            {
                throw new User_Session_Expired('Sorry the users session has expired. Need to establish a new User_Session($User).');
            }
        }else
        {
            throw new User_Not_Logged_In('Sorry there is no user currently logged in. Need to establish a new User_Session($User)');
        }
    }

    private function Does_User_Session_Exist() : bool
    {
        if(isset($_SESSION['User_Session']))
        {
            return true;
        }else
        {
            return false;
        }
    }


}

class User_Session
{
    private \Company\Company $Company;
    private \DateTime $session_expires;
    private \Authentication\User $user;
    function __construct(\Authentication\User $user)
    {
        $this->user = $user;
        $this->Establish_Serialized_Components();
        $this->session_expires = new \DateTime(date('Y-m-d H:i:s',strtotime("+".$this->Company->Get_Session_Time_Limit()." minutes")),$this->Company->Get_Time_Zone());
        if($this->Am_I_Currently_Authenticated(false))
        {
            $_SESSION['User_Session'] = $this;
        }else
        {
            throw new User_Session_Expired('Sorry the user session has expired.  Need to establish a new User_Session($user).');
        }
    }
    public function Am_I_Currently_Authenticated($auto_renew = true) : bool
    {
        $this->user->Check_Password();
        if($auto_renew)
        {
            $this->session_expires = new \DateTime(date('Y-m-d H:i:s',strtotime("+".$this->Company->Get_Session_Time_Limit()." minutes")),$this->Company->Get_Time_Zone());
        }
        if(new \DateTime('now',$this->Company->Get_Time_Zone()) > $this->session_expires)
        {
            return false;
        }else
        {
            return true;
        }
    }

    function Exit_If_Not_Currently_Authenticated($message = "") : void
    {
        if(!$this->Am_I_Currently_Authenticated())
        {
            exit($message);
        }
    }
    /**
     * This will unset all properties of this object and unset the session variable
     */
    function LogOut() : void
    {
        unset($_SESSION['User_Session']);
        ForEach($this as $property => $value)
        {
            unset($this->$property);
        }
    }

    function Get_User_Name() : string
    {
        return $this->user->Get_Username();
    }
    function Set_User_Name(string $username) : void
    {
        $this->user->Set_Username($username);
    }
    private function Establish_Serialized_Components() : void
    {
        $this->Company = new \Company\Company($_SESSION['company_id']);
    }
    public function Reestablish_Serialized_Components() : void
    {
        $this->Establish_Serialized_Components();
    }
}
?>