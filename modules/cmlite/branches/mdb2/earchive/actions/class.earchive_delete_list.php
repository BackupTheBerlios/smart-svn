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

        if (MDB2::isError($result)) 
        {
            trigger_error($result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            return FALSE;
        }

        $row = &$result->fetchRow( MDB2_FETCHMODE_ASSOC );
        
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
        
        $result = $this->B->db->query($sql);

        if (MDB2::isError($result)) 
        {
            trigger_error($result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            return FALSE;
        }
        
        // delete list messages
        $sql = "
            DELETE FROM 
                {$this->B->sys['db']['table_prefix']}earchive_messages
            WHERE
                lid={$data['lid']}";
        
        $result = $this->B->db->query($sql);

        if (MDB2::isError($result)) 
        {
            trigger_error($result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            return FALSE;
        }  
        
        // delete list messages
        $sql = "
            DELETE FROM 
                {$this->B->sys['db']['table_prefix']}earchive_attach
            WHERE
                lid={$data['lid']}";
        
        $result = $this->B->db->query($sql);

        if (MDB2::isError($result)) 
        {
            trigger_error($result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            return FALSE;
        }
        
        // delete list messages word indexes
        $sql = "
            DELETE FROM 
                {$this->B->sys['db']['table_prefix']}earchive_words_crc32
            WHERE
                lid={$data['lid']}";
        
        $result = $this->B->db->query($sql);

        if (MDB2::isError($result)) 
        {
            trigger_error($result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            return FALSE;
        }    
        
        return TRUE;
    }    
}

?>
