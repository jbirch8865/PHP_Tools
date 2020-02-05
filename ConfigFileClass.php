<?php
namespace config;

class ConfigurationFile
{
	private array $configurations;
	private string $filename;
	private string $save_environment;
	//abstract protected function getValue();

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
			global $root_folder;
			$this->Add_Or_Update_Config('project_name',$root_folder);
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

	function Is_Dev()
	{
		if($this->Get_Value_If_Enabled('Environment'))
		{
			if(strtoupper($this->Get_Value_If_Enabled('Environment') == "DEVELOPMENT"))
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

	function Is_Prod()
	{
		if($this->Get_Value_If_Enabled('Environment'))
		{
			if(strtoupper($this->Get_Value_If_Enabled('Environment') == "PRODUCTION"))
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

	function Set_Dev_Environment()
	{
		$this->Add_Or_Update_Config('Environment','DEVELOPMENT');
	}

	function Set_Prod_Environment()
	{
		$this->Add_Or_Update_Config('Environment','PRODUCTION');
	}

	function Reset_Environment()
	{
		$this->Add_Or_Update_Config('Environment',$this->save_environment);
	}

	function Save_Environment()
	{
		$this->save_environment = $this->Get_Value_If_Enabled('Environment');
	}

	function Set_End_User_Date_Format(string $date_format)
	{
		$this->Add_Or_Update_Config('end_user_date_format',$date_format);
	}

	function Get_End_User_Date_Format()
	{
		if($this->Get_Value_If_Enabled('end_user_date_format'))
		{
			return $this->Get_Value_If_Enabled('end_user_date_format');
		}else
		{
			return 'Y-m-d';
		}
	}

	function Get_Name_Of_Project_Database(string $con_name = "project_database")
	{
		return $this->Get_Value_If_Enabled($con_name.'_project_database_name');
	}

	function Get_Name_Of_Project()
	{
		return $this->Get_Value_If_Enabled('project_name');
	}

	function Set_Name_Of_Project_Database(string $project_name)
	{
		$this->Add_Or_Update_Config($project_name.'_project_database_name',$project_name);
	}

	function Get_Connection_Username(string $con_name = "project_database")
	{
		return $this->Get_Value_If_Enabled($con_name.'_username');
	}

	function Get_Connection_Password(string $con_name = "project_database")
	{
		return $this->Get_Value_If_Enabled($con_name.'_password');
	}

	function Get_Connection_Hostname(string $con_name = "project_database")
	{
		return $this->Get_Value_If_Enabled($con_name.'_hostname');
	}

	function Get_Connection_Listeningport(string $con_name = "project_database")
	{
		return $this->Get_Value_If_Enabled($con_name.'_listeningport');
	}

	function Get_Root_Username()
	{
		return $this->Get_Value_If_Enabled('root_username');
	}

	function Get_Root_Password()
	{
		return $this->Get_Value_If_Enabled('root_password');
	}

	function Get_Root_Hostname()
	{
		return $this->Get_Value_If_Enabled('root_hostname');
	}

	function Get_Root_Listeningport()
	{
		return $this->Get_Value_If_Enabled('root_listeningport');
	}
	
	function Set_Database_Connection_Preferences(string $hostname,string $username, string $password, string $con_name = "project_database", string $listeningport = "3306")
	{
		$this->Add_Or_Update_Config($con_name.'_project_database_name',$con_name);
		$this->Add_Or_Update_Config($con_name.'_username',$username);
		$this->Add_Or_Update_Config($con_name.'_password',$password);
		$this->Add_Or_Update_Config($con_name.'_hostname',$hostname);
		$this->Add_Or_Update_Config($con_name.'_listeningport',$listeningport);
	}

	function Get_Images_URL()
	{
		if($this->Get_Value_If_Enabled('vendor_directory'))
		{
			return $this->Get_Value_If_Enabled('vendor_directory')."/images";
		}else
		{
			return false;
		}
	}
	
	function Get_Vendor_URL()
	{
		if($this->Get_Value_If_Enabled('vendor_directory'))
		{
			return $this->Get_Value_If_Enabled('vendor_directory');
		}else
		{
			return false;
		}
	}

	function Set_Vendor_URL(string $vendor_url)
	{
		$this->Add_Or_Update_Config('vendor_directory',$vendor_url);
	}

	function Get_Base_URL()
	{
		if($this->Get_Value_If_Enabled('Base_URL'))
		{
			return $this->Get_Value_If_Enabled('Base_URL');
		}
	}

	function Set_Base_URL(string $base_url)
	{
		$this->Add_Or_Update_Config('Base_URL',$base_url);
	}

	function Is_Config_Set(string $feature)
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

	public function Get_Value_If_Enabled(string $configuration_key)
	{
		if($this->Is_Config_Set($configuration_key))
		{
			return $this->configurations[$configuration_key];
		}else
		{
			return false;
		}
	}

	function Add_Or_Update_Config(string $key, string $value)
	{
		$this->configurations[$key] = $value;
		$this->write_php_ini($this->configurations,$this->filename);
	}

	function Delete_Config_If_Exists(string $key)
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
	{    if ($fp = fopen($fileName, 'w'))
		{
			$startTime = microtime(TRUE);
			do
			{            $canWrite = flock($fp, LOCK_EX);
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
}

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
?>