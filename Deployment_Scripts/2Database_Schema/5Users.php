<?php declare(strict_types=1);
$toolbelt_base->Users = new \DatabaseLink\Table('Users',$toolbelt_base->dblink);
user_Validate_ID_Column($toolbelt_base->Users);
user_Validate_Username_Column($toolbelt_base->Users);
user_Validate_Company_ID_Column($toolbelt_base->Users);
user_Validate_Project_Name_Column($toolbelt_base->Users);
user_Validate_CSPRING_Column($toolbelt_base->Users);
user_Validate_User_Active_Status_Column($toolbelt_base->Users);
user_Validate_Password_Column($toolbelt_base->Users);
$toolbelt_base->Users->Load_Columns();
ADODB_Active_Record::TableKeyHasMany('Companies','id','Users','company_id','\Company\Company');
ADODB_Active_Record::TableBelongsTo('Users','Companies','company_id','id','\Company\Company');

function user_Validate_ID_Column(\DatabaseLink\Table $user_table)
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
function user_Validate_Username_Column(\DatabaseLink\Table $user_table)
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
function user_Validate_Company_ID_Column(\DatabaseLink\Table $user_table)
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
    }else
    {
        $column = new \DatabaseLink\Column('company_id',$user_table,array(
            'COLUMN_TYPE' => 'INT(11)',
            'COLUMN_DEFAULT' => "1",
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
function user_Validate_Project_Name_Column(\DatabaseLink\Table $user_table)
{
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
function user_Validate_CSPRING_Column(\DatabaseLink\Table $user_table)
{
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
        if($column->Am_I_Included_In_Response())
        {
            $column->Exclude_From_Response();
        }
    }else
    {
        $column = new \DatabaseLink\Column('cspring',$user_table,array(
            'COLUMN_TYPE' => 'varchar(64)',
            'COLUMN_DEFAULT' => null,
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "",
            'COLUMN_COMMENTS' => 'exclude')
        );
    }
}
function user_Validate_User_Active_Status_Column(\DatabaseLink\Table $company_table)
{
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
function user_Validate_Password_Column(\DatabaseLink\Table $user_table)
{
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
        if($column->Am_I_Included_In_Response())
        {
            $column->Exclude_From_Response();
        }
    }else
    {
        $column = new \DatabaseLink\Column('verified_hashed_password',$user_table,array(
            'COLUMN_TYPE' => 'varchar(64)',
            'COLUMN_DEFAULT' => null,
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "",
            'COLUMN_COMMENTS' => 'exclude')
        );
    }
}
?>