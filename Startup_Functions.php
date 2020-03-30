<?php
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
?>