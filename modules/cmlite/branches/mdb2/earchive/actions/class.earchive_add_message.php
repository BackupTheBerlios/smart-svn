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
 * earchive_add_message class 
 *
 */
 
class earchive_add_message
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
    function earchive_add_message()
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
     * add message data
     *
     * @param array $data
     */
    function perform( & $data )
    { 
        $mid = $this->B->db->nextId($this->B->sys['db']['table_prefix'].'earchive_messages');

        if (MDB2::isError($mid)) 
        {
            trigger_error($result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            return FALSE;
        }
        
        $sql = '
            INSERT INTO 
                '.$this->B->sys['db']['table_prefix'].'earchive_messages
                (mid,lid,sender,subject,mdate,body,folder)
            VALUES
                ('.$mid.',
                 '.$data['lid'].',
                 "'.$data['sender'].'",
                 "'.$data['subject'].'",
                 "'.$data['mdate'].'",
                 "'.$data['body'].'",
                 "'.$data['folder'].'")';

        $result = $this->B->db->query($sql);
        
        if (MDB2::isError($result)) 
        {
            trigger_error($result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            return FALSE;
        }   
        
        return $mid;
    }  
}

?>
