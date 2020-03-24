<?php declare(strict_types=1);
namespace DatabaseLink;

use ArrayObject;
use DatabaseLink\Column_Does_Not_Exist;
use databaseLink\MySQLLink;
use DatabaseLink\SQLQueryError;
use phpDocumentor\Reflection\Types\Integer;

class Database
{
	public ?MySQLLink $dblink = NULL;
	private string $verified_database_name;
	private MySQLLink $root_dblink;
	private array $tables = array();
	private \ArrayIterator $table_iterator;
	/**
	 * @param string $unverified_database_name if this does not exist then a database will automatically be created and credentials will be created and added to the config file
	 * If the database is already created credentials are expected to already be created and linked in the config file.  If not manual intervention is required. 
	 * @throws SQLQueryError
	 */
	function __construct(string $unverified_database_name,bool $full_rights = true)
	{
		global $toolbelt_base;
		$this->root_dblink = $toolbelt_base->root_dblink;
		$unverified_database_name = $this->root_dblink->Escape_String($unverified_database_name);
		$this->If_Does_Not_Exist_Create_Database_And_Issue_Credentials($unverified_database_name);
		$user_to_use = (int) $full_rights;
		$this->dblink = new MySQLLink($unverified_database_name,$user_to_use);
		$this->Load_Tables();
		$array_object = new ArrayObject($this->tables);
		$this->table_iterator = $array_object->getIterator();
	}
	private function If_Does_Not_Exist_Create_Database_And_Issue_Credentials(string $unverified_database_name) : void
	{
		if($this->Does_Database_Exist($unverified_database_name))
		{
			$this->verified_database_name = $unverified_database_name;
		}else
		{
			$this->Create_Database_And_Issue_Credentials($unverified_database_name);
		}
	}
	private function Does_Database_Exist(string $unverified_database_name) : bool
	{
		if($this->root_dblink->Does_This_Return_A_Count_Of_More_Than_Zero("INFORMATION_SCHEMA.SCHEMATA","SCHEMA_NAME = '".$unverified_database_name."'",'understood'))
		{
			return true;
		}else
		{
			return false;
		}
	}
	private function Create_Database_And_Issue_Credentials(string $unverified_database_name) : void
	{
		$this->Create_Database($unverified_database_name);
		$this->Create_Full_Database_User($unverified_database_name);
		$this->Create_Read_Only_Database_User();
	}
	private function Create_Database(string $unverified_database_name) : void
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
	private function Create_Full_Database_User($unverified_database_name) : void
	{
		$password = Generate_CSPRNG(18,'D&hFl@gg1ng');
		$this->root_dblink->Execute_Any_SQL_Query("
		CREATE USER '".$unverified_database_name."'@'%' IDENTIFIED BY '".$password."'");
		$this->root_dblink->Execute_Any_SQL_Query("GRANT ALL PRIVILEGES ON 
		`".$this->verified_database_name."`.* TO 
		'".$this->verified_database_name."'@'%';");
		$this->root_dblink->cConfigs->Set_Database_Connection_Preferences('localhost',$this->verified_database_name,$password,$this->verified_database_name);
	}
	private function Create_Read_Only_Database_User() : void
	{
		$password = Generate_CSPRNG(14,'D&hFl@gg1ng');
		$this->root_dblink->Execute_Any_SQL_Query("
		CREATE USER 'read_only_".$this->verified_database_name."'@'%' IDENTIFIED BY '".$password."'");
		$this->root_dblink->Execute_Any_SQL_Query("GRANT SELECT ON 
		`".$this->verified_database_name."`.* TO 
		'read_only_".$this->verified_database_name."'@'%';");
		$this->root_dblink->cConfigs->Set_Database_Connection_Preferences('localhost','read_only_'.$this->verified_database_name,$password,$this->verified_database_name,"3306",true);
	}

	/**
	 * This will drop the database with foreign relation checks enabled so it's possible it will fail and the foreign relationship will need to be removed first
	 * @param string $password since this is such a destructive public function you need to enter "destroy" as the password in order for this to execute
	 * This will also destroy all properties belonging to this class.  Recommended that you unset after you run this command
	 * @throws Exception if password not set
	 * @throws SQLQueryError
	 */
	function Drop_Database_And_User(string $password) : void
	{
		if($password != "destroy")
		{
			throw new \Exception("You didn't enter in the password to drop this database");
		}
		$this->root_dblink->Execute_Any_SQL_Query("DROP DATABASE `".$this->verified_database_name."`");
		$this->root_dblink->Execute_Any_SQL_Query("DROP USER '".$this->verified_database_name."'@'%'");
		$this->root_dblink->Execute_Any_SQL_Query("DROP USER 'read_only_".$this->verified_database_name."'@'%'");
		$this->root_dblink->cConfigs->Delete_Database_Configs($this->verified_database_name);
		ForEach($this as $key => $value)
		{
			unset($this->$key);
		}
	}

	function Get_Database_Name() : string
	{
		return $this->dblink->Get_Database_Name();
	}

	private function Load_Tables() : void
	{
		$tables = $this->root_dblink->Execute_Any_SQL_Query("SELECT TABLE_NAME FROM `information_schema`.`tables` WHERE `TABLE_SCHEMA` = '".$this->Get_Database_Name()."'");
		$tables = $this->root_dblink->Get_Results();
		ForEach($tables as $row => $value)
		{
			$this->tables[$value['TABLE_NAME']] = new Table($value['TABLE_NAME'],$this);
		}
	}

	/**
	 * this function will return the current table in the table array and advance the index to the next table
	 * use function Reset_Tables() to reset the pointer back to the beginning
	 * use case while($table = $->Get_Tables())
	 */
	function Get_Tables() : ?Table
	{
		while($this->table_iterator->valid())
		{
			$table = $this->table_iterator->current();
			$this->table_iterator->Next();
			return $table;
		}
		return null;
	}
	function Reset_Tables() : void
	{
		$this->table_iterator->rewind();
	}
	function Get_Number_Of_Tables() : int
	{
		return count($this->tables);
	}
	function Does_Table_Exist(string $table_name) : bool
	{
		While($table = $this->Get_Tables())
		{
			if($table->Get_Table_Name() == $table_name)
			{
				$this->Reset_Tables();
				return true;
			}
		}
		$this->Reset_Tables();
		return false;
	}
	function Drop_All_Constraints()
	{
		$this->root_dblink->Execute_Any_SQL_Query("SELECT concat('ALTER TABLE ', concat(TABLE_SCHEMA,'.',TABLE_NAME), ' DROP FOREIGN KEY ', CONSTRAINT_NAME, ';') as 'query'
		FROM information_schema.key_column_usage 
		WHERE CONSTRAINT_SCHEMA = '".$this->Get_Database_Name()."'
		AND referenced_table_name IS NOT NULL;");
		$rows = $this->root_dblink->Get_Results();
		ForEach($rows as $row)
		{
			$this->root_dblink->Execute_Any_SQL_Query($row['query']);
		}
	}
	function Drop_All_Indexes()
	{
		$this->root_dblink->Execute_Any_SQL_Query("SELECT DISTINCT
    	TABLE_NAME,
    	INDEX_NAME
		FROM INFORMATION_SCHEMA.STATISTICS
		WHERE TABLE_SCHEMA = '".$this->Get_Database_Name()."';");
		$rows = $this->root_dblink->Get_Results();
		ForEach($rows as $row)
		{
			if(strpos($row['INDEX_NAME'],"_ibfk_1"))
			{
				$this->root_dblink->Execute_Any_SQL_Query("ALTER TABLE `".$this->Get_Database_Name()."`.`".$row['TABLE_NAME']."` DROP INDEX `".$row['INDEX_NAME']."`");
			}
		}
	}
}
?>