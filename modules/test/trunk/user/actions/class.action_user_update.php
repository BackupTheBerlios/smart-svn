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
        // assign system array with user date
        if(isset($data['passwd']))
        {
            $this->B->sys['user'][$data['user']]['passwd'] = md5($data['passwd']);
        }
        $this->B->sys['user'][$data['user']]['email']  = $data['email'];
        
        // update the system config file
        M( SF_BASE_MODULE, 'sys_update_config', $this->B->sys );
        
        return TRUE;
    } 
}

?>
