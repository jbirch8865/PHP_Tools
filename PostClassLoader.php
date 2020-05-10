<?php
$toolbelt_base->Companies = new \DatabaseLink\Table('Companies',$toolbelt_base->dblink);
$toolbelt_base->Programs = new \DatabaseLink\Table('Programs',$toolbelt_base->dblink);
$toolbelt_base->Configs = new \DatabaseLink\Table('Configs',$toolbelt_base->dblink);
$toolbelt_base->Company_Configs = new \DatabaseLink\Table('Company_Configs',$toolbelt_base->dblink);
$toolbelt_base->Users = new \DatabaseLink\Table('Users',$toolbelt_base->dblink);
$toolbelt_base->Programs_Have_Sessions = new \DatabaseLink\Table('Programs_Have_Sessions',$toolbelt_base->dblink);
$toolbelt_base->Company_Roles = new \DatabaseLink\Table('Company_Roles',$toolbelt_base->dblink);
$toolbelt_base->Users_Have_Roles = new \DatabaseLink\Table('Users_Have_Roles',$toolbelt_base->dblink);
$toolbelt_base->Rights = new \DatabaseLink\Table('Rights',$toolbelt_base->dblink);
$toolbelt_base->Routes = new \DatabaseLink\Table('Routes',$toolbelt_base->dblink);
$toolbelt_base->Routes_Have_Roles = new \DatabaseLink\Table('Routes_Have_Roles',$toolbelt_base->dblink);
$toolbelt_base->Peoples = new \DatabaseLink\Table('Peoples',$toolbelt_base->dblink);
$toolbelt_base->People_Belong_To_Company = new \DatabaseLink\Table('People_Belong_To_Company',$toolbelt_base->dblink);
$toolbelt_base->Customers = new \DatabaseLink\Table('Customers',$toolbelt_base->dblink);
$toolbelt_base->Credit_Statuses = new \DatabaseLink\Table('Credit_Statuses',$toolbelt_base->dblink);
$toolbelt_base->Equipments = new \DatabaseLink\Table('Equipments',$toolbelt_base->dblink);
$toolbelt_base->Addresses = new \DatabaseLink\Table('Addresses',$toolbelt_base->dblink);
$toolbelt_base->Customer_Has_Addresses = new \DatabaseLink\Table('Customer_Has_Addresses',$toolbelt_base->dblink);
$toolbelt_base->Phone_Numbers = new \DatabaseLink\Table('Phone_Numbers',$toolbelt_base->dblink);
$toolbelt_base->Phone_Number_Types = new \DatabaseLink\Table('Phone_Number_Types',$toolbelt_base->dblink);
$toolbelt_base->Customer_Has_Phone_Numbers = new \DatabaseLink\Table('Customer_Has_Phone_Numbers',$toolbelt_base->dblink);


?>
