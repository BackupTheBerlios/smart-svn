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
 * edit user data script
 *
 */

// Check if this file is included in the Smart environement
//
if (!defined('SF_SECURE_INCLUDE'))
{
    die('No Permission on '. __FILE__);
}

// check if the user of this request have rights to modify this user data
if(FALSE == rights::ask_access_to_modify_user( (int)$_REQUEST['uid'] ))
{
    @header('Location: '.SF_BASE_LOCATION.'/admin/index.php?m=USER');
    exit;
}

// init 
$this->B->form_error = FALSE;

// delete user
if($_POST['deluser'] == 1)
{
    // check if the user of this request try to delete the own account
    // This shouldnt be possible
    //
    if(FALSE == rights::is_login_user( (int) $_POST['uid']) )
    {
        $this->B->user->delete_user( (int) $_POST['uid'] );
        @header('Location: '.SF_BASE_LOCATION.'/admin/index.php?m=USER');
        exit;  
    }
    else
    {        
        $this->B->form_error = 'You can remove your own user account!';    
    }
}

// Modify user data
if(isset($_POST['edituser']))
{  
    // check if some fields are empty
    if(
        empty($_POST['forename'])||
        empty($_POST['lastname'])||
        empty($_POST['email']))
    {        
        $this->B->form_error = 'You have fill out all fields!';
    }
    else
    {
        // Check if you want to change your own rights or status
        if( ($_POST['rights_orig'] != (int)$_POST['rights']) || ($_POST['status_orig'] != (int)$_POST['status']) )
        {
            if(TRUE == rights::is_login_user( (int) $_POST['uid']))
                $this->B->form_error = 'You can not change your own rights or status!';
        }
        
        // Check if you can change rights to the demanded level
        if( (FALSE == $this->B->form_error) && ($_POST['rights_orig'] != (int)$_POST['rights']) )
        {
            if(FALSE == rights::ask_set_rights ( (int) $_POST['uid'], (int)$_POST['rights'] ) )
                $this->B->form_error = 'You can not change to this rights level!';
        }

        // Check if you can change status of this user
        if( (FALSE == $this->B->form_error) && ($_POST['status_orig'] != (int)$_POST['status']) )
        {
            if(FALSE == rights::ask_set_status ( (int) $_POST['uid'] ))
                $this->B->form_error = 'You can not change status of this user!';
        }
        
        // if no error occure, proceed ...
        if(empty($this->B->form_error))
        {
            $this->B->tmp_data = array('forename' => $this->B->db->quoteSmart(commonUtil::stripSlashes($_POST['forename'])),
                                 'lastname' => $this->B->db->quoteSmart(commonUtil::stripSlashes($_POST['lastname'])),
                                 'email'    => $this->B->db->quoteSmart(commonUtil::stripSlashes($_POST['email'])),
                                 'rights'   => (int)$_POST['rights'],
                                 'status'   => (int)$_POST['status']);
            
            // update password if it isnt empty
            if(!empty($_POST['passwd']))
            {
                $this->B->tmp_data['passwd'] == $this->B->db->quoteSmart(md5($_POST['passwd']));
            }
            
            // update user data
            if(FALSE != $this->B->user->update_user( (int)$_REQUEST['uid'], $this->B->tmp_data))
            {
                @header('Location: '.SF_BASE_LOCATION.'/admin/index.php?m=USER');
                exit;
            }
            else
            {
            
                $this->B->form_error = 'This login exist. Chose a other one!';
            }
        }
    }
}
else
{
    // get user data
    $this->B->tmp_fields = array('uid','rights','status','email','login','forename','lastname');
    $this->B->tpl_data = $this->B->user->get_user( (int)$_REQUEST['uid'], $this->B->tmp_fields );
    unset($this->B->tmp_fields);
}

// if error restore the form fields values
if(!empty($this->B->form_error))
{
    $this->B->tpl_data['forename'] = htmlspecialchars(commonUtil::stripSlashes($_POST['forename']));
    $this->B->tpl_data['lastname'] = htmlspecialchars(commonUtil::stripSlashes($_POST['lastname']));
    $this->B->tpl_data['email']    = htmlspecialchars(commonUtil::stripSlashes($_POST['email']));
    $this->B->tpl_data['login']    = htmlspecialchars(commonUtil::stripSlashes($_POST['login']));
    $this->B->tpl_data['passwd']   = htmlspecialchars(commonUtil::stripSlashes($_POST['passwd']));
    $this->B->tpl_data['rights']   = $_POST['rights'];
    $this->B->tpl_data['status']   = $_POST['status'];        
}

?>
