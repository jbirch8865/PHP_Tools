<?php
$toolbelt->Company_Configs->Add_Unique_Columns(array('company_id','config_name'));
Add_Column_Constraint($toolbelt->Company_Configs->Get_Column('company_id'),$toolbelt->Companies->Get_Column('id'));
Add_Column_Constraint($toolbelt->Company_Configs->Get_Column('config_id'),$toolbelt->Configs->Get_Column('id'));
Create_Configs();
company_Create_System_If_Not_Already($toolbelt->Companies);
Create_Backend_User_If_Not_Already($toolbelt->cConfigs);
$column = new \DatabaseLink\Column('company_id',$toolbelt->Users);
$column->Add_Constraint_If_Does_Not_Exist(new \DatabaseLink\Column('id',$toolbelt->Companies));
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

?>