<?php
namespace DatabaseLink;

Class MySQLLink
{
	
	private string $database;
	private int $lastinsertid;
	private int $lastlogid;
	private string $username;
	private string $password;
	private string $hostname;
	private int $listeningport;
	private string $lastmysqlerror;
	private int $lastmysqlerrorno;

	function __construct(string $database_to_connect_to,bool $run_as_root_user = false)
	{
		if($run_as_root_user)
		{
			$this->LoadRootConfigurationFile();
		}else
		{
			$this->LoadConfigurationFile();
		}
		$this->EstablishDatabaseLink($database_to_connect_to);
	}
	
	private function LoadConfigurationFile()
	{
		$cConfigs = new \Config\ConfigurationFile;
		$this->username = $cConfigs->Get_Value_If_Enabled('username');
		$this->password = $cConfigs->Get_Value_If_Enabled('password');
		$this->hostname = $cConfigs->Get_Value_If_Enabled('hostname');
		$this->listeningport = $cConfigs->Get_Value_If_Enabled('listeningport');
	}
	
	private function LoadRootConfigurationFile()
	{
		$cConfigs = new \Config\ConfigurationFile;
		$this->username = $cConfigs->Get_Value_If_Enabled('root_username');
		$this->password = $cConfigs->Get_Value_If_Enabled('root_password');
		$this->hostname = $cConfigs->Get_Value_If_Enabled('hostname');
		$this->listeningport = $cConfigs->Get_Value_If_Enabled('listeningport');
	}

	private function EstablishDatabaseLink($database_to_connect_to)
	{
		$driver = 'mysqli';
 
		$db = \newAdoConnection($driver); 		 
		if(!$db->connect($this->hostname,$this->username,$this->password,$database_to_connect_to))
		{
			throw new SQLConnectionError("Couldn't connect to mysql");
		}
		
	}
	
	function ExecuteSQLQuery( $Query, $Type = '10', $Ignore_Log_Error = true)
	{
		Try
		{
			$Response = $this->QuerySQL($Query);
			$this->LastInsertID = mysqli_insert_id($this->Database);
			$this->AddToSyslog($Query, $this->LastMySQLError, $Type,$Ignore_Log_Error);		
			return $Response;
		} catch (SQLQueryError $e)
		{
			$this->AddToSyslog($Query, $this->LastMySQLError, $Type,$Ignore_Log_Error);		
			throw new SQLQueryError($e->getMessage());
		} catch (DuplicatePrimaryKeyRequest $e)
		{
			$this->AddToSyslog($Query, $this->LastMySQLError, $Type,$Ignore_Log_Error);		
			throw new DuplicatePrimaryKeyRequest($e->getMessage());
		} catch (\Exception $e)
		{
			$this->AddToSyslog($Query, "unknown error running this SQL query", $Type,$Ignore_Log_Error);		
			throw new \Exception($e->getMessage());
		}
	}
	
	private function QuerySQL($Query)
	{
		if(!$Response = mysqli_query($this->Database, $Query))
		{
			$this->LastMySQLError = mysqli_error($this->Database);
			$this->LastMySQLErrorNo = mysqli_errno($this->Database);
			if($this->LastMySQLErrorNo == '1062')
			{
				throw new DuplicatePrimaryKeyRequest("You are trying to create a duplicate entry for the primary key in the DB");
			}else
			{
				throw new SQLQueryError("SQL Server returned error number ".$this->LastMySQLErrorNo." - ".mysqli_error($this->Database));
			}
		}else
		{
			return $Response;
		}
	}
	
	function AddToSyslog( $Query, $Response = "", $Type = '3',$Ignore_Log_Error)
	{
		Try 
		{
			$this->QuerySQL("INSERT INTO `syslog`.`Sys_Log` SET Message = '".str_replace("'","\'",$Query)."', Response = '".str_replace("'","\'",$Response)."', Message_Type = '$Type'");
			$this->LastLogID = mysqli_insert_id($this->Database);
		} catch (SQLQueryError $e)
		{
			if(!$Ignore_Log_Error)
			{
				throw new SQLQueryError($e->getMessage());
			}
		}
	}
	function GetCurrentLink()
	{
		return $this->Database;
	}
	function GetLastInsertID()
	{
		return $this->LastInsertID;
	}
	function GetLastLogID()
	{
		return $this->LastLogID;
	}

	/**
    *
    * Returns the last error that mysqli had
    *
    */
	function GetLastError()
	{
		return $this->LastMySQLError;
	}
	function GetLastErrorNumber()
	{
		return $this->LastMySQLErrorNo;
	}
}
///UPDATE - BELOW is an exerpt from a previous project I am retaining in case it comes in handy again.
/*
Class Incident_Tickets_DB_Link 
{
	private $DBLink;
	public function __construct()
	{
		try
		{
			$this->SetDBLink();
		} catch (Exception $e)
		{
			throw new SQLConnectionError("There was an error connecting to the SQL DB");
		}
	}
	private function SetDBLink()
	{
		global $Incident_Tickets_Link;
		$this->DBLink = $Incident_Tickets_Link;
	}
	function GetDBLink()
	{
		return $this->DBLink;
	}	
}
*/
////Due to the issues of constantly needing $User = new User($User_ID) or $Ticket = new Ticket($Ticket_ID) I was rapidly using up all the avaiable SQL thread connections.  So after researching online I decided to store all the necessary Database links as global variables and then build classes that load those global variables to local DBLink variables. Then I have a public method called GetDBLink in each class.  So instead of Exctends MySQLLink we will instead extend the instantion of the link we want the naming convention will be NameOfDatabase_DB_Link
//global $Incident_Tickets_Link;
//$Incident_Tickets_Link = new MySQLLink('Incident_Tickets');
?>