<?php declare(strict_types=1);
namespace Company;

use Active_Record\Active_Record;
class Company_Config extends Active_Record
{
    public $_table = "Company_Configs";

    function __construct()
    {
        parent::__construct();
    }
    /**
     * @throws User_Not_Set
     * @throws UpdateFailed
     */
    public function Create_Or_Update_Config(int $config_id,string $config_value) : void
    {
        try
        {
            global $user;
            $company_id = $user->Company->Get_Company_ID();
        }catch(\Exception $e)
        {
            throw new \api\User_Not_Set('Looks like the user was not properly set when making this request');
        }
        if(!$this->_saved)
        {
            if(!$this->load('config_id=? AND company_id=?',array($config_id,$company_id)))
            {
                throw new \Active_Record\Active_Record_Object_Failed_To_Load($config_id." with value ".$config_value." failed to load with error ".$this->ErrorMsg()." and error number ".$this->ErrorNo());
            }
        }
        $this->config_id = $config_id;
        $this->config_value = $config_value;
        $this->company_id = $company_id;
        $this->Create_Object();    
    }
    public function Get_Config_ID()
    {
        return $this->config_id;
    }
    public function Get_Config_Name() : string
    {
        $config = new \Company\Config();
        $id = (int) $this->config_id;
        $config->Load_Config_By_ID($id);
        return $config->Get_Config_Name();
    }
    public function Get_Config_Value()
    {
        return $this->config_value;
    }
    /**
     * @throws Exception if users currently exist for company
     * @throws UpdateFailed
     */
    public function Create_Config_For_Company_With_No_Users(int $config_id,string $config_value,int $company_id) : void
    {
        if($this->_saved)
        {
            throw new \Active_Record\Object_Is_Already_Loaded('Sorry this instance of Company_Config is already loaded');
        }
        $company = new \Company\Company();
        $company->Load_Company_By_ID($company_id);
        if(count($company->Users))
        {
            throw new \Exception("Sorry you can't use this function if the company has users.");
        }
        $this->config_id = $config_id;
        $this->config_value = $config_value;
        $this->company_id = $company_id;
        $this->Create_Object();
    }

}

?>