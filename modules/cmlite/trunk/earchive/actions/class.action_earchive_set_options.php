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
 * action_earchive_set_options class 
 *
 */
 
class action_earchive_set_options extends action
{
    /**
     * Set options for this module
     *
     * @param array $data
     */
    function perform( $data )
    {     
        // Rebuild the words index of all messages 
        if(isset($_POST['update_earchive_options_wordindex']))
        {
            M( MOD_EARCHIVE, 'rebuild_words_index' );
        }
        // fetch messages from email accounts 
        if(isset($_POST['update_earchive_options_fetchemails']))
        {
            M( MOD_EARCHIVE, 'fetch_emails', array('status' => 'status>1') );
            
            // Delete cache data
            M( MOD_COMMON, 'cache_delete', array('group' => 'earchive'));            
        }   
        // enable to fetch raw headers from emails 
        if (isset($_POST['update_earchive_options_rawheaders']))
        {
            $this->B->sys['module']['earchive']['get_header'] = (bool)$_POST['earchive_fetch_headers'];
            $this->B->_modified = TRUE;
        } 
    } 
}

?>
