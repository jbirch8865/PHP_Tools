<?php

use DatabaseLink\SQLQueryError;

class DatabaseTest extends \PHPUnit\Framework\TestCase
{
    private \DatabaseLink\Database $dblink;
    private \DatabaseLink\MySQLLink $root_dblink;
    public \config\ConfigurationFile $cConfigs;

	public function setUp() :void
	{
        global $toolbelt;
        $this->cConfigs = $toolbelt->cConfigs;
        $this->dblink = $toolbelt->dblink;
        $this->root_dblink = $toolbelt->root_dblink;
    }
    
    public function tearDown() :void
    {

    }
    function test_Create_Database_If_Does_Not_Exist()
    {   
        $this->root_dblink->Execute_Any_SQL_Query("SHOW DATABASES WHERE `Database` = 'not_a_real_database'");
        if($this->root_dblink->Get_Num_Of_Rows())
        {
            $new_database = new \DatabaseLink\Database('not_a_real_database');
            $new_database->Drop_Database_And_User("destroy");            
        }
        $new_database = new \DatabaseLink\Database('not_a_real_database');
        $this->root_dblink->Execute_Any_SQL_Query("SHOW DATABASES WHERE `Database` = 'not_a_real_database'");
        $this->assertEquals('1',$this->root_dblink->Get_Num_Of_Rows());        
    }
    function test_Establish_Connection_On_Existing_Database_Without_Tables()
    {
        $new_database = new \DatabaseLink\Database('not_a_real_database',false);
        $this->assertEquals('not_a_real_database',$new_database->Get_Database_Name());
    }
    function test_Create_3_Tables()
    {
        $new_database = new \DatabaseLink\Database('not_a_real_database');
        $new_table = new \DatabaseLink\Table('not_a_real_table',$new_database);
        $this->assertEquals('not_a_real_table',$new_table->Get_Table_Name());
        $new_table = new \DatabaseLink\Table('not_a_real_table2',$new_database);
        $this->assertEquals('not_a_real_table2',$new_table->Get_Table_Name());
        $new_table = new \DatabaseLink\Table('not_a_real_table3',$new_database);
        $this->assertEquals('not_a_real_table3',$new_table->Get_Table_Name());
    }
    function test_Fail_Creating_Tables_With_Read_Only_User()
    {
        $this->expectException(\DatabaseLink\SQLQueryError::class);
        $new_database = new \DatabaseLink\Database('not_a_real_database',false);
        $new_table = new \DatabaseLink\Table('not_a_real_table4',$new_database);
    }
    function test_Establish_Connection_On_Existing_Database_With_Tables()
    {
        $new_database = new \DatabaseLink\Database('not_a_real_database',false);
        $this->assertEquals('3',$new_database->Get_Number_Of_Tables());
    }
    function test_Get_Tables_From_Database_Connection()
    {
        $new_database = new \DatabaseLink\Database('not_a_real_database',false);
        While($table = $new_database->Get_Tables())
        {
            $this->assertIsString($table->Get_Table_Name());
        }
    }
    function test_Reset_Tables()
    {
        $new_database = new \DatabaseLink\Database('not_a_real_database',false);
        While($table = $new_database->Get_Tables())
        {
            $this->assertIsString($table->Get_Table_Name());
        }
        $new_database->Reset_Tables();
        $table = $new_database->Get_Tables();
        $this->assertEquals('not_a_real_table',$table->Get_Table_Name());
    }
    function test_Clean_Up()
    {
        $new_database = new \DatabaseLink\Database('not_a_real_database');
        $new_database->Drop_Database_And_User('destroy');
        $this->root_dblink->Execute_Any_SQL_Query("SHOW DATABASES WHERE `Database` = 'not_a_real_database'");
        $this->assertFalse((bool) $this->root_dblink->Get_Num_Of_Rows());
    }

}

?>