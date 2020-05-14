<?php declare(strict_types=1);
namespace app\Helpers;

use Active_Record\Active_Record;
use Active_Record\iActiveRecord;

class Object_Has_Tag extends Active_Record implements iActiveRecord
{
    public $_table = "Object_Has_Tags";

    function __construct()
    {
        parent::__construct();
        global $toolbelt_base;
        $toolbelt_base->active_record_relationship_manager->Load_Table_Belongs_To_If_Empty($this->table_dblink,$this->table_dblink->Get_Column('tag_id'),$toolbelt_base->Tags,$toolbelt_base->Tags->Get_Column('id'),'\app\Helpers\Tag',true);
    }
    /**
     * Doesn't work for object_has_tags
     */
    public function Get_Friendly_Name() : string
    {
        throw new \Exception('friendly name not unique for object_has_tags');
    }
    /**
     * Doesn't work for object_has_tags
     */
    public function Load_By_Friendly_Name(string $friendly_name,?\Active_Record\Active_Record $object = null): void
    {
        throw new \Exception('friendly name not unique for object_has_tags');
    }
    /**
     * @throws UpdateFailed — — if adodb->save method fails
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Set_Tag(Tag $tag,bool $update_immediately = true) : void
    {
        $this->Set_Int($this->table_dblink->Get_Column('tag_id'),$tag->Get_Verified_ID(),$update_immediately);
    }
    /**
     * @throws UpdateFailed — — if adodb->save method fails
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Set_Object(Active_Record $active_record,bool $update_immediately = true) : void
    {
        $this->Set_Varchar($this->table_dblink->Get_Column('object_table_name'),$active_record->table_dblink->Get_Table_Name(),true,false);
        $this->Set_Int($this->table_dblink->Get_Column('object_id'),$active_record->Get_Verified_ID(),$update_immediately);
    }
} 

?>
