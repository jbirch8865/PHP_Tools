<?php declare(strict_types=1);
$toolbelt_base->Users_Have_Roles = new \DatabaseLink\Table('Users_Have_Roles',$toolbelt_base->dblink);
users_have_roles_Validate_ID_Column($toolbelt_base->Users_Have_Roles);
users_have_roles_Validate_User_ID_Column($toolbelt_base->Users_Have_Roles);
users_have_roles_Validate_Role_ID_Column($toolbelt_base->Users_Have_Roles);
$toolbelt_base->Users_Have_Roles->Load_Columns();
ADODB_Active_Record::TableHasMany('Users','Users_Have_Roles','user_id','\Authentication\User_Role');

function users_have_roles_Validate_ID_Column(\DatabaseLink\Table $Users_Have_Roles)
{
    if($column = $Users_Have_Roles->Get_Column('id'))
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
        $column = new \DatabaseLink\Column('id',$Users_Have_Roles,array(
            'COLUMN_TYPE' => 'int(11)',
            'COLUMN_DEFAULT' => null,
            'is_nullable' => false,
            'column_key' => "PRI",
            'EXTRA' => "AUTO_INCREMENT")
        );
    }
}
function users_have_roles_Validate_User_ID_Column(\DatabaseLink\Table $Users_Have_Roles)
{
    if($column = $Users_Have_Roles->Get_Column('user_id'))
    {
        if($column->Get_Column_Key() != "")
        {
            $column->Set_Column_Key(""); 
        }
        if($column->Get_Data_Type() != "int(11)")
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
        $column = new \DatabaseLink\Column('user_id',$Users_Have_Roles,array(
            'COLUMN_TYPE' => 'int(11)',
            'COLUMN_DEFAULT' => null,
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
function users_have_roles_Validate_Role_ID_Column(\DatabaseLink\Table $Users_Have_Roles)
{
    if($column = $Users_Have_Roles->Get_Column('role_id'))
    {
        if($column->Get_Column_Key() != "")
        {
            $column->Set_Column_Key(""); 
        }
        if($column->Get_Data_Type() != "int(11)")
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
        $column = new \DatabaseLink\Column('access_token',$Users_Have_Roles,array(
            'COLUMN_TYPE' => 'int(11)',
            'COLUMN_DEFAULT' => null,
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
?>