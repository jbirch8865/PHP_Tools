<?php declare(strict_types=1);
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

	/**
	 * @param int $which_user [0 = read_only_database_required,1 = full_rights_database_required,2 = root_access]
	 */
	function __construct(string $database_to_connect_to,int $which_user = 0)
	{
		global $cConfigs;
		$this->cConfigs = $cConfigs;
		if($which_user == 2)
		{
			$this->Load_Root_Configuration_File();
			if(!$this->username)
			{
				throw new \Exception("Check config file for database configs.  Root username does not exist.");
			}
		}elseif($which_user == 1)
		{
			$this->Load_Configuration_File($database_to_connect_to);
			if(!$this->username)
			{
				throw new \Exception("Check config file for database configs. ".$this->cConfigs->Get_Name_Of_Project()." username does not exist.");
			}
		}else
		{
			$this->Load_Read_Only_Configuration_File($database_to_connect_to);
			if(!$this->username)
			{
				throw new \Exception("Check config file for database configs. read_only_".$this->cConfigs->Get_Name_Of_Project()." username does not exist.");
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
	private function Load_Read_Only_Configuration_File(string $database_to_connect_to)
	{
		$this->username = $this->cConfigs->Get_Value_If_Enabled('read_only_'.$database_to_connect_to.'_username');
		$this->password = $this->cConfigs->Get_Value_If_Enabled('read_only_'.$database_to_connect_to.'_password');
		$this->hostname = $this->cConfigs->Get_Value_If_Enabled('read_only_'.$database_to_connect_to.'_hostname');
		$this->listeningport = $this->cConfigs->Get_Value_If_Enabled('read_only_'.$database_to_connect_to.'_listeningport');
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
	function Execute_Any_SQL_Query(string $query)
	{
		if($run = $this->database->execute($query))
		{
			if($run instanceof \ADORecordSet_mysqli)
			{
				$this->results = $run;
				return true;
			}else
			{
				return null;
			}
		}else
		{
			throw new SQLQueryError($query.' did not successfully execute with error message - '.$this->Get_Last_Error());
		}
	}
	/**
	 * @param string $table the name of the table to insert or update data
	 * @param array $query_parameters ('column_name' => 'new_value')
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
		return $this->database->errorNo();
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
?>