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
 * earchive_set_options class 
 *
 */
 
class earchive_set_options
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
    function earchive_set_options()
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
     * Set options for this module
     *
     * @param array $data
     */
    function perform( $data )
    {    
        // set the passID to external fetching emails 
        if(isset($_POST['update_earchive_options_passID']))
        {
            if(!preg_match("/[^a-zA-Z0-9]+/", $_POST['passID']))
            {
                $this->B->sys['module']['earchive']['passID'] = $_POST['passID'];
                $this->B->_modified = TRUE;
            }
        }    
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
    } 
}

?>
