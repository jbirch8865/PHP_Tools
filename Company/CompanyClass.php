<?php declare(strict_types=1);
namespace Company;

use Active_Record_Object;
use ADODB_Active_Record;
class Company extends ADODB_Active_Record implements Active_Record_Object
{
    private \config\ConfigurationFile $cConfigs;
    public \DatabaseLink\Table $table_dblink;
    private string $table_name = "Companies";
    var $_table = "Companies";

    function __construct(?int $unverified_id = null)
    {
        parent::__construct();
        global $cConfigs;
        $this->cConfigs = $cConfigs;
        global $dblink;
        $this->table_dblink = new \DatabaseLink\Table($this->table_name,$dblink);
        if (!empty($unverified_id)) 
        {
            $this->Load_Properties($unverified_id);
        }
    }
    private function Load_Properties(?int $id_to_verify) : void
    {
        if(!empty($id_to_verify))
        {
            if(!$this->Fill_Properties_If_Company_Exists($id_to_verify))
            {
                throw new \Company\CompanyDoesNotExist($id_to_verify." is not a valid company");
            }    
        }
    }
    /**
     * @param ?int $id_to_verify the id to look for, make null for name search
     * @param string $name_to_search the name to look for instead
     * @return bool will return false if not found and true after loading properties
     */
    private function Fill_Properties_If_Company_Exists(?int $id_to_verify) : bool
    {
        if(!empty($id_to_verify))
        {
            return $this->load('`id`=\''.$id_to_verify."'");
        }
    }
    public function Get_Verified_ID() : ?int
    {
        return $this->id;
    }

    public function Get_Company_Name() : string
    {
        return $this->company_name;
    }

    public function Get_Table_Name() : string
    {
        return $this->table_name;
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
    public function Set_Object_Active() : void
    {
        $this->active_status = 1;
    }
    public function Set_Object_Inactive() : void
    {
        $this->active_status = 0;
    }
    public function Is_Object_Active() : bool
    {  
        return $this->Get_Active_Status();
    }
    private function Get_Active_Status() : bool
    {
        return $this->active_status;
    }
    public function Create_Object() : void
    {
        $this->Update_Object();
    }
    public function Update_Object() : void
    {
        if(!$this->save())
        {
            throw new \Active_Record\UpdateFailed($this->Get_Company_Name().' failed to create or update with error '.$this->ErrorMsg());
        }
    }
    public function Delete_Object(string $password) : void
    {
        if($password != "destroy")
        {
            throw new \Exception("destroy password not set.");
        }
        $this->Delete();
    }
    public function Change_Primary_Key(int $new_key) : void
    {
        $this->table_dblink->database_dblink->dblink->Execute_Any_SQL_Query("UPDATE `".$this->table_name."` SET `id` = '$new_key' WHERE `company_name` = '".$this->Get_Company_Name()."'");
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