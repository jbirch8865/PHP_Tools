<?php
namespace DatabaseLink;
class Column_Row {
    private $dblink;
    private $required;
    private $table_name;
    private $column_name; 

    function __construct($dblink, $column_name, $table_name)
    {
        $table_name = $table_name;
        $column_name = $column_name;
        $this->dblink = $dblink;
        $this->Load_Column_Parameters();
    }

    private function Load_Column_Parameters()
    {
        try
        {
            $column_comments = $this->Query_For_Column_Comments();
            $this->required = $column_comments['required'];
        } catch (\Exception $e)
        {
            throw new \Exception($e->getMessage());
        }
    }

    private function Query_For_Column_Comments()
    {
        try 
        {
            $query_information_schema = $this->dblink->ExecuteSQLQuery("SELECT COLUMN_COMMENT FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '".$this->table_name."' AND COLUMN_NAME = '".$this->column_name."'");
            $query_information_schema = mysqli_fetch_assoc($query_information_schema);
            $query_information_schema = json_decode($query_information_schema['COLUMN_COMMENT']);
            return $query_information_schema;
        } catch (\Exception $e)
        {
            throw new \Exception($e->getMessage());
        }
    }
}
?>