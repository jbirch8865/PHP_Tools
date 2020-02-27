<?php
@session_start();
$_SESSION['company_id'] = 1;
require dirname(__FILE__) . DIRECTORY_SEPARATOR . 'ClassLoader.php';
require dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Deployment_Scripts/Loader.php';
?>