<?php declare(strict_types=1);
namespace app\Helpers;

use Active_Record\Active_Record;
use Active_Record\iActiveRecord;

class Tags extends Active_Record implements iActiveRecord
{
    public $_table = "Tags";

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
        return $this->Get_Value_From_Name('name');
    }
    /**
     * @throws Active_Record_Object_Failed_To_Load
     * @throws Object_Is_Already_Loaded
     */
    public function Load_By_Friendly_Name(string $friendly_name,?\Active_Record\Active_Record $object = null): void
    {
        throw new \Exception('friendly name not unique for tag');
    }
    /**
     * @throws \Active_Record\Varchar_Too_Long_To_Set if string too long and trim is false
     */
    public function Set_Tag_Name(string $name,bool $update_immediately = true) : void
    {
        $this->Set_Varchar($this->table_dblink->Get_Column('name'),$name,true,$update_immediately);
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
     * @throws \Active_Record\Varchar_Too_Long_To_Set if string too long and trim is false
     */
    public function Get_Tag_Name() : string
    {
        return $this->Get_Value_From_Name('name');
    }
}

?>
