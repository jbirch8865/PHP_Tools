<?php declare(strict_types=1);
$toolbelt_base->Tags = new \DatabaseLink\Table('Tags',$toolbelt_base->dblink);
tags_Validate_ID_Column($toolbelt_base->Tags);
tags_Validate_Name_Column($toolbelt_base->Tags);
tags_Validate_Company_Column($toolbelt_base->Tags);
$toolbelt_base->Tags->Load_Columns();

function tags_Validate_ID_Column(\DatabaseLink\Table $Tags)
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
function tags_Validate_Name_Column(\DatabaseLink\Table $Tags)
{
    try
    {
        $column = $Tags->Get_Column('name');
        if($column->Get_Column_Key() != "")
        {
            $column->Set_Column_Key("");
        }
        if($column->Get_Data_Type() != "varchar(75)")
        {
            $column->Set_Data_Type("varchar(75)");
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
        $column = new \DatabaseLink\Column('name',$Tags,array(
            'COLUMN_TYPE' => 'varchar(75)',
            'COLUMN_DEFAULT' => null,
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
function tags_Validate_Company_Column(\DatabaseLink\Table $Tags)
{
    try{ $column = $Tags->Get_Column('company_id');

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
        $column = new \DatabaseLink\Column('company_id',$Tags,array(
            'COLUMN_TYPE' => 'int(11)',
            'COLUMN_DEFAULT' => null,
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}

?>
