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
    die('No Permission on ' . __FILE__);
}

// the user class
include_once SF_BASE_DIR . '/admin/modules/earchive/class.earchive.php';

//User Class instance
$B->earchive = & new earchive;

// set the base template for this module
$B->module = SF_BASE_DIR . '/admin/modules/earchive/templates/index.tpl.php';  

// Switch to module features
switch($_REQUEST['mf'])
{
    case 'edit_list':
        include( SF_BASE_DIR."/admin/modules/earchive/editlist.php" ); 
        // set the base template for this module feature
        $B->section = SF_BASE_DIR . '/admin/modules/earchive/templates/editlist.tpl.php';    
        break;
    case 'add_list':
    /*
        // have rights to add users?
        if(FALSE == rights::ask_access_to_add_user ())
        {
            @header('Location: index.php?m=earchive');
            exit;
        }    
        */
        if(isset($_POST['addlist']))
        {
            include( SF_BASE_DIR."/admin/modules/earchive/addlist.php" ); 
        }
        // set the base template for this module feature
        $B->section = SF_BASE_DIR . '/admin/modules/earchive/templates/addlist.tpl.php';
        break;
    case 'del_list':
        // Include default
        include( SF_BASE_DIR."/admin/modules/earchive/dellist.php" );     
        break;      
    default:
        // set the base template for this module
        $B->section = SF_BASE_DIR . '/admin/modules/earchive/templates/default.tpl.php';    

        $B->tmp_fields = array('lid','status','email','name','description');
        $B->all_lists = $B->earchive->get_lists( $B->tmp_fields );  
}

?>
