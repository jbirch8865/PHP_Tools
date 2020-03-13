<?php
namespace project_tags;

abstract class Tag
{
    private string $tag_title;
    private int $verified_tag_id;
    public $icon; 
    private $dblink;

    function __construct(?int $unverified_tag_id = NULL,?string $new_tag_title = "")
    {
        global $dblink;
        $this->dblink = $dblink;
        if(!empty($unverified_tag_id))
        {
            $this->Load_Properties($unverified_tag_id);
        }else
        {
        }
    }

    private function Load_Properties($unverified_tag_id)
    {
        if($this->Verify_Tag_ID($unverified_tag_id))
        {
            $this->Populate_Tag_Properties();
        }else
        {
            throw new Tag_Does_Not_Exist("The unverified_tag_id does not coorospond to a verified_tag_id");
        }
    }

    private function Populate_Tag_Properties()
    {
        if(!is_null($this->verified_tag_id))
        {
            $results = $this->dblink->ExecuteSQLQuery("SELECT * FROM `Tags` WHERE `tag_id` = '".$this->verified_tag_id."'");
            while($row = mysqli_fetch_assoc($results))
            {
                $this->name = $row['Name'];
                $this->tag_type_id = $row['tag_type'];
                $this->icon = new \bootstrap\icon($row['icon']);
            }
        }else
        {
            throw new \Exception("Trying to Load_Properties before giving a unique id");
        }
    }

    public function Verify_Tag_ID($id_to_verify)
    {
        if($this->Does_Tag_Exist($id_to_verify))
        {
            $this->verified_tag_id = $id_to_verify;
            return true;
        }else
        {
            $this->verified_tag_id = null;
            return false;
        }
    }

    public function Does_Tag_Exist($unverified_tag_id)
    {
        try
        {
            $results = $this->dblink->ExecuteSQLQuery("SELECT * FROM `Tags` WHERE `tag_id` = '".$unverified_tag_id."'");
            if(mysqli_num_rows($results) == 1)
            {
                return true;
            }else
            {
                return false;
            }
        } catch (\Exception $e)
        {
            $log_exception = new \logging\Log_To_Console($e->getMessage());
            return false;
        }               
    }

    public function Get_Tag_Name()
    {
        return $this->name;
    }

    public function Get_Tag_ID()
    {
        return $this->verified_tag_id;
    }

    public function Get_Type_ID()
    {
        return $this->tag_type_id;
    }
    
    public function Set_Tag_Name($name)
    {
        if($name == ""){return false;}
        $this->name = $name;
        $this->Update_Tag();
    }

    public function Set_Tag_Type($tag_type_id)
    {
        $this->tag_type_id = $tag_type_id;
    }

    private function Update_Tag()
    {
        if(is_null($this->verified_tag_id)){ return false;}
        $name = mysqli_real_escape_string($this->dblink->GetCurrentLink(),$this->Get_Tag_Name());
        return $this->dblink->ExecuteSQLQuery("UPDATE `Tags` SET `Name` = '".$name."', `tag_type` = '".$this->tag_type_id."' WHERE `tag_id` = '".$this->verified_tag_id."'");
    }

    public function Create_Tag($name = NULL)
    {
        if(is_null($this->verified_tag_id) && !empty($name))
        {
            $this->Set_Tag_Name($name);
            $name = mysqli_real_escape_string($this->dblink->GetCurrentLink(),$this->Get_Tag_Name());
            if($this->dblink->ExecuteSQLQuery("INSERT INTO `Tags` SET `Name` = '".$name."', `tag_type` = '".$this->tag_type_id."'"))
            {
                return $this->Verify_Tag_ID($this->dblink->GetLastInsertID());
            }else
            {
                return false;
            }
        }else
        {
            return false;
        }
    }

    public function Delete_Tag()
    {
        if(!is_null($this->verified_tag_id))
        {
            if($this->dblink->ExecuteSQLQuery("UPDATE `Tags` SET `Active_Status` = '0' WHERE `tag_id` = '".$this->verified_tag_id."'"))
            {
                return true;
            }else
            {
                return false;
            }
        }else
        {
            return false;
        }
    }
}
?>