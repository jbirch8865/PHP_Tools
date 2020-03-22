<?php declare(strict_types=1);
global $program_table;
$program_table = new \DatabaseLink\Table('Programs',$dblink);
program_Validate_ID_Column($program_table);
program_Validate_Project_Name_Column($program_table);
program_Validate_Secret_Column($program_table);
program_Validate_Client_ID_Column($program_table);
program_Validate_Active_Status_Column($company_table);
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
function program_Validate_Project_Name_Column(\DatabaseLink\Table $program_table)
{
    if($column = $program_table->Get_Column('project_name'))
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
        $column = new \DatabaseLink\Column('project_name',$program_table,array(
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
        $column = new \DatabaseLink\Column('secret',$program_table,array(
            'COLUMN_TYPE' => 'varchar(64)',
            'COLUMN_DEFAULT' => null,
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
function program_Validate_Client_ID_Column(\DatabaseLink\Table $program_table)
{
    if($column = $program_table->Get_Column('client_id'))
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
        $column = new \DatabaseLink\Column('client_id',$program_table,array(
            'COLUMN_TYPE' => 'varchar(64)',
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