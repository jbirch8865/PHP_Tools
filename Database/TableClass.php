<?php declare(strict_types=1);
namespace databaseLink;
use DatabaseLink\Column_Does_Not_Exist;
use DatabaseLink\SQLQueryError;


class Table
{
	public ?Database $database_dblink = NULL;
	private ?string $verified_table_name = NULL;
	private int $number_of_table_rows = 0;
	private array $rows_in_query = array();
	private array $columns = array();
	private \ArrayIterator $row_iterator;
	private \ArrayIterator $column_iterator;
	private string $where_section;

	/**
	 * @param string $unverified_table_name if does not exist will automatically create it
	 * @throws SQLQueryError
	 */
	function __construct(string $unverified_table_name,Database &$database_dblink)
	{
		$this->database_dblink = $database_dblink;
		$unverified_table_name = $this->database_dblink->dblink->Escape_String($unverified_table_name);
		$this->If_Does_Not_Exist_Create_Table($unverified_table_name);
		$this->Load_Columns();
	}
	private function If_Does_Not_Exist_Create_Table(string $unverified_table_name) : void
	{
		if($this->Does_Table_Exist($unverified_table_name))
		{
			$this->verified_table_name = $unverified_table_name;
		}else
		{
			$this->Create_Table($unverified_table_name);
		}
	}
	private function Does_Table_Exist(string $unverified_table_name) : bool
	{
		if($this->database_dblink->dblink->Does_This_Return_A_Count_Of_More_Than_Zero('information_schema.tables','table_schema = \''.$this->database_dblink->Get_Database_Name().'\' AND table_name = \''.$unverified_table_name.'\'','understood'))
		{
			return true;
		}else
		{
			return false;
		}
	}
	private function Create_Table(string $unverified_table_name):void
	{
		$this->database_dblink->dblink->Execute_Any_SQL_Query("CREATE TABLE `".$unverified_table_name."` (
			 `delete_me` int(11) NOT NULL)
			 ENGINE=InnoDB DEFAULT CHARSET=latin1;");
		if($this->Does_Table_Exist($unverified_table_name))
		{
			$this->verified_table_name = $unverified_table_name;
			$this->Create_Column('id');
			$this->Delete_Column('delete_me');
		}else
		{
			throw new SQLQueryError("Table did not appear to create.  Last Error - ".$this->database_dblink->dblink->Get_Last_Error());
		}
	}
	function Get_Table_Name() : string
	{
		return $this->verified_table_name;
	}
	function Get_Number_Of_Rows_In_Table() : int
	{
		$this->Load_Table();
		return $this->number_of_table_rows;
	}
	function Load_Columns() : void
	{
		$this->columns = array();
		$this->database_dblink->dblink->Execute_Any_SQL_Query("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '".$this->database_dblink->Get_Database_Name()."' AND TABLE_NAME = '".$this->Get_Table_Name()."'");
		$results = $this->database_dblink->dblink->Get_Results();
		ForEach($results as $key => $value)
		{
			$this->columns[$value['COLUMN_NAME']] = new Column($value['COLUMN_NAME'],$this,$value);
		}
		$arrayObject = new \ArrayObject($this->columns);
		$this->column_iterator = $arrayObject->getIterator();
	}
	private function Load_Table() : void
	{
		$this->database_dblink->dblink->Execute_Any_SQL_Query("SELECT *	FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '".$this->database_dblink->Get_Database_Name()."' AND TABLE_NAME = '".$this->Get_Table_Name()."'");
		$results = $this->database_dblink->dblink->Get_First_Row();
		$this->number_of_table_rows = (int) $results['TABLE_ROWS'];
	}

	/**
	 * @param array $INSERT_DATA array("column_name" => "value")
	 * @throws Column_Does_Not_Exist
	 * @throws Column_Is_Required
	 */
	function Insert_Row(array $INSERT_DATA) : void
	{
		ForEach($INSERT_DATA as $column_name => $value)
		{
			if(!array_key_exists($column_name,$this->columns))
			{
				throw new Column_Does_Not_Exist("Sorry but I can't find the column ".$column_name." in table ".$this->Get_Table_Name());
			}
		}
		While($column = $this->Get_Columns())
		{
			if(is_null($column->Get_Default_Value()) && !array_key_exists($column_name,$INSERT_DATA) && !$column->Does_Auto_Increment())
			{
				throw new Column_Is_Required("Sorry but you didn't pass a value for column ".$column_name." and this column is required in table ".$this->Get_Table_Name()." when inserting a new row");
			}
		}
		$this->database_dblink->dblink->Execute_Insert_Or_Update_SQL_Query($this->Get_Table_Name(),$INSERT_DATA);
	}
	/**
	 * @param array $UPDATE_DATA array("column_name" => "value")
	 * @throws Column_Does_Not_Exist
	 */
	function Update_Row(array $UPDATE_DATA,string $WHERE = "") : void
	{
		ForEach($UPDATE_DATA as $column_name => $value)
		{
			if(!array_key_exists($column_name,$this->columns))
			{
				throw new Column_Does_Not_Exist("Sorry but I can't find the column ".$column_name." in table ".$this->Get_Table_Name());
			}
		}
		$this->database_dblink->dblink->Execute_Insert_Or_Update_SQL_Query($this->Get_Table_Name(),$UPDATE_DATA,true,$WHERE);
	}

	/**
	 * This is now depricated use the LimitBy functions to build a query and use the Query_Table function to run
	 * @param array $select_data array("column_name","column2_name")
	 * @param bool $select_all_data if true will ignore select_data
	 * @param string $where = "WHERE `column_name` = 'red'"
	 * @throws SQLQueryError
	 * @return void Use $->Get_Queried_Data() to get results or $->Get_Number_Of_Rows_In_Query() to see the number of results
	 */
	function Query_Single_Table(array $select_data = array(),bool $select_all_data = false,string $where = "") : void
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
		$rows = $this->database_dblink->dblink->Get_Results();
		$arrayObject = new \ArrayObject($rows);
		$this->row_iterator = $arrayObject->getIterator();
	}

	/**
	 * This is now depricated use the LimitBy functions to build a query and use the Query_Table function to run
	 * @param array $select_data ["column_name","column2_name"]
	 * @throws SQLQueryError
	 * @return void Use $->Get_Queried_Data() to get results or $->Get_Number_Of_Rows_In_Query() to see the number of results
	 */
	function Query_Table(array $select_data = []) : void
	{
		$columns_to_select = implode(", ",$select_data);
		$this->database_dblink->dblink->Execute_Any_SQL_Query("SELECT ".$columns_to_select." FROM `".$this->Get_Table_Name()."`".$this->Get_Where_Clause());
		$rows = $this->database_dblink->dblink->Get_Results();
		$arrayObject = new \ArrayObject($rows);
		$this->row_iterator = $arrayObject->getIterator();
	}

	/**
	 * while($row = $->Get_Queried_Data())
	 */
	function Get_Queried_Data() : ?array
	{
		while($this->row_iterator->valid())
		{
			$return_value = $this->row_iterator->current();
			$this->row_iterator->Next();
			return $return_value;
		}
		return null;
	}
	/**
	 * while($column = $table_class->Get_Columns())
	 */
	function Get_Columns():?Column
	{
		while($this->column_iterator->valid())
		{
			$return_value = $this->column_iterator->current();
			$this->column_iterator->Next();
			return $return_value;
		}
		return null;
	}
	function Get_Column(string $column_name):?Column
	{
		$this->Reset_Columns();
		While($column = $this->Get_Columns())
		{
			if($column->Get_Column_Name() == $column_name)
			{
				$this->Reset_Columns();
				return $column;
			}
		}
		throw new \DatabaseLink\Column_Does_Not_Exist($column_name.' does not exist in table '.$this->Get_Table_Name());
	}
	function Does_Column_Exist(string $column_name):bool
	{
		$this->Reset_Columns();
		While($column = $this->Get_Columns())
		{
			if($column->Get_Column_Name() == $column_name)
			{
				$this->Reset_Columns();
				return true;
			}
		}
		return false;

	}
	function Get_Number_Of_Columns() : int
	{
		return count($this->columns);
	}
	function Reset_Queried_Data() : void
	{
		$this->row_iterator->rewind();
	}
	function Reset_Columns() : void
	{
		$this->column_iterator->rewind();
	}
	function Get_Number_Of_Rows_In_Query() : int
	{
		$count = iterator_count($this->row_iterator);
		$this->Reset_Queried_Data();
		return $count;
	}
	/**
	 * @param string $where = "WHERE `id` = '3'"
	 * @param bool $delete_all_data must be true if you want to delete all data, $where will be ignored if true
	 * @throws Exception if delete_all_data is false and where is empty
	 * @throws SQLQueryError
	 */
	function Delete_Row(string $where = "",bool $delete_all_data = false) : void
	{
		if(!$delete_all_data && $where == "")
		{
			throw new \Exception("Sorry you need to specify that you want to delete all data explicitly.");
		}elseif($delete_all_data)
		{
			$where = "";
		}else
		{
			$where = " ".$where;
		}
		$this->database_dblink->dblink->Execute_Any_SQL_Query("DELETE FROM `".$this->Get_Table_Name()."`".$where);
	}
	/**
	 * This will drop the table with foreign relation checks enabled so it's possible it will fail and the foreign relationship will need to be removed first
	 * @param string $password since this is such a destructive public function you need to enter "destroy" as the password in order for this to execute
	 * This will also destroy all properties belonging to this class.  Recommended that you unset after you run this command
	 * @throws Exception if password not set properly
	 * @throws SQLQueryError
	 */
	function Drop_Table($password) : bool
	{
		if(!$password == "destroy")
		{
			throw new \Exception("You didn't provide the password to delete this table.");
		}
		$this->database_dblink->dblink->Execute_Any_SQL_Query("DROP TABLE `".$this->verified_table_name."`;");
		if(!$this->Does_Table_Exist($this->verified_table_name))
		{
			ForEach($this as $key => $value)
			{

				unset($this->$key);
			}
			return true;
		}else
		{
			throw new SQLQueryError("Table did not appear to deleted.  Last Error - ".$this->database_dblink->dblink->Get_Last_Error());
		}
	}
	/**
	 * @throws SQLQueryError
	 */
	function Delete_Column(string $name) : void
	{
		While($column = $this->Get_Columns())
		{
			if($column->Get_Column_Name() == $name)
			{
				$column->Delete_Column('destroy');
				break;
			}
		}
	}
	/**
	 * @param array $default_values {not caps sensative}
	 * array("COLUMN_TYPE" = valid mysql columntype string,
	 * 	"COLUMN_DEFAULT" = ["NULL"{will make column nullable},
	 * 			"string"{in absence of value this will be used, for blank values must have a string value of ''},
	 * 			null{no default, value will be required}],
	 * 	"is_nullable" = bool,"column_key" = ["","PRI","UNI"],
	 *  "EXTRA" = "auto_increment")
	 * if is_nullable = true and default_value is NULL then the default will be NULL if is_nullable = false and default_value = NULL
	 * then there will be no default
	 * @throws Exception if default values aren't all set
	 * @throws SQLQueryError
	 */
	function Create_Column(string $unverified_column_name,array $default_values = array()) : void
	{
		$column = new Column($unverified_column_name,$this,$default_values);
	}
	/**
	 * @param array $columns_to_be_unique = array('column1','column2')
	 * @throws SQLQueryError except on duplicate entries, duplicate entries exception is ignored
	 */
	function Add_Unique_Columns(array $columns_to_be_unique) : void
	{
		ForEach($columns_to_be_unique as $column_name)
		{
			if(!$this->Does_Column_Exist($column_name))
			{
				throw new \DatabaseLink\Column_Does_Not_Exist($column_name." does not exist in table ".$this->Get_Table_Name());
			}
		}
		$columns = implode(",",Wrap_Array_Values_With_String("`",$columns_to_be_unique));
		try
		{
			$this->database_dblink->dblink->Execute_Any_SQL_Query('ALTER TABLE `'.$this->Get_Table_Name().'` ADD UNIQUE `'.implode(",",$columns_to_be_unique).'` ('.$columns.')');
		} catch (\DatabaseLink\SQLQueryError $e)
		{
			if(!$this->database_dblink->dblink->Get_Last_Error_Number() == 1061)
			{
				throw new \DatabaseLink\SQLQueryError($e->getMessage());
			}
		}
	}

	public function LimitBy(\DatabaseLink\Safe_Strings $string)
	{
		$this->where_section = $this->where_section.$string;
	}

	public function LimitByGroup(\DatabaseLink\Safe_Strings $string)
	{
		$this->where_section = $this->where_section."(".$string;
	}

	public function LimitByEndGroup(\DatabaseLink\Safe_Strings $string)
	{
		$this->where_section = $this->where_section.$string.")";
	}

	public function AndLimitBy(\DatabaseLink\Safe_Strings $string)
	{
		$this->where_section = $this->where_section."AND".$string;
	}

	public function AndLimitByGroup(\DatabaseLink\Safe_Strings $string)
	{
		$this->where_section = $this->where_section."AND(".$string;
	}

	public function AndLimitByEndGroup(\DatabaseLink\Safe_Strings $string)
	{
		$this->where_section = $this->where_section."AND".$string.")";
	}

	public function OrLimitBy(\DatabaseLink\Safe_Strings $string)
	{
		$this->where_section = $this->where_section."OR".$string;
	}

	public function OrLimitByGroup(\DatabaseLink\Safe_Strings $string)
	{
		$this->where_section = $this->where_section."OR(".$string;
	}

	public function OrLimitByEndGroup(\DatabaseLink\Safe_Strings $string)
	{
		$this->where_section = $this->where_section."OR".$string.")";
	}

	private function Get_Where_Clause() : string
	{
		return "WHERE".$this->where_section;
	}
	/**
     * @param string $object_class Company must be a valid app\Helpers\ class
     */
    public function Get_All_Objects(string $object_class,\Illuminate\Http\Request $request) : \Illuminate\Http\JsonResponse
    {
         if($request->input('include_disabled',false))
         {
             $this->Query_Single_Table(array('id'),false,"LIMIT ".$request->input('offset',0).", ".$request->input('limit',50));
         }else
         {
             $this->Query_Single_Table(array('id'),false,"WHERE `Active_Status` = '1' LIMIT ".$request->input('offset',0).", ".$request->input('limit',50));
         }
         $objects = array();
         While($row = $this->Get_Queried_Data())
         {
             $class = '\\app\\Helpers\\'.$object_class;
             $object = new $class;
             $object->Load_Object_By_ID($row['id']);
             if($request->input('include_details',false))
             {
                 $objects[$object->Get_Friendly_Name()] = $object->Get_API_Response_Collection();
             }else
             {
                 $objects[$object->Get_Friendly_Name()] = $object->Get_Verified_ID();
             }
         }
         return Response_200([
             'message' => 'Response Objects',
             $object_class => $objects
         ],$request);

	}
}

class Where
{
	private array $sections;

	function Add_Section(Where_Section $where_section)
	{
		$this->sections[] = $where_section;
	}

	function Print_Statement() : string
	{
		$return = 'WHERE';
		ForEach($this->sections as $where_section)
		{
			$return = $return.$where_section->Print_Statement();
		}
		return $return;
	}
}
/**
 * For any values, real_escap_string is taken care of for you
 */
class Where_Section
{
	private \Test_Tools\toolbelt_base $toolbelt;
	private array $sections;
	private string $type;
	/**
	 * @param string $type 'and'||'or'||''
	 */
	function __construct(string $type)
	{
		if($type == 'and' || $type == 'or' || $type == "")
		{
			$this->type = $type;
		}else
		{
			throw new \Exception('Where statement type can only be "and" or "or" or "" '.$type.' given');
		}
		$this->toolbelt = new \Test_Tools\toolbelt_base;
	}
	function And_Section(\DatabaseLink\Where_Section $where_section)
	{
		$this->sections['and'] = [$where_section];
	}

	function And_Column(\DatabaseLink\Column $column, string $value)
	{
		$value = $this->toolbelt->dblink->dblink->Escape_String($value);
		$this->sections['and'] = ['column' => $column,'value' => $value];
	}

	function Or_Section(\DatabaseLink\Where_Section $where_section)
	{
		$this->sections['or'] = [$where_section];
	}

	function Or_Column(\DatabaseLink\Column $column, string $value)
	{
		$value = $this->toolbelt->dblink->dblink->Escape_String($value);
		$this->sections['or'] = ['column' => $column,'value' => $value];
	}

	function Print_Statement() : string
	{
		$return = $this->type.'(';
		$first_run = true;
		ForEach($this->sections as $method => $section)
		{
			if(!$first_run)
			{
				if(count($section) > 1)
				{
					$return = $return."`".$section['column']."`='".$section['value']."'";
				}else
				{
					$return = $return.$section[0]->Print_Statement();
				}
			}else
			{
				if(count($section) > 1)
				{
					$return = $return.strtoupper($method)."`".$section['column']."`='".$section['value']."'";
				}else
				{
					$return = $return.$section[0]->Print_Statement();
				}
			}
		}
		$return = $return.')';
		return $return;
	}
}
?>
