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
 * view_login class of the template "tpl.login.php"
 *
 */
 
class view_login extends view
{  
     /**
     * Default template
     * @var string $template
     */
    var $template = 'login';

    /**
     * Execute the view of the template "tpl.login.php"
     *
     * @return bool false on error else true
     */
    function perform()
    {
        // create capcha picture and public key
        M( MOD_USER,
           'captcha_make',
           array( 'captcha_pic' => 'tpl_captcha_pic',
                  'public_key'  => 'tpl_public_key'));
                            
        if(isset($_POST['login']))
        {
            // validate captcha turing/public keys
            if (FALSE === M( MOD_USER,
                             'captcha_validate',
                             array( 'turing_key'  => $_POST['captcha_turing_key'],
                                    'public_key'  => $_POST['captcha_public_key'])))
            {
                $this->B->tpl_error = 'Wrong turing key!!<br /><br />';
                $this->_reset_form_data();
                return TRUE;
            }
            
            /* check login and password */
            if( FALSE === M( MOD_USER, 
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
    
    /**
     * default authentication
     *
     */
    function auth()
    {
        // Directed authentication event to the module handler, 
        // which takes the authentication part
        // The variable SF_AUTH_MODULE must be declared in the "common"
        // module event_handler.php file
        M( SF_AUTH_MODULE, 'sys_authenticate' );
    }
    
    /**
     * default prepend filter chain
     *
     */
    function prependFilterChain()
    {
        // Directed intercepting filter event (auto_prepend)
        // see smart/actions/class.system_sys_prepend.php
        M( MOD_SYSTEM, 'sys_prepend' );    
    }   
    
    /**
     * default append filter chain
     *
     */
    function appendFilterChain()
    {
        // Directed intercepting filter event (auto_append)
        // see smart/actions/class.system_sys_append.php
        M( MOD_SYSTEM, 'sys_append' );   
    }       
}

?>
