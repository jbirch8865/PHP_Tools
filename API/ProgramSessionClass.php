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
     * @throws \Active_Record\Object_Is_Already_Loaded
     * @throws \Active_Record\Active_Record_Object_Failed_To_Load
     * @throws \Active_Record\Varchar_Too_Long_To_Set
     * @throws \Authentication\Incorrect_Password
     * @throws \Authentication\User_Does_Not_Exist
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
            $dateTime = new \DateTime(gmdate('Y-m-d H:i:s',strtotime('+'.$User->Companies->Get_Session_Time_Limit()." seconds")));
            $this->Set_Varchar('access_token',Generate_CSPRNG(45),false,false);
            $this->Set_Timestamp('experation_timestamp',$dateTime,false,true);
        } catch (\Active_Record\Active_Record_Object_Failed_To_Load $e)
        {
            $this->Set_Varchar('client_id',$client_id,false,false);
            $this->Set_Varchar('access_token',Generate_CSPRNG(45),false,false);
            $dateTime = new \DateTime(gmdate('Y-m-d H:i:s',strtotime('+'.$User->Companies->Get_Session_Time_Limit()." seconds")));
            $this->Set_Timestamp('experation_timestamp',$dateTime,false,false);
            $this->Set_Int('user_id',$User->Get_Verified_ID(),true);
        }
    }
    /**
     * @throws \API\Session_Not_Established
     * @throws \Active_Record\Update_Failed
     */
    public function Revoke_Session() : void
    {
        if(!$this->Is_Loaded())
        {
            throw new \API\Session_Not_Established('sorry there is no session to revoke');
        }
        $dateTime = new \DateTime("5 minutes ago");
        $this->Set_Timestamp('experation_timestamp',$dateTime,false,true);
    }
    public function Get_Access_Token() : string
    {
        return $this->Get_Value_From_Name('access_token');
    }
    public function Get_User_ID() : string
    {
        return $this->Get_Value_From_Name('user_id');
    }
    public function Get_Experation() : \DateTime
    {
        $datetime = new \DateTime($this->Get_Value_From_Name('experation_timestamp'));
        return $datetime;
    }
    public function Get_Client_ID() : string
    {
        return $this->Get_Value_From_Name('client_id');
    }
    /**
     * @throws \API\Session_Not_Established
     */
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
}

?>