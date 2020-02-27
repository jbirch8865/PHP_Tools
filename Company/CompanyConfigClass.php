<?php declare(strict_types=1);
namespace Company;

use Active_Record_Object;
use ADODB_Active_Record;
class Company_Config extends ADODB_Active_Record implements Active_Record_Object
{
    private \config\ConfigurationFile $cConfigs;
    public \DatabaseLink\Table $table_dblink;
    public $_where = "";
    public $_table = "Company_Configs";
    private $table_name = "Company_Configs";

    function __construct(?string $unverified_config_id = null)
    {
        parent::__construct();
        global $cConfigs;
        $this->cConfigs = $cConfigs;
        global $dblink;
        $this->table_dblink = new \DatabaseLink\Table($this->table_name,$dblink);
        $this->_where = "`id`='".$unverified_config_id."' AND `company_id`='".$_SESSION['company_id']."'";
        if(!empty($unverified_config_id))
        {
            $this->Does_Config_Exist($unverified_config_id);
        }
    }
    private function Does_Config_Exist(string $unverified_config_id) : bool
    {
        if(!$this->load())
        {
            throw new \Exception($unverified_config_id." is not a valid config id or it does not belong to ".$_SESSION['company_id']);
        }
        return true;
    }
    public function Create_Or_Update_Config(string $config_name,$config_value) : void
    {
        if(!$this->_saved)
        {
            $this->load('config_name=? AND company_id=?',array($config_name,$_SESSION['company_id']));
        }
        $this->config_name = $config_name;
        $this->config_value = $config_value;
        $this->company_id = $_SESSION['company_id'];
        $this->Set_Object_Active();
        $this->Update_Object();    
    }
    public function Get_Config_Name()
    {
        return $this->config_name;
    }
    public function Get_Config_Value()
    {
        return $this->config_value;
    }
    public function Get_Table_Name() : string
    {
        return $this->table_name;
    }
    public function Update_Object() : void
    {
        if(!$this->save())
        {
            throw new \Active_Record\UpdateFailed($this->config_name.' failed to create or update with error '.$this->ErrorMsg());
        }
    }
    public function Create_Object() : void
    {
        $this->Update_Object();
    }
    public function Delete_Object(string $password) : void
    {
        if($password != "destroy")
        {
            throw new \Exception("destroy password not set.");
        }
        $this->Delete();
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


}

?>