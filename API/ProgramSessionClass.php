<?php declare(strict_types=1);
namespace API;

use Active_Record\Active_Record;
use Active_Record\Object_Is_Already_Loaded;
use Active_Record\Object_Is_Currently_Inactive;
use Active_Record\Active_Record_Object_Failed_To_Load;
use Company\Company;
use API\Session_Not_Established;

class Program_Session extends Active_Record implements \Authentication\iUser
{
    public $_table = "Programs_Have_Sessions";
    function __construct()
    {
        $toolbelt = new \Test_Tools\toolbelt;
        $toolbelt->active_record_relationship_manager->Load_Table_Key_Has_Many_If_Empty($toolbelt->Programs_Have_Sessions,$toolbelt->Users_Have_Roles,$toolbelt->Programs_Have_Sessions->Get_Column('user_id'),$toolbelt->Users_Have_Roles->Get_Column('user_id'),'\Authentication\User_Role');
        parent::__construct();
    }
    /**
     * @throws Active_Record_Object_Failed_To_Load
     * @throws Object_Is_Already_Loaded
     */
    public function Load_Session_By_Access_Token(string $access_token) : void
    {
        $this->Load_From_Varchar('access_token',$access_token);
    }
    /**
     * @throws \Active_Record\Object_Is_Already_Loaded
     * @throws \Active_Record\Varchar_Too_Long_To_Set if client_id, username or password are too long
     * @throws \Authentication\Incorrect_Password
     * @throws \Authentication\User_Does_Not_Exist
     * @throws \Active_Record\Object_Is_Currently_Inactive
     * @throws \Active_Record\Update_Failed If client id does not exist
     * 
     */
    public function Create_New_Session(string $client_id,Company $company, string $username, string $password,bool $only_if_user_is_active = true) : void
    {
        if($this->Is_Loaded())
        {
            throw new Object_Is_Already_Loaded("Program Session is already loaded");
        }
        $User = new \Authentication\User($username,$password,$company,false,$only_if_user_is_active);
        try
        {
            $this->Load_From_Multiple_Vars(array(array('client_id',$client_id),array('user_id',$User->Get_Verified_ID())));
            $dateTime = new \DateTime(gmdate('Y-m-d H:i:s',strtotime('+'.$User->Companies->Get_Session_Time_Limit()." seconds")));
            $this->Set_Varchar($this->table_dblink->Get_Column('access_token'),Generate_CSPRNG(45),false,false);
            $this->Set_Timestamp($this->table_dblink->Get_Column('experation_timestamp'),$dateTime,true);
        } catch (Active_Record_Object_Failed_To_Load $e)
        {
            $this->Set_Varchar($this->table_dblink->Get_Column('client_id'),$client_id,false,false);
            $this->Set_Varchar($this->table_dblink->Get_Column('access_token'),Generate_CSPRNG(45),false,false);
            $dateTime = new \DateTime(gmdate('Y-m-d H:i:s',strtotime('+'.$User->Companies->Get_Session_Time_Limit()." seconds")));
            $this->Set_Timestamp($this->table_dblink->Get_Column('experation_timestamp'),$dateTime,false);
            $this->Set_Int($this->table_dblink->Get_Column('user_id'),$User->Get_Verified_ID(),true);
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
            throw new Session_Not_Established('sorry there is no session to revoke');
        }
        $dateTime = new \DateTime("5 minutes ago");
        $this->Set_Timestamp($this->table_dblink->Get_Column('experation_timestamp'),$dateTime,false,true);
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Get_Access_Token() : string
    {
        return $this->Get_Value_From_Name('access_token');        
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Get_User_ID() : string
    {
        return $this->Get_Value_From_Name('user_id');
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Get_Username() : string
    {
        $toolbelt = new \Test_Tools\toolbelt;
        $toolbelt->Users->Query_Single_Table(['username'],false,"WHERE `id` = '".$this->Get_User_ID()."'");
        $username = $toolbelt->Users->Get_Queried_Data();
        return $username['username'];
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Get_Experation() : \DateTime
    {
        $datetime = new \DateTime($this->Get_Value_From_Name('experation_timestamp'));
        return $datetime;
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Get_Client_ID() : string
    {
        return $this->Get_Value_From_Name('client_id');
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Is_Expired() : bool
    {
        if(gmdate('Y-m-d H:i:s') > date('Y-m-d H:i:s',strtotime($this->Get_Experation()->format('Y-m-d H:i:s'))))
        {
            return true;
        }else
        {
            return false;
        }
    }
}

?>