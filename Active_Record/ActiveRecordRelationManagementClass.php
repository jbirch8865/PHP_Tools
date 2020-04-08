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
    function Load_Table_Has_Many_If_Empty(
    \DatabaseLink\Table $called_table,
    \DatabaseLink\Table $linked_to,
    \DatabaseLink\Column $linked_column,
    string $object_to_create)
    {
        $create = false;
        if(!key_exists($called_table->Get_Table_Name(),$this->table_has_many))
        {
            $create = true;            
        }elseif(!key_exists($linked_to->Get_Table_Name(),$this->table_has_many[$called_table->Get_Table_Name()]))
        {
            $create = true;
        }
        if($create)
        {
            $this->table_has_many[$called_table->Get_Table_Name()][$linked_to->Get_Table_Name()] = [$called_table->Get_Table_Name(),
                $linked_to->Get_Table_Name(),
                $linked_column->Get_Column_Name(),
                $object_to_create];
            \ADODB_Active_Record::TableHasMany($this->table_has_many[$called_table->Get_Table_Name()][$linked_to->Get_Table_Name()][0],
                $this->table_has_many[$called_table->Get_Table_Name()][$linked_to->Get_Table_Name()][1],
                $this->table_has_many[$called_table->Get_Table_Name()][$linked_to->Get_Table_Name()][2],
                $this->table_has_many[$called_table->Get_Table_Name()][$linked_to->Get_Table_Name()][3]);
        }
        
    }

    /**
     * Programs_Have_Sessions using key user_id has many records from Users_Have_Roles matching to the user_id column
     */
    function Load_Table_Key_Has_Many_If_Empty(
    \DatabaseLink\Table $parent_table,
    \DatabaseLink\Table $linked_to,
    \DatabaseLink\Column $parent_column,
    \DatabaseLink\Column $linked_column, 
    string $object_to_create)
    {
        $create = false;
        if(!key_exists($parent_table->Get_Table_Name(),$this->table_key_has_many))
        {
            $create = true;            
        }elseif(!key_exists($linked_to->Get_Table_Name(),$this->table_key_has_many[$parent_table->Get_Table_Name()]))
        {
            $create = true;
        }
        if($create)
        {
            $this->table_key_has_many[$parent_table->Get_Table_Name()][$linked_to->Get_Table_Name()] = [$parent_table->Get_Table_Name(),
                $linked_to->Get_Table_Name(),
                $parent_column->Get_Column_Name(),
                $linked_column->Get_Column_Name(), 
                $object_to_create];
            \ADODB_Active_Record::TableKeyHasMany(
                $this->table_key_has_many[$parent_table->Get_Table_Name()][$linked_to->Get_Table_Name()][0],
                $this->table_key_has_many[$parent_table->Get_Table_Name()][$linked_to->Get_Table_Name()][2],
                $this->table_key_has_many[$parent_table->Get_Table_Name()][$linked_to->Get_Table_Name()][1],
                $this->table_key_has_many[$parent_table->Get_Table_Name()][$linked_to->Get_Table_Name()][3],
                $this->table_key_has_many[$parent_table->Get_Table_Name()][$linked_to->Get_Table_Name()][4]);
        }
    }

    /**
     * Users_Have_Roles on column named role_id belongs to the table Company_Roles on the column named id
     */
    function Load_Table_Belongs_To_If_Empty(
    \DatabaseLink\Table $parent_table,
    \DatabaseLink\Column $column_named,
    \DatabaseLink\Table $belongs_to,
    \DatabaseLink\Column $belongs_to_column, 
    string $object_to_create)
    {
        $create = false;
        if(!key_exists($parent_table->Get_Table_Name(),$this->table_belongs_to))
        {
            $create = true;            
        }elseif(!key_exists($belongs_to->Get_Table_Name(),$this->table_belongs_to[$parent_table->Get_Table_Name()]))
        {
            $create = true;
        }
        if($create)
        {
            $this->table_belongs_to[$parent_table->Get_Table_Name()][$belongs_to->Get_Table_Name()] = [
                $parent_table->Get_Table_Name(),
                $belongs_to->Get_Table_Name(),
                $column_named->Get_Column_Name(),
                $belongs_to_column->Get_Column_Name(), 
                $object_to_create];
            \ADODB_Active_Record::TableBelongsTo(
                $this->table_belongs_to[$parent_table->Get_Table_Name()][$belongs_to->Get_Table_Name()][0],
                $this->table_belongs_to[$parent_table->Get_Table_Name()][$belongs_to->Get_Table_Name()][1],
                $this->table_belongs_to[$parent_table->Get_Table_Name()][$belongs_to->Get_Table_Name()][2],
                $this->table_belongs_to[$parent_table->Get_Table_Name()][$belongs_to->Get_Table_Name()][3],
                $this->table_belongs_to[$parent_table->Get_Table_Name()][$belongs_to->Get_Table_Name()][4]);
        }
    }

    function Get_Relationships_From_Parent_Table(\DatabaseLink\Table $parent_table)
    {
        $children = [];
        ForEach($this->table_belongs_to[$parent_table] as $child_name => $relationship)
        {
            $children[] = $child_name;
        }
        ForEach($this->table_has_many[$parent_table] as $child_name => $relationship)
        {
            $children[] = $child_name;
        }
        ForEach($this->table_key_has_many[$parent_table] as $child_name => $relationship)
        {
            $children[] = $child_name;
        }
    }

}

?>