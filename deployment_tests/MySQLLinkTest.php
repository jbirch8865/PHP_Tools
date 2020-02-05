<?php

class MySQLLinkTest extends \PHPUnit\Framework\TestCase
{
    private \DatabaseLink\Database $dblink;
    private \DatabaseLink\MySQLLink $root_dblink;
    private \config\ConfigurationFile $cConfigs;
	public function setUp() :void
	{
        global $cConfigs;
        $this->cConfigs = $cConfigs;
        global $dblink;
        $this->dblink = $dblink;
        global $root_dblink;
        $this->root_dblink = $root_dblink;
    }
    
    function test_Fail_On_Database_Missing()
    {   
        $this->expectException(\DatabaseLink\SQLConnectionError::class);
        new \DatabaseLink\MySQLLink('not_a_real_database',true);
    }

    function test_Fail_On_No_Username_Non_Root()
    {
        $this->expectException(Exception::class);
        $username = $this->cConfigs->Get_Connection_Username($this->cConfigs->Get_Name_Of_Project());
        $this->cConfigs->Delete_Config_If_Exists($this->cConfigs->Get_Name_Of_Project().'_username');
        new \DatabaseLink\MySQLLink("",false);
        $this->cConfigs->Set_Database_Connection_Preferences(
            $this->cConfigs->Get_Connection_Hostname($this->cConfigs->Get_Name_Of_Project()),
            $username,
            $this->cConfigs->Get_Connection_Password($this->cConfigs->Get_Name_Of_Project()),
            $this->cConfigs->Get_Name_Of_Project(),
            $this->cConfigs->Get_Connection_Listeningport($this->cConfigs->Get_Name_Of_Project())
        );
    }
    function test_Fail_On_No_Username_Root()
    {
        $this->expectException(Exception::class);
        $username = $this->cConfigs->Get_Connection_Username('root_username');
        $this->cConfigs->Delete_Config_If_Exists('root_username');
        new \DatabaseLink\MySQLLink("",true);
        $this->cConfigs->Set_Database_Connection_Preferences(
            $this->cConfigs->Get_Connection_Hostname('root'),
            $username,
            $this->cConfigs->Get_Connection_Password('root'),
            'root',
            $this->cConfigs->Get_Connection_Listeningport('root')
        );
    }
}

?>