<?php

class IconTest extends \PHPUnit\Framework\TestCase
{
	private $dblink;
    private $folder_name;
	public function setUp() :void
	{
        try
        {
            global $root_folder;
            $this->folder_name = $root_folder;
            global $cConfigs;
            $this->dblink = new DatabaseLink\MySQLLink($cConfigs->Get_Name_Of_Project_Database($this->folder_name));
        }catch(\DatabaseLink\SQLConnectionError $e)
        {
            $this->assertEquals("Failed connecting to the sql database",false);
        }
	}

    function test_Plus_Icon_Exists()
	{    
        $this->assertTrue($this->dblink->Execute_Any_SQL_Query('SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = \''.$this->folder_name.'\' AND table_name = \'Icon_Library\''));
        $this->assertIsArray($row = $this->dblink->Get_First_Row());
        if($row['COUNT(*)'] == '0')
        {
            Build_Icon_Table();
        }else
        {
            $this->assertTrue($this->dblink->Execute_Any_SQL_Query('SELECT `icon_id` FROM `Icon_Library` WHERE `icon_id` = \'1\''));
            $this->assertIsArray($row = $this->dblink->Get_First_Row());
            $this->assertEquals("1",$row['icon_id']);
        }
    }
}

?>