<?php

class DatabaseTest extends \PHPUnit\Framework\TestCase
{
    private $DBLink;

    public function setUp() :void
    {
        $this->DBLink = new DatabaseLink\MySQLLink('syslog');
    }

    public function test_Execute_SQL_Query()
    {
        $this->assertTrue($this->DBLink->ExecuteSQLQuery("INSERT INTO Sys_Log SET `Message` = 'PHPUnit Test Insert Successful'", '10', false));
    }
    
    public function test_Error_On_Duplicate_Key()
    {
        $this->expectException(DatabaseLink\DuplicatePrimaryKeyRequest::class);
        $this->DBLink->ExecuteSQLQuery("INSERT INTO Sys_Log SET `id` = '1', `Message` = 'This should be a duplicate key'");
    }

    public function test_SQL_Syntax_Error()
    {
        $this->expectException(DatabaseLink\SQLQueryError::class);
        $this->DBLink->ExecuteSQLQuery("Syntax error");
    }
}
