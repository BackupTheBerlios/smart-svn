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
 * action_earchive_delete_attach class 
 *
 */
 
class action_earchive_delete_attach extends action
{
    /**
     * delete message attachment
     *
     * @param array $data
     */
    function perform( & $data )
    { 
        if(empty($data['aid']))
        {
            trigger_error("'aid' is empty!\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            return FALSE;
        }

        // get message attach folder and lid of message 
        $sql = "
            SELECT
                lid,
                folder
            FROM
                {$this->B->sys['db']['table_prefix']}earchive_messages
            WHERE
                mid={$data['mid']}";

        $result = $this->B->db->query($sql);
        $m_data = $result->fetchRow(MDB2_FETCHMODE_ASSOC);
        
        // get list folder of message
        $sql = "
            SELECT
                folder
            FROM
                {$this->B->sys['db']['table_prefix']}earchive_lists
            WHERE
                lid={$m_data['lid']}";
        
        $result = $this->B->db->query($sql);
        $l_data = $result->fetchRow(MDB2_FETCHMODE_ASSOC);

        // full path of message data folder
        $path = SF_BASE_DIR.'data/earchive/'.$l_data['folder'].'/'.$m_data['folder'];
        
        $fields = array('file');
        
        // get attach file
        $sql = "
                SELECT
                    file
                FROM
                    {$this->B->sys['db']['table_prefix']}earchive_attach 
                WHERE 
                    aid={$data['aid']}";
        
        $result = $this->B->db->query($sql);
        $row    = $result->fetchRow(MDB2_FETCHMODE_ASSOC);
        
        // delete attachment file
        if(!@unlink($path.'/'.$row['file']))
        {
            trigger_error("Can't unlink file: ".$path.'/'.$row['file']."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);        
        }
        
        // delete attach db entry
        $sql = "
                DELETE FROM 
                    {$this->B->sys['db']['table_prefix']}earchive_attach
                WHERE
                    aid={$data['aid']}";  
        
        $result = $this->B->db->query($sql);
        
        if (MDB2::isError($result)) 
        {
            trigger_error($result->getMessage()."\n\nINFO: ".$result->code()."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
        }        
    
        return TRUE;
    }  
}

?>
