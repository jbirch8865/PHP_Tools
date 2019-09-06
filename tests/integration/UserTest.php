<?php

use User_Session\User_Is_Not_Authenticated;

use function Test_User\invokeMethod;

class UserTest extends \PHPUnit\Framework\TestCase
{
	private $dblink;
    private $user;
    private $username;

    public static function setUpBeforeClass() :void
    {
    }
    public function __construct()
    {
        parent::__construct();
        $this->username = 'jbirch8865';
        $this->dblink = new DatabaseLink\MySQLLink('D-H');   
    }
    public function setUp() :void
	{

	}

    function I_Have_Not_Tried_To_Login_This_Session_Yet()
    {
        $user = new \User_Session\Current_User;
        $this->assertFalse(invokeMethod($user,'Does_User_Session_Exist'));
    }

    function I_Have_Tried_And_Failed_To_Login_This_Session()
    {

    }

    function test_Set_Username()
    {
        $user = new \User_Session\User_Session;
        $user->Set_Username($this->username);
        $this->assertEquals($this->username,invokeMethod($user,'Get_Username'));
    }

    function test_Create_Salt()
    {
        $user = new \User_Session\User_Session;
        $user->Set_Username($this->username);
        $this->assertIsString(\Test_User\invokeMethod($user, 'Create_Salt'));
    }

    function test_Hash_Password()
    {
        $user = new \User_Session\User_Session;
        $user->Set_Username($this->username);
        $user->Set_Password('TestAPassword');
        $this->assertIsString(\Test_User\invokeMethod($user, 'Hash_Password_Given'));
    }    

    function test_Get_New_Salt()
    {
        $user = new \User_Session\User_Session;
        $user->Set_Username('New_User');
        $user->Set_Password('TestAPassword');
        $this->assertIsString(\Test_User\invokeMethod($user, 'Get_A_Valid_Salt'));
    }

    function test_Set_Password()
    {
        $user = new \User_Session\User_Session;
        $user->Set_Username('jbirch8865');
        $user->Set_Password('TestAPassword');
        $this->assertEquals('TestAPassword',invokeMethod($user,'Get_Password'));
    }

    function test_Create_User()
    {
        $this->user = new \User_Session\User_Session;
        $this->user->Set_Username($this->username);
        $this->user->Set_Password('TestAPassword');
        $this->assertTrue($this->user->Create_User());
    }

	function test_Does_User_Exist()
	{    
        $this->user = new \User_Session\User_Session;
        $this->user->Set_Username($this->username);
        $this->assertTrue(invokeMethod($this->user,'Does_User_Exist'));
    }

    function test_Authenticate_User_With_Correct_Password()
    {
        $this->user = new \User_Session\User_Session;
        $this->user->Set_Username($this->username);
        $this->user->Set_Password('TestAPassword');
        $this->assertTrue($this->user->Authenticate_User());
    }

    function test_Authenticate_User_With_Incorrect_Password()
    {
        $this->expectException(\User_Session\User_Is_Not_Authenticated::Class);
        $this->user = new \User_Session\User_Session;
        $this->user->Set_Username($this->username);
        $this->user->Set_Password('TestAWrongPassword');
        $this->user->Authenticate_User();
    }

    function test_Authenticate_Session()
    {
        $user = new \User_Session\Current_User;
        $user->Set_Username($this->username);
        $user->Set_Password('TestAPassword');
        $this->assertTrue($user->Authenticate());
    }

    function test_Current_Session_And_Is_Authenticated()
    {
        $user = new \User_Session\Current_User;
        $this->assertTrue($user->Am_I_Currently_Authenticated());
        $user->LogOut();
    }

    function test_Current_Session_Is_Not_Authenticated()
    {
        $user = new \User_Session\Current_User;
        $this->assertFalse($user->Am_I_Currently_Authenticated());
    }

    function test_Delete_User()
    {
        $this->user = new \User_Session\User_Session;
        $this->user->Set_Username($this->username);
        $this->assertTrue($this->user->Delete_User());
    }
}
?>