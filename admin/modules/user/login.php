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
 * Default login page of the user module
 *
 */

// Check if this file is included in the Smart environement
//
if (!defined('SF_SECURE_INCLUDE'))
{
    die('No Permission on'. __FILE__);
}

$B->login = FALSE;

// Check login data
if(isset($_POST['login']))
{
    if(FALSE !== ($rights = $B->auth->checklogin($_POST['login_name'], $_POST['password'])))
    {
        if($rights > 1)
            @header('Location: index.php');
        else
            @header('Location: ../index.php');
        exit;
    }
}

// load the login template
include SF_BASE_DIR . '/admin/modules/user/templates/login.tpl.php';    
exit; 

?>
