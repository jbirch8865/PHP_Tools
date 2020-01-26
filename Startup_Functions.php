<?php
function Build_User_Table()
{
    global $root_folder;
    $dblink = new \DatabaseLink\MySQLLink($root_folder);
    $dblink->Execute_Any_SQL_Query('CREATE TABLE `Users` (
        `person_id` int(11) NOT NULL,
        `username` varchar(90) NOT NULL,
        `password` varchar(90) NOT NULL,
        `cspring` varchar(90) NOT NULL,
        `access_token` varchar(55) DEFAULT NULL,
        `current_session_token` varchar(13) DEFAULT NULL,
        `session_expires` timestamp NOT NULL DEFAULT current_timestamp(),
        `Active_Status` tinyint(1) NOT NULL DEFAULT 1
        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
}

?>