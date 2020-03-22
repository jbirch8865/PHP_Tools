<?php

use DatabaseLink\SQLQueryError;

class TableTest extends \PHPUnit\Framework\TestCase
{
    private \DatabaseLink\Database $database_dblink;
    public \config\ConfigurationFile $cConfigs;

	public function setUp() :void
	{
        global $cConfigs;
        $this->cConfigs = new \config\ConfigurationFile();
$this->cConfigs = &$cConfigs;
        $this->database_dblink = new \DatabaseLink\Database('not_a_real_database');
    }
    
    public function tearDown() :void
    {

    }
    function test_Create_3_Tables_With_Data()
    {
        $new_table = new \DatabaseLink\Table('not_a_real_table',$this->database_dblink);
        $this->assertEquals('not_a_real_table',$new_table->Get_Table_Name());
        $new_table->Insert_Row(array("id" => 1));
        $new_table = new \DatabaseLink\Table('not_a_real_table2',$this->database_dblink);
        $this->assertEquals('not_a_real_table2',$new_table->Get_Table_Name());
        $new_table->Insert_Row(array("id" => 2));
        $new_table = new \DatabaseLink\Table('not_a_real_table3',$this->database_dblink);
        $this->assertEquals('not_a_real_table3',$new_table->Get_Table_Name());
        $new_table->Insert_Row(array("id" => 3));
    }
    function test_Load_Existing_Table()
    {
        $new_table = new \DatabaseLink\Table('not_a_real_table',$this->database_dblink);
        $this->assertEquals('1',$new_table->Get_Number_Of_Rows_In_Table());
    }
    function test_Update_Existing_Data()
    {
        $new_table = new \DatabaseLink\Table('not_a_real_table2',$this->database_dblink);
        $new_table->Update_Row(array('id' => 3));
        $new_table->Query_Single_Table(array(),true);
        While($row = $new_table->Get_Queried_Data())
        {
            $this->assertEquals('3',$row['id']);
        }
    }
    function test_Add_Multiple_Rows()
    {
        $new_table = new \DatabaseLink\Table('not_a_real_table2',$this->database_dblink);
        $new_table->Insert_Row(array('id' => '1'));
        $new_table->Insert_Row(array('id' => '2'));
        $new_table->Insert_Row(array('id' => '4'));
        $new_table->Insert_Row(array('id' => '5'));
        $this->assertEquals('5',$new_table->Get_Number_Of_Rows_In_Table());
    }
    function test_Reset_Query_In_The_Middle_Of_Getting_It()
    {
        $new_table = new \DatabaseLink\Table('not_a_real_table2',$this->database_dblink);
        $new_table->Query_Single_Table(array(),true,"ORDER BY `id` LIMIT 4");
        $this->assertEquals('4',$new_table->Get_Number_Of_Rows_In_Query());
        while($row = $new_table->Get_Queried_Data())
        {
            if($row['id'] == 3)
            {
                break;
            }
        }
        $this->assertEquals(4,$new_table->Get_Queried_Data()['id']);
        $new_table->Reset_Queried_Data();
        $this->assertEquals(1,$new_table->Get_Queried_Data()['id']);
    }
    function test_Delete_Row()
    {
        $new_table = new \DatabaseLink\Table('not_a_real_table2',$this->database_dblink);
        $new_table->Delete_Row("WHERE `id` = '4'");
        $this->assertEquals('4',$new_table->Get_Number_Of_Rows_In_Table());
        $new_table->Delete_Row("",true);
        $this->assertEquals('0',$new_table->Get_Number_Of_Rows_In_Table());
    }
    function test_Delete_Existing_Table()
    {
        $new_table = new \DatabaseLink\Table('not_a_real_table',$this->database_dblink);
        $new_table->Drop_Table("destroy");
        $this->assertTrue(true);
    }
    function test_Clean_Up()
    {
        $this->database_dblink->Drop_Database_And_User('destroy');
        $this->assertTrue(true);
    }

}

?>