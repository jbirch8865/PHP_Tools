<?php declare(strict_types=1);
namespace app\Helpers;

use Active_Record\Active_Record;
use Active_Record\iActiveRecord;
use sms\twilio_number;

class Phone_Number extends Active_Record implements iActiveRecord
{
    public $_table = "Phone_Numbers";
    function __construct()
    {
        parent::__construct();
        global $toolbelt_base;
        $toolbelt_base->active_record_relationship_manager->Load_Table_Belongs_To_If_Empty($this->table_dblink,$this->table_dblink->Get_Column('company_id'),$this->toolbelt->tables->Get_Companies(),$this->toolbelt->tables->Get_Companies()->Get_Column('id'),'\app\Helpers\Company',true);
    }
    public function Get_Companies() : array
    {
        return $this->Companies;
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Get_Friendly_Name(bool $format_as_e164 = false) : string
    {
        if($format_as_e164)
        {
            return  $this->Get_E164_Formatted_Number();
        }elseif(empty($this->Get_Value_From_Name('ext')))
        {
            return $this->Get_Phone_Number_Area_Code().'-'.$this->Get_Phone_Number_Prefix().'-'.$this->Get_Phone_Number_Suffix();
        }else
        {
            return $this->Get_Phone_Number_Area_Code().'-'.$this->Get_Phone_Number_Prefix().'-'.$this->Get_Phone_Number_Suffix().' ext.'.$this->Get_Phone_Number_Ext();
        }
    }
    private function Get_E164_Formatted_Number() : string
    {
        return "+".$this->Get_Phone_Number_Country_Code().$this->Get_Phone_Number_Area_Code().$this->Get_Phone_Number_Prefix().$this->Get_Phone_Number_Suffix();
    }
    /**
     * @throws Active_Record_Object_Failed_To_Load
     * @throws Object_Is_Already_Loaded
     */
    public function Load_By_Friendly_Name(string $friendly_name,?\Active_Record\Active_Record $object = null): void
    {
        throw new \Exception('friendly name not unique for phone number');
    }
    /**
     * @throws \Active_Record\Varchar_Too_Long_To_Set if string too long and trim is false
     */
    public function Set_Phone_Number_Description(string $description,bool $update_immediately = true) : void
    {
        $this->Set_Varchar($this->table_dblink->Get_Column('description'),$description,true,$update_immediately);
    }
    /**
     * @throws \Active_Record\Varchar_Too_Long_To_Set if string too long and trim is false
     */
    public function Set_Phone_Number_Country_Code(int $country_code,bool $update_immediately = true) : void
    {
        $this->Set_Phone_Number_Carrier("",false);
        $this->Set_Int($this->table_dblink->Get_Column('country_code'),$country_code,$update_immediately);
    }
    /**
     * @throws \Active_Record\Varchar_Too_Long_To_Set if string too long and trim is false
     */
    public function Set_Phone_Number_Area_Code(int $area_code,bool $update_immediately = true) : void
    {
        $this->Set_Phone_Number_Carrier("",false);
        $this->Set_Int($this->table_dblink->Get_Column('area_code'),$area_code,$update_immediately);
    }
    /**
     * @throws \Active_Record\Varchar_Too_Long_To_Set if string too long and trim is false
     */
    public function Set_Phone_Number_Prefix(int $prefix,bool $update_immediately = true) : void
    {
        $this->Set_Phone_Number_Carrier("",false);
        $this->Set_Int($this->table_dblink->Get_Column('prefix'),$prefix,$update_immediately);
    }
    /**
     * @throws \Active_Record\Varchar_Too_Long_To_Set if string too long and trim is false
     */
    public function Set_Phone_Number_Suffix(int $suffix,bool $update_immediately = true) : void
    {
        $this->Set_Phone_Number_Carrier("",false);
        $this->Set_Int($this->table_dblink->Get_Column('suffix'),$suffix,$update_immediately);
    }
    /**
     * @throws \Active_Record\Varchar_Too_Long_To_Set if string too long and trim is false
     */
    public function Set_Phone_Number_Ext(int $ext,bool $update_immediately = true) : void
    {
        $this->Set_Int($this->table_dblink->Get_Column('ext'),$ext,$update_immediately);
    }
    /**
     * @throws \Active_Record\Varchar_Too_Long_To_Set if string too long and trim is false
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Set_Phone_Number_Type(string $type,bool $update_immediately = true) : void
    {
        $this->Set_Varchar($this->table_dblink->Get_Column('type'),$type,true,$update_immediately);
    }
    /**
     * @throws \Active_Record\Varchar_Too_Long_To_Set if string too long and trim is false
     */
    public function Set_Phone_Number_Carrier(string $carrier,bool $update_immediately = true) : void
    {
        $this->Set_Varchar($this->table_dblink->Get_Column('carrier'),$carrier,true,$update_immediately);
    }
    /**
     * @throws UpdateFailed — — if adodb->save method fails
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Set_Company(Company $company,bool $update_immediately = true) : void
    {
        $this->Set_Int($this->table_dblink->Get_Column('company_id'),$company->Get_Verified_ID(),$update_immediately);
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Get_Address_Description() : string
    {
        return $this->Get_Value_From_Name('description');
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Get_Phone_Number_Country_Code() : string
    {
        return $this->Get_Value_From_Name('country_code');
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Get_Phone_Number_Area_Code() : string
    {
        return $this->Get_Value_From_Name('area_code');
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Get_Phone_Number_Prefix() : string
    {
        return $this->Get_Value_From_Name('prefix');
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Get_Phone_Number_Suffix() : string
    {
        return $this->Get_Value_From_Name('suffix');
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Get_Phone_Number_Ext() : string
    {
        return $this->Get_Value_From_Name('ext');
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Get_Phone_Number_Type() : string
    {
        return $this->Get_Value_From_Name('type');
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Get_Phone_Number_Carrier() : string
    {
        return $this->Get_Value_From_Name('carrier');
    }

    public function Create_Object() : bool
    {
        if(!$this->Is_Loaded())
        {
            $this->Set_Phone_Number_Carrier("",false);
            $this->Set_Phone_Number_Type("",false);
            parent::Create_Object();
            $twilio = new twilio_number($this);
            $twilio->Ensure_Carrier_And_Type_Exist_Or_Mark_NA();
        }else
        {
            $twilio = new twilio_number($this);
            $twilio->Ensure_Carrier_And_Type_Exist_Or_Mark_NA(false);
            parent::Create_Object();
        }
        return true;
    }

}

?>
