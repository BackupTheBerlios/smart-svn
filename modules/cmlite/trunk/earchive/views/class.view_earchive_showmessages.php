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
 * view_earchive_showmessages class
 *
 */
 
class view_earchive_showmessages extends view
{
     /**
     * Default template for this view
     * @var string $template
     */
    var $template = 'earchive_showmessages';
    
     /**
     * Default template folder for this view
     * @var string $template_folder
     */    
    var $template_folder = 'modules/earchive/templates/';
    
    /**
     * Execute the view of the template "tpl.earchive_showmessages.php"
     *
     * @return bool true on success else false
     */
    function perform()
    {      
        // Delete messages on demande
        if(isset($_POST['deletemess']))
        {
            if(count($_POST['mid']) > 0)
            {
                foreach($_POST['mid'] as $mid)
                {
                    // assign template vars with list data
                    M( MOD_EARCHIVE, 
                       'delete_message', 
                       array( 'mid'    => (int) $mid));
                }
            }
        }

        // assign template vars with list data
        M( MOD_EARCHIVE, 
           'get_list', 
           array( 'lid'    => (int)$_REQUEST['lid'], 
                  'var'    => 'tpl_list',
                  'fields' => array('lid','name')));

        // assign template vars with message data
        M( MOD_EARCHIVE, 
           'get_messages', 
           array( 'lid'    => (int)$_REQUEST['lid'], 
                  'var'    => 'tpl_messages',
                  'order'  => 'mdate DESC',
                  'pager'  => array('var' => 'tpl_messages_pager', 'limit' => 20, 'delta' => 3),
                  'fields' => array('mid', 'lid', 'subject', 'sender', 'mdate')));

        return TRUE;
    }    
}

?>
