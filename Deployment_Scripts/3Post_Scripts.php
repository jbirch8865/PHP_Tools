<?php
Add_All_Constraints();
Create_Configs();
company_Create_System_If_Not_Already($toolbelt->Companies);
Create_Backend_User_If_Not_Already($toolbelt->cConfigs);
try
{
    $toolbelt->cConfigs->Save_Environment();
} catch (\config\Config_Missing $e)
{
    $toolbelt->cConfigs->Set_Dev_Environment();
}
function company_Create_System_If_Not_Already()
{
    try
    {
        $company = new \Company\Company();
        $company->Load_Company_By_ID(1);
        if(!$company->Get_Company_Name() == 'System')
        {
            $company->Set_Company_Name('System');
        }
    } catch (\Active_Record\Active_Record_Object_Failed_To_Load $e)
    {
        try
        {
            $company = new \Company\Company();
            $company->Load_Company_By_Name('System');
            $company->Change_Primary_Key(1,$company->Get_Verified_ID());
        } catch (\Active_Record\Active_Record_Object_Failed_To_Load $e)
        {
            $company = new \Company\Company();
            $company->Set_Company_Name('System',true,false);
            $company->Create_Object();
        }
    }
}
function Add_Column_Constraint(\DatabaseLink\Column $from_column,\DatabaseLink\Column $to_column):void
{
    $from_column->Add_Constraint_If_Does_Not_Exist($to_column);
}
function Create_Configs()
{
    $config = new \Company\Config();
    $config->Create_Or_Update_Config('company_time_zone','UTC');
    $config = new \Company\Config();
    $config->Create_Or_Update_Config('session_time_limit','300');
    
}
function Create_Backend_User_If_Not_Already(\config\ConfigurationFile $cConfigs)
{
    $user = new \Authentication\User($cConfigs->Get_Name_Of_Project(),$cConfigs->Get_Connection_Password(),1,true);
}
function Add_All_Constraints()
{
    global $toolbelt;
    $from_to_columns = array(
        array(array('Users','company_id'),array('Companies','id')),
        array(array('Programs_Have_Sessions','user_id'),array('Users','id')),        
        array(array('Company_Configs','company_id'),array('Companies','id')),        
        array(array('Company_Configs','config_id'),array('Configs','id')),        
        array(array('Programs_Have_Sessions','client_id'),array('Programs','client_id'))        
    );

    ForEach($from_to_columns as $index => $value)
    {
        $from_column = new \DatabaseLink\Column($value[0][1],$toolbelt->$value[0][0]);
        $to_column = new \DatabaseLink\Column($value[1][1],$toolbelt->$value[1][0]);
        Add_Column_Constraint($from_column,$to_column);            
    }    
}
function Add_All_Multi_Column_Unique_Indexes()
{
    global $toolbelt;
    $toolbelt->Company_Configs->Add_Unique_Columns(array('company_id','config_name'));
}
?>