<?php declare(strict_types=1);
/**
 * @property string $table_name
 * @property \DatabaseLink\Table $table_dblink
 * @property \config\ConfigurationFile $cConfigs
 */
interface Active_Record_Object
{
    public function Update_Object() : void;
    public function Create_Object() : void;
    /**
	 * This will delete the copmany from the table
	 * @param string $password since this is such a destructive public function you need to enter "destroy" as the password in order for this to execute
	 * Since the record will be deleted recomend unsetting this object immediately after deleting.  *Note you may want to use Set_Object_Inactive instead
     * This will unset all properties on this object.
	 */
    public function Delete_Object(string $password) : void;
    public function Set_Object_Inactive() : void;
    public function Set_Object_Active() : void;
    public function Is_Object_Active() : bool;
}

?>