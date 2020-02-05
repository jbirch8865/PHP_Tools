<?php
namespace project_organizations;

class Organization 
{
    private int $verified_organization_id;
    private string $company_name;
    /**
     * @param int $unverified_organization_id used when you know the organization you are wanting to build
     * @param string $new_company_name used when you want to create a new organization
     */
    function __construct(int $unverified_organization_id = NULL,string $new_company_name = "")
    {
        if(is_null($unverified_organization_id))
        {
            if($new_company_name == "")
            {
                throw new \Exception("No organization given to load or create");
            }
            if($this->Does_Company_Exist($new_company_name))
            {
                throw new \Exception("This company already exists and can't be duplicated");
            }
            $this->Create_Company($new_company_name);
        }else
        {
            $this->Load_Customer($unverified_organization_id);
        }
    }
}
?>