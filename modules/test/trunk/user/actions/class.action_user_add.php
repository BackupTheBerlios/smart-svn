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
        // assign system array with user date
        $this->B->sys['user'][$data['user_data']['login']]['passwd'] = md5($data['user_data']['passwd']);
        $this->B->sys['user'][$data['user_data']['login']]['email']  = $data['user_data']['email'];
        $this->B->sys['user'][$data['user_data']['login']]['role']   = 'admin';

        // update config file with new user data        
        // see modules/SF_BASE_MODULE/actions/class.action_SF_BASE_MODULE_sys_update_config.php
        M( SF_BASE_MODULE, 
           'sys_update_config', 
           array( 'data'     => $this->B->sys,
                  'file'     => SF_BASE_DIR . 'data/'.SF_BASE_MODULE.'/config/config.php',
                  'var_name' => 'this->B->sys',
                  'type'     => 'PHPArray') );
       
        return SF_IS_VALID_ACTION;
    }
    
    /**
     * validate the parameters passed in the data array
     *
     * @param array $data
     * @return bool
     */    
    function validate(  $data = FALSE  )
    {
        // init error var
        $this->B->$data['error'] = FALSE;
        $error                   = & $this->B->$data['error'];
        
        // check if user exists
        if( isset($this->B->sys['user'][$data['user_data']['login']]) )
        {        
            $error = 'Login exists';
            return SF_NO_VALID_ACTION;
        }
        // validate user name
        elseif( !preg_match("/[a-zA-Z0-9]{3,20}/",$data['user_data']['login']) )
        {
            $error = 'Login name require 3-20 chars a-zA-Z0-9.';
            return SF_NO_VALID_ACTION;
        }  
        // validate email
        elseif( !empty($email) && preg_match("/[^a-zA-Z0-9_@-.]/",$data['user_data']['email']) )
        {
            $error = 'Email require 3-20 chars a-zA-Z0-9_@-.';
            return SF_NO_VALID_ACTION;
        }       
        
        return SF_IS_VALID_ACTION;
    }     
}

?>