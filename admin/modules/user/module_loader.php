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
$B->module = SF_BASE_DIR . '/admin/modules/user/templates/index.tpl.php';    
// Assign module handler name
$B->this_module = EVT_HANDLER_USER;    

// Switch to module features
switch($_REQUEST['mf'])
{
    case 'grp_perm':
    
        break;
    case 'usr_perm':
    
        break;
    case 'add_grp':
    
        break;
    case 'del_grp':
    
        break;
    case 'add_usr':
    
        break;
    case 'del_usr':
    
        break;
    default:
        // Include default
        include( SF_BASE_DIR."/admin/modules/user/default.php" );
}

?>
