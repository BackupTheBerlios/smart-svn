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
 * action_user_auth class 
 *
 * The variables produced by the authentication process:
 *
 * $B->is_logged
 * -------------
 * If a user was successfully authenticated this variable is set to TRUE.
 * Else this variable is set to FALSE.
 *
 * $B->logged_user
 * ----------------------
 * login name of the user
 *
 * $B->logged_user_role
 * ------------------
 * Role of the user
 *
 */
 
class action_user_auth extends action
{
    /**
     * User authentication
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {    
        if(!isset($_SESSION['logged_user']) || empty($_SESSION['logged_user']))
        {
            $this->B->_is_logged = FALSE;
        }
                   
        $user = $_SESSION['logged_user'];
        
        if( isset($this->B->sys['user'][$user]) )
        {
            $this->B->logged_user_role = $this->B->sys['user'][$user]['role'];
            $this->B->logged_user      = $user;
            $this->B->_is_logged  = TRUE;       
        }
        else
        {
            $this->B->_is_logged = FALSE;
        }  
        
        return SF_IS_VALID_ACTION;
    } 
}

?>