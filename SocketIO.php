<?php
namespace config;

use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version2X;


class SocketIO extends SocketIOParent
{
    function __construct($action = false, $message = [])
    {
        if(is_null($this->client))
        {
            parent::__construct($action, $message);
        }
    }
}

abstract class SocketIOParent
{
    protected $client = null;
    function __construct($action = false, $message = [])
    {
        $cConfigs = new ConfigurationFile();
        if($cConfigs->Is_Dev())
        {
            $version = new Version2X("http://localhost:3001",[]);
        }else
        {
            $version = new Version2X("http://localhost:3001",[]);
        }
        $this->client = new Client($version);
        try{
            $this->client->initialize();
            if($action)
            {
                $this->emit($action, $message);
            }
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