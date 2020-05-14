<?php declare(strict_types=1);
namespace app\Helpers;

use Active_Record\Active_Record;
use Active_Record\iActiveRecord;

class Company extends Active_Record implements iActiveRecord
{
    public $_table = "Companies";

    function __construct()
    {
        parent::__construct();
        global $toolbelt_base;
        $toolbelt_base->active_record_relationship_manager->Load_Table_Has_Many_If_Empty($this->table_dblink,$toolbelt_base->Company_Configs,$toolbelt_base->Company_Configs->Get_Column('company_id'),'\app\Helpers\Company_Config',false);
        $toolbelt_base->active_record_relationship_manager->Load_Table_Has_Many_If_Empty($this->table_dblink,$toolbelt_base->Company_Roles,$toolbelt_base->Company_Roles->Get_Column('company_id'),'\app\Helpers\Company_Role',false);
        $toolbelt_base->active_record_relationship_manager->Load_Table_Has_Many_If_Empty($this->table_dblink,$toolbelt_base->Equipments,$toolbelt_base->Equipments->Get_Column('company_id'),'\app\Helpers\Equipment',false);
        $toolbelt_base->active_record_relationship_manager->Load_Table_Has_Many_If_Empty($this->table_dblink,$toolbelt_base->Credit_Statuses,$toolbelt_base->Credit_Statuses->Get_Column('company_id'),'\app\Helpers\Credit_Status',false);
        $toolbelt_base->active_record_relationship_manager->Load_Table_Has_Many_If_Empty($this->table_dblink,$toolbelt_base->People_Belong_To_Company,$toolbelt_base->People_Belong_To_Company->Get_Column('company_id'),'\app\Helpers\Employee_Company',false);
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Get_Friendly_Name() : string
    {
        return $this->Get_Value_From_Name('company_name');
    }
    /**
     * @throws Active_Record_Object_Failed_To_Load
     * @throws Object_Is_Already_Loaded
     */
    public function Load_By_Friendly_Name(string $friendly_name,?\Active_Record\Active_Record $object = null): void
    {
        $this->Load_From_Varchar('company_name',$friendly_name);
    }

    /**
     * @throws \Active_Record\Varchar_Too_Long_To_Set if string too long and trim is false
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
                throw new \Active_Record\Varchar_Too_Long_To_Set($company_name." is too long of a name");
            }
        }
        $this->company_name = $company_name;
        if($update_immediately)
        {
            $this->Create_Object();
        }
    }
    /**
     * @throws SQLQueryError
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Change_Primary_Key(int $new_key,int $old_key) : void
    {
        parent::Change_Primary_Key($new_key,$old_key);
    }
    /**
     * @throws Object_Is_Already_Loaded
     * @throws Active_Record_Object_Failed_To_Load
     */
    public function Load_Company_By_Name(string $name_to_search,bool $inactive = false) : void
    {
        $this->Load_From_Varchar('company_name',$name_to_search,$inactive);
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Get_Session_Time_Limit() : ?int
    {
        return (int) $this->Get_Config_Value_By_Name('session_time_limit');
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded if this has not been loaded yet
     */
    public function Set_Session_Time_Limit(int $time_in_seconds) : void
    {
        $config = new \app\Helpers\Config();
        $config->Load_Config_By_Name('session_time_limit');
        $this->Create_Or_Update_Config($config,(string)$time_in_seconds);
    }
    /**
     * @throws \Exception if timezone isn't valid
     * @throws \Active_Record\Object_Has_Not_Been_Loaded if this hasn't been loaded yet
     */
    public function Set_Time_Zone(string $timezone,bool $company_has_no_users = false) : void
    {
        new \DateTimeZone($timezone);
        $config = new \app\Helpers\Config();
        $config->Load_Config_By_Name('company_time_zone');
        $this->Create_Or_Update_Config($config,$timezone);
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     * @throws \Exception if Config isn't set or invalid
     */
    public function Get_Time_Zone() : \DateTimeZone
    {
        return new \DateTimeZone($this->Get_Config_Value_By_Name('company_time_zone'));
    }
    /**
     * @throws UpdateFailed
     */
    public function Create_Object(): bool
    {
        if(parent::Create_Object())
        {
            $this->Set_Default_Configs();
            $this->Create_Company_Role('master',true,true,true,true,true);
            return true;
        }
        return false;
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded — if this has not been loaded yet
     */
    private function Set_Default_Configs() : void
    {
        $time_limit = new \app\Helpers\Config();
        $time_limit->Load_Config_By_Name('session_time_limit');
        $time_zone = new \app\Helpers\Config();
        $time_zone->Load_Config_By_Name('company_time_zone');
        $this->Set_Session_Time_Limit((int) $time_limit->Get_Default_Config_Value(),true);
        $this->Set_Time_Zone($time_zone->Get_Default_Config_Value(),true);
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    private function Get_Config_Value_By_Name(string $config_name) : ?string
    {
        $this->Get_Verified_ID();
        ForEach($this->Company_Configs as $index => $company_config)
        {
            if($company_config->Get_Friendly_Name() == $config_name)
            {
                return $company_config->Get_Config_Value();
            }
        }
        return null;
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded — for config and this
     * @throws Varchar_Too_Long_To_Set
     */
    private function Create_Or_Update_Config(\app\Helpers\Config $config,string $config_value) : void
    {
        $company_config = new \app\Helpers\Company_Config;
        $company_config->Create_Or_Update_Config($config,$this,$config_value);
        $this->LoadRelations('Company_Configs');
    }
    /**
     * @throws \Active_Record\UpdateFailed if the role already exists
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    Private function Create_Role(string $role_name) : int
    {
        $company_role = new \app\Helpers\Company_Role;
        $company_role->Set_Company_ID($this->Get_Verified_ID(),false);
        $company_role->Set_Role_Name($role_name);
        $this->LoadRelations('Company_Roles');
        return $company_role->Get_Verified_ID();
    }

    function Get_Master_Role() : ?\app\Helpers\Company_Role
    {
//        $this->LoadRelations('Company_Roles');
        ForEach($this->Company_Roles as $company_role)
        {
            if($company_role->Get_Friendly_Name() == 'master')
            {
                return $company_role;
            }
        }
        return null;
    }
    function Delete_Company(bool $make_inactive = true) : void
    {
        if($make_inactive)
        {
            $this->Set_Object_Inactive();
        }else
        {
            $this->Delete_Object('destroy');
        }
    }

       /**
    * @throws \Active_Record\Object_Has_Not_Been_Loaded
    */
    function Create_Company_Role(string $role_name,bool $get = true,bool $delete = false,bool $post = true,bool $patch = true,bool $put = true): void
    {
        $role_id = $this->Create_Role($role_name);
        $new_role = new Company_Role;
        $new_role->Load_Object_By_ID($role_id);
        $toolbelt = new \test_tools\toolbelt;
        $toolbelt->Routes->Query_Single_Table(['id'],false);
        while($row = $toolbelt->Routes->Get_Queried_Data())
        {
            $route = new \app\Helpers\Route;
            $route->Load_Object_By_ID((int) $row['id']);
            if($route->Am_I_Implicitly_Allowed())
            {
                continue;
            }
            $right = new \app\Helpers\Right;
            if($get)
            {
                $right->Allow_Get();
            }else
            {
                $right->Deny_Get();
            }
            if($delete)
            {
                $right->Allow_Destroy();
            }else
            {
                $right->Deny_Destroy();
            }
            if($post)
            {
                $right->Allow_Post();
            }else
            {
                $right->Deny_Post();
            }
            if($patch)
            {
                $right->Allow_Patch();
            }else
            {
                $right->Deny_Patch();
            }
            if($put)
            {
                $right->Allow_Put();
            }else
            {
                $right->Deny_Put();
            }
            $route_has_role = new \app\Helpers\Route_Role;
            $route_has_role->Set_Right($right,false);
            $route_has_role->Set_Role($new_role,false);
            $route_has_role->Set_Route($route,true);
        }
    }
}
?>
