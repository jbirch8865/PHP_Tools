<?php

use DatabaseLink\SQLQueryError;

class CompanyTest extends \PHPUnit\Framework\TestCase
{
    private \DatabaseLink\Table $table_dblink;

	public function setUp() :void
	{
        $company = new \Company\Company();
        $this->table_dblink = $company->table_dblink;
    }
    
    public function tearDown() :void
    {

    }

    private function Start_Fresh()
    {
        $company = new \Company\Company();
        try
        {
            $company->Load_Company_By_Name('test_System');
            $company->Delete_Object('destroy');
            unset($company);
        } catch (\Exception $e)
        {
            echo $e->getMessage();
        }
        $company = new \Company\Company();
        try
        {
            $company->Load_Company_By_Name('System_test');
            $company->Delete_Object('destroy');
            unset($company);
        } catch (\Exception $e)
        {
            echo $e->getMessage();
        }

    }
    function test_Create_New_Company()
    {
        $this->Start_Fresh();
        $company = new \Company\Company();
        $company->Set_Company_Name('test_System');
        unset($company);
        $company = new \Company\Company();
        $this->assertTrue($company->Load_Company_By_Name('test_System'));
    }
    function test_Change_Company_Name()
    {
        $company = new \Company\Company();
        $company->Load_Company_By_Name('test_System');
        $company->Set_Company_Name('System_test');
        unset($company);
        $company = new \Company\Company();
        $this->expectException(\Company\CompanyDoesNotExist::class);
        try
        {
            $company->Load_Company_By_Name('test_System');
        } catch (\Company\CompanyDoesNotExist $e)
        {
            $this->assertTrue($company->Load_Company_By_Name('System_test'));
            throw new \Company\CompanyDoesNotExist($e->getMessage());
        }
    }
    function test_Fail_On_Duplicate_Name()
    {
        $company = new \Company\Company();
        $this->expectException(\Active_Record\UpdateFailed::class);
        $company->Set_Company_Name('System_test');
    }
    function test_Clean_Up()
    {
        $company = new \Company\Company();
        $company->Load_Company_By_Name('System_test');
        $company->Delete_Object('destroy');
        $this->addToAssertionCount(1);
    }

}

?>