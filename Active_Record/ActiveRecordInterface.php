<?php
namespace Active_Record;
interface iActiveRecord
{
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    public function Get_Friendly_Name() : ?string;
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded for $object if variable name is not $object then it is required for the function to work
     * @throws \Active_Record\Object_Is_Already_Loaded
     * @throws Active_Record_Object_Failed_To_Load
     */
    public function Load_By_Friendly_Name(string $friendly_name,?\Active_Record\Active_Record $object = null) : void;
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */

    public function Delete_Active_Record() : void;


}

?>
