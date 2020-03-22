<?php declare(strict_types=1);
namespace Authentication;

use Active_Record\Active_Record;
class User extends Active_Record
{
    public \config\ConfigurationFile $cConfigs;
    public \DatabaseLink\Table $table_dblink;
    private string $password;
    var $_table = "Users";

    /**
     * @throws Incorrect_Password
     * @throws User_Does_Not_Exist
     * @throws UpdateFailed
     * @throws Varchar_Too_Long_To_Set if creating a user and the password or username is too long
     */
    function __construct(string $unverified_username,string $unverified_password,int $company_id,bool $create_user = false)
    {
        parent::__construct();
        global $cConfigs;
        $this->cConfigs = new \config\ConfigurationFile();
        $this->cConfigs = &$cConfigs;
        global $dblink;
        $this->table_dblink = new \DatabaseLink\Table($this->_table,$dblink);
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
    /**
     * @throws UpdateFailed
     * @throws Varchar_Too_Long_To_Set
     */
    public function Create_Object() : void
    {
        $this->cspring = Generate_CSPRNG(64);
        $this->verified_hashed_password = $this->Hash_Password($this->password);
        $this->Set_Username($this->username,false,false);
        $this->cspring = $this->cspring;
        $this->company_id = $this->company_id;
        $this->project_name = $this->cConfigs->Get_Name_Of_Project();
        parent::Create_Object();
    }
    public function Get_Username() : string
    {
        return $this->username;
    }
    /**
     * @throws Varchar_Too_Long_To_Set
     */
    public function Set_Username(string $username,bool $trim_if_too_long = true,bool $update_immediately = true) : void
    {
        $this->Set_Varchar('username',$username,$trim_if_too_long,$update_immediately);
    }
    /**
     * There are no password restrictions here.
     * @throws Varchar_Too_Long_To_Set
     */
    public function Set_Password(string $plain_text_password,bool $trim_if_too_long = true,bool $update_immediately = true) : void
    {
        $password = $this->Hash_Password($plain_text_password);
        $this->Set_Varchar('verified_hashed_password',$password,false,true);
    }
    /**
     * @throws Varchar_Too_Long_To_Set
     */
    public function Update_CSPRING() : void
    {
        $cspring = Generate_CSPRNG(64);
        $this->Set_Varchar('cspring',$cspring,false,true);
    }
}
?>