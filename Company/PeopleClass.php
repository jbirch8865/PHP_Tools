<?php declare(strict_types=1);
namespace app\Helpers;

use Active_Record\Active_Record;
use Active_Record\Email_Address_Not_Valid;
use Active_Record\iActiveRecord;

abstract class People extends Active_Record implements iActiveRecord
{
    public $_table = "People";
    function __construct()
    {
        parent::__construct();
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
        if(strlen($first_name) > $this->table_dblink->Get_Column('first_name')->Get_Data_Length())
        {
            if($trim_if_too_long)
            {
                $first_name = substr($first_name,0,$this->table_dblink->Get_Column('first_name')->Get_Data_Length());
            }else
            {
                throw new \Active_Record\Varchar_Too_Long_To_Set($first_name." is too long of a name");
            }
        }
        $this->first_name = $first_name;
        if($update_immediately)
        {
            $this->Create_Object();
        }
    }
    /**
     * @throws \Active_Record\Varchar_Too_Long_To_Set if string too long and trim is false
     */
    public function Set_Last_Name(string $last_name,bool $trim_if_too_long = true,bool $update_immediately = true) : void
    {
        if(strlen($last_name) > $this->table_dblink->Get_Column('last_name')->Get_Data_Length())
        {
            if($trim_if_too_long)
            {
                $last_name = substr($last_name,0,$this->table_dblink->Get_Column('last_name')->Get_Data_Length());
            }else
            {
                throw new \Active_Record\Varchar_Too_Long_To_Set($last_name." is too long of a name");
            }
        }
        $this->last_name = $last_name;
        if($update_immediately)
        {
            $this->Create_Object();
        }
    }
    /**
     * @throws \Active_Record\Varchar_Too_Long_To_Set if string too long and trim is false
     */
    public function Set_Title(string $title,bool $trim_if_too_long = true,bool $update_immediately = true) : void
    {
        if(strlen($title) > $this->table_dblink->Get_Column('title')->Get_Data_Length())
        {
            if($trim_if_too_long)
            {
                $title = substr($title,0,$this->table_dblink->Get_Column('title')->Get_Data_Length());
            }else
            {
                throw new \Active_Record\Varchar_Too_Long_To_Set($title." is too long of a name");
            }
        }
        $this->title = $title;
        if($update_immediately)
        {
            $this->Create_Object();
        }
    }
    /**
     * @throws \Active_Record\Varchar_Too_Long_To_Set if string too long and trim is false
     */
    public function Set_Description(string $description,bool $trim_if_too_long = true,bool $update_immediately = true) : void
    {
        if(strlen($description) > $this->table_dblink->Get_Column('description')->Get_Data_Length())
        {
            if($trim_if_too_long)
            {
                $description = substr($description,0,$this->table_dblink->Get_Column('description')->Get_Data_Length());
            }else
            {
                throw new \Active_Record\Varchar_Too_Long_To_Set($description." is too long of a name");
            }
        }
        $this->description = $description;
        if($update_immediately)
        {
            $this->Create_Object();
        }
    }
    /**
     * @throws \Active_Record\Varchar_Too_Long_To_Set if string too long and trim is false
     */
    public function Set_Email(string $email,bool $trim_if_too_long = true,bool $update_immediately = true) : void
    {
        $pattern = '/^(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){255,})(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){65,}@)(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22))(?:\\.(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-+[a-z0-9]+)*\\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-+[a-z0-9]+)*)|(?:\\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\\]))$/iD';
        if (preg_match($pattern, $email) === 0) {
            throw new Email_Address_Not_Valid('Sorry '.$email.' is not a valid email address');
        }
        if(strlen($email) > $this->table_dblink->Get_Column('email')->Get_Data_Length())
        {
            if($trim_if_too_long)
            {
                $email = substr($email,0,$this->table_dblink->Get_Column('email')->Get_Data_Length());
            }else
            {
                throw new \Active_Record\Varchar_Too_Long_To_Set($email." is too long of a name");
            }
        }
        $this->email = $email;
        if($update_immediately)
        {
            $this->Create_Object();
        }
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
    /**
     * @throws UpdateFailed
     */
    public function Create_Object(): bool
    {
        if(parent::Create_Object())
        {
            return true;
        }
        return false;
    }
    public function Delete_Active_Record() : void
    {
        app()->request->validate([
            'active_status' => ['required','bool']
        ]);
        if(app()->request->input('active_status'))
        {
            $this->Set_Object_Inactive();
        }else
        {
            $this->Delete_Object('destroy');
        }
    }

}
?>
