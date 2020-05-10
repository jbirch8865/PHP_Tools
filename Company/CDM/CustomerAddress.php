<?php declare(strict_types=1);
namespace app\Helpers;

class Customer_Address extends Address
{
    private \Test_Tools\toolbelt $toolbelt;
    private Customer_Has_Address $link;
    function __construct()
    {
        $this->toolbelt = new \Test_Tools\toolbelt;
        $this->link = new Customer_Has_Address;
        parent::__construct();
        global $toolbelt_base;
        $toolbelt_base->active_record_relationship_manager->Load_Table_Belongs_To_If_Empty(
            $this->table_dblink,$this->table_dblink->Get_Column('id'),
            $toolbelt_base->Customer_Has_Addresses,
            $toolbelt_base->Customer_Has_Addresses->Get_Column('address_id'),
            '\app\Helpers\Customer_Has_Address',false);
    }

    /**
     * @throws \Active_Record\UpdateFailed
     */
    public function Create() : void
    {
        $this->Set_Company($this->toolbelt->Get_Company(),false);
        parent::Create_Object();
        $this->link->Set_Address($this,false);
        $this->link->Set_Customer($this->toolbelt->Get_Customer(3));
        $this->LoadRelations('Customer_Has_Addresses');
    }
    public function Create_Object() : bool
    {
        $this->Create();
        return true;
    }
}
?>
