<?php
namespace databaseLink;

use DatabaseLink\Column_Does_Not_Exist;
use DatabaseLink\SQLQueryError;
Class MySQLLink
{
	public \config\ConfigurationFile $cConfigs;
	private string $username;
	private string $password;
	private string $hostname;
	private \ADODB_mysqli $database;
	private \ADORecordSet_mysqli $results;
	private \ADORecordSet_array_mysqli $cached_results;
	private string $listeningport;

	function __construct(string $database_to_connect_to,bool $run_as_root_user = false)
	{
		global $cConfigs;
		$this->cConfigs = $cConfigs;
		if($run_as_root_user)
		{
			$this->Load_Root_Configuration_File();
			if(!$this->username)
			{
				throw new \Exception("Check config file for database configs.  Root username does not exist.");
			}
		}else
		{
			$this->Load_Configuration_File($database_to_connect_to);
			if(!$this->username)
			{
				throw new \Exception("Check config file for database configs. Username does not exist.");
			}
		}
		$this->Establish_Database_Link($database_to_connect_to);
	}
	private function Load_Configuration_File(string $database_to_connect_to)
	{
		$this->username = $this->cConfigs->Get_Value_If_Enabled($database_to_connect_to.'_username');
		$this->password = $this->cConfigs->Get_Value_If_Enabled($database_to_connect_to.'_password');
		$this->hostname = $this->cConfigs->Get_Value_If_Enabled($database_to_connect_to.'_hostname');
		$this->listeningport = $this->cConfigs->Get_Value_If_Enabled($database_to_connect_to.'_listeningport');
	}
	private function Load_Root_Configuration_File()
	{
		$this->username = $this->cConfigs->Get_Value_If_Enabled('root_username');
		$this->password = $this->cConfigs->Get_Value_If_Enabled('root_password');
		$this->hostname = $this->cConfigs->Get_Value_If_Enabled('root_hostname');
		$this->listeningport = $this->cConfigs->Get_Value_If_Enabled('root_listeningport');
	}
	private function Establish_Database_Link(string $database_to_connect_to)
	{
		$driver = 'mysqli';
 
		$db = \newAdoConnection($driver); 		 
		if(!$database_to_connect_to == "")
		{
			if(!$db->connect($this->hostname,$this->username,$this->password,$database_to_connect_to))
			{
				throw new SQLConnectionError("Couldn't connect to mysql");
			}	
		}else
		{
			if(!$db->connect($this->hostname,$this->username,$this->password))
			{
				throw new SQLConnectionError("Couldn't connect to mysql");
			}	
		}
		$this->database = $db;
		
	}
	function Is_Connected()
	{
		return $this->database->isConnected();
	}
	function Execute_Any_SQL_Query(string $query,bool $cache_query = false)
	{
		if($cache_query)
		{
			$ADODB_CACHE_DIR=dirname(__FILE__).'/queries';
			if($run = $this->database->cacheExecute(6000,$query))
			{
				$this->cached_results = $run;	
				return true;	
			}else
			{
				return false;
			}
		}else
		{
			if($run = $this->database->execute($query))
			{
				if($run instanceof \ADORecordSet_mysqli)
				{
					$this->results = $run;
					return true;
				}else
				{
					return false;
				}
			}else
			{
				throw new SQLQueryError($query.' did not successfully execute with error message - '.$this->Get_Last_Error());
			}
		}
	}
	/**
	 * @param string $table the name of the table to insert or update data
	 * @param array $query_parameters an array that required keys to be the string name of the mysql column and the value to be the correct type value you want to update or insert
	 * @param bool $update true if you are updating or false if you are inserting
	 * @param string $where_clause if you are updating and want a where clause add the complete sql formatted string
	 * @param bool $only_changed_values true if you want to only update the values that have changed this re-reads the database after checking for changed fields
	 * false if you just want to send the update the constructed statement could be substantially longer than only changed elements
	 * @param bool $protect_against_sql_injection true to escape quotes false to submit as given
	 */
	function Execute_Insert_Or_Update_SQL_Query(string $table, array $query_parameters,bool $update = false,string $where_clause = "",bool $only_changed_values = false,bool $protect_against_sql_injection = true)
	{
		if($update)
		{
			$this->database->autoExecute($table,$query_parameters,'UPDATE', $where_clause,!$only_changed_values,!$protect_against_sql_injection);
		}else //insert statement
 		{
			$this->database->autoExecute($table,$query_parameters,'INSERT',false,!$only_changed_values,!$protect_against_sql_injection);
		}
	}
	function Get_Last_Insert_ID()
	{
		return $this->database->insert_id();
	}
	function Get_Last_Error()
	{
		return $this->database->errorMsg();
	}
	function Get_Last_Error_Number()
	{
		return false;
	}
	private function Get_Row_Results()
	{
		$return_array = array();
		$this->results->fetchInto($return_array);
		return $return_array;
	}
	function Get_Results()
	{
		$results = array();	
		While(!$this->results->EOF)
		{
			$row_array = array();
			$row = $this->Get_Row_Results();
			ForEach($row as $key => $value)
			{
				if(!is_int($key))
				{
					$row_array[$key] = $value;
				}	
			}
			$results[] = $row_array;
		}
		return $results;
	}
	function Get_Num_Of_Rows()
	{
		return $this->results->numRows();
	}
	function Get_First_Row($use_cached_result = false)
	{
		if($use_cached_result)
		{
			$this->cached_results->moveFirst();
			return $this->cached_results->fetchRow();	
		}else
		{
			if(!$this->results->moveFirst())
			{
				return false;
			}else
			{
				return $this->results->fetchRow();	
			}
		}
	}
	/**
	 * @param string $from This is the FROM statement
	 * @param string $where This is the WHERE statement
	 * the query is written as SELECT count(*) FROM $from $where
	 */
	function Does_This_Return_A_Count_Of_More_Than_Zero(string $from, string $where)
	{
		$this->Execute_Any_SQL_Query("SELECT COUNT(*) FROM $from WHERE $where");
		$row = $this->Get_First_Row();
		if($row['COUNT(*)'] == '0')
		{
			return false;
		}else
		{
			return true;
		}	
	}
	function Get_Results_Current_EOF_Status()
	{
		return $this->results->EOF;
	}
}

class Database
{
	public ?MySQLLink $dblink = NULL;
	private MySQLLink $root_dblink;
	private ?string $verified_database_name = NULL;
	private array $tables = array();
	/**
	 * @param string $unverified_database_name if this does not exist then a database will automatically be created and credentials will be created and added to the config file
	 * If the database is already created credentials are expected to already be created and linked in the config file.  If not manual intervention is required.
	 */
	function __construct(string $unverified_database_name)
	{
		global $root_dblink;
		$this->root_dblink = $root_dblink;
		$this->If_Does_Not_Exist_Create_Database_And_Issue_Credentials($unverified_database_name);
		$this->dblink = new MySQLLink($this->verified_database_name);
		$this->Load_Tables();
	}
	private function If_Does_Not_Exist_Create_Database_And_Issue_Credentials(string $unverified_database_name)
	{
		if($this->Does_Database_Exist($unverified_database_name))
		{
			$this->verified_database_name = $unverified_database_name;
		}else
		{
			$this->Create_Database_And_Issue_Credentials($unverified_database_name);
		}
	}
	private function Does_Database_Exist(string $unverified_database_name)
	{
		if($this->root_dblink->Does_This_Return_A_Count_Of_More_Than_Zero("INFORMATION_SCHEMA.SCHEMATA","SCHEMA_NAME = '".$unverified_database_name."'"))
		{
			return true;
		}else
		{
			return false;
		}
	}
	private function Create_Database_And_Issue_Credentials(string $unverified_database_name)
	{
		$this->Create_Database($unverified_database_name);
		$this->Create_User();
	}
	private function Create_Database(string $unverified_database_name)
	{
		$this->root_dblink->Execute_Any_SQL_Query("CREATE DATABASE ".$unverified_database_name);
		if($this->Does_Database_Exist($unverified_database_name))
		{
			$this->verified_database_name = $unverified_database_name;
		}else
		{
			throw new SQLQueryError("Database did not appear to create.  Last Error - ".$this->root_dblink->Get_Last_Error());
		}
	}
	private function Create_User()
	{
		$password = Generate_CSPRNG(14,'D&hFl@gg1ng');
		$this->root_dblink->Execute_Any_SQL_Query("
		CREATE USER '".$this->verified_database_name."'@'%' IDENTIFIED BY '".$password."'");
		$this->root_dblink->Execute_Any_SQL_Query("GRANT ALL PRIVILEGES ON 
		`".$this->verified_database_name."`.* TO 
		'".$this->verified_database_name."'@'%';");
		$this->root_dblink->cConfigs->Set_Database_Connection_Preferences('localhost',$this->verified_database_name,$password,$this->verified_database_name);
	}
	/**
	 * This will drop the database with foreign relation checks enabled so it's possible it will fail and the foreign relationship will need to be removed first
	 */
	function Drop_Database_And_User()
	{
		$this->root_dblink->Execute_Any_SQL_Query("DROP DATABASE `".$this->verified_database_name."`");
		$this->root_dblink->Execute_Any_SQL_Query("DROP USER '".$this->verified_database_name."'@'%'");
		$this->root_dblink->cConfigs->Delete_Config_If_Exists($this->verified_database_name.'_username');
		$this->root_dblink->cConfigs->Delete_Config_If_Exists($this->verified_database_name.'_project_database_name');
		$this->root_dblink->cConfigs->Delete_Config_If_Exists($this->verified_database_name.'_password');
		$this->root_dblink->cConfigs->Delete_Config_If_Exists($this->verified_database_name.'_hostname');
		$this->root_dblink->cConfigs->Delete_Config_If_Exists($this->verified_database_name.'_listeningport');
	}

	function Get_Database_Name()
	{
		return $this->verified_database_name;
	}

	private function Load_Tables()
	{
		$tables = $this->root_dblink->Execute_Any_SQL_Query("SELECT TABLE_NAME FROM `information_schema`.`tables` WHERE `TABLE_SCHEMA` = '".$this->Get_Database_Name()."'");
		$tables = $this->root_dblink->Get_Results();
		ForEach($tables as $row => $value)
		{
			$this->tables[$value['TABLE_NAME']] = new Table($value['TABLE_NAME'],$this);
		}
	}
}

class Table
{
	public ?Database $database_dblink = NULL;
	private \config\ConfigurationFile $cConfigs;
	private ?string $verified_table_name = NULL;
	private int $number_of_table_rows = 0;
	private array $rows_in_query = array();
	private array $columns = array();
	/**
	 * @param string $unverified_table_name if does not exist will automatically create it
	 */
	function __construct(string $unverified_table_name,Database $database_dblink)
	{
		global $cConfigs;
		$this->cConfigs = $cConfigs;
		$this->database_dblink = $database_dblink;
		$this->If_Does_Not_Exist_Create_Table($unverified_table_name);
		$this->Load_Table();
		$this->Load_Columns();
	}
	private function If_Does_Not_Exist_Create_Table(string $unverified_table_name)
	{
		if($this->Does_Table_Exist($unverified_table_name))
		{
			$this->verified_table_name = $unverified_table_name;
		}else
		{
			$this->Create_Table($unverified_table_name);
		}
	}
	private function Does_Table_Exist(string $unverified_table_name)
	{
		if($this->database_dblink->dblink->Does_This_Return_A_Count_Of_More_Than_Zero('information_schema.tables','table_schema = \''.$this->database_dblink->Get_Database_Name().'\' AND table_name = \''.$unverified_table_name.'\''))
		{
			return true;
		}else
		{
			return false;
		}
	}
	private function Create_Table(string $unverified_table_name)
	{
		$this->database_dblink->dblink->Execute_Any_SQL_Query("CREATE TABLE `".$unverified_table_name."` (
			 `id` int(11) NOT NULL)
			 ENGINE=InnoDB DEFAULT CHARSET=latin1;");
		if($this->Does_Table_Exist($unverified_table_name))
		{
			$this->verified_table_name = $unverified_table_name;
		}else
		{
			throw new SQLQueryError("Table did not appear to create.  Last Error - ".$this->database_dblink->dblink->Get_Last_Error());
		}
	}
	function Get_Table_Name()
	{
		return $this->verified_table_name;
	}
	private function Load_Columns()
	{
		(array) $array = array();
		$this->database_dblink->dblink->Execute_Any_SQL_Query("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '".$this->cConfigs->Get_Name_Of_Project()."' AND TABLE_NAME = '".$this->Get_Table_Name()."'");
		$results = $this->database_dblink->dblink->Get_Results();
		ForEach($results as $key => $value)
		{
			$this->columns[$value['COLUMN_NAME']] = new Column($value['COLUMN_NAME'],$this,$value);
		}
	}
	private function Load_Table()
	{
		(array) $array = array();
		$this->database_dblink->dblink->Execute_Any_SQL_Query("SELECT *	FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '".$this->cConfigs->Get_Name_Of_Project()."' AND TABLE_NAME = '".$this->Get_Table_Name()."'");
		$results = $this->database_dblink->dblink->Get_Results();
		ForEach($results as $key => $value)
		{
			$this->number_of_table_rows = $value['TABLE_ROWS'];
		}
	}

	/**
	 * @param array $INSERT_DATA array("column_name" => "value")
	 */
	function Insert_Row(array $INSERT_DATA)
	{
		ForEach($INSERT_DATA as $column_name => $value)
		{
			if(!array_key_exists($column_name,$this->columns))
			{
				throw new Column_Does_Not_Exist("Sorry but I can't find the column ".$column_name." in table ".$this->Get_Table_Name());
			}
		}
		ForEach($this->columns as $column_name => $column)
		{
			if(is_null($column->Get_Default_Value()) && !$column->Is_Column_Nullable() && !array_key_exists($column_name,$INSERT_DATA))
			{
				throw new Column_Does_Not_Exist("Sorry but you didn't pass a value for column ".$column_name." and this column is required in table ".$this->Get_Table_Name()." when inserting a new row");
			}
		}
		$this->database_dblink->dblink->Execute_Insert_Or_Update_SQL_Query($this->Get_Table_Name(),$INSERT_DATA);
	}
	/**
	 * @param array $INSERT_DATA array("column_name" => "value")
	 */
	function Update_Row(array $INSERT_DATA,string $WHERE = "")
	{
		ForEach($INSERT_DATA as $column_name => $value)
		{
			if(!array_key_exists($column_name,$this->columns))
			{
				throw new Column_Does_Not_Exist("Sorry but I can't find the column ".$column_name." in table ".$this->Get_Table_Name());
			}
		}
		$this->database_dblink->dblink->Execute_Insert_Or_Update_SQL_Query($this->Get_Table_Name(),$INSERT_DATA,true,$WHERE);
	}
	/**
	 * @param array $select_data array("column_name","column2_name")
	 * @param bool $select_all_data if true will ignore select_data
	 * @param string $where = "WHERE `column_name` = 'red'"
	 */
	function Select_Single_Table_Data(array $select_data = array(),bool $select_all_data = false,string $where = "")
	{
		if(!$where == "")
		{
			$where = " ".$where;
		}
		if($select_all_data)
		{
			$columns_to_select = "*";
		}else
		{
			$columns_to_select = implode(", ",$select_data);
		}
		$this->database_dblink->dblink->Execute_Any_SQL_Query("SELECT ".$columns_to_select." FROM `".$this->Get_Table_Name()."`".$where);
	}

}

class Column
{
	public Table $table_dblink;
	private \config\ConfigurationFile $cConfigs;
	private ?string $verified_column_name = NULL;
	private string $data_type = "INT";
	private ?int $data_length = 11;
	private ?string $default_value = NULL;
	private bool $is_nullable = true;
	private int $ordinal_position;
	private string $column_key = "";
	private $field_value = NULL;

	/**
	 * @param array $default_values {not caps sensative} array("data_type" = valid mysql datatype string,"CHARACTER_MAXIMUM_LENGTH" = valid type int,"COLUMN_DEFAULT" = [NULL,"string",""],
	 * "is_nullable" = bool,"column_key" = ["","PRI","UNI","MUL"],"ordinal_position" = [1,2]) if is_nullable = true and default_value is NULL then the default will be NULL if is_nullable = false and default_value = NULL
	 * then there will be no default
	 */
	function __construct(string $unverified_column_name,Table $table_dblink,array $default_values = array())
	{
		global $cConfigs;
		$this->cConfigs = $cConfigs;
		$this->table_dblink = $table_dblink;
		if(count($default_values)>0)
		{
			$this->Set_Default_Values_From_Array($default_values);
		}
		$this->If_Does_Not_Exist_Create_Column($unverified_column_name);
	}
	private function If_Does_Not_Exist_Create_Column(string $unverified_column_name)
	{
		if($this->Does_Column_Exist($unverified_column_name))
		{
			$this->verified_column_name = $unverified_column_name;
		}else
		{
			$this->Create_Column($unverified_column_name);
		}
	}
	private function Does_Column_Exist(string $unverified_column_name)
	{
		if($this->table_dblink->database_dblink->dblink->Does_This_Return_A_Count_Of_More_Than_Zero('information_schema.columns','table_schema = \''.$this->table_dblink->database_dblink->Get_Database_Name().'\' AND column_name = \''.$unverified_column_name.'\' AND table_name = \''.$this->table_dblink->Get_Table_Name().'\''))
		{
			return true;
		}else
		{
			return false;
		}
	}
	private function Create_Column(string $unverified_column_name)
	{
		if(is_null($this->default_value) && $this->is_nullable)
		{
			$default_value = "DEFAULT NULL";
		}elseif(is_null($this->default_value) && !$this->is_nullable)
		{
			$default_value = "";
		}else
		{
			$default_value = "DEFAULT ".$this->default_value;
		}
		if($this->is_nullable)
		{
			$NULL = "NULL";
		}else
		{
			$NULL = "NOT NULL";
		}
		$this->table_dblink->database_dblink->dblink->Execute_Any_SQL_Query("ALTER TABLE ".$this->table_dblink->Get_Table_Name()." ADD 
		".$unverified_column_name." ".$this->data_type."(".$this->data_length.") $default_value $NULL");
		if($this->Does_Column_Exist($unverified_column_name))
		{
			$this->verified_column_name = $unverified_column_name;
		}else
		{
			throw new SQLQueryError("Column did not appear to create.  Last Error - ".$this->table_dblink->database_dblink->dblink->Get_Last_Error());
		}
	}
	function Set_Data_Type(string $data_type)
	{
		$this->data_type = $data_type;
	}
	function Set_Data_Length(?int $data_length)
	{
		$this->data_length = $data_length;
	}
	function Set_Default_Value(?string $default_value)
	{
		$this->default_value = $default_value;
	}
	function Set_Ordinal_Position(int $position)
	{
		$this->oridinal_position = $position;
	}
	function Set_Column_Key(string $column_key = "")
	{
		$this->column_key = $column_key;
	}
	function Set_Field_Value($value)
	{
		$this->field_value = $value;
	}
	function Get_Data_Type()
	{
		return $this->data_type;
	}
	function Get_Data_Length()
	{
		return $this->data_length;
	}
	function Get_Default_Value()
	{
		return $this->default_value;
	}
	function Get_Column_Name()
	{
		return $this->verified_column_name;
	}
	function Column_Is_Nullable()
	{
		$this->is_nullable = true;
	}
	function Column_Is_Not_Nullable()
	{
		$this->is_nullable = false;
	}
	function Is_Column_Nullable()
	{
		return $this->is_nullable;
	}
	function Get_Ordinal_Position()
	{
		return $this->ordinal_position;
	}
	function Get_Column_Key()
	{
		return $this->column_key;
	}
	function Get_Field_Value()
	{
		return $this->field_value;
	}


	//Messy code that I don't know what to do with

	private function Set_Default_Values_From_Array(array $default_values)
	{
		ForEach($default_values as $value_name => $value_to_set)
		{
			if(strtolower($value_name) == "data_type")
			{
				$this->Set_Data_Type($value_to_set);
			}elseif(strtolower($value_name) == "character_maximum_length")
			{
				$this->Set_Data_Length($value_to_set);
			}elseif(strtolower($value_name) == "column_default")
			{
				$this->Set_Default_Value($value_to_set);
			}elseif(strtolower($value_name) == "is_nullable")
			{
				if($value_to_set == "YES")
				{
					$this->Column_Is_Nullable();
				}else
				{
					$this->Column_Is_Not_Nullable();
				}
			}elseif(strtolower($value_name) == "ordinal_position")
			{
				$this->Set_Ordinal_Position($value_to_set);
			}elseif(strtolower($value_name) == "column_key")
			{
				$this->Set_Column_Key($value_to_set);
			}
		}
	}

}

?>