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
 * earchive_view_showmessages class of the template "showmessages.tpl.php"
 *
 */
 
class earchive_view_showmessages
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
    function earchive_view_showmessages()
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
     * Execute the view of the template "showmessages.tpl.php"
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
                    $this->B->M( MOD_EARCHIVE, 
                                 'delete_message', 
                                 array( 'mid'    => (int) $mid));
                }
            }
        }

        // assign template vars with list data
        $this->B->M( MOD_EARCHIVE, 
                     'get_list', 
                     array( 'lid'    => (int)$_REQUEST['lid'], 
                            'var'    => 'tpl_list',
                            'fields' => array('lid','name')));

        // assign template vars with message data
        $this->B->M( MOD_EARCHIVE, 
                     'get_messages', 
                     array( 'lid'    => (int)$_REQUEST['lid'], 
                            'var'    => 'tpl_messages',
                            'pager'  => array('var' => 'tpl_messages_pager', 'limit' => 20, 'delta' => 3),
                            'fields' => array('mid', 'lid', 'subject', 'sender', 'mdate')));

        return TRUE;
    }    
}

?>
