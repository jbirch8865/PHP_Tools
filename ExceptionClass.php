<?php
//We don't need a namespace as each class we should be using the long name which would include any namespaces
class \docker\BadFolderLocation Extends \Exception{
	function __construct($secret_directory = '/run/secret/', $config_directory = '/')
	{
		parent::__construct("Either the Secret directory ".$secret_directory.", or config directory ".$config_directory." does not exist.  Please verify existance in the container");
	}
}
class \docker\SecretDoesNotExist Extends \Exception{
	function __construct($secret_name)
	{
		parent::__construct("The docker secret ".$secret_name." does not exist, please ensure the docker secret is configured in the docker swarm");
	}
}
class \docker\ConfigDoesNotExist Extends \Exception{
	function __construct($config_name)
	{
		parent::__construct("The docker config ".$config_name." does not exist. Please make sure docker swarm is configured with it");
	}
}

class \number_validator\InvalidPhoneNumber Extends \Exception {
	function __construct($phone_number)
	{
		parent::__construct("The phone number ".$phone_number." is not valid, please try again.  Make sure it does not start with a + and is no more than 11 digits if using the country code");
	}
}

class \number_validator\Missing_Access_Key Extends \docker\SecretDoesNotExist{
	function __construct()
	{
		parent::__construct('Number_Validator_Access_Key');
	}
}

class \sms\Missing_SID_Or_Token Extends \docker\SecretDoesNotExist{
	function __construct($what_is_missing)
	{
		parent::__construct($what_is_missing);
	}
}
class \sms\Missing_From_Number Extends \docker\ConfigDoesNotExist {
	function __construct($what_is_missing)
	{
		parent::__construct($what_is_missing);
	}
}
class \sms\MessageBodyTooLong Extends \Exception{
	function __construct($message_body)
	{
		parent::__construct("Message body is more than 160 legal characters - ".$message_body);
	}
}
class \sms\MessageNotReadyToSend Extends \Exception{
	function __construct($message = Null)
	{
		parent::__construct($message);
	}
}
class \sms\ThisIsADuplicateMessage Extends \Exception{
	function __construct()
	{
		parent::__construct("You are trying to send the same message to the same person today.  You can't do this using Send_SMS use Send_Message to bypas this error and send anyway");
	}
}

?>
