<?php declare(strict_types=1);
namespace app\Helpers;

use Active_Record\Active_Record;
use Active_Record\iActiveRecord;
use databaseLink\Table;

class Tag extends Active_Record implements iActiveRecord
{
    public $_table = "Tags";

    function __construct()
    {
        parent::__construct();
        global $toolbelt_base;
        $toolbelt_base->active_record_relationship_manager->Load_Table_Belongs_To_If_Empty($this->table_dblink,$this->table_dblink->Get_Column('company_id'),$toolbelt_base->Companies,$toolbelt_base->Companies->Get_Column('id'),'\app\Helpers\Company',true);
        $toolbelt_base->active_record_relationship_manager->Load_Table_Has_Many_If_Empty($this->table_dblink,$toolbelt_base->Tags_Have_Roles,$toolbelt_base->Tags_Have_Roles->Get_Column('tag_id'),'\app\Helpers\Tags_Have_Role');
    }
    public function Get_Companies() : array
    {
        return $this->Companies;
    }
    public function Get_Tags_Have_Roles() : array
    {
        return $this->Tags_Have_Roles;
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Get_Friendly_Name() : string
    {
        return $this->Get_Value_From_Name('name');
    }
    /**
     * @throws Active_Record_Object_Failed_To_Load
     * @throws Object_Is_Already_Loaded
     */
    public function Load_By_Friendly_Name(string $friendly_name,?\Active_Record\Active_Record $object = null): void
    {
        throw new \Exception('friendly name not unique for tag');
    }
    /**
     * @throws \Active_Record\Varchar_Too_Long_To_Set if string too long and trim is false
     */
    public function Set_Tag_Name(string $name,bool $update_immediately = true) : void
    {
        $this->Set_Varchar($this->table_dblink->Get_Column('name'),$name,true,$update_immediately);
    }
    /**
     * @throws UpdateFailed — — if adodb->save method fails
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Set_Company(Company $company,bool $update_immediately = true) : void
    {
        $this->Set_Int($this->table_dblink->Get_Column('company_id'),$company->Get_Verified_ID(),$update_immediately);
    }
    /**
     * @throws \Active_Record\Varchar_Too_Long_To_Set if string too long and trim is false
     */
    public function Get_Tag_Name() : string
    {
        return $this->Get_Value_From_Name('name');
    }
    /**
     * @throws \Active_Record\Varchar_Too_Long_To_Set if string too long and trim is false
     */
    public function Set_Table_Name(Table $table,bool $update_immediately = true) : void
    {
        $this->Set_Varchar($this->table_dblink->Get_Column('object_table_name'),$table->Get_Table_Name(),false,$update_immediately);
    }

    public function Change_Primary_Key(int $new_key,int $old_key) : void
    {
        parent::Change_Primary_Key($new_key,$old_key);
    }

    public function Allow_Duplicates() : bool
    {
        $tag = new Tag;
        $tag->Load_Object_By_ID($this->toolbelt->cConfigs->Get_Multi_Tag_ID());
        if(is_null($this->Get_Object_Has_Tag_From_Tag($tag)))
        {
            return false;
        }else
        {
            return true;
        }

    }

    protected function Create_Object() : bool
    {
        parent::Create_Object();
        $tag_has_role = new Tags_Have_Role;
        $tag_has_role->Set_Role($this->toolbelt->objects->Get_Company()->Get_Master_Role(),true,true,true,false);
        $tag_has_role->Set_Tag($this);
        return true;
    }
}

?>
