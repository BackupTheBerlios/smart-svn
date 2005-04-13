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
    function perform( & $data )
    {    
        $success = TRUE;
  
        //create captcha_pics dir if it dosent exist
        if(!is_writeable( SF_BASE_DIR . 'data/navigation' ))
        {
            $this->B->setup_error[] = 'Must be writeable: ' . SF_BASE_DIR . 'data/navigation';
            $success = FALSE;
        }  
    
        if($success == TRUE)
        {
            // create db tables
            if(file_exists(SF_BASE_DIR . 'modules/navigation/actions/sys_setup/_setup_'.$_POST['dbtype'].'.php'))
            {
                // include mysql setup
                include_once( SF_BASE_DIR . 'modules/navigation/actions/sys_setup/_setup_'.$_POST['dbtype'].'.php' );    
            }
            else
            {
                $this->B->setup_error[] = 'NAVIGATION module: This db type isnt supported: ' . $_POST['dbtype'];
                $success = FALSE;
            }
        }
    
        $this->B->conf_val['module']['navigation']['name']     = 'navigation';
        $this->B->conf_val['module']['navigation']['version']  = MOD_NAVIGATION_VERSION;
        $this->B->conf_val['module']['navigation']['mod_type'] = 'cms';

        return $success;
    }
}

?>
