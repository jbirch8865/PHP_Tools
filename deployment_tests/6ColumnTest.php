<?php declare(strict_types=1);

use DatabaseLink\SQLQueryError;

class ColumnTest extends \PHPUnit\Framework\TestCase
{
    private \DatabaseLink\Table $table_dblink;
    public \config\ConfigurationFile $cConfigs;

	public function setUp() :void
	{
        global $toolbelt;
        $this->cConfigs = $toolbelt->cConfigs;
        $database_dblink = new \DatabaseLink\Database('not_a_real_database');
        $this->table_dblink = new \DatabaseLink\Table('auto_increment_table',$database_dblink);
    }
    
    public function tearDown() :void
    {

    }

    function test_Auto_Increment()
    {
        $this->table_dblink->Create_Column('name',array('column_type' => 'varchar(35)','CHARACTER_MAXIMUM_LENGTH' => 35,'COLUMN_DEFAULT' => "''","is_nullable" => false,"COLUMN_KEY" => "","EXTRA" => ""));
        $this->table_dblink->Insert_Row(array('name' => 'something'));
        $this->table_dblink->Query_Single_Table(array('id','name'),false,"WHERE `id` = 1");
        while($row = $this->table_dblink->Get_Queried_Data())
        {
            $this->assertEquals('something',$row['name']);
        }
    }
    function test_Construct_Existing_Column()
    {
        while($existing_column = $this->table_dblink->Get_Columns())
        {
            if($existing_column->Get_Column_Name() == 'name')
            {
                $this->assertEquals('varchar(35)',$existing_column->Get_Data_Type());
            }
        }
    }
    function test_Delete_Column()
    {
        $column_exists = false;
        $this->table_dblink->Create_Column('new_column',array('column_type' => 'varchar(35)','CHARACTER_MAXIMUM_LENGTH' => 35,'COLUMN_DEFAULT' => "''","is_nullable" => false,"COLUMN_KEY" => "","EXTRA" => ""));
        While($column = $this->table_dblink->Get_Columns())
        {
            if($column->Get_Column_Name() == 'new_column')
            {
                $column_exists = true;
            }
        }
        $this->assertTrue($column_exists);
        $this->table_dblink->Delete_Column('new_column');
        $column_exists = false;
        While($column = $this->table_dblink->Get_Columns())
        {
            if($column->Get_Column_Name() == 'new_column')
            {
                $column_exists = true;
            }
        }
        $this->assertFalse($column_exists);
    }
    function test_Change_Column_Properties()
    {
        $this->table_dblink->Create_Column('new_column',array('column_type' => 'varchar(35)','CHARACTER_MAXIMUM_LENGTH' => 35,'COLUMN_DEFAULT' => "''","is_nullable" => false,"COLUMN_KEY" => "","EXTRA" => ""));
        $column = $this->table_dblink->Get_Column('new_column');
        $column->Set_Default_Value('new_default');
        $test_column = $this->table_dblink->Get_Column('new_column');
        $this->assertEquals('new_default',$test_column->Get_Default_Value());
        $column->Set_Default_Value("null");
        $test_column = $this->table_dblink->Get_Column('new_column');
        $this->assertEquals("NULL",$test_column->Get_Default_Value());
        $this->table_dblink->Delete_Row("",true);
        $column->Set_Default_Value(null);
        $column->Set_Data_Type('INT(11)');
        $this->assertEquals("INT(11)",$column->Get_Data_Type());
        $new_table = new \DatabaseLink\Table('new_table',$this->table_dblink->database_dblink);
        $new_table->Create_Column('new_column',array('COLUMN_TYPE' => 'varchar(35)','COLUMN_DEFAULT' => 'something_to_default','is_nullable' => true,"column_key" => "","EXTRA" => ""));
        $new_table->Delete_Column('id');
        $column = $new_table->Get_Column('new_column');
        $column->Set_Column_Key('UNI');
        $this->assertTrue(true);
    }
    function test_Clean_Up()
    {
        $this->table_dblink->database_dblink->Drop_Database_And_User('destroy');
        $this->assertTrue(true);
    }

}

?>