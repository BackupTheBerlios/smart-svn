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
 * action_user_update class 
 *
 */
 
class action_user_update extends action
{
    /**
     * update user data
     *
     * @param array $data
     * @return bool true or false on error
     */
    function perform( $data )
    {
        // check to update the password
        if(isset($data['passwd']))
        {
            $this->B->sys['user'][$data['user']]['passwd'] = md5($data['passwd']);
        }
        // assign new email
        $this->B->sys['user'][$data['user']]['email']  = $data['email'];
        
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
}

?>