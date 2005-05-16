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
 * view_user_adduser class of the template "tpl.user_adduser.php"
 *
 */
 
class ViewUserAddUser extends SmartView
{
     /**
     * Default template for this view
     * @var string $template
     */
    public $template = 'adduser';
    
     /**
     * Default template folder for this view
     * @var string $template_folder
     */    
    public $templateFolder = 'modules/user/templates/';
    
    /**
     * Execute the view of the template "adduser.tpl.php"
     *
     * @return bool true on success else false
     */
    function perform()
    { 
        // Init template form field values
        $this->tplVar['error']            = FALSE;
        $this->tplVar['form_email']       = '';
        $this->tplVar['form_login']       = '';
        $this->tplVar['form_passwd']      = '';
        $this->tplVar['form_name']        = '';
        $this->tplVar['form_lastname']    = '';  
        $this->tplVar['form_website']     = '';
        $this->tplVar['form_description'] = '';                
    
        // add user on demande
        if( isset($_POST['addthisuser']) )
        {

            // check if required fields are empty
            if (FALSE == $this->_check_empty_fields())
            {
                // reset form fields on error
                $this->_reset_old_fields_data();
                return TRUE;
            }            

            // array with new user data
            $_data = array( 'error'     => 'tpl_error',
                            'user_data' => array('email'    => commonUtil::stripSlashes($_POST['email']),
                                                 'login'    => commonUtil::stripSlashes($_POST['login']),
                                                 'passwd'   => commonUtil::stripSlashes($_POST['passwd']) ));
            
            // add new user data
            if(SF_IS_VALID_ACTION == M( MOD_USER,
                                        'add',
                                        $_data ))
            {
                // reload the user module on success
                @header('Location: '.SF_BASE_LOCATION.'/'.SF_CONTROLLER.'?'.SF_ADMIN_CODE.'=1&m=user');
                exit; 
            }
            else
            {
                // reset form fields on error
                $this->_reset_old_fields_data();
                return TRUE;                
            }
        }
            
        return TRUE;
    } 
    
    /**
     * check if required fields are empty
     *
     * @return bool true on success else false
     * @access privat
     */       
    function _check_empty_fields()
    {
        // check if some fields are empty
        if( empty($_POST['login']) || empty($_POST['passwd']) )
        {        
            $this->B->tpl_error = 'You have fill out the login and password fields!';
            return FALSE;
        }  
        return TRUE;
    }  
    
    /**
     * reset the form fields with old user data
     *
     * @access privat
     */       
    function _reset_old_fields_data()
    {
        // if empty assign form field with old values
        $this->tplVar['form_email']  = commonUtil::stripSlashes($_POST['email']);
        $this->tplVar['form_login']  = commonUtil::stripSlashes($_POST['login']);
        $this->tplVar['form_passwd'] = commonUtil::stripSlashes($_POST['passwd']);          
    }       
}

?>