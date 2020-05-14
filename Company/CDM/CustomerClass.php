<?php declare(strict_types=1);
namespace app\Helpers;

use Active_Record\Active_Record;
use Active_Record\iActiveRecord;

class Customer extends Active_Record implements iActiveRecord
{
    public $_table = "Customers";
    function __construct()
    {
        parent::__construct();
        global $toolbelt_base;
        $toolbelt_base->active_record_relationship_manager->Load_Table_Belongs_To_If_Empty($this->table_dblink,$this->table_dblink->Get_Column('company_id'),$this->toolbelt->Companies,$this->toolbelt->Companies->Get_Column('id'),'\app\Helpers\Company',true);
        $toolbelt_base->active_record_relationship_manager->Load_Table_Belongs_To_If_Empty($this->table_dblink,$this->table_dblink->Get_Column('credit_status'),$this->toolbelt->Credit_Statuses,$this->toolbelt->Credit_Statuses->Get_Column('id'),'\app\Helpers\Credit_Status',true);
        $toolbelt_base->active_record_relationship_manager->Load_Table_Has_Many_If_Empty($this->table_dblink,$this->toolbelt->Customer_Has_Addresses,$this->toolbelt->Customer_Has_Addresses->Get_Column('customer_id'),'\app\Helpers\Customer_Has_Address',false);
        $toolbelt_base->active_record_relationship_manager->Load_Table_Has_Many_If_Empty($this->table_dblink,$this->toolbelt->Customer_Has_Phone_Numbers,$this->toolbelt->Customer_Has_Phone_Numbers->Get_Column('customer_id'),'\app\Helpers\Customer_Has_Phone_Number',false);
    }

    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Get_Friendly_Name() : ?string
    {
        return $this->Get_Value_From_Name('customer_name');
    }

    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded for $object if variable name is not $object then it is required for the function to work
     * @throws \Active_Record\Object_Is_Already_Loaded
     * @throws Active_Record_Object_Failed_To_Load
     */
    public function Load_By_Friendly_Name(string $friendly_name,?\Active_Record\Active_Record $object = null) : void
    {
        throw new \Exception(debug_backtrace()[1]['function'].' does not work on '.get_class($this).'.');
    }

    /**
     * @throws \Active_Record\Varchar_Too_Long_To_Set if string too long and trim is false
     */
    public function Set_Customer_Name(string $company_name,bool $trim_if_too_long = true,bool $update_immediately = true) : void
    {
        $this->Set_Varchar($this->table_dblink->Get_Column('customer_name'),$company_name,$trim_if_too_long,$update_immediately);
    }
    /**
     * @throws \Active_Record\Varchar_Too_Long_To_Set if string too long and trim is false
     */
    public function Set_Credit_Status(Credit_Status $credit_status,bool $update_immediately = true) : void
    {
        $this->Set_Int($this->table_dblink->Get_Column('credit_status'),$credit_status->Get_Verified_ID(),$update_immediately);
    }
    /**
     * @throws \Active_Record\Varchar_Too_Long_To_Set if string too long and trim is false
     */
    public function Set_Website(string $website,bool $update_immediately = true) : void
    {
        $this->Set_Varchar($this->table_dblink->Get_Column('website'),$website,false,$update_immediately);
    }
    /**
     * @throws \Active_Record\Varchar_Too_Long_To_Set if string too long and trim is false
     */
    public function Set_CCB(string $CCB,bool $trim_if_too_long = true,bool $update_immediately = true) : void
    {
        $this->Set_Varchar($this->table_dblink->Get_Column('ccb'),$CCB,$trim_if_too_long,$update_immediately);
    }
    /**
     * @throws \Active_Record\Varchar_Too_Long_To_Set if string too long and trim is false
     */
    public function Set_Company(Company $company,bool $update_immediately = true) : void
    {
        $this->Set_Int($this->table_dblink->Get_Column('company_id'),$company->Get_Verified_ID(),$update_immediately);
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Get_Customer_Name() : string
    {
        return $this->Get_Value_From_Name('customer_name');
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Get_Website() : string
    {
        return $this->Get_Value_From_Name('website');
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Get_CCB() : string
    {
        return $this->Get_Value_From_Name('ccb');
    }
}
?>
