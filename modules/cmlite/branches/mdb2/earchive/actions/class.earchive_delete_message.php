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
 * earchive_delete_message class 
 *
 */
 
class earchive_delete_message
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
    function earchive_delete_message()
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
        $this->B->M( MOD_EARCHIVE, 
                     'get_message', 
                     array( 'mid'    => $data['mid'], 
                            'var'    => 'm_data',
                            'fields' => array('lid','folder')));   

        // get list folder
        $this->B->M( MOD_EARCHIVE, 
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
        
        $this->B->db->query($sql);    
        
        // delete list messages
        $sql = "
            DELETE FROM 
                {$this->B->sys['db']['table_prefix']}earchive_attach
            WHERE
                mid={$data['mid']}";
        
        $this->B->db->query($sql); 
        
        // delete list messages word indexes
        $sql = "
            DELETE FROM 
                {$this->B->sys['db']['table_prefix']}earchive_words_crc32
            WHERE
                mid={$data['mid']}";
        
        $this->B->db->query($sql);     
        
        return TRUE;
    }    
}

?>
