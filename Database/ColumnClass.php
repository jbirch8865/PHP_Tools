<?php declare(strict_types=1);
namespace DatabaseLink;
use DatabaseLink\Column_Does_Not_Exist;
use DatabaseLink\SQLQueryError;


class Column extends MySQL_Equation_Strings
{
	public Table $table_dblink;
	private ?string $verified_column_name = NULL;
	private string $data_type = "INT(11)";
	private ?int $data_length = null;
	private ?string $default_value = NULL;
	private string $auto_increment = "auto_increment";
	private bool $is_nullable = false;
	private string $column_key = "PRI";
	protected ?string $field_value = NULL;
	private bool $include_in_response = true;

	/**
	 * @param array $default_values {not caps sensative}
	 * array("COLUMN_TYPE" = valid mysql columntype string,
	 * 	"COLUMN_DEFAULT" = ["NULL"{will make column nullable},
	 * 			"string"{in absence of value this will be used},
	 * 			null{no default, value will be required}],
	 * 	"is_nullable" = bool,"column_key" = ["","PRI","UNI"],
	 *  "EXTRA" = "auto_increment",
	 *  "CHARACTER_MAXIMUM_LENGTH" = "64",
	 * 	["COLUMN_COMMENT" = "exclude"])
	 * if is_nullable = true and default_value is NULL then the default will be NULL if is_nullable = false and default_value = NULL
	 * then there will be no default
	 * @throws Exception if default values are not all set
	 * @throws SQLQueryError
	 */
	function __construct(string $unverified_column_name,Table &$table_dblink,array $default_values = array())
	{
		$this->table_dblink = $table_dblink;
		$unverified_column_name = $this->table_dblink->database_dblink->dblink->Escape_String($unverified_column_name);
		if((count($default_values) != 6 && !$this->Does_Column_Exist($unverified_column_name)) && (count($default_values) == 0 && $this->Does_Column_Exist($unverified_column_name)))
		{
			throw new \Exception('sorry I can\'t create the column unless you supply all the default values');
		}else
		{
			$this->Set_Default_Values_From_Array($default_values);
		}
		$this->If_Does_Not_Exist_Create_Column($unverified_column_name);
	}
	private function If_Does_Not_Exist_Create_Column(string $unverified_column_name) : void
	{
		if($this->Does_Column_Exist($unverified_column_name))
		{
			$this->verified_column_name = $unverified_column_name;
		}else
		{
			$this->Create_Column($unverified_column_name);
		}
	}
	private function Does_Column_Exist(string $unverified_column_name) : bool
	{
		if($this->table_dblink->database_dblink->dblink->Does_This_Return_A_Count_Of_More_Than_Zero('information_schema.columns','table_schema = \''.$this->table_dblink->database_dblink->Get_Database_Name().'\' AND column_name = \''.$unverified_column_name.'\' AND table_name = \''.$this->table_dblink->Get_Table_Name().'\'','understood'))
		{
			return true;
		}else
		{
			return false;
		}
	}
	/**
	 * @throws SQLQueryError
	 */
	private function Create_Column(string $unverified_column_name) : void
	{
		$AUTO_INCREMENT = "";
		$default_value = "";
		if(strtolower($this->column_key) == 'pri')
		{
			$PRIMARY_KEY = ", ADD PRIMARY KEY(`".$unverified_column_name."`)";
		}elseif(strtolower($this->column_key) == 'uni')
		{
			$PRIMARY_KEY = ", ADD CONSTRAINT ".$unverified_column_name." UNIQUE IF NOT EXISTS (`".$unverified_column_name."`)";
		}else
		{
			$PRIMARY_KEY = "";
		}
		if($this->is_nullable)
		{
			$NULL = " NULL";
		}else
		{
			$NULL = " NOT NULL";
		}
		if(is_null($this->default_value))
		{
			$default_values = "";
		}elseif(strtolower($this->default_value) == 'null')
		{
			$NULL = " NULL";
			$default_value = " DEFAULT NULL";
		}else
		{
			$default_value = " DEFAULT '".$this->default_value."'";
		}
		if(!$this->include_in_response)
		{
			$COMMENT = " COMMENT 'exclude'";
		}	else
		{
			$COMMENT = "";
		}
		if(strtolower($this->auto_increment) == "auto_increment")
		{
			$NULL = " NOT NULL";
			$AUTO_INCREMENT = " AUTO_INCREMENT";
		}
		try
		{
			$this->table_dblink->database_dblink->dblink->Execute_Any_SQL_Query("ALTER TABLE ".$this->table_dblink->Get_Table_Name()." ADD
			".$unverified_column_name." ".$this->data_type."$NULL$default_value$AUTO_INCREMENT$PRIMARY_KEY$COMMENT");
		} catch (SQLQueryError $e)
		{
			if($this->table_dblink->database_dblink->dblink->Get_Last_Error_Number() == 1060)
			{
				$this->table_dblink->database_dblink->dblink->Execute_Any_SQL_Query("ALTER TABLE ".$this->table_dblink->Get_Table_Name()." MODIFY
				".$unverified_column_name." ".$this->data_type."$NULL$default_value$AUTO_INCREMENT$PRIMARY_KEY$COMMENT");
			}else
			{
				throw new SQLQueryError($e->getMessage());
			}
		} catch (\Exception $e)
		{
			throw new \Exception('did i catch it?'.$e->getMessage());
		}
		if($this->Does_Column_Exist($unverified_column_name))
		{
			$this->verified_column_name = $unverified_column_name;
			$this->table_dblink->Load_Columns();
		}else
		{
			throw new SQLQueryError("Column did not appear to create.  Last Error - ".$this->table_dblink->database_dblink->dblink->Get_Last_Error());
		}
	}
	/**
	 * @throws SQLQueryError
	 */
	function Add_Constraint_If_Does_Not_Exist(Column $column_to_relate,bool $cascade = true):void
	{
		if($cascade)
		{
			$this->table_dblink->database_dblink->dblink->Execute_Any_SQL_Query("ALTER TABLE `".$this->table_dblink->Get_Table_Name()."`
			ADD CONSTRAINT `".$this->table_dblink->Get_Table_Name()."_".$this->Get_Column_Name()."_ibfk_1` FOREIGN KEY IF NOT EXISTS (`".$this->Get_Column_Name()."`) REFERENCES
			`".$column_to_relate->table_dblink->Get_Table_Name()."`(`".$column_to_relate->Get_Column_Name()."`) ON DELETE CASCADE ON UPDATE CASCADE;");
		}else
		{
			$this->table_dblink->database_dblink->dblink->Execute_Any_SQL_Query("ALTER TABLE `".$this->table_dblink->Get_Table_Name()."`
			ADD CONSTRAINT `".$this->table_dblink->Get_Table_Name()."_".$this->Get_Column_Name()."_ibfk_1` FOREIGN KEY IF NOT EXISTS (`".$this->Get_Column_Name()."`) REFERENCES
			`".$column_to_relate->table_dblink->Get_Table_Name()."`(`".$column_to_relate->Get_Column_Name()."`);");
		}

	}
	/**
	 * @throws SQLQueryError
	 */
	function Update_Column() : void
	{
		$this->Create_Column($this->Get_Column_Name());
	}
	/**
	 * @param string $password due to the destructive nature please pass 'destroy' to ensure you ware wanting to delete this column from the database
	 * @throws Exception if you don't use the password
	 * @throws SQLQueryError
	 */
	function Delete_Column(string $password):void
	{
		if($password != 'destroy')
		{
			throw new \Exception("sorry you need to pass 'destroy' as the password to delete a column");
		}
		$this->table_dblink->database_dblink->dblink->Execute_Any_SQL_Query('ALTER TABLE `'.$this->table_dblink->Get_Table_Name().'` DROP `'.$this->verified_column_name.'`');

	}
	/**
	 * @throws SQLQueryError
	 */
	function Set_Data_Type(string $data_type,bool $update_now = true):void
	{
		$this->data_type = $data_type;
		if($update_now)
		{
			$this->Create_Column($this->verified_column_name);
		}
	}
	/**
	 * @throws SQLQueryError
	 */
	function Set_Default_Value(?string $default_value,bool $update_now = true):void
	{
		$this->default_value = $default_value;
		if($update_now)
		{
			$this->Create_Column($this->verified_column_name);
		}
	}
	/**
	 * @throws SQLQueryError
	 */
	function Exclude_From_Response(bool $update_now = true):void
	{
		$this->include_in_response = false;
		if($update_now)
		{
			$this->Create_Column($this->verified_column_name);
		}
	}
	function Am_I_Included_In_Response():bool
	{
		return (bool) $this->include_in_response;
	}
	/**
	 * @throws SQLQueryError
	 */
	function Set_Column_Key(string $column_key = "",bool $update_now = true):void
	{
		$this->column_key = $column_key;
		if($update_now)
		{
			$this->Create_Column($this->verified_column_name);
		}
	}
	/**
	 * @throws SQLQueryError
	 */
	function Set_Data_Length(int $data_length, bool $update_now = true) : void
	{
		$this->data_length = $data_length;
		if($update_now)
		{
			$this->Create_Column($this->verified_column_name);
		}
	}
	/**
	 * @throws SQLQueryError
	 * Escapes Strings
	 */
	function Set_Field_Value(?string $value):void
	{
		$value = $this->table_dblink->database_dblink->dblink->Escape_String($value);
		$this->field_value = $value;
	}
	function Get_Data_Length() : ?int
	{
		return $this->data_length;
	}
	function Get_Data_Type() : string
	{
		return $this->data_type;
	}
	function Get_Default_Value() : ?string
	{
		return $this->default_value;
	}
	function Get_Column_Name() : string
	{
		return $this->verified_column_name;
	}
	/**
	 * @throws SQLQueryError
	 */
	function Column_Is_Nullable(bool $update_now = true) :void
	{
		$this->is_nullable = true;
		if($update_now)
		{
			$this->Create_Column($this->verified_column_name);
		}
	}
	/**
	 * @throws SQLQueryError
	 */
	function Column_Is_Not_Nullable(bool $update_now = true) :void
	{
		$this->is_nullable = false;
		if($update_now)
		{
			$this->Create_Column($this->verified_column_name);
		}
	}
	/**
	 * @throws SQLQueryError
	 */

	function Column_Auto_Increments(bool $update_now = true) : void
	{
		$this->auto_increment = "auto_increment";
		if($update_now)
		{
			$this->Create_Column($this->verified_column_name);
		}
	}
	/**
	 * @throws SQLQueryError
	 */

	function Column_Does_Not_Auto_Increments(bool $update_now = true) : void
	{
		$this->auto_increment = "";
		if($update_now)
		{
			$this->Create_Column($this->verified_column_name);
		}
	}
	function Is_Column_Nullable() : bool
	{
		return $this->is_nullable;
	}
	function Get_Column_Key() : string
	{
		return $this->column_key;
	}
	function Get_Field_Value() : string
	{
		return $this->field_value;
	}
	function Does_Auto_Increment() : bool
	{
		if($this->auto_increment == 'auto_increment')
		{
			return true;
		}else
		{
			return false;
		}
	}

	//Messy code that I don't know how to clean up
	private function Set_Default_Values_From_Array(array $default_values) : void
	{
		ForEach($default_values as $value_name => $value_to_set)
		{
			if(strtolower($value_name) == "column_type")
			{
				$this->Set_Data_Type($value_to_set,false);
			}elseif(strtolower($value_name) == "character_maximum_length")
			{
				$value_to_set = (int) $value_to_set;
				$this->Set_Data_Length($value_to_set,false);
			}elseif(strtolower($value_name) == "column_default")
			{
				if(is_string($value_to_set))
				{
					if($value_to_set == "''''")
					{
						$value_to_set = "";
					}else
					{
						$value_to_set = trim($value_to_set,"'");
					}
				}
				$this->Set_Default_Value($value_to_set,false);
			}elseif(strtolower($value_name) == "is_nullable")
			{
				if($value_to_set == "YES")
				{
					$this->Column_Is_Nullable(false);
				}else
				{
					$this->Column_Is_Not_Nullable(false);
				}
			}elseif(strtolower($value_name) == "column_key")
			{
				$this->Set_Column_Key($value_to_set,false);
			}elseif(strtolower($value_name) == "extra")
			{
				if($value_to_set == "auto_increment")
				{
					$this->Column_Auto_Increments(false);
				}else
				{
					$this->Column_Does_Not_Auto_Increments(false);
				}
			}elseif(strtolower($value_name) == "character_maximum_length")
			{
				$this->Set_Data_Length($value_to_set,false);
			}elseif(strtolower($value_name) == 'column_comment')
			{
				if(strtolower($value_to_set) == 'exclude')
				{
					$this->include_in_response = false;
				}else
				{
					$this->include_in_response = true;
				}
			}
		}
	}

}

abstract class MySQL_Equation_Strings
{
	protected ?string $field_value;
	public \DatabaseLink\Table $table_dblink;

	public function Equals(?string $value) : \DatabaseLink\Safe_Strings
	{
		$this->Set_Field_Value($value);
		if(is_null($this->Get_Field_Value()))
		{
			$string = "`".$this->table_dblink->Get_Table_Name()."`.`".$this->Get_Column_Name()."`IS NULL ";
			$safestring = new \DatabaseLink\Safe_Strings($string);
		}else
		{
			$string = "`".$this->table_dblink->Get_Table_Name()."`.`".$this->Get_Column_Name()."`='".$this->Get_Field_Value()."'";
			$safestring = new \DatabaseLink\Safe_Strings($string);
		}
		return $safestring;
	}

	public function NotEquals(?string $value) : \DatabaseLink\Safe_Strings
	{
		$this->Set_Field_Value($value);
		if(is_null($this->Get_Field_Value()))
		{
			$string = "`".$this->table_dblink->Get_Table_Name()."`.`".$this->Get_Column_Name()."`IS NOT NULL ";
			$safestring = new \DatabaseLink\Safe_Strings($string);
		}else
		{
			$string = "`".$this->table_dblink->Get_Table_Name()."`.`".$this->Get_Column_Name()."`<>'".$this->Get_Field_Value()."'";
			$safestring = new \DatabaseLink\Safe_Strings($string);
		}
		return $safestring;
	}

	public function GreaterThan(string $value) : \DatabaseLink\Safe_Strings
	{
		$this->Set_Field_Value($value);
		$string = "`".$this->table_dblink->Get_Table_Name()."`.`".$this->Get_Column_Name()."`>'".$this->Get_Field_Value()."'";
		$safestring = new \DatabaseLink\Safe_Strings($string);
		return $safestring;
	}

	public function GreaterThanOrEqual(string $value) : \DatabaseLink\Safe_Strings
	{
		$this->Set_Field_Value($value);
		$string = "`".$this->table_dblink->Get_Table_Name()."`.`".$this->Get_Column_Name()."`>='".$this->Get_Field_Value()."'";
		$safestring = new \DatabaseLink\Safe_Strings($string);
		return $safestring;
	}

	public function LessThan(string $value) : \DatabaseLink\Safe_Strings
	{
		$this->Set_Field_Value($value);
		$string = "`".$this->table_dblink->Get_Table_Name()."`.`".$this->Get_Column_Name()."`<'".$this->Get_Field_Value()."'";
		$safestring = new \DatabaseLink\Safe_Strings($string);
		return $safestring;
	}

	public function LessThanOrEqual(string $value) : \DatabaseLink\Safe_Strings
	{
		$this->Set_Field_Value($value);
		$string = "`".$this->table_dblink->Get_Table_Name()."`.`".$this->Get_Column_Name()."`<='".$this->Get_Field_Value()."'";
		$safestring = new \DatabaseLink\Safe_Strings($string);
		return $safestring;
	}
	abstract function Set_Field_Value(?string $value) : void;
	abstract function Get_Field_Value() : string;
	abstract function Get_Column_Name() : string;
}

class Safe_Strings
{
	private $safestring;
	function __construct(string $string)
	{
		$this->safestring = $string;
	}

	function Print_String() : string
	{
		return $this->safestring;
	}
}
?>
