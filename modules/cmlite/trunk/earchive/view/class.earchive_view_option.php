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
 * earchive_view_option class 
 *
 */
 
class earchive_view_option
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
    function earchive_view_option()
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
     * Control the options template of earchive
     *
     * @param array $data
     */
    function perform( $data )
    {    
        // Rebuild the words index of all messages 
        if(isset($_POST['earchive_rebuild_index']))
        {
            $this->B->M( MOD_EARCHIVE, 'rebuild_words_index' );
        }
        // fetch messages from email accounts 
        if(isset($_POST['earchive_fetch_emails']))
        {
            $this->B->M( MOD_EARCHIVE, 'fetch_emails', array('status' => 'status>1') );
        }   
        
        return TRUE;
    } 
}

?>
