<?php declare(strict_types=1);
$toolbelt_base->Addresses = new \DatabaseLink\Table('Addresses',$toolbelt_base->dblink);
addresses_Validate_ID_Column($toolbelt_base->Addresses);
addresses_Validate_Address_Description_Column($toolbelt_base->Addresses);
addresses_Validate_Address_Name_Column($toolbelt_base->Addresses);
addresses_Validate_Address_Street1_Column($toolbelt_base->Addresses);
addresses_Validate_Address_Street2_Column($toolbelt_base->Addresses);
addresses_Validate_Address_City_Column($toolbelt_base->Addresses);
addresses_Validate_Address_State_Column($toolbelt_base->Addresses);
addresses_Validate_Address_Zip_Column($toolbelt_base->Addresses);
addresses_Validate_Google_Lat_Column($toolbelt_base->Addresses);
addresses_Validate_Google_Lng_Column($toolbelt_base->Addresses);
addresses_Validate_Google_URL_Column($toolbelt_base->Addresses);
addresses_Validate_Google_ID_Column($toolbelt_base->Addresses);
addresses_Validate_Company_Column($toolbelt_base->Addresses);
$toolbelt_base->Addresses->Load_Columns();
function addresses_Validate_ID_Column(\DatabaseLink\Table $address_table)
{
    try
    {
        $column = $address_table->Get_Column('id');
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
        $column = new \DatabaseLink\Column('id',$address_table,array(
            'COLUMN_TYPE' => 'int(11)',
            'COLUMN_DEFAULT' => null,
            'is_nullable' => false,
            'column_key' => "PRI",
            'EXTRA' => "AUTO_INCREMENT")
        );
    }
}
function addresses_Validate_Address_Description_Column(\DatabaseLink\Table $address_table)
{
    try
    {
        $column = $address_table->Get_Column('description');
        if($column->Get_Column_Key() != "")
        {
            $column->Set_Column_Key("");
        }
        if($column->Get_Data_Type() != "varchar(75)")
        {
            $column->Set_Data_Type("varchar(75)");
        }
        if($column->Get_Default_Value() != "physical")
        {
            $column->Set_Default_Value("physical");
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
        $column = new \DatabaseLink\Column('description',$address_table,array(
            'COLUMN_TYPE' => 'varchar(75)',
            'COLUMN_DEFAULT' => "physical",
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
function addresses_Validate_Address_Name_Column(\DatabaseLink\Table $address_table)
{
    try
    {
        $column = $address_table->Get_Column('name');
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
        $column = new \DatabaseLink\Column('name',$address_table,array(
            'COLUMN_TYPE' => 'varchar(75)',
            'COLUMN_DEFAULT' => "",
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
function addresses_Validate_Address_Street1_Column(\DatabaseLink\Table $address_table)
{
    try
    {
        $column = $address_table->Get_Column('street1');
        if($column->Get_Column_Key() != "")
        {
            $column->Set_Column_Key("");
        }
        if($column->Get_Data_Type() != "varchar(125)")
        {
            $column->Set_Data_Type("varchar(125)");
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
        $column = new \DatabaseLink\Column('street1',$address_table,array(
            'COLUMN_TYPE' => 'varchar(125)',
            'COLUMN_DEFAULT' => "",
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
function addresses_Validate_Address_Street2_Column(\DatabaseLink\Table $address_table)
{
    try
    {
        $column = $address_table->Get_Column('street2');
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
        $column = new \DatabaseLink\Column('street2',$address_table,array(
            'COLUMN_TYPE' => 'varchar(75)',
            'COLUMN_DEFAULT' => "",
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
function addresses_Validate_Address_City_Column(\DatabaseLink\Table $address_table)
{
    try
    {
        $column = $address_table->Get_Column('city');
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
        $column = new \DatabaseLink\Column('city',$address_table,array(
            'COLUMN_TYPE' => 'varchar(75)',
            'COLUMN_DEFAULT' => "",
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
function addresses_Validate_Address_State_Column(\DatabaseLink\Table $address_table)
{
    try
    {
        $column = $address_table->Get_Column('state');
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
        $column = new \DatabaseLink\Column('state',$address_table,array(
            'COLUMN_TYPE' => 'varchar(30)',
            'COLUMN_DEFAULT' => "",
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
function addresses_Validate_Address_Zip_Column(\DatabaseLink\Table $address_table)
{
    try
    {
        $column = $address_table->Get_Column('zip');
        if($column->Get_Column_Key() != "")
        {
            $column->Set_Column_Key("");
        }
        if($column->Get_Data_Type() != "varchar(10)")
        {
            $column->Set_Data_Type("varchar(10)");
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
        $column = new \DatabaseLink\Column('zip',$address_table,array(
            'COLUMN_TYPE' => 'varchar(10)',
            'COLUMN_DEFAULT' => "",
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
function addresses_Validate_Google_Lat_Column(\DatabaseLink\Table $address_table)
{
    try
    {
        $column = $address_table->Get_Column('lat');
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
        $column = new \DatabaseLink\Column('lat',$address_table,array(
            'COLUMN_TYPE' => 'varchar(30)',
            'COLUMN_DEFAULT' => "",
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
function addresses_Validate_Google_Lng_Column(\DatabaseLink\Table $address_table)
{
    try
    {
        $column = $address_table->Get_Column('lng');
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
        $column = new \DatabaseLink\Column('lng',$address_table,array(
            'COLUMN_TYPE' => 'varchar(30)',
            'COLUMN_DEFAULT' => "",
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
function addresses_Validate_Google_URL_Column(\DatabaseLink\Table $address_table)
{
    try
    {
        $column = $address_table->Get_Column('url');
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
        $column = new \DatabaseLink\Column('url',$address_table,array(
            'COLUMN_TYPE' => 'varchar(75)',
            'COLUMN_DEFAULT' => "",
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
function addresses_Validate_Google_ID_Column(\DatabaseLink\Table $address_table)
{
    try
    {
        $column = $address_table->Get_Column('google_id');
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
        $column = new \DatabaseLink\Column('google_id',$address_table,array(
            'COLUMN_TYPE' => 'varchar(30)',
            'COLUMN_DEFAULT' => "",
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
function addresses_Validate_Company_Column(\DatabaseLink\Table $address_table)
{
    try{ $column = $address_table->Get_Column('company_id');

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
        $column = new \DatabaseLink\Column('company_id',$address_table,array(
            'COLUMN_TYPE' => 'int(11)',
            'COLUMN_DEFAULT' => null,
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}

?>
