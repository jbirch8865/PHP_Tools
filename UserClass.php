<?php

namespace User_Session;

use config\SocketIO;

use function GuzzleHttp\json_encode;

class User_Session
{
    private $person_id;
    private $username;
    private $password;
    private $hashed_password_given;
    private $dblink;
    private $configs;
    private $salt;
    private $is_user_authenticated;
    private $session_expires;
    private $number_of_authentication_attempts;

    function __construct()
    {
        $this->LoadConfigurationFile();
        $this->Create_Database_Connection();
        $this->username = "";
        $this->password = "";
        $this->person_id = "";
        $this->number_of_authentication_attempts = 0;
        $this->hashed_password_given = "";
        $this->session_expires = date('Y-m-d H:i:s');
    }

    function Set_Username($username)
    {
        $this->username = str_replace(" ", "_", $username);
        if ($this->Does_User_Exist()) {
            $this->Set_User_ID_From_Username();
            $this->Get_Salt_From_DB();
            return true;
        } else {
            return false;
        }
    }

    function Set_Password($password)
    {
        if ($this->Get_Username() == "") {
            throw new User_Does_Not_Exist("You need to set a username before you configure a password");
        }
        $this->password = $password;
        $this->Hash_Password_Given();
    }

    function Set_User_ID($id)
    {
        $this->person_id = $id;
    }

    function Is_Auth0_Logged_In()
    {
        $auth0 = Auth0_Client();
        $auth0_Manage = Auth0_Management();
        $userInfo = $auth0->getUser();
        if (!$userInfo) {
            return false;
        } else {
            $results = $auth0_Manage->users()->getAll(["q" => $userInfo['email']]);
            $username = $results[0]['user_metadata']['user_name'];
            $this->Set_Username($username);
            return true;
        }
    }

    function Auth0_Login()
    {
        $_SESSION['auth0_redirect'] = $_SERVER['REQUEST_URI'];
        $auth0 = Auth0_Client();
        $auth0->login();
    }

    function Auth0_Logout()
    {
        $this->dblink->ExecuteSQLQuery("UPDATE `Users` SET `auth0_session_exp` = '" . date('Y-m-d') . "' WHERE `person_id` ='" . $this->Get_User_ID() . "'");
    }

    function Authenticate_User()
    {
        $this->number_of_authentication_attempts = $this->number_of_authentication_attempts + 1;
        if ($this->Am_I_Currently_Authenticated()) {
            return true;
        }
        if ($this->username == "" || $this->password == "") {
            throw new User_Is_Not_Authenticated("You can't authenticate a user until you set the username and password");
        }
        $this->password = "";  //This is just to be safe and get rid of plain text passwords asap
        if (!$this->Does_User_Exist()) {
            throw new User_Does_Not_Exist("You can't authenticate a user that doesn't exist");
        }
        if ($this->hashed_password_given == $this->Get_Hashed_Password_From_DB()) {
            $this->Create_API_Token();
            $this->is_user_authenticated = true;
        } else {
            $this->is_user_authenticated = false;
            throw new User_Is_Not_Authenticated("password given is incorrect");
        }
        $this->session_expires = date('Y-m-d H:i:s', strtotime('+' . $this->configs['Session_Time_Limit_In_Minutes'] . ' minutes'));

        return $this->is_user_authenticated;
    }
    /**
     * 
     */
    function Create_User()
    {
        if ($this->username == "" || $this->password == "") {
            throw new \Exception("you can't create a user without setting the username and password");
        }
        try {
            if ($results = $this->dblink->ExecuteSQLQuery("INSERT INTO " . $this->configs['user_table_name'] . " SET " . $this->configs['username_column_name'] . " = '" . $this->username . "', " . $this->configs['password_column_name'] . " = '" . $this->hashed_password_given . "', " . $this->configs['cspring_column_name'] . " = '" . $this->salt . "' ON DUPLICATE KEY UPDATE `Active_Status` = '1', `" . $this->configs['password_column_name'] . "` = '" . $this->hashed_password_given . "', `" . $this->configs['cspring_column_name'] . "` = '" . $this->salt . "'")) {
                $this->Set_User_ID($this->dblink->GetLastInsertID());
                return true;
            } else {
                throw new \Exception('Error querying creating user ' . $this->dblink->GetLastError());
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     *  Uses username to delete the unique record.
     */
    function Delete_User()
    {
        if ($this->username == "") {
            throw new \Exception("you can't Delete a user without setting the username");
        }
        if (!$this->Does_User_Exist()) {
            throw new \Exception("this user does not exist");
        }

        try {
            if ($results = $this->dblink->ExecuteSQLQuery("UPDATE " . $this->configs['user_table_name'] . " SET `Active_Status` = '0' WHERE " . $this->configs['username_column_name'] . " = '" . $this->username . "'")) {
                return true;
            } else {
                throw new \Exception('Error deleting user ' . $this->dblink->GetLastError());
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     *  Uses username to delete the unique record.
     */
    private function Delete_User_From_DB()
    {
        if ($this->username == "") {
            throw new \Exception("you can't Delete a user without setting the username");
        }
        if (!$this->Does_User_Exist()) {
            throw new \Exception("this user does not exist");
        }

        try {
            if ($results = $this->dblink->ExecuteSQLQuery("DELETE FROM " . $this->configs['user_table_name'] . " WHERE " . $this->configs['username_column_name'] . " = '" . $this->username . "'")) {
                return true;
            } else {
                throw new \Exception('Error deleting user ' . $this->dblink->GetLastError());
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    function LogOut()
    {
        $this->is_user_authenticated = false;
        $this->password = "";
        $this->session_expires = date('Y-m-d H:i:s');
        try {
            session_destroy();
            session_start();
            $_SESSION['Add_Info'] = array();
            $_SESSION['Add_Warning'] = array();
        } catch (\Exception $e) {
        }
    }

    /**
     * WILL DO THE AUTHENTICATION FOR API REQUESTS. USERNAME WILL BE CHANGED
     */
    public function Am_I_Currently_Authenticated($throw_exception = false, $auto_renew = true)
    {
        if (function_exists('apache_request_headers') && isset(apache_request_headers()['Authorization']) && !isset($_GET['no_auth'])) {
            $results = $this->dblink->ExecuteSQLQuery("SELECT * FROM `Users` WHERE `current_session_token` = '" . apache_request_headers()['Authorization'] . "' AND `auth0_session_exp` > '" . date('Y-m-d H:i') . "'");
            if (mysqli_num_rows($results)) {
                $this->is_user_authenticate = true;
                $results = mysqli_fetch_assoc($results);
                $this->Set_Username($results['username']);
                return true;
            } else {
                return false;
            }
        } else {
            if ($this->is_user_authenticated) {
                if (!$this->Is_Expired($throw_exception)) {
                    if (!strpos($_SERVER['SCRIPT_NAME'], "ajax_return_scripts")) {
                        echo '<script>localStorage.setItem("hash","' . $this->Get_API_Token() . '")</script>';
                    }
                    if ($auto_renew) {
                        $this->Renew_Session();
                    }
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }

    private function Is_Expired($throw_exception)
    {
        if (Date('Y-m-d H:i:s') > $this->session_expires) {
            if ($throw_exception) {
                throw new User_Session_Expired("The authentication has expired");
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * Have I tried more than once to authenticate and I'm currently not authenticated
     */
    function Have_I_Failed_At_Authenticating_This_Session()
    {
        if ($this->number_of_authentication_attempts > 0 && !$this->Am_I_Currently_Authenticated()) {
            return true;
        } else {
            return false;
        }
    }

    public function Get_Username()
    {
        return $this->username;
    }
    public function Get_User_ID()
    {
        return $this->person_id;
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
        $this->session_expires = date('Y-m-d H:i:s', strtotime('+' . $this->configs['Session_Time_Limit_In_Minutes'] . ' minutes'));
        $this->password = ""; //Should already be blank but just in case
        $_SESSION["User_Session"] = $this;
    }

    private function Set_User_ID_From_Username()
    {
        if ($results = $this->Query_DB_For_User()) {
            $results = mysqli_fetch_assoc($results);
            $this->Set_User_ID($results['person_id']);
        } else {
            return false;
        }
    }

    private function Does_User_Exist()
    {
        if ($results = $this->Query_DB_For_User()) {
            return true;
        } else {
            return false;
        }
    }

    private function Query_DB_For_User()
    {
        try {
            $results = $this->dblink->ExecuteSQLQuery("SELECT * FROM " . $this->configs['user_table_name'] . " WHERE " . $this->configs['username_column_name'] . " = '" . $this->username . "' AND `Active_Status` = '1'");
            if (mysqli_num_rows($results) == 1) {
                return $results;
            } else {
                return false;
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    private function Get_Salt_From_DB()
    {
        try {
            $results = $this->dblink->ExecuteSQLQuery("SELECT `cspring` FROM " . $this->configs['user_table_name'] . " WHERE " . $this->configs['username_column_name'] . " = '" . $this->username . "'");
            if (mysqli_num_rows($results) == 1) {
                $salt = mysqli_fetch_assoc($results);
                $salt = $salt['cspring'];
                return $salt;
            } else {
                throw new \Exception('Error querying user salt ' . $this->dblink->GetLastError());
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    private function Get_A_Valid_Salt()
    {
        if ($this->Does_User_Exist()) {
            return $this->Get_Salt_From_DB();
        } else {
            return $this->Create_Salt();
        }
    }

    private function Create_Salt()
    {
        if ($this->Does_User_Exist()) {
            throw new \Exception("This user already has a salt created");
        }
        return bin2hex(random_bytes(32));
    }

    /**
     * This will hash the password that was set externally while using this class
     */
    private function Hash_Password_Given()
    {
        if ($this->password == "") {
            throw new \Exception("You can't hash until you add a password");
        }
        return $this->Hash_Password($this->password);
    }

    private function Hash_Password($password)
    {
        $this->salt = $this->Get_A_Valid_Salt();
        $PasswordHash = $password . $this->salt;
        $this->hashed_password_given = hash('sha256', $PasswordHash);
        return $this->hashed_password_given;
    }

    public function Change_Password($password)
    {
        $this->Hash_Password($password);
        try {
            if ($results = $this->dblink->ExecuteSQLQuery("UPDATE " . $this->configs['user_table_name'] . " SET " . $this->configs['password_column_name'] . " = '" . $this->hashed_password_given . "' WHERE " . $this->configs['username_column_name'] . " = '" . $this->username . "'")) {
                return true;
            } else {
                throw new \Exception('Error updating user password' . $this->dblink->GetLastError());
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
    /**
     * If the user has already been created in the past this will get the original hashed password
     * so we can compare the hashed_password_given with this password for authentication
     */
    private function Get_Hashed_Password_From_DB()
    {
        try {
            $results = $this->dblink->ExecuteSQLQuery("SELECT `password` FROM " . $this->configs['user_table_name'] . " WHERE " . $this->configs['username_column_name'] . " = '" . $this->username . "'");
            if (mysqli_num_rows($results) == 1) {
                $password = mysqli_fetch_assoc($results);
                $password = $password['password'];
                return $password;
            } else {
                throw new \Exception('Error querying password ' . $this->dblink->GetLastError());
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    private function LoadConfigurationFile()
    {
        try {
            $configs = new \Config\ConfigurationFile;
            if ($this->Do_Configs_Exist($configs->Configurations())) {
                $this->configs = $configs->Configurations();
            } else {
                throw new \Exception("Configs are invalid");
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    private function Do_Configs_Exist($configs)
    {
        if (!isset($configs['database_name']) || !isset($configs['user_table_name'])) {
            return false;
        } else {
            return true;
        }
    }

    public function Create_Database_Connection()
    {
        $this->dblink = new \DatabaseLink\MySQLLink($this->configs['database_name']);
    }

    public function Get_DBLink()
    {
        return $this->dblink;
    }

    public function Currently_Default_Password()
    {
        if ($this->Am_I_Currently_Authenticated() && $this->Get_Hashed_Password() == $this->Hash_Password($this->configs['default_password'])) {
            return true;
        } else {
            return false;
        }
    }

    public function Create_API_Token()
    {
        if (isset($_SERVER['HTTP_HOST'])) {
            $token      = $_SERVER['HTTP_HOST'];
            $token     .= $_SERVER['REQUEST_URI'];
            $token     .= uniqid(rand(), true);
            $hash        = strtoupper(md5($token));
            $hash = substr($hash,  0,  8) .
                substr($hash,  8,  4) .
                substr($hash, 12,  4) .
                substr($hash, 16,  4) .
                substr($hash, 20, 12);
            $cConfigs = new \config\ConfigurationFile();
            $this->dblink->ExecuteSQLQuery("UPDATE `Users` SET `current_session_token` = '" . $hash . "', `auth0_session_exp` = '" . date('Y-m-d H:i', strtotime('+10 hours')) . "' WHERE `username` = '" . $this->Get_Username() . "'");
            return $hash;
        }
        return false;
    }

    public function Get_API_Token()
    {
        $results = $this->dblink->ExecuteSQLQuery("SELECT `current_session_token` FROM `Users` WHERE `username` = '" . $this->Get_Username() . "'");
        if (mysqli_num_rows($results)) {
            $results = mysqli_fetch_assoc($results);
            $results = $results['current_session_token'];
        } else {
            $results = false;
        }
        return $results;
    }
}

class Current_User
{
    private $user_session;
    function __construct()
    {
        if ($this->Does_User_Session_Exist() && !isset($_SERVER['HTTP_AUTHORIZATION']) && basename($_SERVER['SCRIPT_NAME']) != 'api_login.php') {
            $this->user_session = $_SESSION['User_Session'];
            $this->user_session->Create_Database_Connection();
        } else {
            $this->user_session = new User_Session;
            $_SESSION["User_Session"] = $this->user_session;
        }
    }

    public function Get_API_Token()
    {
        return $this->user_session->Get_API_Token();
    }
    private function Does_User_Session_Exist()
    {
        if (isset($_SESSION['User_Session'])) {
            return true;
        } else {
            return false;
        }
    }

    public function Am_I_Currently_Authenticated($throw_exception = false, $auto_renew = true)
    {
        return $this->user_session->Am_I_Currently_Authenticated($throw_exception, $auto_renew);
    }

    function Exit_If_Not_Currently_Authenticated($message = "", $throw_exception = false)
    {
        if (!$this->Am_I_Currently_Authenticated($throw_exception)) {
            header("Response: " . json_encode(["message" => "Sorry you need to authenticate."]));
            exit($message);
        }
    }

    function Authenticate($throw_exception = false)
    {
        try {
            $authenticated = $this->user_session->Authenticate_User();
        } catch (\User_Session\User_Is_Not_Authenticated $e) {
            if ($throw_exception) {
                throw new \User_Session\User_Is_Not_Authenticated($e->getMessage());
            }
            $authenticated = false;
        } catch (\User_Session\User_Does_Not_Exist $e) {
            if ($throw_exception) {
                throw new \User_Session\User_Does_Not_Exist($e->getMessage());
            }
            $authenticated = false;
        } catch (\Exception $e) {
            if ($throw_exception) {
                throw new \Exception($e->getMessage());
            }
            $authenticated = false;
        }
        return $authenticated;
    }

    function Have_I_Failed_At_Authenticating_This_Session()
    {
        return $this->user_session->Have_I_Failed_At_Authenticating_This_Session();
    }

    function LogOut()
    {
        $this->user_session->Auth0_Logout();
        $this->user_session->LogOut();
    }

    function Set_Password($password)
    {
        try {
            $this->user_session->Set_Password($password);
        } catch (User_Does_Not_Exist $e) {
            throw new User_Does_Not_Exist($e->getMessage());
        }
    }

    function Set_Username($username)
    {
        $this->user_session->Set_Username($username);
    }

    function Get_Username()
    {
        return $this->user_session->Get_Username();
    }

    function Currently_Default_Password()
    {
        return $this->user_session->Currently_Default_Password();
    }

    function Get_User_ID()
    {
        return $this->user_session->Get_User_ID();
    }

    function Change_Password($password)
    {
        if ($this->Get_Username() == "") {
            throw new User_Does_Not_Exist("You need to set a username before you can change the password");
        }
        $this->user_session->Change_Password($password);
    }

    function Get_DBLink()
    {
        return $this->user_session->Get_DBLink();
    }
}
