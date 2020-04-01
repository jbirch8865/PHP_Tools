<?php declare(strict_types=1);
namespace Company;

use Active_Record\Active_Record;
class Company_Role extends Active_Record
{
    public $_table = "Company_Roles";

    function __construct()
    {
        parent::__construct();
    }
    public function Get_Role_Name() : string
    {
        return $this->Get_Value_From_Name('role_name');
    }
    public function Get_Company_ID() : int
    {
        return (int) $this->Get_Value_From_Name('company_id');
    }
    /**
     * @throws UpdateFailed — — if adodb->save method fails
     */
    public function Set_Role_Name(string $role_name,bool $update_immediately = true) : void
    {
        $this->Set_Varchar('role_name',$role_name,true,$update_immediately);
    }
    /**
     * @throws UpdateFailed — — if adodb->save method fails
     */
    public function Set_Company_ID(int $company_id,bool $update_immediately = true) : void
    {
        $this->Set_Int('company_id',$company_id,$update_immediately);
    }
    /**
     * @throws \Active_Record\Active_Record_Object_Failed_To_Load if adodb->load method fails
     * @throws Object_Is_Already_Loaded
     */
    public function Load_Role_By_ID(int $role_id) : void
    {
        $this->Load_From_Int('id',$role_id);
    }
    /**
     * @throws Object_Is_Already_Loaded
     * @throws Active_Record_Object_Failed_To_Load — if adodb->load method fails
     */
    public function Load_Role_By_Name(string $role_name) : void
    {
        $this->Load_From_Varchar('role_name',$role_name);
    }
}

?>