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
 * earchive_update_message class 
 *
 */
 
class earchive_update_message
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
    function earchive_update_message()
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
     * update message data
     *
     * @param array $data
     */
    function perform( & $data )
    { 
        if(empty($data['mid']))
        {
            trigger_error("'mid' is empty!\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            return FALSE;
        }
        if(count($_POST['aid']) > 0)
        {
            $this->_delete_attach( $data );  
        }
        
        return $this->_update_message( $data );     
    }  

    /**
     * update message data
     *
     * @param array $data
     */
    function _update_message( & $data )
    {
        $set = '';
        $comma = '';
        
        foreach($data['fields'] as $key => $val)
        {
            $set .= $comma.$key.'='.$val;
            $comma = ',';
        }
        
        $sql = '
            UPDATE 
                '.$this->B->sys['db']['table_prefix'].'earchive_messages
            SET
                '.$set.'
            WHERE
                mid='.$data['mid'];
        
        $result = $this->B->db->query($sql);
        
        if (DB::isError($result)) 
        {
            trigger_error($result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            return FALSE;
        }     
        
        return TRUE;
    }

    /**
     * delete message attachment
     *
     * @param array $data
     */    
    function _delete_attach( & $data )
    {
        // get data folder and lid of message 
        $sql = "
            SELECT
                lid,
                folder
            FROM
                {$this->B->sys['db']['table_prefix']}earchive_messages
            WHERE
                mid={$mid}";

        $m_data = $this->B->db->getRow($sql, array(), DB_FETCHMODE_ASSOC);
        
        // get data folder of message
        $sql = "
            SELECT
                folder
            FROM
                {$this->B->sys['db']['table_prefix']}earchive_lists
            WHERE
                lid={$m_data['lid']}";
        
        $l_data = $this->B->db->getRow($sql, array(), DB_FETCHMODE_ASSOC);

        // full path of message data folder
        $path = SF_BASE_DIR.'data/earchive/'.$l_data['folder'].'/'.$m_data['folder'];
        
        $fields = array('file');
            
        // get attachment files and delete them
        foreach($data['aid'] as $aid)
        {
            $sql = "
                SELECT
                    file
                FROM
                    {$this->B->sys['db']['table_prefix']}earchive_attach 
                WHERE 
                    aid={$aid}";
            $result = $this->B->db->query($sql);   
            
            $row = $result->FetchRow( DB_FETCHMODE_ASSOC );
            
            @unlink($path.'/'.$row['file']); 
        
            $sql = "
                DELETE FROM 
                    {$this->B->sys['db']['table_prefix']}earchive_attach
                WHERE
                    aid={$aid}";  
        
            $result = $this->B->db->query($sql);
        }        
    }
}

?>
