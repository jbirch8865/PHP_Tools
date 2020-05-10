<?php declare(strict_types=1);
namespace app\Helpers;

use app\Helpers\iUser;
use app\Helpers\Company_Role;
use Active_Record\Active_Record;
use Active_Record\iActiveRecord;
use app\Helpers\Company;

class Customer_Has_Phone_Number extends Active_Record implements iActiveRecord
{
    public $_table = "Customer_Has_Phone_Numbers";
    private \Test_Tools\toolbelt $toolbelt;

    function __construct()
    {
        parent::__construct();
        $this->toolbelt = new \Test_Tools\toolbelt;
        global $toolbelt_base;
        $toolbelt_base->active_record_relationship_manager->Load_Table_Belongs_To_If_Empty($this->table_dblink,$this->table_dblink->Get_Column('customer_id'),$this->toolbelt->Customers,$this->toolbelt->Customers->Get_Column('id'),'\app\Helpers\Customer',true);
        $toolbelt_base->active_record_relationship_manager->Load_Table_Belongs_To_If_Empty($this->table_dblink,$this->table_dblink->Get_Column('phone_number_id'),$this->toolbelt->Phone_Numbers,$this->toolbelt->Phone_Numbers->Get_Column('id'),'\app\Helpers\Phone_Number',true);
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
    public function Set_Phone_Number(Phone_Number $phone_number,bool $update_immediately = true) : void
    {
        $phone_number = $phone_number->Get_Verified_ID();
        $this->Set_Int($this->table_dblink->Get_Column('phone_number_id'),$phone_number,$update_immediately);
    }
    /**
     * @throws Active_Record_Object_Failed_To_Load
     * @throws Object_Is_Already_Loaded
     * @throws \Active_Record\Object_Has_Not_Been_Loaded for user and company_role
     */
    public function Load_Customer_Phone_Number_From_Phone_Number(Phone_Number $phone_number):void
    {
        $this->Load_From_Int('phone_number_id',$phone_number->Get_Verified_ID());
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
     * Don't use this function as this isn't how you delete a phone number
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

        /**
     * @throws \Exception always
     * Don't use this function as their is no friendly name
     */
    public function Get_Friendly_Name(): ?string
    {
        throw new \Exception(debug_backtrace()[1]['function'].' does not work on '.get_class($this).'.');
    }

}

?>
