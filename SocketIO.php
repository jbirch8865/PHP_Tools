<?php
namespace config;

use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version2X;


class EmitSocketMessage
{
    private $curl;
    function __construct($message)
    {
        $messages = [
            "updateBizPref"
        ];
        if(!in_array($message,$messages))
        {
            throw new \Exception('not a valid message to emit');
        }
        $this->curl = curl_init($_SERVER['SERVER_NAME'].'/scripts/react/emit_event.php?event='.$message);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $response = curl_exec($ch);
        curl_close($ch);
    }
}

class SocketIO extends SocketIOParent
{
    function __construct()
    {
        if(is_null($this->client))
        {
            parent::__construct();
        }
    }
}

abstract class SocketIOParent
{
    protected $client = null;
    function __construct()
    {
        $cConfigs = new ConfigurationFile();
        if($cConfigs->Is_Dev())
        {
            $version = new Version2X("http://localhost:3001",[]);
        }else
        {
            $version = new Version2X("http://dandh.dsfellowship.com:3001",[]);
        }
        $this->client = new Client($version);
        try{
            $this->client->initialize();
        } catch(\Exception $e)
        {
            $var = $e->getMessage();
            echo '';
        }
    }

    function __destruct()
    {
        $this->client->close();        
    }
    
    function emit($action, $data = [])
    {
        $this->client->emit($action,$data);
    }    
}