<?php
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
namespace exception;

class CustomException Extends \Exception {
	function __construct($message = Null)
	{
		parent::__construct($message);
	}		
}

namespace docker;

class BadFolderLocation Extends \exception\CustomException {
	function __construct($secret_directory = '/run/secret/', $config_directory = '/')
	{
		parent::__construct("Either the Secret directory ".$secret_directory.", or config directory ".$config_directory." does not exist.  Please verify existance in the container");
	}
}
class SecretDoesNotExist Extends \exception\CustomException{
	function __construct($secret_name)
	{
		parent::__construct("The docker secret ".$secret_name." does not exist, please ensure the docker secret is configured in the docker swarm");
	}
}
class ConfigDoesNotExist Extends \exception\CustomException{
	function __construct($config_name)
	{
		parent::__construct("The docker config ".$config_name." does not exist. Please make sure docker swarm is configured with it");
	}
}

namespace number_validator;
class InvalidPhoneNumber Extends \exception\CustomException {
	function __construct($phone_number)
	{
		parent::__construct("The phone number ".$phone_number." is not valid, please try again.  Make sure it does not start with a + and is no more than 11 digits if using the country code");
	}
}

class Missing_Access_Key Extends \docker\SecretDoesNotExist{
	function __construct()
	{
		parent::__construct('Number_Validator_Access_Key');
	}
}

class Missing_SID_Or_Token Extends \docker\SecretDoesNotExist{
	function __construct($what_is_missing)
	{
		parent::__construct($what_is_missing);
	}
}
class Missing_From_Number Extends \docker\ConfigDoesNotExist {
	function __construct($what_is_missing)
	{
		parent::__construct($what_is_missing);
	}
}
class MessageBodyTooLong Extends \exception\CustomException{
	function __construct($message_body)
	{
		parent::__construct("Message body is more than 160 legal characters - ".$message_body);
	}
}
class MessageNotReadyToSend Extends \exception\CustomException{
	function __construct($message = Null)
	{
		parent::__construct($message);
	}
}
class ThisIsADuplicateMessage Extends \exception\CustomException{
	function __construct()
	{
		parent::__construct("You are trying to send the same message to the same person today.  You can't do this using Send_SMS use Send_Message to bypas this error and send anyway");
	}
}

namespace User_Session;
class User_Session_Expired Extends \exception\CustomException{
	function __construct($message = Null)
	{
		parent::__construct($message);
	}	
}
class User_Already_Exists Extends \exception\CustomException{
	function __construct($message = Null)
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

class User_Is_Not_Authenticated Extends \exception\CustomException{
	function __construct($message = Null)
	{
		parent::__construct($message);
	}	
}

class User_Is_Already_Authenticated Extends \exception\CustomException{
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

namespace logging;
class Log_Does_Not_Exist Extends \exception\CustomException{
	function __construct($message = Null)
	{
		parent::__construct($message);
	}		
}

namespace project_tags;
class Tag_Does_Not_Exist Extends \exception\CustomException{
	function __construct($message = Null)
	{
		parent::__construct($message);
	}		
}


?>