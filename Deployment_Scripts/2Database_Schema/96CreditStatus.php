<?php declare(strict_types=1);
$toolbelt_base->Credit_Statuses = new \DatabaseLink\Table('Credit_Statuses',$toolbelt_base->dblink);
credit_statuses_Validate_ID_Column($toolbelt_base->Credit_Statuses);
credit_statuses_Validate_Credit_Status_Name_Column($toolbelt_base->Credit_Statuses);
credit_statuses_Validate_Company_Column($toolbelt_base->Credit_Statuses);
credit_statuses_Validate_Active_Status_Column($toolbelt_base->Credit_Statuses);
$toolbelt_base->Credit_Statuses->Load_Columns();
function credit_statuses_Validate_ID_Column(\DatabaseLink\Table $credit_status_table)
{
    try
    {
        $column = $credit_status_table->Get_Column('id');
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
        $column = new \DatabaseLink\Column('id',$credit_status_table,array(
            'COLUMN_TYPE' => 'int(11)',
            'COLUMN_DEFAULT' => null,
            'is_nullable' => false,
            'column_key' => "PRI",
            'EXTRA' => "AUTO_INCREMENT")
        );
    }
}
function credit_statuses_Validate_Credit_Status_Name_Column(\DatabaseLink\Table $credit_status_table)
{
    try
    {
        $column = $credit_status_table->Get_Column('credit_status_name');
        if($column->Get_Column_Key() != "")
        {
            $column->Set_Column_Key("");
        }
        if($column->Get_Data_Type() != "varchar(35)")
        {
            $column->Set_Data_Type("varchar(35)");
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
        $column = new \DatabaseLink\Column('credit_status_name',$credit_status_table,array(
            'COLUMN_TYPE' => 'varchar(35)',
            'COLUMN_DEFAULT' => null,
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
function credit_statuses_Validate_Company_Column(\DatabaseLink\Table $Customers)
{
    try{ $column = $Customers->Get_Column('company_id');

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
        $column = new \DatabaseLink\Column('company_id',$Customers,array(
            'COLUMN_TYPE' => 'int(11)',
            'COLUMN_DEFAULT' => null,
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
function credit_statuses_Validate_Active_Status_Column(\DatabaseLink\Table $Customers)
{
    try
    {
        $column = $Customers->Get_Column('active_status');
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
        $column = new \DatabaseLink\Column('active_status',$Customers,array(
            'COLUMN_TYPE' => 'INT(11)',
            'COLUMN_DEFAULT' => "1",
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}

?>
