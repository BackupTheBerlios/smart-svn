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
 * action_common_sys_setup class 
 *
 */
 
class action_common_sys_setup extends action
{
    /**
     * Do setup for this module
     *
     * @param array $data
     */
    function perform( $data )
    {            
        // init the success var
        $success = TRUE;
            
        // include here all stuff to get work this module:
        // creating db tables

        // The module name and version
        // this array vars were saved later by the setup handler
        // in the file /admin/modules/common/config/config.php
        //
        $this->B->conf_val['module']['common']['name']     = 'common';
        $this->B->conf_val['module']['common']['version']  = MOD_COMMON_VERSION;
        $this->B->conf_val['module']['common']['mod_type'] = 'littlejo';

        if( !is_writeable( SF_BASE_DIR . 'modules/common/config' ) )
        {
            $this->B->setup_error[] = 'Must be writeable: ' . SF_BASE_DIR . 'modules/common/config';
            $success = FALSE;
        }
        
        if( !is_writeable( SF_BASE_DIR . 'modules/common/tmp/cache' ) )
        {
            $this->B->setup_error[] = 'Must be writeable: ' . SF_BASE_DIR . 'modules/common/tmp/cache';
            $success = FALSE;
        }        
            
        // if noting is going wrong $success is still TRUE else FALSE
        // ex.: if creating db tables fails you must set this var to false
        return $success;  
    }    
}

?>