<?php declare(strict_types=1);
$toolbelt_base->Peoples = new \DatabaseLink\Table('Peoples',$toolbelt_base->dblink);
Peoples_Validate_ID_Column($toolbelt_base->Peoples);
Peoples_Validate_First_Name_Column($toolbelt_base->Peoples);
Peoples_Validate_Last_Name_Column($toolbelt_base->Peoples);
Peoples_Validate_Title_Column($toolbelt_base->Peoples);
Peoples_Validate_Email_Column($toolbelt_base->Peoples);
Peoples_Validate_Description_Column($toolbelt_base->Peoples);
Peoples_Validate_User_Active_Status_Column($toolbelt_base->Peoples);
Peoples_Validate_Company_Column($toolbelt_base->Peoples);

$toolbelt_base->Peoples->Load_Columns();
function Peoples_Validate_ID_Column(\DatabaseLink\Table $Peoples)
{
    try{ $column = $Peoples->Get_Column('id');

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
        $column = new \DatabaseLink\Column('id',$Peoples,array(
            'COLUMN_TYPE' => 'int(11)',
            'COLUMN_DEFAULT' => null,
            'is_nullable' => false,
            'column_key' => "PRI",
            'EXTRA' => "AUTO_INCREMENT")
        );
    }
}
function Peoples_Validate_First_Name_Column(\DatabaseLink\Table $Peoples)
{
    try{ $column = $Peoples->Get_Column('first_name');

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
        $column = new \DatabaseLink\Column('first_name',$Peoples,array(
            'COLUMN_TYPE' => 'varchar(30)',
            'COLUMN_DEFAULT' => "",
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
function Peoples_Validate_Last_Name_Column(\DatabaseLink\Table $Peoples)
{
    try{ $column = $Peoples->Get_Column('last_name');

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
        $column = new \DatabaseLink\Column('last_name',$Peoples,array(
            'COLUMN_TYPE' => 'varchar(30)',
            'COLUMN_DEFAULT' => "",
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
function Peoples_Validate_Title_Column(\DatabaseLink\Table $Peoples)
{
    try{ $column = $Peoples->Get_Column('title');

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
        $column = new \DatabaseLink\Column('title',$Peoples,array(
            'COLUMN_TYPE' => 'varchar(75)',
            'COLUMN_DEFAULT' => "",
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
function Peoples_Validate_Email_Column(\DatabaseLink\Table $Peoples)
{
    try{ $column = $Peoples->Get_Column('email');

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
        $column = new \DatabaseLink\Column('email',$Peoples,array(
            'COLUMN_TYPE' => 'varchar(75)',
            'COLUMN_DEFAULT' => "",
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
function Peoples_Validate_Description_Column(\DatabaseLink\Table $Peoples)
{
    try{ $column = $Peoples->Get_Column('description');

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
        $column = new \DatabaseLink\Column('description',$Peoples,array(
            'COLUMN_TYPE' => 'varchar(255)',
            'COLUMN_DEFAULT' => "",
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
function Peoples_Validate_User_Active_Status_Column(\DatabaseLink\Table $Peoples)
{
    try
    {
        $column = $Peoples->Get_Column('active_status');
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
        $column = new \DatabaseLink\Column('active_status',$Peoples,array(
            'COLUMN_TYPE' => 'INT(11)',
            'COLUMN_DEFAULT' => "1",
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
function Peoples_Validate_Company_Column(\DatabaseLink\Table $Peoples)
{
    try{ $column = $Peoples->Get_Column('company_id');

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
        $column = new \DatabaseLink\Column('company_id',$Peoples,array(
            'COLUMN_TYPE' => 'int(11)',
            'COLUMN_DEFAULT' => null,
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}

?>
