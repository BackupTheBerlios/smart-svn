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
 * user_view_adduser class of the template "login.tpl.php"
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
     * Execute the view of the template "index.tpl.php"
     * create the template variables
     * and listen to an action
     *
     * @return bool true on success else false
     */
    function perform()
    {
        // User rights class
        include(SF_BASE_DIR.'modules/user/includes/class.rights.php');  

        // have rights to add users?
        if(FALSE == rights::ask_access_to_add_user ())
        {
            @header('Location: '.SF_BASE_LOCATION.'/admin/index.php?admin=1');
            exit;
        } 
        
        // the user class
        include_once SF_BASE_DIR . 'modules/user/includes/class.user.php';

        //User Class instance
        $this->B->user = & new user;  

        
        // Check login data
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

            // Check if some form fields are empty
            if(
                empty($_POST['forename'])||
                empty($_POST['lastname'])||
                empty($_POST['email'])||
                empty($_POST['login'])||
                empty($_POST['passwd']))
            {
                // if empty assign form field with old values
                $this->B->form_forename = htmlspecialchars(commonUtil::stripSlashes($_POST['forename']));
                $this->B->form_lastname = htmlspecialchars(commonUtil::stripSlashes($_POST['lastname']));
                $this->B->form_email    = htmlspecialchars(commonUtil::stripSlashes($_POST['email']));
                $this->B->form_login    = htmlspecialchars(commonUtil::stripSlashes($_POST['login']));
                $this->B->form_passwd   = htmlspecialchars(commonUtil::stripSlashes($_POST['passwd']));
                $this->B->form_rights   = $_POST['rights'];
                $this->B->form_status   = $_POST['status'];
    
                $this->B->form_error = 'You have fill out all fields!';
            }
            else
            {
                // add new user
                $this->B->tmp_data = array( 
                        'forename' => $this->B->db->quoteSmart(commonUtil::stripSlashes($_POST['forename'])),
                        'lastname' => $this->B->db->quoteSmart(commonUtil::stripSlashes($_POST['lastname'])),
                        'email'    => $this->B->db->quoteSmart(commonUtil::stripSlashes($_POST['email'])),
                        'login'    => $this->B->db->quoteSmart(commonUtil::stripSlashes($_POST['login'])),
                        'passwd'   => $this->B->db->quoteSmart(md5($_POST['passwd'])),
                        'rights'   => (int)$_POST['rights'],
                        'status'   => (int)$_POST['status']);
                  
                if(FALSE !== $this->B->user->add_user($this->B->tmp_data))
                {
                    @header('Location: '.SF_BASE_LOCATION.'/index.php?admin=1&m=user');
                    exit;
                }
                else
                {
                    // on error during add user
                    $this->B->form_forename = htmlspecialchars(commonUtil::stripSlashes($_POST['forename']));
                    $this->B->form_lastname = htmlspecialchars(commonUtil::stripSlashes($_POST['lastname']));
                    $this->B->form_email    = htmlspecialchars(commonUtil::stripSlashes($_POST['email']));
                    $this->B->form_login    = htmlspecialchars(commonUtil::stripSlashes($_POST['login']));
                    $this->B->form_passwd   = htmlspecialchars(commonUtil::stripSlashes($_POST['passwd']));
                    $this->B->form_rights   = $_POST['rights'];
                    $this->B->form_status   = $_POST['status'];   
    
                    $this->B->form_error = 'This login exist. Chose an other one!';
                }
            }
        }
            
        return TRUE;
    }    
}

?>
