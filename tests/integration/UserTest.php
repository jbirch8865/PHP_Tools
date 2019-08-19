<?php

use User_Session\User_Session;

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
        $user->Set_Password('TestAPassword');
        $this->assertIsString(\Test_User\invokeMethod($user, 'Hash_Password_Given'));
    }    

    function test_Get_New_Salt()
    {
        $user = new \User_Session\User_Session;
        $user->Set_Password('TestAPassword');
        $this->assertIsString(\Test_User\invokeMethod($user, 'Get_A_Valid_Salt'));
    }

    function test_Set_Password()
    {
        $user = new \User_Session\User_Session;
        $user->Set_Password('TestAPassword');
        $this->assertEquals('TestAPassword',invokeMethod($user,'Get_Password'));
    }

    function test_Create_User()
    {
        $this->user = new \User_Session\User_Session;
        $this->user->Set_Username($this->username);
        $this->user->Set_Password('TestAPassword');
        $this->assertTrue($this->user->Create_User(1));
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
        $this->user->Set_Password('TestAWrongPassword');
        $this->user->Set_Username($this->username);
        $this->user->Authenticate_User();
    }
    
    function test_Delete_User()
    {
        $this->user = new \User_Session\User_Session;
        $this->user->Set_Username($this->username);
        $this->assertTrue($this->user->Delete_User());
    }


}

?>