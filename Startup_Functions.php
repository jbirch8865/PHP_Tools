<?php
function Build_Organization_Table()
{
    global $dblink;
    $dblink->Execute_Any_SQL_Query('CREATE TABLE `Organizations` (
        `organization_id` int(11) NOT NULL,
        `name` varchar(50) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
    $dblink->Execute_Any_SQL_Query('ALTER TABLE `Organizations`
        ADD PRIMARY KEY (`organization_id`);
    ');
    $dblink->Execute_Any_SQL_Query('ALTER TABLE `Organizations` CHANGE `organization_id` `organization_id` INT(11) NOT NULL AUTO_INCREMENT');
    $dblink->Execute_Insert_Or_Update_SQL_Query('Organizations',array('organization_id' => 1));
    $dblink->Execute_Any_SQL_Query('ALTER TABLE `Users`
    ADD CONSTRAINT `Users_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `Organizations` (`organization_id`) ON DELETE CASCADE ON UPDATE CASCADE');
}

function Build_Icon_Table()
{
    global $dblink;
    $dblink->Execute_Any_SQL_Query('CREATE TABLE `Icon_Library` (
        `icon_id` int(11) NOT NULL,
        `description` varchar(50) NOT NULL,
        `filename` varchar(50) NOT NULL,
        `height` int(11) DEFAULT NULL,
        `width` int(11) DEFAULT NULL,
        `locked` tinyint(1) NOT NULL DEFAULT 1,
        `organization_id` int(11) DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
    $dblink->Execute_Any_SQL_Query('ALTER TABLE `Icon_Library` ADD PRIMARY KEY (`icon_id`);');    
    $dblink->Execute_Any_SQL_Query('ALTER TABLE `Icon_Library` CHANGE `icon_id` `icon_id` INT(11) NOT NULL AUTO_INCREMENT');
    $dblink->Execute_Any_SQL_Query('ALTER TABLE `Icon_Library`
    ADD CONSTRAINT `Icon_Library_ibfk_1` FOREIGN KEY (`organization_id`) REFERENCES `Organizations` (`organization_id`) ON DELETE CASCADE ON UPDATE CASCADE');
    $dblink->Execute_Insert_Or_Update_SQL_Query('Icon_Library',array('description' => 'Expand Plus Icon','filename' => '+.png'));
}


function Build_User_Table()
{
    global $dblink;
    $dblink->Execute_Any_SQL_Query('CREATE TABLE `Users` (
        `person_id` int(11) NOT NULL,
        `username` varchar(90) NOT NULL,
        `password` varchar(90) NOT NULL,
        `cspring` varchar(90) NOT NULL,
        `access_token` varchar(55) DEFAULT NULL,
        `current_session_token` varchar(13) DEFAULT NULL,
        `session_expires` timestamp NOT NULL DEFAULT current_timestamp(),
        `active_status` tinyint(1) NOT NULL DEFAULT 1,
        `organization_id` int(11) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
    $dblink->Execute_Any_SQL_Query('ALTER TABLE `Users`
    ADD PRIMARY KEY (`person_id`),
    ADD UNIQUE KEY `username` (`username`)');
    $dblink->Execute_Any_SQL_Query('ALTER TABLE `Users` CHANGE `person_id` `person_id` INT(11) NOT NULL AUTO_INCREMENT');
    $dblink->Execute_Any_SQL_Query('ALTER TABLE `Users` AUTO_INCREMENT = 2');
        
}
function Build_Preferences_Table()
{
    global $dblink;
    $dblink->Execute_Any_SQL_Query('CREATE TABLE `Preferences` (
        `preference_id` int(11) NOT NULL,
        `preference_description` varchar(50) NOT NULL,
        `active_status` tinyint(1) NOT NULL DEFAULT 1
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
    $dblink->Execute_Any_SQL_Query('ALTER TABLE `Preferences` CHANGE `preference_id` `preference_id` INT(11) NOT NULL AUTO_INCREMENT');
}
function Generate_CSPRNG(int $length,string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') 
{
    $str = '';
    $max = mb_strlen($keyspace, '8bit') - 1;
    if ($max < 1) {
        throw new Exception('$keyspace must be at least two characters long');
    }
    for ($i = 0; $i < $length; ++$i) {
        $str .= $keyspace[random_int(0, $max)];
    }
    return $str;
}
?>