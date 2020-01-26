<?php

class DatabaseTest extends \PHPUnit\Framework\TestCase
{
	private $dblink;
    private $folder_name;
	public function setUp() :void
	{
        try
        {
            global $root_folder;
            $this->folder_name = $root_folder;
            $cConfigs = new \config\ConfigurationFile();
            $this->dblink = new DatabaseLink\MySQLLink($cConfigs->Get_Name_Of_Project_Database($this->folder_name));
        }catch(\DatabaseLink\SQLConnectionError $e)
        {
            $this->assertEquals("Failed connecting to the sql database",false);
        }
	}

	function test_Execute_SQL_Query()
	{    
        $tables = $this->dblink->Execute_Any_SQL_Query('SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = `'.$this->folder_name.'` AND table_name = `Users`');
        $does_user_table_exist = mysqli_fetch_assoc($tables);
        if($does_user_table_exist['COUNT(*)'] == '1')
        {
            Build_User_Table();
        }else
        {
            $query = $this->dblink->Execute_Any_SQL_Query("SHOW COLUMNS FROM `Users` LIKE 'password'");
            $this->assertEquals('1',mysqli_num_rows($query));
        }
    }
    

}

?>