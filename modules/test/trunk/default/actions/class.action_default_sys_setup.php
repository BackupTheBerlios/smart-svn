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
 * action_default_sys_setup class 
 *
 */
 
class action_default_sys_setup extends action
{ 
    /**
     * Do setup for this module
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {            
        $success = SF_IS_VALID_ACTION;;
        // The module name and version
        $this->B->conf_val['module']['default']['name']     = 'default';
        $this->B->conf_val['module']['default']['version']  = MOD_DEFAULT_VERSION;
        $this->B->conf_val['module']['default']['mod_type'] = 'littlejo';
        
        return $success;
    }    
}

?>