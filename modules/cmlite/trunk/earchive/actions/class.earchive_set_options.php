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
        // Rebuild the words index of all messages 
        if(isset($_POST['earchive_rebuild_index']))
        {
            $this->B->M( MOD_EARCHIVE, 'rebuild_words_index' );
        }
        // fetch messages from email accounts 
        if(isset($_POST['earchive_fetch_emails']))
        {
            // Include cache and create instance
            if(!is_object($this->B->cache))
            {
                include_once(SF_BASE_DIR . 'modules/common/PEAR/Cache.php');            
                $this->B->cache = new Cache('db', array('dsn'         => $this->B->dsn,
                                                        'cache_table' => $this->B->sys['db']['table_prefix'].'cache'));
            }
            // Delete all cache data
            $this->B->cache->flush();
            
            $this->B->M( MOD_EARCHIVE, 'fetch_emails', array('status' => 'status>1') );
        }          
    } 
}

?>
