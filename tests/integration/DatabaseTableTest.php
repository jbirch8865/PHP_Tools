<?php

class FieldTest extends \PHPUnit\Framework\TestCase
{
	private $DBLink;
	private $configs;

	public function setUp() :void
	{
		$this->configs = new config\ConfigurationFile();
		$this->configs = $this->configs->Configurations();
        $this->DBLink = new DatabaseLink\MySQLLink($this->configs['database_name']);   
	}
	
	function test_Instantiate_Field()
	{    
		$field = new DatabaseLink\Field($this->DBLink,$this->configs["username_column_name"],$this->configs["user_table_name"]);
		$this->assertTrue(\Test_User\invokeMethod($field,'Is_Field_Required'));
	}	
	
	function test_Set_Field_Value()
	{
		$field = new DatabaseLink\Field($this->DBLink,$this->configs["username_column_name"],$this->configs["user_table_name"]);
		\Test_User\invokeMethod($field, 'Set_Field_Value',array("Brian"));
		$this->assertEquals('Brian',\Test_User\invokeMethod($field,'Get_Field_Value'));
	}

	function test_I_Should_Not_Update_The_DB_With_This_Value()
	{
		$field = new DatabaseLink\Field($this->DBLink,$this->configs["username_column_name"],$this->configs["user_table_name"]);
		$this->assertFalse(\Test_User\invokeMethod($field, 'Should_I_Update_Or_Insert_Value'));
	}

	function test_I_Should_Update_The_DB_With_This_Value()
	{
		$field = new DatabaseLink\Field($this->DBLink,$this->configs["username_column_name"],$this->configs["user_table_name"]);
		\Test_User\invokeMethod($field,'Manually_Set_Field_Value',array("something"));
		$this->assertTrue(\Test_User\invokeMethod($field, 'Should_I_Update_Or_Insert_Value'));
	}

	function test_Set_SQL_Injected_First_Name()
	{
		$field = new DatabaseLink\Field($this->DBLink,$this->configs["username_column_name"],$this->configs["user_table_name"]);
		\Test_User\invokeMethod($field,'Manually_Set_Field_Value',array("Joel's"));
		$this->assertEquals("Joel\'s",\Test_User\invokeMethod($field,'Get_Field_Value'));

	}

	function test_Try_To_Change_Locked_Field()
	{
		$this->expectException(DatabaseLink\Field_Is_Locked ::class);
		$field = new DatabaseLink\Field($this->DBLink,$this->configs["username_column_name"],$this->configs["user_table_name"]);
		\Test_User\invokeMethod($field,'Lock_Value');
		\Test_User\invokeMethod($field,'Manually_Set_Field_Value',array("Joel's"));
	}
}

class PrimaryKeyTest extends \PHPUnit\Framework\TestCase
{
	private $DBLink;

	public function setUp() :void
	{
		$this->configs = new config\ConfigurationFile();
		$this->configs = $this->configs->Configurations();
        $this->DBLink = new DatabaseLink\MySQLLink($this->configs['database_name']);   
	}

	function test_Fail_To_Instantiate_Non_Primary_Key_Field()
	{    
		$this->expectException(DatabaseLink\Not_A_Primary_Key ::class);
		$field = new DatabaseLink\PrimaryKey($this->DBLink,$this->configs['username_column_name'],$this->configs['user_table_name']);
		$this->assertFalse($field->Am_I_Ready_To_Update());
	}	

	function test_Instantiate_Primary_Key()
	{    
		$field = new DatabaseLink\PrimaryKey($this->DBLink,$this->configs['user_id_column_name'],$this->configs['user_table_name']);
		$this->assertFalse($field->Am_I_Ready_To_Update());
	}	

	function test_I_Should_Be_Ready_To_Update()
	{    
		$field = new DatabaseLink\PrimaryKey($this->DBLink,$this->configs['user_id_column_name'],$this->configs['user_table_name']);
		$field->Set_Field_Value_From_DB("1");
		$this->assertTrue($field->Am_I_Ready_To_Update());
	}

	function test_I_Should_Not_Be_Ready_To_Update()
	{
		$field = new DatabaseLink\PrimaryKey($this->DBLink,$this->configs['primary_key_column_1'],$this->configs['primary_key_table_name']);
		$field->Manually_Set_Field_Value("1");
		$this->assertEquals("1",\Test_User\invokeMethod($field,'Get_Field_Value'));
		$this->assertFalse($field->Am_I_Ready_To_Update());

	}

}

class PrimaryKeysTest extends \PHPUnit\Framework\TestCase
{
	private $DBLink;

	public function setUp() :void
	{
		$this->configs = new config\ConfigurationFile();
		$this->configs = $this->configs->Configurations();
        $this->DBLink = new DatabaseLink\MySQLLink($this->configs['database_name']);   
	}

	function test_Instantiate()
	{    
		$field = new DatabaseLink\PrimaryKeys($this->DBLink,$this->configs['primary_key_table_name']);
		$this->assertEquals("",$field->Return_PRIMARY_KEY_Equals());	
	}	

	function test_Set_Value_Manually()
	{
		$field = new DatabaseLink\PrimaryKeys($this->DBLink,$this->configs['primary_key_table_name']);
		$field->Set_Primary_Key_Value_Manually($this->configs['primary_key_column_1'],'1');
		$this->assertTrue(\Test_User\invokeMethod($field,'Is_This_Primary_Key_Set',array($this->configs['primary_key_column_1'])));
	}

	function test_I_Should_Not_Be_Ready_To_Update()
	{
		$field = new DatabaseLink\PrimaryKeys($this->DBLink,$this->configs['primary_key_table_name']);
		$field->Set_Primary_Key_Value_Manually($this->configs['primary_key_column_1'],'1');
		$this->assertFalse(\Test_User\invokeMethod($field,'Is_Primary_Key_Ready_For_Update',array($this->configs['primary_key_column_1'])));
	}

	function test_Set_Value_From_DB()
	{
		$field = new DatabaseLink\PrimaryKeys($this->DBLink,$this->configs['primary_key_table_name']);
		$field->Set_Primary_Key_Value_From_DB($this->configs['primary_key_column_1'],'1');
		$this->assertTrue(\Test_User\invokeMethod($field,'Is_Primary_Key_Ready_For_Update',array($this->configs['primary_key_column_1'])));
	}
}

class RowTest extends \PHPUnit\Framework\TestCase
{
	private $configs;
	private $user_id_created;

	function setUp() :void
	{
		$this->configs = new \config\ConfigurationFile();
		$this->configs = $this->configs->Configurations();
	}
	function test_Instantiate()
	{    
		$field = new DatabaseLink\Row($this->configs['database_name'],$this->configs['user_table_name']);
		$this->assertFalse(\Test_User\invokeMethod($field,'Am_I_Ready_To_Update_Row'));
	}	

	function test_Insert_Row()
	{
		try
		{
			$this->DeleteUser();
		} catch (\Exception $e)
		{}
		$this->CreateUser();
		$row = new DatabaseLink\Row($this->configs['database_name'],$this->configs['primary_key_table_name']);
		\Test_User\invokeMethod($row,'Set_Primary_Key_Value_Manually',array($this->configs['primary_key_column_1'],$this->user_id_created));
		\Test_User\invokeMethod($row,'Set_Primary_Key_Value_Manually',array($this->configs['primary_key_column_2'],'1'));
		\Test_User\invokeMethod($row,'Insert_Row');
		$this->Delete_Row();
	}

	function Delete_Row()
	{
		$row = new DatabaseLink\Row($this->configs['database_name'],$this->configs['primary_key_table_name']);
		\Test_User\invokeMethod($row,'Single_Row_Search',array($this->configs['primary_key_column_1'],$this->user_id_created));
		\Test_User\invokeMethod($row,'Delete_Row');	
		$this->assertTrue(true);

	}

	private function CreateUser()
	{
		$user = new \User_Session\User_Session;
		$user->Set_Username($this->configs['test_username']);
		$user->Set_Password('TestAPassword');
		$user->Create_User();
		$this->user_id_created = $user->Get_User_ID();
	}

	private function DeleteUser()
	{
		$user = new \User_Session\User_Session;
		$user->Set_Username($this->configs['test_username']);
		$user->Delete_User();
	}
}
?>