<?php declare(strict_types=1);
namespace Authentication;
global $dblink;
global $cConfigs;
$user_table = new \DatabaseLink\Table('Users',$dblink);
Validate_ID_Column($user_table);
Validate_Username_Column($user_table);
Validate_Company_ID_Column($user_table);
Validate_Project_Name_Column($user_table);
Validate_CSPRING_Column($user_table);
Validate_User_Active_Status_Column($user_table);
Validate_Password_Column($user_table);
Create_Backend_User_If_Not_Already($cConfigs);
function Validate_User_Active_Status_Column(\DatabaseLink\Table $company_table)
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

function Validate_CSPRING_Column(\DatabaseLink\Table $user_table)
{
    global $cConfigs;
    if($column = $user_table->Get_Column('cspring'))
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
    }else
    {
        $column = new \DatabaseLink\Column('cspring',$user_table,array(
            'COLUMN_TYPE' => 'varchar(64)',
            'COLUMN_DEFAULT' => null,
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
function Validate_Project_Name_Column(\DatabaseLink\Table $user_table)
{
    global $cConfigs;
    if($column = $user_table->Get_Column('project_name'))
    {
        if($column->Get_Column_Key() != "")
        {
            $column->Set_Column_Key(""); 
        }
        if($column->Get_Data_Type() != "varchar(20)")
        {
            $column->Set_Data_Type("varchar(20)");
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
    }else
    {
        $column = new \DatabaseLink\Column('project_name',$user_table,array(
            'COLUMN_TYPE' => 'varchar(20)',
            'COLUMN_DEFAULT' => null,
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
function Validate_Company_ID_Column(\DatabaseLink\Table $user_table)
{
    if($column = $user_table->Get_Column('company_id'))
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
        $company_table = new \DatabaseLink\Table('Companies',$user_table->database_dblink);
        $column->Add_Constraint_If_Does_Not_Exist(new \DatabaseLink\Column('id',$company_table));
    }else
    {
        $column = new \DatabaseLink\Column('company_id',$user_table,array(
            'COLUMN_TYPE' => 'INT(11)',
            'COLUMN_DEFAULT' => "1",
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
        $company_table = new \DatabaseLink\Table('Companies',$user_table->database_dblink);
        $column->Add_Constraint_If_Does_Not_Exist(new \DatabaseLink\Column('id',new \DatabaseLink\Table('Companies',$company_table)));
    }
}
function Validate_Username_Column(\DatabaseLink\Table $user_table)
{
    if($column = $user_table->Get_Column('username'))
    {
        if($column->Get_Column_Key() != "")
        {
            $column->Set_Column_Key(""); 
        }
        if($column->Get_Data_Type() != "varchar(25)")
        {
            $column->Set_Data_Type("varchar(25)");
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
    }else
    {
        $column = new \DatabaseLink\Column('username',$user_table,array(
            'COLUMN_TYPE' => 'varchar(25)',
            'COLUMN_DEFAULT' => null,
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
function Validate_ID_Column(\DatabaseLink\Table $user_table)
{
    if($column = $user_table->Get_Column('id'))
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
        $column = new \DatabaseLink\Column('id',$user_table,array(
            'COLUMN_TYPE' => 'int(11)',
            'COLUMN_DEFAULT' => null,
            'is_nullable' => false,
            'column_key' => "PRI",
            'EXTRA' => "auto_increment")
        );
    }
}
function Validate_Password_Column(\DatabaseLink\Table $user_table)
{
    global $cConfigs;
    if($column = $user_table->Get_Column('verified_hashed_password'))
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
    }else
    {
        $column = new \DatabaseLink\Column('verified_hashed_password',$user_table,array(
            'COLUMN_TYPE' => 'varchar(64)',
            'COLUMN_DEFAULT' => null,
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}

function Create_Backend_User_If_Not_Already(\config\ConfigurationFile $cConfigs)
{
    $user = new User($cConfigs->Get_Name_Of_Project(),$cConfigs->Get_Value_If_Enabled($cConfigs->Get_Name_Of_Project().'_password'),1,true);
}
?>