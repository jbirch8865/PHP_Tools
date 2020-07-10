<?php
namespace config;

use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version2X;

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
        $version = new Version2X("http://localhost:3001");
        $this->client = new Client($version);        
        $this->client->initialize();
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