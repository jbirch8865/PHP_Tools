<?php
namespace sms;

use app\Helpers\Phone_Number;
use Test_Tools\toolbelt;
use Twilio\Exceptions\ConfigurationException;
use Twilio\Exceptions\TwilioException;
use Twilio\Http\Client as HttpClient;
use Twilio\Rest\Client;
class IniConfigError Extends \Exception{}
class MessageBodyTooLong Extends \Exception{}
class MessageNotReadyToSend Extends \Exception{}
class ThisIsADuplicateMessage Extends \Exception{}

class twilio_number extends twilio
{
    private Phone_Number $phone_number;
    /**
     * @throws \Active_Record\Object_Has_Not_Been_Loaded
     */
    function __construct(Phone_Number $send_to)
	{
        $this->phone_number = $send_to;
        $this->phone_number->Get_Verified_ID(); //thow not loaded exception
        parent::__construct();
	}
	private function Query_Twilio(bool $update_now) : void
	{
        $response = $this->Get_Client()->lookups->v1->phoneNumbers($this->phone_number->Get_Friendly_Name(true))
        ->fetch(["type" => ["carrier"]]);
        $this->phone_number->Set_Phone_Number_Carrier($response->carrier['name'],false);
        $this->phone_number->Set_Phone_Number_Type($response->carrier['type'],$update_now);
    }
    private function Query_Twilio_If_Needed(bool $update_now = true) : void
    {
        if($this->phone_number->Get_Phone_Number_Carrier() == "")
        {
            $this->Query_Twilio($update_now);
            if($this->phone_number->Get_Phone_Number_Carrier() == "")
            {
                $this->phone_number->Set_Phone_Number_Type("NA",$update_now);
                $this->phone_number->Set_Phone_Number_Carrier("NA",$update_now);
            }
        }
    }
    public function Ensure_Carrier_And_Type_Exist_Or_Mark_NA(bool $update_now = true) : void
    {
        $this->Query_Twilio_If_Needed($update_now);
    }
}

abstract class  twilio {
    public toolbelt $toolbelt;
    private Client $twilio_client;

	function __construct()
	{
        $this->toolbelt = new toolbelt;
		$this->LoadConfigs();
	}

	private function LoadConfigs() : void
	{
        $sid = $this->toolbelt->cConfigs->Get_Twilio_SID();
        $token = $this->toolbelt->cConfigs->Get_Twilio_Token();
        try
        {
            $this->twilio_client = new Client($sid, $token);
        } catch (ConfigurationException $e)
        {
            throw new Twilio_Connection_Error('could not form a client request using the given sid and token');
        }
    }
    public function Set_Token(string $new_token) : void
    {
        $this->toolbelt->cConfigs->Set_Twilio_Token($new_token);
    }

    public function Get_Client() : Client
    {
        return $this->twilio_client;
    }


}
?>
