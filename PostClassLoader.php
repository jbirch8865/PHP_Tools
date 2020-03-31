<?php
$toolbelt_base->Companies = new \DatabaseLink\Table('Companies',$toolbelt_base->dblink);
$toolbelt_base->Programs = new \DatabaseLink\Table('Programs',$toolbelt_base->dblink);
$toolbelt_base->Configs = new \DatabaseLink\Table('Configs',$toolbelt_base->dblink);
$toolbelt_base->Company_Configs = new \DatabaseLink\Table('Company_Configs',$toolbelt_base->dblink);
ADODB_Active_Record::TableHasMany('Companies','Company_Configs','company_id','\Company\Company_Config');
ADODB_Active_Record::TableHasMany('Company_Configs','Configs','config_id','\Company\Config');
$toolbelt_base->Users = new \DatabaseLink\Table('Users',$toolbelt_base->dblink);
ADODB_Active_Record::TableKeyHasMany('Companies','id','Users','company_id','\Company\Company');
ADODB_Active_Record::TableBelongsTo('Users','Companies','company_id','id','\Company\Company');
//ADODB_Active_Record::TableBelongsTo('Programs_Have_Sessions','Users','user_id','id','\Authentication\User'); If we want this we would need to not construct the user with the __construct function.  Don't like this idea, ins
$toolbelt_base->Programs_Have_Sessions = new \DatabaseLink\Table('Programs_Have_Sessions',$toolbelt_base->dblink);

?>