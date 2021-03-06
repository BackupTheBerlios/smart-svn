<?php
// ----------------------------------------------------------------------
// Smart PHP Framework
// Copyright (c) 2004, 2005
// by Armand Turpel < smart@open-publisher.net >
// http://smart.open-publisher.net/
// ----------------------------------------------------------------------
// GNU LESSER GENERAL PUBLIC LICENSE
// To read the license please visit http://www.gnu.org/licenses/lgpl.txt
// ----------------------------------------------------------------------

/**
 * action_system_sys_init class 
 *
 */
 
class action_system_sys_init extends action
{
    /**
     * Only check if the version number has changed > include upgrade code
     *
     *
     * @param array $data
     */  
    function perform( $data )
    {
        include_once(SF_BASE_DIR.'smart/includes/system_version.php');           

        // Check for upgrade  
        if($this->B->system_version != (string)$this->B->sys['info']['version'])
        {
            $this->B->sys['info']['name']    = $this->B->system_name;
            $this->B->sys['info']['version'] = $this->B->system_version;
            $this->B->system_update_flag = TRUE;  
                
            // include additional upgrade code here
        }   
        return SF_IS_VALID_ACTION;
    }
}

?>
