<?php declare(strict_types=1);
$toolbelt_base->Programs = new \DatabaseLink\Table('Programs',$toolbelt_base->dblink);
program_Validate_ID_Column($toolbelt_base->Programs);
program_Validate_Program_Name_Column($toolbelt_base->Programs);
program_Validate_Secret_Column($toolbelt_base->Programs);
program_Validate_Client_ID_Column($toolbelt_base->Programs);
program_Validate_Active_Status_Column($toolbelt_base->Companies);
$toolbelt_base->Programs->Load_Columns();
function program_Validate_ID_Column(\DatabaseLink\Table $program_table)
{
    if($column = $program_table->Get_Column('id'))
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
        $column = new \DatabaseLink\Column('id',$program_table,array(
            'COLUMN_TYPE' => 'int(11)',
            'COLUMN_DEFAULT' => null,
            'is_nullable' => false,
            'column_key' => "PRI",
            'EXTRA' => "AUTO_INCREMENT")
        );
    }
}
function program_Validate_Program_Name_Column(\DatabaseLink\Table $program_table)
{
    if($column = $program_table->Get_Column('program_name'))
    {
        if($column->Get_Column_Key() != "UNI")
        {
            $column->Set_Column_Key("UNI"); 
        }
        if($column->Get_Data_Type() != "varchar(64)")
        {
            $column->Set_Data_Type("varchar(64)");
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
        $column = new \DatabaseLink\Column('program_name',$program_table,array(
            'COLUMN_TYPE' => 'varchar(64)',
            'COLUMN_DEFAULT' => null,
            'is_nullable' => false,
            'column_key' => "UNI",
            'EXTRA' => "")
        );
    }
}
function program_Validate_Secret_Column(\DatabaseLink\Table $program_table)
{
    if($column = $program_table->Get_Column('secret'))
    {
        if($column->Get_Column_Key() != "")
        {
            $column->Set_Column_Key(""); 
        }
        if($column->Get_Data_Type() != "varchar(48)")
        {
            $column->Set_Data_Type("varchar(48)");
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
        $column = new \DatabaseLink\Column('secret',$program_table,array(
            'COLUMN_TYPE' => 'varchar(48)',
            'COLUMN_DEFAULT' => null,
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
function program_Validate_Client_ID_Column(\DatabaseLink\Table $program_table)
{
    if($column = $program_table->Get_Column('Client_ID'))
    {
        if($column->Get_Column_Key() != "UNI")
        {
            $column->Set_Column_Key("UNI"); 
        }
        if($column->Get_Data_Type() != "varchar(32)")
        {
            $column->Set_Data_Type("varchar(32)");
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
        $column = new \DatabaseLink\Column('Client_ID',$program_table,array(
            'COLUMN_TYPE' => 'varchar(32)',
            'COLUMN_DEFAULT' => null,
            'is_nullable' => false,
            'column_key' => "UNI",
            'EXTRA' => "")
        );
    }
}
function program_Validate_Active_Status_Column(\DatabaseLink\Table $program_table)
{
    if($column = $program_table->Get_Column('active_status'))
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
        $column = new \DatabaseLink\Column('active_status',$program_table,array(
            'COLUMN_TYPE' => 'INT(11)',
            'COLUMN_DEFAULT' => "1",
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
?>