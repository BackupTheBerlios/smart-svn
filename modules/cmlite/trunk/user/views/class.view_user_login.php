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
 
class view_user_login extends view
{
     /**
     * Default template for this view
     * @var string $template
     */
    var $template = 'user_login';
    
     /**
     * Default template folder for this view
     * @var string $template_folder
     */    
    var $template_folder = 'modules/user/templates/';
    
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
        M( MOD_USER,
           'captcha_make',
           array( 'captcha_pic' => 'tpl_captcha_pic',
                  'public_key'  => 'tpl_public_key'));
                            
        // Check login data
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
            
            M( MOD_USER, 
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
    
    /**
     * disable prepend filter chain by overloading the methode of the parent class
     *
     */
    function prependFilterChain()
    { 
        // do nothing
    }  
    
    /**
     * disable append filter chain by overloading the methode of the parent class
     *
     */
    function appendFilterChain()
    { 
        // do nothing
    } 
    
    /**
     * disable authentication by overloading the methode of the parent class
     *
     */
    function auth()
    {  
        // do nothing
    }  
    
    /**
     * disable logout by overloading the methode of the parent class
     *
     */
    function logout()
    { 
        // do nothing
    }        
}

?>
