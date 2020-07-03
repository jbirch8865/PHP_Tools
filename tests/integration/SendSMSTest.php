<?php

class SendSMSTest extends \PHPUnit\Framework\TestCase
{
	private $unique_text;
	
	function __construct()
	{
		parent::__construct();
		$this->unique_text = uniqid();
	}
	
	function test_Can_I_Send_An_SMS()
	{   
		$sms = new \sms\TextMessage;
		$phone = '15038287180';
		$sms->Set_Message_Body("Can I send a test - Unique Test #".$this->unique_text);
		$this->assertNull($sms->Set_To_Number($phone));
		//$sms->Send_Message();

	}
	
	function Duplicate_Message_Failed()
	{
		$this->expectException(DuplicatePrimaryKeyRequest::class);
		$sms = new \sms\SMSMessageWithChecks;
		$phone = '15038287180';
		$sms->Set_Message_Body("Can I send a test - Unique Test #".$this->unique_text);
		//$sms->Send_SMS();
	}

}

?>