<?php
$cConfigs = new \config\ConfigurationFile();
$white_html_three_dots_jpg = '<img src="'.$cConfigs->Get_Images_URL().'/dots_menu.jpg" style="width:20px;">';
$html_green_checkmark = '<img src="'.$cConfigs->Get_Images_URL().'/green_checkmark.jpg" style="width:30px;">';
$html_checkmark = '<img src="'.$cConfigs->Get_Images_URL().'/checkmark.jpg" style="width:30px;">';
$html_delete = '<img src="'.$cConfigs->Get_Images_URL().'/delete.png" style="width:30px;">';
$html_yellow_exclamation = '<img src="'.$cConfigs->Get_Images_URL().'/yellow_exclamation.png" style="width:30px;">';
$html_white_down_arrow = '<img src="'.$cConfigs->Get_Images_URL().'/white_down_arrow.png" style="width:30px;">';
if(!isset($_SESSION['Add_Info'])){$_SESSION['Add_Info'] = array();}
?>