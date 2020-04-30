<?php declare(strict_types=1);
$toolbelt_base->People_Belong_To_Company = new \DatabaseLink\Table('People_Belong_To_Company',$toolbelt_base->dblink);
people_belong_to_company_Validate_ID_Column($toolbelt_base->People_Belong_To_Company);
people_belong_to_company_Validate_Company_ID_Column($toolbelt_base->People_Belong_To_Company);
people_belong_to_company_Validate_People_ID_Column($toolbelt_base->People_Belong_To_Company);
$toolbelt_base->People_Belong_To_Company->Load_Columns();

function people_belong_to_company_Validate_ID_Column(\DatabaseLink\Table $People_Belong_To_Company)
{
    try
    {
        $column = $People_Belong_To_Company->Get_Column('id');
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
        $column = new \DatabaseLink\Column('id',$People_Belong_To_Company,array(
            'COLUMN_TYPE' => 'int(11)',
            'COLUMN_DEFAULT' => null,
            'is_nullable' => false,
            'column_key' => "PRI",
            'EXTRA' => "AUTO_INCREMENT",
            'COLUMN_COMMENT' => 'exclude')
        );
    }
}
function people_belong_to_company_Validate_Company_ID_Column(\DatabaseLink\Table $People_Belong_To_Company)
{
    try
    {
        $column = $People_Belong_To_Company->Get_Column('company_id');
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
        $column = new \DatabaseLink\Column('company_id',$People_Belong_To_Company,array(
            'COLUMN_TYPE' => 'int(11)',
            'COLUMN_DEFAULT' => null,
            'is_nullable' => false,
            'column_key' => "",
            'EXTRA' => "")
        );
    }
}
function people_belong_to_company_Validate_People_ID_Column(\DatabaseLink\Table $People_Belong_To_Company)
{
    try
    {
        $column = $People_Belong_To_Company->Get_Column('people_id');
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
        $column = new \DatabaseLink\Column('people_id',$People_Belong_To_Company,array(
            'COLUMN_TYPE' => 'int(11)',
            'COLUMN_DEFAULT' => null,
            'is_nullable' => false,
            'column_key' => "UNI",
            'EXTRA' => "")
        );
    }
}
?>
