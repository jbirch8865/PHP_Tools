<?php
namespace DatabaseLink;
class Row {
    private $dblink;
    private $fields;
    private $field_object;
    private $primary_keys;
    private $table_name;
    private $loaded_from_db;

    function __construct(string $database, $table_name, $field_object = "\DatabaseLink\Field")
    {
        $this->table_name = $table_name;
        $this->field_object = $field_object;
        $this->dblink = new \DatabaseLink\MySQLLink($database);
        $this->Build_Fields_For_Row();
        $this->Load_Empty_Primary_Keys();
        $this->loaded_from_db = false;
    }
    /**
     * When we load a row from a database query this will return true
     * if we have not loaded the row from the database it likely means
     * we are creating a new entry and this will return false
     */
    protected function Have_I_Been_Loaded_From_DB()
    {
        return $this->loaded_from_db;
    }
    /**
     * Returns field value
     * @param string $column_name
     * @return string
     */
    protected function Get_Field_Value($column_name)
    {
        try
        {
            return $this->fields[$column_name]->Get_Field_Value();
        } catch (\Exception $e)
        {
            throw new \Exception($e->getMessage());
        }
    }
    /**
     * Sets field value
     * @param string $column_name
     */
    protected function Set_Field_Value($column_name,$value_to_set)
    {
        try
        {
            return $this->fields[$column_name]->Manually_Set_Field_Value($value_to_set);
        } catch (\Exception $e)
        {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Returns first row matched
     * @param string $column_name_to_search
     * @param string $value_to_find
     */
    protected function Single_Row_Search($column_name_to_search,$value_to_find)
    {
        if($this->Return_All_Fields_Except_Primary_Key_For_SQL_SELECT_Statement() == "")
        {
            $return_columns = "*";
        }else
        {
            $return_columns = $this->Return_All_Fields_Except_Primary_Key_For_SQL_SELECT_Statement();
        }
            
        try {
            $query = $this->dblink->ExecuteSQLQuery("SELECT ".$return_columns." FROM `".$this->table_name."` WHERE `".$column_name_to_search."` = '".$value_to_find."'");
            $query = mysqli_fetch_assoc($query);
            ForEach($query as $field_name => $field_value)
            {
                if(isset($this->fields[$field_name]))
                {
                    $this->fields[$field_name]->Set_Field_Value($field_value);
                }
            }
            $this->Set_Primary_Keys_From_Search($column_name_to_search,$value_to_find);
            $this->loaded_from_db = true;
        } catch (\Exception $e)
        {
            throw new \Exception($e->getMessage());
        }
    }

    protected function Insert_Row()
    {
        if($this->Are_All_Fields_Set() && !$this->Am_I_Ready_To_Update_Row())
        {
            $primary_keys_equal = $this->primary_keys->Return_PRIMARY_KEY_Equals();
            $set_statement = $this->Return_FIELD_EQUALS_VALUE_String().$primary_keys_equal;
            try
            {
                $this->dblink->ExecuteSQLQuery("INSERT INTO `".$this->table_name."` SET ".$set_statement);
                
            }catch (\Exception $e)
            {
                throw new \Exception($e->getMessage());
            }
        }else
        {
            if($this->Am_I_Ready_To_Update_Row())
            {
                throw new Fields_Are_Not_Set_Properly("You have loaded the primary keys from the database, cannot insert new row.  Use update row instead");
            } else
            {
                throw new Fields_Are_Not_Set_Properly("Not all fields are properly set, cannot insert row");
            }
        }
    }
    /**
     * Runs sql statement to update the row with the given primary keys.
     */
    protected function Update_Row()
    {
        try
        {
            if($this->Am_I_Ready_To_Update_Row())
            {
                $sql_statement = $this->Return_FIELD_EQUALS_VALUE_String()." WHERE ".$this->primary_keys->Return_PRIMARY_KEY_Equals();
                $this->dblink->ExecuteSQLQuery("UPDATE `".$this->table_name."` ".$sql_statement);
            }else
            {
                throw new Row_Not_Ready_To_Update("This row hasn't been properly loaded or set.");
            }
        } catch (Row_Not_Ready_To_Update $e)
        {
            throw new Row_Not_Ready_To_Update($e->getMessage());
        } catch (\Exception $e)
        {
            throw new \Exception($e->getMessage());
        }
    }
    /**
     * Runs sql statement to delete the row based on the loaded primary keys.
     */
    protected function Delete_Row()
    {
        try
        {
            if($this->Am_I_Ready_To_Update_Row())
            {
                $sql_statement = " WHERE ".$this->primary_keys->Return_PRIMARY_KEY_Equals_AND();
                $this->dblink->ExecuteSQLQuery("DELETE FROM `".$this->table_name."` ".$sql_statement);
            }else
            {
                throw new Row_Not_Ready_To_Update("This row hasn't been properly loaded.");
            }
        } catch (Row_Not_Ready_To_Update $e)
        {
            throw new Row_Not_Ready_To_Update($e->getMessage());
        } catch (\Exception $e)
        {
            throw new \Exception($e->getMessage());
        }
    }
    /**
     * Should be used when setting the primary key for a new entry.
     * Should not be used if setting the primary key from the DB for updating an existing entry.
     * Use Set_Primary_Key_Value_From_DB for updating an existing entry.
     * @param string $column_name
     * @param var $value_to_set
     */
    protected function Set_Primary_Key_Value_Manually($column_name, $value_to_set)
    {
        if(!$this->Am_I_Ready_To_Update_Row())
        {
            $this->primary_keys->Set_Primary_Key_Value_Manually($column_name, $value_to_set);
        }else
        {
            throw new \Exception("This row has already had the primary keys set cannot change them.");
        }
    }

    /**
     * Should be used when setting the primary key from the DB for updating an existing entry.
     * Use Set_Primary_Key_Value_Manually for when we are going to be inserting a new entry.
     * @param string $column_name
     * @param var $value_to_set
     */
    private function Set_Primary_Key_Value_From_DB($column_name, $value_to_set)
    {
        $this->primary_keys->Set_Primary_Key_Value_From_DB($column_name, $value_to_set);
    }

    private function Am_I_Ready_To_Update_Row()
    {
        return $this->primary_keys->Are_Ready_For_Update();
    }

    private function Are_All_Fields_Set()
    {
        if($this->Are_All_Data_Fields_Set() && $this->Are_All_Primary_Keys_Properly_Set())
        {
            return true;
        }else
        {
            return false;
        }
    }
    private function Are_All_Primary_Keys_Properly_Set()
    {
        if($this->primary_keys->Are_Properly_Set())
        {
            return true;
        }else
        {
            return false;
        }
    }

    private function Are_All_Data_Fields_Set()
    {
        ForEach($this->fields as $field)
        {
            if($field->is_field_required())
            {
                if(is_null($field->Get_Field_Value()))
                {
                    return false;
                }
            }
        }
        return true;
    }

    private function Return_FIELD_EQUALS_VALUE_String()
    {
        $string_to_return = "";

        ForEach($this->fields as $field)
        {
            if($field->Should_I_Update_Or_Insert_Value())
            {
                $string_to_return = $string_to_return.", `".$field->Get_Field_Name()."` = '".$field->Get_Field_Value()."'";
            } 
        }
        $string_to_return = substr($string_to_return,2);
        return $string_to_return;
    }

    private function Return_All_Fields_Except_Primary_Key_For_SQL_SELECT_Statement()
    {
        $field_names = "`";
        ForEach($this->fields as $field_name => $field_value)
        {
            $field_names = $field_names.$field_name."`, `";
        }
        return substr($field_names,0,strlen($field_names) - 3);
    }

    private function Build_Fields_For_Row()
    {
        $this->fields = array();
        $get_columns = $this->dblink->ExecuteSQLQuery("SHOW COLUMNS IN `$this->table_name` WHERE `KEY` <> 'PRI'");
        while($column = mysqli_fetch_assoc($get_columns))
        {
            $this->fields[$column['Field']] = new $this->field_object($this->dblink,$column['Field'],$this->table_name);
        }
    }

    private function Load_Empty_Primary_Keys()
    {
        $this->primary_keys = new PrimaryKeys($this->dblink,$this->table_name);
    }

    private function Set_Primary_Keys_From_Search($column_name_to_search, $value_to_find)
    {
        try {
            $query = $this->dblink->ExecuteSQLQuery("SELECT ".$this->primary_keys->Return_PRIMARY_KEY_For_SQL_SELECT_Statement()." FROM `".$this->table_name."` WHERE `".$column_name_to_search."` = '".$value_to_find."'");
            $query = mysqli_fetch_assoc($query);
            ForEach($query as $field_name => $field_value)
            {
                $this->Set_Primary_Key_Value_From_DB($field_name,$field_value);
            }
        } catch (\Exception $e)
        {
            throw new \Exception($e->getMessage());
        }                
    }
}
?>