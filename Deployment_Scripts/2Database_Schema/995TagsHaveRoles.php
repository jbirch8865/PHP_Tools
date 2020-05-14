<?php declare(strict_types=1);
$toolbelt_base->Tags_Have_Roles = new \DatabaseLink\Table('Tags_Have_Roles',$toolbelt_base->dblink);
tags_have_roles_Validate_ID_Column($toolbelt_base->Tags_Have_Roles);
tags_have_roles_Validate_Tag_ID_Column($toolbelt_base->Tags_Have_Roles);
tags_have_roles_Validate_Role_ID_Column($toolbelt_base->Tags_Have_Roles);
tags_have_roles_Validate_Get_Column($toolbelt_base->Tags_Have_Roles);
tags_have_roles_Validate_Delete_Column($toolbelt_base->Tags_Have_Roles);
tags_have_roles_Validate_Post_Column($toolbelt_base->Tags_Have_Roles);
$toolbelt_base->Tags_Have_Roles->Load_Columns();
function tags_have_roles_Validate_ID_Column(\DatabaseLink\Table $Tags)
{
    try
    {
        $column = $Tags->Get_Column('id');
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
        $column = new \DatabaseLink\Column('id',$Tags,array(
            'COLUMN_TYPE' => 'int(11)',
            'COLUMN_DEFAULT' => null,
            'is_nullable' => false,
            'column_key' => "PRI",
            'EXTRA' => "AUTO_INCREMENT",
            'COLUMN_COMMENT' => 'exclude')
        );
    }
}
function tags_have_roles_Validate_Tag_ID_Column(\DatabaseLink\Table $Tags)
{
    try
    {
        $column = $Tags->Get_Column('tag_id');
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
        $column = new \DatabaseLink\Column('tag_id',$Tags,array(
            'COLUMN_TYPE' => 'int(11)',
            'COLUMN_DEFAULT' => null,
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
function tags_have_roles_Validate_Role_ID_Column(\DatabaseLink\Table $Tags)
{
    try{ $column = $Tags->Get_Column('role_id');

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
        $column = new \DatabaseLink\Column('role_id',$Tags,array(
            'COLUMN_TYPE' => 'int(11)',
            'COLUMN_DEFAULT' => null,
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
function tags_have_roles_Validate_Get_Column(\DatabaseLink\Table $tags_have_roles)
{
    try{ $column = $tags_have_roles->Get_Column('get');

        if($column->Get_Column_Key() != "")
        {
            $column->Set_Column_Key("");
        }
        if($column->Get_Data_Type() != "int(1)")
        {
            $column->Set_Data_Type("int(1)");
        }
        if($column->Get_Default_Value() != '0')
        {
            $column->Set_Default_Value('0');
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
        $column = new \DatabaseLink\Column('get',$tags_have_roles,array(
            'COLUMN_TYPE' => 'int(1)',
            'COLUMN_DEFAULT' => '0',
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
function tags_have_roles_Validate_Delete_Column(\DatabaseLink\Table $tags_have_roles)
{
    try{ $column = $tags_have_roles->Get_Column('destroy');

        if($column->Get_Column_Key() != "")
        {
            $column->Set_Column_Key("");
        }
        if($column->Get_Data_Type() != "int(1)")
        {
            $column->Set_Data_Type("int(1)");
        }
        if($column->Get_Default_Value() != '0')
        {
            $column->Set_Default_Value('0');
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
        $column = new \DatabaseLink\Column('destroy',$tags_have_roles,array(
            'COLUMN_TYPE' => 'int(1)',
            'COLUMN_DEFAULT' => '0',
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
function tags_have_roles_Validate_Post_Column(\DatabaseLink\Table $tags_have_roles)
{
    try{ $column = $tags_have_roles->Get_Column('post');

        if($column->Get_Column_Key() != "")
        {
            $column->Set_Column_Key("");
        }
        if($column->Get_Data_Type() != "int(1)")
        {
            $column->Set_Data_Type("int(1)");
        }
        if($column->Get_Default_Value() != '0')
        {
            $column->Set_Default_Value('0');
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
        $column = new \DatabaseLink\Column('post',$tags_have_roles,array(
            'COLUMN_TYPE' => 'int(1)',
            'COLUMN_DEFAULT' => '0',
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
?>
