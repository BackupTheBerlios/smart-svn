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
 * earchive_view_editmessage class of the template "editmessage.tpl.php"
 *
 */
 
class earchive_view_editmessage
{
    /**
     * Global system instance
     * @var object $B
     */
    var $B;
    
    /**
     * constructor
     *
     */
    function earchive_view_editmessage()
    {
        $this->__construct();
    }

    /**
     * constructor php5
     *
     */
    function __construct()
    {
        $this->B = & $GLOBALS['B'];
    }
    
    /**
     * Execute the view of the template "editmessage.tpl.php"
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
                        $this->B->M( MOD_EARCHIVE, 
                                     'delete_attach', 
                                     array( 'mid'    => (int)$_REQUEST['mid'], 
                                            'aid'    => (int)$aid));
                    }
                }
        
                // update message data
                $tmp_data = array('subject'     => $this->B->db->quote(commonUtil::stripSlashes($_POST['subject'])),
                                  'body'        => $this->B->db->quote(commonUtil::stripSlashes($_POST['body'])));

                if(TRUE === $this->B->M( MOD_EARCHIVE, 
                                         'update_message', 
                                         array( 'mid'    => (int)$_REQUEST['mid'], 
                                                'fields' => $tmp_data)))
                {        
                    @header('Location: index.php?admin=1&m=earchive&sec=showmessages&lid='.(int)$_REQUEST["lid"].'&pageID='.(int)$_REQUEST["pageID"]);
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
            $this->B->M( MOD_EARCHIVE, 
                         'get_message', 
                         array( 'mid'    => (int)$_REQUEST['mid'], 
                                'var'    => 'tpl_data',
                                'fields' => array('mid','lid','subject','sender','body','folder')));
  
            // assign template vars with message attach data
            $this->B->M( MOD_EARCHIVE, 
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
