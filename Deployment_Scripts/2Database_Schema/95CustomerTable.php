<?php declare(strict_types=1);
$toolbelt_base->Customers = new \DatabaseLink\Table('Customers',$toolbelt_base->dblink);
customer_Validate_ID_Column($toolbelt_base->Customers);
customer_Validate_Customer_Name_Column($toolbelt_base->Customers);
customer_Validate_Credit_Status_Column($toolbelt_base->Customers);
customer_Validate_Website_Column($toolbelt_base->Customers);
customer_Validate_CCB_Column($toolbelt_base->Customers);
customer_Validate_Company_Column($toolbelt_base->Customers);
Customer_Validate_Active_Status_Column($toolbelt_base->Customers);
$toolbelt_base->Customers->Load_Columns();
function customer_Validate_ID_Column(\DatabaseLink\Table $Customers)
{
    try{ $column = $Customers->Get_Column('id');

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
        $column = new \DatabaseLink\Column('id',$Customers,array(
            'COLUMN_TYPE' => 'int(11)',
            'COLUMN_DEFAULT' => null,
            'is_nullable' => false,
            'column_key' => "PRI",
            'EXTRA' => "AUTO_INCREMENT")
        );
    }
}
function customer_Validate_Customer_Name_Column(\DatabaseLink\Table $Customers)
{
    try{ $column = $Customers->Get_Column('customer_name');

        if($column->Get_Column_Key() != "")
        {
            $column->Set_Column_Key("");
        }
        if($column->Get_Data_Type() != "varchar(75)")
        {
            $column->Set_Data_Type("varchar(75)");
        }
        if($column->Get_Default_Value() != "")
        {
            $column->Set_Default_Value("");
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
        $column = new \DatabaseLink\Column('customer_name',$Customers,array(
            'COLUMN_TYPE' => 'varchar(75)',
            'COLUMN_DEFAULT' => "",
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
function customer_Validate_Credit_Status_Column(\DatabaseLink\Table $Customers)
{
    try{ $column = $Customers->Get_Column('credit_status');

        if($column->Get_Column_Key() != "")
        {
            $column->Set_Column_Key("");
        }
        if($column->Get_Data_Type() != "int(11)")
        {
            $column->Set_Data_Type("int(11)");
        }
        if($column->Get_Default_Value() != "0")
        {
            $column->Set_Default_Value("0");
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
        $column = new \DatabaseLink\Column('credit_status',$Customers,array(
            'COLUMN_TYPE' => 'int(11)',
            'COLUMN_DEFAULT' => "0",
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
function customer_Validate_Website_Column(\DatabaseLink\Table $Customers)
{
    try{ $column = $Customers->Get_Column('website');

        if($column->Get_Column_Key() != "")
        {
            $column->Set_Column_Key("");
        }
        if($column->Get_Data_Type() != "varchar(75)")
        {
            $column->Set_Data_Type("varchar(75)");
        }
        if($column->Get_Default_Value() != "")
        {
            $column->Set_Default_Value("");
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
        $column = new \DatabaseLink\Column('website',$Customers,array(
            'COLUMN_TYPE' => 'varchar(75)',
            'COLUMN_DEFAULT' => "",
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
function customer_Validate_CCB_Column(\DatabaseLink\Table $Customers)
{
    try{ $column = $Customers->Get_Column('ccb');

        if($column->Get_Column_Key() != "")
        {
            $column->Set_Column_Key("");
        }
        if($column->Get_Data_Type() != "varchar(30)")
        {
            $column->Set_Data_Type("varchar(30)");
        }
        if($column->Get_Default_Value() != "")
        {
            $column->Set_Default_Value("");
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
        $column = new \DatabaseLink\Column('ccb',$Customers,array(
            'COLUMN_TYPE' => 'varchar(30)',
            'COLUMN_DEFAULT' => "",
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
function customer_Validate_Company_Column(\DatabaseLink\Table $Customers)
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
function Customer_Validate_Active_Status_Column(\DatabaseLink\Table $Customers)
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
