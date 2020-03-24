<?php
namespace Test_Tools;

class toolbelt_base
{
    public \config\ConfigurationFile $cConfigs;
    public \DatabaseLink\MySQLLink $root_dblink;
    public \DatabaseLink\Database $dblink;
    public \DatabaseLink\Database $read_only_dblink;
    public \DatabaseLink\Table $Companies;
    public \DatabaseLink\Table $Programs;
    public \DatabaseLink\Table $Configs;
    public \DatabaseLink\Table $Company_Configs;
    public \DatabaseLink\Table $Users;
    public \DatabaseLink\Table $Programs_Have_Sessions;
    
    /**
     * Call protected/private method of a class.
     *
     * @param object &$object    Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}

class toolbelt extends toolbelt_base
{
    public \config\ConfigurationFile $cConfigs;
    public \DatabaseLink\MySQLLink $root_dblink;
    public \DatabaseLink\Database $dblink;
    public \DatabaseLink\Database $read_only_dblink;
    public \DatabaseLink\Table $Companies;
    public \DatabaseLink\Table $Programs;
    public \DatabaseLink\Table $Configs;
    public \DatabaseLink\Table $Company_Configs;
    public \DatabaseLink\Table $Users;
    public \DatabaseLink\Table $Programs_Have_Sessions;
    
    function __construct()
    {
        global $toolbelt_base;
        $this->cConfigs = $toolbelt_base->cConfigs;
        $this->root_dblink = $toolbelt_base->root_dblink;
        $this->dblink = $toolbelt_base->dblink;
        $this->read_only_dblink = $toolbelt_base->read_only_dblink;
        $this->Companies = $toolbelt_base->Companies;
        $this->Programs = $toolbelt_base->Programs;
        $this->Configs = $toolbelt_base->Configs;
        $this->Company_Configs = $toolbelt_base->Company_Configs;
        $this->Users = $toolbelt_base->Users;
        $this->Programs_Have_Sessions = $toolbelt_base->Programs_Have_Sessions;
    }
}

?>