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
 
class view_register
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
    function view_register()
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
     * Execute the view of the template "group_register.tpl.php"
     *
     * @return bool true on success else false
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
        $this->B->M( MOD_USER,
                     'captcha_make',
                     array( 'captcha_pic' => 'tpl_captcha_pic',
                            'public_key'  => 'tpl_public_key'));
                            
        if( $_POST['do_register'] )
        {
            // validate captcha turing/public keys
            if (FALSE === $this->B->M( MOD_USER,
                                       'captcha_validate',
                                       array( 'turing_key'  => $_POST['captcha_turing_key'],
                                              'public_key'  => $_POST['captcha_public_key'])))
            {
                $this->B->tpl_error = '- Wrong turing key<br /><br />';
                $this->_reset_form_data();
                return TRUE;
            }
                   
            if(FALSE == $this->B->M( MOD_USER, 
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
}

?>
