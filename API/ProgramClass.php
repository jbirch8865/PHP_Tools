<?php declare(strict_types=1);
namespace API;

use Active_Record\Active_Record;
class Program extends Active_Record
{
    public $_table = "Programs";

    function __construct()
    {
        parent::__construct();
    }
    /**
     * @throws Active_Record_Object_Failed_To_Load
     */
    public function Load_Program_By_Name(string $program_name) : void
    {
        $this->Load_From_Varchar('program_name',$program_name);
    }
    /**
     * @throws Active_Record_Object_Failed_To_Load
     */
    public function Load_Program_By_Secret(string $secret) : void
    {
        $this->Load_From_Varchar('secret',$secret);
    }
    /**
     * @throws Active_Record_Object_Failed_To_Load
     */
    public function Load_Program_By_ID(int $id) : void
    {
        $this->Load_From_Int('id',$id);
    }
    public function Create_Project(string $program_name) : void
    {
        if($this->Is_Loaded())
        {
            throw new \Active_Record\Object_Is_Already_Loaded($this->Get_Value_From_Name('program_name'));
        }
        $this->Set_Varchar('program_name',$program_name,true,false);
        $this->Set_Varchar('secret',Generate_CSPRNG(48),false,false);
        $this->Set_Varchar('client_id',Generate_CSPRNG(32),false,true);
    }
    /**
     * @throws SQLQueryError
     */
    public function Change_Primary_Key(int $new_key,int $old_key) : void
    {
        parent::Change_Primary_Key($new_key,$old_key);
    }
    public function Set_Program_Name(string $program_name) : void
    {
        $this->Set_Varchar('program_name',$program_name);
    }
    public function Delete_Program() : void
    {
        parent::Delete_Object('destroy');
    }
    public function Get_Secret() : string
    {
        return $this->Get_Value_From_Name('secret');
    }
    public function Get_Program_Name() : string
    {
        return $this->Get_Value_From_Name('program_name');
    }
    public function Get_Client_ID() : string
    {
        return $this->Get_Value_From_Name('client_id');
    }
    /**
     * This table doesn't have active status, delete and create are the only options
     * this function does nothing
     */
    public function Set_Object_Active() : void
    {
    }
    /**
     * This table doesn't have active status, delete and create are the only options
     * this function does nothing
     */
    public function Set_Object_Inactive() : void
    {
    }
    /**
     * This table doesn't have active status, delete and create are the only options
     * this function does nothing
     */
    public function Is_Object_Active() : bool
    {  
        return false;
    }

}

?>