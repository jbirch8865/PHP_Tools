<?php
namespace exception;

class CustomException Extends \Exception {
	function __construct($message = Null)
	{
		parent::__construct($message);
	}
}
namespace config;

use Test_Tools\Toolbelt;

use function exception\Send_Message_Get_Response;

class config_file_missing Extends \exception\CustomException {
	function __construct($message = "",$create_config_file = false)
	{
		if($create_config_file)
		{
            $toolbelt = new Toolbelt;
			$go_ahead_and_create_file = $toolbelt->functions->Send_Message_To_Stdin_Get_Response("Config file does not exist.  Would you like to create it?");
			if(strtoupper($go_ahead_and_create_file) == "Y" || strtoupper($go_ahead_and_create_file) == "YES")
			{
				$toolbelt->functions->Ask_User_For_Credentials();
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

class Config_Missing Extends \exception\CustomException {
	function __construct($message = "")
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

class Column_Is_Required Extends \exception\CustomException{
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

namespace Active_Record;
Class UpdateFailed Extends \exception\CustomException{
	function __construct($message = Null)
	{
		parent::__construct($message);
	}
}

Class Active_Record_Object_Failed_To_Load Extends \exception\CustomException{
	function __construct($message = Null)
	{
		parent::__construct($message);
	}
}

Class Varchar_Too_Long_To_Set Extends \exception\CustomException{
	function __construct($message = Null)
	{
		parent::__construct($message);
	}
}

Class Object_Is_Already_Loaded Extends \exception\CustomException{
	function __construct($message = Null)
	{
		parent::__construct($message);
	}
}

Class Object_Has_Not_Been_Loaded Extends \exception\CustomException{
	function __construct($message = Null)
	{
		parent::__construct($message);
	}
}

Class Object_Is_Currently_Inactive Extends \exception\CustomException{
	function __construct($message = Null)
	{
		parent::__construct($message);
	}
}

Class Relationship_Miss_Match Extends \exception\CustomException{
    function __construct($message = Null)
    {
        parent::__construct($message);
    }
}

Class Email_Address_Not_Valid Extends \exception\CustomException{
    function __construct($message = Null)
    {
        parent:: __construct($message);
    }
}
Class User_Lacks_Rights Extends \exception\CustomException{
    function __construct($message = Null)
    {
        parent::__construct($message);
    }
}

namespace app\Helpers;

Class User_Not_Set Extends \exception\CustomException{
	function __construct($message = Null)
	{
		parent::__construct($message);
	}
}
Class Session_Not_Established Extends \exception\CustomException{
	function __construct($message = Null)
	{
		parent::__construct($message);
	}
}

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

namespace sms;

class Twilio_Connection_Error Extends \exception\CustomException{
	function __construct($message = Null)
	{
		parent::__construct($message);
	}
}

?>
