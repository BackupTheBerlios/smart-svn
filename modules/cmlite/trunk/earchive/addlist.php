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
 * this file is included in the class.EARCHIVE_SYS_LOAD_MODULE.php file
 *
 */

// Check if this file is included in the Smart environement
//
if (!defined('SF_SECURE_INCLUDE'))
{
    die('No Permission on ' . __FILE__);
}

// check if the user of this request have rights
if(FALSE == earchive_rights::ask_access_to_list())
{
    @header('Location: '.SF_BASE_LOCATION.'/admin/index.php');
    exit;
}

// Init form field values
$this->B->form_error       = FALSE;
$this->B->form_name        = '';
$this->B->form_emailserver = '';
$this->B->form_email       = '';
$this->B->form_description = '';
$this->B->form_status      = '';

// Check if some form fields are empty
if(
    empty($_POST['name'])||
    empty($_POST['emailserver'])||
    empty($_POST['email']))
{
    // if empty assign form field with old values
    $this->B->form_name        = htmlspecialchars(commonUtil::stripSlashes($_POST['name']));
    $this->B->form_emailserver = htmlspecialchars(commonUtil::stripSlashes($_POST['emailserver']));
    $this->B->form_email       = htmlspecialchars(commonUtil::stripSlashes($_POST['email']));
    $this->B->form_description = htmlspecialchars(commonUtil::stripSlashes($_POST['description']));
    $this->B->form_status      = $_POST['status'];
    
    $this->B->form_error = 'You have fill out all fields!';
}
else
{
    // get list messages attachment folder string
    $list_folder = commonUtil::unique_md5_str();

    if(!@mkdir(SF_BASE_DIR . '/data/earchive/' . $list_folder, SF_DIR_MODE))
    {
        $this->B->form_error = 'Cannot create list messages attachment folder! Contact the administrator.';
    }

    // add new email list
    $this->B->tmp_data = array('name'  => $this->B->db->quoteSmart(commonUtil::stripSlashes($_POST['name'])),
                         'emailserver' => $this->B->db->quoteSmart(commonUtil::stripSlashes($_POST['emailserver'])),
                         'email'       => $this->B->db->quoteSmart(commonUtil::stripSlashes($_POST['email'])),
                         'description' => $this->B->db->quoteSmart(commonUtil::stripSlashes($_POST['description'])),
                         'folder'      => $this->B->db->quoteSmart($list_folder),
                         'status'      => (int)$_POST['status']);
             
    if((FALSE === $this->B->form_error) && (FALSE !== $this->B->earchive->add_list($this->B->tmp_data)))
    {
        @header('Location: '.SF_BASE_LOCATION.'/admin/index.php?m=EARCHIVE');
        exit;
    }
}

?>
