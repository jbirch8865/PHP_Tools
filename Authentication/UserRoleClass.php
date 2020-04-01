<?php declare(strict_types=1);
namespace Authentication;

use Active_Record\Active_Record;
class User_Role extends Active_Record
{
    public $_table = "Users_Have_Roles";

    function __construct()
    {
        parent::__construct();
    }
    /**
     * @throws \Active_Record\Active_Record_Object_Failed_To_Load if role id is not valid
     * @throws Object_Is_Already_Loaded
     */
    public function Get_Role_Name() : string
    {
        $role_id = $this->Get_Role_ID();
        $role = new \Company\Company_Role;
        $role->Load_Role_By_ID($role_id);
        return $this->Get_Value_From_Name('role_name');
    }
    public function Get_Role_ID() : int
    {
        return (int) $this->Get_Value_From_Name('role_id');
    }
    /**
     * @throws UpdateFailed — — if the object isn't ready to update
     * @throws Active_Record_Object_Failed_To_Load — If role doesn't exist
     */
    public function Set_Role_By_Name(string $role_name,bool $update_immediately = true) : void
    {
        $role_id = new \Company\Company_Role;
        $role_id->Load_Role_By_Name($role_name);
        $role_id = $role_id->Get_Verified_ID();
        $this->Set_Int('role_id',$role_id,$update_immediately);
    }
    /**
     * @throws UpdateFailed — — if adodb->save method fails
     */
    public function Set_Role_By_ID(int $role_id,bool $update_immediately = true) : void
    {
        $this->Set_Int('role_id',$role_id,$update_immediately);
    }
    /**
     * @throws UpdateFailed — — if adodb->save method fails
     */
    public function Set_User_By_ID(int $user_id,bool $update_immediately = true) : void
    {
        $this->Set_Int('user_id',$user_id,$update_immediately);
    }

}

?>