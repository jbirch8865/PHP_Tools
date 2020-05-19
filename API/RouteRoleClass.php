<?php declare(strict_types=1);
namespace app\Helpers;

use Active_Record\Active_Record;
use Active_Record\iActiveRecord;

class Route_Role extends Active_Record implements iActiveRecord
{
    public $_table = "Routes_Have_Roles";

    function __construct()
    {
        $toolbelt = new \Test_Tools\toolbelt;
        parent::__construct();
        global $toolbelt_base;
        $toolbelt_base->active_record_relationship_manager->Load_Table_Belongs_To_If_Empty($this->table_dblink,$this->table_dblink->Get_Column('route_id'),$toolbelt->tables->Routes,$toolbelt->tables->Routes->Get_Column('id'),'\app\Helpers\Route',true);
        $toolbelt_base->active_record_relationship_manager->Load_Table_Belongs_To_If_Empty($this->table_dblink,$this->table_dblink->Get_Column('role_id'),$toolbelt->tables->Company_Roles,$toolbelt->tables->Company_Roles->Get_Column('id'),'\app\Helpers\Company_Role',true);
        $toolbelt_base->active_record_relationship_manager->Load_Table_Belongs_To_If_Empty($this->table_dblink,$this->table_dblink->Get_Column('right_id'),$toolbelt->tables->Rights,$toolbelt->tables->Rights->Get_Column('id'),'\app\Helpers\Right',false);
    }
    public function Get_Routes() : Route
    {
        $this->Routes;
        return $this->Routes;
    }
    public function Get_Company_Roles() : Company_Role
    {
        $this->Company_Roles;
        return $this->Company_Roles;
    }
    public function Get_Rights() : Right
    {
        $this->Rights;
        return $this->Rights;
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Get_Route_Name() : string
    {
        $this->Get_Verified_ID();
        return $this->Route->Get_Value_From_Name('name');
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Get_Route_ID() : int
    {
        return (int) $this->Get_Value_From_Name('route_id');
    }
    /**
     * @throws Update_Failed if other required parameters aren't set yet
     * @throws \Active_Record\Object_Has_Not_Been_Loaded for route
     */
    public function Set_Route(\app\Helpers\Route $route,bool $update_immediately) : void
    {
        $this->Set_Int($this->table_dblink->Get_Column('route_id'),$route->Get_Verified_ID(),$update_immediately);
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Get_Friendly_Name() : string
    {
        return $this->Company_Role->Get_Value_From_Name('name');
    }
    /**
     * @throws Active_Record_Object_Failed_To_Load
     * @throws \Active_Record\Object_Has_Not_Been_Loaded For $route required
     * @throws Object_Is_Already_Loaded
     */
    public function Load_By_Friendly_Name(string $friendly_name,?\Active_Record\Active_Record $route = null): void
    {
        $this->Load_From_Multiple_Vars([['role_name',$friendly_name],['route_id',$route->Get_Verified_ID()]]);
    }

    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Get_Role_ID() : int
    {
        return (int) $this->Get_Value_From_Name('role_id');
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded for company_role
     * @throws Update_Failed if other required parameters aren't set yet
     */
    public function Set_Role(\app\Helpers\Company_Role $company_role,bool $update_immediately) : void
    {
        $this->Set_Int($this->table_dblink->Get_Column('role_id'),$company_role->Get_Verified_ID(),$update_immediately);
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Get_Right_ID() : int
    {
        return (int) $this->Get_Value_From_Name('right_id');
    }
    /**
     * @throws Update_Failed if other required parameters aren't set yet
     */
    public function Set_Right(\app\Helpers\Right $right,bool $update_immediately) : void
    {
        $this->Set_Int($this->table_dblink->Get_Column('right_id'),$right->Get_Verified_ID(),$update_immediately);
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded for route and role
     */
    public function Load_From_Route_And_Role(\app\Helpers\Route $route,\app\Helpers\Company_Role $role) : void
    {
        $this->Load_From_Multiple_Vars([['route_id',$route->Get_Verified_ID()],['role_id',$role->Get_Verified_ID()]]);
    }
    public function Delete_Active_Record() : void
    {
        $this->Delete_Object('destroy');
    }

}

?>
