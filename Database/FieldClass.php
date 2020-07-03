<?php
namespace DatabaseLink;
class Field
{
   public $dblink;
   private $data_type;
   private $column_default;
   private $required;
   private $locked;
   private $table_name;
   private $column_name; 
   private $field_value;
   private $field_value_changed;

   function __construct($dblink, $column_name, $table_name)
   {
       $this->table_name = $table_name;
       $this->column_name = $column_name;
       $this->dblink = $dblink;
       $this->field_value_changed = false;
       $this->Load_Column_Parameters();
   }

   /**
    * @param var $value_to_set
    * Does not update if field is currently lock, will return Field_Is_Locked exception
    * Value special characters are escaped for sql injection
    */
   public function Manually_Set_Field_Value($value_to_set)
   {
    if($this->locked)
    {
         throw new Field_Is_Locked("This field is current locked, please use Unlock_Value() prior to setting this value");
    }else
    {
        $this->field_value_changed = true;
        $this->Set_Field_Value($this->Escape_Special_Characters_In_String($value_to_set));
    }
   }
   
   /**
    * @param var $value_to_set
    * Updates Field Value no matter what and doesn't set field_value_changed to true
    * Should only be used when loading a value from the DB use Manually_Set_Field_Value 
    * When setting the field value from a person input.
    */
    public function Set_Field_Value_From_DB($value_to_set)
    {
        $this->Set_Field_Value($this->Escape_Special_Characters_In_String($value_to_set));
    }

    /**
    * @return string 
    * If this value was manually set the returned value will be escaped.
    * If this value was imported from DB the returned value will not be escaped.
    * If this value was imported from DB upon UPDATE this value will not be included
    * 
    */
    public function Get_Field_Value()
   {
       return $this->field_value;
   }

   /**
    * Returns the column name from the table
    * @return string
    */
   public function Get_Field_Name()
   {
       return $this->column_name;
   }

   /**
    * Get the name of the table we are working with
    */
    public function Get_Table_Name()
    {
        return $this->table_name;
    }

   /**
    * Disable Set_Field_Value
    */
   protected function Lock_Value()
   {
       $this->locked = true;
   }
   /**
    * Enable Set_Field_Value
    */
   protected function Unlock_Value()
   {
       $this->locked = false;
   }
   /**
    * Checks to see if the field value has changed since instantiation
    */
   protected function Should_I_Update_Or_Insert_Value()
   {
       return $this->field_value_changed;
   }
   /**
    * If true this field value has to be set in order to update / insert into mysql
    */
   protected function Is_Field_Required()
   {
       if($this->required == 'false')
       {
           return false;
       }else
       {
           return true;
       }
   }

   private function Escape_Special_Characters_In_String($string)
   {
        return mysqli_real_escape_string($this->dblink->GetCurrentLink(),$string);
   }

   private function Query_For_Column_Comments()
   {
       try 
       {
           $query_information_schema = $this->dblink->ExecuteSQLQuery("SELECT COLUMN_KEY, COLUMN_COMMENT, COLUMN_DEFAULT, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '".$this->table_name."' AND COLUMN_NAME = '".$this->column_name."'");
           $query_information_schema = mysqli_fetch_assoc($query_information_schema);
           $query_information_schema['COLUMN_COMMENT'] = json_decode($query_information_schema['COLUMN_COMMENT']);
           return $query_information_schema;
       } catch (\Exception $e)
       {
           throw new \Exception($e->getMessage());
       }
   }
   
   private function Load_Column_Parameters()
   {
       try
       {
           $column_comments = $this->Query_For_Column_Comments();
           $this->required = $column_comments['COLUMN_COMMENT']->required;
           $this->data_type = $column_comments['DATA_TYPE'];
           $this->column_default = $column_comments['COLUMN_DEFAULT'];
       } catch (\Exception $e)
       {
           throw new \Exception($e->getMessage());
       }
   }

   private function Set_Field_Value($value_to_set)
   {
        $this->field_value = $value_to_set;
   }


}

class PrimaryKey Extends Field
{
    private $column_details;
    private $ready_to_update;

    function __construct($dblink, $column_name, $table_name)
    {
        parent::__construct($dblink, $column_name, $table_name);
        $this->Query_For_Column_Comments();
        $this->ready_to_update = false;
        if(!$this->Am_I_A_Primary_Key())
        {
            throw new Not_A_Primary_Key("This column is not a valid primary key");
        }
    }    

    /**
     * This will set the primary key value only if the column is not 
     * set to auto increment
     * @param var $value_to_set
     */
    public function Manually_Set_Field_Value($value_to_set)
    {
        if($this->Do_I_Auto_Increment())
        {
            throw new Primary_Key_Auto_Increments("You cannot set this value as it is automatically incremented in MySQL");
        }else
        {
            parent::Manually_Set_Field_Value($value_to_set);
        }
    }

    /**
     * This will set the primary key value no matter what.  
     * If you are not populating this value from the Database 
     * use Manually_Set_Field_Value()
     * @param var $value_to_set
     */
    public function Set_Field_Value_From_DB($value_to_set)
    {
        parent::Set_Field_Value_From_DB($value_to_set);
        $this->ready_to_update = true;
    }

    public function Do_I_Auto_Increment()
    {
        if($this->column_details['EXTRA'] == 'auto_increment')
        {
            return true;
        }else
        {
            return false;
        }
    }

    public function Am_I_Ready_To_Update()
    {
        return $this->ready_to_update;
    }

    private function Am_I_A_Primary_Key()
    {
        if($this->column_details['COLUMN_KEY'] == 'PRI')
        {
            return true;
        }else
        {
            return false;
        }
    }

    private function Query_For_Column_Comments()
    {
        try 
        {
            $query_information_schema = $this->dblink->ExecuteSQLQuery("SELECT COLUMN_KEY, EXTRA FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '".$this->Get_Table_Name()."' AND COLUMN_NAME = '".$this->Get_Field_Name()."'");
            $this->column_details = mysqli_fetch_assoc($query_information_schema);
        } catch (\Exception $e)
        {
            throw new \Exception($e->getMessage());
        }
    }
}
?>