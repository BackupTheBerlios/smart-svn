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
 * action_default_sys_init class 
 *
 */
 
class action_default_sys_init extends action
{
    /**
     * Check if version number has changed and perfom additional upgarde code
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {
        // Check for upgrade  
        if(MOD_DEFAULT_VERSION != (string)$this->B->sys['module']['default']['version'])
        {        
            // The module name and version
            $this->B->sys['module']['default']['name']     = 'default';
            $this->B->sys['module']['default']['version']  = MOD_DEFAULT_VERSION;
            $this->B->sys['module']['default']['mod_type'] = 'test';
            $this->B->sys['module']['default']['info']     = 'This is the default module';
            $this->B->system_update_flag = TRUE; 
        }
    }    
}

?>
