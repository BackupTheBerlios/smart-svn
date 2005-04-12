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
 * action_earchive_add_message class 
 *
 */
 
class action_earchive_add_message extends action
{
    /**
     * add message data
     *
     * @param array $data
     */
    function perform( & $data )
    { 
        foreach ($data as $key => $value)
        {
            if(empty($value))
            {
                $data[$value] = '';
            }
            if($key != 'lid')
            {
                $data[$key] = $this->B->db->quoteSmart($data[$value]);
            }
        }
    
        $sql = "
            INSERT INTO 
                ".$this->B->sys['db']['table_prefix']."earchive_messages
                (lid,message_id,root_id,parent_id,enc_type,sender,subject,mdate,body,folder,header)
            VALUES
                (".$data["lid"].",
                 ".$data["message_id"].",
                 ".$data["root_id"].",
                 ".$data["parent_id"].",
                 ".$data["enc_type"].",
                 ".$data["sender"].",
                 ".$data["subject"].",
                 ".$data["mdate"].",
                 ".$data["body"].",
                 ".$data["folder"].",
                 ".$data["header"].")";

        $result = $this->B->db->query($sql);
        
        if (MDB2::isError($result)) 
        {
            trigger_error($result->getMessage()."\n\nINFO: ".$result->code()."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            return FALSE;
        }   
        
        $sql = 'SELECT LAST_INSERT_ID() AS mid';
        
        $result = $this->B->db->query($sql);
        
        $res = $result->fetchRow( MDB2_FETCHMODE_ASSOC );
       
        return $res['mid'];
    }  
}

?>
