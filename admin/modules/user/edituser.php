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
    die('No Permission on '. __FILE__);
}

if(FALSE == rights::ask_access_to_modify_user( (int)$_REQUEST['uid'] ))
{
    @header('Location: index.php?m=USER');
    exit;
}

$B->form_error = FALSE;

if($_POST['deluser'] == 1)
{
    if(FALSE == rights::is_login_user( (int) $_POST['uid']) )
    {
        $B->user->delete_user( (int) $_POST['uid'] );
        @header('Location: index.php?m=USER');
        exit;  
    }
    else
    {        
        $B->form_error = 'You can remove your own user account!';    
    }
}

if(isset($_POST['edituser']))
{  
    if(
        empty($_POST['forename'])||
        empty($_POST['lastname'])||
        empty($_POST['email']))
    {        
        $B->form_error = 'You have fill out all fields!';
    }
    else
    {
        // Check if you want to change your own rights or status
        if( ($_POST['rights_orig'] != (int)$_POST['rights']) || ($_POST['status_orig'] != (int)$_POST['status']) )
        {
            if(TRUE == rights::is_login_user( (int) $_POST['uid']))
                $B->form_error = 'You can not change your own rights or status!';
        }
        
        // Check if you can change rights to the demanded level
        if( (FALSE == $B->form_error) && ($_POST['rights_orig'] != (int)$_POST['rights']) )
        {
            if(FALSE == rights::ask_set_rights ( (int) $_POST['uid'], (int)$_POST['rights'] ) )
                $B->form_error = 'You can not change to this rights level!';
        }

        // Check if you can change status of this user
        if( (FALSE == $B->form_error) && ($_POST['status_orig'] != (int)$_POST['status']) )
        {
            if(FALSE == rights::ask_set_status ( (int) $_POST['uid'] ))
                $B->form_error = 'You can not change status of this user!';
        }
        
        if(empty($B->form_error))
        {
            $B->tmp_data = array('forename' => $B->dbdata->escapeString($_POST['forename']),
                                 'lastname' => $B->dbdata->escapeString($_POST['lastname']),
                                 'email'    => $B->dbdata->escapeString($_POST['email']),
                                 'rights'   => (int)$_POST['rights'],
                                 'status'   => (int)$_POST['status']);
            
            if(!empty($_POST['passwd']))
            {
                $B->tmp_data['passwd'] == md5($_POST['passwd']);
            }
            
            if(FALSE != $B->user->update_user( (int)$_REQUEST['uid'], $B->tmp_data))
            {
                @header('Location: index.php?m=USER');
                exit;
            }
            else
            {
            
                $B->form_error = 'This login exist. Chose a other one!';
            }
        }
    }
}
else
{
    $B->tmp_fields = array('uid','rights','status','email','login','forename','lastname');
    $B->tpl_data = $B->user->get_user( (int)$_REQUEST['uid'], $B->tmp_fields );
    unset($B->tmp_fields);
}

// if error restore the form fields values
if(!empty($B->form_error))
{
    $B->tpl_data['forename'] = htmlentities($B->util->stripSlashes($_POST['forename']));
    $B->tpl_data['lastname'] = htmlentities($B->util->stripSlashes($_POST['lastname']));
    $B->tpl_data['email']    = htmlentities($B->util->stripSlashes($_POST['email']));
    $B->tpl_data['login']    = htmlentities($B->util->stripSlashes($_POST['login']));
    $B->tpl_data['passwd']   = htmlentities($B->util->stripSlashes($_POST['passwd']));
    $B->tpl_data['rights']   = $_POST['rights'];
    $B->tpl_data['status']   = $_POST['status'];        
}

?>
