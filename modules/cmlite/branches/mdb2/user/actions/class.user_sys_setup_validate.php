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
 * user_sys_setup_validate class 
 *
 */
 
class user_sys_setup_validate
{
    /**
     * Global system instance
     * @var object $B
     */
    var $B;
    
    /**
     * constructor
     *
     */
    function user_sys_setup_validate()
    {
        $this->__construct();
    }

    /**
     * constructor php5
     *
     */
    function __construct()
    {
        $this->B = & $GLOBALS['B'];
    }
    
    /**
     * Validate user data and dir check
     *
     * @param array $data
     */
    function perform( $data )
    {    
        $success = TRUE;
        // Do setup 
        if( empty($data['username']) )
        {
            $this->B->setup_error[] = 'username field is empty!';
            $success = FALSE;
        }
        if( empty($data['userlastname']) )
        {
            $this->B->setup_error[] = 'lastname field is empty!';
            $success = FALSE;
        }
        if( empty($data['userlogin']) )
        {
            $this->B->setup_error[] = 'login field is empty!';
            $success = FALSE;
        }
        if( empty($data['userpasswd1']) || ($data['userpasswd2'] != $data['userpasswd1']) )
        {
            $this->B->setup_error[] = 'password fields are empty or not equal!';
            $success = FALSE;
        } 
  
        //create captcha_pics dir if it dosent exist
        if(!is_writeable( SF_BASE_DIR . 'modules/user/actions/captcha/pics' ))
        {
            $this->B->setup_error[] = 'Must be writeable: ' . SF_BASE_DIR . 'modules/user/actions/captcha/pics';
            $success = FALSE;
        }  

        return $success;
    }
}

?>
