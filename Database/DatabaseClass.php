<?php
namespace databaseLink;
Class MySQLLink
{
	private string $username;
	private string $password;
	private string $hostname;
	private string $lastmysqlerror;
	private int $lastmysqlerrorno;
	private \ADODB_mysqli $database;
	private string $listeningport;

	function __construct(string $database_to_connect_to,bool $run_as_root_user = false)
	{
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
		$cConfigs = new \Config\ConfigurationFile;
		$this->username = $cConfigs->Get_Value_If_Enabled($database_to_connect_to.'_username');
		$this->password = $cConfigs->Get_Value_If_Enabled($database_to_connect_to.'_password');
		$this->hostname = $cConfigs->Get_Value_If_Enabled($database_to_connect_to.'_hostname');
		$this->listeningport = $cConfigs->Get_Value_If_Enabled($database_to_connect_to.'_listeningport');
	}
	private function Load_Root_Configuration_File()
	{
		$cConfigs = new \Config\ConfigurationFile;
		$this->username = $cConfigs->Get_Value_If_Enabled('root_username');
		$this->password = $cConfigs->Get_Value_If_Enabled('root_password');
		$this->hostname = $cConfigs->Get_Value_If_Enabled('root_hostname');
		$this->listeningport = $cConfigs->Get_Value_If_Enabled('root_listeningport');
	}
	private function Establish_Database_Link(string $database_to_connect_to)
	{
		$driver = 'mysqli';
 
		$db = \newAdoConnection($driver); 		 
		if(!$db->connect($this->hostname,$this->username,$this->password,$database_to_connect_to))
		{
			throw new SQLConnectionError("Couldn't connect to mysql");
		}
		$this->database = $db;
		
	}
	function Is_Connected()
	{
		return $this->database->isConnected();
	}
	function Execute_Any_SQL_Query(string $query)
	{
		$response = $this->database->execute($query);
		return $response;
	}
	function Execute_Any_SQL_Query_With_Caching(string $query)
	{
		$ADODB_CACHE_DIR=dirname(__FILE__).'/queries';
		$response = $this->database->cacheExecute($query);
		return $response;
	}
	function Get_Last_Insert_ID()
	{
		return $this->database->insert_id();
	}
	function Get_Last_Error()
	{
		return $this->lastmysqlerror;
	}
	function Get_Last_Error_Number()
	{
		return $this->lastmysqlerrorno;
	}
}
?>