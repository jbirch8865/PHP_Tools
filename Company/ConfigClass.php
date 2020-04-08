<?php declare(strict_types=1);
namespace Company;

use Active_Record\Active_Record;
class Config extends Active_Record
{
    public $_table = "Configs";

    function __construct()
    {
        parent::__construct();
    }
    /**
     * @throws UpdateFailed
     */
    public function Create_Or_Update_Config(string $config_name,string $config_default_value) : void
    {
        try
        {
            $this->Load_From_Varchar('config_name',$config_name);
        } catch (\Active_Record\Active_Record_Object_Failed_To_Load $e)
        {

        } catch (\Active_Record\Object_Is_Already_Loaded $e)
        {
            
        }
        $this->Set_Config_Name($config_name);
        $this->Set_Config_Default_Value($config_default_value);
        $this->Create_Object();    
    }
    /**
     * @throws Varchar_Too_Long_To_Set
     * @throws UpdateFailed
     */
    public function Set_Config_Name(string $config_name,bool $trim_if_too_long = true,bool $update_immediately = false) : void
    {
        $this->Set_Varchar($this->table_dblink->Get_Column('config_name'),$config_name,$trim_if_too_long,$update_immediately);
    }
    /**
     * @throws Varchar_Too_Long_To_Set
     * @throws UpdateFailed
     */
    public function Set_Config_Default_Value(string $config_value,bool $update_immediately = false) : void
    {
        $this->Set_Varchar($this->table_dblink->Get_Column('default_value'),$config_value,false,$update_immediately);
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Get_Default_Config_Value() : string
    {
        return $this->Get_Value_From_Name('default_value');
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Get_Config_Name() : string
    {
        return $this->Get_Value_From_Name('config_name');
    }
    /**
     * @throws Active_Record_Object_Failed_To_Load
     * @throws Object_Is_Already_Loaded
     */
    public function Load_Config_By_Name(string $config_name) : void
    {
        $this->Load_From_Varchar('config_name',$config_name);
    }
    /**
     * @throws Active_Record_Object_Failed_To_Load
     * @throws Object_Is_Already_Loaded
     */
    public function Load_Config_By_ID(int $config_id) : void
    {
        $this->Load_From_Int('id',$config_id);
    }
}

?>