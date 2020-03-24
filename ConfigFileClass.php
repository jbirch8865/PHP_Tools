<?php
namespace config;

class ConfigurationFile
{
	private array $configurations;
	private string $filename;
	private string $save_environment;
	/**
	 * @param string $filename if config.local.ini || ConfigFileClass.php looks at project_folder/vendor/jbirch8865/php_tools/$filename
	 * if any other string value will simply look at your string value with no additional context
	 */
	function __construct(string $filename = "config.local.ini")
	{
		$this->save_environment = false;
		if($filename == "config.local.ini" || $filename == "ConfigFileClass.php")
		{			
			$this->filename = dirname(__FILE__) . DIRECTORY_SEPARATOR . $filename;
		}else
		{
			$this->filename = $filename;
		}

		if($this->DoesFileExist())
		{
			$this->configurations = $this->LoadFile();
		}else
		{
			throw new config_file_missing("$filename does not exist");
		}
		if(!$this->Get_Name_Of_Project())
		{
			global $project_folder_name;
			$this->Add_Or_Update_Config('project_name',$project_folder_name);
		}
	}
	
	private function DoesFileExist()
	{
		if(file_exists($this->filename))
		{
			return true;
		}else
		{
			return false;
		}
	}

	private function LoadFile()
	{
		try
		{
			return parse_ini_file ($this->filename);
		} catch (\Exception $e)
		{
			throw new \Exception("Error loading this ini configuration file");
		}
	}
	/**
	 * @throws Config_Missing
	 */
	function Is_Dev() : bool
	{
		$this->Throw_Error_If_Config_Does_Not_Exist('Environment');
		if(strtoupper($this->Get_Value_If_Enabled('Environment') == "DEVELOPMENT"))
		{
			return true;
		}else
		{
			return false;
		}
	}
	/**
	 * @throws Config_Missing
	 */
	function Is_Prod() : bool
	{
		$this->Throw_Error_If_Config_Does_Not_Exist('Environment');
		if(strtoupper($this->Get_Value_If_Enabled('Environment') == "PRODUCTION"))
		{
			return true;
		}else
		{
			return false;
		}
	}

	function Set_Dev_Environment() : void
	{
		$this->Add_Or_Update_Config('Environment','DEVELOPMENT');
	}

	function Set_Prod_Environment() : void
	{
		$this->Add_Or_Update_Config('Environment','PRODUCTION');
	}
	/**
	 * @throws Exception if $->Save_Environment() has not been previously called
	 */
	function Reset_Environment() : void
	{
		if(!is_null($this->save_environment))
		{
			$this->Add_Or_Update_Config('Environment',$this->save_environment);
		}else
		{
			throw new \Exception("Must call $->Save_Environment before you can reset.");
		}
	}
	/**
	 * @throws Config_Missing
	 */
	function Save_Environment() : void
	{
		$this->Throw_Error_If_Config_Does_Not_Exist('Environment');
		$this->save_environment = $this->Get_Value_If_Enabled('Environment');
	}

	/**
	 * @param string $date_format follow php date recognized character format
	 * @link https://www.php.net/manual/en/function.date.php
	 */
	function Set_System_Date_Format(string $date_format) : void
	{
		$this->Add_Or_Update_Config('system_date_format',$date_format);
	}
	/**
	 * @return string If not set will return Y-m-d
	 */
	function Get_System_Date_Format() : string
	{
		if($this->Get_Value_If_Enabled('system_date_format'))
		{
			return $this->Get_Value_If_Enabled('system_date_format');
		}else
		{
			return 'Y-m-d';
		}
	}

	/**
	 * @param string $date_and_time_format follow php date recognized character format
	 * @link https://www.php.net/manual/en/function.date.php
	 */
	function Set_System_Date_And_Time_Format(string $date_and_time_format) : void
	{
		$this->Add_Or_Update_Config('system_date_and_time_format',$date_and_time_format);
	}
	function Delete_Database_Configs($project_name) :void
	{
		$this->Delete_Config_If_Exists($project_name.'_username');
		$this->Delete_Config_If_Exists($project_name.'_project_database_name');
		$this->Delete_Config_If_Exists($project_name.'_password');
		$this->Delete_Config_If_Exists($project_name.'_hostname');
		$this->Delete_Config_If_Exists($project_name.'_listeningport');

	}
	/**
	 * @return string If not set will return Y-m-d H:i:s
	 */
	function Get_System_Date_And_Time_Format() : string
	{
		if($this->Get_Value_If_Enabled('system_date_and_time_format'))
		{
			return $this->Get_Value_If_Enabled('system_date_and_time_format');
		}else
		{
			return 'Y-m-d H:i:s';
		}
	}
	
	/**
	 * @param string $date_format follow php date recognized character format
	 * @link https://www.php.net/manual/en/function.date.php
	 */
	function Set_System_Time_Format(string $date_format) : void
	{
		$this->Add_Or_Update_Config('system_time_format',$date_format);
	}
	/**
	 * @return string If not set will return H:i:s
	 */
	function Get_System_Time_Format() : string
	{
		if($this->Get_Value_If_Enabled('system_time_format'))
		{
			return $this->Get_Value_If_Enabled('system_time_format');
		}else
		{
			return 'H:i:s';
		}
	}
	
	
	function Set_Client_ID(string $client_id) : void
	{
		$this->Add_Or_Update_Config('client_id',$client_id);
	}
	function Set_Secret_ID(string $secret) : void
	{
		$this->Add_Or_Update_Config('secret',$secret);
	}
	function Get_Client_ID() : string
	{
		return $this->Get_Value_If_Enabled('client_id');
	}
	function Get_Secret_ID() : string
	{
		return $this->Get_Value_If_Enabled('secret');
	}



	/**
	 * @param string $con_name leave blank to default to the name of the current project
	 * @throws Config_Missing
	 */
	function Get_Name_Of_Project_Database(string $con_name = "") : string
	{
		if($con_name == "")
		{
			$con_name = $this->Get_Name_Of_Project();
		}
		$this->Throw_Error_If_Config_Does_Not_Exist($con_name.'_project_database_name');
		return $this->Get_Value_If_Enabled($con_name.'_project_database_name');
	}

	function Get_Name_Of_Project() : ?string
	{
		return $this->Get_Value_If_Enabled('project_name');
	}

	function Set_Name_Of_Project_Database(string $project_name) : void
	{
		$this->Add_Or_Update_Config($project_name.'_project_database_name',$project_name);
	}

	/**
	 * @throws Config_Missing
	 * @param string $con_name leave blank to default to project name
	 */
	function Get_Connection_Username(string $con_name = "",bool $read_only = false) : string
	{
		if($con_name == "")
		{
			$con_name = $this->Get_Name_Of_Project();
		}
		if($read_only)
		{
			$con_name = 'read_only_'.$con_name;
		}
		$this->Throw_Error_If_Config_Does_Not_Exist($con_name.'_username');
		return $this->Get_Value_If_Enabled($con_name.'_username');
	}

	/**
	 * @throws Config_Missing
	 * @param string $con_name leave blank to default to project name
	 */
	function Get_Connection_Password(string $con_name = "",bool $read_only = false) : string
	{
		if($con_name == "")
		{
			$con_name = $this->Get_Name_Of_Project();
		}
		if($read_only)
		{
			$con_name = 'read_only_'.$con_name;
		}
		$this->Throw_Error_If_Config_Does_Not_Exist($con_name.'_password');
		return $this->Get_Value_If_Enabled($con_name.'_password');
	}

	/**
	 * @throws Config_Missing
	 * @param string $con_name leave blank to default to project name
	 */
	function Get_Connection_Hostname(string $con_name = "",bool $read_only = false) : string
	{
		if($con_name == "")
		{
			$con_name = $this->Get_Name_Of_Project();
		}
		if($read_only)
		{
			$con_name = 'read_only_'.$con_name;
		}
		$this->Throw_Error_If_Config_Does_Not_Exist($con_name.'_hostname');
		return $this->Get_Value_If_Enabled($con_name.'_hostname');
	}

	/**
	 * @throws Config_Missing
	 * @param string $con_name leave blank to default to project name
	 */
	function Get_Connection_Listeningport(string $con_name = "",bool $read_only = false) : string
	{
		if($con_name == "")
		{
			$con_name = $this->Get_Name_Of_Project();
		}
		if($read_only)
		{
			$con_name = 'read_only_'.$con_name;
		}
		$this->Throw_Error_If_Config_Does_Not_Exist($con_name.'_listeningport');
		return $this->Get_Value_If_Enabled($con_name.'_listeningport');
	}

	/**
	 * @throws Config_Missing
	 */
	function Get_Root_Username() : string
	{
		$this->Throw_Error_If_Config_Does_Not_Exist('root_username');
		return $this->Get_Value_If_Enabled('root_username');
	}

	/**
	 * @throws Config_Missing
	 */
	function Get_Root_Password() : string
	{
		$this->Throw_Error_If_Config_Does_Not_Exist('root_password');
		return $this->Get_Value_If_Enabled('root_password');
	}

	/**
	 * @throws Config_Missing
	 */
	function Get_Root_Hostname() : string
	{
		$this->Throw_Error_If_Config_Does_Not_Exist('root_hostname');
		return $this->Get_Value_If_Enabled('root_hostname');
	}

	/**
	 * @throws Config_Missing
	 */
	function Get_Root_Listeningport() : string
	{
		$this->Throw_Error_If_Config_Does_Not_Exist('root_listeningport');
		return $this->Get_Value_If_Enabled('root_listeningport');
	}

	/**
	 * @param string $con_name leave blank to default to the name of the project
	 * @param bool $read_only are these credentials that are only granted select privileages
	 */
	function Set_Database_Connection_Preferences(string $hostname,string $username, string $password, string $con_name = "", string $listeningport = "3306", bool $read_only = false) : void
	{
		if($con_name == "")
		{
			$con_name = $this->Get_Name_Of_Project();
		}
		if($read_only)
		{
			$this->Add_Or_Update_Config('read_only_'.$con_name.'_project_database_name',$con_name);
			$con_name = 'read_only_'.$con_name;
		}else
		{
			$this->Add_Or_Update_Config($con_name.'_project_database_name',$con_name);
		}
		$this->Add_Or_Update_Config($con_name.'_username',$username);
		$this->Add_Or_Update_Config($con_name.'_password',$password);
		$this->Add_Or_Update_Config($con_name.'_hostname',$hostname);
		$this->Add_Or_Update_Config($con_name.'_listeningport',$listeningport);
	}

	function Is_Config_Set(string $feature) : bool
	{
		if(isset($this->configurations[$feature]))
		{
			if($this->configurations[$feature])
			{
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

	/**
	 * @throws Config_Missing
	 */
	function Throw_Error_If_Config_Does_Not_Exist(string $config_name) : void
	{
		if(!$this->Is_Config_Set($config_name))
		{
			throw new Config_Missing($config_name." is missing from the config file");
		}
	}
	protected function Get_Value_If_Enabled(string $configuration_key) : ?string
	{
		if($this->Is_Config_Set($configuration_key))
		{
			return $this->configurations[$configuration_key];
		}else
		{
			return null;
		}
	}

	/**
	 * Physically updates file
	 */
	protected function Add_Or_Update_Config(string $key, string $value) : void
	{
		$this->configurations[$key] = $value;
		$this->write_php_ini($this->configurations,$this->filename);
	}

	/**
	 * Physically updates file
	 */
	protected function Delete_Config_If_Exists(string $key) : void
	{
		unset($this->configurations[$key]);
		$this->write_php_ini($this->configurations,$this->filename);
	}

	private function write_php_ini(array $array,string $file)
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
		$this->safefilerewrite($file, implode("\r\n", $res));
	}
	
	private function safefilerewrite(string $fileName,string $dataToSave)
	{   if ($fp = fopen($fileName, 'w'))
		{
			$startTime = microtime(TRUE);
			do
			{            $canWrite = flock($fp, LOCK_EX);
			   // If lock not obtained sleep for 0 - 100 milliseconds, to avoid collision and CPU load
			   if(!$canWrite) usleep(round(rand(0, 100)*1000));
			} while ((!$canWrite)and((microtime(TRUE)-$startTime) < 5));
	
			//file was locked so now we can store information
			if ($canWrite)
			{            
				fwrite($fp, $dataToSave);
				flock($fp, LOCK_UN);
			}
			fclose($fp);
		}
	
	}
}

/*
class Public_File
{
	private string $filename;
	private string $foldername;

	function __construct(string $filename,string $foldername)
	{
		$this->filename = $filename;
		$this->foldername = $foldername;
		$this->Error_If_Directory_Does_Not_Exist();
	}

	private function Does_The_File_Or_Folder_Exists()
	{
		return file_exists(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $this->foldername);
	}

	private function Does_The_Folder_Exists()
	{
		return $this->Does_The_File_Or_Folder_Exists($this->foldername);
	}

	function Does_The_File_Exists()
	{
		return $this->Does_The_File_Or_Folder_Exists($this->filename);
	}

	private function Error_If_Directory_Does_Not_Exist()
	{
		if(!$this->Does_The_Folder_Exists())
		{
			throw new file_or_folder_does_not_exist("file_exists(".dirname(__FILE__) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $this->foldername.") was false.");
		}
	}

	function Create_Or_Overwrite_File($data_to_write)
	{
		$this->safefilerewrite($data_to_write);
	}

	private function safefilerewrite(string $dataToSave)
	{    
		if($fp = fopen(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $this->foldername . DIRECTORY_SEPARATOR . $this->filename, 'w'))
		{
			$startTime = microtime(TRUE);
			do
			{            
			   $canWrite = flock($fp, LOCK_EX);
			   // If lock not obtained sleep for 0 - 100 milliseconds, to avoid collision and CPU load
			   if(!$canWrite) usleep(round(rand(0, 100)*1000));
			} while ((!$canWrite)and((microtime(TRUE)-$startTime) < 5));
	
			//file was locked so now we can store information
			if ($canWrite)
			{            fwrite($fp, $dataToSave);
				flock($fp, LOCK_UN);
			}
			fclose($fp);
		}
	}

	function Get_File_Contents()
	{
		if($this->Does_The_File_Exists())
		{
			return file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $this->foldername . DIRECTORY_SEPARATOR . $this->filename);
		}else
		{
			throw new \config\file_or_folder_does_not_exist("Can't get the contents because the file doesn't exist");
		}	
	}

	function Delete_File()
	{
		return unlink(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $this->foldername . DIRECTORY_SEPARATOR . $this->filename);
	}

}

class Public_Folder
{
	private string $foldername;

	function __construct(string $foldername,$create_if_non_existant = false)
	{
		$this->foldername = $foldername;
		if($create_if_non_existant)
		{
			$this->Create_Directory_If_Non_Existant();
		}else
		{
			$this->Exit_If_Directory_Is_Non_Existant();
		}
	}

	private function Does_The_Folder_Exist()
	{
		return file_exists(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $this->foldername);
	}

	private function Exit_If_Directory_Is_Non_Existant()
	{
		if(!$this->Does_The_Folder_Exist())
		{
			throw new file_or_folder_does_not_exist($this->foldername." does not exist");
		}
	}

	private function Create_Directory_If_Non_Existant()
	{
		if(!$this->Does_The_Folder_Exist())
		{
			$this->Create_Public_Directory();
		}
	}
	
	private function Create_Public_Directory()
	{
		return mkdir(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $this->foldername);
	}

	function Delete_Public_Directory(bool $delete_files = false)
	{
		if (is_dir(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $this->foldername))
		{
			$dir_handle = opendir(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $this->foldername);
		}
		while($file = readdir($dir_handle)) 
		{
			if ($file != "." && $file != "..") 
			{
				if(!$delete_files)
				{
					return false;
				}
				if (!is_dir(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $this->foldername . DIRECTORY_SEPARATOR . $file))
				{
					unlink(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $this->foldername . DIRECTORY_SEPARATOR . $file);
				}else	
				{
					$this->Delete_Public_Directory(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $this->foldername . DIRECTORY_SEPARATOR . $file);
				}
			}
		}
		closedir($dir_handle);
		rmdir(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . $this->foldername);
		return true;
	}
}
*/
?>