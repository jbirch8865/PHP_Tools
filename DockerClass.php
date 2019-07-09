<?php
namespace docker;

class Docker {
	private $all_docker_secrets;
	private $all_docker_configs;
	private $docker_secrets_folder_location;
	private $docker_configs_folder_location;

	function __construct($secrets_folder = '/run/secrets/',$configs_folder = '/')
	{
		$this->all_docker_secrets = array();
		$this->all_docker_configs = array();
		$this->docker_secrets_folder_location = $secrets_folder;
		$this->docker_configs_folder_location = $configs_folder;
		try {
			$this->Populate_Docker_Secrets();
			$this->Populate_Docker_Configs();
		} catch (BadFolderLocation $e)
		{
			throw new BadFolderLocation($secrets_folder, $configs_folder);
		} catch (\Exception $e)
		{
			throw new \Exception($e->getMessage());
		}
	}

	public function Get_Secret_Value($Secret_To_Get)
	{
		try {
			if(is_set($this->All_Docker_Secrets[$Secret_To_Get]))
			{
				return $this->All_Docker_Secrets[$Secret_To_Get];
			}else
			{
				throw new SecretDoesNotExist($Secret_To_Get);
			}
		}catch (SecretDoesNotExist $e)
		{
			throw new SecretDoesNotExist($Secret_To_Get);
		}catch (\Exception $e)
		{
			throw new \Exception($e->getMessage());
		}
	}

	public function Get_Config_Value($Config_To_Get)
	{
		try {
			if(is_set($this->All_Docker_Configs[$Config_To_Get]))
			{
				return $this->All_Docker_Configs[$Config_To_Get];
			}else
			{
				throw new ConfigDoesNotExist($Config_To_Get);
			}
		}catch (ConfigDoesNotExist $e)
		{
			throw new ConfigDoesNotExist($Config_To_Get);
		}catch (\Exception $e)
		{
			throw new \Exception($e->getMessage());
		}
	}

	private function Populate_Docker_Secrets()
	{
		try {
			if($this->Is_This_A_Valid_File_Or_Directory($this->docker_secrets_folder_location))
			{
				$dir = new \DirectoryIterator($this->docker_secrets_folder_location);
				foreach ($dir as $fileinfo) {
	    				if (!$fileinfo->isDot() && filesize($this->docker_secrets_folder_location."/".$fileinfo->getFilename()) != 0)
					{
						$myfile = fopen($this->docker_secrets_folder_location."/".$fileinfo->getFilename(), "r");
        					$this->all_docker_secrets[$fileinfo->getFilename()] = fread($myfile,filesize($this->docker_secrets_folder_location."/".$fileinfo->getFilename()));
						fclose($myfile);
    					}
				}
			}else
			{
				throw new BadFolderLocation();
			}
		} catch (BadFolderLocation $e)
		{
			throw new BadFolderLocation();
		} catch (\Exception $e)
		{
			throw new \Exception($e->getMessage());
		}
	}

	private function Populate_Docker_Configs()
	{
		try {
			if($this->Is_This_A_Valid_File_Or_Directory($this->docker_configs_folder_location))
			{
				$dir = new \DirectoryIterator($this->docker_configs_folder_location);
				foreach ($dir as $fileinfo) {
	    				if (!$fileinfo->isDot() && filesize($this->docker_configs_folder_location."/".$fileinfo->getFilename()) != 0)
					{
						$myfile = fopen($this->docker_configs_folder_location."/".$fileinfo->getFilename(), "r");
        					$this->all_docker_configs[$fileinfo->getFilename()] = fread($myfile,filesize($this->docker_configs_folder_location."/".$fileinfo->getFilename()));
						fclose($myfile);
    					}
				}
			}else
			{
				throw new BadFolderLocation();
			}
		} catch (BadFolderLocation $e)
		{
			throw new BadFolderLocation();
		} catch (\Exception $e)
		{
			throw new \Exception($e->getMessage());
		}
	}

	private function Is_This_A_Valid_File_Or_Directory($folder_to_validate)
	{
		try {
			if(file_exists($folder_to_validate))
			{
				return true;
			} else
			{
				return false;
			}
		} catch (\Exception $e)
		{
			throw new \Exception($e->getMessage());
		}
	}
}
?>
