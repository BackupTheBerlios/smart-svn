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
 * edit email message data script
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

// Modify list data
if(isset($_POST['editmessage']))
{  
    // check if some fields are empty
    if(
        empty($_POST['subject'])||
        empty($_POST['body']))
    {        
        $B->form_error = 'You have fill out all fields!';
    }
    else
    {
        
        // add new user
        $B->tmp_data = array('subject'     => $B->db->quoteSmart($B->util->stripSlashes($_POST['subject'])),
                             'body'        => $B->db->quoteSmart($B->util->stripSlashes($_POST['body'])));
        
        // delete attachements on demande
        if(count($_POST['aid']) > 0)
        {
            $fields = array('lid','folder');
            // message folder
            $data   = $B->earchive->get_message( (int)$_REQUEST['mid'], $fields );
            // list folder
            $l_data = $B->earchive->get_list( $data['lid'], $fields );        
            
            $path = SF_BASE_DIR.'/data/earchive/'.$l_data['folder'].'/'.$data['folder'];
            
            $fields = array('file');
            
            // get attachment files and delete them
            foreach($_POST['aid'] as $aid)
            {
                $file = $B->earchive->get_attach( $aid, $fields );
                @unlink($path.'/'.$file['file']);           
            }
        }
        
        // update message data
        if(FALSE !== $B->earchive->update_message((int)$_REQUEST['mid'], $B->tmp_data))
        {
            @header('Location: index.php?m=EARCHIVE&mf=show_mess&lid='.(int)$_REQUEST["lid"].'&pageID='.(int)$_REQUEST["pageID"]);
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
    $B->tmp_fields = array('mid','lid','subject','sender','body','folder');
    $B->tpl_data = $B->earchive->get_message( (int)$_REQUEST['mid'], $B->tmp_fields );
    unset($B->tmp_fields);

    // get list data
    $B->tmp_fields = array('aid','file','size','type');    
    $B->tpl_attach = $B->earchive->get_message_attach( (int)$_REQUEST['mid'], $B->tmp_fields );
    unset($B->tmp_fields);
}

// if error restore the form fields values
if(!empty($B->form_error))
{
    // if empty assign form field with old values
    $B->tpl_data['subject'] = $B->util->stripSlashes($_POST['subject']);
    $B->tpl_data['body']    = $B->util->stripSlashes($_POST['body']);
    $B->tpl_data['mid']     = $_POST['mid'];
    $B->tpl_data['lid']     = $_POST['lid'];
    $B->tpl_data['pageID']  = $_POST['pageID'];
}

?>
