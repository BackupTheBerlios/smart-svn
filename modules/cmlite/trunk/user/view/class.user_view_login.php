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
 * user_view_login class of the template "login.tpl.php"
 *
 */
 
class user_view_login
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
    function user_view_login()
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
     * Execute the view of the template "index.tpl.php"
     * create the template variables
     * and listen to an action
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
                            
        // Check login data
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
            
            $this->B->M( MOD_USER, 
                         'check_login',
                         array( 'login'       => $_POST['login_name'],
                                'passwd'      => $_POST['password'],
                                'forward_url' => $_REQUEST['url']));
        }
            
        return TRUE;
    } 
    
    function _reset_form_data()
    {
        $this->B->tpl_form = array();
        $this->B->tpl_form['login_name'] = htmlentities(commonUtil::stripSlashes($_POST['login_name']));     
    }     
}

?>
