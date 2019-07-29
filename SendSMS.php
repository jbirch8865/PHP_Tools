<?php
namespace sms;
use Twilio\Rest\Client;
use PHPUnit\Util\Configuration;

class IniConfigError Extends \Exception{}
class MessageBodyTooLong Extends \Exception{}
class MessageNotReadyToSend Extends \Exception{}
class ThisIsADuplicateMessage Extends \Exception{}

class TextMessage {
	private $message_body;
	private $send_to;
	private $sid;
	private $token;
	private $send_from;


	function __construct()
	{
		$this->LoadConfigs();
	}

	private function LoadConfigs()
	{
		try
		{
			$Configs = new \config\ConfigurationFile;
			$this->sid = $Configs->Configurations()['Twilio_SID'];
			$this->token = $Configs->Configurations()['Twilio_Token'];
			$this->send_from = $Configs->Configurations()['Twilio_From_Number'];
		} catch (\Exception $e)
		{
			throw new \Exception($e->getMessage());
		}
	}

	public function Set_To_Number($send_to)
	{
		try {
			$this->send_to = new \number_validator\PhoneNumber($send_to);
		} catch (\Exception $e)
		{
			throw new \Exception($e->getMessage());
		}
	}

	public function Set_Message_Body($message_body)
	{
		try {
			$this->message_body = $message_body;
			$sms_string_analyzer = new \Instasent\SMSCounter\SMSCounter();
			$analyze_results = $sms_string_analyzer->count($message_body);
			if($analyze_results->messages > 1){throw new MessageBodyTooLong("This message is too long to send");}
		} catch (MessageBodyTooLong $e)
		{
			throw new MessageBodyTooLong($this->message_body);
		} catch (\Exception $e)
		{
			throw new \Exception($e->getMessage());
		}
	}

	public function Send_Message()
	{
		if($this->Is_Message_Ready_To_Send())
		{
			try {
				$twilio = $this->Twilio_Client_Object();
				$message = $twilio->messages->create($this->send_to->Print_Number(),array("body" => $this->message_body,"from" => $this->send_from));
			} catch (\Exception $e)
			{
				throw new \Exception($e->getMessage());
			}
		}else{
			throw new MessageNotReadyToSend("This message is either missing a body or to number. to number -".$this->send_to->Print_Number().". Message Body - ".$this->message_body);
		}
	}

	public function Twilio_Client_Object()
	{
		try {
			$twilio = new Client($this->sid, $this->token);
			return $twilio;
		} catch (\Exception $e)
		{
			throw new \Exception($e->getMessage());
		}
	}

	public function Is_Message_Ready_To_Send()
	{
		if($this->send_to && $this->message_body)
		{
			return true;
		} else
		{
			return false;
		}
	}

	public function Print_Send_To()
	{
		return $this->send_to->Print_Number();
	}

	public function Print_Message_Body()
	{
		return $this->message_body;
	}
}

class SMSMessageWithChecks extends TextMessage
{
	function __construct()
	{
	        parent::__construct();
	}

	private function Is_This_A_Duplicate_Message()
	{
		try {
			$twilio = $this->Twilio_Client_Object();

			$today = new \DateTime(date('Y-m-d'));
			$messages = $twilio->messages->read(array("dateSent" => $today,"to" => $this->Print_Send_To()),20);
			foreach($messages as $record)
			{
				if($record->body == $this->Print_Message_Body())
				{
					return true;
				}
			}
			return false;
		} catch (\Exception $e)
		{
			throw new \Exception($e->getMessage());
		}
	}

	public function Send_SMS()
	{
		if($this->Is_This_A_Duplicate_Message())
		{
			throw new ThisIsADuplicateMessage("This message was already sent today");
		}else
		{
			$this->Send_Message();
		}
	}
}

/*
	sample of how to use this class
	try
	{
		$sms = new SMSMessageWithChecks();
		$sms->Set_To_Number("1".$_GET['phone']);
		$sms->Set_Message_Body($_GET['body']);
		$sms->Send_SMS();
		echo 'Message Sent';
	} catch (ThisIsADuplicateMessage $e)
	{
		echo 'Sorry this message was already sent today';
	} catch (\Exception $e)
	{
		echo 'Unknown Error';
	}
*/
?>
