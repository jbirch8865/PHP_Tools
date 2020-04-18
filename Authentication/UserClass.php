<?php declare(strict_types=1);
namespace Authentication;

use Active_Record\Active_Record;
use Active_Record\Object_Is_Currently_Inactive;

class User extends Active_Record implements iUser
{
    public \config\ConfigurationFile $cConfigs;
    public \DatabaseLink\Table $table_dblink;
    private string $password;
    var $_table = "Users";

    /**
     * @throws Incorrect_Password
     * @throws User_Does_Not_Exist
     * @throws UpdateFailed
     * @throws Varchar_Too_Long_To_Set if creating a user and the password is too long
     * @param bool $only_if_active if $create_user is true this will be ignored, if we are loading / authorizing credentials this will load even if user is inactive
     * @throws Object_Is_Currently_Inactive
     * @param string $unverified_password to skip checking the password use 'skip_check' as the password
     */
    function __construct(string $unverified_username,string $unverified_password,\Company\Company $company,bool $create_user = false,bool $only_if_active = true)
    {
        parent::__construct();
        global $toolbelt_base;
        $toolbelt_base->active_record_relationship_manager->Load_Table_Belongs_To_If_Empty($this->table_dblink,$this->table_dblink->Get_Column('company_id'),$toolbelt_base->Companies,$toolbelt_base->Companies->Get_Column('id'),'\Company\Company');
        $this->cConfigs = $toolbelt_base->cConfigs;
        $this->table_dblink = new \DatabaseLink\Table($this->_table,$toolbelt_base->dblink);
        $this->company_id = $company->Get_Verified_ID();
        $this->Set_Username($unverified_username);
        $this->password = $unverified_password;
        $this->project_name = $this->cConfigs->Get_Name_Of_Project();
        if($this->Load_User_If_Exists())
        {
            $this->password = $this->Hash_Password($this->password);
            if($only_if_active)
            {
                if(!$this->Is_Object_Active())
                {
                    throw new Object_Is_Currently_Inactive($this->Get_Username().' is currently inactive.');
                }
            }
            if($unverified_password != 'skip_check')
            {
                $this->Check_Password();
            }
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
    private function Check_Password() : void
    {
        if(!$this->Is_Password_Correct())
        {
            throw new Incorrect_Password($this->password." is not correct.");
        }
    }
    private function Is_Password_Correct() : bool
    {
        if($this->verified_hashed_password == $this->password)
        {
            return true;
        }else
        {
            return false;
        }
    }
    protected function Hash_Password(string $password) : string
    {
        $string_to_hash = $password.$this->cspring;
        return hash('sha256',$string_to_hash);
    }
    /**
     * @throws UpdateFailed
     * @throws Varchar_Too_Long_To_Set
     */
    protected function Create_Object() : void
    {
        $this->cspring = Generate_CSPRNG(64);
        $this->verified_hashed_password = $this->Hash_Password($this->password);
        parent::Create_Object();
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Get_Username() : string
    {
        return $this->Get_Value_From_Name('username');
    }
    private function Set_Username(string $username) : void
    {
        $this->Set_Varchar($this->table_dblink->Get_Column('username'),$username,true,false);
    }
    /**
     * @param bool $mark_inactive if false will delete record from database
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Delete_User(bool $mark_inactive = true)
    {
        if($mark_inactive)
        {
            $this->Set_Object_Inactive();
        }else
        {
            $this->Delete_Object('destroy');
        }
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded — for company_role
     * @throws \Active_Record\Object_Has_Not_Been_Loaded — for user
     * @throws \Active_Record\UpdateFailed if role already assigned
     */
    public function Assign_Company_Role(\Company\Company_Role $company_role): void
    {
        $user_role = new \Authentication\User_Role;
        $user_role->Set_Role($company_role,false);
        $user_role->Set_User($this,true);
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded — for user and company_role
     * @throws Active_Record_Object_Failed_To_Load
     */
    public function Remove_Company_Role(\Company\Company_Role $company_role): void
    {
        $user_role = new \Authentication\User_Role;
        $user_role->Load_User_Role_From_User_And_Company_Role($this,$company_role);
        $user_role->Delete_User_Role();
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Get_User_ID(): int
    {
        return $this->Get_Verified_ID();
    }
}
?>