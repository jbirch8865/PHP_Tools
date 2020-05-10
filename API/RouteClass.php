<?php declare(strict_types=1);
namespace app\Helpers;
use Active_Record\Active_Record;

class Route extends Active_Record implements \Active_Record\iActiveRecord
{
    public $_table = "Routes";

    function __construct()
    {
        $toolbelt = new \test_tools\toolbelt;
        parent::__construct();
        $toolbelt->active_record_relationship_manager->Load_Table_Has_Many_If_Empty($this->table_dblink,$toolbelt->Routes_Have_Roles,$toolbelt->Routes_Have_Roles->Get_Column('route_id'),'\app\Helpers\Route_Role',false);
    }


    public function Delete_Active_Record() : void
    {

    }

    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Get_Name() : string
    {
        return $this->Get_Value_From_Name('name');
    }


    public function Set_Name(string $route_name,bool $update_immediately = true) : void
    {
        $this->Set_Varchar($this->table_dblink->Get_Column('name'),$route_name,false,$update_immediately);
    }



    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Get_Module_Name() : string
    {
        return $this->Get_Value_From_Name('module');
    }


    public function Set_Module_Name(string $module_name,bool $update_immediately = true) : void
    {
        $this->Set_Varchar($this->table_dblink->Get_Column('module'),$module_name,false,$update_immediately);
    }

    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Get_Friendly_Name(): ?string
    {
        return $this->Get_Value_From_Name('name');
    }



    /**
     * @throws Object_Is_Already_Loaded
     * @throws Active_Record_Object_Failed_To_Load — if name doesn't exist
     */
    public function Load_From_Route_Name(string $route_name) : void
    {
        $this->Load_From_Varchar('name',$route_name);
    }


    /**
     * @throws Object_Is_Already_Loaded
     * @throws Active_Record_Object_Failed_To_Load — if name doesn't exist
     */
    public function Load_By_Friendly_Name(string $friendly_name, ?\Active_Record\Active_Record $object = null): void
    {
        $this->Load_From_Route_Name($friendly_name);
    }




    /**
     * @throws Update_Failed if not all required values set and update_immediately is true
     */
    public function Set_Implicit_Allow(bool $implicit_allow = false,bool $update_immediately = true) : void
    {
        $this->Set_Int($this->table_dblink->Get_Column('implicit_allow'),(int) $implicit_allow,$update_immediately);
    }


    protected function Create_Object() : bool
    {
        if(parent::Create_Object())
        {
            $return = true;
        }else
        {
            $return = false;
        }
        $this->Add_To_All_Companies_Master_Role();
        return $return;
    }


    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Am_I_Implicitly_Allowed() : bool
    {
        return (bool) $this->Get_Value_From_Name('implicit_allow');
    }



    /**
     * @throws \Exception if no route
     */
    function Get_Current_Route_Name() : string
    {
        $route = \Illuminate\Support\Facades\Route::currentRouteName();
        if(is_null($route))
        {
            throw new \Exception('no route');
        }else
        {
            return $route;
        }
    }

    function Get_Current_Route_Method() : string
    {
        $method = app()->request->method();
        if($method == 'DELETE'){$method = 'destroy';}
        return strtolower($method);
    }



    private function Add_To_All_Companies_Master_Role() : void
    {
        $toolbelt = new \test_tools\toolbelt;
        $toolbelt->Companies->Query_Single_Table(['id'],false);
        $toolbelt->Companies->Reset_Queried_Data();
        while($row = $toolbelt->Companies->Get_Queried_Data())
        {
            $company = new \app\Helpers\Company;
            $company->Load_Object_By_ID((int) $row['id']);
            $role = $company->Get_Master_Role();
            $route_role = new \app\Helpers\Route_Role;
            try
            {
                $route_role->Load_From_Route_And_Role($this,$role);
                continue;
            } catch (\Active_Record\Active_Record_Object_Failed_To_Load $e)
            {
            }
            $right = new \app\Helpers\Right;
            $right->Allow_Destroy();
            $right->Allow_Get();
            $right->Allow_Patch();
            $right->Allow_Post();
            $right->Allow_Put();
            $route_role->Set_Right($right,false);
            $route_role->Set_Role($role,false);
            $route_role->Set_Route($this,true);
            ForEach($company->Company_Roles as $company_role)
            {
                if($company_role->Get_Friendly_Name() == 'master')
                {
                    continue;
                }
                $route_role = new \app\Helpers\Route_Role;
                try
                {
                    $route_role->Load_From_Route_And_Role($this,$company_role);
                    continue;
                } catch (\Active_Record\Active_Record_Object_Failed_To_Load $e)
                {
                }
                $right = new \app\Helpers\Right;
                $right->Deny_Get();
                $route_role->Set_Right($right,false);
                $route_role->Set_Role($company_role,false);
                $route_role->Set_Route($this,true);
            }
        }
    }



}

?>
