<?php declare(strict_types=1);
namespace Active_Record;

use Active_Record_Object;
use ADODB_Active_Record;
abstract class Active_Record extends ADODB_Active_Record
{
    protected \config\ConfigurationFile $cConfigs;
    protected \DatabaseLink\Table $table_dblink;

    function __construct(string $table_name)
    {
        parent::__construct($table_name);
        global $cConfigs;
        $this->cConfigs = $cConfigs;
        global $dblink;
        $this->table_dblink = new \DatabaseLink\Table($this->Get_Table_Name(),$dblink);
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
    private function Load_Object(string $column_name,$var_to_search) : void
    {
        if($this->Is_Loaded())
        {
            throw new Object_Is_Already_Loaded("This active record object has already been loaded from table ".$this->Get_Table_Name()." with id of ".$this->Get_Verified_ID());
        }
        if(!$this->table_dblink->Does_Column_Exist($column_name))
        {
            throw new \DatabaseLink\Column_Does_Not_Exist($column_name." does not exist in table ".$this->Get_Table_Name());
        }
        if(!$this->load('`'.$column_name.'`=\''.$var_to_search."'"))
        {
            throw new Active_Record_Object_Failed_To_Load("Failed loading ".$var_to_search." from column ".$column_name." in table ".$this->Get_Table_Name());
        }
    }
    private function Is_Loaded():bool
    {
        if(is_null($this->Get_Verified_ID()))
        {
            return false;
        }else
        {
            return true;
        }
    }
    /**
     * @throws \DatabaseLink\Column_Does_Not_Exist if column id isn't present
     */
    public function Get_Verified_ID() : ?string
    {
        return $this->Get_Value_From_Name('id');
    }
    /**
     * @throws \DatabaseLink\Column_Does_Not_Exist
     */
    protected function Get_Value_From_Name(string $column_name) : ?string
    {
        if($this->table_dblink->Does_Column_Exist($column_name))
        {
            return $this->$column_name;
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
            throw new Varchar_Too_Long_To_Set($value_to_set." is too long of a string for column ".$column_name." in table ".$this->Get_Table_Name());
        }
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
        return $this->active_status;
    }
    /**
     * @throws UpdateFailed if adodb->save method fails
     */
    public function Create_Object() : void
    {
        $this->Set_Object_Active();
        $this->Update_Object();
    }
    /**
     * @throws UpdateFailed if adodb->save method fails
     */
    public function Update_Object() : void
    {
        if(!$this->save())
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
     * @throws \DatabaseLink\SQLQueryError
     */
    protected function Change_Primary_Key(int $new_key,int $old_key) : void
    {
        $this->table_dblink->database_dblink->dblink->Execute_Any_SQL_Query("UPDATE `".$this->Get_Table_Name()."` SET `id` = ? WHERE `id` = ?",array($new_key,$old_key));
    }
}
?>