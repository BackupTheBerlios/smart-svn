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
 * action_user_add class 
 *
 */
 
class action_user_add extends action
{
    /**
     * add user
     *
     * @param array $data
     * @return bool
     */
    function perform( $data = FALSE )
    {
        // init error var
        $this->B->$data['error'] = FALSE;
        $error                   = & $this->B->$data['error'];

        // get user data
        $user   = $data['user_data']['login'];
        $passwd = $data['user_data']['passwd'];
        $email  = $data['user_data']['email'];
        
        // check if user exists
        if( isset($this->B->sys['user'][$user]) )
        {
            $error = 'Login exists';
            return FALSE;
        }
        // validate user name
        elseif( !preg_match("/[a-zA-Z0-9]{3,20}/",$user) )
        {
            $error = 'Login name require 3-20 chars a-zA-Z0-9.';
            return FALSE;
        }  
        // validate email
        elseif( !empty($email) && preg_match("/[^a-zA-Z0-9_@-.]/",$email) )
        {
            $error = 'Email require 3-20 chars a-zA-Z0-9_@-.';
            return FALSE;
        }         
        
        // assign system array with user date
        $this->B->sys['user'][$user]['passwd'] = md5($passwd);
        $this->B->sys['user'][$user]['email']  = $email;
        $this->B->sys['user'][$user]['role']   = 'admin';
        
        // update the system config file
        M( SF_BASE_MODULE, 'sys_update_config', $this->B->sys );
       
        return TRUE;
    } 
}

?>
