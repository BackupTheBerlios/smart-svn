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
    $this->B->tmp_data = array('forename' => $this->B->db->quoteSmart(commonUtil::stripSlashes($_POST['forename'])),
                         'lastname' => $this->B->db->quoteSmart(commonUtil::stripSlashes($_POST['lastname'])),
                         'email'    => $this->B->db->quoteSmart(commonUtil::stripSlashes($_POST['email'])),
                         'login'    => $this->B->db->quoteSmart(commonUtil::stripSlashes($_POST['login'])),
                         'passwd'   => $this->B->db->quoteSmart(md5($_POST['passwd'])),
                         'rights'   => (int)$_POST['rights'],
                         'status'   => (int)$_POST['status']);
                  
    if(FALSE !== $this->B->user->add_user($this->B->tmp_data))
    {
        @header('Location: '.SF_BASE_LOCATION.'/admin/index.php?m=USER');
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

?>
