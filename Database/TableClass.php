<?php
namespace DatabaseLink;
class table
{
    private $rows;
    private $table_name;
    private $primary_keys;
    private $dblink;
    private $database_name;
    function __construct($database,$table_name)
    {
        $this->table_name = $table_name;
        $this->rows = array();
        $this->database_name = $database;
        $this->dblink =  new \DatabaseLink\MySQLLink($this->database_name);
        $this->primary_keys = new PrimaryKeys($this->dblink,$table_name);
    }

    function Load_All_Rows()
    {
        $results = $this->dblink->ExecuteSQLQuery("SELECT ".$this->primary_keys->Return_PRIMARY_KEY_For_SQL_SELECT_Statement()." FROM ".$this->table_name);
        While($row = mysqli_fetch_assoc($results))
        {
            $this->rows[] = new Row($this->database_name,$this->table_name);
            $this->rows[count($this->rows) - 1]->Single_Row_Search()
        }
    }
}


?>