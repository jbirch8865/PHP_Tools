<?php
namespace databaseLink;
Class MySQLLink
{
	
	private string $username;
	private string $password;
	private string $hostname;
	private string $lastmysqlerror;
	private int $lastmysqlerrorno;
	private string $database;
	private string $lastinsertid;
	private string $listeningport;

	function __construct(string $database_to_connect_to,bool $run_as_root_user = false)
	{
		if($run_as_root_user)
		{
			$this->LoadRootConfigurationFile();
			if(!$this->username)
			{
				throw new \Exception("Check config file for database configs.  Root username does not exist.");
			}
		}else
		{
			$this->LoadConfigurationFile($database_to_connect_to);
			if(!$this->username)
			{
				throw new \Exception("Check config file for database configs. Username does not exist.");
			}
		}
		$this->EstablishdatabaseLink($database_to_connect_to);
	}
	private function LoadConfigurationFile(string $database_to_connect_to)
	{
		$cConfigs = new \Config\ConfigurationFile;
		$this->username = $cConfigs->Get_Value_If_Enabled($database_to_connect_to.'_username');
		$this->password = $cConfigs->Get_Value_If_Enabled($database_to_connect_to.'_password');
		$this->hostname = $cConfigs->Get_Value_If_Enabled($database_to_connect_to.'_hostname');
		$this->listeningport = $cConfigs->Get_Value_If_Enabled($database_to_connect_to.'_listeningport');
	}
	private function LoadRootConfigurationFile()
	{
		$cConfigs = new \Config\ConfigurationFile;
		$this->username = $cConfigs->Get_Value_If_Enabled('root_username');
		$this->password = $cConfigs->Get_Value_If_Enabled('root_password');
		$this->hostname = $cConfigs->Get_Value_If_Enabled('hostname');
		$this->listeningport = $cConfigs->Get_Value_If_Enabled('listeningport');
	}
	private function EstablishdatabaseLink(string $database_to_connect_to)
	{
		$driver = 'mysqli';
 
		$db = \newAdoConnection($driver); 		 
		if(!$db->connect($this->hostname,$this->username,$this->password,$database_to_connect_to))
		{
			throw new SQLConnectionError("Couldn't connect to mysql");
		}
		
	}
	function ExecuteSQLQuery(string $query)
	{
		$response = $this->QuerySQL($query);
		$this->lastinsertid = mysqli_insert_id($this->database);
		return $response;
	}
	private function QuerySQL(string $query)
	{
		if(!$response = mysqli_query($this->database, $query))
		{
			$this->lastmysqlerror = mysqli_error($this->database);
			$this->lastmysqlerrorno = mysqli_errno($this->database);
			if($this->lastmysqlerrorno == '1062')
			{
				throw new DuplicatePrimaryKeyRequest("You are trying to create a duplicate entry for the primary key in the DB");
			}else
			{
				throw new SQLQueryError("SQL Server returned error number ".$this->lastmysqlerrorno." - ".mysqli_error($this->database));
			}
		}else
		{
			return $response;
		}
	}
	function GetCurrentLink()
	{
		return $this->database;
	}
	function GetLastInsertID()
	{
		return $this->lastinsertid;
	}
	function GetLastError()
	{
		return $this->lastmysqlerror;
	}
	function GetLastErrorNumber()
	{
		return $this->lastmysqlerrorno;
	}
}
?>