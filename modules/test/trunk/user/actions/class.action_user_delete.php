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
 * action_user_delete class 
 *
 */
 
class action_user_delete extends action
{
    /**
     * delete user
     *
     * @param array $data
     * @return bool true or false on error
     */
    function perform( $data )
    {
        // remove this user data from the system array
        unset( $this->B->sys['user'][$data['user']] );
        
        // update config file with new user data        
        // see modules/SF_BASE_MODULE/actions/class.action_SF_BASE_MODULE_sys_update_config.php
        M( SF_BASE_MODULE, 
           'sys_update_config', 
           array( 'data'     => $this->B->sys,
                  'file'     => SF_BASE_DIR . 'modules/'.SF_BASE_MODULE.'/config/config.php',
                  'var_name' => 'this->B->sys',
                  'type'     => 'PHPArray') );  
        
        return TRUE;
    } 
}

?>