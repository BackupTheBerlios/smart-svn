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
 * earchive_update_list class 
 *
 */
 
class earchive_update_list
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
    function earchive_update_list()
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
    function perform( & $data )
    { 
        if(empty($data['lid']))
        {
            trigger_error("'lid' is empty!\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
            return FALSE;
        }
        
        $set = '';
        $comma = '';
        
        foreach($data['data'] as $key => $val)
        {
            $set .= $comma.$key.'='.$val;
            $comma = ',';
        }
        
        $sql = '
            UPDATE 
                '.$this->B->sys['db']['table_prefix'].'earchive_lists
            SET
                '.$set.'
            WHERE
                lid='.$data['lid'];
        
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
