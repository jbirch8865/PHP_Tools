<?php declare(strict_types=1);
namespace app\Helpers;

use app\Helpers\iUser;
use app\Helpers\Company_Role;
use Active_Record\Active_Record;
use Active_Record\iActiveRecord;
use app\Helpers\Company;

class Customer_Has_Address extends Active_Record implements iActiveRecord
{
    public $_table = "Customer_Has_Addresses";

    function __construct()
    {
        parent::__construct();
        global $toolbelt_base;
        $toolbelt_base->active_record_relationship_manager->Load_Table_Belongs_To_If_Empty($this->table_dblink,$this->table_dblink->Get_Column('customer_id'),$toolbelt_base->Customers,$toolbelt_base->Customers->Get_Column('id'),'\app\Helpers\Customer',true);
        $toolbelt_base->active_record_relationship_manager->Load_Table_Belongs_To_If_Empty($this->table_dblink,$this->table_dblink->Get_Column('address_id'),$toolbelt_base->Addresses,$toolbelt_base->Addresses->Get_Column('id'),'\app\Helpers\Address',false);
    }
    public function Get_Customers() : Customer
    {
        $this->Customers;
        return $this->Customers;
    }
    public function Get_Addresses() : Address
    {
        $this->Addresses;
        return $this->Addresses;
    }
    /**
     * @throws UpdateFailed — — if adodb->save method fails
     * @throws \Active_Record\Object_Has_Not_Been_Loaded for company_role
     */
    public function Set_Customer(Customer $customer,bool $update_immediately = true) : void
    {
        $customer_id = $customer->Get_Verified_ID();
        $this->Set_Int($this->table_dblink->Get_Column('customer_id'),$customer_id,$update_immediately);
    }
    /**
     * @throws UpdateFailed — — if adodb->save method fails
     * @throws \Active_Record\Object_Has_Not_Been_Loaded for user
     */
    public function Set_Address(Address $address,bool $update_immediately = true) : void
    {
        $address_id = $address->Get_Verified_ID();
        $this->Set_Int($this->table_dblink->Get_Column('address_id'),$address_id,$update_immediately);
    }
    /**
     * @throws Active_Record_Object_Failed_To_Load
     * @throws Object_Is_Already_Loaded
     * @throws \Active_Record\Object_Has_Not_Been_Loaded for user and company_role
     */
    public function Load_Customer_Address_From_Address(Address $address):void
    {
        $this->Load_From_Int('address_id',$address->Get_Verified_ID());
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

    /**
     * @throws \Active_Record\UpdateFailed
     */
    public function Create_Object(): bool
    {
        return parent::Create_Object();
    }
}

?>
