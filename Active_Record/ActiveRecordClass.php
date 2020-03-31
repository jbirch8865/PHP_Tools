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
     * @throws Object_Is_Already_Loaded
     * @throws \DatabaseLink\Column_Does_Not_Exist
     * @throws \Active_Record\Active_Record_Object_Failed_To_Load if adodb->load method fails
     */
    protected function Load_From_Int(string $column_name,int $int_to_search) : void
    {
        $this->Load_Object($column_name,$int_to_search);
    }
    /**
     * @throws Object_Is_Already_Loaded
     * @throws \DatabaseLink\Column_Does_Not_Exist
     * @throws Active_Record_Object_Failed_To_Load if adodb->load method fails
     */
    protected function Load_From_Varchar(string $column_name,string $varchar_to_search) : void
    {
        $this->Load_Object($column_name,$varchar_to_search);
    }
    /**
     * @param array $load_from array(array('column_name','value'),array('column_name','value))
     * @throws Object_Is_Already_Loaded
     * @throws \DatabaseLink\Column_Does_Not_Exist
     * @throws Active_Record_Object_Failed_To_Load if adodb->load method fails
     */
    protected function Load_From_Multiple_Vars(array $load_from) : void
    {
        $this->Load_Object("",$load_from);
    }
    private function Load_Object(string $column_name,$var_to_search) : void
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
     */
    public function Get_Verified_ID() : ?int
    {
        $id = (int) $this->Get_Value_From_Name('id');
        return $id;
    }
    /**
     * @throws \DatabaseLink\Column_Does_Not_Exist
     */
    protected function Get_Value_From_Name(string $column_name) : ?string
    {
        if($this->table_dblink->Does_Column_Exist($column_name))
        {
            $column_name = (string) $this->$column_name;
            return $column_name;
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
     */
    protected function Set_Varchar(string $column_name,string $value_to_set,bool $trim_if_too_long = true,bool $update_immediately = true) : void
    {
        if($trim_if_too_long)
        {
            $value_to_set = substr($value_to_set,0,$this->table_dblink->Get_Column($column_name)->Get_Data_Length());
        }else
        {
            if(strlen($value_to_set) > $this->table_dblink->Get_Column($column_name)->Get_Data_Length())
            {
                throw new Varchar_Too_Long_To_Set($value_to_set." is too long of a string for column ".$column_name." in table ".$this->Get_Table_Name());
            }
        }
        $this->$column_name = $value_to_set;
        if($update_immediately)
        {
            $this->Create_Object();
        }
    }
    /**
     * @throws Update_Failed
     */
    protected function Set_Timestamp(string $column_name,DateTime $value_to_set,bool $update_immediately = true) : void
    {
        $this->$column_name = $value_to_set->format('Y-m-d H:i:s');
        if($update_immediately)
        {
            $this->Create_Object();
        }
    }
    /**
     * @throws Update_Failed
     */
    protected function Set_Int(string $column_name,int $value_to_set,bool $update_immediately = true) : void
    {
        $this->$column_name = $value_to_set;
        if($update_immediately)
        {
            $this->Create_Object();
        }
    }
    protected function Set_Object_Active() : void
    {
        if($this->table_dblink->Does_Column_Exist('active_status')) 
        {
            $this->active_status = 1;
        }
    }
    protected function Set_Object_Inactive() : void
    {
        if($this->table_dblink->Does_Column_Exist('active_status')) 
        {
            $this->active_status = 0;
        }
    }
    protected function Is_Object_Active() : bool
    {  
        return $this->Get_Active_Status();
    }
    private function Get_Active_Status() : bool
    {
        return (bool) $this->active_status;
    }
    /**
     * @throws UpdateFailed if adodb->save method fails
     */
    protected function Create_Object() : void
    {
        $this->Set_Object_Active();
        $this->Update_Object();
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
     */
    protected function Delete_Object(string $password) : void
    {
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
     */
    protected function Change_Primary_Key(int $new_key,int $old_key) : void
    {
        $this->table_dblink->database_dblink->dblink->Execute_Any_SQL_Query("UPDATE `".$this->Get_Table_Name()."` SET `id` = ? WHERE `id` = ?",array($new_key,$old_key));
    }
    public function Get_Response_Collection() : array
    {
        $collection = [];
        ForEach($this as $property_name => $property_value)
        {
            if(is_string($property_value))
            {
                while($column = $this->table_dblink->Get_Columns())
                {
                    if($column->Get_Column_Name() == $property_name)
                    {
                        $collection[$property_name] = $property_value;
                    }
                }
            }
        }
        return $collection;
    }
}
?>