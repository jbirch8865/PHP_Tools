<?php

namespace Active_Record;

use Twilio\TwiML\Voice\Pay;

class RelationshipManager
{
    private array $table_has_many = [];
    private array $table_key_has_many = [];
    private array $table_belongs_to = [];
    /**
     * Companies have many Company Roles inside Company Roles the column `company_id` is linked to the primary key of the Companies table
     */
    function Load_Table_Has_Many_If_Empty(string $name,
    \DatabaseLink\Table $called_table,
    \DatabaseLink\Table $linked_to,
    \DatabaseLink\Column $called_column,
    string $object_to_create)
    {
        if(!key_exists($name,$this->table_has_many))
        {
            $this->table_has_many[$name] = [$called_table->Get_Table_Name(),$linked_to->Get_Table_Name(),$called_column->Get_Column_Name(),$object_to_create];
            \ADODB_Active_Record::TableHasMany($this->table_has_many[$name][0],$this->table_has_many[$name][1],$this->table_has_many[$name][2],$this->table_has_many[$name][3]);
        }
    }

    /**
     * Programs_Have_Sessions using key user_id has many records from Users_Have_Roles matching to the user_id column
     */
    function Load_Table_Key_Has_Many_If_Empty(string $name,
    \DatabaseLink\Table $parent_table,
    \DatabaseLink\Table $linked_to,
    \DatabaseLink\Column $parent_column,
    \DatabaseLink\Column $linked_column, 
    string $object_to_create)
    {
        if(!key_exists($name,$this->table_key_has_many))
        {
            $this->table_key_has_many[$name] = [$parent_table->Get_Table_Name(),$linked_to->Get_Table_Name(),$parent_column->Get_Column_Name(),$linked_column->Get_Column_Name(), $object_to_create];
            \ADODB_Active_Record::TableKeyHasMany($this->table_key_has_many[$name][0],$this->table_key_has_many[$name][1],$this->table_key_has_many[$name][2],$this->table_key_has_many[$name][3],$this->table_key_has_many[$name][4]);
        }
    }

    /**
     * Users_Have_Roles on column named role_id belongs to the table Company_Roles on the column named id
     */
    function Load_Table_Belongs_To_If_Empty(string $name,
    \DatabaseLink\Table $table_with_many,
    \DatabaseLink\Column $column_named,
    \DatabaseLink\Table $belongs_to,
    \DatabaseLink\Column $belongs_to_column, 
    string $object_to_create)
    {
        if(!key_exists($name,$this->table_belongs_to))
        {
            $this->table_belongs_to[$name] = [$table_with_many->Get_Table_Name(),$belongs_to->Get_Table_Name(),$column_named->Get_Column_Name(),$belongs_to_column->Get_Column_Name(), $object_to_create];
            \ADODB_Active_Record::TableBelongsTo($this->table_belongs_to[$name][0],$this->table_belongs_to[$name][1],$this->table_belongs_to[$name][2],$this->table_belongs_to[$name][3],$this->table_belongs_to[$name][4]);
        }
    }

}

?>