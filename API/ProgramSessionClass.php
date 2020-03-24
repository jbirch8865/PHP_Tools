<?php declare(strict_types=1);
namespace API;

use Active_Record\Active_Record;
class Program_Session extends Active_Record
{
    public $_table = "Programs_Have_Sessions";

    function __construct()
    {
        parent::__construct();
    }
    /**
     * @throws Active_Record_Object_Failed_To_Load
     */
    public function Load_Session_By_Access_Token(string $access_token) : void
    {
        $this->Load_From_Varchar('access_token',$access_token);
    }
    /**
     * @throws Active_Record_Object_Failed_To_Load
     */
    public function Load_Program_By_ID(int $id) : void
    {
        $this->Load_From_Int('id',$id);
    }
    /**
     * @throws \Active_Record\Object_Is_Already_Loaded
     * @throws \Active_Record\Active_Record_Object_Failed_To_Load
     * @throws \Active_Record\Varchar_Too_Long_To_Set
     */
    public function Create_New_Session(string $client_id,string $secret,int $company_id, string $username, string $password) : void
    {
        if($this->Is_Loaded())
        {
            throw new \Active_Record\Object_Is_Already_Loaded("Program Session is already loaded");
        }
        $User = new \Authentication\User($username,$password,$company_id,false);
        try
        {
            $this->Load_From_Multiple_Vars(array(array('client_id',$client_id),array('user_id',$User->Get_Verified_ID())));
        } catch (\Active_Record\Active_Record_Object_Failed_To_Load $e)
        {
            $this->Set_Varchar('client_id',$client_id,false,false);
            $this->Set_Varchar('access_token',Generate_CSPRNG(45),false,false);
            $dateTime = new \DateTime(date('Y-m-d H:i',strtotime('+'.$User->Companies->Get_Session_Time_Limit()." minutes")));
            $this->Set_Timestamp('experation_timestamp',$dateTime,false,false);
            $this->Set_Int('user_id',$User->Get_Verified_ID(),true);
        }
    }
    public function Revoke_Session()
    {
        if(!$this->Is_Loaded())
        {
            throw new \API\Session_Not_Established('sorry there is no session to revoke');
        }
        $dateTime = new \DateTime("5 minutes ago");
        $this->Set_Timestamp('experation_timestamp',$dateTime,false,true);
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
    public function Is_Expired() : bool
    {
        if(!$this->Is_Loaded())
        {
            throw new \API\Session_Not_Established('no session established');
        }
        if(gmdate('Y-m-d H:i:s') > date('Y-m-d H:i:s',strtotime($this->experation_timestamp)))
        {
            return true;
        }else
        {
            return false;
        }
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