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
 * action_user_get class 
 *
 */
 
class action_user_get extends action
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
        $this->B->$data['result'] = FALSE;
        $result                   = & $this->B->$data['result'];
        
        // get the user data
        $result['login'] = $data['user'];
        $result['email'] = $this->B->sys['user'][$data['user']]['email'];
        
        return TRUE;
    } 
    /**
     * validate the parameters passed in the data array
     *
     * @param array $data
     * @return bool
     */    
    function validate(  $data = FALSE  )
    {
        // check if this user exists
        if(!isset($this->B->sys['user'][$data['user']]))
        {
            return FALSE;
        }      
        
        return TRUE;
    }      
}

?>
