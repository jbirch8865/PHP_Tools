<?php declare(strict_types=1);
$toolbelt_base->Programs_Have_Sessions = new \DatabaseLink\Table('Programs_Have_Sessions',$toolbelt_base->dblink);
programs_have_sessions_Validate_ID_Column($toolbelt_base->Programs_Have_Sessions);
programs_have_sessions_Validate_Client_ID_Column($toolbelt_base->Programs_Have_Sessions);
programs_have_sessions_Validate_Access_Token_Column($toolbelt_base->Programs_Have_Sessions);
programs_have_sessions_Validate_User_ID_Column($toolbelt_base->Programs_Have_Sessions);
programs_have_sessions_Validate_Experation_Timestamp_Column($toolbelt_base->Programs_Have_Sessions);
$toolbelt_base->Programs_Have_Sessions->Load_Columns();
function programs_have_sessions_Validate_ID_Column(\DatabaseLink\Table $programs_have_sessions_table)
{
    if($column = $programs_have_sessions_table->Get_Column('id'))
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
        $column = new \DatabaseLink\Column('id',$programs_have_sessions_table,array(
            'COLUMN_TYPE' => 'int(11)',
            'COLUMN_DEFAULT' => null,
            'is_nullable' => false,
            'column_key' => "PRI",
            'EXTRA' => "AUTO_INCREMENT")
        );
    }
}
function programs_have_sessions_Validate_Client_ID_Column(\DatabaseLink\Table $programs_have_sessions_table)
{
    if($column = $programs_have_sessions_table->Get_Column('Client_ID'))
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
        $column = new \DatabaseLink\Column('Client_ID',$programs_have_sessions_table,array(
            'COLUMN_TYPE' => 'varchar(64)',
            'COLUMN_DEFAULT' => null,
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
function programs_have_sessions_Validate_Access_Token_Column(\DatabaseLink\Table $programs_have_sessions_table)
{
    if($column = $programs_have_sessions_table->Get_Column('access_token'))
    {
        if($column->Get_Column_Key() != "UNI")
        {
            $column->Set_Column_Key("UNI"); 
        }
        if($column->Get_Data_Type() != "varchar(45)")
        {
            $column->Set_Data_Type("varchar(45)");
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
        $column = new \DatabaseLink\Column('access_token',$programs_have_sessions_table,array(
            'COLUMN_TYPE' => 'varchar(45)',
            'COLUMN_DEFAULT' => null,
            'is_nullable' => false,
            'column_key' => "UNI",
            'EXTRA' => "")
        );
    }
}
function programs_have_sessions_Validate_User_ID_Column(\DatabaseLink\Table $programs_have_sessions_table)
{
    if($column = $programs_have_sessions_table->Get_Column('user_id'))
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
        $column = new \DatabaseLink\Column('user_id',$programs_have_sessions_table,array(
            'COLUMN_TYPE' => 'int(11)',
            'COLUMN_DEFAULT' => null,
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
function programs_have_sessions_Validate_Experation_Timestamp_Column(\DatabaseLink\Table $programs_have_sessions_table)
{
    if($column = $programs_have_sessions_table->Get_Column('experation_timestamp'))
    {
        if($column->Get_Column_Key() != "")
        {
            $column->Set_Column_Key(""); 
        }
        if($column->Get_Data_Type() != "timestamp")
        {
            $column->Set_Data_Type("timestamp");
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
        $column = new \DatabaseLink\Column('experation_timestamp',$programs_have_sessions_table,array(
            'COLUMN_TYPE' => 'timestamp',
            'COLUMN_DEFAULT' => null,
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
?>