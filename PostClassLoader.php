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
$toolbelt_base->People = new \DatabaseLink\Table('People',$toolbelt_base->dblink);
$toolbelt_base->People_Belong_To_Company = new \DatabaseLink\Table('People_Belong_To_Company',$toolbelt_base->dblink);


?>
