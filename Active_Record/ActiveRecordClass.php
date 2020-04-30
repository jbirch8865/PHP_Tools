<?php declare(strict_types=1);
namespace Active_Record;

use Active_Record_Object;
use ADODB_Active_Record;
use DateTime;

abstract class Active_Record extends ADODB_Active_Record
{
    public \config\ConfigurationFile $cConfigs;
    protected \DatabaseLink\Table $table_dblink;

    function __construct()
    {
        parent::__construct();
        global $toolbelt_base;
        $this->cConfigs = $toolbelt_base->cConfigs;
        $table_name = $this->_table;
        $this->table_dblink = $toolbelt_base->$table_name;
    }

    /**
     * @throws Active_Record_Object_Failed_To_Load
     * @throws Object_Is_Already_Loaded
     */
    public function Load_Object_By_ID(int $id,bool $inactive = false) : void
    {
        $this->Load_From_Int('id',$id,$inactive);
    }

    /**
     * @throws Object_Is_Already_Loaded
     * @throws \DatabaseLink\Column_Does_Not_Exist
     * @throws \Active_Record\Active_Record_Object_Failed_To_Load if adodb->load method fails
     */
    protected function Load_From_Int(string $column_name,int $int_to_search,bool $inactive = false) : void
    {
        $this->Load_Object($column_name,$int_to_search,$inactive);
    }
    /**
     * @throws Object_Is_Already_Loaded
     * @throws \DatabaseLink\Column_Does_Not_Exist
     * @throws Active_Record_Object_Failed_To_Load if adodb->load method fails
     */
    protected function Load_From_Varchar(string $column_name,string $varchar_to_search,bool $inactive = false) : void
    {
        $this->Load_Object($column_name,$varchar_to_search,$inactive);
    }
    /**
     * @param array $load_from array(array('column_name','value'),array('column_name','value))
     * @throws Object_Is_Already_Loaded
     * @throws \DatabaseLink\Column_Does_Not_Exist
     * @throws Active_Record_Object_Failed_To_Load if adodb->load method fails
     */
    protected function Load_From_Multiple_Vars(array $load_from,bool $inactive = false) : void
    {
        $this->Load_Object("",$load_from,$inactive);
    }
    private function Load_Object(string $column_name,$var_to_search,bool $inactive = false) : void
    {
        if($this->Is_Loaded())
        {
            throw new Object_Is_Already_Loaded("This active record object has already been loaded from table ".$this->Get_Table_Name()." with id of ".$this->Get_Verified_ID());
        }
        if(!$this->table_dblink->Does_Column_Exist($column_name) && $column_name != "")
        {
            throw new \DatabaseLink\Column_Does_Not_Exist($column_name." does not exist in table ".$this->Get_Table_Name());
        }
        if(is_array($var_to_search))
        {
            $columns = array();
            $values = array();
            ForEach($var_to_search as $value_to_match)
            {
                if(!$this->table_dblink->Does_Column_Exist($value_to_match[0]))
                {
                    throw new \DatabaseLink\Column_Does_Not_Exist($value_to_match[0]." does not exist in table ".$this->Get_Table_Name());
                }
                $columns[] = $value_to_match[0];
                $values[] = $value_to_match[1];
            }
            $columns = Wrap_Array_Values_With_String('`',$columns);
            $columns = Append_To_Array_Values_With_String('=?',$columns);
            $columns = implode(" AND ",$columns);
            if(!$this->load($columns,$values))
            {
                throw new Active_Record_Object_Failed_To_Load("Failed loading ".$columns." in table ".$this->Get_Table_Name());
            }
        }else
        {
            if(!$this->load('`'.$column_name.'`=?',array($var_to_search)))
            {
                throw new Active_Record_Object_Failed_To_Load("Failed loading ".$var_to_search." from column ".$column_name." in table ".$this->Get_Table_Name());
            }
        }
        if(!$inactive)
        {
            if(!$this->Is_Object_Active())
            {
                throw new Object_Is_Currently_Inactive($this->Get_Verified_ID().' is currently inactive');
            }
        }

    }
    public function Is_Loaded():bool
    {
        if($this->_saved)
        {
            return true;
        }else
        {
            return false;
        }
    }
    /**
     * @throws \DatabaseLink\Column_Does_Not_Exist if column id isn't present
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Get_Verified_ID() : int
    {
        return (int) $this->Get_Value_From_Name('id');
    }
    /**
     * @throws \DatabaseLink\Column_Does_Not_Exist
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    protected function Get_Value_From_Name(string $column_name) : ?string
    {
        if(!$this->Is_Loaded()){throw new Object_Has_Not_Been_Loaded('Can not get value from '.$column_name.' because this object has not been loaded yet.');}
        if($this->table_dblink->Does_Column_Exist($column_name))
        {
            return (string) $this->$column_name;
        }else
        {
            throw new \DatabaseLink\Column_Does_Not_Exist("Table ".$this->Get_Table_Name()." does not contain ".$column_name." column. Check schema to see primary key column");
        }

    }
    public function Get_Table_Name() : string
    {
        return $this->_table;
    }
    /**
     * @throws Varchar_Too_Long_To_Set
     * @throws UpdateFailed — if adodb->save method fails
     */
    protected function Set_Varchar(\DatabaseLink\Column $column,string $value_to_set,bool $trim_if_too_long = true,bool $update_immediately = true) : void
    {
        if($trim_if_too_long)
        {
            $value_to_set = substr($value_to_set,0,$column->Get_Data_Length());
        }else
        {
            if(strlen($value_to_set) > $column->Get_Data_Length())
            {
                throw new Varchar_Too_Long_To_Set($value_to_set." is too long of a string for column ".$column->Get_Column_Name()." in table ".$this->Get_Table_Name());
            }
        }
        $name = $column->Get_Column_Name();
        $this->$name = $value_to_set;
        if($update_immediately)
        {
            $this->Create_Object();
        }
    }
    /**
     * @throws Update_Failed
     */
    protected function Set_Timestamp(\DatabaseLink\Column $column,DateTime $value_to_set,bool $update_immediately = true) : void
    {
        $name = $column->Get_Column_Name();
        $this->$name = $value_to_set->format('Y-m-d H:i:s');
        if($update_immediately)
        {
            $this->Create_Object();
        }
    }
    /**
     * @throws Update_Failed
     */
    protected function Set_Int(\DatabaseLink\Column $column,int $value_to_set,bool $update_immediately = true) : void
    {
        $name = $column->Get_Column_Name();
        $this->$name = $value_to_set;
        if($update_immediately)
        {
            $this->Create_Object();
        }
    }
    public function Set_Active_Status(?bool $active_status) : void
    {
        if(is_null($active_status)){return;}
        if($active_status)
        {
            $this->Set_Object_Active();
            $this->Update_Object();
        }else
        {
            $this->Set_Object_Inactive();
        }
    }

    /**
     * @throws \DatabaseLink\Column_Does_Not_Exist if table does not support this option
     */
    protected function Set_Object_Active() : void
    {
        try
        {
            $this->Get_Active_Status();
        } catch (\Active_Record\Object_Has_Not_Been_Loaded $e)
        {}
        $this->active_status = 1;
    }
    /**
     * @throws \DatabaseLink\Column_Does_Not_Exist if table does not support this option
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    protected function Set_Object_Inactive() : void
    {
        $this->Get_Active_Status();
        $this->active_status = 0;
        $this->Update_Object();
    }
    /**
     * @throws \DatabaseLink\Column_Does_Not_Exist if table does not support this option
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Is_Object_Active() : bool
    {
        return $this->Get_Active_Status();
    }
    /**
     * @throws \DatabaseLink\Column_Does_Not_Exist if table does not support this option
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Get_Active_Status() : bool
    {
        if(!$this->table_dblink->Does_Column_Exist('active_status'))
        {
            return true;
        }
        return (bool) $this->Get_Value_From_Name('active_status');
    }
    /**
     * @throws UpdateFailed if adodb->save method fails
     * @return bool true if this is a new object, false if this is just updating
     */
    protected function Create_Object() : bool
    {
        $new_object = !$this->Is_Loaded();
        try
        {
            if($new_object)
            {
                $this->Set_Object_Active();
            }
        } catch (\DatabaseLink\Column_Does_Not_Exist $e){}
        $this->Update_Object();
        return $new_object;
    }
    /**
     * @throws UpdateFailed if adodb->save method fails
     */
    protected function Update_Object() : void
    {
        if(!parent::save())
        {
            throw new \Active_Record\UpdateFailed('Object for table '.$this->Get_Table_Name().' failed to create or update with error '.$this->ErrorMsg());
        }
    }
    /**
     * Deletes the object from the database.
     *
     * @param string $password verify your intentions by passing the word "destroy"
     * @throws \Exception if the password isn't correct
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    protected function Delete_Object(string $password) : void
    {
        $this->Get_Verified_ID();//This will throw the exception of object not loaded
        if($password != "destroy")
        {
            throw new \Exception("destroy password not set.");
        }
        parent::Delete();
    }
    /**
     * blocks the child public method delete from being called outside of the protected Delete_Object function
     * this method is just an empty shell
     * @throws \Exception
     */
    public function Delete() : void
    {
        throw new \Exception('Must use the protected function Delete_Object in order to delete the active record object.');
    }
    /**
     * blocks the child public method delete from being called outside of the protected Update_Object or Create_Object function
     * this method is just an empty shell
     * @throws \Exception
     */
    public function save() : void
    {
        throw new \Exception('Must use the protected function Update_Object or Create_Object in order to update the active record object.');
    }
    /**
     * @throws \DatabaseLink\SQLQueryError
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    protected function Change_Primary_Key(int $new_key,int $old_key) : void
    {
        $this->Get_Verified_ID();
        $this->table_dblink->database_dblink->dblink->Execute_Any_SQL_Query("UPDATE `".$this->Get_Table_Name()."` SET `id` = ? WHERE `id` = ?",array($new_key,$old_key));
        $this->Load('id = 1');
    }
    /**
     * @param int $recursive_depth is the depth you want to go on loading relational objects
     */
    public function Get_Response_Collection(int $recursive_depth = 0,int $offset = 0,int $limit = 1) : array
    {
        $related_tables = [];
        if($recursive_depth > 0)
        {
            $this->Load_All_Relationships($offset,$limit);
            $toolbelt = new \Test_Tools\toolbelt;
            $related_tables = $toolbelt->active_record_relationship_manager->Get_Relationships_From_Parent_Table($this->table_dblink);
        }
        $collection = [];
        ForEach($this as $property_name => $property_value)
        {
            if(!is_array($property_value) && !is_object($property_value))
            {
                $this->table_dblink->Reset_Columns();
                while($column = $this->table_dblink->Get_Columns())
                {
                    if($column->Get_Column_Name() == $property_name && $column->Am_I_Included_In_Response())
                    {
                        $collection[$property_name] = $property_value;
                        break;
                    }
                }
            }else
            {
                if(in_array($property_name,$related_tables))
                {
                    if(is_array($property_value))
                    {
                        ForEach($property_value as $active_record)
                        {
                            $collection[$property_name][] = $active_record->Get_Response_Collection($recursive_depth - 1);
                        }
                    }else
                    {
                        $collection[$property_name] = $property_value->Get_Response_Collection($recursive_depth - 1);
                    }
                }
            }
        }
        return $collection;
    }

    private function Load_All_Relationships(int $offset,int $limit) : void
    {
        $toolbelt = new \Test_Tools\toolbelt;
        $relationships_to_load = $toolbelt->active_record_relationship_manager->Get_Relationships_From_Parent_Table($this->table_dblink);
        ForEach($relationships_to_load as $child_table_name)
        {
            $this->LoadRelations($child_table_name,'',$offset,$limit);
        }
    }

    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    function Get_API_Response_Collection(): array
    {
        return $this->Get_Response_Collection((int) app()->request->input('include_details',0),(int) app()->request->input('details_offset',0),(int) app()->request->input('details_limit',1));
    }

}
?>
