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
            $this->B->form_forename = '';
            $this->B->form_lastname = '';
            $this->B->form_email    = '';
            $this->B->form_login    = '';
            $this->B->form_passwd   = '';
            $this->B->form_rights   = '';
            $this->B->form_status   = '';
         

            $_data = array( 'error'     => 'tpl_error',
                            'user_data' => array('forename' => commonUtil::stripSlashes($_POST['forename']),
                                                 'lastname' => commonUtil::stripSlashes($_POST['lastname']),
                                                 'email'    => commonUtil::stripSlashes($_POST['email']),
                                                 'login'    => commonUtil::stripSlashes($_POST['login']),
                                                 'passwd'   => commonUtil::stripSlashes($_POST['passwd']),
                                                 'rights'   => 1,
                                                 'status'   => 1));
            
            // add new user data
            if(FALSE !== ($user_id = M( MOD_USER,
                                        'add',
                                        $_data )))
            {
                @header('Location: '.SF_BASE_LOCATION.'/'.SF_CONTROLLER.'?admin=1&m=user&sec=edituser&uid='.$user_id);
                exit; 
            }
            else
            {
                $this->_reset_old_fields_data();
                return TRUE;                
            }
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
        $this->B->form_forename = htmlspecialchars(commonUtil::stripSlashes($_POST['forename']));
        $this->B->form_lastname = htmlspecialchars(commonUtil::stripSlashes($_POST['lastname']));
        $this->B->form_email    = htmlspecialchars(commonUtil::stripSlashes($_POST['email']));
        $this->B->form_login    = htmlspecialchars(commonUtil::stripSlashes($_POST['login']));
        $this->B->form_passwd   = htmlspecialchars(commonUtil::stripSlashes($_POST['passwd']));          
    }           
}

?>
