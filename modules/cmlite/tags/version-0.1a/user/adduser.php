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
 * add user script
 *
 */

// Check if this file is included in the Smart environement
//
if (!defined('SF_SECURE_INCLUDE'))
{
    die('No Permission on ' . __FILE__);
}

// Init form field values
$B->form_error = FALSE;
$B->form_forename = '';
$B->form_lastname = '';
$B->form_email    = '';
$B->form_login    = '';
$B->form_passwd   = '';
$B->form_rights   = '';
$B->form_status   = '';

// Check if some form fields are empty
if(
    empty($_POST['forename'])||
    empty($_POST['lastname'])||
    empty($_POST['email'])||
    empty($_POST['login'])||
    empty($_POST['passwd']))
{
    // if empty assign form field with old values
    $B->form_forename = htmlspecialchars($B->util->stripSlashes($_POST['forename']));
    $B->form_lastname = htmlspecialchars($B->util->stripSlashes($_POST['lastname']));
    $B->form_email    = htmlspecialchars($B->util->stripSlashes($_POST['email']));
    $B->form_login    = htmlspecialchars($B->util->stripSlashes($_POST['login']));
    $B->form_passwd   = htmlspecialchars($B->util->stripSlashes($_POST['passwd']));
    $B->form_rights   = $_POST['rights'];
    $B->form_status   = $_POST['status'];
    
    $B->form_error = 'You have fill out all fields!';
}
else
{
    // add new user
    $B->tmp_data = array('forename' => $B->db->quoteSmart($B->util->stripSlashes($_POST['forename'])),
                         'lastname' => $B->db->quoteSmart($B->util->stripSlashes($_POST['lastname'])),
                         'email'    => $B->db->quoteSmart($B->util->stripSlashes($_POST['email'])),
                         'login'    => $B->db->quoteSmart($B->util->stripSlashes($_POST['login'])),
                         'passwd'   => $B->db->quoteSmart(md5($_POST['passwd'])),
                         'rights'   => (int)$_POST['rights'],
                         'status'   => (int)$_POST['status']);
                  
    if(FALSE !== $B->user->add_user($B->tmp_data))
    {
        @header('Location: '.SF_BASE_LOCATION.'/admin/index.php?m=USER');
        exit;
    }
    else
    {
        // on error during add user
        $B->form_forename = htmlspecialchars($B->util->stripSlashes($_POST['forename']));
        $B->form_lastname = htmlspecialchars($B->util->stripSlashes($_POST['lastname']));
        $B->form_email    = htmlspecialchars($B->util->stripSlashes($_POST['email']));
        $B->form_login    = htmlspecialchars($B->util->stripSlashes($_POST['login']));
        $B->form_passwd   = htmlspecialchars($B->util->stripSlashes($_POST['passwd']));
        $B->form_rights   = $_POST['rights'];
        $B->form_status   = $_POST['status'];   
    
        $B->form_error = 'This login exist. Chose an other one!';
    }
}

?>
