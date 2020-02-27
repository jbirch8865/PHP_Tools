<?php declare(strict_types=1);
namespace Company;
global $dblink;
$company_table = new \DatabaseLink\Table('Companies',$dblink);
company_Validate_ID_Column($company_table);
company_Validate_Company_Name_Column($company_table);
company_Create_System_If_Not_Already($company_table);
company_Validate_Company_Active_Status_Column($company_table);
function company_Validate_Company_Active_Status_Column(\DatabaseLink\Table $company_table)
{
    global $cConfigs;
    if($column = $company_table->Get_Column('active_status'))
    {
        if($column->Get_Column_Key() != "")
        {
            $column->Set_Column_Key(""); 
        }
        if($column->Get_Data_Type() != "INT(11)")
        {
            $column->Set_Data_Type("INT(11)");
        }
        if($column->Get_Default_Value() != "1")
        {
            $column->Set_Default_Value("1");
        }
        if($column->Is_Column_Nullable())
        {
            $column->Column_Is_Not_Nullable();
        }
        if($column->Does_Auto_Increment())
        {
            $column->Column_Does_Not_Auto_Increments();
        }
        $column->Update_Column();
    }else
    {
        $column = new \DatabaseLink\Column('active_status',$company_table,array(
            'COLUMN_TYPE' => 'INT(11)',
            'COLUMN_DEFAULT' => "1",
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
function company_Validate_Company_Name_Column(\DatabaseLink\Table $company_table)
{
    global $cConfigs;
    if($column = $company_table->Get_Column('company_name'))
    {
        if($column->Get_Column_Key() != "UNI")
        {
            $column->Set_Column_Key("UNI"); 
        }
        if($column->Get_Data_Type() != "varchar(75)")
        {
            $column->Set_Data_Type("varchar(75)");
        }
        if($column->Get_Default_Value() != null)
        {
            $column->Set_Default_Value(null);
        }
        if($column->Is_Column_Nullable())
        {
            $column->Column_Is_Not_Nullable();
        }
        if($column->Does_Auto_Increment())
        {
            $column->Column_Does_Not_Auto_Increments();
        }
        $column->Update_Column();
    }else
    {
        $column = new \DatabaseLink\Column('company_name',$company_table,array(
            'COLUMN_TYPE' => 'varchar(75)',
            'COLUMN_DEFAULT' => null,
            'is_nullable' => false,
            'column_key' => "UNI",
            'EXTRA' => "")
        );
    }
}
function company_Validate_ID_Column(\DatabaseLink\Table $company_table)
{
    global $cConfigs;
    if($column = $company_table->Get_Column('id'))
    {
        if($column->Get_Column_Key() != "PRI")
        {
            $column->Set_Column_Key("PRI"); 
        }
        if($column->Get_Data_Type() != "int(11)")
        {
            $column->Set_Data_Type("int(11)");
        }
        if($column->Get_Default_Value() != null)
        {
            $column->Set_Default_Value(null);
        }
        if($column->Is_Column_Nullable())
        {
            $column->Column_Is_Not_Nullable();
        }
        if(!$column->Does_Auto_Increment())
        {
            $column->Column_Auto_Increments();
        }
    }else
    {
        $column = new \DatabaseLink\Column('id',$company_table,array(
            'COLUMN_TYPE' => 'int(11)',
            'COLUMN_DEFAULT' => null,
            'is_nullable' => false,
            'column_key' => "PRI",
            'EXTRA' => "AUTO_INCREMENT")
        );
    }
}
function company_Create_System_If_Not_Already()
{
    try
    {
        $company = new Company(1);
        if(!$company->Get_Company_Name() == 'System') 
        {
            $company->Set_Company_Name('System');
        }
    } catch (CompanyDoesNotExist $e)
    {
        try
        {
            $company = new Company();
            $company->Load_Company_By_Name('System');
            $company->Change_Primary_Key(1);
        } catch (CompanyDoesNotExist $e)
        {
            $company = new Company();
            $company->Set_Company_Name('System');
        }
    }
}
?>