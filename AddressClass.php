<?php declare(strict_types=1);
namespace app\Helpers;

use Active_Record\Active_Record;
use Active_Record\iActiveRecord;

class Address extends Active_Record implements iActiveRecord
{
    public $_table = "Addresses";

    function __construct()
    {
        parent::__construct();
        global $toolbelt_base;
        $toolbelt_base->active_record_relationship_manager->Load_Table_Belongs_To_If_Empty($this->table_dblink,$this->table_dblink->Get_Column('company_id'),$toolbelt_base->Companies,$toolbelt_base->Companies->Get_Column('id'),'\app\Helpers\Company',true);
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Get_Friendly_Name() : string
    {
        return $this->Get_Value_From_Name('description');
    }
    /**
     * @throws Active_Record_Object_Failed_To_Load
     * @throws Object_Is_Already_Loaded
     */
    public function Load_By_Friendly_Name(string $friendly_name,?\Active_Record\Active_Record $object = null): void
    {
        throw new \Exception('friendly name not unique for address');
    }
    /**
     * @throws \Active_Record\Varchar_Too_Long_To_Set if string too long and trim is false
     */
    public function Set_Address_Description(string $description,bool $update_immediately = true) : void
    {
        $this->Set_Varchar($this->table_dblink->Get_Column('description'),$description,true,$update_immediately);
    }
    /**
     * @throws \Active_Record\Varchar_Too_Long_To_Set if string too long and trim is false
     */
    public function Set_Address_Name(string $name,bool $update_immediately = true) : void
    {
        $this->Set_Varchar($this->table_dblink->Get_Column('name'),$name,true,$update_immediately);
    }
    /**
     * @throws \Active_Record\Varchar_Too_Long_To_Set if string too long and trim is false
     */
    public function Set_Address_Street1(string $street1,bool $update_immediately = true) : void
    {
        $this->Set_Varchar($this->table_dblink->Get_Column('street1'),$street1,true,$update_immediately);
    }
    /**
     * @throws \Active_Record\Varchar_Too_Long_To_Set if string too long and trim is false
     */
    public function Set_Address_Street2(string $street2,bool $update_immediately = true) : void
    {
        $this->Set_Varchar($this->table_dblink->Get_Column('street2'),$street2,true,$update_immediately);
    }
    /**
     * @throws \Active_Record\Varchar_Too_Long_To_Set if string too long and trim is false
     */
    public function Set_Address_City(string $city,bool $update_immediately = true) : void
    {
        $this->Set_Varchar($this->table_dblink->Get_Column('city'),$city,true,$update_immediately);
    }
    /**
     * @throws \Active_Record\Varchar_Too_Long_To_Set if string too long and trim is false
     */
    public function Set_Address_State(string $state,bool $update_immediately = true) : void
    {
        $this->Set_Varchar($this->table_dblink->Get_Column('state'),$state,true,$update_immediately);
    }
    /**
     * @throws \Active_Record\Varchar_Too_Long_To_Set if string too long and trim is false
     */
    public function Set_Address_Zip(string $zip,bool $update_immediately = true) : void
    {
        $this->Set_Varchar($this->table_dblink->Get_Column('zip'),$zip,true,$update_immediately);
    }
    /**
     * @throws \Active_Record\Varchar_Too_Long_To_Set if string too long and trim is false
     */
    public function Set_Address_Lat(string $lat,bool $update_immediately = true) : void
    {
        $this->Set_Varchar($this->table_dblink->Get_Column('lat'),$lat,true,$update_immediately);
    }
    /**
     * @throws \Active_Record\Varchar_Too_Long_To_Set if string too long and trim is false
     */
    public function Set_Address_Lng(string $lng,bool $update_immediately = true) : void
    {
        $this->Set_Varchar($this->table_dblink->Get_Column('lng'),$lng,true,$update_immediately);
    }
    /**
     * @throws \Active_Record\Varchar_Too_Long_To_Set if string too long and trim is false
     */
    public function Set_Address_URL(string $url,bool $update_immediately = true) : void
    {
        $this->Set_Varchar($this->table_dblink->Get_Column('url'),$url,true,$update_immediately);
    }
    /**
     * @throws \Active_Record\Varchar_Too_Long_To_Set if string too long and trim is false
     */
    public function Set_Google_ID(string $google_id,bool $update_immediately = true) : void
    {
        $this->Set_Varchar($this->table_dblink->Get_Column('google_id'),$google_id,true,$update_immediately);
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
    public function Get_Company() : int
    {
        return (int) $this->Get_Value_From_Name('company_id');
    }
    /**
     * @throws \Active_Record\Varchar_Too_Long_To_Set if string too long and trim is false
     */
    public function Get_Address_Description() : string
    {
        return $this->Get_Value_From_Name('description');
    }
    /**
     * @throws \Active_Record\Varchar_Too_Long_To_Set if string too long and trim is false
     */
    public function Get_Address_Name() : string
    {
        return $this->Get_Value_From_Name('name');
    }
    /**
     * @throws \Active_Record\Varchar_Too_Long_To_Set if string too long and trim is false
     */
    public function Get_Address_Street1() : string
    {
        return $this->Get_Value_From_Name('street1');
    }
    /**
     * @throws \Active_Record\Varchar_Too_Long_To_Set if string too long and trim is false
     */
    public function Get_Address_Street2() : string
    {
        return $this->Get_Value_From_Name('street2');
    }
    /**
     * @throws \Active_Record\Varchar_Too_Long_To_Set if string too long and trim is false
     */
    public function Get_Address_City() : string
    {
        return $this->Get_Value_From_Name('city');
    }
    /**
     * @throws \Active_Record\Varchar_Too_Long_To_Set if string too long and trim is false
     */
    public function Get_Address_State() : string
    {
        return $this->Get_Value_From_Name('state');
    }
    /**
     * @throws \Active_Record\Varchar_Too_Long_To_Set if string too long and trim is false
     */
    public function Get_Address_Zip() : string
    {
        return $this->Get_Value_From_Name('zip');
    }
    /**
     * @throws \Active_Record\Varchar_Too_Long_To_Set if string too long and trim is false
     */
    public function Get_Address_Lat() : string
    {
        return $this->Get_Value_From_Name('lat');
    }
    /**
     * @throws \Active_Record\Varchar_Too_Long_To_Set if string too long and trim is false
     */
    public function Get_Address_Lng() : string
    {
        return $this->Get_Value_From_Name('lng');
    }
    /**
     * @throws \Active_Record\Varchar_Too_Long_To_Set if string too long and trim is false
     */
    public function Get_Address_URL() : string
    {
        return $this->Get_Value_From_Name('url');
    }
    /**
     * @throws \Active_Record\Varchar_Too_Long_To_Set if string too long and trim is false
     */
    public function Get_Google_ID() : string
    {
        return $this->Get_Value_From_Name('google_id');
    }
}

?>
