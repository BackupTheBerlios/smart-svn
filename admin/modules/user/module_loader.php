<?php
// ----------------------------------------------------------------------
// Smart (PHP Framework)
// Copyright (c) 2004
// by Armand Turpel < smart@open-publisher.net >
// http://smart.open-publisher.net/
// ----------------------------------------------------------------------
// LICENSE GPL
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------

/**
 * module loader of the user module
 */

// Check if this file is included in the Smart environement
//
if (!defined('SF_SECURE_INCLUDE'))
{
    die('No Permission on'. __FILE__);
}

// set the base template for this module
include_once SF_BASE_DIR . '/admin/modules/user/class.user.php';

//User Class instance
$B->user = & new user;

// set the base template for this module
$B->module = SF_BASE_DIR . '/admin/modules/user/templates/index.tpl.php'; 

// Assign module handler name
$B->this_module = EVT_HANDLER_USER;    

// Switch to module features
switch($_REQUEST['mf'])
{
    case 'edit_usr':
        // Include default
        include( SF_BASE_DIR."/admin/modules/user/edituser.php" );     
        break;
    case 'add_usr':
        if(isset($_POST['adduser']))
        {
            include( SF_BASE_DIR."/admin/modules/user/adduser.php" ); 
        }
        // set the base template for this module
        $B->section = SF_BASE_DIR . '/admin/modules/user/templates/adduser.tpl.php';
        break;
    case 'del_usr':
        // Include default
        include( SF_BASE_DIR."/admin/modules/user/deluser.php" );     
        break;      
    default:
        // set the base template for this module
        $B->section = SF_BASE_DIR . '/admin/modules/user/templates/default.tpl.php';    

        if(isset($_REQUEST['usr_rights']))
            $B->tmp_rights = $_REQUEST['usr_rights'];
        else
            $B->tmp_rights = FALSE;

        $B->tmp_fields = array('uid','rights','status','email','login','forename','lastname');
        $B->all_users = $B->user->get_users( $B->tmp_fields, $B->tmp_rights );  
}

?>
