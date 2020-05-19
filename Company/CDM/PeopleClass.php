<?php declare(strict_types=1);
namespace app\Helpers;

use Active_Record\Active_Record;
use Active_Record\Email_Address_Not_Valid;
use Active_Record\iActiveRecord;

class People extends Active_Record implements iActiveRecord
{
    public $_table = "Peoples";
    function __construct()
    {
        parent::__construct();
        global $toolbelt_base;
        $toolbelt_base->active_record_relationship_manager->Load_Table_Belongs_To_If_Empty($this->table_dblink,$this->table_dblink->Get_Column('company_id'),$toolbelt_base->Companies,$toolbelt_base->Companies->Get_Column('id'),'\app\Helpers\Company',true);
    }
    public function Get_Companies() : Company
    {
        $this->Companies;
        return $this->Companies;
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Get_Friendly_Name() : string
    {
        return $this->Get_Value_From_Name('first_name').' '.$this->Get_Value_From_Name('last_name');
    }
    /**
     * @throws Exception always for this class
     */
    public function Load_By_Friendly_Name(string $friendly_name,?\Active_Record\Active_Record $object = null): void
    {
        throw new \Exception('This doesn\'t work with People');
    }

    /**
     * @throws \Active_Record\Varchar_Too_Long_To_Set if string too long and trim is false
     */
    public function Set_First_Name(string $first_name,bool $trim_if_too_long = true,bool $update_immediately = true) : void
    {
        $this->Set_Varchar($this->table_dblink->Get_Column('first_name'),$first_name,$trim_if_too_long,$update_immediately);
    }
    /**
     * @throws \Active_Record\Varchar_Too_Long_To_Set if string too long and trim is false
     */
    public function Set_Last_Name(string $last_name,bool $trim_if_too_long = true,bool $update_immediately = true) : void
    {
        $this->Set_Varchar($this->table_dblink->Get_Column('last_name'),$last_name,$trim_if_too_long,$update_immediately);
    }
    /**
     * @throws \Active_Record\Varchar_Too_Long_To_Set if string too long and trim is false
     */
    public function Set_Title(string $title,bool $trim_if_too_long = true,bool $update_immediately = true) : void
    {
        $this->Set_Varchar($this->table_dblink->Get_Column('title'),$title,$trim_if_too_long,$update_immediately);
    }
    /**
     * @throws \Active_Record\Varchar_Too_Long_To_Set if string too long and trim is false
     */
    public function Set_Description(string $description,bool $trim_if_too_long = true,bool $update_immediately = true) : void
    {
        $this->Set_Varchar($this->table_dblink->Get_Column('description'),$description,$trim_if_too_long,$update_immediately);
    }
    /**
     * @throws \Active_Record\Varchar_Too_Long_To_Set if string too long and trim is false
     * @throws \Active_Record\Email_Address_Not_Valid if email address is not valid
     */
    public function Set_Email(string $email,bool $update_immediately = true,bool $send_response_on_failure = true) : void
    {
        $this->toolbelt->functions->Validate_Email($email,$send_response_on_failure);
        $this->Set_Varchar($this->table_dblink->Get_Column('email'),$email,false,$update_immediately);
    }
    /**
     * @throws \Active_Record\Varchar_Too_Long_To_Set if string too long and trim is false
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Set_Company(Company $company,bool $update_immediately = true) : void
    {
        $this->Set_Int($this->table_dblink->Get_Column('company_id'),$company->Get_Verified_ID(),$update_immediately);
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Get_First_Name() : string
    {
        return $this->Get_Value_From_Name('first_name');
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Get_Last_Name() : string
    {
        return $this->Get_Value_From_Name('last_name');
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Get_Title() : string
    {
        return $this->Get_Value_From_Name('title');
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Get_Email() : string
    {
        return $this->Get_Value_From_Name('email');
    }
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Get_Description() : string
    {
        return $this->Get_Value_From_Name('description');
    }
}
?>
