<?php
namespace config;

class ConfigurationFile
{
	private array $configurations;
	private string $filename;

	function __construct(string $filename = "config.local.ini")
	{
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
	 * DO NOT USE, as of version 1.4.20 moved this function to Get_Value_If_Enabled('name_of_the_config_you_want')
	 */
	function Configurations()
	{
		return $this->Configurations;
	}
	function Is_Dev()
	{
		if(isset($this->Configurations()['Environment']))
		{
			if(strtoupper($this->Configurations()['Environment']) == "DEVELOPMENT")
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
		if(isset($this->Configurations()['Environment']))
		{
			if(strtoupper($this->Configurations()['Environment']) == "PRODUCTION")
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

	/**
	 * Please use Get_Name_Of_Project_Database as of version 1.4.20 this function is depricated
	 */
	function Name_Of_Project_Database()
	{
		if(isset($this->Configurations()['database_name']))
		{
			return $this->Configurations()['database_name'];
		}
	}

	function Get_Name_Of_Project_Database()
	{
		return $this->Get_Value_If_Enabled('database_name');
	}

	function Set_Name_Of_Project_Database(string $name_of_database)
	{
		$this->Add_Or_Update_Config('database_name',$name_of_database);
	}

	function Get_Images_URL()
	{
		if(isset($this->Configurations()['vendor_directory']))
		{
			return $this->Configurations()['vendor_directory']."/images";
		}
	}
	
	function Get_Vendor_URL()
	{
		if(isset($this->Configurations()['vendor_directory']))
		{
			return $this->Configurations()['vendor_directory']."/vendor";
		}
	}

	function Get_Base_URL()
	{
		if(isset($this->Configurations()['Base_URL']))
		{
			return $this->Configurations()['Base_URL'];
		}
	}

	function Is_Feature_Enabled($feature)
	{
		if(isset($this->Configurations()[$feature]))
		{
			if($this->Configurations()[$feature])
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

	public function Get_Value_If_Enabled($configuration_key)
	{
		if($this->Is_Feature_Enabled($configuration_key))
		{
			return $this->Configurations()[$configuration_key];
		}else
		{
			return false;
		}
	}
	
	public function Set_Night_Mode()
	{
		$this->Add_Or_Update_Config('after_business_hours','1');
	}

	public function Set_Day_Mode()
	{
		$this->Add_Or_Update_Config('after_business_hours','0');
	}

	public function Is_Night_Mode_On()
	{
		if($this->Is_Feature_Enabled('after_business_hours'))
		{
			if($this->Configurations()['after_business_hours'] == '1')
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

	function Add_Or_Update_Config($key, $value)
	{
		$this->Configurations[$key] = $value;
		$this->write_php_ini($this->Configurations,$this->filename);
	}

	private function write_php_ini($array, $file)
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
	
	private function safefilerewrite($fileName, $dataToSave)
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
?>