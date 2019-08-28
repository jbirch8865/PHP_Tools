<?php

class FieldTest extends \PHPUnit\Framework\TestCase
{
	private $DBLink;

	public function setUp() :void
	{
        $this->DBLink = new DatabaseLink\MySQLLink('D-H');   
	}

	function test_Instantiate_Field()
	{    
		$field = new DatabaseLink\Field($this->DBLink,"first_name","People");
		$this->assertFalse(\Test_User\invokeMethod($field,'Is_Field_Required'));
	}	
	
	function test_Set_Field_Value()
	{
		$field = new DatabaseLink\Field($this->DBLink,"first_name","People");
		\Test_User\invokeMethod($field, 'Set_Field_Value',array("Brian"));
		$this->assertEquals('Brian',\Test_User\invokeMethod($field,'Get_Field_Value'));
	}

	function test_I_Should_Not_Update_The_DB_With_This_Value()
	{
		$field = new DatabaseLink\Field($this->DBLink,"first_name","People");
		$this->assertFalse(\Test_User\invokeMethod($field, 'Should_I_Update_Or_Insert_Value'));

	}

	function test_I_Should_Update_The_DB_With_This_Value()
	{
		$field = new DatabaseLink\Field($this->DBLink,"first_name","People");
		\Test_User\invokeMethod($field,'Manually_Set_Field_Value',array("something"));
		$this->assertTrue(\Test_User\invokeMethod($field, 'Should_I_Update_Or_Insert_Value'));
	}

	function test_Set_SQL_Injected_First_Name()
	{
		$field = new DatabaseLink\Field($this->DBLink,"first_name","People");
		\Test_User\invokeMethod($field,'Manually_Set_Field_Value',array("Joel's"));
		$this->assertEquals("Joel\'s",\Test_User\invokeMethod($field,'Get_Field_Value'));

	}

	function test_Try_To_Change_Locked_Field()
	{
		$this->expectException(DatabaseLink\Field_Is_Locked ::class);
		$field = new DatabaseLink\Field($this->DBLink,"first_name","People");
		\Test_User\invokeMethod($field,'Lock_Value');
		\Test_User\invokeMethod($field,'Manually_Set_Field_Value',array("Joel's"));
	}
}

class PrimaryKeyTest extends \PHPUnit\Framework\TestCase
{
	private $DBLink;

	public function setUp() :void
	{
        $this->DBLink = new DatabaseLink\MySQLLink('D-H');   
	}

	function test_Fail_To_Instantiate_Non_Primary_Key_Field()
	{    
		$this->expectException(DatabaseLink\Not_A_Primary_Key ::class);
		$field = new DatabaseLink\PrimaryKey($this->DBLink,'first_name','People');
		$this->assertFalse($field->Am_I_Ready_To_Update());
	}	

	function test_Instantiate_Primary_Key()
	{    
		$field = new DatabaseLink\PrimaryKey($this->DBLink,'person_id','Person_Belongs_To_Company');
		$this->assertFalse($field->Am_I_Ready_To_Update());
	}	

	function test_I_Should_Be_Ready_To_Update()
	{    
		$field = new DatabaseLink\PrimaryKey($this->DBLink,'person_id','Person_Belongs_To_Company');
		$field->Set_Field_Value_From_DB("1");
		$this->assertTrue($field->Am_I_Ready_To_Update());
	}

	function test_I_Should_Not_Be_Ready_To_Update()
	{
		$field = new DatabaseLink\PrimaryKey($this->DBLink,'person_id','Person_Belongs_To_Company');
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
        $this->DBLink = new DatabaseLink\MySQLLink('D-H');   
	}

	function test_Instantiate()
	{    
		$field = new DatabaseLink\PrimaryKeys($this->DBLink,'Person_Belongs_To_Company');
		$this->assertEquals("",$field->Return_PRIMARY_KEY_Equals());	
	}	

	function test_Set_Value_Manually()
	{
		$field = new DatabaseLink\PrimaryKeys($this->DBLink,'Person_Belongs_To_Company');
		$field->Set_Primary_Key_Value_Manually('person_id','1');
		$this->assertTrue(\Test_User\invokeMethod($field,'Is_This_Primary_Key_Set',array('person_id')));
	}

	function test_I_Should_Not_Be_Ready_To_Update()
	{
		$field = new DatabaseLink\PrimaryKeys($this->DBLink,'Person_Belongs_To_Company');
		$field->Set_Primary_Key_Value_Manually('person_id','1');
		$this->assertFalse(\Test_User\invokeMethod($field,'Is_Primary_Key_Ready_For_Update',array('person_id')));
	}

	function test_Set_Value_From_DB()
	{
		$field = new DatabaseLink\PrimaryKeys($this->DBLink,'Person_Belongs_To_Company');
		$field->Set_Primary_Key_Value_From_DB('person_id','1');
		$this->assertTrue(\Test_User\invokeMethod($field,'Is_Primary_Key_Ready_For_Update',array('person_id')));
	}

}

class RowTest extends \PHPUnit\Framework\TestCase
{
	function test_Instantiate()
	{    
		$field = new DatabaseLink\Row('D-H','People');
		$this->assertFalse(\Test_User\invokeMethod($field,'Am_I_Ready_To_Update_Row'));
	}	

	function test_Link_Person_To_Company_Manually()
	{
		$row = new DatabaseLink\Row('D-H','Person_Belongs_To_Company');
		\Test_User\invokeMethod($row,'Set_Primary_Key_Value_Manually',array('customer_id','1'));
		\Test_User\invokeMethod($row,'Set_Primary_Key_Value_Manually',array('person_id','1'));
		$this->assertFalse(\Test_User\invokeMethod($row,'Am_I_Ready_To_Update_Row'));
	}
	function test_Link_Person_To_Company_From_DB()
	{
		$row = new DatabaseLink\Row('D-H','Person_Belongs_To_Company');
		\Test_User\invokeMethod($row,'Set_Primary_Key_Value_From_DB',array('customer_id','1'));
		\Test_User\invokeMethod($row,'Set_Primary_Key_Value_From_DB',array('person_id','1'));
		$this->assertTrue(\Test_User\invokeMethod($row,'Am_I_Ready_To_Update_Row'));
	}
	function test_Insert_Row()
	{
		$row = new DatabaseLink\Row('D-H','Person_Belongs_To_Company');
		\Test_User\invokeMethod($row,'Set_Primary_Key_Value_Manually',array('customer_id','1'));
		\Test_User\invokeMethod($row,'Set_Primary_Key_Value_Manually',array('person_id','1'));
		\Test_User\invokeMethod($row,'Insert_Row');	
		$this->assertTrue(true);
	}
	function test_Delete_Row()
	{
		$row = new DatabaseLink\Row('D-H','Person_Belongs_To_Company');
		\Test_User\invokeMethod($row,'Single_Row_Search',array('person_id','1'));
		\Test_User\invokeMethod($row,'Delete_Row');	
		$this->assertTrue(true);
	}
}
?>