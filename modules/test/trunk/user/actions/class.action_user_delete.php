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
        
        // update the system config file
        M( SF_BASE_MODULE, 'sys_update_config', $this->B->sys );        
        
        return TRUE;
    } 
}

?>
