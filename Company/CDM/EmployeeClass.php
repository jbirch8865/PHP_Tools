<?php declare(strict_types=1);
namespace app\Helpers;

class Employee extends People
{
    private Employee_Company $link;

    function __construct()
    {
        $this->link = new Employee_Company;
        parent::__construct();
        global $toolbelt_base;
        $toolbelt_base->active_record_relationship_manager->Load_Table_Belongs_To_If_Empty(
            $this->table_dblink,$this->table_dblink->Get_Column('id'),
            $toolbelt_base->People_Belong_To_Company,
            $toolbelt_base->People_Belong_To_Company->Get_Column('people_id'),
            '\app\Helpers\Employee_Company',false);
    }
    public function Get_People_Belong_To_Company() : Employee
    {
        $this->People_Belong_To_Company;
        return $this->People_Belong_To_Company;
    }

    public function Create(): void
    {
        $this->Set_Company($this->toolbelt->objects->Get_Company(),false);
        parent::Create_Object();
        $this->link->Set_Company($this->toolbelt->objects->Get_Company(),false);
        $this->link->Set_Employee($this);
        $this->LoadRelations('People_Belong_To_Company');
    }
    public function Create_Object() : bool
    {
        $this->Create();
        return true;
    }
}
?>
