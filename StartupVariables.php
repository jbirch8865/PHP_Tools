<?php
$tmp_folder = '';
$tmp_folder = explode('/',dirname(__FILE__));
/**
 * @var string $probject_folder_name name of the parent folder the project is installed in
 * The folder structure would be project_folder/vendor/jbirch8865/php_tools/StartupVariables.php
 */
global $project_folder_name;
(string) $project_folder_name = $tmp_folder[count($tmp_folder) - 4];
?>