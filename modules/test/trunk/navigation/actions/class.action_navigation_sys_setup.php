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
 * action_navigation_sys_setup class 
 *
 */
 
class action_navigation_sys_setup extends action
{   
    /**
     * Do setup for this module
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {            
        // init the success var
        $success = TRUE;
            
        // include here all stuff to get work this module:
        // creating db tables

        // The module name and version
        // these array vars were saved later by the setup handler
        // in the file /modules/common/config/config.php
        //
        $this->B->conf_val['module']['navigation']['name']     = 'navigation';
        $this->B->conf_val['module']['navigation']['version']  = MOD_NAVIGATION_VERSION;
        $this->B->conf_val['module']['navigation']['mod_type'] = 'test';
        $this->B->conf_val['module']['navigation']['info']     = 'This is the navigation modul';

        // this directory must be writeable
        if(!is_writeable( SF_BASE_DIR . 'data/navigation' ))
        {
            $this->B->setup_error[] = 'Must be writeable: ' . SF_BASE_DIR . 'data/navigation';
            $success = FALSE;
        }
        
        // check if all files in this directory are writeable
        $directory =& dir( SF_BASE_DIR . 'data/navigation');
        while (false != ($file = $directory->read()))
        {
            if ( ( $file == '.' ) || ( $file == '..' ) || ($file == '.htaccess') )
            {
                continue;
            }            
            if(!is_writeable( SF_BASE_DIR . 'data/navigation/'.$file ))
            {
                $this->B->setup_error[] = 'Must be writeable: ' . SF_BASE_DIR . 'data/navigation/'.$file;
                $success = FALSE;
            }     
        }
            
        // if noting is going wrong $success is still TRUE else FALSE
        // ex.: if creating db tables fails you must set this var to false
        return $success;
    }    
}

?>