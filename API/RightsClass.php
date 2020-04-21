<?php declare(strict_types=1);
namespace app\Helpers;

use Active_Record\Active_Record;
use Active_Record\iActiveRecord;

class Right extends Active_Record
{
    public $_table = "Rights";

    function __construct()
    {
        $toolbelt = new \test_tools\toolbelt;
        parent::__construct();
        $toolbelt->active_record_relationship_manager->Load_Table_Belongs_To_If_Empty($this->table_dblink,$this->table_dblink->Get_Column('id'),$toolbelt->Routes_Have_Roles,$toolbelt->Routes_Have_Roles->Get_Column('right_id'),'\app\Helpers\Route_Role');
        $this->get = 0;
        $this->post = 0;
        $this->put = 0;
        $this->patch = 0;
        $this->destroy = 0;
    }
    public function Allow_Get() : void
    {
        $this->Set_Int($this->table_dblink->Get_Column('get'),1);
    }
    public function Deny_Get() : void
    {
        $this->Set_Int($this->table_dblink->Get_Column('get'),0);
    }
    public function Allow_Destroy() : void
    {
        $this->Set_Int($this->table_dblink->Get_Column('destroy'),1);
    }
    public function Deny_Destroy() : void
    {
        $this->Set_Int($this->table_dblink->Get_Column('destroy'),0);
    }
    public function Allow_Post() : void
    {
        $this->Set_Int($this->table_dblink->Get_Column('post'),1);
    }
    public function Deny_Post() : void
    {
        $this->Set_Int($this->table_dblink->Get_Column('post'),0);
    }
    public function Allow_Patch() : void
    {
        $this->Set_Int($this->table_dblink->Get_Column('patch'),1);
    }
    public function Deny_Patch() : void
    {
        $this->Set_Int($this->table_dblink->Get_Column('patch'),0);
    }
    public function Allow_Put() : void
    {
        $this->Set_Int($this->table_dblink->Get_Column('put'),1);
    }
    public function Deny_Put() : void
    {
        $this->Set_Int($this->table_dblink->Get_Column('put'),0);
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Is_Method_Allowed(string $method)
    {
        $method = strtolower($method);
        return (bool) $this->Get_Value_From_Name($method);
    }
    /**
     * @throws \Active_Record\Active_Record_Object_Failed_To_Load if adodb->load method fails
     * @throws Object_Is_Already_Loaded
     */
    public function Load_Right_By_ID(int $right_id) : void
    {
        $this->Load_From_Int('id',$right_id);
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    function Get_API_Response_Collection(): array
    {
        return $this->Get_Response_Collection(app()->request->input('include_details',0),app()->request->input('details_offset',0),app()->request->input('details_limit',1));
    }
    public function Delete_Active_Record() : void
    {
        app()->request->validate([
            'active_status' => ['required','bool']
        ]);
    }


}

?>
