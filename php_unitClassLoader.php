<?php
global $running_tests;
$running_tests = true;
require dirname(__FILE__) . DIRECTORY_SEPARATOR . 'PreClassLoader.php';
require dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Deployment_Scripts/Loader.php';
require dirname(__FILE__) . DIRECTORY_SEPARATOR . 'PostClassLoader.php';
?>
