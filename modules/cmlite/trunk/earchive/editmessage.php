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
$this->B->form_error = FALSE;

// Modify list data
if(isset($_POST['editmessage']))
{  
    // check if some fields are empty
    if(
        empty($_POST['subject'])||
        empty($_POST['body']))
    {        
        $this->B->form_error = 'You have fill out all fields!';
    }
    else
    {
        
        // add new user
        $this->B->tmp_data = array('subject'     => $this->B->db->quoteSmart(commonUtil::stripSlashes($_POST['subject'])),
                             'body'        => $this->B->db->quoteSmart(commonUtil::stripSlashes($_POST['body'])));
        
        // delete attachements on demande
        if(count($_POST['aid']) > 0)
        {
            $fields = array('lid','folder');
            // message folder
            $data   = $this->B->earchive->get_message( (int)$_REQUEST['mid'], $fields );
            // list folder
            $l_data = $this->B->earchive->get_list( $data['lid'], $fields );        
            
            $path = SF_BASE_DIR.'/data/earchive/'.$l_data['folder'].'/'.$data['folder'];
            
            $fields = array('file');
            
            // get attachment files and delete them
            foreach($_POST['aid'] as $aid)
            {
                $file = $this->B->earchive->get_attach( $aid, $fields );
                @unlink($path.'/'.$file['file']); 
                $this->B->earchive->delete_attach_db_entry( $aid );
            }
        }
        
        // update message data
        if(FALSE !== $this->B->earchive->update_message((int)$_REQUEST['mid'], $this->B->tmp_data))
        {
            @header('Location: index.php?m=EARCHIVE&mf=show_mess&lid='.(int)$_REQUEST["lid"].'&pageID='.(int)$_REQUEST["pageID"]);
            exit;
        }
        else
        {
            
            $this->B->form_error = 'Error during update. Try again!';
        }
    }
}
else
{
    // get list data
    $this->B->tmp_fields = array('mid','lid','subject','sender','body','folder');
    $this->B->tpl_data = $this->B->earchive->get_message( (int)$_REQUEST['mid'], $this->B->tmp_fields );
    unset($this->B->tmp_fields);

    // get list data
    $this->B->tmp_fields = array('aid','file','size','type');    
    $this->B->tpl_attach = $this->B->earchive->get_message_attach( (int)$_REQUEST['mid'], $this->B->tmp_fields );
    unset($this->B->tmp_fields);
}

// if error restore the form fields values
if(!empty($this->B->form_error))
{
    // if empty assign form field with old values
    $this->B->tpl_data['subject'] = commonUtil::stripSlashes($_POST['subject']);
    $this->B->tpl_data['body']    = commonUtil::stripSlashes($_POST['body']);
    $this->B->tpl_data['mid']     = $_POST['mid'];
    $this->B->tpl_data['lid']     = $_POST['lid'];
    $this->B->tpl_data['pageID']  = $_POST['pageID'];
}

?>
