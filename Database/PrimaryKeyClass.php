<?php
namespace DatabaseLink;

class PrimaryKeys {
    private $dblink;
    private $table_name;
    private $primary_key_or_keys;

    function __construct($dblink, $table_name)
    {
        $this->table_name = $table_name;
        $this->dblink = $dblink;
        $this->Build_Primary_Key_Columns();
    }

    /**
     * @param string $column_name
     * @param var $value_to_set 
     */
    public function Set_Primary_Key_Value_Manually($column_name, $value_to_set)
    {
        $this->primary_key_or_keys[$column_name]->Manually_Set_Field_Value($value_to_set);
    }

    /**
     * @param string $column_name
     * @param var $value_to_set 
     */
    public function Set_Primary_Key_Value_From_DB($column_name, $value_to_set)
    {
        $this->primary_key_or_keys[$column_name]->Set_Field_Value_From_DB($value_to_set);
    }

     /**
     * Formats a string like `Primary_Key` = 'Value_Of_Key', `Primary_Key2` = 'Value_Of_Key2'
     * @return string  
     */
    public function Return_PRIMARY_KEY_Equals()
    {
        $string_to_return = "`";
        ForEach($this->primary_key_or_keys as $column_name => $primary_key)
        {
            if(!is_null($primary_key->Get_Field_Value()))
            {
                $string_to_return = $string_to_return.$primary_key->Get_Field_Name()."` = '".$primary_key->Get_Field_Value()."', `";
            }
        }
        return substr($string_to_return,0,strlen($string_to_return)-3);
    }

     /**
     * Formats a string like `Primary_Key` = 'Value_Of_Key' AND `Primary_Key2` = 'Value_Of_Key2'
     * @return string  
     */
    public function Return_PRIMARY_KEY_Equals_AND()
    {
        $string_to_return = "`";
        ForEach($this->primary_key_or_keys as $column_name => $primary_key)
        {
            if(!is_null($primary_key->Get_Field_Value()))
            {
                $string_to_return = $string_to_return.$primary_key->Get_Field_Name()."` = '".$primary_key->Get_Field_Value()."' AND `";
            }
        }
        return substr($string_to_return,0,strlen($string_to_return)-6);
    }

     /**
     * Formats a string like `Primary_Key`, `Primary_Key2`
     * @return string  
     */
    public function Return_PRIMARY_KEY_For_SQL_SELECT_Statement()
    {
        $string_to_return = "`";
        ForEach($this->primary_key_or_keys as $column_name => $primary_key)
        {
            if(!is_null($primary_key->Get_Field_Name()))
            {
                $string_to_return = $string_to_return.$primary_key->Get_Field_Name()."`, `";
            }
        }
        return substr($string_to_return,0,strlen($string_to_return)-3);
    }
    /**
     * Checks to make sure that either the primary key is only
     * a auto_increment value and is currently blank OR
     * the primary key column or columns have been properly set
     */
    public function Are_Properly_Set()
    {
        $is_properly_set = true;
        ForEach($this->primary_key_or_keys as $key_name => $primary_key)
        {
            if(!$this->Does_Primary_Key_Auto_Increment($primary_key))
            {
                if(!$this->Is_This_Primary_Key_Set($key_name))
                {
                    $is_properly_set = false;
                }
            }
        }
        return $is_properly_set;
    }

    /**
     * Checks to make sure the primary key(s) have been properly set for 
     * updating the row in the database.
     */
    public function Are_Ready_For_Update()
    {
        $is_properly_set = true;
        ForEach($this->primary_key_or_keys as $key_name => $primary_key)
        {
            if(!$this->Is_Primary_Key_Ready_For_Update($key_name))
            {
                $is_properly_set = false;
            }
        }
        return $is_properly_set;
    }

    private function Does_Primary_Key_Auto_Increment($primary_key)
    {
        if($primary_key->Do_I_Auto_Increment())
        {
            return true;
        }else
        {
            return false;
        }
    }

    private function Is_Primary_Key_Ready_For_Update($primary_key)
    {
        return $this->primary_key_or_keys[$primary_key]->Am_I_Ready_To_Update();
    }

    private function Is_This_Primary_Key_Set($primary_key)
    {
        if(is_null($this->primary_key_or_keys[$primary_key]->Get_Field_Value()))
        {
            return false;
        }else
        {
            return true;
        }
    }
    private function Build_Primary_Key_Columns()
    {
        $this->primary_key_or_keys = array();
        $primary_key_columns = $this->dblink->ExecuteSQLQuery("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '".$this->table_name."' AND COLUMN_KEY = 'PRI'");
        while($column = mysqli_fetch_assoc($primary_key_columns))
        {
            $this->primary_key_or_keys[$column['COLUMN_NAME']] = new PrimaryKey($this->dblink,$column['COLUMN_NAME'],$this->table_name);
        }
    }
}

?>