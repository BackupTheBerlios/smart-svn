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
 * earchive_delete_list class 
 *
 */
 
class earchive_delete_list
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
    function earchive_delete_list()
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
     * Delete email list data and attachement folder
     *
     * @param array $data
     */
    function perform( $data )
    { 
        if(empty($data['lid']))
        {
            return FALSE;
        }
        
        // get list attachement folder
        $sql = "
            SELECT
                folder
            FROM
                {$this->B->sys['db']['table_prefix']}earchive_lists 
            WHERE
                lid={$data['lid']}";
        
        $result = $this->B->db->query($sql);     

        $row = &$result->FetchRow( DB_FETCHMODE_ASSOC );
        
        $folder = $row['folder'];
        
        $path = SF_BASE_DIR.'data/earchive/'.$folder;
        
        if(!empty($folder) && @is_dir($path))
        {   
            // delete attachements folder for this list
            commonUtil::delete_dir_tree( $path );
        }
        
        // delete list
        $sql = "
            DELETE FROM 
                {$this->B->sys['db']['table_prefix']}earchive_lists
            WHERE
                lid={$data['lid']}";
        
        $this->B->db->query($sql);
        
        // delete list messages
        $sql = "
            DELETE FROM 
                {$this->B->sys['db']['table_prefix']}earchive_messages
            WHERE
                lid={$data['lid']}";
        
        $this->B->db->query($sql);    
        
        // delete list messages
        $sql = "
            DELETE FROM 
                {$this->B->sys['db']['table_prefix']}earchive_attach
            WHERE
                lid={$data['lid']}";
        
        $this->B->db->query($sql); 
        
        // delete list messages word indexes
        $sql = "
            DELETE FROM 
                {$this->B->sys['db']['table_prefix']}earchive_words_crc32
            WHERE
                lid={$data['lid']}";
        
        $this->B->db->query($sql);    

        // Include cache and create instance
        if(!is_object($this->B->cache))
        {
            include_once(SF_BASE_DIR . 'modules/common/PEAR/Cache.php');            
            $this->B->cache = new Cache('db', array('dsn'         => $this->B->dsn,
                                                    'cache_table' => $this->B->sys['db']['table_prefix'].'cache'));
        }
        // Delete all cache data
        $this->B->cache->flush();
        
        return TRUE;
    }    
}

?>
