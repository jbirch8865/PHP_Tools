<?php declare(strict_types=1);
global $toolbelt_base;
$toolbelt_base->Rights = new \DatabaseLink\Table('Rights',$toolbelt_base->dblink);
rights_Validate_ID_Column($toolbelt_base->Rights);
rights_Validate_Get_Column($toolbelt_base->Rights);
rights_Validate_Delete_Column($toolbelt_base->Rights);
rights_Validate_Post_Column($toolbelt_base->Rights);
rights_Validate_Patch_Column($toolbelt_base->Rights);
rights_Validate_Put_Column($toolbelt_base->Rights);
$toolbelt_base->Rights->Load_Columns();
function rights_Validate_ID_Column(\DatabaseLink\Table $rights)
{
    try{ $column = $rights->Get_Column('id');
    
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
        $column = new \DatabaseLink\Column('id',$Rights,array(
            'COLUMN_TYPE' => 'int(11)',
            'COLUMN_DEFAULT' => null,
            'is_nullable' => false,
            'column_key' => "PRI",
            'EXTRA' => "AUTO_INCREMENT")
        );
    }
}
function rights_Validate_Get_Column(\DatabaseLink\Table $rights)
{
    try{ $column = $rights->Get_Column('get');
    
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
        $column = new \DatabaseLink\Column('get',$rights,array(
            'COLUMN_TYPE' => 'int(1)',
            'COLUMN_DEFAULT' => '0',
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
function rights_Validate_Delete_Column(\DatabaseLink\Table $rights)
{
    try{ $column = $rights->Get_Column('destroy');
    
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
        $column = new \DatabaseLink\Column('destroy',$rights,array(
            'COLUMN_TYPE' => 'int(1)',
            'COLUMN_DEFAULT' => '0',
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
function rights_Validate_Post_Column(\DatabaseLink\Table $rights)
{
    try{ $column = $rights->Get_Column('post');
    
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
        $column = new \DatabaseLink\Column('post',$rights,array(
            'COLUMN_TYPE' => 'int(1)',
            'COLUMN_DEFAULT' => '0',
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
function rights_Validate_Patch_Column(\DatabaseLink\Table $rights)
{
    try{ $column = $rights->Get_Column('patch');
    
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
        $column = new \DatabaseLink\Column('patch',$rights,array(
            'COLUMN_TYPE' => 'int(1)',
            'COLUMN_DEFAULT' => '0',
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
function rights_Validate_Put_Column(\DatabaseLink\Table $rights)
{
    try{ $column = $rights->Get_Column('put');
    
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
        $column = new \DatabaseLink\Column('put',$rights,array(
            'COLUMN_TYPE' => 'int(1)',
            'COLUMN_DEFAULT' => '0',
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}

?>