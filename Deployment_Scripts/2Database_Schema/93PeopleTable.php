<?php declare(strict_types=1);
$toolbelt_base->People = new \DatabaseLink\Table('People',$toolbelt_base->dblink);
people_Validate_ID_Column($toolbelt_base->People);
people_Validate_First_Name_Column($toolbelt_base->People);
people_Validate_Last_Name_Column($toolbelt_base->People);
people_Validate_Title_Column($toolbelt_base->People);
people_Validate_Email_Column($toolbelt_base->People);
people_Validate_Description_Column($toolbelt_base->People);
People_Validate_User_Active_Status_Column($toolbelt_base->People);
$toolbelt_base->People->Load_Columns();
function people_Validate_ID_Column(\DatabaseLink\Table $People)
{
    try{ $column = $People->Get_Column('id');

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
        $column = new \DatabaseLink\Column('id',$People,array(
            'COLUMN_TYPE' => 'int(11)',
            'COLUMN_DEFAULT' => null,
            'is_nullable' => false,
            'column_key' => "PRI",
            'EXTRA' => "AUTO_INCREMENT")
        );
    }
}
function people_Validate_First_Name_Column(\DatabaseLink\Table $People)
{
    try{ $column = $People->Get_Column('first_name');

        if($column->Get_Column_Key() != "")
        {
            $column->Set_Column_Key("");
        }
        if($column->Get_Data_Type() != "varchar(30)")
        {
            $column->Set_Data_Type("varchar(30)");
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
        $column = new \DatabaseLink\Column('first_name',$People,array(
            'COLUMN_TYPE' => 'varchar(30)',
            'COLUMN_DEFAULT' => "",
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
function people_Validate_Last_Name_Column(\DatabaseLink\Table $People)
{
    try{ $column = $People->Get_Column('last_name');

        if($column->Get_Column_Key() != "")
        {
            $column->Set_Column_Key("");
        }
        if($column->Get_Data_Type() != "varchar(30)")
        {
            $column->Set_Data_Type("varchar(30)");
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
        $column = new \DatabaseLink\Column('last_name',$People,array(
            'COLUMN_TYPE' => 'varchar(30)',
            'COLUMN_DEFAULT' => "",
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
function people_Validate_Title_Column(\DatabaseLink\Table $People)
{
    try{ $column = $People->Get_Column('title');

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
        $column = new \DatabaseLink\Column('title',$People,array(
            'COLUMN_TYPE' => 'varchar(75)',
            'COLUMN_DEFAULT' => "",
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
function people_Validate_Email_Column(\DatabaseLink\Table $People)
{
    try{ $column = $People->Get_Column('email');

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
        $column = new \DatabaseLink\Column('email',$People,array(
            'COLUMN_TYPE' => 'varchar(75)',
            'COLUMN_DEFAULT' => "",
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
function people_Validate_Description_Column(\DatabaseLink\Table $People)
{
    try{ $column = $People->Get_Column('description');

        if($column->Get_Column_Key() != "")
        {
            $column->Set_Column_Key("");
        }
        if($column->Get_Data_Type() != "varchar(255)")
        {
            $column->Set_Data_Type("varchar(255)");
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
        $column = new \DatabaseLink\Column('description',$People,array(
            'COLUMN_TYPE' => 'varchar(255)',
            'COLUMN_DEFAULT' => "",
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
function People_Validate_User_Active_Status_Column(\DatabaseLink\Table $People)
{
    try
    {
        $column = $People->Get_Column('active_status');
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
        $column = new \DatabaseLink\Column('active_status',$People,array(
            'COLUMN_TYPE' => 'INT(11)',
            'COLUMN_DEFAULT' => "1",
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}

?>
