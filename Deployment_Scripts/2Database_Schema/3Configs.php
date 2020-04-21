<?php declare(strict_types=1);
$toolbelt_base->Configs = new \DatabaseLink\Table('Configs',$toolbelt_base->dblink);
config_Validate_ID_Column($toolbelt_base->Configs);
config_Validate_Active_Status_Column($toolbelt_base->Configs);
config_Validate_Config_Name_Column($toolbelt_base->Configs);
config_Validate_Default_Value_Column($toolbelt_base->Configs);
$toolbelt_base->Configs->Load_Columns();
function config_Validate_ID_Column(\DatabaseLink\Table $config_table)
{
    try
    {
        $column = $config_table->Get_Column('id');
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
        $column = new \DatabaseLink\Column('id',$config_table,array(
            'COLUMN_TYPE' => 'int(11)',
            'COLUMN_DEFAULT' => null,
            'is_nullable' => false,
            'column_key' => "PRI",
            'EXTRA' => "AUTO_INCREMENT")
        );
    }
}
function config_Validate_Active_Status_Column(\DatabaseLink\Table $config_table)
{
    try
    {
        $column = $config_table->Get_Column('active_status');
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
    } catch (\DatabaseLink\Column_Does_Not_Exist $e)
    {
        $column = new \DatabaseLink\Column('active_status',$config_table,array(
            'COLUMN_TYPE' => 'INT(11)',
            'COLUMN_DEFAULT' => "1",
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
function config_Validate_Config_Name_Column(\DatabaseLink\Table $config_table)
{
    try
    {
        $column = $config_table->Get_Column('config_name');
        if($column->Get_Column_Key() != "UNI")
        {
            $column->Set_Column_Key("UNI");
        }
        if($column->Get_Data_Type() != "varchar(35)")
        {
            $column->Set_Data_Type("varchar(35)");
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
        $column = new \DatabaseLink\Column('config_name',$config_table,array(
            'COLUMN_TYPE' => 'varchar(35)',
            'COLUMN_DEFAULT' => null,
            'is_nullable' => false,
            'column_key' => "UNI",
            'EXTRA' => "")
        );
    }
}
function config_Validate_Default_Value_Column(\DatabaseLink\Table $config_table)
{
    try
    {
        $column = $config_table->Get_Column('default_value');
        if($column->Get_Column_Key() != "")
        {
            $column->Set_Column_Key("");
        }
        if($column->Get_Data_Type() != "varchar(200)")
        {
            $column->Set_Data_Type("varchar(200)");
        }
        if($column->Get_Default_Value() != "NULL")
        {
            $column->Set_Default_Value("NULL");
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
        $column = new \DatabaseLink\Column('default_value',$config_table,array(
            'COLUMN_TYPE' => 'varchar(200)',
            'COLUMN_DEFAULT' => "NULL",
            'is_nullable' => true,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}

?>
