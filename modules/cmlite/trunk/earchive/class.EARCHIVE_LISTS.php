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
 * EARCHIVE_LISTS class 
 *
 */
 
class EARCHIVE_LISTS
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
    function EARCHIVE_LISTS()
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
     * Get all available email lists
     *
     * @param array $data
     */
    function perform( $data )
    {    
        $comma   = '';
        $_fields = '';
        foreach ($data['fields'] as $f)
        {
            $_fields .= $comma.$f;
            $comma = ',';
        }
        
        $sql = "
            SELECT
                {$_fields}
            FROM
                {$this->B->sys['db']['table_prefix']}earchive_lists
            WHERE
                status>1
            ORDER BY
                name ASC";
        
        $result = $this->B->db->query($sql);

        if (DB::isError($result)) 
        {
            trigger_error($result->getMessage()."\n\nINFO: ".$result->userinfo."\n\nFILE: ".__FILE__."\nLINE: ".__LINE__, E_USER_ERROR);
        }
        
        // get var name to store the result
        $this->B->$data['var'] = array();
        $_result               = & $this->B->$data['var'];
        
        if(is_object($result))
        {
            while($row = &$result->FetchRow( DB_FETCHMODE_ASSOC ))
            {
                $tmp = array();
                foreach($data['fields'] as $f)
                {
                    $tmp[$f] = stripslashes($row[$f]);
                }
                $_result[] = $tmp;
            }
        }
    }
}

?>
