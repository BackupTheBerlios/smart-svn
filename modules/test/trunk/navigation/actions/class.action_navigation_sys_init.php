<?php
// ----------------------------------------------------------------------
// Smart PHP Framework
// Copyright (c) 2004
// by Armand Turpel < smart@open-publisher.net >
// http://smart.open-publisher.net/
// ----------------------------------------------------------------------
// LICENSE GPL
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------

/**
 * action_navigation_sys_init class 
 *
 */
 
class action_navigation_sys_init extends action
{
    /**
     * Check if version number has changed and perfom additional upgarde code
     *
     * @param array $data
     */
    function perform( $data )
    {
        // Check for upgrade  
        if(MOD_NAVIGATION_VERSION != (string)$this->B->sys['module']['navigation']['version'])
        {
            // set the new version num of this module
            $this->B->sys['module']['navigation']['version']  = MOD_NAVIGATION_VERSION;
            $this->B->system_update_flag = TRUE;  
                
            // include here additional upgrade code
        }
        
        return SF_IS_VALID_ACTION;
    }    
}

?>