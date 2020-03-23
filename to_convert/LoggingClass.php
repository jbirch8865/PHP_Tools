<?php
namespace logging;

class Log_To_Console
{
    public function __construct($message, $log_only_in_dev = true)
    {
        $cConfigs = new \config\ConfigurationFile();
        if ($log_only_in_dev) {
            if ($cConfigs->Is_Dev()) {
                echo '<script>console.log("'.$message.'");</script>';
            }
        } else {
            echo '<script>console.log("'.$message.'");</script>';
        }
    }
}

class Log_To_DB
{
    private $verified_log_id;
    private $timestamp;
    private $person_id;
    private $log_entry;
    private $log_type;
    private $dblink;

    public function __construct($unverified_log_id = null)
    {
        $this->verified_log_id = null;
        $this->person_id = null;
        $this->log_entry = "";
        $this->log_type = 1;
        global $dblink;
        $this->dblink = $dblink;
    }

    private function Load_Log($unverified_log_id)
    {
        if ($this->Verify_Log_ID($unverified_log_id)) {
            $this->Populate_Log_Properties();
        } else {
            throw new Log_Does_Not_Exist("Log does not exist.");
        }
    }
    private function Verify_Log_ID($id_to_verify)
    {
        if ($this->Does_Log_Exist($id_to_verify)) {
            $this->verified_log_id = $id_to_verify;
            return true;
        } else {
            $this->verified_log_id = null;
            return false;
        }
    }

    private function Does_Log_Exist($unverified_log_id)
    {
        $results = $this->dblink->ExecuteSQLQuery("SELECT * FROM `Log` WHERE `log_id` = '".$unverified_log_id."'");
        if (mysqli_num_rows($results) == 1) {
            return true;
        } else {
            return false;
        }
    }

    private function Populate_Log_Properties()
    {
        $results = $this->dblink->ExecuteSQLQuery("SELECT * FROM `Log` WHERE `log_id` = '".$this->verified_log_id."'");
        while ($row = mysqli_fetch_assoc($results)) {
            $this->timestamp = $row['timestamp'];
            $this->person_id = $row['person_id'];
            $this->log_entry = $row['log_entry'];
            $this->log_type = $row['log_type'];
        }
    }

    public function Set_Person_ID($user_id = null)
    {
        $current_user = new \User_Session\Current_User;
        $this->person_id = $current_user->Get_User_ID();
        if ($this->person_id == '') {
            $this->person_id = '1';
        }
    }

    public function Set_Log_Entry($log_entry)
    {
        $this->log_entry = $log_entry;
    }

    public function Set_Log_Type($log_type)
    {
        $this->log_type = (int) $log_type;
    }

    public function Get_Timestamp()
    {
        return $this->timestamp;
    }

    public function Get_Person_ID()
    {
        return $this->person_id;
    }

    public function Get_Log_Entry()
    {
        return $this->log_entry;
    }

    public function Get_Log_Type()
    {
        return $this->log_type;
    }

    public function Get_Log_ID()
    {
        return $this->verified_log_id;
    }

    public function Create_Log()
    {
        $this->Set_Person_ID();
        if (is_null($this->verified_log_id)) {
            $log_entry = mysqli_real_escape_string($this->dblink->GetCurrentLink(), $this->Get_Log_Entry());
            if ($results = $this->dblink->ExecuteSQLQuery("INSERT INTO `Log` SET `person_id` = '".$this->Get_Person_ID()."', `log_entry` = '".$log_entry."', `log_type` = '".$this->Get_Log_Type()."'")) {
                return $this->Verify_Log_ID($this->dblink->GetLastInsertID());
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}

class Logfile
{
    private $file_content;
    private $filename;

    public function __construct($filename, $filelocation = "logs/")
    {
        $this->filename = dirname(__FILE__) . DIRECTORY_SEPARATOR . $filelocation.$filename;
        if ($this->IsThisAString($this->filename)) {
            if ($this->DoesFileExist()) {
                $this->file_content = $this->LoadFile();
            } else {
                $this->file_content = "";
            }
        } else {
            throw new \Exception("This is not a valid filename");
        }
    }
    
    private function DoesFileExist()
    {
        if ($this->IsThisAString($this->filename)) {
            if (file_exists($this->filename)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    private function IsThisAString($string)
    {
        try {
            $string = (string) $string;
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    private function LoadFile()
    {
        try {
            return file_get_contents($this->filename);
        } catch (\Exception $e) {
            throw new \Exception("Error loading this log file");
        }
    }
    public function Get_File_Contents()
    {
        return $this->file_content;
    }
    
    public function Set_File_Contents($file_contents)
    {
        $this->safefilerewrite($file_contents);
    }
    
    private function safefilerewrite($dataToSave)
    {
        if ($fp = fopen($this->filename, 'w')) {
            $startTime = microtime(true);
            do {
                $canWrite = flock($fp, LOCK_EX);
                // If lock not obtained sleep for 0 - 100 milliseconds, to avoid collision and CPU load
                if (!$canWrite) {
                    usleep(round(rand(0, 100)*1000));
                }
            } while ((!$canWrite)and((microtime(true)-$startTime) < 5));
    
            //file was locked so now we can store information
            if ($canWrite) {
                fwrite($fp, $dataToSave);
                flock($fp, LOCK_UN);
            }
            fclose($fp);
        }
    }
}
