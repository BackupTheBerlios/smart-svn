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
 * earchive_add_attach class 
 *
 */
 
class earchive_add_attach
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
    function earchive_add_attach()
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
     * store attachment data
     *
     * @param array $data
     */
    function perform( & $data )
    { 
        if($f = @fopen($data['path_file'], 'wb'))
        {
            if(@fwrite($f, $data['content'], $data['size']))
            {
                @fclose($f);
                @chmod($data['path_file'], SF_FILE_MODE);
                return $this->_add_attach( $data['mid'], $data['lid'], $data );
            }
            else
            {
                trigger_error("Could not write file: ".$data['path_file']."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);            
                return FALSE;
            }
        }
        
        trigger_error("Could not open file to write: ".$data['path_file']."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);            
        return FALSE;     
    }  

    /**
     * add email message attachment db data
     *
     * @param int $mid Message ID
     * @param int $lid List ID
     * @param array $data associative array of list data
     * @return bool true or false
     */     
    function _add_attach( $mid, $lid, &$data )
    {
        $aid = $this->B->db->nextId($this->B->sys['db']['table_prefix'].'earchive_seq_add_attach');

        if (DB::isError($aid)) 
        {
            trigger_error($result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
        }
        
        $sql = '
            INSERT INTO 
                '.$this->B->sys['db']['table_prefix'].'earchive_attach
                (aid,mid,lid,file,size,type)
            VALUES
                ('.$aid.',
                 '.$mid.',
                 '.$lid.',
                 '.$data['file'].',
                 '.$data['size'].',
                 '.$data['type'].')';

        $result = $this->B->db->query($sql);
        
        if (DB::isError($result)) 
        {
            trigger_error($result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            return FALSE;
        }   
        
        return TRUE;
    } 
}

?>
