<?php
// ----------------------------------------------------------------------
// Smart PHP Framework
// Copyright (c) 2004
// by Armand Turpel < smart@open-publisher.net >
// http://smart.open-publisher.net/
// ----------------------------------------------------------------------
// LICENSE GPL
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------

/**
 * view_earchive_editmessage class
 *
 */
 
class view_earchive_editmessage extends view
{
     /**
     * Default template for this view
     * @var string $template
     */
    var $template = 'earchive_editmessage';
    
     /**
     * Default template folder for this view
     * @var string $template_folder
     */    
    var $template_folder = 'modules/earchive/templates/';
 
    /**
     * Execute the view of the template "tpl.earchive_editmessage.php"
     *
     * @return bool true on success else false
     */
    function perform()
    {
        // init 
        $this->B->form_error = FALSE;

        // Modify list data
        if(isset($_POST['updatemessage']))
        {  
            // check if some fields are empty
            if( empty($_POST['subject']) )
            {        
                $this->B->form_error = 'You have to fill out subject field!';
                $this->_reset_old_fields_data();
                return TRUE;                
            }
            else
            {
                // delete attachments ?
                if(is_array($_POST['aid']) && (count($_POST['aid'] > 0)))
                {
                    foreach ($_POST['aid'] as $aid)
                    {
                        M( MOD_EARCHIVE, 
                           'delete_attach', 
                           array( 'mid' => (int)$_REQUEST['mid'], 
                                  'aid' => (int)$aid));
                    }
                }
        
                // update message data
                $tmp_data = array('subject'     => $this->B->db->quoteSmart(commonUtil::stripSlashes($_POST['subject'])),
                                  'body'        => $this->B->db->quoteSmart(commonUtil::stripSlashes($_POST['body'])));

                if(TRUE === M( MOD_EARCHIVE, 
                               'update_message', 
                               array( 'mid'    => (int)$_REQUEST['mid'], 
                                      'fields' => $tmp_data)))
                {        
                    @header('Location: '.SF_CONTROLLER.'?admin=1&m=earchive&sec=showmessages&lid='.(int)$_REQUEST["lid"].'&pageID='.(int)$_REQUEST["pageID"]);
                    exit;
                }
                else
                {
                    $this->B->form_error = 'Error during update. Try again!';
                    $this->_reset_old_fields_data();
                    return TRUE;                    
                }
            }
        }
        else
        {
            // assign template vars with message data
            M( MOD_EARCHIVE, 
               'get_message', 
               array( 'mid'    => (int)$_REQUEST['mid'], 
                      'var'    => 'tpl_data',
                      'fields' => array('mid','lid','subject','sender','body','folder')));
  
            // assign template vars with message attach data
            M( MOD_EARCHIVE, 
               'get_attachments', 
               array( 'mid'    => (int)$_REQUEST['mid'], 
                      'var'    => 'tpl_attach',
                      'fields' => array('aid','file','size','type')));
        }

        return TRUE;
    } 
    
    
    /**
     * reset the form fields with old user data
     *
     * @access privat
     */       
    function _reset_old_fields_data()
    {
        // if empty assign form field with old values
        $this->B->tpl_data['subject'] = commonUtil::stripSlashes($_POST['subject']);
        $this->B->tpl_data['body']    = commonUtil::stripSlashes($_POST['body']);
        $this->B->tpl_data['mid']     = $_POST['mid'];
        $this->B->tpl_data['lid']     = $_POST['lid'];
        $this->B->tpl_data['pageID']  = $_POST['pageID'];         
    }    
}

?>
