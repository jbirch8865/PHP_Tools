<?php declare(strict_types=1);
namespace Authentication;
global $dblink;
Delete_Company_Name_Column_From_Users_Table($dblink);
function Delete_Company_Name_Column_From_Users_Table(\DatabaseLink\Database $dblink)
{
    if($dblink->Does_Table_Exist('Users'))
    {
        $table = new \DatabaseLink\Table('Users',$dblink);
    }else
    {
        return;
    }
    try
    {
        $column = new \DatabaseLink\Column('company_name',$table);
    } catch (\Exception $e)
    {
        if(!$e->getMessage() == "sorry I can't create the column unless you supply all the default values")
        {
            throw new \Exception($e->getMessage());
        }else
        {
            return;
        }
    }
    $column->Delete_Column();
}
?>