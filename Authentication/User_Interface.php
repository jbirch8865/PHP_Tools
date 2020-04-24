<?php
namespace app\Helpers;
interface iUser 
{
    /**
     * @throws \DatabaseLink\Column_Does_Not_Exist if column id isn't present
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Get_User_ID() : int;
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded — for company_role
     * @throws \Active_Record\Object_Has_Not_Been_Loaded — for user
     * @throws \Active_Record\UpdateFailed if role already assigned
     */

    public function Assign_Company_Role(\app\Helpers\Company_Role $company_role) : void;
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded — for user and company_role
     * @throws Active_Record_Object_Failed_To_Load
     */

    public function Remove_Company_Role(\app\Helpers\Company_Role $company_role) : void;
    public function Get_Verified_ID() : int;
}

?>