<?php

class SendSMSTest extends \PHPUnit\Framework\TestCase
{
	function test_Can_I_Send_An_SMS()
	{   
		//$this->expectException(\sms\ThisIsADuplicateMessage::class);  
		$sms = new \sms\SMSMessageWithChecks;
		$phone = new \number_validator\PhoneNumber('15038287180');
		$sms->Set_Message_Body("Can I send a test");
		$this->assertNull($sms->Set_To_Number($phone->Print_Number()));
		$this->assertTrue($sms->Send_SMS());

	}
	

}

?>