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
 * USER_SYS_LOAD_MODULE class 
 *
 */
 
class USER_SYS_LOAD_MODULE
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
    function USER_SYS_LOAD_MODULE()
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
     * Perform on admin requests for this module
     *
     * @param array $data
     */
    function perform( $data )
    {    
        // User rights class
        include(SF_BASE_DIR.'/admin/modules/user/class.rights.php');  
        
        // the user class
        include_once SF_BASE_DIR . '/admin/modules/user/class.user.php';

        //User Class instance
        $this->B->user = & new user;

        // set the base template for this module
        $this->B->module = SF_BASE_DIR . '/admin/modules/user/templates/index.tpl.php'; 

        // Assign template var : module handler name
        $this->B->this_module = EVT_HANDLER_USER;    

        // Switch to module features
        switch($_REQUEST['mf'])
        {
            case 'edit_usr':
                include( SF_BASE_DIR."/admin/modules/user/edituser.php" ); 
                // set the base template for this module feature
                $this->B->section = SF_BASE_DIR . '/admin/modules/user/templates/edituser.tpl.php';    
                break;
            case 'add_usr':
                // have rights to add users?
                if(FALSE == rights::ask_access_to_add_user ())
                {
                    @header('Location: '.SF_BASE_LOCATION.'/admin/index.php?m=USER');
                    exit;
                }    
        
                if(isset($_POST['adduser']))
                {
                    include( SF_BASE_DIR."/admin/modules/user/adduser.php" ); 
                }
                // set the base template for this module feature
                $this->B->section = SF_BASE_DIR . '/admin/modules/user/templates/adduser.tpl.php';
                break;
            case 'del_usr':
                // Include default
                include( SF_BASE_DIR."/admin/modules/user/deluser.php" );     
                break;      
            default:
                // set the base template for this module
                $this->B->section = SF_BASE_DIR . '/admin/modules/user/templates/default.tpl.php';    
  
                if(isset($_REQUEST['usr_rights']))
                    $this->B->tmp_rights = $_REQUEST['usr_rights'];
                else
                    $this->B->tmp_rights = FALSE;

                $this->B->tmp_fields = array('uid','rights','status','email','login','forename','lastname');
                $this->B->all_users = $this->B->user->get_users( $this->B->tmp_fields, $this->B->tmp_rights );  
        }
       
    } 
}

?>
