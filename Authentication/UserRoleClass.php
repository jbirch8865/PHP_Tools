<?php declare(strict_types=1);
namespace Authentication;

use Authentication\iUser;
use Company\Company_Role;
use Active_Record\Active_Record;
use Company\Company;

class User_Role extends Active_Record
{
    public $_table = "Users_Have_Roles";

    function __construct()
    {
        parent::__construct();
        global $toolbelt_base;
        $toolbelt_base->active_record_relationship_manager->Load_Table_Belongs_To_If_Empty($this->table_dblink,$this->table_dblink->Get_Column('role_id'),$toolbelt_base->Company_Roles,$toolbelt_base->Company_Roles->Get_Column('id'),'\Company\Company_Role');
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Get_Role_ID() : int 
    {
        return (int) $this->Get_Value_From_Name('role_id');
    }
     /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Get_User_ID() : int
    {
        return (int) $this->Get_Value_From_Name('user_id');
    }
    /**
     * @throws UpdateFailed — — if adodb->save method fails
     * @throws \Active_Record\Object_Has_Not_Been_Loaded for company_role
     */
    public function Set_Role(Company_Role $company_role,bool $update_immediately = true) : void
    {
        $role_id = $company_role->Get_Verified_ID();
        $this->Set_Int($this->table_dblink->Get_Column('role_id'),$role_id,$update_immediately);
    }
    /**
     * @throws UpdateFailed — — if adodb->save method fails
     * @throws \Active_Record\Object_Has_Not_Been_Loaded for user
     */
    public function Set_User(iUser $user,bool $update_immediately = true) : void
    {
        $user_id = $user->Get_Verified_ID();
        $this->Set_Int($this->table_dblink->Get_Column('user_id'),$user_id,$update_immediately);
    }
    /**
     * @throws Active_Record_Object_Failed_To_Load
     * @throws Object_Is_Already_Loaded
     */
    public function Load_User_Role_From_ID(int $user_role_id) : void
    {
        $this->Load_From_Int('id',$user_role_id);
    }
    /**
     * @throws Active_Record_Object_Failed_To_Load
     * @throws Object_Is_Already_Loaded
     * @throws \Active_Record\Object_Has_Not_Been_Loaded for user and company_role
     */
    public function Load_User_Role_From_User_And_Company_Role(iUser $user,Company_Role $company_role):void
    {
        $this->Load_From_Multiple_Vars([['user_id',$user->Get_Verified_ID()],['role_id',$company_role->Get_Verified_ID()]]);
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Delete_User_Role() : void
    {
        $this->Delete_Object('destroy');
    }
}

?>