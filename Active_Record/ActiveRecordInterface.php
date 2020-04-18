<?php
namespace Active_Record;
interface iActiveRecord
{
    /**
     * @throws Object_Is_Already_Loaded
     * @throws \Active_Record\Active_Record_Object_Failed_To_Load if adodb->load method fails
     */
    public function Load_Object_By_ID(int $object_id) : void;

    public function Get_Friendly_Name() : ?string;

}

?>
