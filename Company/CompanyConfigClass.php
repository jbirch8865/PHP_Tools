<?php declare(strict_types=1);
namespace Company;

use Active_Record\Active_Record;
use Active_Record\iActiveRecord;

class Company_Config extends Active_Record implements iActiveRecord
{
    public $_table = "Company_Configs";

    function __construct()
    {
        parent::__construct();
        global $toolbelt_base;
        $toolbelt_base->active_record_relationship_manager->Load_Table_Belongs_To_If_Empty($this->table_dblink,$this->table_dblink->Get_Column('company_id'),$toolbelt_base->Companies,$toolbelt_base->Companies->Get_Column('id'),'\Company\Company');
        $toolbelt_base->active_record_relationship_manager->Load_Table_Belongs_To_If_Empty($this->table_dblink,$this->table_dblink->Get_Column('config_id'),$toolbelt_base->Configs,$toolbelt_base->Configs->Get_Column('id'),'\Company\Config');
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded for config and company
     * @throws UpdateFailed
     * @throws Varchar_Too_Long_To_Set
     */
    public function Create_Or_Update_Config(\Company\Config $config,\Company\Company $company,string $config_value) : void
    {
        $config_id = $config->Get_Verified_ID();
        $company_id = $company->Get_Verified_ID();
        try
        {
            $this->Load_From_Multiple_Vars([['config_id',$config_id],['company_id',$company_id]]);
        } catch (\Active_Record\Active_Record_Object_Failed_To_Load $e)
        {

        } catch (\Active_Record\Object_Is_Already_Loaded $e)
        {
            
        }
        $this->Set_Int($this->table_dblink->Get_Column('config_id'),$config_id,false);
        $this->Set_Int($this->table_dblink->Get_Column('company_id'),$company_id,false);
        $this->Set_Varchar($this->table_dblink->Get_Column('config_value'),$config_value,false,false);
        $this->Create_Object();    
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Get_Config_ID()
    {
        return $this->Get_Value_From_Name('config_id');
    }
    /**
     * @throws \Active_Record\Active_Record_Object_Failed_To_Load
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Load_Object_By_ID(int $object_id): void
    {
        $this->Load_From_Int('id',$object_id);
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Get_Friendly_Name() : string
    {
        $this->Get_Verified_ID();
        return $this->Configs->Get_Value_From_Name('config_name');
    }
    /**
     * @throws Active_Record_Object_Failed_To_Load
     * @throws Object_Is_Already_Loaded
     * Don't use this function as there is no friendly name
     */
    public function Load_By_Friendly_Name(string $friendly_name,?\Active_Record\Active_Record $object = null): void
    {
        throw new \Exception('Load_By_Friendly_Name does not work on Company Config Class for name '.$friendly_name.'.');
    }

    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Get_Config_Value()
    {
        return $this->Get_Value_From_Name('config_value');
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Delete_Active_Record() : void
    {
        app()->request->validate([
            'active_status' => ['required','bool']
        ]);
    }

}

?>