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
 * view_register class of the template "group_register.tpl.php"
 *
 */
 
class view_register extends view
{
     /**
     * Default template for this view
     * @var string $template
     */
    var $template = 'register';
    
    /**
     * Execute the view of the template "group_register.tpl.php"
     *
     * @return bool false on error else true
     */
    function perform()
    {
        // check if option allow_register is not set
        if($this->B->sys['option']['user']['allow_register'] == FALSE)
        {
            @header('Location: '.SF_BASE_LOCATION.'/index.php');
            exit;  
        }   
        
        // init template vars
        $this->B->tpl_error = FALSE;
        $this->B->tpl_success = NULL;
        
        // create capcha picture and public key
        M( MOD_USER,
           'captcha_make',
           array( 'captcha_pic' => 'tpl_captcha_pic',
                  'public_key'  => 'tpl_public_key'));
                            
        if( $_POST['do_register'] )
        {
            // validate captcha turing/public keys
            if (FALSE === M( MOD_USER,
                             'captcha_validate',
                             array( 'turing_key'  => $_POST['captcha_turing_key'],
                                    'public_key'  => $_POST['captcha_public_key'])))
            {
                $this->B->tpl_error = '- Wrong turing key<br /><br />';
                $this->_reset_form_data();
                return TRUE;
            }
                   
            if(FALSE == M( MOD_USER, 
                           'register', 
                           array('register'    => $_POST['do_register'],
                                 'error_var'   => 'tpl_error',
                                 'email_subject' => 'Your Earchive registration',
                                 'email_msg'     => 'You have to click on the link below to activate your account:<br /><br />(URL)<br /><br />Please contact the administrator on problems: (EMAIL).',
                                 'reg_data' => array('login'    => $_POST['login'], 
                                                     'passwd1'  => $_POST['passwd1'],
                                                     'passwd2'  => $_POST['passwd2'],
                                                     'forename' => $_POST['forename'],
                                                     'lastname' => $_POST['lastname'],
                                                     'email'    => $_POST['email']))))
            {
                $this->B->tpl_success = 'fail';
                $this->_reset_form_data();
                return TRUE;            
            }
            $this->B->tpl_success = 'ok';
        }
        return TRUE;
    }    
    
    function _reset_form_data()
    {
        $this->B->tpl_form = array();
        $this->B->tpl_form['forename'] = commonUtil::stripSlashes($_POST['forename']); 
        $this->B->tpl_form['lastname'] = commonUtil::stripSlashes($_POST['lastname']); 
        $this->B->tpl_form['login']    = commonUtil::stripSlashes($_POST['login']); 
        $this->B->tpl_form['email']    = commonUtil::stripSlashes($_POST['email']);     
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
