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
 * view_login class of the template "group_login.tpl.php"
 *
 */
 
class view_login
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
    function view_login()
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
     * Execute the view of the template "group_login.tpl.php"
     *
     * @return bool true on success else false
     */
    function perform()
    {
        // create capcha picture and public key
        $this->B->M( MOD_USER,
                     'captcha_make',
                     array( 'captcha_pic' => 'tpl_captcha_pic',
                            'public_key'  => 'tpl_public_key'));
                            
        if(isset($_POST['login']))
        {
            // validate captcha turing/public keys
            if (FALSE === $this->B->M( MOD_USER,
                                       'captcha_validate',
                                       array( 'turing_key'  => $_POST['captcha_turing_key'],
                                              'public_key'  => $_POST['captcha_public_key'])))
            {
                $this->B->tpl_error = 'Wrong turing key!!<br /><br />';
                $this->_reset_form_data();
                return TRUE;
            }
            
            /* check login and password */
            if( FALSE === $this->B->M( MOD_USER, 
                                       'check_login', 
                                       array( 'login'          => $_POST['login_name'],
                                              'passwd'         => $_POST['password'],
                                              'forward_urlvar' => $_GET['url'])))
            {
                $this->B->tpl_error = 'Login fails. Please try again!!<br /><br />';
                $this->_reset_form_data();
                return TRUE;            
            }
        }
        return TRUE;
    }    
    
    function _reset_form_data()
    {
        $this->B->tpl_form = array();
        $this->B->tpl_form['login_name'] = commonUtil::stripSlashes($_POST['login_name']);     
    }    
}

?>
