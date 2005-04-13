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
 * action_earchive_delete_message class 
 *
 */
 
class action_earchive_delete_message extends action
{
    /**
     * Delete email list message data and attachement folder
     *
     * @param array $data
     */
    function perform( $data )
    { 
        if(empty($data['mid']))
        {
            return FALSE;
        }
        
        // get message folder and lid
        M( MOD_EARCHIVE, 
           'get_message', 
           array( 'mid'    => $data['mid'], 
                  'var'    => 'm_data',
                  'fields' => array('lid','folder')));   

        // get list folder
        M( MOD_EARCHIVE, 
           'get_list', 
           array( 'lid'    => $this->B->m_data['lid'], 
                  'var'    => 'l_data',
                  'fields' => array('lid','folder')));
        
        // build whole path to message folder
        $path = SF_BASE_DIR.'/data/earchive/'.$this->B->l_data['folder'].'/'.$this->B->m_data['folder'];
        
        if(!empty($this->B->m_data['folder']) && @is_dir($path))
        {   
            // delete attachements folder for this message
            commonUtil::delete_dir_tree( $path );
        }
        
        // delete list messages
        $sql = "
            DELETE FROM 
                {$this->B->sys['db']['table_prefix']}earchive_messages
            WHERE
                mid={$data['mid']}";
        
        $result = $this->B->db->query($sql);    
        
        if (DB::isError($result)) 
        {
            trigger_error($result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
        }         
        
        // delete list messages
        $sql = "
            DELETE FROM 
                {$this->B->sys['db']['table_prefix']}earchive_attach
            WHERE
                mid={$data['mid']}";
        
        $result = $this->B->db->query($sql); 
        
        if (DB::isError($result)) 
        {
            trigger_error($result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
        }         
        
        // Delete message words from the search index
        M( MOD_EARCHIVE, 
           'word_indexer', 
           array( 'delete_words' => TRUE,
                  'mid'          => (int)$data['mid']));   

        // Delete cache data
        M( MOD_COMMON, 'cache_delete', array('group' => 'earchive'));
        
        return TRUE;
    }    
}

?>
