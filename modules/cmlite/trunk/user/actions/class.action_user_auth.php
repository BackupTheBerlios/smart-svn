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
 *
 */
 
class action_user_auth extends action
{
    /**
     * @var bool $_is_logged flag: if user is logged true else false
     * @access privat
     */
    var $_is_logged = FALSE;
    
    /**
     * User authentication
     * if user is not logged switch to the login view
     *
     * @param array $data
     */
    function perform( $data = FALSE )
    {        
        if( (SF_SECTION == 'admin') && (FALSE == $this->_is_logged) )
        {
            $_REQUEST['view'] = 'login';
            $_REQUEST['m']    = SF_AUTH_MODULE;

            $this->B->user_is_logged = $this->_is_logged;
            return FALSE;
        }
        else
        {  
            $this->B->user_is_logged     = $this->_is_logged;
            $this->B->user_logged_rights = $this->B->session->get('user_logged_rights');
            return TRUE;
        }
    } 
    
    /**
     * Validate this user session (authentication)
     *
     * @return TRUE allways
     */    
    function validate( $data = FALSE )
    {
        // check if user logged id exists
        if(!$this->B->session->exists('user_logged_uid'))
        {
            $this->_is_logged = FALSE;
            return TRUE;
        }      

        // get session data to verify if this session is valide
        $this->B->user_logged_uid    = $this->B->session->get('user_logged_uid');
        $this->B->user_logged_login  = $this->B->session->get('user_logged_login');
        $user_logged_hashid          = $this->B->session->get('user_logged_hashid');

        // create hash id
        $compare_hashid = md5($this->B->sys['system']['hashid'].$this->B->user_logged_login.$this->B->user_logged_uid);

        // compare the two hash id's
        if( $user_logged_hashid != $compare_hashid )
        {
            // delete all session vars
            $this->B->session->del_all();
            
            $this->_is_logged = FALSE;
            return TRUE;        
        }
        else
        {
            $this->_is_logged = TRUE;
            return TRUE;              
        }
    }    
}

?>