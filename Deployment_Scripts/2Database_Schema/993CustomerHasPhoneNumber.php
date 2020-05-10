<?php declare(strict_types=1);
$toolbelt_base->Customer_Has_Phone_Numbers = new \DatabaseLink\Table('Customer_Has_Phone_Numbers',$toolbelt_base->dblink);
customer_has_phone_numbers_Validate_ID_Column($toolbelt_base->Customer_Has_Phone_Numbers);
customer_has_phone_numbers_Validate_Customer_ID_Column($toolbelt_base->Customer_Has_Phone_Numbers);
customer_has_phone_numbers_Validate_Phone_Number_ID_Column($toolbelt_base->Customer_Has_Phone_Numbers);
$toolbelt_base->Customer_Has_Phone_Numbers->Load_Columns();

function customer_has_phone_numbers_Validate_ID_Column(\DatabaseLink\Table $Customer_Has_Phone_Numbers)
{
    try
    {
        $column = $Customer_Has_Phone_Numbers->Get_Column('id');
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
        $column = new \DatabaseLink\Column('id',$Customer_Has_Phone_Numbers,array(
            'COLUMN_TYPE' => 'int(11)',
            'COLUMN_DEFAULT' => null,
            'is_nullable' => false,
            'column_key' => "PRI",
            'EXTRA' => "AUTO_INCREMENT",
            'COLUMN_COMMENT' => 'exclude')
        );
    }
}
function customer_has_phone_numbers_Validate_Customer_ID_Column(\DatabaseLink\Table $Customer_Has_Phone_Numbers)
{
    try
    {
        $column = $Customer_Has_Phone_Numbers->Get_Column('customer_id');
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
        $column = new \DatabaseLink\Column('customer_id',$Customer_Has_Phone_Numbers,array(
            'COLUMN_TYPE' => 'int(11)',
            'COLUMN_DEFAULT' => null,
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
function customer_has_phone_numbers_Validate_Phone_Number_ID_Column(\DatabaseLink\Table $Customer_Has_Phone_Numbers)
{
    try
    {
        $column = $Customer_Has_Phone_Numbers->Get_Column('phone_number_id');
        if($column->Get_Column_Key() != "UNI")
        {
            $column->Set_Column_Key("UNI");
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
        $column = new \DatabaseLink\Column('phone_number_id',$Customer_Has_Phone_Numbers,array(
            'COLUMN_TYPE' => 'int(11)',
            'COLUMN_DEFAULT' => null,
            'is_nullable' => false,
            'column_key' => "UNI",
            'EXTRA' => "")
        );
    }
}
?>
