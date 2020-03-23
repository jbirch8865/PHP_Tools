<?php declare(strict_types=1);
namespace Authentication;

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
    public function Load_Config_By_Name(string $config_name) : void
    {
        $this->Load_From_Varchar('config_name',$config_name);
    }
    /**
     * @throws Active_Record_Object_Failed_To_Load
     */
    public function Load_Config_By_ID(int $config_id) : void
    {
        $this->Load_From_Int('id',$config_id);
    }
}

?>