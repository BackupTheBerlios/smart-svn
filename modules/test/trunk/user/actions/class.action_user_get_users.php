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
 * action_user_get_users class 
 *
 */
 
class action_user_get_users extends action
{
    /**
     * get user data
     *
     * @param array $data
     * @return bool true or false on error
     */
    function perform( $data = FALSE )
    {
        // init the result array
        $this->B->$data['result'] = array();
        // make reference to the result array
        $_result                  = & $this->B->$data['result'];

        // Walk through the assigned users
        foreach($this->B->sys['user'] as $key => $val)
        {
            // assign users to the result array
            $_result[] = array('user' => $key,
                               'role' => $this->B->sys['user'][$key]['role']);
        }
        
        // sort the result array by user names
        sort($_result);
        
        return TRUE;
    } 
}

?>
