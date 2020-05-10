<?php

use Active_Record\Email_Address_Not_Valid;
use App\Rules\Does_This_Exist_In_Context;

/**
 * @throws Exception if you use a different keyspace it has to be more than two characters long
 */
function Generate_CSPRNG(int $length,string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ+=.?$') : string
{
    $str = '';
    $max = mb_strlen($keyspace, '8bit') - 1;
    if ($max < 1) {
        throw new Exception('$keyspace must be at least two characters long');
    }
    for ($i = 0; $i < $length; ++$i) {
        $str .= $keyspace[random_int(0, $max)];
    }
    return $str;
}
/**
 * @param string $string_to_add example "<-"
 * @param array $array_to_modify array('socks','shoes','pants')
 * @return array array('<-socks<-','<-shoes<-','<-pants<-')
 */
function Wrap_Array_Values_With_String(string $string_to_add,array $array_to_modify) : array
{
    $array = Append_To_Array_Values_With_String($string_to_add,$array_to_modify);
    $array = Prepend_To_Array_Values_With_String($string_to_add,$array);
    return $array;
}
/**
 * @param string $string_to_add example "<-"
 * @param array $array_to_modify array('socks','shoes','pants')
 * @return array array('socks<-','shoes<-','pants<-')
 */
function Append_To_Array_Values_With_String(string $string_to_add,array $array_to_modify) : array
{
    ForEach($array_to_modify as $key => $value)
    {
        $array_to_modify[$key] = $value.$string_to_add;
    }
    return $array_to_modify;
}
/**
 * @param string $string_to_add example "<-"
 * @param array $array_to_modify array('socks','shoes','pants')
 * @return array array('<-socks','<-shoes','<-pants')
 */
function Prepend_To_Array_Values_With_String(string $string_to_add,array $array_to_modify) : array
{
    ForEach($array_to_modify as $key => $value)
    {
        $array_to_modify[$key] = $string_to_add.$value;
    }
    return $array_to_modify;
}
/**
 * @param string $string_to_add example "<-"
 * @param array $array_to_modify array('socks','shoes','pants')
 * @return array array('socks<-','shoes<-','pants')
 */
function Prepend_To_Array_Except_Last_Element_With_String(string $string_to_add,array $array_to_modify) : array
{
    $i = 1;
    ForEach($array_to_modify as $key => $value)
    {
        if($i < count($array_to_modify))
        {
            $array_to_modify[$key] = $string_to_add.$value;
        }
        $i = $i + 1;
    }
    return $array_to_modify;
}
/**
 * @return bool fsockopen("www.google.com",80)
 */
function is_connected() : bool
{
    $connected = @fsockopen("www.google.com", 80);
                                        //website, port  (try 80 or 443)
    if ($connected){
        $is_conn = true; //action when connected
        fclose($connected);
    }else{
        $is_conn = false; //action in connection failure
    }
    return $is_conn;
}
function Array_To_Ini(array $array) : string
{
	$res = array();
	foreach($array as $key => $val)
	{
		if(is_array($val))
		{
			$res[] = "[$key]";
			foreach($val as $skey => $sval) $res[] = "$skey = ".(is_numeric($sval) ? $sval : '"'.$sval.'"');
		}
		else $res[] = "$key = ".(is_numeric($val) ? $val : '"'.$val.'"');
	}
	return implode("\r\n", $res);
}
function Send_Message_To_Stdin_Get_Response(string $message) :string
{
	echo $message;
	$handle = fopen("php://stdin","r"); // read from STDIN
	$line = trim(fgets($handle));
	return $line;
}
function Ask_User_For_Credentials() : void
{
    $root_username = Send_Message_To_Stdin_Get_Response("Database root username?");
	$root_password = Send_Message_To_Stdin_Get_Response("Database root password?");
    $root_hostname = Send_Message_To_Stdin_Get_Response("Database hostname, leave blank for localhost?");
    if ($root_hostname == "")
    {
        $root_hostname = 'localhost';
    }
    $root_listeningport = Send_Message_To_Stdin_Get_Response("Database listeningport, leave blank for 3306?");
    if($root_listeningport == "")
    {
        $root_listeningport = '3306';
    }
	if(mysqli_connect($root_hostname,$root_username,$root_password,'',$root_listeningport))
	{
		Create_Config_File($root_username,$root_password,$root_hostname,$root_listeningport);
	}else
	{
		$connection_failed_try_again = Send_Message_To_Stdin_Get_Response("I tried connecting to the database but failed, would you like to try again?");
		if(strtoupper($connection_failed_try_again) == 'Y' || strtoupper($connection_failed_try_again) == 'Y')
		{
			Ask_User_For_Credentials();
		}else
		{
            return;
		}
	}
}
function Create_Config_File(string $root_username,string $root_password,string $root_hostname,string $root_listeningport) : void
{
    echo 'creating config file and terminating execution';
    $array = array('root_username' => $root_username,'root_password' => $root_password,'root_hostname' => $root_hostname,'root_listeningport' => $root_listeningport);
    $ini_string = Array_To_Ini($array);
    $file_handle = fopen(dirname(__FILE__).DIRECTORY_SEPARATOR.'config.local.ini','w');
    fwrite($file_handle,$ini_string);
    fclose($file_handle);
}
function Validate_Array_Types(array $array,string $objecttype) :void
{
    ForEach($array as $object)
    {
        try
        {
            if(get_class($object) == $objecttype)
            {
                throw new \Exception(get_class($object).' is not a valid '.$objecttype);
            }
        } catch (\Exception $e)
        {
            throw new \Exception(get_class($object).' is not a valid '.$objecttype);
        }
    }
}

function stringEndsWith($haystack,$needle) {
    $expectedPosition = strlen($haystack) - strlen($needle);
    return strrpos($haystack, $needle, 0) === $expectedPosition;
}

function Enable_Disabled_Object(\DatabaseLink\Column $column,\Active_Record\Active_Record $object) : void
{
    $toolbelt = new \Test_Tools\toolbelt;
    if($toolbelt->Get_Route()->Get_Current_Route_Method() == "patch")
    {
        app()->request->validate(['id' => new Does_This_Exist_In_Context($column,true)]);
        $object->Load_Object_By_ID($column->Get_Field_Value(),true);
        $object->Set_Object_Active(true);
    }
}

function Validate_Email(string $email,bool $send_response = true) : void
{
    $pattern = '/^(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){255,})(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){65,}@)(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22))(?:\\.(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-+[a-z0-9]+)*\\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-+[a-z0-9]+)*)|(?:\\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\\]))$/iD';
    if (preg_match($pattern, $email) === 0) {
        if($send_response)
        {
            Response_422(['message' => 'Sorry '.$email.' is not a valid email address'],app()->request)->send();
            exit();
        }else
        {
            throw new Email_Address_Not_Valid('Sorry '.$email.' is not a valid email address');
        }
    }

}


use Illuminate\Http\Request;


/**
 * Get Success
 */
 function Response_200(array $payload,Request $request)
 {
    global $toolbelt_base;
    $toolbelt_base->Null_All();
    return response()->json($payload);
 }
 /**
  * Post/Patch/Put Success
  */
 function Response_201(array $payload,Request $request)
 {
    global $toolbelt_base;
    $toolbelt_base->Null_All();
    return response()->json($payload,201);
 }
 /**
  * What you are asking for I just can't do for you
  */
 function Response_422(array $payload,Request $request)
 {
    global $toolbelt_base;
    $toolbelt_base->Null_All();
    return response()->json($payload,422);
 }
 /**
  * I can't understand your request
  */
 function Response_400(array $payload,Request $request)
 {
    global $toolbelt_base;
    $toolbelt_base->Null_All();
    return response()->json($payload,400);
 }
 /**
  * You are not allowed to do this either for authentication or authorization issues
  */
 function Response_401(array $payload,Request $request)
 {
    global $toolbelt_base;
    $toolbelt_base->Null_All();
    return response()->json($payload,401);
 }
 /**
  * My Bad Sorry I need to fix this
  */
 function Response_500(array $payload,Request $request)
 {
    global $toolbelt_base;
    $toolbelt_base->Null_All();
    return response()->json($payload,500);
 }
?>
