<?php declare(strict_types=1);
namespace app\Helpers;

use Active_Record\Active_Record;
use Active_Record\iActiveRecord;

class Tags_Have_Role extends Active_Record implements iActiveRecord
{
    public $_table = "Tags_Have_Roles";
    private Tag $tag_to_add;
    private Company_Role $role_to_add;
    private bool $get_to_assign;
    private bool $post_to_assign;
    private bool $destroy_to_assign;

    function __construct()
    {
        parent::__construct();
        global $toolbelt_base;
        $toolbelt_base->active_record_relationship_manager->Load_Table_Belongs_To_If_Empty($this->table_dblink,$this->table_dblink->Get_Column('tag_id'),$toolbelt_base->Tags,$toolbelt_base->Tags->Get_Column('id'),'\app\Helpers\Tag',true);
        $toolbelt_base->active_record_relationship_manager->Load_Table_Belongs_To_If_Empty($this->table_dblink,$this->table_dblink->Get_Column('role_id'),$toolbelt_base->Company_Roles,$toolbelt_base->Company_Roles->Get_Column('id'),'\app\Helpers\Company_Role',true);
    }
    /**
     * Doesn't work for Tags_Have_Roles
     */
    public function Get_Friendly_Name() : string
    {
        throw new \Exception('friendly name not unique for Tags_Have_Role');
    }
    /**
     * Doesn't work for Tags_Have_Roles
     */
    public function Load_By_Friendly_Name(string $friendly_name,?\Active_Record\Active_Record $object = null): void
    {
        throw new \Exception('friendly name not unique for Tags_Have_Role');
    }
    /**
     * @throws UpdateFailed — — if adodb->save method fails
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Set_Tag(Tag $tag,bool $update_immediately = true) : void
    {
        $this->tag_to_add = $tag;
        $this->Set_Int($this->table_dblink->Get_Column('tag_id'),$tag->Get_Verified_ID(),$update_immediately);
    }
    /**
     * @throws UpdateFailed — — if adodb->save method fails
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Set_Role(Company_Role $company_role,bool $allow_get = false,bool $allow_post = false,bool $allow_destroy = false,bool $update_immediately = true) : void
    {
        $this->role_to_add = $company_role;
        $this->get_to_assign = $allow_get;
        $this->post_to_assign = $allow_post;
        $this->destroy_to_assign = $allow_destroy;
        $this->Set_Int($this->table_dblink->Get_Column('get'),(int) $allow_get,false);
        $this->Set_Int($this->table_dblink->Get_Column('post'),(int) $allow_post,false);
        $this->Set_Int($this->table_dblink->Get_Column('destroy'),(int) $allow_destroy,false);
        $this->Set_Int($this->table_dblink->Get_Column('role_id'),$company_role->Get_Verified_ID(),$update_immediately);
    }
    /**
     * @throws \Active_Record\Object_Is_Already_Loaded
     * @throws \Active_Record\Active_Record_Object_Failed_To_Load — if adodb->load method fails
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     * private function because programmer cannot run set_tag or set_role to update as the update will delete all current data
     * for example if you set_tag to change the tag that is currently assigned you will loose the role, get, post and destory values
     */
    public function Load_By_Tag_And_Role(Tag $tag,Company_Role $company_role) : void
    {
        $this->Load_From_Multiple_Vars([['tag_id',$tag->Get_Verified_ID()],['role_id',$company_role->Get_Verified_ID()]]);
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Is_Method_Allowed(string $method)
    {
        $method = strtolower($method);
        return (bool) $this->Get_Value_From_Name($method);
    }
    protected function Create_Object() : bool
    {
        try
        {
            $this->Load_By_Tag_And_Role($this->tag_id,$this->role_id);
            $this->Delete_Object('destroy');
            $this->Set_Tag($this->tag_to_add,false);
            $this->Set_Role($this->role_to_add,$this->get_to_assign,$this->post_to_assign,$this->destroy_to_assign,false);
        } catch (\Active_Record\Active_Record_Object_Failed_To_Load $e)
        {
        } catch (\Exception $e)
        {
            throw new \Exception("Looks like you are trying to update a Tags Have Role.  You cannot do this.");
        }
        return parent::Create_Object();
    }
}

?>
