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
 * user_view_adduser class of the template "adduser.tpl.php"
 *
 */
 
class user_view_adduser
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
    function user_view_adduser()
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
     * Execute the view of the template "adduser.tpl.php"
     *
     * @return bool true on success else false
     */
    function perform()
    {    
        // check permission to add new user
        $this->B->F( USER_FILTER,
                     'permission',
                     array( 'action'  => 'add'));
 
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

            // check if required fields are empty
            if (FALSE == $this->_check_empty_fields())
            {
                $this->_reset_old_fields_data();
                return TRUE;
            }            

            $_data = array( 'error'     => 'tpl_error',
                            'user_data' => array('forename' => $this->B->db->quote(commonUtil::stripSlashes($_POST['forename'])),
                                                 'lastname' => $this->B->db->quote(commonUtil::stripSlashes($_POST['lastname'])),
                                                 'email'    => $this->B->db->quote(commonUtil::stripSlashes($_POST['email'])),
                                                 'login'    => $this->B->db->quote(commonUtil::stripSlashes($_POST['login'])),
                                                 'passwd'   => $this->B->db->quote(md5($_POST['passwd'])),
                                                 'rights'   => 1,
                                                 'status'   => 1));
            
            // add new user data
            if(FALSE !== ($user_id = $this->B->M( MOD_USER,
                                                  'add',
                                                  $_data )))
            {
                @header('Location: '.SF_BASE_LOCATION.'/index.php?admin=1&m=user&sec=edituser&uid='.$user_id);
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
     * check if required fields are empty
     *
     * @return bool true on success else false
     * @access privat
     */       
    function _check_empty_fields()
    {
        // check if some fields are empty
        if(
           empty($_POST['forename'])||
           empty($_POST['lastname'])||
           empty($_POST['email'])||
           empty($_POST['login'])||
           empty($_POST['passwd']))
        {        
            $this->B->tpl_error = 'You have fill out all fields!';
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
        $this->B->form_forename = htmlspecialchars(commonUtil::stripSlashes($_POST['forename']));
        $this->B->form_lastname = htmlspecialchars(commonUtil::stripSlashes($_POST['lastname']));
        $this->B->form_email    = htmlspecialchars(commonUtil::stripSlashes($_POST['email']));
        $this->B->form_login    = htmlspecialchars(commonUtil::stripSlashes($_POST['login']));
        $this->B->form_passwd   = htmlspecialchars(commonUtil::stripSlashes($_POST['passwd']));          
    }    
}

?>
