<?php
/**
 * @throws Exception if you use a different keyspace it has to be more than two characters long
 */
function Generate_CSPRNG(int $length,string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') : string
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
?>