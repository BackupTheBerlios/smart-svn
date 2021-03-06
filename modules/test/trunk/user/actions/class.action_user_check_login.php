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
 * action_user_check_login class 
 *
 */
 
class action_user_check_login extends action
{
    /**
     * Check login data and set session vars
     *
     * @param array $data
     */
    function perform( $data )
    {    
        $passwd = md5($data['passwd']);
     
        if( $this->B->sys['user'][$data['login']]['passwd'] == $passwd )
        {
            $this->B->session->set('logged_user', $data['login']);
            
            if(isset($data['noforward']))
            {
                return SF_IS_VALID_ACTION;
            }
            @header('Location: '.SF_BASE_LOCATION.'/'.SF_CONTROLLER.'?'.SF_ADMIN_CODE.'=1');
            exit;
        }
        else
        {
            return SF_NO_VALID_ACTION;
        }  
    } 
}

?>