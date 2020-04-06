<?php declare(strict_types=1);
namespace Authentication;

use Active_Record\Active_Record;
use Active_Record\Object_Is_Currently_Inactive;

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
     * @param bool $only_if_active if $create_user is true this will be ignored, if we are loading / authorizing credentials this will load even if user is inactive
     * @throws Object_Is_Currently_Inactive
     */
    function __construct(string $unverified_username,string $unverified_password,int $company_id,bool $create_user = false,bool $only_if_active = true)
    {
        parent::__construct();
        global $toolbelt_base;
        $toolbelt_base->active_record_relationship_manager->Load_Table_Belongs_To_If_Empty('User_Belongs_To_Company',$this->table_dblink,$this->table_dblink->Get_Column('company_id'),$toolbelt_base->Companies,$toolbelt_base->Companies->Get_Column('id'),'\Company\Company');
        $this->cConfigs = $toolbelt_base->cConfigs;
        $this->table_dblink = new \DatabaseLink\Table($this->_table,$toolbelt_base->dblink);
        $this->company_id = $company_id;
        $this->username = $unverified_username;
        $this->password = $unverified_password;
        $this->project_name = $this->cConfigs->Get_Name_Of_Project();
        if($this->Load_User_If_Exists())
        {
            if($only_if_active)
            {
                if(!$this->Is_Object_Active())
                {
                    throw new Object_Is_Currently_Inactive($this->Get_Username().' is currently inactive.');
                }
            }
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

    public function Delete_User()
    {
        $this->Delete_Object('destroy');
    }
}
?>