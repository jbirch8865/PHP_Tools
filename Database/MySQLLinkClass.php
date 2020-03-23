<?php declare(strict_types=1);
namespace databaseLink;

use DatabaseLink\Column_Does_Not_Exist;
use DatabaseLink\SQLQueryError;
Class MySQLLink
{
	public \config\ConfigurationFile $cConfigs;
	private int $which_user;
	private string $username;
	private string $password;
	private string $hostname;
	private string $listeningport;
	private string $database_name;
	private \ADODB_mysqli $database;
	private \ADORecordSet_mysqli $results;
	private \ADORecordSet_array_mysqli $cached_results;
	/**
	 * @param string $database_to_connect_to leave blank to default to the project name
	 * @param int $which_user [0 = read_only_database,1 = full_rights_database,2 = root_access]
	 * @throws Config_Missing
	 * @throws SQLConnectionError
	 */
	function __construct(string $database_to_connect_to = "",int $which_user = 0)
	{
		$this->database_name = $database_to_connect_to;
		$this->which_user = $which_user;
		global $toolbelt;
		$this->cConfigs = $toolbelt->cConfigs;
		if($database_to_connect_to == "" && $which_user != 2)
		{
			$database_to_connect_to = $this->cConfigs->Get_Name_Of_Project();
		}
		if($this->which_user == 2)
		{
			$this->Load_Root_Configuration_File();
		}elseif($this->which_user == 1)
		{
			$this->Load_Configuration_File($database_to_connect_to);
		}else
		{
			$this->Load_Read_Only_Configuration_File($database_to_connect_to);
		}
		$this->Establish_Database_Link($database_to_connect_to);
	}
	private function Load_Configuration_File(string $database_to_connect_to) : void
	{
		$this->username = $this->cConfigs->Get_Connection_Username($database_to_connect_to);
		$this->password = $this->cConfigs->Get_Connection_Password($database_to_connect_to);
		$this->hostname = $this->cConfigs->Get_Connection_Hostname($database_to_connect_to);
		$this->listeningport = $this->cConfigs->Get_Connection_Listeningport($database_to_connect_to);
	}
	private function Load_Root_Configuration_File() : void
	{
		$this->username = $this->cConfigs->Get_Root_Username();
		$this->password = $this->cConfigs->Get_Root_Password();
		$this->hostname = $this->cConfigs->Get_Root_Hostname();
		$this->listeningport = $this->cConfigs->Get_Root_Listeningport();
	}
	private function Load_Read_Only_Configuration_File(string $database_to_connect_to) : void
	{
		$this->username = $this->cConfigs->Get_Connection_Username($database_to_connect_to,true);
		$this->password = $this->cConfigs->Get_Connection_Password($database_to_connect_to,true);
		$this->hostname = $this->cConfigs->Get_Connection_Hostname($database_to_connect_to,true);
		$this->listeningport = $this->cConfigs->Get_Connection_Listeningport($database_to_connect_to,true);
	}
	private function Establish_Database_Link(string $database_to_connect_to) : void
	{
		$driver = 'mysqli';
 
		$db = \newAdoConnection($driver); 		 
		if(!$database_to_connect_to == "")
		{
			if(!$db->connect($this->hostname,$this->username,$this->password,$database_to_connect_to))
			{
				throw new SQLConnectionError("Couldn't connect to mysql with the error ".$db->ErrorMsg()." and error number ".$db->ErrorNo());
			}	
		}else
		{
			if(!$db->connect($this->hostname,$this->username,$this->password))
			{
				throw new SQLConnectionError("Couldn't connect to mysql with the error ".$db->ErrorMsg()." and error number ".$db->ErrorNo());
			}	
		}
		$this->database = $db;
		
	}
	function Is_Connected() : bool
	{
		return $this->database->isConnected();
	}
	/**
	 * @throws SQLQueryError
	 * @return null if no recordset returned
	 * @param array|null $bind_variables if set will prevent sql injection by replacing '?' 
	 * found in your query with the values in the array in the order they are given
	 */
	function Execute_Any_SQL_Query(string $query,?array $bind_variables = null) : ?bool
	{
		if(is_null($bind_variables))
		{
			$bind_variables_actual = false;
		}else
		{
			$bind_variables_actual = $bind_variables;
		}
		if($run = $this->database->execute($query,$bind_variables_actual))
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
			throw new SQLQueryError($query.' did not successfully execute with error message - '.$this->Get_Last_Error().' error number - '.$this->Get_Last_Error_Number());
		}
	}
	/**
	 * @param array $query_parameters ('column_name' => 'new_value')
	 * @param string $where_clause "last_name like 'Sm%'"
	 * @param bool $only_changed_values true if you want to only update the values that have changed this re-reads the database after checking for changed fields
	 * false if you just want to send the update the constructed statement could be substantially longer than only changed elements
	 * @param bool $protect_against_sql_injection true to escape quotes false to submit as given
	 */
	public function Execute_Insert_Or_Update_SQL_Query(string $table_name, array $query_parameters,bool $update_instead_of_insert_requires_where = false,string $where_clause = "",bool $only_changed_values = false,bool $protect_against_sql_injection = true) : void
	{
		if($update_instead_of_insert_requires_where)
		{
			$this->database->autoExecute($table_name,$query_parameters,'UPDATE', $where_clause,!$only_changed_values,!$protect_against_sql_injection);
		}else //insert statement
 		{
			$this->database->autoExecute($table_name,$query_parameters,'INSERT',false,!$only_changed_values,!$protect_against_sql_injection);
		}
	}
	function Get_Database_Name() : string
	{
		return $this->database_name;
	}
	function Get_Last_Insert_ID() : ?int
	{
		return $this->database->insert_id();
	}
	function Get_Last_Error() : ?string
	{
		return $this->database->errorMsg();
	}
	function Get_Last_Error_Number() : ?int
	{
		return $this->database->errorNo();
	}
	private function Get_Row_Results() : array
	{
		$return_array = array();
		$this->results->fetchInto($return_array);
		return $return_array;
	}
	function Get_Number_Of_Affected_Rows() : ?int
	{
		return $this->database->affected_rows();
	}
	function Get_Results() : array
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
	function Get_Num_Of_Rows() : int
	{
		return $this->results->numRows();
	}
	function Get_First_Row($use_cached_result = false) : ?array
	{
		if($use_cached_result)
		{
			$this->cached_results->moveFirst();
			return $this->cached_results->fetchRow();	
		}else
		{
			if(!$this->results->moveFirst())
			{
				return null;
			}else
			{
				return $this->results->fetchRow();	
			}
		}
	}
	/**
	 * @param string $from `Users` OR `Users` INNER JOIN ...
	 * @param string $where "`id` = '3'"
	 * @param string $sql_injection_proof you have to protect from sql injection prior to using this function, use code word "understood" to use this function
	 * @throws Exception if you don't use code word
	 */
	public function Does_This_Return_A_Count_Of_More_Than_Zero(string $from, string $where,string $sql_injection_proof) : bool
	{
		if($sql_injection_proof != "understood")
		{
			throw new \Exception('This query is not protected against sql injection.  Please pass "understood" as the string parameter for "$sql_injection_proof" indicating you have protected against injection prior to using this function.');
		}
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
	function Get_Results_Current_EOF_Status() : bool
	{
		return $this->results->EOF;
	}
	function Escape_String(string $string_to_escape) : string
	{
		$link = mysqli_connect($this->cConfigs->Get_Root_Hostname(),$this->cConfigs->Get_Root_Username(),$this->cConfigs->Get_Root_Password(),"",(int)$this->cConfigs->Get_Root_Listeningport());
		$string = mysqli_real_escape_string($link,$string_to_escape);
		mysqli_close($link);
		return $string;
	}
}
?>