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

$B->form_error = FALSE;
$B->form_forename = '';
$B->form_lastname = '';
$B->form_email    = '';
$B->form_login    = '';
$B->form_passwd   = '';
$B->form_rights   = '';
$B->form_status   = '';

if(
    empty($_POST['forename'])||
    empty($_POST['lastname'])||
    empty($_POST['email'])||
    empty($_POST['login'])||
    empty($_POST['passwd']))
{
    $B->form_forename = htmlentities($B->util->stripSlashes($_POST['forename']));
    $B->form_lastname = htmlentities($B->util->stripSlashes($_POST['lastname']));
    $B->form_email    = htmlentities($B->util->stripSlashes($_POST['email']));
    $B->form_login    = htmlentities($B->util->stripSlashes($_POST['login']));
    $B->form_passwd   = htmlentities($B->util->stripSlashes($_POST['passwd']));
    $B->form_rights   = $_POST['rights'];
    $B->form_status   = $_POST['status'];
    
    $B->form_error = 'You have fill out all fields!';
}
else
{
    $B->tmp_data = array('forename' => $B->dbdata->escapeString($_POST['forename']),
                         'lastname' => $B->dbdata->escapeString($_POST['lastname']),
                         'email'    => $B->dbdata->escapeString($_POST['email']),
                         'login'    => $B->dbdata->escapeString($_POST['login']),
                         'passwd'   => md5($_POST['passwd']),
                         'rights'   => (int)$_POST['rights'],
                         'status'   => (int)$_POST['status']);
                  
    if(FALSE != $B->user->add_user($B->tmp_data))
    {
        @header('Location: index.php?m=USER');
        exit;
    }
    else
    {
        $B->form_forename = htmlentities($B->util->stripSlashes($_POST['forename']));
        $B->form_lastname = htmlentities($B->util->stripSlashes($_POST['lastname']));
        $B->form_email    = htmlentities($B->util->stripSlashes($_POST['email']));
        $B->form_login    = htmlentities($B->util->stripSlashes($_POST['login']));
        $B->form_passwd   = htmlentities($B->util->stripSlashes($_POST['passwd']));
        $B->form_rights   = $_POST['rights'];
        $B->form_status   = $_POST['status'];   
    
        $B->form_error = 'This login exist. Chose a other one!';
    }
}

?>
