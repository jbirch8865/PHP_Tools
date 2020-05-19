<?php declare(strict_types=1);
namespace app\Helpers;

use app\Helpers\iUser;
use app\Helpers\Company_Role;
use Active_Record\Active_Record;
use Active_Record\iActiveRecord;
use app\Helpers\Company;

class User_Role extends Active_Record implements iActiveRecord
{
    public $_table = "Users_Have_Roles";

    function __construct()
    {
        parent::__construct();
        global $toolbelt_base;
        $toolbelt_base->active_record_relationship_manager->Load_Table_Belongs_To_If_Empty($this->table_dblink,$this->table_dblink->Get_Column('role_id'),$toolbelt_base->Company_Roles,$toolbelt_base->Company_Roles->Get_Column('id'),'\app\Helpers\Company_Role',true);
    }
    public function Get_Company_Roles() : Company_Role
    {
        $this->Company_Roles;
        return $this->Company_Roles;
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
    public function Get_Friendly_Name() : ?string
    {
        return null;
    }
    /**
     * @throws Active_Record_Object_Failed_To_Load
     * @throws Object_Is_Already_Loaded
     * Don't use this function as their is no user role friendly name
     */
    public function Load_By_Friendly_Name(string $friendly_name,?\Active_Record\Active_Record $object = null): void
    {
        throw new \Exception('Load_By_Friendly_Name does not work on User Role Class for name '.$friendly_name.'.');
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

    public function Delete_Active_Record() : void
    {
        app()->request->validate([
            'active_status' => ['required','bool']
        ]);
        $this->Delete_User_Role();
    }

}

?>
