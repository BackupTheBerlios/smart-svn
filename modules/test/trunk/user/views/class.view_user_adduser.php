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
 
class view_user_adduser extends view
{
     /**
     * Default template for this view
     * @var string $template
     */
    var $template = 'user_adduser';
    
     /**
     * Default template folder for this view
     * @var string $template_folder
     */    
    var $template_folder = 'modules/user/templates/';
    
    /**
     * Execute the view of the template "adduser.tpl.php"
     *
     * @return bool true on success else false
     */
    function perform()
    {    
        // add user on demande
        if( ($_GET['sec'] == 'adduser') && isset($_POST['adduser']) )
        {
            // Init form field values
            $this->B->tpl_error     = FALSE;
            $this->B->form_email    = '';
            $this->B->form_login    = '';
            $this->B->form_passwd   = '';

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
            if(FALSE !== M( MOD_USER,
                             'add',
                             $_data ))
            {
                // reload the user module on success
                @header('Location: '.SF_BASE_LOCATION.'/'.SF_CONTROLLER.'?admin=1&m=user');
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
        $this->B->form_email    = commonUtil::stripSlashes($_POST['email']);
        $this->B->form_login    = commonUtil::stripSlashes($_POST['login']);
        $this->B->form_passwd   = commonUtil::stripSlashes($_POST['passwd']);          
    }       
}

?>