<?php declare(strict_types=1);
namespace Company;

use Active_Record\Active_Record;
class Company extends Active_Record
{
    private array $table_has_many = [['Company_Configs','company_id','Companies','\Company\Company_Config']];
    public $_table = "Companies";

    function __construct()
    {
        parent::__construct();
        ForEach($this->table_has_many as $relation)
        {
//            \ADODB_Active_Record::TableHasMany($relation[1],$relation[0],$relation[2],$relation[3]);
        }
    }
    public function Get_Company_Name() : string
    {
        return $this->company_name;
    }
    /**
     * @throws Exception if string too long and trim is false
     */
    public function Set_Company_Name(string $company_name,bool $trim_if_too_long = true,bool $update_immediately = true) : void
    {
        if(strlen($company_name) > $this->table_dblink->Get_Column('company_name')->Get_Data_Length())
        {
            if($trim_if_too_long)
            {
                $company_name = substr($company_name,0,$this->table_dblink->Get_Column('company_name')->Get_Data_Length());
            }else
            {
                throw new \Exception($company_name." is too long of a name");
            }
        }
        $this->company_name = $company_name;
        if($update_immediately)
        {
            $this->Set_Object_Active();
            $this->Update_Object();
        }
    }
    /**
     * @throws SQLQueryError
     */
    public function Change_Primary_Key(int $new_key,int $old_key) : void
    {
        parent::Change_Primary_Key($new_key,$old_key);
    }
    /**
     * @throws Exception if object already loaded
     * @throws CompanyDoesNotExist
     */
    public function Load_Company_By_Name(string $name_to_search) : void
    {
        $this->Load_From_Varchar('company_name',$name_to_search);
    }
    /**
     * @throws Exception if object already loaded
     * @throws \Active_Record\Active_Record_Object_Failed_To_Load
     */
    public function Load_Company_By_ID(int $id_to_search) : void
    {
        $this->Load_From_Int('id',$id_to_search);
    }
    public function Get_Session_Time_Limit() : ?int
    {
        return (int) $this->Get_Config_Value_By_Name('session_time_limit');
    }
    /**
     * @throws UpdateFailed
     * @throws User_Not_Set
     */
    public function Set_Session_Time_Limit(int $time_in_seconds,bool $company_has_no_users = false) : void
    {
        $config = new \Company\Config();
        $config->Load_Config_By_Name('session_time_limit');
        $this->Create_Or_Update_Config($config->Get_Verified_ID(),(string)$time_in_seconds,$company_has_no_users);
    }
    /**
     * @throws UpdateFailed
     * @throws User_Not_Set
     */
    public function Set_Time_Zone(string $timezone,bool $company_has_no_users = false) : void
    {
        $timezone_verify = new \DateTimeZone($timezone);
        $config = new \Company\Config();
        $config->Load_Config_By_Name('company_time_zone');
        $this->Create_Or_Update_Config($config->Get_Verified_ID(),$timezone,$company_has_no_users);
    }
    public function Get_Time_Zone() : ?\DateTimeZone
    {
        try
        {
            return new \DateTimeZone($this->Get_Config_Value_By_Name('company_time_zone'));
        } catch (\Exception $e)
        {
            return null;
        }
    }
    /**
     * @throws UpdateFailed
     * @throws User_Not_Set
     */
    public function Create_Object(): void
    {
        parent::Create_Object();
        $this->Set_Default_Configs();
    }
    /**
     * Can't be called after a user has been created
     */
    private function Set_Default_Configs() : void
    {
        $time_limit = new \Company\Config();
        $time_limit->Load_Config_By_Name('session_time_limit');
        $time_zone = new \Company\Config();
        $time_zone->Load_Config_By_Name('company_time_zone');        
        $this->Set_Session_Time_Limit((int) $time_limit->Get_Default_Config_Value(),true);
        $this->Set_Time_Zone($time_zone->Get_Default_Config_Value(),true);
    }
    private function Get_Config_Value_By_Name(string $config_name) : ?string
    {
        ForEach($this->Company_Configs as $index => $company_config)
        {
            if($company_config->Get_Config_Name() == $config_name)
            {
                return $company_config->Get_Config_Value();
            }
        }
        return null;
    }
    /**
     * @throws UpdateFailed
     * @throws User_Not_Set
     */
    private function Create_Or_Update_Config(int $config_id,string $config_value,bool $company_has_no_users = false) : void
    {
        $config = new \Company\Company_Config();
        if($company_has_no_users)
        {
            $config->Create_Config_For_Company_With_No_Users($config_id,$config_value,$this->Get_Verified_ID());
        }else
        {
            $config->Create_Or_Update_Config($config_id,$config_value);
        }
        $this->LoadRelations('Company_Configs');
    }
}
?>