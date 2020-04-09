<?php declare(strict_types=1);
namespace Company;

use Active_Record\Active_Record;
class Company_Role extends Active_Record
{
    public $_table = "Company_Roles";

    function __construct()
    {
        parent::__construct();
        global $toolbelt_base;
        $toolbelt_base->active_record_relationship_manager->Load_Table_Belongs_To_If_Empty($this->table_dblink,$this->table_dblink->Get_Column('company_id'),$toolbelt_base->Companies,$toolbelt_base->Companies->Get_Column('id'),'\Company\Company');
        $toolbelt_base->active_record_relationship_manager->Load_Table_Has_Many_If_Empty($this->table_dblink,$toolbelt_base->Users_Have_Roles,$toolbelt_base->Users_Have_Roles->Get_Column('role_id'),'\Authentication\User_Role');
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Get_Role_Name() : string
    {
        return $this->Get_Value_From_Name('role_name');
    }
    /**
     * @throws UpdateFailed — — if adodb->save method fails
     */
    public function Set_Role_Name(string $role_name,bool $update_immediately = true) : void
    {
        $this->Set_Varchar($this->table_dblink->Get_Column('role_name'),$role_name,true,$update_immediately);
    }
    /**
     * @throws UpdateFailed — — if adodb->save method fails
     */
    public function Set_Company_ID(int $company_id,bool $update_immediately = true) : void
    {
        $this->Set_Int($this->table_dblink->Get_Column('company_id'),$company_id,$update_immediately);
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
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     * 
     */
    public function Delete_Role(bool $mark_inactive = true) : void
    {
        if($mark_inactive)
        {
            $this->Set_Object_Inactive();
        }else
        {
            $this->Delete_Object('destroy');

        }
    }
}

?>