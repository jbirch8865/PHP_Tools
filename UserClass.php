<?php
namespace User_Session;

class User_Session 
{
    private $username;
    private $password;
    private $hashed_password_given;
    private $dblink;
    private $configs;
    private $salt;
    private $is_user_authenticated;
    private $session_expires;

    function __construct()
    {
        $this->LoadConfigurationFile();
        $this->dblink = new \DatabaseLink\MySQLLink($this->configs['database_name']);
        $this->username = "";
        $this->password = "";
        $this->hashed_password_given = "";    
        $this->session_expires = date('Y-m-d H:i:s');
    }

    function Set_Username($username)
    {
        $this->username = $username;
        if($this->Does_User_Exist())
        {
            $this->Get_Salt_From_DB();
        }
    }

    function Set_Password($password)
    {
        $this->password = $password;
        $this->Hash_Password_Given();
    }

    function Authenticate_User()
    {
        if($this->Am_I_Currently_Authenticated())
        {
            return true;
        }
        if($this->username == "" || $this->password == "")
        {
            throw new User_Is_Not_Authenticated("You can't authenticate a user until you set the username and password");
        }
        if(!$this->Does_User_Exist())
        {
            throw new User_Does_Not_Exist("You can't authenticate a user that doesn't exist");
        }
        if($this->hashed_password_given == $this->Get_Hashed_Password_From_DB())
        {
            $this->is_user_authenticated = true;
        }else
        {
            $this->is_user_authenticated = false;
            throw new User_Is_Not_Authenticated("password given is incorrect");
        }
        $this->session_expires = date('Y-m-d H:i:s',strtotime('+'.$this->configs['Session_Time_Limit_In_Minutes'].' minutes'));
        
        $this->password = "";
        $_SESSION["User_Session"] = $this;        
        return $this->is_user_authenticated;
    }
    /**
     * 
     */
    function Create_User()
    {
        if($this->username == "" || $this->password == "")
        {
            throw new \Exception("you can't create a user without setting the username and password");
        }
        if($this->Does_User_Exist())
        {
            throw new \Exception("this user has already been created");
        }

        try
        {
            if($results = $this->dblink->ExecuteSQLQuery("INSERT INTO ".$this->configs['user_table_name']." SET ".$this->configs['username_column_name']." = '".$this->username."', ".$this->configs['password_column_name']." = '".$this->hashed_password_given."', ".$this->configs['cspring_column_name']." = '".$this->salt."'"))
            {
                return true;    
            }else
            {
                throw new \Exception('Error querying creating user '.$this->dblink->GetLastError());
            }
        } catch (\Exception $e)
        {
            throw new \Exception($e->getMessage());
        }       
    }

    /**
     *  Uses username to delete the unique record.
     */
    function Delete_User()
    {
        if($this->username == "")
        {
            throw new \Exception("you can't Delete a user without setting the username");
        }
        if(!$this->Does_User_Exist())
        {
            throw new \Exception("this user does not exist");
        }

        try
        {
            if($results = $this->dblink->ExecuteSQLQuery("DELETE FROM ".$this->configs['user_table_name']." WHERE ".$this->configs['username_column_name']." = '".$this->username."'"))
            {
                return true;    
            }else
            {
                throw new \Exception('Error deleting user '.$this->dblink->GetLastError());
            }
        } catch (\Exception $e)
        {
            throw new \Exception($e->getMessage());
        }       
    }
    
    function LogOut()
    {
        $this->is_user_authenticated = false;
        $this->password = "";
        $this->session_expires = date('Y-m-d H:i:s');
        $_SESSION['User_Session'] = $this;
    }
    
    private function Am_I_Currently_Authenticated()
    {
        if($this->is_user_authenticated)
        {
            if(Date('Y-m-d H:i:s') < $this->session_expires)
            {
                $this->Renew_Session();
                return true;
            }else
            {
                return false;
            }
        }else
        {
            return false;
        }
    }

    private function Get_Username()
    {
        return $this->username;
    }
    private function Get_Password()
    {
        return $this->password;
    }
    private function Get_Hashed_Password()
    {
        return $this->hashed_password_given;
    }


    private function Renew_Session()
    {
        $this->session_expires = date('Y-m-d H:i:s',strtotime('+'.$this->configs['Session_Time_Limit_In_Minutes'].' minutes'));
        $this->password = ""; //Should already be blank but just in case
        $_SESSION["User_Session"] = $this;  
    }

    private function Does_User_Exist()
    {
        try
        {
            $results = $this->dblink->ExecuteSQLQuery("SELECT * FROM ".$this->configs['user_table_name']." WHERE ".$this->configs['username_column_name']." = '".$this->username."'");
            if(mysqli_num_rows($results) == 1)
            {
                return true;
            }else
            {
                return false;
            }
        } catch (\Exception $e)
        {
            throw new \Exception($e->getMessage());
        }
    }

    private function Get_Salt_From_DB()
    {
        try
        {
            $results = $this->dblink->ExecuteSQLQuery("SELECT `cspring` FROM ".$this->configs['user_table_name']." WHERE ".$this->configs['username_column_name']." = '".$this->username."'");
            if(mysqli_num_rows($results) == 1)
            {
                $salt = mysqli_fetch_assoc($results);
                $salt = $salt['cspring'];
                return $salt;
            }else
            {
                throw new \Exception('Error querying user salt '.$this->dblink->GetLastError());
            }
        } catch (\Exception $e)
        {
            throw new \Exception($e->getMessage());
        }
    }

    private function Get_A_Valid_Salt()
    {
        if($this->Does_User_Exist())
        {
            return $this->Get_Salt_From_DB();
        }else
        {
            return $this->Create_Salt();
        }

    }

    private function Create_Salt()
    {
        if($this->Does_User_Exist())
        {
            throw new \Exception("This user already has a salt created");
        }
        return bin2hex(random_bytes(32));
    }

    /**
     * This will hash the password that was set externally while using this class
     */
    private function Hash_Password_Given()
    {
        if($this->password == "")
        {
            throw new \Exception("You can't hash until you add a password");
        }
        $this->salt = $this->Get_A_Valid_Salt();
        $PasswordHash = $this->password.$this->salt;
        $this->hashed_password_given = hash('sha256', $PasswordHash);
        return $this->hashed_password_given;
    }

    /**
     * If the user has already been created in the past this will get the original hashed password
     * so we can compare the hashed_password_given with this password for authentication
     */
    private function Get_Hashed_Password_From_DB()
    {
        try
        {
            $results = $this->dblink->ExecuteSQLQuery("SELECT `password` FROM ".$this->configs['user_table_name']." WHERE ".$this->configs['username_column_name']." = '".$this->username."'");
            if(mysqli_num_rows($results) == 1)
            {
                $password = mysqli_fetch_assoc($results);
                $password = $password['password'];
                return $password;
            }else
            {
                throw new \Exception('Error querying password '.$this->dblink->GetLastError());
            }
        } catch (\Exception $e)
        {
            throw new \Exception($e->getMessage());
        }        
    }

    private function LoadConfigurationFile()
	{
		try 
		{
            $configs = new \Config\ConfigurationFile;
            if($this->Do_Configs_Exist($configs->Configurations()))
            {
                $this->configs = $configs->Configurations();
            }else
            {
                throw new \Exception("Configs are invalid");
            }
		} catch (\Exception $e)
		{
			throw new \Exception($e->getMessage());
		}
	}

    private function Do_Configs_Exist($configs)
    {
        if(!isset($configs['database_name'])||!isset($configs['user_table_name']))
        {
            return false;
        }else
        {
            return true;
        }
    }
    
}

class Current_User
{
    private $user_session;

    function __construct()
    {
        if($this->Does_User_Session_Exist())
        {
            $this->user_session = $_SESSION['User_Session'];
        }else
        {
            $this->user_session = new User_Session;   
        }
    }

    private function Does_User_Session_Exist()
    {
        if(isset($_SESSION['User_Session']))
        {
            return true;
        }else
        {
            return false;
        }
    }

    function Authenticate()
    {
        return $this->user_session->Authenticate_User();
    }

    function LogOut()
    {
        $this->user_session->LogOut();
    }

    function Set_Password($password)
    {
        $this->user_session->Set_Password($password);
    }

    function Set_Username($username)
    {
        $this->user_session->Set_Username($username);
    }
}
?>