<?php
namespace number_validator;

class PhoneNumber {
	private $phone_number;
	private $access_key;

	function __construct($phone_number)
	{
		try {
			$this->phone_number = $phone_number;
			$this->Load_Validate_Keys();
			$this->ValidatePhoneNumber();
		} catch (InvalidPhoneNumber $e)
		{
			throw new InvalidPhoneNumber($this->phone_number);
		}
	}

	public function ValidatePhoneNumber()
	{
		try {
			$submit_request = curl_init("http://apilayer.net/api/validate?access_key=".$this->access_key."&number=".$this->phone_number);
			curl_setopt($submit_request, CURLOPT_RETURNTRANSFER, true);
			$json_results = curl_exec($submit_request);
			curl_close($submit_request);

			$validation_results = json_decode($json_results, true);
			if(!$validation_results['valid']) {
				throw new InvalidPhoneNumber();
			}
		} catch (InvalidPhoneNumber $e) {
			throw new InvalidPhoneNumber();
		} catch (\Exception $e)
		{
			throw new \Exception($e->getMessage());
		}
	}

	private function Load_Validate_Keys()
	{
                try
                {
                       	$Access_Key = new \docker\Docker;
                        $this->access_key = $Access_Key->Get_Secret_Value('Number_Validator_Access_Key');
                } catch (\docker\SecretDoesNotExist $e)
                {
                        throw new Missing_Access_Key('Number_Validator_Access_Key');
                } catch (\Exception $e)
		{
			throw new \Exception($e->getMessage());
		}
	}

	public function Print_Number()
	{
		return $this->phone_number;
	}
}
?>
