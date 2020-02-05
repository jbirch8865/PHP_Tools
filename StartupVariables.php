<?php
$tmp_folder = '';
$tmp_folder = explode('/',dirname(__FILE__));
global $root_folder;
(string) $root_folder = $tmp_folder[count($tmp_folder) - 4];
?>