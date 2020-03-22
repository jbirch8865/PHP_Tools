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
     * @throws User_Not_Set
     * @throws UpdateFailed
     */
    public function Create_Or_Update_Config(string $config_name,string $config_default_value) : void
    {
        if(!$this->_saved)
        {
            try
            {
                $this->Load_From_Varchar('config_name',$config_name);
            } catch (\Active_Record\Active_Record_Object_Failed_To_Load $e)
            {

            }
        }
        $this->config_name = $config_name;
        $this->default_value = $config_default_value;
        $this->Create_Object();    
    }
    /**
     * @throws Varchar_Too_Long_To_Set
     */
    public function Set_Config_Name(string $config_name,bool $trim_if_too_long = true,bool $update_immediately = false) : void
    {
        $this->Set_Varchar('config_name',$config_name,$trim_if_too_long,$update_immediately);
    }
    /**
     * @throws Varchar_Too_Long_To_Set
     */
    public function Set_Config_Default_Value(string $config_value,bool $update_immediately = false) : void
    {
        $this->Set_Varchar('config_value',$config_value,false,$update_immediately);
    }
    public function Get_Default_Config_Value() : string
    {
        return $this->default_value;
    }
    /**
     * @throws Active_Record_Object_Failed_To_Load
     */
    public function Load_Config_By_Name(string $config_name) : void
    {
        $this->Load_From_Varchar('config_name',$config_name);
    }
    /**
     * @throws Active_Record_Object_Failed_To_Load
     */
    public function Load_Config_By_ID(int $config_id) : void
    {
        $this->Load_From_Int('id',$config_id);
    }
}

?>