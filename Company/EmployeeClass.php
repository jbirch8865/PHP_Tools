<?php declare(strict_types=1);
namespace app\Helpers;

class Employee extends People
{
    private \Test_Tools\toolbelt $toolbelt;
    function __construct()
    {
        $this->toolbelt = new \Test_Tools\toolbelt;
        parent::__construct();
        global $toolbelt_base;
        $toolbelt_base->active_record_relationship_manager->Load_Table_Belongs_To_If_Empty(
            $this->table_dblink,$this->table_dblink->Get_Column('id'),
            $toolbelt_base->People_Belong_To_Company,
            $toolbelt_base->People_Belong_To_Company->Get_Column('people_id'),
            '\app\Helpers\Employee_Company');
    }

    function Create_Object(): bool
    {
        if($return = parent::Create_Object())
        {
            $link = new Employee_Company;
            $link->Set_Company($this->toolbelt->Get_Company(),false);
            try
            {
                $link->Set_Employee($this);
                $this->LoadRelations('People_Belong_To_Company');
            } finally { }
        }
        return $return;
    }
}
?>
