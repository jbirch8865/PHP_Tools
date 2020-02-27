<?php
namespace exception;

class CustomException Extends \Exception {
	function __construct($message = Null)
	{
		parent::__construct($message);
	}		
}
namespace config;
class config_file_missing Extends \exception\CustomException {
	function __construct($message = "",$create_config_file = false)
	{
		if($create_config_file)
		{
			echo "Config file does not exist.  Would you like to create it?";
			echo "Type 'yes' or 'y' to continue: ";

			$handle = fopen("php://stdin","r"); // read from STDIN
			$line = trim(fgets($handle));

			if($line !== 'yes' && $line !== 'y'){

			}else
			{
			}
		}
		parent::__construct($message);
	}
}
class file_or_folder_does_not_exist Extends \exception\CustomException {
	function __construct($message = "")
	{
		parent::__construct($message);
	}
}

namespace Authentication;
class User_Session_Expired Extends \exception\CustomException{
	function __contruct($message = Null)
	{
		parent::__construct($message);
	}
}
class User_Not_Logged_In Extends \exception\CustomException{
	function __contruct($message = Null)
	{
		parent::__construct($message);
	}
}
class User_Does_Not_Exist Extends \exception\CustomException{
	function __construct($message = Null)
	{
		parent::__construct($message);
	}
}

class Incorrect_Password Extends \exception\CustomException{
	function __construct($message = Null)
	{
		parent::__construct($message);
	}
}
namespace DatabaseLink;
class Table_Does_Not_Exist Extends \exception\CustomException{
	function __construct($message = Null)
	{
		parent::__construct($message);
	}		
}

class Field_Is_Locked Extends \exception\CustomException{
	function __construct($message = Null)
	{
		parent::__construct($message);
	}		
}

class Not_A_Primary_Key Extends \exception\CustomException{
	function __construct($message = Null)
	{
		parent::__construct($message);
	}		
}

class Primary_Key_Auto_Increments Extends \exception\CustomException{
	function __construct($message = Null)
	{
		parent::__construct($message);
	}		
}

class Row_Not_Ready_To_Update Extends \exception\CustomException{
	function __construct($message = Null)
	{
		parent::__construct($message);
	}		
}

class Fields_Are_Not_Set_Properly Extends \exception\CustomException{
	function __construct($message = Null)
	{
		parent::__construct($message);
	}		
}

class Column_Does_Not_Exist Extends \exception\CustomException{
	function __construct($message = Null)
	{
		parent::__construct($message);
	}		
}

class SQL_Search_Returned_Null Extends \exception\CustomException{
	function __construct($message = Null)
	{
		parent::__construct($message);
	}		
}

Class SQLConnectionError Extends \exception\CustomException{
	function __construct($message = Null)
	{
		parent::__construct($message);
	}		
}

Class SQLQueryError Extends \exception\CustomException{
	function __construct($message = Null)
	{
		parent::__construct($message);
	}		
}

Class DuplicatePrimaryKeyRequest Extends \exception\CustomException{
	function __construct($message = Null)
	{
		parent::__construct($message);
	}		
}

namespace Company;
Class CompanyDoesNotExist Extends \exception\CustomException{
	function __construct($message = Null)
	{
		parent::__construct($message);
	}		
}

namespace Active_Record;
Class UpdateFailed Extends \exception\CustomException{
	function __construct($message = Null)
	{
		parent::__construct($message);
	}
}

?>