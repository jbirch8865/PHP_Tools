<?php

use DatabaseLink\SQLQueryError;

class CompanyTest extends \PHPUnit\Framework\TestCase
{
    private \DatabaseLink\Table $table_dblink;
    public \Test_Tools\toolbelt $toolbelt;

	public function setUp() :void
	{
        $toolbelt = new \Test_Tools\toolbelt();;
        $this->table_dblink = $toolbelt->Companies;
        $this->toolbelt = $toolbelt;
    }
    
    public function tearDown() :void
    {

    }

    private function Start_Fresh()
    {
        $company = new \app\Helpers\Company();
        try
        {
            $company->Load_Company_By_Name('test_System');
            $this->toolbelt->invokeMethod($company,'Delete_Object',array('destroy'));
            unset($company);
        } catch (\Exception $e)
        {
//            echo $e->getMessage();
        }
        $company = new \app\Helpers\Company();
        try
        {
            $company->Load_Company_By_Name('System_test');
            $this->toolbelt->invokeMethod($company,'Delete_Object',array('destroy'));
            unset($company);
        } catch (\Exception $e)
        {
//            echo $e->getMessage();
        }

    }
    function test_Create_New_Company()
    {
        $this->Start_Fresh();
        $company = new \app\Helpers\Company();
        $company->Set_Company_Name('test_System');
        unset($company);
        $company = new \app\Helpers\Company();
        $company->Load_Company_By_Name('test_System');
        $this->addToAssertionCount(1);
    }
    function test_Change_Company_Name()
    {
        $company = new \app\Helpers\Company();
        $company->Load_Company_By_Name('test_System');
        $company->Set_Company_Name('System_test');
        unset($company);
        $company = new \app\Helpers\Company();
        $this->expectException(\Active_Record\Active_Record_Object_Failed_To_Load::class);
        $company->Load_Company_By_Name('test_System');
    }
    function test_Fail_On_Duplicate_Name()
    {
        $company = new \app\Helpers\Company();
        $this->expectException(\Active_Record\UpdateFailed::class);
        $company->Set_Company_Name('System_test');
    }
    function test_Clean_Up()
    {
        $company = new \app\Helpers\Company();        
        $company->Load_Company_By_Name('System_test');
        $this->toolbelt->invokeMethod($company,'Delete_Object',array('destroy'));
        $this->addToAssertionCount(1);
    }

}

?>