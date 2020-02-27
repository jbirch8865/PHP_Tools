<?php declare(strict_types=1);
namespace Company;
global $dblink;
$company_config_table = new \DatabaseLink\Table('Company_Configs',$dblink);
$company_table = new \DatabaseLink\Table('Companies',$dblink);
company_config_Validate_ID_Column($company_config_table);
company_config_Validate_Company_ID_Column($company_config_table);
company_config_Validate_Config_Name($company_config_table);
company_config_Validate_Config_Value($company_config_table);
company_config_Validate_Active_Status_Column($company_config_table);
company_config_Add_Company_ID_Constraint($company_table,$company_config_table);
company_config_Create_Session_Time_Limit_If_Not_Already($company_config_table);
$company_config_table->Add_Unique_Columns(array('company_id','config_name'));
function company_config_Validate_Active_Status_Column(\DatabaseLink\Table $company_config_table)
{
    global $cConfigs;
    if($column = $company_config_table->Get_Column('active_status'))
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
        $column = new \DatabaseLink\Column('active_status',$company_config_table,array(
            'COLUMN_TYPE' => 'INT(11)',
            'COLUMN_DEFAULT' => "1",
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}

function company_config_Validate_ID_Column(\DatabaseLink\Table $company_config_table)
{
    global $cConfigs;
    if($column = $company_config_table->Get_Column('id'))
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
        $column = new \DatabaseLink\Column('id',$company_config_table,array(
            'COLUMN_TYPE' => 'int(11)',
            'COLUMN_DEFAULT' => null,
            'is_nullable' => false,
            'column_key' => "PRI",
            'EXTRA' => "AUTO_INCREMENT")
        );
    }
}
function company_config_Validate_Company_ID_Column(\DatabaseLink\Table $company_config_table)
{
    global $cConfigs;
    if($column = $company_config_table->Get_Column('company_id'))
    {
        if($column->Get_Column_Key() != "")
        {
            $column->Set_Column_Key(""); 
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
        if($column->Does_Auto_Increment())
        {
            $column->Column_Does_Not_Auto_Increments();
        }
        $column->Update_Column();
    }else
    {
        $column = new \DatabaseLink\Column('company_id',$company_config_table,array(
            'COLUMN_TYPE' => 'int(11)',
            'COLUMN_DEFAULT' => null,
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
function company_config_Validate_Config_Name(\DatabaseLink\Table $company_config_table)
{
    global $cConfigs;
    if($column = $company_config_table->Get_Column('config_name'))
    {
        if($column->Get_Column_Key() != "")
        {
            $column->Set_Column_Key(""); 
        }
        if($column->Get_Data_Type() != "varchar(40)")
        {
            $column->Set_Data_Type("varchar(40)");
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
        $column = new \DatabaseLink\Column('config_name',$company_config_table,array(
            'COLUMN_TYPE' => 'varchar(40)',
            'COLUMN_DEFAULT' => null,
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
function company_config_Validate_Config_Value(\DatabaseLink\Table $company_config_table)
{
    global $cConfigs;
    if($column = $company_config_table->Get_Column('config_value'))
    {
        if($column->Get_Column_Key() != "")
        {
            $column->Set_Column_Key(""); 
        }
        if($column->Get_Data_Type() != "varchar(200)")
        {
            $column->Set_Data_Type("varchar(200)");
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
        $column = new \DatabaseLink\Column('config_value',$company_config_table,array(
            'COLUMN_TYPE' => 'varchar(200)',
            'COLUMN_DEFAULT' => null,
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
function company_config_Add_Company_ID_Constraint(\DatabaseLink\Table $company_table,\DatabaseLink\Table $company_config_table)
{
    $column = $company_table->Get_Column('id');
    $company_id = $company_config_table->Get_Column('company_id');
    $company_id->Add_Constraint_If_Does_Not_Exist($column);
}
function company_config_Create_Session_Time_Limit_If_Not_Already(\DatabaseLink\Table $company_config_table) : void
{
    $system_company = new Company($_SESSION['company_id']);
    $system_company->Set_Default_Configs();
}
?>