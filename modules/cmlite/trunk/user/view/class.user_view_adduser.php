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
            $this->B->form_error = FALSE;
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

            // get new user data
            $this->B->tmp_data = array( 
                        'forename' => $this->B->db->quoteSmart(commonUtil::stripSlashes($_POST['forename'])),
                        'lastname' => $this->B->db->quoteSmart(commonUtil::stripSlashes($_POST['lastname'])),
                        'email'    => $this->B->db->quoteSmart(commonUtil::stripSlashes($_POST['email'])),
                        'login'    => $this->B->db->quoteSmart(commonUtil::stripSlashes($_POST['login'])),
                        'passwd'   => $this->B->db->quoteSmart(md5($_POST['passwd'])),
                        'rights'   => 1,
                        'status'   => 1);

            // check if a user instance exists
            if(!is_object($this->B->user))
            {
                // the user class
                include_once SF_BASE_DIR . 'modules/user/includes/class.user.php';        
                //User Class instance
                $this->B->user = & new user;  
            }
            
            // add new user data
            if(FALSE !== ($user_id = $this->B->user->add_user($this->B->tmp_data)))
            {
                @header('Location: '.SF_BASE_LOCATION.'/index.php?admin=1&m=user&sec=edituser&uid='.$user_id);
                exit; 
            }
            else
            {
                $this->B->form_error = 'Unknown error! Please try again.';
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
            $this->B->form_error = 'You have fill out all fields!';
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
