<?php declare(strict_types=1);
global $toolbelt_base;
$toolbelt_base->Routes = new \DatabaseLink\Table('Routes',$toolbelt_base->dblink);
routes_Validate_ID_Column($toolbelt_base->Routes);
routes_Validate_Route_Column($toolbelt_base->Routes);
routes_Validate_Implicit_Allow($toolbelt_base->Routes);
routes_Validate_Module_Column($toolbelt_base->Routes);
$toolbelt_base->Routes->Load_Columns();
function routes_Validate_ID_Column(\DatabaseLink\Table $routes)
{
    try{ $column = $routes->Get_Column('id');
    
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
        $column = new \DatabaseLink\Column('id',$routes,array(
            'COLUMN_TYPE' => 'int(11)',
            'COLUMN_DEFAULT' => null,
            'is_nullable' => false,
            'column_key' => "PRI",
            'EXTRA' => "AUTO_INCREMENT")
        );
    }
}
function routes_Validate_Route_Column(\DatabaseLink\Table $routes)
{
    try{ $column = $routes->Get_Column('name');
    
        if($column->Get_Column_Key() != "UNI")
        {
            $column->Set_Column_Key("UNI"); 
        }
        if($column->Get_Data_Type() != "varchar(250)")
        {
            $column->Set_Data_Type("varchar(250)");
        }
        if($column->Get_Default_Value() != '')
        {
            $column->Set_Default_Value('');
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
        $column = new \DatabaseLink\Column('name',$routes,array(
            'COLUMN_TYPE' => 'varchar(250)',
            'COLUMN_DEFAULT' => '',
            'is_nullable' => false,
            'column_key' => "UNI",
            'EXTRA' => "")
        );
    }
}
function routes_Validate_Implicit_Allow(\DatabaseLink\Table $routes)
{
    try{ $column = $routes->Get_Column('implicit_allow');
    
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
        $column = new \DatabaseLink\Column('implicit_allow',$routes,array(
            'COLUMN_TYPE' => 'int(1)',
            'COLUMN_DEFAULT' => '0',
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
function routes_Validate_Module_Column(\DatabaseLink\Table $routes)
{
    try{ $column = $routes->Get_Column('module');
    
        if($column->Get_Column_Key() != "")
        {
            $column->Set_Column_Key(""); 
        }
        if($column->Get_Data_Type() != "varchar(250)")
        {
            $column->Set_Data_Type("varchar(250)");
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
        $column = new \DatabaseLink\Column('module',$routes,array(
            'COLUMN_TYPE' => 'varchar(250)',
            'COLUMN_DEFAULT' => null,
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
?>