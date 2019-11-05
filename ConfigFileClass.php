<?php
namespace config;


class ConfigurationFile
{
	private $Configurations;
	private $filename;

	function __construct($fileName = "config.local.ini")
	{
		if($fileName == "config.local.ini" || $fileName == "ConfigFileClass.php")
		{			
			$this->filename = dirname(__FILE__) . DIRECTORY_SEPARATOR . $fileName;
		}else
		{
			$this->filename = $fileName;
		}
		if($this->IsThisAString($this->filename))
		{
			if($this->DoesFileExist())
			{
				$this->Configurations = $this->LoadFile();
			}else
			{
				$this->Configurations = array();
			}
		}else
		{
			throw new \Exception("This is not a valid filename");
		}
	}
	
	private function DoesFileExist()
	{
		if($this->IsThisAString($this->filename))
		{
			if(file_exists($this->filename))
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
	private function IsThisAString($string)
	{
		try
		{
			$string = (string) $string;
			return true;
		}catch (\Exception $e)
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
	function Get_End_User_Date_Format()
	{
		if(!empty($this->Configurations()['end_user_date_format']))
		{
			return $this->Configurations()['end_user_date_format'];
		}else
		{
			return 'Y-m-d';
		}
	}
	function Name_Of_Project_Database()
	{
		if(isset($this->Configurations()['database_name']))
		{
			return $this->Configurations()['database_name'];
		}
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