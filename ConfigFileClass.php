<?php
namespace config;

class ConfigurationFile
{
	private $Configurations;

	function __construct($fileName = "config.local.ini")
	{
		$fileName = dirname(__FILE__) . DIRECTORY_SEPARATOR . $fileName;
		if($this->IsThisAString($fileName))
		{
			if($this->DoesFileExist($fileName))
			{
				$this->Configurations = $this->LoadFile($fileName);
			}else
			{
				$this->Configurations = array();
			}
		}else
		{
			throw new \Exception("This is not a valid filename");
		}
	}
	
	private function DoesFileExist($fileName)
	{
		if($this->IsThisAString($fileName))
		{
			if(file_exists($fileName))
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
	private function LoadFile($fileName)
	{
		try
		{
			return parse_ini_file ($fileName);
		} catch (\Exception $e)
		{
			throw new \Exception("Error loading this ini configuration file");
		}
	}
	function Configurations()
	{
		return $this->Configurations;
	}
}
?>