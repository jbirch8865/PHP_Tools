<?php

class NumberValidatorTest extends \PHPUnit\Framework\TestCase
{
	private $Number;
	function test_Can_I_Validate_Number()
	{     
		$this->Number = new \number_validator\PhoneNumber('15038287180');
		$this->assertInstanceOf(\number_validator\PhoneNumber::class, $this->Number);
	}
	

}

?>