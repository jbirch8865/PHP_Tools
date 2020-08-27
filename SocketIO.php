<?php

namespace config;

use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version2X;


class SocketIO extends SocketIOParent
{
    function __construct($Object_Being_Updated)
    {
        parent::__construct();
        try {
            $class = get_class($Object_Being_Updated);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
        switch ($class) {
            case \company_program\Equipment_Need::class:
                if (Is_This_A_Dispatching_Shift($Object_Being_Updated->shift)) {
                    $this->Send_Message("updateDispatchNumbers");
                    $this->Send_Message("updateDispatchWizard");
                    $this->Send_Message("updateJobSearch");
                }
                break;
            case \company_program\Shift::class:
                if (Is_This_A_Dispatching_Shift($Object_Being_Updated)) {
                    $this->Send_Message("updateDispatchNumbers");
                    $this->Send_Message("updateDispatchWizard");
                    $this->Send_Message("updateJobSearch");
                }
                break;
            case \company_program\Need::class:
                if (Is_This_A_Dispatching_Shift($Object_Being_Updated->shift)) {
                    $this->Send_Message("updateDispatchNumbers");
                    $this->Send_Message("updateDispatchWizard");
                    $this->Send_Message("updateJobSearch");
                }
                break;
            case \config\ConfigurationFile::class:
                $this->Send_Message("updateBizPref");
                break;
            case \company_program\Current_User::class:
                $this->Send_Message("updateUserPref");
                break;
            default:
                break;
        }
    }

    private function Send_Message($action, $message = [])
    {
        parent::emit($action, $message);
    }
}

abstract class SocketIOParent
{
    protected $client = null;
    function __construct($action = false, $message = [])
    {
        $cConfigs = new ConfigurationFile();
        if ($cConfigs->Is_Dev()) {
            $version = new Version2X("http://dandh.dsfellowship.com:3001", []);
        } else {
            $version = new Version2X("http://dandh.dsfellowship.com:3001", []);
        }
        if (is_null($this->client)) {
            $this->client = new Client($version);
        }
        try {
            $this->client->initialize();
            if ($action) {
                $this->emit($action, $message);
            }
        } catch (\Exception $e) {
            $var = $e->getMessage();
        }
    }

    function __destruct()
    {
        $this->client->close();
    }

    function emit($action, $data = [])
    {
        $this->client->emit($action, $data);
    }
}
