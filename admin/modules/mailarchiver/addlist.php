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
$B->form_name = '';
$B->form_emailuser   = '';
$B->form_email       = '';
$B->form_emailpasswd = '';
$B->form_description   = '';
$B->form_status   = '';

// Check if some form fields are empty
if(
    empty($_POST['name'])||
    empty($_POST['emailuser'])||
    empty($_POST['email'])||
    empty($_POST['emailpasswd'])||
    empty($_POST['description']))
{
    // if empty assign form field with old values
    $B->form_name        = htmlspecialchars($B->util->stripSlashes($_POST['name']));
    $B->form_emailuser   = htmlspecialchars($B->util->stripSlashes($_POST['emailuser']));
    $B->form_email       = htmlspecialchars($B->util->stripSlashes($_POST['email']));
    $B->form_emailpasswd = htmlspecialchars($B->util->stripSlashes($_POST['emailpasswd']));
    $B->form_description = htmlspecialchars($B->util->stripSlashes($_POST['description']));
    $B->form_status      = $_POST['status'];
    
    $B->form_error = 'You have fill out all fields!';
}
else
{
    // get list messages attachment folder string
    $list_folder = $B->util->unique_md5_str();

    if(!@mkdir(SF_BASE_DIR . '/data/mailarchiver/' . $list_folder, 0775))
    {
        $B->form_error = 'Cannot create list messages attachment folder! Contact the administrator.';
    }

    // add new user
    $B->tmp_data = array('name'        => $B->conn->qstr($B->util->stripSlashes($_POST['name']),       magic_quotes_runtime()),
                         'emailuser'   => $B->conn->qstr($B->util->stripSlashes($_POST['emailuser']),  magic_quotes_runtime()),
                         'email'       => $B->conn->qstr($B->util->stripSlashes($_POST['email']),      magic_quotes_runtime()),
                         'emailpasswd' => $B->conn->qstr($B->util->stripSlashes($_POST['emailpasswd']),magic_quotes_runtime()),
                         'description' => $B->conn->qstr($B->util->stripSlashes($_POST['description']),magic_quotes_runtime()),
                         'folder'      => $B->conn->qstr($list_folder),
                         'status'      => (int)$_POST['status']);
             
    if((FALSE === $B->form_error) && (FALSE !== $B->marchiver->add_list($B->tmp_data)))
    {
        @header('Location: index.php?m=MAILARCHIVER');
        exit;
    }
}

?>
