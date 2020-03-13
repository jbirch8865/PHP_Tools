<?php declare(strict_types=1);
namespace Company;

use Active_Record\Active_Record;
class Company extends Active_Record
{
    function __construct(string $table_name)
    {
        parent::__construct($table_name);
    }

    public function Get_Company_Name() : string
    {
        return $this->company_name;
    }

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
    public function Change_Primary_Key(int $new_key,int $old_key) : void
    {
        parent::Change_Primary_Key($new_key,$old_key);
    }
    public function Load_Company_By_Name(string $name_to_search) : bool
    {
        if(!empty($this->id))
        {
            throw new \Exception("sorry ".$this->Get_Company_Name()." company has already been loaded.  You need to create an empty Company object.");
        }
        if($this->load('`company_name`=\''.$name_to_search."'"))
        {
            return true;
        }else
        {
            throw new CompanyDoesNotExist($name_to_search." does not exist");
        }
    }

    public function Get_Session_Time_Limit() : ?string
    {
        return $this->Get_Config_Value_By_Name('session_time_limit');
    }

    public function Set_Session_Time_Limit(string $time_in_minutes) : void
    {
        $this->Create_Or_Update_Config('session_time_limit',$time_in_minutes);
    }

    public function Set_Time_Zone(string $timezone) : void
    {
        $timezone_verify = new \DateTimeZone($timezone);
        $this->Create_Or_Update_Config('company_time_zone',$timezone);
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
    public function Set_Default_Configs() : void
    {
        $this->Set_Session_Time_Limit('300');
        $this->Set_Time_Zone('America/Los_Angeles');
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

    private function Create_Or_Update_Config(string $config_name,string $config_value) : void
    {
        $config = new \Company\Company_Config();
        $config->Create_Or_Update_Config($config_name,$config_value);
        $this->LoadRelations('Company_Configs');
    }
}
?>