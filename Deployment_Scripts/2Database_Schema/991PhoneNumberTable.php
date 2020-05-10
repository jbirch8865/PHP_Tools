<?php declare(strict_types=1);
$toolbelt_base->Phone_Numbers = new \DatabaseLink\Table('Phone_Numbers',$toolbelt_base->dblink);
phone_numbers_Validate_ID_Column($toolbelt_base->Phone_Numbers);
phone_numbers_Validate_Description_Column($toolbelt_base->Phone_Numbers);
phone_numbers_Validate_Country_Code_Column($toolbelt_base->Phone_Numbers);
phone_numbers_Validate_Area_Code_Column($toolbelt_base->Phone_Numbers);
phone_numbers_Validate_Prefix_Column($toolbelt_base->Phone_Numbers);
phone_numbers_Validate_Suffix_Column($toolbelt_base->Phone_Numbers);
phone_numbers_Validate_Extension_Column($toolbelt_base->Phone_Numbers);
phone_numbers_Validate_Type_Column($toolbelt_base->Phone_Numbers);
phone_numbers_Validate_Carrier_Column($toolbelt_base->Phone_Numbers);
phone_numbers_Validate_Company_Column($toolbelt_base->Phone_Numbers);
$toolbelt_base->Phone_Numbers->Load_Columns();
function phone_numbers_Validate_ID_Column(\DatabaseLink\Table $phone_number_table)
{
    try
    {
        $column = $phone_number_table->Get_Column('id');
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
    } catch (\DatabaseLink\Column_Does_Not_Exist $e)
    {
        $column = new \DatabaseLink\Column('id',$phone_number_table,array(
            'COLUMN_TYPE' => 'int(11)',
            'COLUMN_DEFAULT' => null,
            'is_nullable' => false,
            'column_key' => "PRI",
            'EXTRA' => "AUTO_INCREMENT")
        );
    }
}
function phone_numbers_Validate_Description_Column(\DatabaseLink\Table $phone_number_table)
{
    try
    {
        $column = $phone_number_table->Get_Column('description');
        if($column->Get_Column_Key() != "")
        {
            $column->Set_Column_Key("");
        }
        if($column->Get_Data_Type() != "varchar(75)")
        {
            $column->Set_Data_Type("varchar(75)");
        }
        if($column->Get_Default_Value() != "")
        {
            $column->Set_Default_Value("");
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
    } catch (\DatabaseLink\Column_Does_Not_Exist $e)
    {
        $column = new \DatabaseLink\Column('description',$phone_number_table,array(
            'COLUMN_TYPE' => 'varchar(75)',
            'COLUMN_DEFAULT' => "",
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
function phone_numbers_Validate_Country_Code_Column(\DatabaseLink\Table $phone_number_table)
{
    try
    {
        $column = $phone_number_table->Get_Column('country_code');
        if($column->Get_Column_Key() != "")
        {
            $column->Set_Column_Key("");
        }
        if($column->Get_Data_Type() != "varchar(10)")
        {
            $column->Set_Data_Type("varchar(10)");
        }
        if($column->Get_Default_Value() != "NULL")
        {
            $column->Set_Default_Value("NULL");
        }
        if(!$column->Is_Column_Nullable())
        {
            $column->Is_Column_Nullable();
        }
        if($column->Does_Auto_Increment())
        {
            $column->Column_Does_Not_Auto_Increments();
        }
        $column->Update_Column();
    } catch (\DatabaseLink\Column_Does_Not_Exist $e)
    {
        $column = new \DatabaseLink\Column('country_code',$phone_number_table,array(
            'COLUMN_TYPE' => 'varchar(10)',
            'COLUMN_DEFAULT' => "NULL",
            'is_nullable' => true,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
function phone_numbers_Validate_Area_Code_Column(\DatabaseLink\Table $phone_number_table)
{
    try
    {
        $column = $phone_number_table->Get_Column('area_code');
        if($column->Get_Column_Key() != "")
        {
            $column->Set_Column_Key("");
        }
        if($column->Get_Data_Type() != "varchar(10)")
        {
            $column->Set_Data_Type("varchar(10)");
        }
        if($column->Get_Default_Value() != "NULL")
        {
            $column->Set_Default_Value("NULL");
        }
        if(!$column->Is_Column_Nullable())
        {
            $column->Is_Column_Nullable();
        }
        if($column->Does_Auto_Increment())
        {
            $column->Column_Does_Not_Auto_Increments();
        }
        $column->Update_Column();
    } catch (\DatabaseLink\Column_Does_Not_Exist $e)
    {
        $column = new \DatabaseLink\Column('area_code',$phone_number_table,array(
            'COLUMN_TYPE' => 'varchar(10)',
            'COLUMN_DEFAULT' => "NULL",
            'is_nullable' => true,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
function phone_numbers_Validate_Prefix_Column(\DatabaseLink\Table $phone_number_table)
{
    try
    {
        $column = $phone_number_table->Get_Column('prefix');
        if($column->Get_Column_Key() != "")
        {
            $column->Set_Column_Key("");
        }
        if($column->Get_Data_Type() != "varchar(10)")
        {
            $column->Set_Data_Type("varchar(10)");
        }
        if($column->Get_Default_Value() != "NULL")
        {
            $column->Set_Default_Value("NULL");
        }
        if(!$column->Is_Column_Nullable())
        {
            $column->Is_Column_Nullable();
        }
        if($column->Does_Auto_Increment())
        {
            $column->Column_Does_Not_Auto_Increments();
        }
        $column->Update_Column();
    } catch (\DatabaseLink\Column_Does_Not_Exist $e)
    {
        $column = new \DatabaseLink\Column('prefix',$phone_number_table,array(
            'COLUMN_TYPE' => 'varchar(10)',
            'COLUMN_DEFAULT' => "NULL",
            'is_nullable' => true,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
function phone_numbers_Validate_Suffix_Column(\DatabaseLink\Table $phone_number_table)
{
    try
    {
        $column = $phone_number_table->Get_Column('suffix');
        if($column->Get_Column_Key() != "")
        {
            $column->Set_Column_Key("");
        }
        if($column->Get_Data_Type() != "varchar(10)")
        {
            $column->Set_Data_Type("varchar(10)");
        }
        if($column->Get_Default_Value() != "NULL")
        {
            $column->Set_Default_Value("NULL");
        }
        if(!$column->Is_Column_Nullable())
        {
            $column->Is_Column_Nullable();
        }
        if($column->Does_Auto_Increment())
        {
            $column->Column_Does_Not_Auto_Increments();
        }
        $column->Update_Column();
    } catch (\DatabaseLink\Column_Does_Not_Exist $e)
    {
        $column = new \DatabaseLink\Column('suffix',$phone_number_table,array(
            'COLUMN_TYPE' => 'varchar(10)',
            'COLUMN_DEFAULT' => "NULL",
            'is_nullable' => true,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
function phone_numbers_Validate_Extension_Column(\DatabaseLink\Table $phone_number_table)
{
    try
    {
        $column = $phone_number_table->Get_Column('ext');
        if($column->Get_Column_Key() != "")
        {
            $column->Set_Column_Key("");
        }
        if($column->Get_Data_Type() != "varchar(10)")
        {
            $column->Set_Data_Type("varchar(10)");
        }
        if($column->Get_Default_Value() != "NULL")
        {
            $column->Set_Default_Value("NULL");
        }
        if(!$column->Is_Column_Nullable())
        {
            $column->Is_Column_Nullable();
        }
        if($column->Does_Auto_Increment())
        {
            $column->Column_Does_Not_Auto_Increments();
        }
        $column->Update_Column();
    } catch (\DatabaseLink\Column_Does_Not_Exist $e)
    {
        $column = new \DatabaseLink\Column('ext',$phone_number_table,array(
            'COLUMN_TYPE' => 'varchar(10)',
            'COLUMN_DEFAULT' => "NULL",
            'is_nullable' => true,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
function phone_numbers_Validate_Type_Column(\DatabaseLink\Table $phone_number_table)
{
    try
    {
        $column = $phone_number_table->Get_Column('type');
        if($column->Get_Column_Key() != "")
        {
            $column->Set_Column_Key("");
        }
        if($column->Get_Data_Type() != "varchar(35)")
        {
            $column->Set_Data_Type("int(11)");
        }
        if($column->Get_Default_Value() != "NA")
        {
            $column->Set_Default_Value("NA");
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
    } catch (\DatabaseLink\Column_Does_Not_Exist $e)
    {
        $column = new \DatabaseLink\Column('type',$phone_number_table,array(
            'COLUMN_TYPE' => 'varchar(35)',
            'COLUMN_DEFAULT' => "NA",
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
function phone_numbers_Validate_Carrier_Column(\DatabaseLink\Table $phone_number_table)
{
    try
    {
        $column = $phone_number_table->Get_Column('carrier');
        if($column->Get_Column_Key() != "")
        {
            $column->Set_Column_Key("");
        }
        if($column->Get_Data_Type() != "varchar(35)")
        {
            $column->Set_Data_Type("varchar(35)");
        }
        if($column->Get_Default_Value() != "")
        {
            $column->Set_Default_Value("");
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
    } catch (\DatabaseLink\Column_Does_Not_Exist $e)
    {
        $column = new \DatabaseLink\Column('carrier',$phone_number_table,array(
            'COLUMN_TYPE' => 'varchar(35)',
            'COLUMN_DEFAULT' => "",
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
function phone_numbers_Validate_Company_Column(\DatabaseLink\Table $phone_number_table)
{
    try{ $column = $phone_number_table->Get_Column('company_id');

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
    } catch (\DatabaseLink\Column_Does_Not_Exist $e)
    {
        $column = new \DatabaseLink\Column('company_id',$phone_number_table,array(
            'COLUMN_TYPE' => 'int(11)',
            'COLUMN_DEFAULT' => null,
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}

?>
