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
 * edit email list data script
 *
 */

// Check if this file is included in the Smart environement
//
if (!defined('SF_SECURE_INCLUDE'))
{
    die('No Permission on '. __FILE__);
}

// check user rights to access the list
if(FALSE == mailarchiver_rights::ask_access_to_list())
{
    @header('Location: index.php');
    exit;
}

// init 
$B->form_error = FALSE;

// delete list
if($_POST['dellist'] == 1)
{
    // check if the user of this request try to delete a list
    // with rights other than administrator 5.
    //
    if(TRUE == mailarchiver_rights::ask_access_to_delete_list())
    {
        $B->marchiver->delete_list((int)$_REQUEST['lid']);
        @header('Location: index.php?m=MAILARCHIVER');
        exit;
    }    
    else
    {        
        $B->form_error = 'You cant remove this list';    
    }
}

// Modify user data
if(isset($_POST['editlist']))
{  
    // check if some fields are empty
    if(
        empty($_POST['name'])||
        empty($_POST['emailuser'])||
        empty($_POST['email'])||
        empty($_POST['emailpasswd']))
    {        
        $B->form_error = 'You have fill out all fields!';
    }
    else
    {
        
        // add new user
        $B->tmp_data = array('name'        => $B->conn->qstr($B->util->stripSlashes($_POST['name']),       magic_quotes_runtime()),
                             'emailuser'   => $B->conn->qstr($B->util->stripSlashes($_POST['emailuser']),  magic_quotes_runtime()),
                             'email'       => $B->conn->qstr($B->util->stripSlashes($_POST['email']),      magic_quotes_runtime()),
                             'emailpasswd' => $B->conn->qstr($B->util->stripSlashes($_POST['emailpasswd']),magic_quotes_runtime()),
                             'description' => $B->conn->qstr($B->util->stripSlashes($_POST['description']),magic_quotes_runtime()),
                             'status'      => (int)$_POST['status']);
            
        // update user data
        if(FALSE !== $B->marchiver->update_list((int)$_REQUEST['lid'], $B->tmp_data))
        {
            @header('Location: index.php?m=MAILARCHIVER');
            exit;
        }
        else
        {
            
            $B->form_error = 'Error during update. Try again!';
        }
    }
}
else
{
    // get user data
    $B->tmp_fields = array('lid','name','status','email','emailuser','emailpasswd','description');
    $B->tpl_data = $B->marchiver->get_list( (int)$_REQUEST['lid'], $B->tmp_fields );
    unset($B->tmp_fields);
}

// if error restore the form fields values
if(!empty($B->form_error))
{
    // if empty assign form field with old values
    $B->tpl_data['name']        = htmlspecialchars($B->util->stripSlashes($_POST['name']));
    $B->tpl_data['emailuser']   = htmlspecialchars($B->util->stripSlashes($_POST['emailuser']));
    $B->tpl_data['email']       = htmlspecialchars($B->util->stripSlashes($_POST['email']));
    $B->tpl_data['emailpasswd'] = htmlspecialchars($B->util->stripSlashes($_POST['emailpasswd']));
    $B->tpl_data['description'] = htmlspecialchars($B->util->stripSlashes($_POST['description']));
    $B->tpl_data['status']      = $_POST['status'];     
}

?>
