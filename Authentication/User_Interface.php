<?php
namespace Authentication;
interface iUser 
{
    /**
     * @throws \DatabaseLink\Column_Does_Not_Exist if column id isn't present
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Get_User_ID() : int;

    public function Assign_Company_Role(\Company\Company_Role $company_role) : void;

    public function Remove_Company_Role(\Company\Company_Role $company_role) : void;
}

?>