<?php
namespace User_Session;

class User_Session 
{
    private $username;
    private $password;

    function __construct()
    {
        $this->Set_Username("");
        $this->Set_Password("");    
    }

    function Set_Username($username)
    {
        $this->username = $username;
    }

    function Set_Password($password)
    {
        $this->password = $password;
    }

    function Is_User_Authorized()
    {

    }
}
?>