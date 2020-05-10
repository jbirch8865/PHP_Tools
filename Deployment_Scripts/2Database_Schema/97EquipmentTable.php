<?php declare(strict_types=1);
$toolbelt_base->Equipments = new \DatabaseLink\Table('Equipments',$toolbelt_base->dblink);
equipments_Validate_ID_Column($toolbelt_base->Equipments);
equipments_Validate_Equipment_Name_Column($toolbelt_base->Equipments);
equipments_Validate_Company_Column($toolbelt_base->Equipments);
equipments_Validate_Active_Status_Column($toolbelt_base->Equipments);
$toolbelt_base->Equipments->Load_Columns();
function equipments_Validate_ID_Column(\DatabaseLink\Table $equipment_table)
{
    try
    {
        $column = $equipment_table->Get_Column('id');
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
        $column = new \DatabaseLink\Column('id',$equipment_table,array(
            'COLUMN_TYPE' => 'int(11)',
            'COLUMN_DEFAULT' => null,
            'is_nullable' => false,
            'column_key' => "PRI",
            'EXTRA' => "AUTO_INCREMENT")
        );
    }
}
function equipments_Validate_Equipment_Name_Column(\DatabaseLink\Table $equipment_table)
{
    try
    {
        $column = $equipment_table->Get_Column('equipment_name');
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
        $column = new \DatabaseLink\Column('equipment_name',$equipment_table,array(
            'COLUMN_TYPE' => 'varchar(75)',
            'COLUMN_DEFAULT' => null,
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
function equipments_Validate_Company_Column(\DatabaseLink\Table $equipment_table)
{
    try{ $column = $equipment_table->Get_Column('company_id');

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
        $column = new \DatabaseLink\Column('company_id',$equipment_table,array(
            'COLUMN_TYPE' => 'int(11)',
            'COLUMN_DEFAULT' => null,
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
function equipments_Validate_Active_Status_Column(\DatabaseLink\Table $equipment_table)
{
    try
    {
        $column = $equipment_table->Get_Column('active_status');
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
        $column = new \DatabaseLink\Column('active_status',$equipment_table,array(
            'COLUMN_TYPE' => 'INT(11)',
            'COLUMN_DEFAULT' => "1",
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}

?>
