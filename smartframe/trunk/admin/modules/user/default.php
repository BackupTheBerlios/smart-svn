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
 * Default (entry) page of the user module
 *
 */

// Check if this file is included in the Smart environement
//
if (!defined('SF_SECURE_INCLUDE'))
{
    die('No Permission on'. __FILE__);
}

// set the base template for this module
$B->section = SF_BASE_DIR . '/admin/modules/user/templates/default.tpl.php';    

if(isset($_REQUEST['usr_rights']))
{
    $B->tmp_rights = $_REQUEST['usr_rights'];
}
else
{
    $B->tmp_rights = FALSE;
}

$B->tmp_fields = array('uid','rights','status','email','login','forename','lastname');
$B->all_users = $B->user->get_users( $B->tmp_fields, $B->tmp_rights );  

?>
