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
                    $this->B->earchive->delete_message( $mid );
                }
            }
        }
        
        // get list name and id
        $fields = array('lid','name');
        $this->B->tpl_list = $this->B->earchive->get_list( (int)$_GET['lid'], $fields );

        // get list messages
        $fields = array('mid', 'lid', 'subject', 'sender', 'mdate');
        $this->B->earchive->get_messages( 'tpl_messages', (int)$_GET['lid'], $fields, 'tpl_messages_pager');
        
        return TRUE;
    }    
}

?>
