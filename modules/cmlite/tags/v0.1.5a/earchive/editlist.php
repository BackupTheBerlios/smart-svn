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
if(FALSE == earchive_rights::ask_access_to_list())
{
    @header('Location: '.SF_BASE_LOCATION.'/admin/index.php');
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
    if(TRUE == earchive_rights::ask_access_to_delete_list())
    {
        $B->earchive->delete_list((int)$_REQUEST['lid']);
        
        // delete word index of this list
        include_once(SF_BASE_DIR.'/admin/include/class.sfWordIndexer.php');        
        word_indexer::delete_words( 'earchive_words_crc32', 'lid', (int)$_REQUEST['lid'] );
        
        @header('Location: index.php?m=EARCHIVE');
        exit;
    }    
    else
    {        
        $B->form_error = 'You cant remove this list';    
    }
}

// Modify list data
if(isset($_POST['editlist']))
{  
    // check if some fields are empty
    if(
        empty($_POST['name'])||
        empty($_POST['emailserver'])||
        empty($_POST['email']))
    {        
        $B->form_error = 'You have fill out all fields!';
    }
    else
    {
        
        // add new user
        $B->tmp_data = array('name'        => $B->db->quoteSmart(commonUtil::stripSlashes($_POST['name'])),
                             'emailserver' => $B->db->quoteSmart(commonUtil::stripSlashes($_POST['emailserver'])),
                             'email'       => $B->db->quoteSmart(commonUtil::stripSlashes($_POST['email'])),
                             'description' => $B->db->quoteSmart(commonUtil::stripSlashes($_POST['description'])),
                             'status'      => (int)$_POST['status']);
            
        // update list data
        if(FALSE !== $B->earchive->update_list((int)$_REQUEST['lid'], $B->tmp_data))
        {
            @header('Location: index.php?m=EARCHIVE');
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
    // get list data
    $B->tmp_fields = array('lid','name','status','email','emailserver','description');
    $B->tpl_data = $B->earchive->get_list( (int)$_REQUEST['lid'], $B->tmp_fields );
    unset($B->tmp_fields);
}

// if error restore the form fields values
if(!empty($B->form_error))
{
    // if empty assign form field with old values
    $B->tpl_data['name']        = commonUtil::stripSlashes($_POST['name']);
    $B->tpl_data['emailserver'] = commonUtil::stripSlashes($_POST['emailserver']);
    $B->tpl_data['email']       = commonUtil::stripSlashes($_POST['email']);
    $B->tpl_data['description'] = commonUtil::stripSlashes($_POST['description']);
    $B->tpl_data['status']      = $_POST['status'];     
}

?>
