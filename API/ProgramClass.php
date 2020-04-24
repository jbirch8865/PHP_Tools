<?php declare(strict_types=1);
namespace app\Helpers;

use Active_Record\Object_Is_Already_Loaded;
use Active_Record\Active_Record;
use Active_Record\iActiveRecord;

class Program extends Active_Record implements iActiveRecord
{
    public $_table = "Programs";

    function __construct()
    {
        parent::__construct();
    }
    /**
     * @throws Active_Record_Object_Failed_To_Load
     * @throws Object_Is_Already_Loaded
     */
    public function Load_Program_By_Name(string $program_name) : void
    {
        $this->Load_From_Varchar('program_name',$program_name);
    }
    /**
     * @throws Active_Record_Object_Failed_To_Load
     * @throws Object_Is_Already_Loaded
     */
    public function Load_Program_By_Client_ID(string $client_id) : void
    {
        $this->Load_From_Varchar('client_id',$client_id);
    }
    /**
     * @throws Active_Record_Object_Failed_To_Load
     * @throws Object_Is_Already_Loaded
     */
    public function Load_Object_By_ID(int $id) : void
    {
        $this->Load_From_Int('id',$id);
    }
    /**
     * @throws Object_Is_Already_Loaded
     */
    public function Create_Project(string $program_name) : void
    {
        if($this->Is_Loaded())
        {
            throw new Object_Is_Already_Loaded($this->Get_Value_From_Name('program_name'));
        }
        $this->Set_Varchar($this->table_dblink->Get_Column('program_name'),$program_name,true,false);
        $this->Set_Varchar($this->table_dblink->Get_Column('secret'),Generate_CSPRNG(48),false,false);
        $this->Set_Varchar($this->table_dblink->Get_Column('client_id'),Generate_CSPRNG(32),false,true);
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
        $this->Set_Varchar($this->table_dblink->Get_Column('program_name'),$program_name);
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Delete_Program() : void
    {
        parent::Delete_Object('destroy');
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Get_Secret() : string
    {
        return $this->Get_Value_From_Name('secret');
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Get_Friendly_Name() : string
    {
        return $this->Get_Value_From_Name('program_name');
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Get_Client_ID() : string
    {
        return $this->Get_Value_From_Name('client_id');
    }
    /**
     * @throws Active_Record_Object_Failed_To_Load
     * @throws Object_Is_Already_Loaded
     */
    public function Load_By_Friendly_Name(string $friendly_name,?\Active_Record\Active_Record $object = null): void
    {
        $this->Load_From_Varchar('program_name',$friendly_name);
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

    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    function Get_API_Response_Collection(): array
    {
        return $this->Get_Response_Collection(app()->request->input('include_details',0),app()->request->input('details_offset',0),app()->request->input('details_limit',1));
    }
    public function Delete_Active_Record() : void
    {
        app()->request->validate([
            'active_status' => ['required','bool']
        ]);

    }


}

?>