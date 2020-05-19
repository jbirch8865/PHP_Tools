<?php declare(strict_types=1);
namespace app\Helpers;

use app\Helpers\iUser;
use app\Helpers\Company_Role;
use Active_Record\Active_Record;
use Active_Record\iActiveRecord;
use app\Helpers\Company;

class Employee_Company extends Active_Record implements iActiveRecord
{
    public $_table = "People_Belong_To_Company";

    function __construct()
    {
        parent::__construct();
        global $toolbelt_base;
        $toolbelt_base->active_record_relationship_manager->Load_Table_Belongs_To_If_Empty($this->table_dblink,$this->table_dblink->Get_Column('company_id'),$toolbelt_base->Companies,$toolbelt_base->Companies->Get_Column('id'),'\app\Helpers\Company',false);
        $toolbelt_base->active_record_relationship_manager->Load_Table_Belongs_To_If_Empty($this->table_dblink,$this->table_dblink->Get_Column('people_id'),$toolbelt_base->Peoples,$toolbelt_base->Peoples->Get_Column('id'),'\app\Helpers\Employee',false);
    }
    public function Get_Companies() : Company
    {
        $this->Companies;
        return $this->Companies;
    }
    public function Get_Peoples() : People
    {
        $this->Peoples;
        return $this->Peoples;
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Get_People_ID() : int
    {
        return (int) $this->Get_Value_From_Name('people_id');
    }
     /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Get_Company_ID() : int
    {
        return (int) $this->Get_Value_From_Name('company_id');
    }

    /**
     * @throws UpdateFailed — — if adodb->save method fails
     * @throws \Active_Record\Object_Has_Not_Been_Loaded for company_role
     */
    public function Set_Company(Company $company,bool $update_immediately = true) : void
    {
        $company_id = $company->Get_Verified_ID();
        $this->Set_Int($this->table_dblink->Get_Column('company_id'),$company_id,$update_immediately);
    }
    /**
     * @throws UpdateFailed — — if adodb->save method fails
     * @throws \Active_Record\Object_Has_Not_Been_Loaded for user
     */
    public function Set_Employee(Employee $employee,bool $update_immediately = true) : void
    {
        $employee_id = $employee->Get_Verified_ID();
        $this->Set_Int($this->table_dblink->Get_Column('people_id'),$employee_id,$update_immediately);
    }
    /**
     * @throws Active_Record_Object_Failed_To_Load
     * @throws Object_Is_Already_Loaded
     * @throws \Active_Record\Object_Has_Not_Been_Loaded for user and company_role
     */
    public function Load_Employee_Company_From_Employee(Employee $employee):void
    {
        $this->Load_From_Int('people_id',$employee->Get_Verified_ID());
    }

    /**
     * @throws \Exception always
     * Don't use this function as their is no friendly name
     */
    public function Load_By_Friendly_Name(string $friendly_name,?\Active_Record\Active_Record $object = null): void
    {
        throw new \Exception(debug_backtrace()[1]['function'].' does not work on '.get_class($this).' for name '.$friendly_name.'.');
    }
    /**
     * @throws \Exception always
     * Don't use this function as their is no friendly name
     */
    public function Get_Friendly_Name(): ?string
    {
        throw new \Exception(debug_backtrace()[1]['function'].' does not work on '.get_class($this).'.');

    }
    /**
     * @throws \Exception always
     * Don't use this function as this isn't how you delete a employee or company
     */
    public function Delete_Active_Record(): void
    {
        throw new \Exception(debug_backtrace()[1]['function'].' does not work on '.get_class($this).'.');
    }
}

?>
