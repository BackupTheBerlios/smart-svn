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
 * action_earchive_delete_list class 
 *
 */
 
class action_earchive_delete_list extends action
{
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

        // Delete message words index of this list
        M( MOD_EARCHIVE, 
           'word_indexer', 
           array( 'delete_words' => TRUE,
                  'lid'          => (int)$data['lid']));      

        // Delete cache data
        M( MOD_COMMON, 'cache_delete', array('group' => 'earchive'));
        
        return TRUE;
    }    
}

?>
