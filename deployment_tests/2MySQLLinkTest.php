<?php

class MySQLLinkTest extends \PHPUnit\Framework\TestCase
{
    private \DatabaseLink\Database $dblink;
    private \DatabaseLink\MySQLLink $root_dblink;
    private \config\ConfigurationFile $cConfigs;
    private string $project_username;
    private string $root_username;

	public function setUp() :void
	{
        global $cConfigs;
        $this->cConfigs = $cConfigs;
        global $dblink;
        $this->dblink = $dblink;
        global $root_dblink;
        $this->root_dblink = $root_dblink;
        $this->project_username =  $this->cConfigs->Get_Connection_Username($this->cConfigs->Get_Name_Of_Project());
        $this->root_username =  $this->cConfigs->Get_Connection_Username('root');
    }
    
    public function tearDown() :void
    {
        $this->cConfigs->Set_Database_Connection_Preferences(
            $this->cConfigs->Get_Connection_Hostname($this->cConfigs->Get_Name_Of_Project()),
            $this->project_username,
            $this->cConfigs->Get_Connection_Password($this->cConfigs->Get_Name_Of_Project()),
            $this->cConfigs->Get_Name_Of_Project(),
            $this->cConfigs->Get_Connection_Listeningport($this->cConfigs->Get_Name_Of_Project())
        );
        $this->cConfigs->Set_Database_Connection_Preferences(
            $this->cConfigs->Get_Connection_Hostname('root'),
            $this->root_username,
            $this->cConfigs->Get_Connection_Password('root'),
            'root',
            $this->cConfigs->Get_Connection_Listeningport('root')
        );
    }
    function test_Fail_On_Database_Missing()
    {   
        $this->root_dblink->Execute_Any_SQL_Query("SHOW DATABASES WHERE `Database` = 'not_a_real_database'");
        if($this->root_dblink->Get_Num_Of_Rows())
        {
            ///So if I forgot and put true then Drop_Database_And_User would fail thoughts?
            $new_database = new \DatabaseLink\Database('not_a_real_database',false);
            $new_database->Drop_Database_And_User("destroy");            
        }
        $this->expectException(\DatabaseLink\SQLConnectionError::class);
        new \DatabaseLink\MySQLLink('not_a_real_database',2);
    }

    function test_Fail_On_No_Username_Non_Root()
    {
        $this->cConfigs->Delete_Config_If_Exists($this->cConfigs->Get_Name_Of_Project().'_username');
        $this->expectException(TypeError::class);
        new \DatabaseLink\MySQLLink("",1);
    }
    function test_Fail_On_No_Username_Root()
    {
        $this->expectException(TypeError::class);
        $this->cConfigs->Delete_Config_If_Exists('root_username');
        new \DatabaseLink\MySQLLink("",2);
    }
    function test_Fail_On_No_Database_Non_Root()
    {
        $this->expectException(TypeError::class);
        new \DatabaseLink\MySQLLink("",1);
    }
    function test_Execute_Any_SQL_Query_Non_Root()
    {
        $this->dblink->dblink->Execute_Any_SQL_Query("CREATE TABLE `fake_table_delete_me` (
            `organization_id` int(11) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1");
        $this->dblink->dblink->Execute_Any_SQL_Query("SHOW TABLES WHERE `Tables_in_".$this->cConfigs->Get_Name_Of_Project_Database()."` = 'fake_table_delete_me'");
        $this->assertEquals('1',$this->dblink->dblink->Get_Num_Of_Rows());
        $this->dblink->dblink->Execute_Any_SQL_Query("DROP TABLE `fake_table_delete_me`");
        $this->dblink->dblink->Execute_Any_SQL_Query("SHOW TABLES WHERE `Tables_in_".$this->cConfigs->Get_Name_Of_Project_Database()."` = 'fake_table_delete_me'");
        $this->assertEquals('0',$this->dblink->dblink->Get_Num_Of_Rows());

    }
    function test_Fail_Previledged_Execute_Any_SQL_Query_Non_Root()
    {
        $this->expectException(\DatabaseLink\SQLQueryError::class);
        $this->dblink->dblink->Execute_Any_SQL_Query("CREATE DATABASE `fake_database_delete_me`");
    }
    function test_Previledged_Execute_Any_SQL_Query_Root()
    {
        $this->root_dblink->Execute_Any_SQL_Query("CREATE DATABASE `fake_database_delete_me`");
        $this->root_dblink->Execute_Any_SQL_Query("SHOW DATABASES WHERE `Database` = 'fake_database_delete_me'");
        $this->assertEquals('1',$this->root_dblink->Get_Num_Of_Rows());
        $this->root_dblink->Execute_Any_SQL_Query("DROP DATABASE `fake_database_delete_me`");
        $this->root_dblink->Execute_Any_SQL_Query("SHOW DATABASES WHERE `Database` = 'fake_database_delete_me'");
        $this->assertEquals('0',$this->root_dblink->Get_Num_Of_Rows());
    }
    function test_Insert_On_Execute_Insert_Or_Update_SQL_Query()
    {
        $this->dblink->dblink->Execute_Any_SQL_Query("CREATE TABLE `fake_table_delete_me` (
            `organization_id` int(11) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1");
        $this->dblink->dblink->Execute_Insert_Or_Update_SQL_Query('fake_table_delete_me',array('organization_id' => '1'));
        $this->dblink->dblink->Execute_Any_SQL_Query("SELECT * FROM `fake_table_delete_me`");
        $this->assertEquals('1',$this->dblink->dblink->Get_Num_Of_Rows());
        $this->dblink->dblink->Execute_Any_SQL_Query("DROP TABLE `fake_table_delete_me`");
        $this->dblink->dblink->Execute_Any_SQL_Query("SHOW TABLES WHERE `Tables_in_".$this->cConfigs->Get_Name_Of_Project_Database()."` = 'fake_table_delete_me'");
        $this->assertEquals('0',$this->dblink->dblink->Get_Num_Of_Rows());
    }
    function test_Update_On_Execute_Insert_Or_Update_SQL_Query()
    {
        $this->dblink->dblink->Execute_Any_SQL_Query("CREATE TABLE `fake_table_delete_me` (
            `organization_id` int(11) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1");
        $this->dblink->dblink->Execute_Insert_Or_Update_SQL_Query('fake_table_delete_me',array('organization_id' => '1'));
        $this->dblink->dblink->Execute_Any_SQL_Query("SELECT * FROM `fake_table_delete_me`");
        $this->assertEquals('1',$this->dblink->dblink->Get_Num_Of_Rows());
        $this->dblink->dblink->Execute_Insert_Or_Update_SQL_Query('fake_table_delete_me',array("organization_id" => "2"),true);
        $this->dblink->dblink->Execute_Any_SQL_Query("SELECT * FROM `fake_table_delete_me`");
        $this->assertEquals('2',$this->dblink->dblink->Get_First_Row()['organization_id']);
        $this->dblink->dblink->Execute_Any_SQL_Query("DROP TABLE `fake_table_delete_me`");
        $this->dblink->dblink->Execute_Any_SQL_Query("SHOW TABLES WHERE `Tables_in_".$this->cConfigs->Get_Name_Of_Project_Database()."` = 'fake_table_delete_me'");
        $this->assertEquals('0',$this->dblink->dblink->Get_Num_Of_Rows());
    }
}

?>