<?php declare(strict_types=1);
namespace app\Helpers;

class Customer_Phone_Number extends Phone_Number
{
    private Customer_Has_Phone_Number $link;
    function __construct()
    {
        $this->link = new Customer_Has_Phone_Number;
        parent::__construct();
        global $toolbelt_base;
        $toolbelt_base->active_record_relationship_manager->Load_Table_Belongs_To_If_Empty(
            $this->table_dblink,$this->table_dblink->Get_Column('id'),
            $this->toolbelt->Customer_Has_Phone_Numbers,
            $this->toolbelt->Customer_Has_Phone_Numbers->Get_Column('phone_number_id'),
            '\app\Helpers\Customer_Has_Phone_Number',false);
    }

    /**
     * @throws \Active_Record\UpdateFailed
     */
    public function Create() : void
    {
        $this->Set_Company($this->toolbelt->Get_Company(),false);
        parent::Create_Object();
        $this->link->Set_Phone_Number($this,false);
        $this->link->Set_Customer($this->toolbelt->Get_Customer(3));
        $this->LoadRelations('Customer_Has_Phone_Numbers');
    }
    public function Create_Object() : bool
    {
        $this->Create();
        return true;
    }
}
?>
