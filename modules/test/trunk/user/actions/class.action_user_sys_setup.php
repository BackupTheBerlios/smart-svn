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
 * action_user_sys_setup class 
 *
 */
 
class action_user_sys_setup extends action
{
    /**
     * Do setup for this module
     *
     * @param array $data
     */
    function perform( $data )
    {    
        $success = TRUE;
        // Do setup 

        //create captcha_pics dir if it dosent exist
        if(!is_writeable( SF_BASE_DIR . 'data/captcha' ))
        {
            $this->B->setup_error[] = 'Must be writeable: ' . SF_BASE_DIR . 'data/captcha';
            $success = FALSE;
        }  
    
        $this->B->conf_val['module']['user']['name']     = 'user';
        $this->B->conf_val['module']['user']['version']  = MOD_USER_VERSION;
        $this->B->conf_val['module']['user']['mod_type'] = 'lite';
        $this->B->conf_val['module']['user']['info']     = 'This is leader module of this module group. Author: Armand Turpel <smart AT open-publisher.net>';  
       
        // define user name, password and role
        $this->B->conf_val['user']['admin']['passwd'] = md5('admin');
        $this->B->conf_val['user']['admin']['role']   = 'admin';
        
        return $success;
    }
}

?>
