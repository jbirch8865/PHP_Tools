<?php
namespace logging;
class Log_To_Console
{
    function __construct($message,$log_only_in_dev = true)
    {
        $cConfigs = new \config\ConfigurationFile();
        if($log_only_in_dev)
        {
            if($cConfigs->Is_Dev())
            {
                echo '<script>console.log("'.$message.'");</script>';
            }
        }else
        {
            echo '<script>console.log("'.$message.'");</script>';
        }
    }
}
?>