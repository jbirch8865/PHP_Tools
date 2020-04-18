<?php
try
{
    $toolbelt_base->cConfigs->Save_Environment();
} catch (\config\Config_Missing $e)
{
    $toolbelt_base->cConfigs->Set_Dev_Environment();
}
Add_All_Constraints();
Add_All_Multi_Column_Unique_Indexes();
Create_Backend_Program_For_API($toolbelt_base);
Create_Configs();
Create_System_If_Not_Already();
Create_Backend_User_If_Not_Already($toolbelt_base->cConfigs);
function Create_System_If_Not_Already()
{
    $toolbelt = new \Test_Tools\toolbelt;
    if($toolbelt->cConfigs->Is_Prod()){return;}
    try
    {
        $company = new \Company\Company();
        $company->Load_Object_By_ID(1);
        if($company->Get_Friendly_Name() != 'System')
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
            $company->Change_Primary_Key(1,$company->Get_Verified_ID());
        }
    }
    if(is_null($company->Get_Master_Role()))
    {
        $company->Create_Company_Role('master');
    }
}
function Create_Backend_Program_For_API(\Test_Tools\toolbelt_base $toolbelt_base)
{
    $toolbelt = new \Test_Tools\toolbelt;
    if($toolbelt->cConfigs->Is_Prod()){return;}
    try
    {
        $program = new \API\Program();
        $program->Load_Object_By_ID(1);
        if(!$program->Get_Friendly_Name() == 'Sandbox')
        {
            $program->Get_Friendly_Name('Sandbox');
        }
    } catch (\Active_Record\Active_Record_Object_Failed_To_Load $e)
    {
        try
        {
            $program = new \API\Program();
            $program->Load_Program_By_Name('Sandbox');
            $program->Change_Primary_Key(1,$program->Get_Verified_ID());
        } catch (\Active_Record\Active_Record_Object_Failed_To_Load $e)
        {
            $program = new \API\Program();
            $program->Create_Project('Sandbox');
        }
    }
    $toolbelt_base->cConfigs->Set_Client_ID($program->Get_Client_ID());
    $toolbelt_base->cConfigs->Set_Secret_ID($program->Get_Secret());
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
    $toolbelt = new \Test_Tools\toolbelt;
    if($toolbelt->cConfigs->Is_Prod()){return;}
    $company = new \Company\Company;
    $company->Load_Object_By_ID(1);
    $user = new \Authentication\User('default',$toolbelt->cConfigs->Get_Client_ID(),$company,true);
    try
    {
        $user->Assign_Company_Role($company->Get_Master_Role());
    } catch (\Active_Record\UpdateFailed $e)
    {

    }
}
function Add_All_Constraints()
{
    global $toolbelt_base;
    $from_to_columns = array(
        array(array('Users','company_id'),array('Companies','id')),
        array(array('Programs_Have_Sessions','user_id'),array('Users','id')),
        array(array('Company_Configs','company_id'),array('Companies','id')),
        array(array('Company_Configs','config_id'),array('Configs','id')),
        array(array('Programs_Have_Sessions','client_id'),array('Programs','client_id')),
        array(array('Company_Roles','company_id'),array('Companies','id')),
        [['Users_Have_Roles','user_id'],['Users','id']],
        [['Users_Have_Roles','role_id'],['Company_Roles','id']]
    );

    ForEach($from_to_columns as $index => $value)
    {
        $from_column_name = $value[0][1];
        $from_table_name = $value[0][0];
        $to_column_name = $value[1][1];
        $to_table_name = $value[1][0];
        $from_column = new \DatabaseLink\Column($from_column_name,$toolbelt_base->$from_table_name);
        $to_column = new \DatabaseLink\Column($to_column_name,$toolbelt_base->$to_table_name);
        Add_Column_Constraint($from_column,$to_column);
    }
}
function Add_All_Multi_Column_Unique_Indexes()
{
    global $toolbelt_base;
    $toolbelt_base->Company_Configs->Add_Unique_Columns(array('company_id','config_id'));
    $toolbelt_base->Programs_Have_Sessions->Add_Unique_Columns(array('client_id','user_id'));
    $toolbelt_base->Company_Roles->Add_Unique_Columns(array('company_id','role_name'));
    $toolbelt_base->Users->Add_Unique_Columns(array('company_id','username','project_name'));
    $toolbelt_base->Users_Have_Roles->Add_Unique_Columns(array('user_id','role_id'));

}
?>
